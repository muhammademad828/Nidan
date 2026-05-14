export function initScrollAnimations() {
    if (typeof window.gsap === 'undefined' || typeof window.ScrollTrigger === 'undefined') {
        return;
    }

    const { gsap, ScrollTrigger } = window;

    gsap.registerPlugin(ScrollTrigger);

    if (document.querySelector('#parallax-motifs')) {
        gsap.to('#parallax-motifs', {
            y: -100,
            ease: 'none',
            scrollTrigger: {
                trigger: '#heritage-section',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1,
            },
        });
    }

    if (document.querySelectorAll('.reveal-item').length > 0) {
        ScrollTrigger.batch('.reveal-item', {
            start: 'top 90%',
            onEnter: (batch) => gsap.to(batch, {
                opacity: 1,
                y: 0,
                stagger: 0.15,
                duration: 1.2,
                ease: 'power2.out',
                overwrite: true,
            }),
            once: true,
        });
    }

    document.querySelectorAll('.reveal:not(.reveal-item)').forEach((el) => {
        gsap.to(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                onEnter: () => el.classList.add('active'),
            },
        });
    });
}
