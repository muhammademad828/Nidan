/**
 * useCart — fully reactive cart using fetch (no Inertia page reload).
 * The shared Inertia `cart` prop is the SSR initial state.
 * All mutations go through JSON endpoints and update local state directly.
 */
import { ref, computed } from 'vue'
import { usePage as _usePage } from '@inertiajs/vue3'

/* ── Module-level reactive state (shared across all component instances) ── */
const isOpen  = ref(false)
const loading = ref(false)
const _cart   = ref(null) // null = not yet overridden from SSR

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? ''
}

async function apiCall(method, url, body = null) {
    const opts = {
        method,
        headers: {
            'Accept':       'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
        },
    }
    if (body) opts.body = JSON.stringify(body)
    const res = await fetch(url, opts)
    if (!res.ok) throw new Error(`Cart API error ${res.status}`)
    return res.json()
}

export function useCart() {
    const page = _usePage()

    /* The Inertia SSR prop is the initial source of truth.
       Once a mutation fires, _cart takes over reactively. */
    const cartData = computed(() =>
        _cart.value ?? page.props.cart ?? { items: [], subtotal: 0, item_count: 0 }
    )

    const items     = computed(() => cartData.value.items     ?? [])
    const subtotal  = computed(() => cartData.value.subtotal  ?? 0)
    const itemCount = computed(() => cartData.value.item_count ?? 0)

    function open()   { isOpen.value = true  }
    function close()  { isOpen.value = false }
    function toggle() { isOpen.value = !isOpen.value }

    /* ── Mutations ── */

    async function addToCart(productId, variationId = null, qty = 1, addons = []) {
        loading.value = true
        try {
            const data = await apiCall('POST', '/cart/add', {
                product_id:   productId,
                variation_id: variationId,
                quantity:     qty,
                addons,
            })
            _cart.value = data
            isOpen.value = true
        } catch (e) {
            console.error('addToCart error', e)
        } finally {
            loading.value = false
        }
    }

    async function updateQty(cartItemId, qty) {
        // Optimistic: already applied by CartDrawer via localQty
        try {
            const data = await apiCall('PATCH', `/cart/update/${cartItemId}`, { quantity: qty })
            _cart.value = data
        } catch (e) {
            console.error('updateQty error', e)
        }
    }

    async function removeItem(cartItemId) {
        try {
            const data = await apiCall('DELETE', `/cart/remove/${cartItemId}`)
            _cart.value = data
        } catch (e) {
            console.error('removeItem error', e)
        }
    }

    async function addBundle(bundleId) {
        loading.value = true
        try {
            const data = await apiCall('POST', '/cart/bundle', { bundle_id: bundleId })
            _cart.value = data
            isOpen.value = true
        } catch (e) {
            console.error('addBundle error', e)
        } finally {
            loading.value = false
        }
    }

    function formatPrice(amount, currency = null) {
        const cur = currency ?? page.props.settings?.general?.default_currency ?? 'EGP'
        const loc = page.props.locale === 'ar' ? 'ar-EG' : 'en-EG'
        return new Intl.NumberFormat(loc, {
            style:                 'currency',
            currency:              cur,
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        }).format(amount)
    }

    return {
        isOpen,
        loading,
        cartData,
        items,
        subtotal,
        itemCount,
        open,
        close,
        toggle,
        addToCart,
        updateQty,
        removeItem,
        addBundle,
        formatPrice,
    }
}
