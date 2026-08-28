<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * GD-based image utility for the storefront.
 *
 * Provides:
 *  - dimension reading
 *  - center-crop-to-cover resizing (so uploads always fit their slot perfectly)
 *  - format-aware save
 *  - one-call upload processing (validate + auto-adjust + store)
 *
 * No external Image package is required (GD ships with PHP).
 */
class ImageUtility
{
    /**
     * Process an uploaded file so it fits the target slot:
     *  - reads dimensions
     *  - center-crops to $tw x $th when it doesn't already match
     *  - stores the (possibly adjusted) image on the public disk
     *
     * SVGs and non-GD formats are stored as-is. If the upload already has the
     * exact target dimensions it is stored without re-encoding.
     *
     * @return string|null  Stored relative path (e.g. "banners/xyz.jpg") or null.
     */
    public function processUpload(UploadedFile $file, int $tw, int $th, string $folder): ?string
    {
        $src = $file->getRealPath();
        if (! $src || ! is_file($src)) {
            return null;
        }

        $ext = strtolower((string) $file->getClientOriginalExtension()) ?: 'jpg';
        $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true) ? $ext : 'jpg';

        // GD cannot rasterize SVG — keep it as-is.
        if ($ext === 'svg') {
            return $this->storeProcessed($src, $folder, $ext);
        }

        $dim = @getimagesize($src);
        if (! $dim || ($dim[0] ?? 0) <= 0 || ($dim[1] ?? 0) <= 0) {
            return $this->storeProcessed($src, $folder, $ext);
        }

        // Already the perfect size — no re-encode needed.
        if ($dim[0] === $tw && $dim[1] === $th) {
            return $this->storeProcessed($src, $folder, $ext);
        }

        // GD not available — keep the original rather than crashing.
        if (! self::gdAvailable()) {
            return $this->storeProcessed($src, $folder, $ext);
        }

        $cropped = $this->resizeCropCover($src, $tw, $th);
        if ($cropped) {
            return $this->storeProcessed($cropped, $folder, $this->extensionForFormat($dim[2] ?? null) ?? $ext);
        }

        return $this->storeProcessed($src, $folder, $ext);
    }
    /**
     * Read the [width, height] of the given image file, or null on failure.
     *
     * @param  string  $path  Absolute or local filesystem path.
     */
    public function dimensions(string $path): ?array
    {
        $info = @getimagesize($path);

        return $info ? [$info[0], $info[1]] : null;
    }

    /**
     * Center-crop to the exact target dimensions using "cover" behaviour
     * (no distortion: scale to fill, then crop the overflow).
     *
     * If the source is an SVG (GD cannot rasterize it) it returns null
     * so the caller can fall back to storing the original.
     *
     * @param  string  $src     Absolute path to the source image.
     * @param  int     $tw      Target width in pixels.
     * @param  int     $th      Target height in pixels.
     * @param  string  $dest    Absolute path to write to. If null, a temp file is used.
     * @return string|null      Absolute path to the written image, or null on failure.
     */
    public function resizeCropCover(string $src, int $tw, int $th, ?string $dest = null): ?string
    {
        $dim = @getimagesize($src);
        if (! $dim) {
            return null;
        }

        $format = $dim[2] ?? 0;
        // GD cannot rasterize SVG — leave it untouched for the caller to store as-is.
        if (strtolower((string) pathinfo($src, PATHINFO_EXTENSION)) === 'svg') {
            return null;
        }

        $srcW = $dim[0];
        $srcH = $dim[1];

        if ($srcW <= 0 || $srcH <= 0 || $tw <= 0 || $th <= 0) {
            return null;
        }

        $image = $this->open($src, $format);
        if (! $image) {
            return null;
        }

        // Compute cover scale: maximise so both dimensions are covered.
        $scale = max($tw / $srcW, $th / $srcH);
        $scaleW = (int) round($srcW * $scale);
        $scaleH = (int) round($srcH * $scale);

        $resized = imagecreatetruecolor($scaleW, $scaleH);
        if (! $resized) {
            imagedestroy($image);

            return null;
        }

        $this->preserveAlpha($resized, $format);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $scaleW, $scaleH, $srcW, $srcH);

        // Crop centered overflow.
        $cropX = (int) floor(($scaleW - $tw) / 2);
        $cropY = (int) floor(($scaleH - $th) / 2);
        $cropX = max(0, $cropX);
        $cropY = max(0, $cropY);

        $out = imagecreatetruecolor($tw, $th);
        if (! $out) {
            imagedestroy($resized);
            imagedestroy($image);

            return null;
        }

        $this->preserveAlpha($out, $format);
        imagecopy($out, $resized, 0, 0, $cropX, $cropY, $tw, $th);

        imagedestroy($resized);

        if ($dest === null) {
            $dest = tempnam(sys_get_temp_dir(), 'img').'.png';
        }

        $ok = $this->save($out, $dest, $format);
        imagedestroy($out);
        imagedestroy($image);

        return $ok ? $dest : null;
    }

    /**
     * Move a temp image into the chosen public disk folder with a correct,
     * content-derived extension.
     *
     * When $preferredExt is omitted the real format is detected from the file
     * content so the stored name never mis-matches the bytes (e.g. a JPEG
     * written through a .png-named temp file is stored as .jpg).
     *
     * @return string|null  Stored relative path (e.g. "banners/xyz.jpg") or null.
     */
    public function storeProcessed(string $srcPath, string $folder, ?string $preferredExt = null): ?string
    {
        if (! $srcPath || ! is_file($srcPath)) {
            return null;
        }

        $ext = strtolower((string) ($preferredExt ?: pathinfo($srcPath, PATHINFO_EXTENSION) ?: ''));
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
            $dim = @getimagesize($srcPath);
            $ext = $this->extensionForFormat($dim[2] ?? null) ?? 'jpg';
        }
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }

        $name = uniqid('', true).'.'.$ext;

        $stored = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($folder, $srcPath, $name);
        @unlink($srcPath);

        return $stored;
    }

    protected function extensionForFormat(?int $format): ?string
    {
        return match ($format) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_WEBP => 'webp',
            default        => null,
        };
    }

    protected static function gdAvailable(): bool
    {
        return function_exists('imagecreatetruecolor')
            || function_exists('imagecreatefromstring');
    }

    protected function open(string $src, int $format)
    {
        return match ($format) {
            IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP => @imagecreatefromstring(@file_get_contents($src)),
            default => null,
        };
    }

    protected function preserveAlpha($img, int $format): void
    {
        if (function_exists('imagealphablending')) {
            imagealphablending($img, false);
        }
        if (function_exists('imagesavealpha')) {
            imagesavealpha($img, true);
        }
    }

    protected function save($img, string $dest, int $format): bool
    {
        $dest = rtrim($dest, '\\/');

        return match ($format) {
            IMAGETYPE_JPEG => (bool) @imagejpeg($img, $dest, 88),
            IMAGETYPE_WEBP => (bool) @imagewebp($img, $dest, 88),
            default        => (bool) @imagepng($img, $dest, 6),
        };
    }
}
