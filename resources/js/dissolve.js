/**
 * Dissolve Animation Engine for Web Warga (Kalurahan Purwobinangun)
 * Handles smooth entry & re-entry dissolve effects on scroll for all distinct cards and inner elements.
 */

export function initDissolveEffects() {
    // Only initialize on citizen portal (Web Warga layout - never on admin panel)
    if (
        document.body?.dataset?.role === 'admin' ||
        document.querySelector('.admin-portal') ||
        document.querySelector('.admin-sidebar') ||
        window.location.pathname.startsWith('/admin') ||
        window.location.pathname.includes('/admin/')
    ) {
        return;
    }

    const wargaMain = document.querySelector('main');
    if (!wargaMain) return;

    // Check IntersectionObserver support
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.dissolve-card, .dissolve-child').forEach(el => {
            el.classList.add('is-visible');
        });
        return;
    }

    // Bi-directional observer: adds 'is-visible' on enter, removes 'is-visible' on exit
    const dissolveObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const el = entry.target;
            if (entry.isIntersecting) {
                el.classList.add('is-visible');
            } else {
                // When scrolling out of view (up or down), remove is-visible so it dissolves in again upon re-entry
                el.classList.remove('is-visible');
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -30px 0px',
        threshold: 0.08
    });

    const isExcluded = (el) => {
        return el.closest('header, .hero-purwobinangun, nav, footer, .modal-overlay, #preview-modal, #camera-modal, .swal2-container, .modal-dialog-box, #live-clock, #live-date, .popup-notification, #notificationConfirmModal, [role="dialog"], .no-dissolve') !== null;
    };

    function attachDissolve(card) {
        if (!card || isExcluded(card)) return;

        if (!card.classList.contains('dissolve-card')) {
            card.classList.add('dissolve-card');
        }
        dissolveObserver.observe(card);
    }

    function scanElements() {
        // Select all cards on Web Warga (excluding headbar, nav, and footer)
        const selectors = [
            'main .space-y-6 > div',
            'main .civic-card',
            'main .bg-white.rounded-xl',
            'main .bg-white.rounded-2xl',
            'main .bg-gradient-to-r.rounded-xl',
            'main .border.rounded-xl',
            'main .border-2.rounded-xl',
            'aside > div',
            '.dissolve-card'
        ];

        const cards = document.querySelectorAll(selectors.join(', '));
        cards.forEach(card => {
            attachDissolve(card);
        });
    }

    // Initial scan on load
    scanElements();

    // Re-scan when DOM changes (e.g. form step navigation, dynamic filters)
    const mutationObserver = new MutationObserver(() => {
        scanElements();
    });

    mutationObserver.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style']
    });

    window.addEventListener('resize', scanElements, { passive: true });
}
