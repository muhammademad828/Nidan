import './bootstrap';
import { initMobileMenu } from './front/menu';
import { initScrollAnimations } from './front/animations';
import { initHomeSlider } from './front/home';
import { initAddToCart } from './front/product';
import { initCartActions } from './front/cart';

document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();
    initScrollAnimations();
    initHomeSlider();
    initAddToCart();
    initCartActions();
});
