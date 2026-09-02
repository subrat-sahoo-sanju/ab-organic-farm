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

  // Reference "Welcome To" / "Product in Focus" menu: icon tabs + sliding indicator + lazy product grid.
    Alpine.data('welcomeTabs', (gridId, initialKey, tabs) => ({
      active: initialKey,
      loading: false,
      tabs,
      init() {
        // Wait for x-for + :class to settle, then show the indicator under the active tab.
        let tries = 0;
        const tryShow = () => {
          if (!this.$refs.rail || !this.$refs.rail.querySelector('.menu-nav-item.active')) {
            if (tries++ < 25) setTimeout(tryShow, 60);
            return;
          }
          this.moveToActive();
          this.syncScrollbar();
        };
        this.$nextTick(() => setTimeout(tryShow, 0));
      },
      get activeTab() {
        return this.tabs.find(t => t.key === this.active) || this.tabs[0] || null;
      },
      moveToActive() {
        const btn = this.$refs.rail?.querySelector('.menu-nav-item.active');
        if (btn) this.placeIndicator(btn);
      },
      placeIndicator(el) {
        const ind = this.$refs.indicator;
        if (!ind || !el) return;
        ind.style.opacity = '1';
        ind.style.width = el.offsetWidth + 'px';
        ind.style.transform = 'translateX(' + el.offsetLeft + 'px)';
      },
      initScrollbar(thumb) {
        const grid = this.$gridEl = document.getElementById(gridId);
        if (!grid || !thumb) return;
        this.$thumb = thumb;
        grid.addEventListener('scroll', () => this.syncScrollbar(), { passive: true });
        new ResizeObserver(() => this.syncScrollbar()).observe(grid);
        this.syncScrollbar();
      },
      syncScrollbar() {
        const grid = this.$gridEl || document.getElementById(gridId);
        const thumb = this.$thumb;
        if (!grid || !thumb) return;
        const track = thumb.parentElement;
        if (!track) return;
        const trackW = track.clientWidth;
        const scrollable = grid.scrollWidth - grid.clientWidth;
        const ratio = scrollable > 0 ? trackW / grid.scrollWidth : 1;
        thumb.style.width = Math.max(20, trackW * ratio) + 'px';
        thumb.style.left = scrollable > 0
          ? (grid.scrollLeft / scrollable) * (trackW - thumb.offsetWidth) + 'px'
          : '0px';
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
        this.syncScrollbar();
      },
    }));

    // Category page tabs (welcome/featured/cross-sell sections): switch active tab,
    // lazy-load that category's products into the rail via the AJAX endpoint.
    Alpine.data('categoryTabs', (gridId, initialKey) => ({
      active: initialKey,
      loading: false,
      async pick(tab, el) {
        if (this.loading || !el || !tab.url || tab.url === '#' || this.active === tab.key) return;
        this.active = tab.key;
        this.loading = true;
        const grid = document.getElementById(gridId);
        grid?.classList.add('opacity-40', 'pointer-events-none');
        try {
          const base = tab.url.replace(/\/+$/, '');
          const r = await fetch(base + (base.includes('?') ? '&' : '?') + 'view=ajax', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
          });
          const d = await r.json();
          if (grid) grid.innerHTML = d.html;
        } catch (e) {
          if (grid) grid.innerHTML = '';
        }
        grid?.classList.remove('opacity-40', 'pointer-events-none');
        this.loading = false;
      },
    }));

    // Category product grid "Show More Products" (Load More): paginated AJAX append.
    // nextUrl / hasMore are read from data-* attributes to avoid quote-escaping issues
    // inside the HTML `x-data` attribute.
    Alpine.data('categoryLoadMore', () => ({
      loading: false,
      failed: false,
      nextUrl: null,
      hasMore: false,
      init() {
        this.nextUrl = this.$el.dataset.nextUrl || null;
        this.hasMore = this.$el.dataset.hasMore === '1';
      },
      loadMore() {
        if (!this.nextUrl || this.loading) return;
        this.loading = true;
        this.failed = false;
        fetch(this.nextUrl, {
          headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
        })
          .then(r => { if (!r.ok) throw new Error('Request failed'); return r.json() })
          .then(d => {
            this.$refs.grid.insertAdjacentHTML('beforeend', d.html);
            this.nextUrl = d.nextPageUrl;
            this.hasMore = d.hasMorePages;
          })
          .catch(() => { this.failed = true })
          .finally(() => { this.loading = false });
      },
    }));
  });

  // Standalone horizontal rail (combo/superfoods etc.): custom scrollbar thumb synced to a scrollable grid.
  document.addEventListener('alpine:init', () => {
    Alpine.data('railScroll', () => ({
      init() {
        this.$nextTick(() => {
          const grid = this.$refs.grid;
          const thumb = this.$refs.thumb;
          const track = thumb ? thumb.parentElement : null;
          if (!grid || !thumb || !track) return;
          const sync = () => {
            const trackW = track.clientWidth;
            const scrollable = grid.scrollWidth - grid.clientWidth;
            const ratio = scrollable > 0 ? trackW / grid.scrollWidth : 1;
            thumb.style.width = Math.max(20, trackW * ratio) + 'px';
            thumb.style.left = scrollable > 0
              ? (grid.scrollLeft / scrollable) * (trackW - thumb.offsetWidth) + 'px'
              : '0px';
          };
          grid.addEventListener('scroll', sync, { passive: true });
          new ResizeObserver(sync).observe(grid);
          sync();
        });
      },
    }));
  });
}
</script>