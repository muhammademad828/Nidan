/**
 * Format a price in Egyptian Pounds (EGP).
 * Supports both Arabic and English locales.
 *
 * @param {number} amount
 * @param {string} currency - defaults to 'EGP'
 * @param {string} locale   - 'ar-EG' or 'en-EG'
 * @returns {string}
 */
export function formatPrice(amount, currency = 'EGP', locale = 'en-EG') {
    return new Intl.NumberFormat(locale, {
        style:                 'currency',
        currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(amount ?? 0)
}

/**
 * Format price using the app's current locale and currency from settings.
 * Use this Vue-aware version in composables that have access to usePage().
 */
export function usePriceFormatter(pageProps) {
    const currency = pageProps?.settings?.general?.default_currency ?? 'EGP'
    const locale   = pageProps?.locale === 'ar' ? 'ar-EG' : 'en-EG'

    return {
        format: (amount) => formatPrice(amount, currency, locale),
        currency,
        symbol: currency === 'EGP' ? 'ج.م' : currency,
    }
}
