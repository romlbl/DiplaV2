// Ajuste la description d'un hero : on garde le plus de lignes possible tant que
// (titre + description) ne dépasse pas 75 % de la hauteur de l'image de fond.
// Markup attendu : <section hero> ... <div x-data="fitHeroText"> <h1/> <p x-ref="desc"/> </div>
const MAX_RATIO = 0.75;

document.addEventListener('alpine:init', () => {
    Alpine.data('fitHeroText', () => ({
        observer: null,

        init() {
            this.fit();

            // Refait le calcul si le hero change de taille (rotation, breakpoint, chargement des polices).
            const hero = this.$root.closest('section');
            if (hero && 'ResizeObserver' in window) {
                this.observer = new ResizeObserver(() => this.fit());
                this.observer.observe(hero);
            }
            document.fonts?.ready.then(() => this.fit());
        },

        destroy() {
            this.observer?.disconnect();
        },

        fit() {
            const hero = this.$root.closest('section');
            const desc = this.$refs.desc;

            // Hero caché (l'autre version mobile/desktop) : rien à mesurer.
            if (!hero || !desc || hero.clientHeight === 0) return;

            // 1. Etat naturel : description complète, sans coupe.
            desc.style.display = 'block';
            desc.style.overflow = 'visible';
            desc.style.webkitLineClamp = 'unset';

            const style = getComputedStyle(desc);
            const lineHeight = parseFloat(style.lineHeight) || parseFloat(style.fontSize) * 1.4;
            const naturalHeight = desc.offsetHeight;
            const totalLines = Math.round(naturalHeight / lineHeight);

            // 2. Place restante pour la description = 75 % du hero - le reste du bloc (titre, paddings).
            const otherHeight = this.$root.offsetHeight - naturalHeight;
            const available = hero.clientHeight * MAX_RATIO - otherHeight;
            const fittingLines = Math.floor(available / lineHeight);

            // 3. Applique le résultat.
            if (fittingLines >= totalLines) return;          // tout tient

            if (fittingLines < 1) {                          // même 1 ligne est de trop
                desc.style.display = 'none';
                return;
            }

            desc.style.display = '-webkit-box';
            desc.style.webkitBoxOrient = 'vertical';
            desc.style.overflow = 'hidden';
            desc.style.webkitLineClamp = String(fittingLines);
        },
    }));
});