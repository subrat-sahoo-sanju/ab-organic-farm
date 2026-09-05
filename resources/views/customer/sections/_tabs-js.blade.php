<script>
if (!window.__tabGridRegistered) {
  window.__tabGridRegistered = true;
  document.addEventListener('alpine:init', () => {
    // Shared tab grid (focus sections): underline switch + lazy load.
    Alpine.data('tabGrid', (gridId, initial = 'all') => ({
      active: initial,
      loading: false,
      gridId,
      async pick(id, url) {
        if (this.loading || this.active === id) return;
        this.active = id;
        this.loading = true;
        const grid = document.getElementById(this.gridId);
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
  // Also used by category "Featured Products" / "Cross Sell" sections (cat-tab-item markup).
  // On mobile the welcome grid becomes a 2-column vertical feed that loads more
  // products automatically as you scroll (infinite scroll).
  Alpine.data('welcomeTabs', (gridId, initialKey, tabs) => ({
    active: initialKey,
    loading: false,
    tabs,
    gridId,
    offset: 0,
    hasMore: false,
    _observer: null,
    mobileQuery: window.matchMedia('(max-width: 639px)'),
    init() {
      const grid = document.getElementById(this.gridId);
      if (grid && grid.classList.contains('welcome-grid')) {
        this.hasMore = grid.dataset.total !== undefined
          ? Number(grid.dataset.more || 0) > 0
          : false;
      }
      let tries = 0;
      const tryShow = () => {
        const btn = this.activeBtn();
        if (!btn) {
          if (tries++ < 25) setTimeout(tryShow, 60);
          return;
        }
        this.placeIndicator(btn);
        this.checkScrollHint();
      };
      this.$nextTick(() => setTimeout(tryShow, 0));
      this.$el.addEventListener('scroll', () => this.checkScrollHint(), true);
      window.addEventListener('resize', () => this.checkScrollHint());
      this.$nextTick(() => this.armObserver());
    },
    activeBtn() {
      const rail = this.$el.querySelector('.welcome-tabs-rail, .cat-welcome-tabs, [x-ref="rail"]');
      return rail
        ? rail.querySelector('.welcome-tab--active, .menu-nav-item.active, .cat-tab-item.active')
        : null;
    },
    get activeTab() {
      return this.tabs.find(t => t.key === this.active) || this.tabs[0] || null;
    },
    checkScrollHint() {
      this.showScrollHint = (() => {
        const rail = this.$el.querySelector('.welcome-tabs-rail, .cat-welcome-tabs');
        return rail ? rail.scrollWidth > rail.clientWidth + 10 : false;
      })();
    },
    showScrollHint: true,
    touchStartX: 0,
    placeIndicator(el) {
      const ind = this.$el.querySelector('.welcome-tab-indicator');
      if (!ind || !el) return;
      ind.style.opacity = '1';
      ind.style.width = el.offsetWidth + 'px';
      ind.style.transform = 'translateX(' + el.offsetLeft + 'px)';
    },
    onTouchStart(e) {
      this.touchStartX = e.changedTouches[0].screenX;
    },
    onTouchEnd(e) {
      this.touchEndX = e.changedTouches[0].screenX;
      const diff = this.touchStartX - this.touchEndX;
      if (Math.abs(diff) > 40 && this.tabs.length > 1) {
        const currentIdx = this.tabs.findIndex(t => t.key === this.active);
        const rails = this.$el.querySelectorAll('.welcome-tabs-rail, .cat-welcome-tabs');
        const rail = rails[rails.length - 1];
        if (!rail) return;
        const btns = rail.querySelectorAll('.welcome-tab, .cat-tab-item, .menu-nav-item');
        if (diff > 0 && currentIdx < this.tabs.length - 1) {
          this.pick(this.tabs[currentIdx + 1], btns[currentIdx + 1]);
        } else if (diff < 0 && currentIdx > 0) {
          this.pick(this.tabs[currentIdx - 1], btns[currentIdx - 1]);
        }
      }
    },
    armObserver() {
      if (this.mobileQuery.matches && !this._observer) {
        const sentinel = this.$el.querySelector('[data-tab-sentinel]');
        const grid = document.getElementById(this.gridId);
        if (sentinel && grid && grid.classList.contains('welcome-grid')) {
          this._observer = new IntersectionObserver(entries => {
            if (entries.some(e => e.isIntersecting)) this.loadMore();
          }, { rootMargin: '200px 0px' });
          this._observer.observe(sentinel);
        }
      }
    },
    async pick(tab, el) {
      if (this.loading || !el || this.active === tab.key) return;
      this.active = tab.key;
      this.placeIndicator(el);
      const grid = document.getElementById(this.gridId);
      if (tab.url) {
        this.loading = true;
        if (grid) {
          grid.setAttribute('aria-busy', 'true');
          grid.innerHTML = this.skeletonGrid();
        }
        try {
          const full = new URL(tab.url, location.origin);
          full.searchParams.set('offset', '0');
          const r = await fetch(full.toString(), { headers: { 'Accept': 'application/json' } });
          if (!r.ok) throw new Error('Request failed');
          const d = await r.json();
          if (grid) {
            grid.innerHTML = d.html;
            window.AnvBoot(grid);
          }
          this.offset = d.count || 0;
          this.hasMore = !!d.hasMore;
        } catch (e) {
          if (grid) grid.innerHTML = '<div class="welcome-empty">Couldn&#39;t load products. Please try again.</div>';
        }
        if (grid) grid.removeAttribute('aria-busy');
        this.loading = false;
        this.armObserver();
      }
      this.$nextTick(() => this.placeIndicator(el));
    },
    skeletonGrid() {
      let html = '';
      for (let i = 0; i < 4; i++) {
        html += '<div class="welcome-skeleton-card"><div class="welcome-skeleton-img"></div><div class="welcome-skeleton-line"></div><div class="welcome-skeleton-line short"></div></div>';
      }
      return html;
    },
    async loadMore() {
      const tab = this.tabs.find(t => t.key === this.active);
      const grid = document.getElementById(this.gridId);
      if (!tab?.url || !grid || this.loading || !this.hasMore) return;
      this.loading = true;
      const sentinel = this.$el.querySelector('[data-tab-sentinel]');
      sentinel?.classList.add('is-active');
      try {
        const full = new URL(tab.url, location.origin);
        full.searchParams.set('offset', String(this.offset));
        const r = await fetch(full.toString(), { headers: { 'Accept': 'application/json' } });
        if (!r.ok) throw new Error('Request failed');
        const d = await r.json();
        grid.insertAdjacentHTML('beforeend', d.html);
        window.AnvBoot(grid);
        this.offset += d.count || 0;
        this.hasMore = !!d.hasMore;
      } catch (e) {
        /* silently stop infinite loading on error */
      }
      sentinel?.classList.remove('is-active');
      this.loading = false;
    },
  }));

    // Category page tabs (welcome/featured/cross-sell sections): switch active tab,
    // lazy-load that category's products into the rail via the AJAX endpoint.
    Alpine.data('categoryTabs', (gridId, initialKey) => ({
      active: initialKey,
      loading: false,
      gridId,
      async pick(tab, el) {
        if (this.loading || !el || !tab.url || tab.url === '#' || this.active === tab.key) return;
        this.active = tab.key;
        this.loading = true;
        const grid = document.getElementById(this.gridId);
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

    // Category welcome intro: admin-configured tabs each with their own pre-rendered
    // product rail. Switches the visible rail client-side (no lazy fetch needed).
    Alpine.data('welcomeRail', (railId, initialKey) => ({
      active: initialKey,
      init() {
        // Defer the sliding-indicator placement until the DOM has settled.
        let tries = 0;
        const tryShow = () => {
          const btn = this.$refs.rail?.querySelector('.cat-tab-item.active');
          if (!btn) { if (tries++ < 25) setTimeout(tryShow, 60); return; }
          const ind = this.$refs.indicator;
          if (ind) {
            ind.style.opacity = '1';
            ind.style.width = btn.offsetWidth + 'px';
            ind.style.transform = 'translateX(' + btn.offsetLeft + 'px)';
          }
        };
        this.$nextTick(() => setTimeout(tryShow, 0));
      },
      pick(key, el) {
        if (this.active === key || !el) return;
        this.active = key;
        const ind = this.$refs.indicator;
        if (ind) {
          ind.style.opacity = '1';
          ind.style.width = el.offsetWidth + 'px';
          ind.style.transform = 'translateX(' + el.offsetLeft + 'px)';
        }
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