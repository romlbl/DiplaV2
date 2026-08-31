// Store partagé entre le header et la page d'accueil : retient l'adresse
// choisie par l'utilisateur pour ses recherches ("À proximité" -> nom du lieu).
// Persisté en localStorage pour survivre aux rechargements de page et aux
// navigations wire:navigate.

const STORAGE_KEY = 'dipla-search-location';

document.addEventListener('alpine:init', () => {
    Alpine.store('searchLocation', {
        label: null,
        lat: null,
        lng: null,

        init() {
            // Juste après une connexion, l'adresse du compte doit prendre le
            // dessus même sur une position déjà en mémoire (héritée d'avant la
            // connexion). Dans tous les autres cas, une position déjà choisie
            // reste prioritaire (voir layouts/public.blade.php pour le flag).
            if (!window.diplaJustLoggedIn) {
                try {
                    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');

                    if (saved && saved.lat && saved.lng) {
                        this.label = saved.label;
                        this.lat = saved.lat;
                        this.lng = saved.lng;
                        return;
                    }
                } catch (error) {
                    console.warn('Impossible de lire la position enregistrée', error);
                }
            }

            // Aucune position à garder : si l'utilisateur connecté a une
            // adresse enregistrée, on l'utilise (par défaut, ou en écrasement
            // juste après connexion).
            const userLocation = window.diplaUserLocation;
            if (userLocation && userLocation.lat && userLocation.lng) {
                this.set(userLocation.label, userLocation.lat, userLocation.lng);
            }
        },

        set(label, lat, lng) {
            this.label = label;
            this.lat = lat;
            this.lng = lng;

            localStorage.setItem(STORAGE_KEY, JSON.stringify({ label, lat, lng }));
        },

        clear() {
            this.label = null;
            this.lat = null;
            this.lng = null;

            localStorage.removeItem(STORAGE_KEY);
        },

        get hasLocation() {
            return this.lat !== null && this.lng !== null;
        },
    });
});