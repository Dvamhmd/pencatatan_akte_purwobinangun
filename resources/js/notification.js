/**
 * Auto-dismiss Popup Notifications
 * Muncul secara halus dan hilang secara otomatis dengan animasi yang smooth setelah 3 detik
 */
export function initAutoDismissNotifications() {
    const initElement = (el) => {
        if (!el || el.dataset.notificationInitialized === 'true') return;
        el.dataset.notificationInitialized = 'true';

        let timeoutId = null;
        const duration = 3000; // 3 detik

        const dismiss = () => {
            if (el.classList.contains('dismissing')) return;
            el.classList.add('dismissing');
            setTimeout(() => {
                if (el && el.parentNode) {
                    el.remove();
                }
            }, 520);
        };

        // Close button click handler
        const closeBtns = el.querySelectorAll('[data-dismiss="notification"], .close-notification-btn');
        closeBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (timeoutId) clearTimeout(timeoutId);
                dismiss();
            });
        });

        // Start 3s countdown timer
        const startTimer = () => {
            timeoutId = setTimeout(dismiss, duration);
        };

        // Pause timer on hover, resume on mouse leave
        el.addEventListener('mouseenter', () => {
            if (timeoutId) clearTimeout(timeoutId);
        });

        el.addEventListener('mouseleave', () => {
            startTimer();
        });

        startTimer();
    };

    // Scan initially
    document.querySelectorAll('.popup-notification').forEach(initElement);

    // Observe dynamically added popup notifications
    if ('MutationObserver' in window) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        if (node.classList && node.classList.contains('popup-notification')) {
                            initElement(node);
                        }
                        const nested = node.querySelectorAll ? node.querySelectorAll('.popup-notification') : [];
                        nested.forEach(initElement);
                    }
                });
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }
}
