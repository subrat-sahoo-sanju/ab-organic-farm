# AB Organic Farm — AI Image & Logo Prompt Pack
### (Reusable prompts: generate product images and the brand logo, then upload via Admin)

Reuse this file every time you need fresh, on-brand images. Copy/paste the prompt block into
any image AI (ChatGPT / Gemini / Midjourney / Ideogram / DALL·E / Firefly), save the output
image(s), then upload through the site admin. The storefront reads images from the public
storage folder automatically — no code change needed.

---

## 1. MASTER BRAND STYLE (add this line into EVERY prompt)

```
Photorealistic consumer product photography, soft natural daylight, clean light-
green/cream studio background (#F4F8F2), gentle soft shadows, centered composition,
high detail, 1000x1000 square, premium organic farm branding, no text overlays
except where specified, appetizing and fresh.
```

Brand palette (use in prompts for consistency):
- Primary green: `#14532D` · Leaf green: `#22A762` · Cream/background: `#F4F8F2`
- Gold accent: `#B5762A` · Charcoal text: `#1F2937`

---

## 2. BRAND LOGO (generate a few versions, pick best)

```
Minimal modern circular emblem logo for an organic farm "AB". Two fresh green leaves
forming an elegant round wreath around the letters "AB", centered. Flat vector style,
crisp edges, transparent/pure white background version and a solid deep-green #14532D
version. Clean, premium, no gradient, no photorealism, high resolution.
```

Secondary short-line logo (for nav/footer):

```
Horizontal minimalist wordmark logo "AB Organic Farm", custom font, with a small
three-leaf sprig icon to the left, deep green #14532D text, transparent background.
```

**Save (upload) locations for logos:**
- Main header logo → Admin → Settings (Store / Design) or `storage/app/public/logos/logo.png`
- Footer / white version → `storage/app/public/logos/logo-white.png`
- Brand logos shown on the homepage "Trusted by / logo slider" → Admin → Catalogue → Brands → edit each brand → upload `logo`. Stored under `storage/app/public/brands/`.

---

## 3. CATEGORY IMAGES (used for the category cards + category banner)

Generate one wide hero banner + one square card per category. Replace `{CATEGORY}` with
the real name below.

```
Square card: A beautiful {CATEGORY} composition, main product in a clean glass/eco jar
at center with a few key ingredients scattered around, soft green studio background,
organic premium feel. 1000x1000 square.
Wide banner: A cinematic wide shot of {CATEGORY} with fresh ingredients and a blurred
green farm landscape in the background, space on the left for headline text,
16:9 landscape.
```

**Upload paths:**
- Category card → Admin → Catalogue → Categories → edit → `Card Image` (`categories.image_path`)
- Category banner → Admin → Catalogue → Categories → edit → `Banner Image` (`categories.banner_image`)
- Stored under `storage/app/public/categories/`.

---

## 4. PRODUCT IMAGES (main deliverable — one block per product)

For **each** product, generate **3 images**: (1) main card photo, (2) hover/second photo,
(3) package/detail shot. The product card shows photo 1 by default and photo 2 on hover.
Use the real product name and its category.

Select the right base line by category, then paste the product name.

**Ghee** (golden clarified butter):
```
A glass jar of golden A2 desi ghee with thick creamy texture, small wooden spoon,
dark-gold liquid catching light, soft green/cream background. {PRODUCT NAME}.
```

**Oil** (cold-pressed oils):
```
A tall elegant glass bottle of cold-pressed oil, light amber/golden liquid, a few raw
seed/mustard/groundnut kernels beside it, green studio background. {PRODUCT NAME}.
```

**Atta** (flours / wheat):
```
A rustic kraft/eco pouch of stone-ground flour, a small wooden bowl of flour and a few
wheat grains, cream background. {PRODUCT NAME}.
```

**Jar / Packed / Multitype variants** of ghee — same ghee line, just show the specific packaging:
- Jar Type → round glass jar
- Packed Type → sealed foil/paper pack
- Multitype → gift box with 2 jars

For every product also add:

