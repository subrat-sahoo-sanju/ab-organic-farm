import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.store('toast', {
    items: [],
    push(message, type = 'success') {
        const id = Date.now() + Math.random()
        this.items.push({ id, message, type })
        setTimeout(() => this.remove(id), 3500)
    },
    remove(id) {
        this.items = this.items.filter(t => t.id !== id)
    },
})

const csrf = () => {
    const m = document.querySelector('meta[name="csrf-token"]')
    return m ? m.content : ''
}

const toast = (msg, type = 'success') => Alpine.store('toast').push(msg, type)

// Shared, reactive cart store — powers header/mobile badges, product-card
// steppers and the cart page without any page refresh.
Alpine.store('cart', {
    count: 0,
    qtys: {},       // product_variant_id => qty
    items: {},      // product_variant_id => cart_item_id

    init() {
        this.load()
    },

    async load() {
        try {
            const res = await fetch('/cart/state', { headers: { Accept: 'application/json' } })
            if (!res.ok) return
            const d = await res.json()
            this.count = d.count || 0
            this.qtys = d.qtys || {}
            this.items = d.items || {}
            this.total = d.total
        } catch (e) {
            /* ignore */
        }
    },

    qtyOf(variantId) {
        return this.qtys[variantId] || 0
    },

    async add(variantId) {
        return this._call('/cart/add', { variant_id: variantId, quantity: 1 }, 'Add to cart')
    },

    // Set an exact quantity for a variant (used by +/- steppers).
    async setQty(variantId, itemId, qty) {
        if (qty <= 0) return this.removeItem(itemId)
        return this._call('/cart/items/' + itemId, { quantity: qty }, null, 'PATCH')
    },

    async removeItem(itemId) {
        return this._call('/cart/items/' + itemId, null, null, 'DELETE')
    },

    async _call(url, body, successMsg, method = 'POST') {
        const opts = {
            method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf(),
                Accept: 'application/json',
            },
        }
        if (body) {
            opts.headers['Content-Type'] = 'application/json'
            opts.body = JSON.stringify(body)
        }
        const res = await fetch(url, opts)
        let data = {}
        try { data = await res.json() } catch (e) { /* ignore */ }

        if (!res.ok) {
            const msg = data.message || data.error || 'Something went wrong'
            toast(msg, 'error')
            throw new Error(msg)
        }

        if (typeof data.count !== 'undefined') this.count = data.count
        await this.load()
        if (successMsg) toast(successMsg)
        return data
    },
})

// Global flash helper — reads data attributes from body
document.addEventListener('DOMContentLoaded', () => {
    const flashes = document.getElementById('flash-data')
    if (flashes) {
        if (flashes.dataset.success) toast(flashes.dataset.success)
        if (flashes.dataset.error) toast(flashes.dataset.error, 'error')
    }
})

// Let the shared cart store hydrate as soon as Alpine is ready.
document.addEventListener('alpine:init', () => {
    Alpine.store('cart').load()
})

/* ═══════════════════════════════════════════════════════════════════
   Anveshan-style cart engine (quick-add steppers, variant modal, fly)
   ═══════════════════════════════════════════════════════════════════ */

// Adjust the cart by a signed delta for a given variant (additive backend).
const adjustCart = async (variants, delta) => {
    const store = Alpine.store('cart')
    const vid = String(variants)
    const qty = store.qtyOf(variants)

    if (delta > 0 && qty === 0) {
        flyToCart()
        setTimeout(() => window.dispatchEvent(new CustomEvent('anv:cart-drawer-open')), 520)
    }
    if (qty + delta <= 0) {
        const itemId = store.items[variants]
        if (itemId) {
            const prev = qty
            await store.removeItem(itemId)
            store.qtys[variants] = 0
            store.count = Math.max(0, store.count - prev)
            store.items[variants] = null
        }
        toast('Removed from cart')
    } else {
        await store._call('/cart/add', { variant_id: variants, quantity: delta }, null)
    }
    refreshReactive()
    window.dispatchEvent(new CustomEvent('anv:qty-changed', { detail: { variants, qty: store.qtyOf(variants) } }))
}

// Pull the reactive store values into Alpine storage (qtys object identity).
const refreshReactive = () => {
    const store = Alpine.store('cart')
    document.dispatchEvent(new CustomEvent('anv:cart-refresh', { detail: store }))
}

