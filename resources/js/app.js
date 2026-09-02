import './bootstrap';
import { initDissolveEffects } from './dissolve';

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDissolveEffects);
} else {
    initDissolveEffects();
}
