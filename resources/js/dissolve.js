/**
 * Dissolve Animation Engine for Web Warga (Kalurahan Purwobinangun)
 * Handles smooth entry & re-entry dissolve effects on scroll for all cards and inner elements.
 */

export function initDissolveEffects() {
    // Only initialize on citizen portal (Web Warga layout)
    const wargaMain = document.querySelector('main');
    if (!wargaMain) return;

    // Check IntersectionObserver support
    if (!('IntersectionObserver' in window)) {
        // Fallback for older browsers: show all directly
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
                // When scrolling out of view, remove is-visible so it dissolves in again upon re-entry
                el.classList.remove('is-visible');
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -25px 0px',
        threshold: 0.05
    });

    const isExcluded = (el) => {
        return el.closest('.modal-overlay, #preview-modal, #camera-modal, .swal2-container, .modal-dialog-box, nav.sticky, #live-clock, #live-date') !== null;
    };

    function attachDissolveToCard(card) {
        if (!card || isExcluded(card)) return;

        if (!card.classList.contains('dissolve-card')) {
            card.classList.add('dissolve-card');
            dissolveObserver.observe(card);

            // Find child sub-elements to stagger dissolve inside the card
            const subElements = card.querySelectorAll(':scope > div, :scope > form > div, :scope .grid > div, :scope ul > li, :scope .space-y-4 > div, :scope .space-y-6 > div, :scope .grid > a');
            let delayIndex = 0;

            subElements.forEach(child => {
                if (isExcluded(child)) return;
                // Avoid double assigning if already marked
                if (!child.classList.contains('dissolve-child') && !child.classList.contains('dissolve-card')) {
                    child.classList.add('dissolve-child');
                    // Calculate smooth staggered delay (cap at 360ms to keep it snappy)
                    const delay = Math.min(delayIndex * 50, 350);
                    child.style.setProperty('--dissolve-delay', `${delay}ms`);
                    delayIndex++;
                }
            });
        }
    }

    function scanElements() {
        // Select all cards and content sections on Web Warga
        const selectors = [
            'section.hero-purwobinangun',
            'main .bg-white.rounded-xl',
            'main .bg-white.rounded-2xl',
            'main .bg-white.rounded-lg',
            'main .civic-card',
            'main .border.rounded-xl',
            'main .border-2.rounded-xl',
            'main .bg-gradient-to-r',
            'main .space-y-6 > div',
            'aside > div',
            'footer > div > div'
        ];

        const cards = document.querySelectorAll(selectors.join(', '));
        cards.forEach(card => {
            attachDissolveToCard(card);
        });
    }

    // Initial scan on load
    scanElements();

    // Re-scan when DOM changes (e.g. form step navigation, dynamic filters, live searches)
    const mutationObserver = new MutationObserver(() => {
        scanElements();
    });

    mutationObserver.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style']
    });

    // Also listen to custom events or window resize to recalculate
    window.addEventListener('resize', scanElements, { passive: true });
}