const flyToCart = () => {
    const btn = document.querySelector('button[data-fly]')
    const badge = document.querySelector('[data-cart-anchor]')
    if (!badge) return
    const el = document.createElement('span')
    el.className = 'floating-cart-fly fixed h-5 w-5 rounded bg-anv-600'
    el.style.left = (btn ? btn.getBoundingClientRect().left : innerWidth / 2) + 'px'
    el.style.top = (btn ? btn.getBoundingClientRect().top : innerHeight / 2) + 'px'
    document.body.appendChild(el)
    const end = badge.getBoundingClientRect()
    el.animate(
        [
            { transform: 'translate(0,0) scale(1)', opacity: 1 },
            { transform: `translate(${end.left - 20}px,${end.top - 20}px) scale(0.2)`, opacity: 0.4 },
        ],
        { duration: 480, easing: 'cubic-bezier(.22,1,.36,1)' }
    ).onfinish = () => el.remove()
}

window.AnvCart = {
    plus(variants) {
        const store = Alpine.store('cart')
        return adjustCart(variants, 1).catch(err => {
            if (err && /stock|available|sold out/i.test(err.message || '')) {
                const info = window.__lastAdd
                if (info) window.dispatchEvent(new CustomEvent('open-notify', { detail: info }))
            }
        })
    },
    minus(variants) {
        const store = Alpine.store('cart')
        const cur = store.qtyOf(variants) || 0
        if (cur > 0) return adjustCart(variants, -1).catch(() => {})
    },
}

// Pop the header / mobile cart badge whenever any quantity changes.
window.addEventListener('anv:qty-changed', () => {
    document.querySelectorAll('.cart-badge').forEach(badge => {
        badge.classList.remove('anv-bump')
        void badge.offsetWidth
        badge.classList.add('anv-bump')
    })
})

// Per-card stepper: reactive qty bound to the global cart store.
Alpine.data('anvStepper', (vid, initial) => ({
    qty: Number(initial || 1),
    init() {
        const store = Alpine.store('cart')
        this.qty = store.qtyOf(vid)
        window.addEventListener('anv:qty-changed', e => {
            if (String(e.detail.variants) === String(vid)) {
                this.qty = e.detail.qty || 0
                this.pop()
            }
        })
        window.addEventListener('anv:cart-refresh', () => {
            this.qty = Alpine.store('cart').qtyOf(vid)
        })
    },
    pop() {
        const pill = this.$root?.querySelector('.anv-qty-pill')
        if (!pill) return
        pill.classList.remove('anv-qty-pop')
        void pill.offsetWidth
        pill.classList.add('anv-qty-pop')
    },
}))

/* ── Variant selector modal ───────────────────────────────────────── */
Alpine.data('variantModal', () => ({
    open: false,
    name: '',
    variants: [],
    init() {
        window.addEventListener('open-variant', (e) => {
            this.name = e.detail.name || ''
            this.variants = e.detail.variants || []
            this.open = true
            document.body.style.overflow = 'hidden'
        })
    },
    close() {
        this.open = false
        document.body.style.overflow = ''
    },
}))

Alpine.data('variantRow', (v, available) => ({
    qty: Alpine.store('cart').qtyOf(v.id),
    ok: available,
    init() {
        window.addEventListener('anv:qty-changed', e => {
            if (String(e.detail.variants) === String(v.id)) this.qty = e.detail.qty || 0
        })
    },
    add() {
        this.ok && window.AnvCart.plus(v.id)
    },
    minus() {
        this.qty > 0 && window.AnvCart.minus(v.id)
    },
}))

/* ── Notify-me modal ──────────────────────────────────────────────── */
Alpine.data('notifyModal', () => ({
    open: false,
    name: '',
    slug: '',
    email: '',
    submitted: false,
    busy: false,
    error: '',
    init() {
        window.addEventListener('open-notify', (e) => {
            this.name = e.detail.name || ''
            this.slug = e.detail.slug || ''
            this.submitted = false
            this.email = ''
            this.error = ''
            this.open = true
            document.body.style.overflow = 'hidden'
            this.$nextTick(() => this.$refs?.email?.focus())
        })
    },
    close() {
        this.open = false
        this.submitted = false
        document.body.style.overflow = ''
    },
    async submit() {
        if (!this.email) return
        this.busy = true
        this.error = ''
        try {
            const res = await fetch('/notify-me', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({ email: this.email, product: this.name, slug: this.slug }),
            })
            const data = await res.json()
            if (!res.ok) throw new Error(data.message || 'Failed')
            this.submitted = true
        } catch (err) {
            this.error = err.message
        } finally {
            this.busy = false
        }
    },
}))

