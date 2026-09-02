<script>
if (!window.__tabGridRegistered) {
  window.__tabGridRegistered = true;
  document.addEventListener('alpine:init', () => {
    // Shared tab grid (focus sections): underline switch + lazy load.
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

    // Reference "Welcome To" menu: icon tabs + sliding indicator + lazy product grid.
    Alpine.data('welcomeTabs', (gridId, initialKey, tabs) => ({
      active: initialKey,
      loading: false,
      tabs,
      init() {
        this.$nextTick(() => this.$nextTick(() => this.moveToActive()));
      },
      moveToActive() {
        const btn = this.$refs.rail?.querySelector('.anv-tab-btn.active');
        if (btn) this.placeIndicator(btn);
      },
      placeIndicator(el) {
        const ind = this.$refs.indicator;
        if (!ind || !el) return;
        ind.style.opacity = '1';
        ind.style.width = el.offsetWidth + 'px';
        ind.style.transform = 'translateX(' + el.offsetLeft + 'px)';
      },
      async pick(tab, el) {
        if (this.loading || !el || this.active === tab.key) return;
        this.active = tab.key;
        this.placeIndicator(el);
        this.loading = true;
        const grid = document.getElementById(gridId);
        grid?.classList.add('opacity-40', 'pointer-events-none');
        try {
          const r = await fetch(tab.url, { headers: { 'Accept': 'application/json' } });
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