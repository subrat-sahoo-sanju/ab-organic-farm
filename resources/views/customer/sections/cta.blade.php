<section class="relative overflow-hidden bg-gradient-to-br from-[#1F5C3F] via-[#2E7D53] to-[#173F2A] py-14">
  <div class="pointer-events-none absolute -left-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
  <div class="pointer-events-none absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-[#9CD198]/20 blur-3xl"></div>
  <div class="relative mx-auto max-w-[1440px] px-4 text-center sm:px-6 lg:px-8">
    <h2 class="font-display text-3xl font-extrabold text-white sm:text-4xl">{{ $sec->title ?: setting('home.cta_title', 'Go Organic. Go Fresh. Go Fast.') }}</h2>
    <p class="mx-auto mt-3 max-w-lg text-base text-white/70">{{ $sec->subtitle ?: setting('home.cta_subtitle', 'Join thousands of families who trust AB Organic Farm for their daily groceries.') }}</p>
    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
      <a href="{{ setting('home.cta_link', route('shop.categories')) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-[#1F5C3F] shadow-lg transition hover:bg-[#F6E4AE]">
        <x-lucide-shopping-cart class="h-4 w-4" />{{ setting('home.cta_button', 'Start Shopping') }}
      </a>
      <a href="{{ route('shop.categories') }}" class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 px-7 py-3.5 text-sm font-bold text-white transition hover:border-white/60 hover:bg-white/10">
        Browse Categories
      </a>
    </div>
  </div>
</section>