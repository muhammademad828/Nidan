export function initHomeSlider() {
    const slides = document.querySelectorAll('.testimonial-slide');
    const dots = document.querySelectorAll('.slider-dot');

    if (!slides.length || !dots.length) {
        return;
    }

    let currentSlide = 0;

    function goToSlide(index) {
        if (!slides[index] || !dots[index]) {
            return;
        }

        slides.forEach((slide) => slide.classList.remove('active'));
        dots.forEach((dot) => dot.classList.remove('active'));

        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index));
    });

    setInterval(() => {
        const next = (currentSlide + 1) % slides.length;
        goToSlide(next);
    }, 5000);
}
