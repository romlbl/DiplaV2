import L from 'leaflet';
import './leaflet-default-icon.js';

// Composant Alpine pour l'itinéraire de la fiche produit : récupère la position
// du user (store partagé "searchLocation" en priorité, sinon géolocalisation
// navigateur), calcule un itinéraire via OSRM, et estime le temps pour
// plusieurs modes de transport à partir de la distance obtenue.
document.addEventListener('alpine:init', () => {
    Alpine.data('routePreview', (destLat, destLng) => ({
        destLat,
        destLng,
        mode: 'walking',
        loading: true,
        error: null,
        distanceKm: null,
        durations: { walking: null, cycling: null, driving: null },
        originLat: null,
        originLng: null,
        map: null,
        polyline: null,

        init() {
            this.resolveOrigin();
        },

        resolveOrigin() {
            if (this.$store.searchLocation.hasLocation) {
                this.originLat = this.$store.searchLocation.lat;
                this.originLng = this.$store.searchLocation.lng;
                this.initMapAndRoute();
                return;
            }

            if (!navigator.geolocation) {
                this.loading = false;
                this.error = "Active ta position pour voir l'itinéraire.";
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.originLat = position.coords.latitude;
                    this.originLng = position.coords.longitude;
                    // On réutilise le store partagé pour ne pas redemander la position
                    // à chaque fiche produit consultée.
                    this.$store.searchLocation.set('Ma position actuelle', this.originLat, this.originLng);
                    this.initMapAndRoute();
                },
                () => {
                    this.loading = false;
                    this.error = "Active ta position pour voir l'itinéraire.";
                },
                { timeout: 8000 }
            );
        },

        initMapAndRoute() {
            this.$nextTick(() => {
                const mapEl = this.$refs.map;
                if (!mapEl || mapEl._leaflet_id) return;

                this.map = L.map(mapEl).setView([this.destLat, this.destLng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19,
                }).addTo(this.map);

                L.marker([this.destLat, this.destLng]).addTo(this.map).bindPopup('Destination');
                L.marker([this.originLat, this.originLng]).addTo(this.map).bindPopup('Toi');

                this.fetchRoute();
            });
        },

        async fetchRoute() {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(
                    `https://router.project-osrm.org/route/v1/driving/${this.originLng},${this.originLat};${this.destLng},${this.destLat}?overview=full&geometries=geojson`
                );
                const data = await response.json();

                if (data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const coords = route.geometry.coordinates.map(([lng, lat]) => [lat, lng]);

                    if (this.polyline) this.map.removeLayer(this.polyline);
                    this.polyline = L.polyline(coords, { color: '#1E3D59', weight: 4 }).addTo(this.map);
                    this.map.fitBounds(coords);

                    this.distanceKm = route.distance / 1000;
                    this.computeDurations();
                } else {
                    this.error = "Itinéraire indisponible pour cette destination.";
                }
            } catch (error) {
                console.error('Erreur OSRM', error);
                this.error = "Impossible de calculer l'itinéraire pour le moment.";
            } finally {
                this.loading = false;
            }
        },

        computeDurations() {
            if (this.distanceKm === null) return;

            // Le tracé provient d'un calcul "voiture" (seul profil disponible sur
            // le serveur OSRM public gratuit) ; pour les autres modes, on estime
            // le temps à partir de vitesses moyennes en ville.
            const speeds = { walking: 5, cycling: 15, driving: 30 };

            this.durations = Object.fromEntries(
                Object.entries(speeds).map(([key, speed]) => {
                    const minutes = Math.round((this.distanceKm / speed) * 60);
                    return [key, minutes < 1 ? '< 1 min' : `${minutes} min`];
                })
            );
        },

        setMode(mode) {
            this.mode = mode;
        },
    }));
});