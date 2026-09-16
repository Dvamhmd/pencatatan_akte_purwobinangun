import './bootstrap';
import { initDissolveEffects } from './dissolve';
import { initAutoDismissNotifications } from './notification';
import { initWargaNotifications } from './warga-notifications';

function initAll() {
    initDissolveEffects();
    initAutoDismissNotifications();
    initWargaNotifications();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