```
Second shot (hover): a different angle with the product opened/1 spoonful, same
background. 1000x1000.
Detail shot: extreme close-up macro of the texture (ghee swirl / oil pour / flour
dust), 1000x1000.
```

**Real product names to generate** (Odia + English branding), grouped by category:

- **Ghee (AB Organic Farm Own):**
  1. A2 Gir Cow Desi Ghee (Jar) — "Organic A2 Gir Cow Desi Ghee"
  2. Village Cow Ghee (Jar) — "Pure Village Cow Ghee"
  3. Packed Desi Ghee (Pack) — "Desi Ghee - Packed"
  4. Multitype Ghee Combo (Box) — "Multitype Ghee Gift Pack"
- **Oil:**
  5. Cold-Pressed Mustard Oil — "Kachhi Ghani Mustard Oil"
  6. Cold-Pressed Groundnut Oil — "Groundnut (Peanut) Oil"
  7. Cold-Pressed Coconut Oil — "Virgin Coconut Oil"
- **Atta:**
  8. Stone-Ground Whole Wheat Atta — "Aata / Whole Wheat Flour"
  9. Multigrain Atta — "Multigrain Atta"

---

## 5. SAVE / UPLOAD PATHS — exactly where images must go

On this machine the real folder is:

```
/Users/subratkumarsahoo/_projects/PROJECTS/LARAVEL/ORGANIC_PROJECT_LARAVEL/organic-store/storage/app/public/
```

which is symlinked to the web as `https://YOUR-SITE/public/storage/...` (i.e. `storage/...` in the DB).

| What | DB field | Folder |
|------|----------|--------|
| Product photo 1, 2, 3… | `product_images` | `storage/app/public/` *(uploaded via admin, auto-resized 1000×1000)* |
| Category card | `categories.image_path` | `storage/app/public/categories/` |
| Category banner | `categories.banner_image` | `storage/app/public/categories/` |
| Brand logo | `brands.logo_path` | `storage/app/public/brands/` |
| Site logo (header) | settings | `storage/app/public/logos/logo.png` |
| Site logo (white/footer) | settings | `storage/app/public/logos/logo-white.png` |
| Homepage section images (native/quality, combos carousel, app, badges) | section config | `storage/app/public/sections/` |

**In the admin you never type paths — you upload the files and paths are written for you.**

---

## 6. HOW TO UPLOAD (admin-driven, all manageable)

1. **Products (multi-image):** Admin → Catalogue → Products → **Add Product** →
   fill Name/SKU/Category/Brand/price → scroll to **Images** → upload 3 files (main,
   hover, detail) in order → Save. Then on the edit screen use ★ (primary) and ←/→
   (reorder) so photo #1 is the main card image.
2. **Categories (+ card + banner image):** Admin → Catalogue → Categories → edit.
3. **Brands (+ logo for homepage slider):** Admin → Catalogue → Brands → edit → upload logo.
4. **Inventory:** Admin → Catalogue → Inventory → adjust stock per variant.
5. **Homepage:** Admin → Settings → Homepage Sections → toggle sections ON, set titles,
   reorder. Sections like Best Sellers / New Arrivals show as soon as products exist and
   are flagged on the product (✔ Best Seller / ✔ New Arrival).

## 7. LIVE REFLECTION (settings ↔ products)
- Flag a product **Featured / Best Seller / New Arrival** in the product form to populate
  the matching homepage rail instantly (after Save + refresh).
- Create/review **approved testimonials** (Admin → Reviews) to populate "What Do Our
  Customers Say".
- Upload **brand logos** to populate the "Trusted by / logo slider".

---

## 8. ONE-CLICK GENERATION CHECKLIST (repeat any time)
1. Pick a product name from section 4 (or a new one in the same category).
2. Paste: `MASTER STYLE` + `category base line` + `{PRODUCT NAME}` + `3-shots line`.
3. Save the 3 images.
4. Admin → Products → (Edit) → Images → upload 3 in order → Save.
5. Set Primary on photo 1. Done — it appears on the store in real time.