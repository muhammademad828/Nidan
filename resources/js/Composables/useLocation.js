import { computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

export function useLocation() {
    const page = usePage()

    const regions       = computed(() => page.props.regions ?? [])
    const currentRegion = computed(() => page.props.currentRegion ?? null)

    function setRegion(regionId) {
        router.post(
            route('region.set'),
            { region_id: regionId },
            { preserveScroll: true }
        )
    }

    function getDeliveryFee() {
        return currentRegion.value?.delivery_fee ?? 0
    }

    function formatFee(fee, currency = 'EGP') {
        if (fee === 0) return 'Free'
        return `${currency} ${fee}`
    }

    return { regions, currentRegion, setRegion, getDeliveryFee, formatFee }
}
