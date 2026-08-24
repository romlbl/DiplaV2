import L from 'leaflet';
import './leaflet-default-icon.js';

function setInputValue(input, value) {
    input.value = value;
    input.dispatchEvent(new Event('input', { bubbles: true }));
}

export function initLocationPicker(container) {
    const addressInput = container.querySelector('[data-role="address-input"]');
    const mapEl = container.querySelector('[data-role="map"]');
    const latInput = container.querySelector('[data-role="latitude"]');
    const lngInput = container.querySelector('[data-role="longitude"]');
    const suggestionsEl = container.querySelector('[data-role="suggestions"]');

    if (!addressInput || !mapEl || !latInput || !lngInput) {
        console.warn('location-picker: éléments manquants dans le conteneur', container);
        return;
    }
    if (mapEl._leaflet_id) {
        return;
    }
    const startLat = parseFloat(latInput.value) || 47.322047;
    const startLng = parseFloat(lngInput.value) || 5.04148;

    const map = L.map(mapEl).setView([startLat, startLng], latInput.value ? 15 : 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    let marker = latInput.value
        ? L.marker([startLat, startLng], { draggable: true }).addTo(map)
        : null;

    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`
            );
            const result = await response.json();

            if (result && result.display_name) {
                setInputValue(addressInput, result.display_name);
            }
        } catch (error) {
            console.error('Erreur de géocodage inverse', error);
        }
    }

    function setPosition(lat, lng, { skipReverseGeocode = false } = {}) {
        setInputValue(latInput, lat);
        setInputValue(lngInput, lng);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', () => {
                const pos = marker.getLatLng();
                setPosition(pos.lat, pos.lng);
            });
        }

        map.setView([lat, lng], 15);

        if (!skipReverseGeocode) {
            reverseGeocode(lat, lng);
        }
    }

    map.on('click', (e) => {
        setPosition(e.latlng.lat, e.latlng.lng);
    });

    let debounceTimer;
    addressInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = addressInput.value.trim();

        if (query.length < 3) {
            suggestionsEl.innerHTML = '';
            suggestionsEl.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&countrycodes=fr&q=${encodeURIComponent(query)}`
                );
                const results = await response.json();
                renderSuggestions(results);
            } catch (error) {
                console.error('Erreur de géocodage', error);
            }
        }, 400);
    });

    function renderSuggestions(results) {
        suggestionsEl.innerHTML = '';

        if (results.length === 0) {
            suggestionsEl.classList.add('hidden');
            return;
        }

        results.forEach((result) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'w-full text-left px-4 py-2 text-sm text-[#333333] hover:bg-[#FDFBF7] transition';
            item.textContent = result.display_name;
            item.addEventListener('click', () => {
                setInputValue(addressInput, result.display_name);
                setPosition(parseFloat(result.lat), parseFloat(result.lon), { skipReverseGeocode: true })
                suggestionsEl.innerHTML = '';
                suggestionsEl.classList.add('hidden');
            });
            suggestionsEl.appendChild(item);
        });

        suggestionsEl.classList.remove('hidden');
    }

    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            suggestionsEl.classList.add('hidden');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-location-picker]').forEach(initLocationPicker);
});

document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-location-picker]').forEach(initLocationPicker);
});