/* ── Cart drawer (slide-in mini-cart) ──────────────────────────────── */
Alpine.data('cartDrawer', (freeAbove = 499) => ({
    open: false,
    freeAbove,
    lines: [],
    total: 0,
    count: 0,
    loading: false,
    init() {
        window.addEventListener('anv:cart-drawer-open', () => this.loadAndOpen())
        window.addEventListener('anv:cart-refresh', () => { if (this.open) this.loadDrawer() })
    },
    toggle() {
        if (this.open) this.close()
        else this.loadAndOpen()
    },
    async loadAndOpen() {
        this.open = true
        document.body.style.overflow = 'hidden'
        await this.loadDrawer()
    },
    close() {
        this.open = false
        document.body.style.overflow = ''
    },
    async loadDrawer() {
        this.loading = true
        try {
            const res = await fetch('/cart/drawer', { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } })
            if (!res.ok) return
            const d = await res.json()
            this.lines = d.lines || []
            this.total = d.total || 0
            this.count = d.count || 0
        } catch (e) { /* ignore */ } finally {
            this.loading = false
        }
    },
    async setQty(line, delta) {
        const newQty = line.quantity + delta
        const store = Alpine.store('cart')
        if (newQty <= 0) {
            await store.removeItem(line.id)
            this.lines = this.lines.filter(l => l.id !== line.id)
        } else {
            await store.setQty(line.variant_id, line.id, newQty)
            await this.loadDrawer()
        }
        await store.load()
    },
    async remove(line) {
        const store = Alpine.store('cart')
        await store.removeItem(line.id)
        this.lines = this.lines.filter(l => l.id !== line.id)
        await store.load()
        if (this.lines.length === 0) this.close()
    },
}))

/* ── Hero slideshow ───────────────────────────────────────────────── */
Alpine.data('heroSlider', (count) => ({
    active: 0,
    paused: false,
    timer: null,
    init() {
        this.play()
    },
    next() {
        this.active = (this.active + 1) % count
        this.play()
    },
    prev() {
        this.active = (this.active - 1 + count) % count
        this.play()
    },
    go(i) {
        this.active = i
        this.play()
    },
    play() {
        clearInterval(this.timer)
        if (this.paused) return
        this.timer = setInterval(() => {
            this.active = (this.active + 1) % count
        }, 5000)
    },
    toggle() {
        this.paused = !this.paused
        if (this.paused) clearInterval(this.timer)
        else this.play()
    },
}))

/* ── Tabs + sliding indicator ─────────────────────────────────────── */
Alpine.data('anvTabs', () => ({
    active: null,
    init() {
        window.addEventListener('anv:tab-pick', (e) => {
            if (this.$root.contains(e.detail.btn)) this.active = e.detail.key
        })
    },
}))

/* ── Reviews slider ───────────────────────────────────────────────── */
Alpine.data('reviewsSlider', () => ({
    index: 0,
    per: 1,
    init() {
        this.measure()
        window.addEventListener('resize', () => this.measure())
    },
    measure() {
        const w = this.$root.closest('[data-rail]')?.clientWidth || 1200
        this.per = w >= 1024 ? 3 : w >= 640 ? 2 : 1
    },
    prev() {
        this.index = Math.max(0, this.index - 1)
    },
    next() {
        this.index = Math.min(this.$root.children.length - this.per, this.index + 1)
    },
}))

