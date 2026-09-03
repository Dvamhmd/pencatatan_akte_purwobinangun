import './bootstrap';
import { initDissolveEffects } from './dissolve';
import { initAutoDismissNotifications } from './notification';

function initAll() {
    initDissolveEffects();
    initAutoDismissNotifications();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

