import L from 'leaflet';

export function initRoutePreview(container) {
    const mapEl = container.querySelector('[data-role="route-map"]');
    const infoEl = container.querySelector('[data-role="route-info"]');
    const destLat = parseFloat(container.dataset.destLat);
    const destLng = parseFloat(container.dataset.destLng);

    if (!mapEl || mapEl._leaflet_id || isNaN(destLat) || isNaN(destLng)) {
        return;
    }

    if (mapEl.dataset.routeInitializing === 'true') {
        return;
    }
    mapEl.dataset.routeInitializing = 'true';

    if (!navigator.geolocation) {
        infoEl.textContent = "Géolocalisation non disponible sur ton navigateur.";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const originLat = position.coords.latitude;
            const originLng = position.coords.longitude;

            const map = L.map(mapEl).setView([destLat, destLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            L.marker([destLat, destLng]).addTo(map).bindPopup('Destination');
            L.marker([originLat, originLng]).addTo(map).bindPopup('Toi');

            try {
                const response = await fetch(
                    `https://router.project-osrm.org/route/v1/driving/${originLng},${originLat};${destLng},${destLat}?overview=full&geometries=geojson`
                );
                const data = await response.json();

                if (data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const coords = route.geometry.coordinates.map(([lng, lat]) => [lat, lng]);

                    L.polyline(coords, { color: '#1E3D59', weight: 4 }).addTo(map);
                    map.fitBounds(coords);

                    const minutes = Math.round(route.duration / 60);
                    const km = (route.distance / 1000).toFixed(1);
                    infoEl.textContent = `${km} km · environ ${minutes} min en voiture`;
                } else {
                    infoEl.textContent = "Itinéraire indisponible pour cette destination.";
                }
            } catch (error) {
                console.error('Erreur OSRM', error);
                infoEl.textContent = "Impossible de calculer l'itinéraire pour le moment.";
            }
        },
        () => {
            infoEl.textContent = "Active ta position pour voir l'itinéraire.";
        },
        { timeout: 8000 }
    );
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-route-preview]').forEach(initRoutePreview);
});

document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-route-preview]').forEach(initRoutePreview);
});