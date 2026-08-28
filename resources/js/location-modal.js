import L from 'leaflet';
import './leaflet-default-icon.js';

// Composant Alpine pour la modale "Rechercher depuis..." du header :
// carte Leaflet + autocomplete Nominatim, écrit le résultat dans le store
// global "searchLocation" (voir resources/js/location-store.js).
document.addEventListener('alpine:init', () => {
    Alpine.data('locationSearchModal', () => ({
        open: false,
        mapReady: false,
        query: '',
        suggestions: [],
        map: null,
        marker: null,
        selectedLat: null,
        selectedLng: null,
        selectedLabel: '',
        searchTimer: null,

        openModal() {
            this.open = true;
            this.$nextTick(() => this.initMap());
        },

        closeModal() {
            this.open = false;
            this.suggestions = [];
        },

        initMap() {
            if (this.mapReady) {
                this.map.invalidateSize();
                return;
            }

            const hasStoredLocation = this.$store.searchLocation.hasLocation;
            const startLat = this.$store.searchLocation.lat ?? 47.322047;
            const startLng = this.$store.searchLocation.lng ?? 5.04148;
            const startZoom = hasStoredLocation ? 14 : 6;

            this.map = L.map(this.$refs.modalMap).setView([startLat, startLng], startZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(this.map);

            if (hasStoredLocation) {
                this.query = this.$store.searchLocation.label ?? '';
                this.selectedLabel = this.$store.searchLocation.label ?? '';
                this.placeMarker(this.$store.searchLocation.lat, this.$store.searchLocation.lng, { skipReverseGeocode: true });
            }

            this.map.on('click', (event) => this.placeMarker(event.latlng.lat, event.latlng.lng));

            this.mapReady = true;

            // La modale est encore en transition à ce stade : le conteneur n'a pas
            // toujours sa taille finale, ce qui décale le centrage de la carte.
            // On force un recalcul une fois la transition terminée.
            setTimeout(() => {
                this.map.invalidateSize();
                this.map.setView([startLat, startLng], startZoom);
            }, 200);
        },

        placeMarker(lat, lng, { skipReverseGeocode = false } = {}) {
            this.selectedLat = lat;
            this.selectedLng = lng;

            if (this.marker) {
                this.marker.setLatLng([lat, lng]);
            } else {
                this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                this.marker.on('dragend', () => {
                    const pos = this.marker.getLatLng();
                    this.placeMarker(pos.lat, pos.lng);
                });
            }

            this.map.setView([lat, lng], 14);

            if (!skipReverseGeocode) {
                this.reverseGeocode(lat, lng);
            }
        },

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`
                );
                const result = await response.json();

                if (result && result.display_name) {
                    this.query = result.display_name;
                    this.selectedLabel = result.display_name;
                }
            } catch (error) {
                console.error('Erreur de géocodage inverse', error);
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
            this.selectedLabel = suggestion.display_name;
            this.suggestions = [];
            this.placeMarker(parseFloat(suggestion.lat), parseFloat(suggestion.lon), { skipReverseGeocode: true });
        },

        useMyPosition() {
            if (!navigator.geolocation) return;

            navigator.geolocation.getCurrentPosition(
                (position) => this.placeMarker(position.coords.latitude, position.coords.longitude),
                () => console.warn('Géolocalisation refusée ou indisponible'),
                { timeout: 8000 }
            );
        },

        confirm() {
            if (this.selectedLat === null || this.selectedLng === null) return;

            const label = this.selectedLabel || this.query || 'Position choisie';

            this.$store.searchLocation.set(label, this.selectedLat, this.selectedLng);
            this.closeModal();

            // Recharge pour que tout ce qui dépend de la position (notamment
            // l'itinéraire sur la fiche produit) se recalcule proprement.
            window.location.reload();
        },

        clearLocation() {
            this.$store.searchLocation.clear();
            this.query = '';
            this.selectedLat = null;
            this.selectedLng = null;
            this.selectedLabel = '';

            if (this.marker) {
                this.map.removeLayer(this.marker);
                this.marker = null;
            }
        },
    }));
});