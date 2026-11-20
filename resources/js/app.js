// axios (bootstrap.js) retiré : le site utilise fetch() natif, autant de JavaScript en moins à charger.

// Alpine.js : NE PAS l'importer ni appeler Alpine.start() ici.
// Livewire 4 embarque et démarre l'unique instance d'Alpine, avec les plugins
// officiels déjà enregistrés (intersect, collapse, focus, anchor, mask, sort, persist).

// Apparitions .reveal / .reveal-scale : IntersectionObserver natif (indépendant d'Alpine).
// Le masquage n'existe que sous html.reveal-ready (script inline du layout).
function initReveal() {
    const items = document.querySelectorAll('.reveal:not(.is-visible), .reveal-scale:not(.is-visible)');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    items.forEach((el) => observer.observe(el));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initReveal);
} else {
    initReveal();
}

document.addEventListener('livewire:navigated', initReveal);

// Halo des cartes .spotlight : position du pointeur en variables CSS (--mx, --my).
document.addEventListener('pointermove', (event) => {
    const card = event.target.closest?.('.spotlight');
    if (!card) return;
    const rect = card.getBoundingClientRect();
    card.style.setProperty('--mx', `${event.clientX - rect.left}px`);
    card.style.setProperty('--my', `${event.clientY - rect.top}px`);
}, { passive: true });
