<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function show(): View
    {
        return view('admin.settings.index', [
            'sections' => $this->sections(),
            'current' => \App\Models\Setting::pluck('value', 'key')->toArray(),
        ]);
    }

    public function update(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate($this->validationRules());

        foreach ($data as $key => $value) {
            $existing = \App\Models\Setting::where('key', $key)->first();
            $existing
                ? $existing->update(['value' => $value ?? ''])
                : \App\Models\Setting::create(['key' => $key, 'value' => $value ?? '']);
        }

        \Illuminate\Support\Facades\Cache::forget('settings.all');

        return back()->with('success', 'Settings saved and live on the storefront.');
    }

    protected function sections(): array
    {
        return [
            'store' => [
                'title' => 'Store Info',
                'keys' => [
                    ['key' => 'store_name', 'label' => 'Store Name'],
                    ['key' => 'store_tagline', 'label' => 'Tagline'],
                    ['key' => 'store_email', 'label' => 'Contact Email'],
                    ['key' => 'store_phone', 'label' => 'Contact Phone'],
                    ['key' => 'store_whatsapp', 'label' => 'WhatsApp Number'],
                    ['key' => 'store_address', 'label' => 'Store Address', 'type' => 'textarea'],
                ],
            ],
            'seo' => [
                'title' => 'SEO & Social',
                'keys' => [
                    ['key' => 'seo_title', 'label' => 'Default Title Tag'],
                    ['key' => 'seo_description', 'label' => 'Meta Description', 'type' => 'textarea'],
                    ['key' => 'seo_keywords', 'label' => 'Meta Keywords'],
                    ['key' => 'og_title', 'label' => 'OG Title'],
                    ['key' => 'og_description', 'label' => 'OG Description', 'type' => 'textarea'],
                    ['key' => 'og_image_url', 'label' => 'OG Image URL'],
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
                    ['key' => 'footer.payment_title', 'label' => 'Payment Block Title'],
                    ['key' => 'footer.payment_text', 'label' => 'Payment Block Text'],
                    ['key' => 'footer.shop_title', 'label' => 'Shop Column Title'],
                    ['key' => 'footer.account_title', 'label' => 'Account Column Title'],
                    ['key' => 'footer.links_shop', 'label' => 'Shop Links', 'type' => 'json', 'json_schema' => 'link_list'],
                    ['key' => 'footer.links_account', 'label' => 'Account Links', 'type' => 'json', 'json_schema' => 'link_list'],
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