/* ── Generic slide carousel (portrait/any image rails, transform-based) ── */
Alpine.data('slideCarousel', () => ({
    index: 0,
    per: 1,
    touchStartX: 0,
    touchEndX: 0,
    init() {
        this.measure()
        window.addEventListener('resize', () => this.measure())
        this.index = Math.min(this.index, this.maxIndex())
    },
    measure() {
        const el = this.$refs.track
        this.per = 1
        if (!el) return
        const first = el.querySelector(':scope > *')
        const gap = 12
        const railW = el.parentElement?.clientWidth || el.clientWidth || 1200
        const itemW = first ? first.getBoundingClientRect().width : railW
        this.per = Math.max(1, Math.round((railW + gap) / (itemW + gap)))
        this.index = Math.min(this.index, this.maxIndex())
    },
    maxIndex() {
        const el = this.$refs.track
        return Math.max(0, (el?.children.length || 1) - this.per)
    },
    prev() {
        this.index = Math.max(0, this.index - 1)
    },
    next() {
        this.index = Math.min(this.maxIndex(), this.index + 1)
    },
    handleTouchStart(e) {
        this.touchStartX = e.changedTouches[0].screenX
    },
    handleTouchEnd(e) {
        this.touchEndX = e.changedTouches[0].screenX
        this.handleSwipe()
    },
    handleSwipe() {
        const diff = this.touchStartX - this.touchEndX
        if (Math.abs(diff) > 50) {
            if (diff > 0) this.next()
            else this.prev()
        }
    },
}))

/* ── Tab grids (welcome / focus) with sliding bar ─────────────────── */
Alpine.data('tabGrid', (gridId, initial) => {
    const grid = () => document.getElementById(gridId)
    let initSnapshot = ''
    return {
        active: initial,
        loading: false,
        init() {
            initSnapshot = grid()?.innerHTML || ''
        },
        async pick(url, key) {
            this.active = key
            const el = grid()
            if (!el) return
            if (!url) {
                el.innerHTML = initSnapshot
                return
            }
            el.innerHTML = Array.from({ length: 4 })
                .map(() => '<div class="aspect-square w-full animate-pulse rounded-2xl bg-sage-100"></div>')
                .join('')
            this.loading = true
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } })
                const d = await res.json()
                el.innerHTML = d.html || ''
            } catch (e) {
                el.innerHTML = initSnapshot
            } finally {
                this.loading = false
            }
        },
    }
})

Alpine.data('headerSearch', () => ({
    drawer: false,
    search: false,
    q: '',
    results: [],
    categories: [],
    loading: false,
    popular: ['Ghee', 'Cold Pressed Oil', 'Atta', 'Honey', 'Jaggery', 'Superfoods'],

    openSearch() {
        this.search = true
        this.q = ''
        this.results = []
        this.$nextTick(() => this.$refs.searchInput && this.$refs.searchInput.focus())
    },

    closeSearch() {
        this.search = false
    },

    async runSearch() {
        const term = (this.q || '').trim()
        if (term.length < 2) {
            this.results = []
            this.categories = []
            this.loading = false
            return
        }
        this.loading = true
        try {
            const res = await fetch('/api/search-suggest?q=' + encodeURIComponent(term), {
                headers: { Accept: 'application/json' },
            })
            if (!res.ok) throw new Error('bad status')
            const data = await res.json()
            this.results = data.products || []
            this.categories = data.categories || []
        } catch (e) {
            this.results = []
        } finally {
            this.loading = false
        }
    },

    goSearch() {
        const term = (this.q || '').trim()
        this.closeSearch()
        window.location.href = '/search?q=' + encodeURIComponent(term)
    },
}))

// Format numbers as Indian Rupees (no decimals when whole)
const fmtINR = (n) => {
    const v = Number(n || 0)
    return '₹' + new Intl.NumberFormat('en-IN', {
        maximumFractionDigits: Number.isInteger(v) ? 0 : 2,
    }).format(v)
}

/* ── Real-time live panel (delivery dashboard & admin delivery) ──────
   Polls a lightweight JSON endpoint on a short interval and keeps the
   page's stats/notifications fresh without any manual refresh.         */
