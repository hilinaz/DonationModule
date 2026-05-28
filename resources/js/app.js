

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || form.dataset.noLoading === 'true') {
        return;
    }

    const method = (form.getAttribute('method') || 'GET').toUpperCase();

    if (method === 'GET') {
        return;
    }

    form.setAttribute('aria-busy', 'true');
    form.classList.add('is-submitting');

    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((control) => {
        control.disabled = true;
        control.classList.add('opacity-70', 'cursor-wait');

        if (control instanceof HTMLButtonElement && control.dataset.loadingLabel) {
            control.dataset.originalText = control.textContent || '';
            control.textContent = control.dataset.loadingLabel;
        }
    });
});
