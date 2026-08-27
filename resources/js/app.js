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

Alpine.start()

// Let the shared cart store hydrate as soon as Alpine is ready.
document.addEventListener('alpine:init', () => {
    Alpine.store('cart').load()
})
