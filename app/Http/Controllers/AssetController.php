<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class AssetController extends Controller
{
    public function css(): Response
    {
        $file = $this->findAsset('*.css');

        return response(file_get_contents($file), 200, [
            'Content-Type' => 'text/css',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function js(): Response
    {
        $file = $this->findAsset('*.js');

        return response(file_get_contents($file), 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function findAsset(string $pattern): string
    {
        $dir = public_path('static/assets');
        $files = glob($dir . '/' . $pattern);

        if (empty($files)) {
            abort(404, 'Asset not found');
        }

        return $files[0];
    }
}
