import { reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { debounce } from '@/Utils/debounce'

export function useFilters() {
    const page = usePage()

    const filters = reactive({
        category: page.props.filters?.category ?? '',
        price:    page.props.filters?.price    ?? '',
        sort:     page.props.filters?.sort     ?? 'featured',
    })

    const applyFilters = debounce(() => {
        const params = Object.fromEntries(
            Object.entries(filters).filter(([, v]) => v !== '' && v !== null)
        )
        router.get(
            route('products.index'),
            params,
            { preserveState: true, replace: true }
        )
    }, 300)

    function setFilter(key, value) {
        filters[key] = value
        applyFilters()
    }

    function clearFilters() {
        filters.category = ''
        filters.price    = ''
        filters.sort     = 'featured'
        applyFilters()
    }

    return { filters, setFilter, clearFilters }
}
