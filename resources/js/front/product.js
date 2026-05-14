export function initAddToCart() {
    const form = document.getElementById('add-to-cart-form');

    if (!form) {
        return;
    }

    const postUrl = form.dataset.cartAddUrl;
    const redirectUrl = form.dataset.cartRedirectUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!postUrl || !redirectUrl || !csrfToken) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const data = new FormData(form);

        try {
            const response = await fetch(postUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: data,
            });

            if (!response.ok) {
                return;
            }

            const json = await response.json();

            if (json.success) {
                alert('Added to cart!');
                window.location.href = redirectUrl;
            }
        } catch (error) {
            console.error(error);
        }
    });
}
