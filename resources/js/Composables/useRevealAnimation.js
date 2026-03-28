import { onMounted, onUnmounted } from 'vue'

/**
 * IntersectionObserver-based scroll reveal.
 * Adds .visible or .in-view class when element enters viewport.
 *
 * Usage:
 *   useRevealAnimation('.reveal, .reveal-start')
 *   useRevealAnimation('.product-card', 'in-view')
 */
export function useRevealAnimation(selector = '.reveal, .reveal-start', activeClass = 'visible') {
    let observer = null

    function init() {
        const els = document.querySelectorAll(selector)
        if (!els.length) return

        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add(activeClass)
                        observer.unobserve(entry.target)
                    }
                })
            },
            { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
        )

        els.forEach((el) => observer.observe(el))
    }

    onMounted(init)

    onUnmounted(() => {
        observer?.disconnect()
    })

    return { init }
}

/**
 * Product card reveal — separate from general reveal for stagger support.
 */
export function useProductReveal() {
    let observer = null

    function init() {
        const cards = document.querySelectorAll('.product-card')
        if (!cards.length) return

        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view')
                        observer.unobserve(entry.target)
                    }
                })
            },
            { threshold: 0.15 }
        )

        cards.forEach((c) => observer.observe(c))
    }

    onMounted(init)
    onUnmounted(() => observer?.disconnect())

    return { init }
}