Alpine.data('livePanel', (cfg = {}) => ({
    url: cfg.url || '/portal/live',
    interval: cfg.interval || 8000,
    countdown: 0,
    countdownTarget: cfg.countdown || 5,
    since: 0,
    lastVersion: -1,
    seenIds: {},
    stats: {},
    newOrders: [],
    connected: false,
    errors: 0,
    pulseClass: '',
    _timer: null,
    _lastActivity: Date.now(),
    _dismissed: false,

    init() {
        const self = this
        this._onActivity = () => { self._lastActivity = Date.now() }
        window.addEventListener('keydown', this._onActivity)
        window.addEventListener('mousedown', this._onActivity)
        window.addEventListener('touchstart', this._onActivity)
        window.addEventListener('visibilitychange', () => {
            window.document.visibilityState == 'visible' && self.poll()
        })
        this.poll()
        this._timer = window.setInterval(() => self.poll(), this.interval)
    },

    destroy() {
        if (this._timer) window.clearInterval(this._timer)
        if (this._countTimer) window.clearInterval(this._countTimer)
        window.removeEventListener('keydown', this._onActivity)
        window.removeEventListener('mousedown', this._onActivity)
        window.removeEventListener('touchstart', this._onActivity)
    },

    async poll() {
        try {
            const sep = this.url.includes('?') ? '&' : '?'
            const res = await fetch(this.url + sep + 'since=' + this.since, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
            })
            if (!res.ok) throw new Error('poll ' + res.status)
            const d = await res.json()
            this.errors = 0
            this.connected = true

            // Pulse any tile whose value changed.
            const changed = []
            for (const k in (d.stats || {})) {
                const nv = Number(d.stats[k] || 0)
                if (this.stats[k] !== undefined && this.stats[k] !== nv) changed.push(k)
                this.stats[k] = nv
            }
            if (changed.length) {
                const key = changed[0]
                this.pulseClass = 'pulse-' + key
                setTimeout(() => { if (this.pulseClass === 'pulse-' + key) this.pulseClass = '' }, 1500)
            }

            // First successful poll seeds without notifying.
            const isFirst = this.lastVersion < 0
            const version = Number(d.version || 0)
            this.lastVersion = isFirst ? version : this.lastVersion
            this.since = Math.max(this.since, version)

            // Brand-new (not previously seen, still to pick up) assignments.
            let freshArr = d.new || []
            let trulyNew = []
            if (!isFirst) {
                trulyNew = freshArr.filter(n => n.status === 'assigned' && !this.seenIds[String(n.id)])
            }
            freshArr.forEach(n => { this.seenIds[String(n.id)] = true })

            if (typeof cfg.onPoll === 'function') {
                try { cfg.onPoll.call(this, d, freshArr, trulyNew) } catch (e) { /* void */ }
            }

            // Show the live banner + start an auto-refresh countdown.
            if (trulyNew.length) {
                this.newOrders = trulyNew
                if (!this._dismissed) this.beginCountdown()
            }
        } catch (e) {
            this.errors = Math.min(this.errors + 1, 999)
        }
    },

    beginCountdown() {
        if (this._countTimer) window.clearInterval(this._countTimer)
        this.countdown = this.countdownTarget
        this._pulseAt = Date.now()
        this._countTimer = window.setInterval(() => {
            if (this._dismissed) {
                window.clearInterval(this._countTimer); this._countTimer = null; return
            }
            this.countdown--
            if (this.countdown <= 0) {
                window.clearInterval(this._countTimer); this._countTimer = null
                // Auto-refresh only if the courier has been idle, so we never
                // reload while they are typing or tapping.
                if (Date.now() - this._lastActivity > 20000) window.location.reload()
                else this._dismissed = true
            }
        }, 1000)
    },

    fmt: fmtINR,
    pulse(key) {
        return this.pulseClass === 'pulse-' + key ? 'anv-pulse-live' : ''
    },
    hasNew() {
        return this.newOrders.length && !this._dismissed
    },
    dismiss() {
        this._dismissed = true
        this.countdown = 0
        if (this._countTimer) { window.clearInterval(this._countTimer); this._countTimer = null }
    },
}))

/* ── Brand loader ─────────────────────────────────────────────────────
   Shows an elegant logo loader only when a page navigation actually takes
   time. Takes: intercept internal <a> clicks / form submits, wait ~180ms,
   show the overlay; if navigation is instant the page unloads first and
   the loader never flashes. Re-hooked on every page load.                 */
