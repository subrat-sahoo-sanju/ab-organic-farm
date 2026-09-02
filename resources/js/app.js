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
        const cur = store.qtyOf(variants) || 0
        return adjustCart(variants, cur === 0 ? 1 : 1)
    },
    minus(variants) {
        const store = Alpine.store('cart')
        const cur = store.qtyOf(variants) || 0
        if (cur > 0) return adjustCart(variants, -1)
    },
}

// Per-card stepper: reactive qty bound to the global cart store.
Alpine.data('anvStepper', (vid, initial) => ({
    qty: Number(initial || 1),
    init() {
        const store = Alpine.store('cart')
        this.qty = store.qtyOf(vid)
        window.addEventListener('anv:qty-changed', e => {
            if (String(e.detail.variants) === String(vid)) this.qty = e.detail.qty || 0
        })
        window.addEventListener('anv:cart-refresh', () => {
            this.qty = Alpine.store('cart').qtyOf(vid)
        })
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

// Start Alpine only after every component is registered.
Alpine.start()
