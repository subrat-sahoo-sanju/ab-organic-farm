<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'privacy-policy', 'title' => 'Privacy Policy',
                'short' => 'How we collect, use and protect your personal information.',
                'hero' => 'Your data, handled with care', 'icon' => 'shield-check',
                'lede' => 'At '.config('app.name', 'AB Organic Farm').', we treat your trust as seriously as we treat our soil. This policy explains exactly what we collect, why, and the measures we take to keep your personal information safe, private and protected.',
                'sections' => [
                    ['heading' => 'Information we collect', 'icon' => 'database', 'body' => 'We collect only what is needed to serve you: your name, delivery address, phone number, email, order history and payment details. When you create an account or place an order we also store preferences so we can personalise your farm favourites.'],
                    ['heading' => 'How we use your information', 'icon' => 'sparkles', 'body' => 'Your details power your orders — processing payments, arranging delivery, sending updates and resolving queries. With your permission we also send seasonal offers and harvest updates. We never sell your data.'],
                    ['heading' => 'How we protect it', 'icon' => 'lock', 'body' => 'All information travels over secure, encrypted connections and is stored on protected servers. Payment data is handled by trusted gateways — we never see or store your full card number.'],
                    ['heading' => 'Cookies and analytics', 'icon' => 'cookie', 'body' => 'We use cookies to keep your cart intact, remember preferences and improve the store. You can disable cookies in your browser at any time.'],
                    ['heading' => 'Your rights', 'icon' => 'user-check', 'body' => 'You may request a copy of the data we hold, ask us to correct or delete it, withdraw marketing consent, or export your data. Email our support team and we respond within five working days.'],
                ],
                'faqs' => [
                    ['q' => 'Do you share my information?', 'a' => 'Only with delivery partners who need your address and payment processors who handle transactions. We never sell or rent your data.'],
                    ['q' => 'How long do you keep my data?', 'a' => 'Order records are kept as long as required for tax and warranty purposes (usually six years). Marketing data is kept until you withdraw consent.'],
                ],
                'sort_order' => 1,
            ],
            [
                'slug' => 'shipping-policy', 'title' => 'Shipping Policy',
                'short' => 'Delivery areas, timelines, charges and what to expect.',
                'hero' => 'From our farm to your doorstep', 'icon' => 'truck',
                'lede' => 'We know fresh food should arrive fast and arrive right. This page spells out where we deliver, how long it takes, what it costs — and what makes our packaging kinder to both your food and the planet.',
                'sections' => [
                    ['heading' => 'Where we deliver', 'icon' => 'map-pin', 'body' => 'We deliver across most of the region including urban centres and surrounding districts. Enter your pincode at checkout and our system instantly confirms whether we service your area.'],
                    ['heading' => 'Order processing', 'icon' => 'clock', 'body' => 'Orders placed before 4pm on a working day are picked, packed and dispatched the same day. Later or weekend orders leave our facility the next working morning.'],
                    ['heading' => 'Delivery timelines', 'icon' => 'timer', 'body' => 'Standard delivery takes 2–4 working days; metro areas often arrive next-day. Express delivery is available at checkout where services permit (24–48 hours).'],
                    ['heading' => 'Charges & free delivery', 'icon' => 'package-check', 'body' => 'Delivery is FREE above a cart value of '.setting('delivery.free_above', 499).'. Below that a small, flat fee is shown before you pay — no surprise charges at the door.'],
                    ['heading' => 'Tracking your order', 'icon' => 'scan-line', 'body' => 'As soon as your order ships you receive tracking by SMS and email. Follow it any time from My Orders in your account.'],
                ],
                'faqs' => [
                    ['q' => 'Can I track my delivery live?', 'a' => 'Yes — you get a tracking link by SMS/email once dispatched, and can monitor progress in My Orders until it reaches you.'],
                    ['q' => 'What if I live outside your zone?', 'a' => 'Your pincode is checked at checkout. If we do not yet serve you, we add your area to our roadmap — new localities launch regularly.'],
                ],
                'sort_order' => 2,
            ],
            [
                'slug' => 'refund-policy', 'title' => 'Refund & Return Policy',
                'short' => 'Our promise if something is not perfect.',
                'hero' => 'Not happy? We make it right', 'icon' => 'rotate-ccw',
                'lede' => 'Fresh, organic food should arrive perfect — and when it does not, we do not argue. Our no-fuss refund and return policy puts your peace of mind first.',
                'sections' => [
                    ['heading' => 'When you can return', 'icon' => 'package-x', 'body' => 'Request a return or refund within 48 hours of delivery if an item arrives damaged, spoiled, incorrect or does not match its description. Photographic evidence speeds up resolution.'],
                    ['heading' => 'How refunds work', 'icon' => 'refresh-ccw', 'body' => 'Approved refunds go to your original payment method or store credit, whichever you prefer. Most are processed within 3–5 working days after approval.'],
                    ['heading' => 'Perishable goods', 'icon' => 'leaf', 'body' => 'Because our ghee, oils and flours are fresh, opened or consumed products cannot be returned for hygiene reasons unless faulty at delivery. Quality checks guarantee freshness before dispatch.'],
                    ['heading' => 'Non-returnable items', 'icon' => 'shield-x', 'body' => 'Sealed and used personal-care and consumable items, discounted bulk packs and any opened product cannot be returned, except when they arrive damaged or defective.'],
                    ['heading' => 'How to request a return', 'icon' => 'headset', 'body' => 'Contact our support team from the Contact page or your order details with your order ID and a short note. We guide you through the rest — usually a replacement or refund within one working day.'],
                ],
                'faqs' => [
                    ['q' => 'How long does a refund take?', 'a' => 'Once approved, refunds settle within 3–5 working days depending on your payment provider. You are emailed the moment it is processed.'],
                    ['q' => 'I received a damaged item. What now?', 'a' => 'Photograph it within 48 hours, raise a ticket from your order, and we will replace it or refund it — usually the same day.'],
                ],
                'sort_order' => 3,
            ],
            [
                'slug' => 'terms-of-service', 'title' => 'Terms of Service',
                'short' => 'The friendly rules that keep everything fair for you and for us.',
                'hero' => 'Clear, fair terms for a better experience', 'icon' => 'file-check',
                'lede' => 'These terms make sure we have a shared understanding — so shopping on '.config('app.name', 'AB Organic Farm').' is effortless and transparent. By using our store you agree to the terms below.',
                'sections' => [
                    ['heading' => 'Using the store', 'icon' => 'store', 'body' => 'You agree to use the store lawfully, keep your account details accurate, and not misuse the service, our content or other customers\' data. Accounts are for personal use unless you have an approved wholesale arrangement.'],
                    ['heading' => 'Orders & pricing', 'icon' => 'badge-cent', 'body' => 'All prices are in rupees and include prevailing taxes where stated. We may correct pricing errors before dispatch. An order is confirmed only after we send confirmation and accept payment.'],
                    ['heading' => 'Product information', 'icon' => 'info', 'body' => 'We describe products and nutritional details in good faith. Because organic and seasonal produce varies naturally, minor differences in colour, size or texture are expected and not grounds for dispute.'],
                    ['heading' => 'Intellectual property', 'icon' => 'copyright', 'body' => 'All content on this store — text, images, logos and branding — belongs to '.config('app.name', 'AB Organic Farm').' and may not be reused for commercial purposes without written permission.'],
                    ['heading' => 'Limitation of liability', 'icon' => 'scale', 'body' => 'To the extent permitted by law our liability for any claim is limited to the value of the goods in the affected order. Nothing here limits your statutory consumer rights.'],
                ],
                'faqs' => [
                    ['q' => 'Can I order in bulk for events?', 'a' => 'Absolutely. Contact our wholesale team for pricing on ghee, oils, flour and pantry staples for events, restaurants and stores.'],
                    ['q' => 'What law governs these terms?', 'a' => 'These terms are governed by the laws of India, subject to the exclusive jurisdiction of the local courts.'],
                ],
                'sort_order' => 4,
            ],
            [
                'slug' => 'cancellation-policy', 'title' => 'Cancellation Policy',
                'short' => 'Change your mind? Here is how to cancel.',
                'hero' => 'Plans change. We understand', 'icon' => 'x-circle',
                'lede' => 'Pressed the button too soon, or your plans changed? We make cancelling an order simple — and free of charge whenever we can.',
                'sections' => [
                    ['heading' => 'Cancelling before dispatch', 'icon' => 'package-open', 'body' => 'Cancel an order free of charge any time before it is dispatched. Simply go to My Orders and tap Cancel, or message support with your order ID.'],
                    ['heading' => 'Cancellation after dispatch', 'icon' => 'truck', 'body' => 'Once an order has left our facility we cannot stop it in transit, but you can refuse delivery or request a return within 48 hours — we issue a full refund once the item returns to us.'],
                    ['heading' => 'Pre-order & custom items', 'icon' => 'calendar-x', 'body' => 'Pre-orders and custom or wholesale orders already in production cannot be cancelled. These are always flagged clearly at checkout before you commit.'],
                    ['heading' => 'Refunds on cancellation', 'icon' => 'banknote', 'body' => 'Refunds for pre-dispatch cancellations are processed to your original payment method within 3–5 working days. Store credit is issued instantly if you prefer.'],
                ],
                'faqs' => [
                    ['q' => 'Can I cancel and reorder immediately?', 'a' => 'Yes. Cancel in My Orders, then place a new order right away. Cancellation before dispatch is instant.'],
                    ['q' => 'Is there a fee to cancel?', 'a' => 'No cancellation fee applies before dispatch. After dispatch a return may incur a standard reverse-logistics fee unless the item was faulty.'],
                ],
                'sort_order' => 5,
            ],
            [
                'slug' => 'returns-exchanges', 'title' => 'Returns & Exchanges',
                'short' => 'Swap or send back an item, hassle-free.',
                'hero' => 'Swaps and returns made simple', 'icon' => 'refresh-cw',
                'lede' => 'Sometimes a swap is better than a refund. Our returns and exchange programme is designed to be painless, fast and genuinely useful.',
                'sections' => [
                    ['heading' => 'Exchange window', 'icon' => 'calendar-clock', 'body' => 'You have 7 days from delivery to request an exchange for a different variant or size of the same product, as long as the item is unopened and in its original packaging.'],
                    ['heading' => 'Eligible items', 'icon' => 'package', 'body' => 'Sealed pantry staples (ghee, oils, flour, dry goods) can be exchanged for any other variant of equal or greater value. Opened or perishable items cannot be exchanged for hygiene reasons.'],
                    ['heading' => 'How exchanges work', 'icon' => 'arrow-right-left', 'body' => 'Raise an exchange request from your order. We arrange reverse pickup if eligible, you hand over the sealed item, and we dispatch the replacement once it reaches us — usually within 2–3 days.'],
                    ['heading' => 'Returning an item', 'icon' => 'package-search', 'body' => 'Follow the same path as exchanges for returns. Where a return fee applies it is shown upfront. Damaged or incorrect items are swapped or refunded at no cost.'],
                ],
                'faqs' => [
                    ['q' => 'Do I pay for reverse pickup?', 'a' => 'Exchanges and returns for damaged, expired or incorrect items are free. Voluntary returns may attract a small reverse-logistics fee, shown before you confirm.'],
                    ['q' => 'Can I exchange for a different product?', 'a' => 'Within the eligible window you can exchange for any product of equal value; a higher-value product costs the difference.'],
                ],
                'sort_order' => 6,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['is_active' => true])
            );
        }
    }
}