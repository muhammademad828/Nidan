import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

/**
 * Access the CMS data shared via Inertia middleware.
 * RULE: Every visible string in Vue templates MUST go through this composable.
 */
export function useCms() {
    const page = usePage()

    const settings = computed(() => page.props.settings ?? {})

    /** Public URL for branding logo, or null if unset (paths are under /storage). */
    const brandLogoSrc = computed(() => {
        const raw = settings.value?.branding?.logo
        if (raw == null || String(raw).trim() === '') {
            return null
        }
        const s = String(raw).trim()
        if (s.startsWith('http://') || s.startsWith('https://')) {
            return s
        }
        return `/storage/${s.replace(/^\/+/, '')}`
    })
    const sections = computed(() => page.props.sections ?? {})
    const content  = computed(() => page.props.content  ?? {})
    const seo      = computed(() => page.props.seo      ?? {})
    const locale   = computed(() => page.props.locale   ?? 'en')

    /**
     * Get a content block value by dot-notation key.
     * @param {string} key - e.g., 'hero.title', 'cart.drawer.empty_message'
     * @param {string} fallback - fallback default (development only)
     */
    function t(key, fallback = '') {
        return content.value[key] ?? fallback
    }

    /**
     * Get a page section object by key.
     * @param {string} key - e.g., 'hero', 'featured'
     */
    function getSection(key) {
        return sections.value?.[key] ?? {}
    }

    /**
     * Get a site setting value.
     * @param {string} group - e.g., 'general', 'social'
     * @param {string} key - e.g., 'whatsapp_number', 'site_name'
     */
    function getSetting(group, key, fallback = '') {
        return settings.value?.[group]?.[key] ?? fallback
    }

    return {
        settings,
        brandLogoSrc,
        sections,
        content,
        seo,
        locale,
        t,
        getSection,
        getSetting,
    }
}
