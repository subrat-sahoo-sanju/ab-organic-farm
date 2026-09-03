@php
$svg = [
    'icon-mega'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>',
    'icon-menu'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h14"/>',
    'icon-shield' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/>',
    'icon-leaf'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21C7 17 5 12 5 8c0-3 2-5 5-5 4 0 8 3 9 7 1 4-1 8-4 10-.4.2-.8.3-1 .4-.6.3-1.5.5-2 .6zM12 21c0-6 6-9 10-12"/>',
    'icon-star'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2.7 5.6 6.1.8-4.5 4.3 1.1 6-5.4-2.9-5.4 2.9 1.1-6-4.5-4.3 6.1-.8L12 3z"/>',
    'icon-cart'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.4 12a2 2 0 002 1.6h7.2a2 2 0 002-1.6L21 8H6"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/>',
    'icon-spark'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m0 12v3m9-9h-3M6 12H3m14.5-5.5l-2 2m-7 7l-2 2m11 0l-2-2m-7-7l-2-2"/>',
    'icon-heart'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.8 8.5a5.5 5.5 0 00-9.3-4A5.5 5.5 0 002.2 8.5c0 4 5.3 8.5 9.3 10.5 4-2 9.3-6.5 9.3-10.5z"/>',
    'icon-drop'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3s6 6.5 6 10.5a6 6 0 01-12 0C6 9.5 12 3 12 3z"/>',
    'icon-flame'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3s4 4.5 4 8a4 4 0 01-8 0c0-1.5 1-3 2-3.5-1 1-.5 2 .5 2.5-1-1-.5-4.5 1.5-7z"/>',
    'icon-clock'  => '<circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>',
    'icon-phone'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 4h4l2 5-3 2a13 13 0 006 6l2-3 5 2v4a2 2 0 01-2 2A18 18 0 013 6a2 2 0 012-2z"/>',
    'icon-flag'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 21V4m0 0c4-2 8 2 12 0v9c-4 2-8-2-12 0"/>',
    'icon-tag'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4h9l7 7-6 6-9-9V4z"/><circle cx="8" cy="8" r="1.5"/>',
    'icon-trophy' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8v7a4 4 0 01-4 4 4 4 0 01-4-4V4zM6 4H4v2a4 4 0 002 3.8M18 4h2v2a4 4 0 01-2 3.8M12 15v3M9 21h6"/>',
    'icon-fire'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3c.5 3-4 5-4 9a4 4 0 008 0c0-2-1-3-2-4 .5 1 0 2-1 2.5.5-2.5-1-6-1-7.5z"/>',
    'icon-box'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8l-9-5-9 5m18 0v8l-9 5-9-5V8m18 0l-9 5m0 8v-8"/>',
    'icon-leaf_2' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21C7 17 5 12 5 8c0-3 2-5 5-5 4 0 8 3 9 7 1 4-1 8-4 10-.4.2-.8.3-1 .4-.6.3-1.5.5-2 .6zM12 21c0-6 6-9 10-12"/>',
];
$path = $svg[$icon ?? 'icon-leaf'] ?? $svg['icon-leaf'];
@endphp
<svg xmlns="http://www.w3.org/2000/svg" @if(isset($cls)) class="{{ $cls }}" @endif fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $path !!}</svg>