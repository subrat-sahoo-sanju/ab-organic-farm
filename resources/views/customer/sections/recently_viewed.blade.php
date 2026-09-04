<style>
  .rv-heading{font-weight:600;text-align:center;color:#7C522A;font-size:1.9rem;margin-bottom:5px}
  .rv-subheading{text-align:center;color:#7C522A;font-size:1.5rem;margin-bottom:20px;margin-top:0}
  @media (min-width:769px){.rv-heading{font-weight:600;text-align:center;color:#7C522A;font-size:2.9rem;margin-bottom:8px}.rv-subheading{text-align:center;color:#7C522A;font-size:1.8rem;margin-bottom:20px;margin-top:0}}
  .rv-slider-container{position:relative;width:100%}
  .rv-horizontal-scroll{display:flex!important;flex-wrap:nowrap!important;overflow-x:auto;overflow-y:hidden;scroll-snap-type:x mandatory;scroll-behavior:smooth;padding:10px 0 20px;margin:0;list-style:none;-webkit-overflow-scrolling:touch;scrollbar-width:thin;scrollbar-color:#888 #f1f1f1}
  .rv-horizontal-scroll::-webkit-scrollbar{height:10px}
  .rv-horizontal-scroll::-webkit-scrollbar-track{background:#f1f1f1}
  .rv-horizontal-scroll::-webkit-scrollbar-thumb{background:#888;border-radius:5px}
  .rv-product-card{flex:0 0 auto;width:150px;scroll-snap-align:start;list-style:none}
  .rv-product-card .anv-card{min-width:0}
  @media (min-width:769px) and (max-width:1024px){.rv-product-card{width:180px}}
  @media (min-width:1025px){.rv-product-card{width:180px}}
</style>
<section id="recently-viewed-section" class="w-full border-t border-sage-100 bg-white py-10 sm:py-14" style="display:none">
    <div class="mx-auto w-full max-w-[1440px] px-4 sm:px-6 lg:px-8">
        <h2 class="rv-heading">{{ $sec->title }}</h2>
        <p class="rv-subheading">{{ $sec->subtitle }}</p>
        <div class="rv-slider-container">
            <ul id="rv-product-grid" class="rv-horizontal-scroll" role="list"></ul>
        </div>
    </div>
</section>
<script>
  (function () {
    var ids = [];
    try { ids = JSON.parse(localStorage.getItem('ab_recent_views') || '[]'); } catch (e) { ids = []; }
    ids = (Array.isArray(ids) ? ids : []).filter(function (n) { return typeof n === 'number' && n > 0; }).slice(0, 12);
    var sec = document.getElementById('recently-viewed-section');
    var grid = document.getElementById('rv-product-grid');
    if (!sec || !grid || !ids.length) return;
    var url = '{{ route('api.recent-viewed') }}?ids=' + ids.join(',');
    fetch(url, { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (d) { if (d && d.html) { grid.innerHTML = d.html; sec.style.display = ''; } })
      .catch(function () {});
  })();
</script>