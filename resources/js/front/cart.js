export function initCartActions() {
    const cartContainer = document.querySelector('[data-cart-update-template]');

    if (!cartContainer) {
        return;
    }

    const template = cartContainer.dataset.cartUpdateTemplate;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!template || !csrfToken) {
        return;
    }

    cartContainer.addEventListener('click', (event) => {
        const button = event.target.closest('[data-cart-qty-btn]');

        if (!button) {
            return;
        }

        const index = Number.parseInt(button.dataset.index ?? '', 10);
        const delta = Number.parseInt(button.dataset.delta ?? '', 10);

        if (Number.isNaN(index) || Number.isNaN(delta)) {
            return;
        }

        const url = template
            .replace('__index__', String(index))
            .replace('__delta__', String(delta));

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'text/html',
            },
        }).then(() => {
            window.location.reload();
        });
    });
}
