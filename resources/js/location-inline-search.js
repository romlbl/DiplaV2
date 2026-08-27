// Barre de recherche de lieu intégrée à la page résultats (sidebar filtres).
// Écrit dans le même store Alpine "searchLocation" que la modale du header
// (resources/js/location-store.js), donc la position choisie ici est
// réutilisée automatiquement sur toutes les pages (accueil, header...).
document.addEventListener('alpine:init', () => {
    Alpine.data('locationInlineSearch', () => ({
        query: '',
        suggestions: [],
        searchTimer: null,

        init() {
            if (this.$store.searchLocation.hasLocation) {
                this.query = this.$store.searchLocation.label ?? '';
            }
        },

        onQueryInput() {
            clearTimeout(this.searchTimer);

            if (this.query.trim().length < 3) {
                this.suggestions = [];
                return;
            }

            this.searchTimer = setTimeout(() => this.fetchSuggestions(), 400);
        },

        async fetchSuggestions() {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&countrycodes=fr&q=${encodeURIComponent(this.query.trim())}`
                );
                this.suggestions = await response.json();
            } catch (error) {
                console.error('Erreur de géocodage', error);
            }
        },

        selectSuggestion(suggestion) {
            this.query = suggestion.display_name;
            this.suggestions = [];

            const lat = parseFloat(suggestion.lat);
            const lng = parseFloat(suggestion.lon);

            this.$store.searchLocation.set(suggestion.display_name, lat, lng);

            if (typeof this.$wire?.setUserLocation === 'function') {
                this.$wire.setUserLocation(lat, lng);
            }
        },

        useMyPosition() {
            if (!navigator.geolocation) return;

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    let label = 'Ma position actuelle';

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`
                        );
                        const result = await response.json();
                        if (result?.display_name) label = result.display_name;
                    } catch (error) {
                        console.error('Erreur de géocodage inverse', error);
                    }

                    this.query = label;
                    this.$store.searchLocation.set(label, lat, lng);

                    if (typeof this.$wire?.setUserLocation === 'function') {
                        this.$wire.setUserLocation(lat, lng);
                    }
                },
                () => console.warn('Géolocalisation refusée ou indisponible'),
                { timeout: 8000 }
            );
        },

        clearLocation() {
            this.query = '';
            this.suggestions = [];
            this.$store.searchLocation.clear();
        },
    }));
});