const initBrandLoader = () => {
    const el = document.getElementById('brand-loader')
    if (!el) return
    let t = null

    const show = () => el.classList.add('is-visible')
    const hide = () => {
        el.classList.remove('is-visible')
        clearTimeout(t)
    }

    const isInternal = (href) => {
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return false
        try {
            const u = new URL(href, location.href)
            return u.host === location.host || u.host === ''
        } catch (e) { return false }
    }

    const schedule = (link) => {
        if (!link || link.hasAttribute('data-no-loader')) return
        clearTimeout(t)
        t = setTimeout(show, 180)
    }

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a')
        if (!a || !isInternal(a.getAttribute('href'))) return
        if (a.target && a.target !== '_self') return
        if (a.getAttribute('download') !== null) return
        schedule(a)
    }, true)

    document.addEventListener('submit', (e) => {
        const f = e.target
        if (!(f instanceof HTMLFormElement)) return
        if (f.dataset.noloader !== undefined) return
        const method = ((f.method || 'get')).toLowerCase()
        const action = f.getAttribute('action')
        if (method === 'get' && action && isInternal(action)) schedule(f)
    }, true)

    window.addEventListener('pagehide', hide)
    window.addEventListener('pageshow', hide)
    window.addEventListener('load', () => setTimeout(hide, 250))
}

document.addEventListener('DOMContentLoaded', initBrandLoader)

// Boot any Alpine components inside freshly injected HTML (tab grids, load-more feeds).
window.AnvBoot = (scope) => {
    if (scope && window.Alpine && typeof Alpine.initTree === 'function') {
        try { Alpine.initTree(scope) } catch (e) { /* ignore */ }
    }
}

/* ── Horizontal rail enhancer ─────────────────────────────────────────
   Brands every product rail (.welcome-grid / .rail-scroll /
   .cat-products-scroll / .menu-product-grid / .rv-horizontal-scroll) so
   users SEE there is a side scroll:
     • thin branded scrollbar on desktop/laptop
     • soft edge fade (.has-overflow) whenever content overflows
     • drag-to-scroll with a mouse (click-drag like a native carousel)
   Re-applies automatically when tabs swap / items append (MutationObserver). */
const RAIL_SELECTOR = '.welcome-grid, .rail-scroll, .cat-products-scroll, .menu-product-grid, .rv-horizontal-scroll'

const decorateRails = () => {
    document.querySelectorAll(RAIL_SELECTOR).forEach(el => el.classList.add('anv-rail'))
}

const refreshRails = () => {
    document.querySelectorAll('.anv-rail').forEach(el => {
        el.classList.toggle('has-overflow', el.scrollWidth > el.clientWidth + 8)
    })
}

let railDrag = null
document.addEventListener('pointerdown', (e) => {
    if (e.pointerType !== 'mouse' || e.button !== 0) return
    const rail = e.target.closest(RAIL_SELECTOR)
    if (!rail) return
    railDrag = { el: rail, x: e.clientX, sl: rail.scrollLeft, moved: false, engaged: false }
}, true)
document.addEventListener('pointermove', (e) => {
    if (!railDrag) return
    const dx = e.clientX - railDrag.x
    if (Math.abs(dx) > 4) {
        if (!railDrag.engaged) {
            railDrag.engaged = true
            railDrag.el.classList.add('anv-rail--dragging')
            document.documentElement.classList.add('anv-dragging')
        }
        railDrag.moved = true
    }
    railDrag.el.scrollLeft = railDrag.sl - dx
}, true)
document.addEventListener('pointerup', () => {
    if (!railDrag) return
    if (railDrag.engaged) {
        railDrag.el.classList.remove('anv-rail--dragging')
        document.documentElement.classList.remove('anv-dragging')
    }
    const d = railDrag
    railDrag = null
    if (d.moved) {
        document.addEventListener('click', function swallow(e) {
            e.preventDefault()
            e.stopPropagation()
            document.removeEventListener('click', swallow, true)
        }, true)
    }
}, true)
document.addEventListener('pointercancel', () => {
    if (railDrag) {
        if (railDrag.engaged) {
            railDrag.el.classList.remove('anv-rail--dragging')
            document.documentElement.classList.remove('anv-dragging')
        }
        railDrag = null
    }
}, true)

document.addEventListener('DOMContentLoaded', () => {
    decorateRails()
    refreshRails()
    window.addEventListener('resize', refreshRails)
    const mo = new MutationObserver(() => {
        decorateRails()
        refreshRails()
    })
    mo.observe(document.body, { childList: true, subtree: true })
})

// Start Alpine only after every component is registered.
Alpine.start()
