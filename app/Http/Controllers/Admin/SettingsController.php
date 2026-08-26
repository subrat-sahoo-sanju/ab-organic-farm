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

        return back()->with('success', 'Settings saved. Some changes may require a cache clear to take effect.');
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
                    default   => ['nullable', 'string', 'max:500'],
                };
            }
        }

        return $rules;
    }
}
