/**
 * Warga Notification Center
 * Handles notification dropdown, unread tracking, delete items, clear all, and category filtering via localStorage.
 */

export function initWargaNotifications() {
    const container = document.getElementById('warga-notif-container');
    const btn = document.getElementById('warga-notif-btn');
    const dropdown = document.getElementById('warga-notif-dropdown');
    const closeBtn = document.getElementById('btn-close-notif-dropdown');
    const markAllReadBtn = document.getElementById('btn-mark-all-read');
    const clearAllBtn = document.getElementById('btn-clear-all-notifs');
    const badge = document.getElementById('warga-notif-badge');
    const ping = document.getElementById('warga-notif-ping');
    const notifList = document.getElementById('warga-notif-list');
    const emptyState = document.getElementById('warga-notif-empty');

    if (!container || !btn || !dropdown || !notifList) {
        return;
    }

    const userId = notifList.dataset.userId || 'default';
    const storageKeyRead = `purwobinangun_warga_read_notifs_${userId}`;
    const storageKeyDeleted = `purwobinangun_warga_deleted_notifs_${userId}`;

    // Helper untuk mengambil set notifikasi yang sudah dibaca
    function getReadNotifIds() {
        try {
            const raw = localStorage.getItem(storageKeyRead);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    // Helper untuk menyimpan set notifikasi yang sudah dibaca
    function saveReadNotifIds(ids) {
        try {
            localStorage.setItem(storageKeyRead, JSON.stringify(ids));
        } catch (e) {
            // Ignore quota errors
        }
    }

    // Helper untuk mengambil set notifikasi yang sudah dihapus
    function getDeletedNotifIds() {
        try {
            const raw = localStorage.getItem(storageKeyDeleted);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    // Helper untuk menyimpan set notifikasi yang sudah dihapus
    function saveDeletedNotifIds(ids) {
        try {
            localStorage.setItem(storageKeyDeleted, JSON.stringify(ids));
        } catch (e) {
            // Ignore quota errors
        }
    }

    // Update tampilan status read/unread, hapus item yang di-delete, dan counter badge & filter
    function updateNotifUI() {
        const readIds = new Set(getReadNotifIds());
        const deletedIds = new Set(getDeletedNotifIds());
        const items = notifList.querySelectorAll('.notif-item');
        
        let unreadCount = 0;
        let totalRemaining = 0;
        let akteCount = 0;
        let profileCount = 0;
        let accountCount = 0;

        items.forEach(item => {
            const id = item.dataset.notifId;
            const type = item.dataset.notifType;

            // Jika item sudah dihapus, hilangkan dari tampilan
            if (deletedIds.has(id)) {
                item.remove();
                return;
            }

            totalRemaining++;
            if (type === 'akte') akteCount++;
            if (type === 'profile') profileCount++;
            if (type === 'account') accountCount++;

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

        // Update Counter pada Badge Lonceng
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

        // Update Counter pada Filter Pills
        const filterCountAll = dropdown.querySelector('.notif-filter-count[data-filter-type="all"]');
        const filterCountAkte = dropdown.querySelector('.notif-filter-count[data-filter-type="akte"]');
        const filterCountProfile = dropdown.querySelector('.notif-filter-count[data-filter-type="profile"]');
        const filterCountAccount = dropdown.querySelector('.notif-filter-count[data-filter-type="account"]');

        if (filterCountAll) filterCountAll.textContent = totalRemaining;
        if (filterCountAkte) filterCountAkte.textContent = akteCount;
        if (filterCountProfile) filterCountProfile.textContent = profileCount;
        if (filterCountAccount) filterCountAccount.textContent = accountCount;

        // Toggle Empty State
        if (emptyState) {
            if (totalRemaining === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }
    }

    // Toggle Dropdown
    function toggleDropdown(show) {
        const isHidden = dropdown.classList.contains('hidden');
        const shouldShow = typeof show === 'boolean' ? show : isHidden;

        if (shouldShow) {
            dropdown.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');
        } else {
            dropdown.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    }

    // Event listener tombol lonceng
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

    // Klik di luar dropdown untuk menutup
    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            toggleDropdown(false);
        }
    });

    // Tombol Escape untuk menutup
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !dropdown.classList.contains('hidden')) {
            toggleDropdown(false);
        }
    });

    // Tandai Semua Sudah Dibaca
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const items = notifList.querySelectorAll('.notif-item');
            const allIds = Array.from(items).map(item => item.dataset.notifId).filter(Boolean);
            const currentRead = new Set(getReadNotifIds());
            allIds.forEach(id => currentRead.add(id));
            saveReadNotifIds(Array.from(currentRead));
            updateNotifUI();
        });
    }

    // Hapus Semua Notifikasi
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!confirm('Apakah Anda yakin ingin menghapus semua daftar notifikasi yang masuk?')) {
                return;
            }
            const items = notifList.querySelectorAll('.notif-item');
            const allIds = Array.from(items).map(item => item.dataset.notifId).filter(Boolean);
            const currentDeleted = new Set(getDeletedNotifIds());
            allIds.forEach(id => currentDeleted.add(id));
            saveDeletedNotifIds(Array.from(currentDeleted));
            
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

    // Filter Kategori Notifikasi
    const filterBtns = dropdown.querySelectorAll('.notif-filter-btn');
    filterBtns.forEach(fBtn => {
        fBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const filter = fBtn.dataset.filter;

            // Update styling tombol filter aktif
            filterBtns.forEach(b => {
                b.classList.remove('bg-[#095b8c]', 'text-white');
                b.classList.add('bg-slate-200', 'text-slate-700');
            });
            fBtn.classList.remove('bg-slate-200', 'text-slate-700');
            fBtn.classList.add('bg-[#095b8c]', 'text-white');

            const items = notifList.querySelectorAll('.notif-item');
            let visibleCount = 0;

            items.forEach(item => {
                const type = item.dataset.notifType;
                if (filter === 'all' || type === filter) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Tampilkan pesan kosong jika filter tidak menghasilkan item
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

    // Inisialisasi status read/unread & hapus item yang tersimpan di deleted
    updateNotifUI();

    // Global click handler untuk membuka notifikasi
    window.handleNotificationClick = function(id, url) {
        if (id) {
            const currentRead = new Set(getReadNotifIds());
            currentRead.add(id);
            saveReadNotifIds(Array.from(currentRead));
            updateNotifUI();
        }
        if (url) {
            window.location.href = url;
        }
    };

    // Global click handler untuk menghapus satu notifikasi
    window.deleteNotification = function(id, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }

        if (!id) return;

        const currentDeleted = new Set(getDeletedNotifIds());
        currentDeleted.add(id);
        saveDeletedNotifIds(Array.from(currentDeleted));

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
