import { NeatGradient } from '@firecms/neat';

let gradientInstance = null;

function initNeatBackground() {
    const canvas = document.getElementById('neat-home-background');
    if (!canvas) return;

    // Évite une double init si l'événement se déclenche deux fois sur la même page
    if (canvas.dataset.neatInitialized === 'true') return;
    canvas.dataset.neatInitialized = 'true';

    gradientInstance = new NeatGradient({
        ref: canvas,
        colors: [
            { color: '#FDFBF7', enabled: true },
            { color: '#FDEEE2', enabled: true },
            { color: '#FAF3E0', enabled: true },
            { color: '#F5EFE6', enabled: true },
            { color: '#EAE3D6', enabled: true },
            { color: '#B8D4E6', enabled: false },
        ],
        speed: 2,
        horizontalPressure: 3,
        verticalPressure: 5,
        waveFrequencyX: 1,
        waveFrequencyY: 3,
        waveAmplitude: 8,
        secondaryWaveEnabled: false,
        secondaryWaveFrequencyX: 3,
        secondaryWaveFrequencyY: 3,
        secondaryWaveAmplitude: 5,
        secondaryWaveSpeed: 0.6,
        secondaryWaveAngle: 1,
        shadows: 0,
        highlights: 2,
        colorBrightness: 1,
        colorSaturation: 6,
        wireframe: false,
        antialias: false,
        colorBlending: 7,
        backgroundColor: '#FDFBF7',
        backgroundAlpha: 1,
        grainScale: 2,
        grainSparsity: 0,
        grainIntensity: 0.175,
        grainSpeed: 1,
        resolution: 1,
        yOffset: 0,
        yOffsetWaveMultiplier: 1.8,
        yOffsetColorMultiplier: 2,
        yOffsetFlowMultiplier: 2.2,
        flowDistortionA: 0,
        flowDistortionB: 0,
        flowScale: 1,
        flowEase: 0,
        flowEnabled: false,
        vignetteIntensity: 0,
        vignetteRadius: 0.8,
        shapeType: 'plane',
        cameraLock: true,
    });
}

function destroyNeatBackground() {
    if (gradientInstance) {
        gradientInstance.destroy?.();
        gradientInstance = null;
    }

    const canvas = document.getElementById('neat-home-background');
    if (canvas) {
        delete canvas.dataset.neatInitialized;
    }
}

// wire:navigate remplace le DOM sans recharger la page : il faut réinitialiser
// à chaque arrivée sur l'accueil, et détruire l'instance avant de quitter la
// page pour ne pas laisser tourner une boucle de rendu sur un canvas retiré du DOM.
document.addEventListener('DOMContentLoaded', initNeatBackground);
document.addEventListener('livewire:navigated', initNeatBackground);
document.addEventListener('livewire:navigating', destroyNeatBackground);