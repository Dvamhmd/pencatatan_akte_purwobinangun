/**
 * Website Notification Center (Warga & Admin)
 * Handles notification dropdown, unread tracking, delete items, clear all, and category filtering via localStorage.
 */

function setupNotificationPanel(options) {
    const {
        prefix,
        storagePrefix,
        filterKeys,
        activeBtnClasses,
        inactiveBtnClasses,
        clickHandlerName,
        deleteHandlerName,
    } = options;

    const container = document.getElementById(`${prefix}-notif-container`);
    const btn = document.getElementById(`${prefix}-notif-btn`);
    const dropdown = document.getElementById(`${prefix}-notif-dropdown`);
    const closeBtn = document.getElementById(`${prefix}-btn-close-notif-dropdown`) || document.getElementById('btn-close-notif-dropdown');
    const markAllReadBtn = document.getElementById(`${prefix}-btn-mark-all-read`) || document.getElementById('btn-mark-all-read');
    const clearAllBtn = document.getElementById(`${prefix}-btn-clear-all-notifs`) || document.getElementById('btn-clear-all-notifs');
    const badge = document.getElementById(`${prefix}-notif-badge`);
    const ping = document.getElementById(`${prefix}-notif-ping`);
    const notifList = document.getElementById(`${prefix}-notif-list`);
    const emptyState = document.getElementById(`${prefix}-notif-empty`);

    if (!container || !btn || !dropdown || !notifList) {
        return;
    }

    const userId = notifList.dataset.userId || 'default';
    const storageKeyRead = `${storagePrefix}_read_notifs_${userId}`;
    const storageKeyDeleted = `${storagePrefix}_deleted_notifs_${userId}`;

    function readStorage(key) {
        try {
            const raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function writeStorage(key, ids) {
        try {
            localStorage.setItem(key, JSON.stringify(ids));
        } catch (e) {
            // Ignore quota errors
        }
    }

    function updateNotifUI() {
        const readIds = new Set(readStorage(storageKeyRead));
        const deletedIds = new Set(readStorage(storageKeyDeleted));
        const items = notifList.querySelectorAll('.notif-item');

        let unreadCount = 0;
        let totalRemaining = 0;
        const typeCounts = Object.fromEntries(filterKeys.map(k => [k, 0]));

        items.forEach(item => {
            const id = item.dataset.notifId;
            const type = item.dataset.notifType;

            if (deletedIds.has(id)) {
                item.remove();
                return;
            }

            totalRemaining++;
            if (Object.prototype.hasOwnProperty.call(typeCounts, type)) {
                typeCounts[type]++;
            }

            const isRead = readIds.has(id);
            const dot = item.querySelector('.notif-unread-dot');

            if (isRead) {
                item.classList.add('notif-read', 'is-read');
                item.classList.remove('notif-unread');
                if (dot) {
                    dot.classList.add('hidden', 'opacity-0');
                    dot.classList.remove('block');
                }
            } else {
                item.classList.remove('notif-read', 'is-read');
                item.classList.add('notif-unread');
                if (dot) {
                    dot.classList.remove('hidden', 'opacity-0');
                    dot.classList.add('block');
                }
                unreadCount++;
            }
        });

        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badge.classList.remove('hidden');
                if (ping) ping.classList.remove('hidden');
            } else {
                badge.textContent = '0';
                badge.classList.add('hidden');
                if (ping) ping.classList.add('hidden');
            }
        }

        const filterCountAll = dropdown.querySelector('.notif-filter-count[data-filter-type="all"]');
        if (filterCountAll) filterCountAll.textContent = totalRemaining;

        filterKeys.forEach(key => {
            const el = dropdown.querySelector(`.notif-filter-count[data-filter-type="${key}"]`);
            if (el) el.textContent = typeCounts[key];
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', totalRemaining > 0);
        }
    }

    function toggleDropdown(show) {
        const isHidden = dropdown.classList.contains('hidden');
        const shouldShow = typeof show === 'boolean' ? show : isHidden;
        dropdown.classList.toggle('hidden', !shouldShow);
        btn.setAttribute('aria-expanded', shouldShow ? 'true' : 'false');
    }

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown();
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(false);
        });
    }

    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            toggleDropdown(false);
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !dropdown.classList.contains('hidden')) {
            toggleDropdown(false);
        }
    });

    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const items = notifList.querySelectorAll('.notif-item');
            const allIds = Array.from(items).map(item => item.dataset.notifId).filter(Boolean);
            const currentRead = new Set(readStorage(storageKeyRead));
            allIds.forEach(id => currentRead.add(id));
            writeStorage(storageKeyRead, Array.from(currentRead));
            updateNotifUI();
        });
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!confirm('Apakah Anda yakin ingin menghapus semua daftar notifikasi yang masuk?')) {
                return;
            }
            const items = notifList.querySelectorAll('.notif-item');
            const allIds = Array.from(items).map(item => item.dataset.notifId).filter(Boolean);
            const currentDeleted = new Set(readStorage(storageKeyDeleted));
            allIds.forEach(id => currentDeleted.add(id));
            writeStorage(storageKeyDeleted, Array.from(currentDeleted));

            items.forEach(item => {
                item.style.transition = 'all 0.25s ease-out';
                item.style.opacity = '0';
                item.style.transform = 'translateX(20px)';
            });

            setTimeout(() => {
                items.forEach(item => item.remove());
                updateNotifUI();
            }, 250);
        });
    }

    const filterBtns = dropdown.querySelectorAll('.notif-filter-btn');
    filterBtns.forEach(fBtn => {
        fBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const filter = fBtn.dataset.filter;

            filterBtns.forEach(b => {
                b.classList.remove(...activeBtnClasses);
                b.classList.add(...inactiveBtnClasses);
            });
            fBtn.classList.remove(...inactiveBtnClasses);
            fBtn.classList.add(...activeBtnClasses);

            const items = notifList.querySelectorAll('.notif-item');
            let visibleCount = 0;

            items.forEach(item => {
                const match = filter === 'all' || item.dataset.notifType === filter;
                item.classList.toggle('hidden', !match);
                if (match) visibleCount++;
            });

            let emptyMsg = notifList.querySelector('.filter-empty-msg');
            if (visibleCount === 0 && items.length > 0) {
                if (!emptyMsg) {
                    emptyMsg = document.createElement('div');
                    emptyMsg.className = 'filter-empty-msg p-6 text-center text-xs text-slate-500';
                    emptyMsg.innerHTML = '<i class="fa-solid fa-filter-circle-xmark text-2xl text-slate-400 mb-2 block"></i>Tidak ada notifikasi pada kategori ini.';
                    notifList.appendChild(emptyMsg);
                }
                emptyMsg.classList.remove('hidden');
            } else if (emptyMsg) {
                emptyMsg.classList.add('hidden');
            }
        });
    });

    updateNotifUI();

    window[clickHandlerName] = function(id, url) {
        if (id) {
            const currentRead = new Set(readStorage(storageKeyRead));
            currentRead.add(id);
            writeStorage(storageKeyRead, Array.from(currentRead));
            updateNotifUI();
        }
        if (url) {
            window.location.href = url;
        }
    };

    window[deleteHandlerName] = function(id, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        if (!id) return;

        const currentDeleted = new Set(readStorage(storageKeyDeleted));
        currentDeleted.add(id);
        writeStorage(storageKeyDeleted, Array.from(currentDeleted));

        const item = notifList.querySelector(`.notif-item[data-notif-id="${id}"]`);
        if (item) {
            item.style.transition = 'all 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
            item.style.opacity = '0';
            item.style.transform = 'translateX(24px)';
            item.style.maxHeight = '0px';
            item.style.paddingTop = '0px';
            item.style.paddingBottom = '0px';
            item.style.margin = '0px';
            item.style.overflow = 'hidden';

            setTimeout(() => {
                item.remove();
                updateNotifUI();
            }, 250);
        } else {
            updateNotifUI();
        }
    };
}

export function initWargaNotifications() {
    setupNotificationPanel({
        prefix: 'warga',
        storagePrefix: 'purwobinangun_warga',
        filterKeys: ['akte', 'profile', 'account'],
        activeBtnClasses: ['bg-[#095b8c]', 'text-white'],
        inactiveBtnClasses: ['bg-slate-200', 'text-slate-700'],
        clickHandlerName: 'handleNotificationClick',
        deleteHandlerName: 'deleteNotification',
    });

    setupNotificationPanel({
        prefix: 'admin',
        storagePrefix: 'purwobinangun_admin',
        filterKeys: ['akte', 'citizen', 'profile'],
        activeBtnClasses: ['bg-[#065b65]', 'text-white'],
        inactiveBtnClasses: ['bg-slate-200', 'text-slate-700'],
        clickHandlerName: 'handleAdminNotificationClick',
        deleteHandlerName: 'deleteAdminNotification',
    });
}
