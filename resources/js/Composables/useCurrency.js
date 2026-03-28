/**
 * useCurrency — reactive currency formatter.
 * Reads default_currency from SiteSetting (shared via Inertia props).
 */
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useCurrency() {
    const page = usePage()

    const currency = computed(() =>
        page.props.settings?.general?.default_currency ?? 'EGP'
    )

    const locale = computed(() =>
        page.props.locale === 'ar' ? 'ar-EG' : 'en-EG'
    )

    /**
     * Format an amount as currency string.
     * @param {number} amount
     * @returns {string}  e.g. "١٢٠ ج.م" or "EGP 120"
     */
    function fmt(amount) {
        if (amount == null || isNaN(amount)) return '—'
        return new Intl.NumberFormat(locale.value, {
            style:                 'currency',
            currency:              currency.value,
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        }).format(amount)
    }

    return { fmt, currency, locale }
}
