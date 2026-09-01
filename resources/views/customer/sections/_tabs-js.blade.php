<script>
if (!window.__tabGridRegistered) {
  window.__tabGridRegistered = true;
  document.addEventListener('alpine:init', () => {
    Alpine.data('tabGrid', (gridId, initial = 'all') => ({
      active: initial,
      loading: false,
      async pick(id, url) {
        if (this.loading || this.active === id) return;
        this.active = id;
        this.loading = true;
        const grid = document.getElementById(gridId);
        grid?.classList.add('opacity-40', 'pointer-events-none');
        try {
          const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
          const d = await r.json();
          if (grid) grid.innerHTML = d.html;
        } catch (e) {
          if (grid) grid.innerHTML = '<div class="col-span-full py-10 text-center text-sm text-charcoal-600/50">Couldn\'t load products. Please try again.</div>';
        }
        grid?.classList.remove('opacity-40', 'pointer-events-none');
        this.loading = false;
      },
    }));
  });
}
</script>