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

// Global flash helper — reads data attributes from body
document.addEventListener('DOMContentLoaded', () => {
    const flashes = document.getElementById('flash-data')
    if (flashes) {
        if (flashes.dataset.success) Alpine.store('toast').push(flashes.dataset.success, 'success')
        if (flashes.dataset.error) Alpine.store('toast').push(flashes.dataset.error, 'error')
    }
})

Alpine.start()
