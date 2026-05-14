export function initMobileMenu() {
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (!toggle || !menu) {
        return;
    }

    toggle.addEventListener('click', () => menu.classList.toggle('active'));
}
