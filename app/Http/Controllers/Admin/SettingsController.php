<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function show(): View
    {
        $current = \App\Models\Setting::query()->get()
            ->mapWithKeys(fn ($s) => [$s->group.'.'.$s->key => $s->value])
            ->toArray();

        return view('admin.settings.index', [
            'sections' => $this->sections(),
            'current' => $current,
            'homepageSections' => HomepageSection::orderBy('sort_order')->get(),
        ]);
    }

    public function updateSections(Request $request): RedirectResponse
    {
        foreach ($request->input('sections', []) as $id => $payload) {
            $section = HomepageSection::find((int) $id);
            if (! $section) {
                continue;
            }

            $config = $section->config ?? [];
            $config['product_count'] = ($payload['product_count'] ?? '') !== '' ? (int) $payload['product_count'] : null;

            if (isset($payload['tabs'])) {
                $config['tabs'] = array_values(array_filter(array_map('trim', explode(',', $payload['tabs']))));
            }

            if (array_key_exists('android_url', $payload)) {
                $config['android_url'] = $payload['android_url'];
            }
            if (array_key_exists('ios_url', $payload)) {
                $config['ios_url'] = $payload['ios_url'];
            }
            if (isset($payload['items'])) {
                $decoded = json_decode((string) $payload['items'], true);
                if (is_array($decoded)) {
                    $config['items'] = $decoded;
                }
            }

            // Section images (desktop + mobile) — upload to storage/sections.
            $images = $config['images'] ?? [];
            $images['alt'] = $payload['image_alt'] ?? ($images['alt'] ?? '');
            $imageKeys = ['desktop' => 'image_desktop', 'mobile' => 'image_mobile'];
            foreach ($imageKeys as $slot => $field) {
                $images[$slot] = $payload[$field.'_existing'] ?? ($images[$slot] ?? '');
                if ($request->hasFile("sections.{$id}.{$field}")) {
                    $file = $request->file("sections.{$id}.{$field}");
                    $path = $file->store('sections', 'public');
                    if ($path) {
                        $images[$slot] = $path;
                    }
                }
            }
            $config['images'] = $images;

            $section->update([
                'title' => $payload['title'] ?? '',
                'subtitle' => $payload['subtitle'] ?? '',
                'is_visible' => ! empty($payload['visible']) && $payload['visible'] !== '0' ? 1 : 0,
                'sort_order' => (int) ($payload['sort_order'] ?? $section->sort_order),
                'config' => $config,
            ]);
        }

        return back()->with('success', 'Homepage sections updated and live on the storefront.');
    }

    public function update(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate($this->validationRules());

        $service = app(\App\Services\SettingsService::class);
        foreach ($data as $key => $value) {
            $service->set($key, $value ?? '');
        }

        return back()->with('success', 'Settings saved and live on the storefront.');
    }

    protected function sections(): array
    {
        return [
            'store' => [
                'title' => 'Store Info',
                'keys' => [
                    ['key' => 'store.name', 'label' => 'Store Name'],
                    ['key' => 'store.tagline', 'label' => 'Tagline'],
                    ['key' => 'store.email', 'label' => 'Contact Email'],
                    ['key' => 'store.phone', 'label' => 'Contact Phone'],
                    ['key' => 'store.address', 'label' => 'Store Address', 'type' => 'textarea'],
                ],
            ],
            'seo' => [
                'title' => 'SEO & Social',
                'keys' => [
                    ['key' => 'seo.title', 'label' => 'Default Title Tag'],
                    ['key' => 'seo.description', 'label' => 'Meta Description', 'type' => 'textarea'],
                    ['key' => 'seo.keywords', 'label' => 'Meta Keywords'],
                    ['key' => 'og.title', 'label' => 'OG Title'],
                    ['key' => 'og.description', 'label' => 'OG Description', 'type' => 'textarea'],
                    ['key' => 'og.image_url', 'label' => 'OG Image URL'],
                    ['key' => 'social.facebook', 'label' => 'Facebook URL'],
                    ['key' => 'social.instagram', 'label' => 'Instagram URL'],
                    ['key' => 'social.whatsapp', 'label' => 'WhatsApp Number'],
                ],
            ],
            'cod' => [
                'title' => 'COD & Payments',
                'keys' => [
                    ['key' => 'cod.enabled', 'label' => 'Enable COD', 'type' => 'boolean'],
                    ['key' => 'cod.min_order_value', 'label' => 'Minimum Order Value (₹)', 'type' => 'number'],
                    ['key' => 'cod.max_order_value', 'label' => 'Maximum Order Value (₹)', 'type' => 'number'],
                    ['key' => 'cod.advance_percent', 'label' => 'Advance Collection % (0-100)', 'type' => 'number'],
                    ['key' => 'cod.delivery_charges', 'label' => 'Delivery Charge (₹)', 'type' => 'number'],
                    ['key' => 'cod.free_delivery_above', 'label' => 'Free Delivery Above (₹)', 'type' => 'number'],
                ],
            ],
            'inventory' => [
                'title' => 'Inventory',
                'keys' => [
                    ['key' => 'inventory.low_stock_threshold', 'label' => 'Global Low Stock Alert Level', 'type' => 'number'],
                    ['key' => 'inventory.email_alerts', 'label' => 'Send Low Stock Email Alerts', 'type' => 'boolean'],
                ],
            ],
            'notifications' => [
                'title' => 'Notification Channels',
                'keys' => [
                    ['key' => 'notify.admin_email', 'label' => 'Admin Notification Email'],
                    ['key' => 'notify.sms', 'label' => 'Enable SMS Notifications', 'type' => 'boolean'],
                    ['key' => 'notify.whatsapp', 'label' => 'Enable WhatsApp Notifications', 'type' => 'boolean'],
                ],
            ],
            'home' => [
                'title' => 'Homepage Content',
                'keys' => [
                    ['key' => 'home.search_placeholder', 'label' => 'Search Bar Placeholder Text'],
                    ['key' => 'home.delivery_charge_text', 'label' => 'Delivery Badge Text (e.g. Free delivery ₹499+)'],
                    ['key' => 'home.tags', 'label' => 'Search Quick Tags', 'type' => 'json', 'json_schema' => 'tags'],
                    ['key' => 'home.promo_cards', 'label' => 'Promo Cards (2 cards)', 'type' => 'json', 'json_schema' => 'promo_cards'],
                    ['key' => 'home.brand_title', 'label' => 'Shop by Brand — Title'],
                    ['key' => 'home.brand_subtitle', 'label' => 'Shop by Brand — Subtitle'],
                    ['key' => 'home.featured_title', 'label' => 'Featured Products — Title'],
                    ['key' => 'home.featured_subtitle', 'label' => 'Featured Products — Subtitle'],
                    ['key' => 'home.best_title', 'label' => 'Best Sellers — Title'],
                    ['key' => 'home.best_subtitle', 'label' => 'Best Sellers — Subtitle'],
                    ['key' => 'home.new_title', 'label' => 'New Arrivals — Title'],
                    ['key' => 'home.new_subtitle', 'label' => 'New Arrivals — Subtitle'],
                    ['key' => 'home.why_title', 'label' => 'Why Choose Us — Title'],
                    ['key' => 'home.why_items', 'label' => 'Why Choose Us — Feature Boxes', 'type' => 'json', 'json_schema' => 'feat_items'],
                    ['key' => 'home.testimonial_title', 'label' => 'Testimonials — Title'],
                    ['key' => 'home.cta_title', 'label' => 'Bottom CTA Banner — Title'],
                    ['key' => 'home.cta_subtitle', 'label' => 'Bottom CTA Banner — Subtitle'],
                    ['key' => 'home.cta_button', 'label' => 'Bottom CTA Banner — Button Text'],
                    ['key' => 'home.cta_link', 'label' => 'Bottom CTA Banner — Button Link'],
                ],
            ],
            'footer' => [
                'title' => 'Footer Content',
                'keys' => [
                    ['key' => 'footer.tagline', 'label' => 'Footer Tagline'],
                    ['key' => 'footer.address', 'label' => 'Corporate Office Address'],
                    ['key' => 'footer.links_services', 'label' => 'Services Links', 'type' => 'json', 'json_schema' => 'link_list'],
                    ['key' => 'footer.links_policies', 'label' => 'Policies Links', 'type' => 'json', 'json_schema' => 'link_list'],
                    ['key' => 'footer.socials', 'label' => 'Social Links (icon: facebook|instagram|twitter|youtube)', 'type' => 'json', 'json_schema' => 'link_list'],
                    ['key' => 'store.contact_link', 'label' => 'Contact Link'],
                ],
            ],
            'display' => [
                'title' => 'Site Display',
                'keys' => [
                    ['key' => 'display.announcement_items', 'label' => 'Announcement Bar Messages (JSON array of strings)', 'type' => 'json', 'json_schema' => 'tags'],
                    ['key' => 'display.app_download_enabled', 'label' => 'App Download Bar: Enable', 'type' => 'boolean'],
                    ['key' => 'display.app_download_heading', 'label' => 'App Download Bar: Heading'],
                    ['key' => 'display.app_download_sub', 'label' => 'App Download Bar: Subline'],
                    ['key' => 'display.app_download_url2', 'label' => 'App Download Bar: Download Link (URL)'],
                    ['key' => 'display.app_store_url', 'label' => 'Footer App Store URL'],
                    ['key' => 'display.app_download_url', 'label' => 'Footer Play Store URL'],
                    ['key' => 'display.rewards_enabled', 'label' => 'Rewards Bar: Enable', 'type' => 'boolean'],
                    ['key' => 'display.rewards_mainline', 'label' => 'Rewards Bar: Mainline Text'],
                    ['key' => 'display.rewards_coins', 'label' => 'Rewards Bar: Coin Count (number)'],
                    ['key' => 'display.rewards_subline', 'label' => 'Rewards Popup: Subline'],
                    ['key' => 'display.rewards_items', 'label' => 'Rewards Popup: Earn Items', 'type' => 'json', 'json_schema' => 'rewards'],
                    ['key' => 'display.whatsapp_enabled', 'label' => 'WhatsApp Widget: Enable', 'type' => 'boolean'],
                    ['key' => 'display.whatsapp_number', 'label' => 'WhatsApp Widget: Number (with country code)'],
                    ['key' => 'display.whatsapp_message', 'label' => 'WhatsApp Widget: Pre-filled Message'],
                    ['key' => 'display.bottom_nav', 'label' => 'Mobile Bottom Nav (JSON list)', 'type' => 'json', 'json_schema' => 'nav_items'],
                ],
            ],
        ];
    }

    protected function validationRules(): array
    {
        $rules = [];
        foreach ($this->sections() as $section) {
            foreach ($section['keys'] as $field) {
                $rules[$field['key']] = match ($field['type'] ?? 'text') {
                    'boolean' => ['nullable', 'boolean'],
                    'number'  => ['nullable', 'numeric', 'min:0'],
                    'json'    => ['nullable', 'json'],
                    default   => ['nullable', 'string', 'max:500'],
                };
            }
        }

        return $rules;
    }
}
