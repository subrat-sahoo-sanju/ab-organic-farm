# Product Image Prompts — AB Organic Farm

Genuine-style (photoreal) product images. Generate each, then upload through
**Admin → Products → edit product → Images (add multiple)** so auto-resize to
1000×1000 happens automatically — or drop the files directly into the folder
under `storage/app/public/products/<product-id>/`.

---

## Shared brand style block (add to every prompt)
> Photorealistic e-commerce product photography, studio softbox lighting, clean
> off-white (#F7F4EC) seamless background, gentle natural shadow, subtle golden
> amber/green brand accents, high detail, sharp focus, premium organic product
> pack shot, square 1:1 composition, no people, no text overlay, no watermark.
> Golden-green minimalist AB Organic Farm label on the pack.

---

## 1. Organic A2 Gir Cow Desi Ghee (Jar) — id 143
A wide-mouth clear glass jar of golden clarified butter (desi ghee), thick pure
golden ghee, half-open metal lid showing glistening grain, small cream-coloured
label band "A2 GIR COW DESI GHEE", sprig of fresh mint leaves beside it.
Upload → `storage/app/public/products/143/`

## 2. Organic A2 Gir Cow Desi Ghee (Jar, 1L) — id 144
Same style, larger 1-litre clear glass jar of rich golden desi ghee, wooden
spoon resting on the rim with a ribbon of ghee, cream label "A2 GIR COW DESI
GHEE", a few golden turmeric roots for accent.
Upload → `storage/app/public/products/144/`

## 3. Pure Village Cow Ghee (Jar) — id 145
Traditional clay/terra-cotta jar of golden village cow ghee, smooth golden fat,
small hand-painted tribal motif, ghee scoop, rustic wooden surface, warm light.
Upload → `storage/app/public/products/145/`

## 4. Desi Ghee - Packed (500 g) — id 146
Pre-packed silver-foil-laminated pouch of desi ghee standing upright, glossy
golden-green AB Organic label "DESI GHEE 500g", subtle steam/ghee sheen.
Upload → `storage/app/public/products/146/`

## 5. Multitype Ghee Gift Pack — id 147
Premium gift box set containing 3 small glass jars of different ghee (cow,
buffalo, flavoured), gift-wrap in cream-and-gold AB Organic packaging, satin
ribbon, clear lid showing the jars.
Upload → `storage/app/public/products/147/`

## 6. Kachhi Ghani Mustard Oil — id 148
Clear PET bottle of deep amber-coloured kachhi ghani (cold-pressed) mustard oil,
green screw cap, crisp green-and-yellow AB Organic label "KACHHI GHANI MUSTARD
OIL 1L", a few mustard seeds and a mustard sprig beside the bottle.
Upload → `storage/app/public/products/148/`

## 7. Cold-Pressed Groundnut (Peanut) Oil — id 149
Clear glass bottle of golden cold-pressed groundnut/peanut oil, light nutty
amber colour, green label "COLD-PRESSED GROUNDNUT OIL", peanuts in shell
scattered alongside.
Upload → `storage/app/public/products/149/`

## 8. Virgin Coconut Oil — id 150
Clear glass jar of white, solid (crystallised) virgin coconut oil, light food
grade, pale-green label "VIRGIN COCONUT OIL", fresh coconut half and shell
beside it.
Upload → `storage/app/public/products/150/`

## 9. Stone-Ground Whole Wheat Atta (5 kg) — id 151
Open kraft-paper sack of fine stone-ground whole-wheat flour (atta), soft beige
flour spilling slightly over the top, green-brown AB Organic label "STONE-GROUND
WHOLE WHEAT ATTA", a few wheat stalks and a small stone grinder stone for accent.
Upload → `storage/app/public/products/151/`

## 10. Multigrain Atta (5 kg) — id 152
Kraft sack of brown multigrain atta flour, visible mixed-grain specks (wheat,
chana, jowar, bajra), label "MULTIGRAIN ATTA", scattered multi-grains and a
light-coloured bowl of the flour beside it.
Upload → `storage/app/public/products/152/`

---

## Optional — second "alternate" image per product (for gallery + hover)
Generate a second, slightly different angle/pack-only shot per product and add
it as a second image in the same admin product edit. Recommended: a 45° angled
pack shot with the cap/cork side facing the camera.

---

## Upload instructions (after your AI generates each square image)
1. Filesystem (direct): copy each generated file into
   `storage/app/public/products/<id>/` — the web URL becomes
   `https://yourdomain/storage/products/<id>/filename.jpg`.
2. Recommended (admin): **Admin → Products → edit that product → Images →
   Upload** one or more files. It auto-resizes to 1000×1000, sets the first as
   primary and enables the gallery + hover-swap. This is the safest option.
3. Recommended size: at least 1000×1000, JPG/PNG/WebP, any aspect (square
   preferred).