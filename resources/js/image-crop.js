import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

/**
 * Cœur du recadrage : charge un fichier dans Cropper, puis exporte le résultat.
 * - onReady : image affichée, cropper prêt (sert à couper le spinner).
 * - onFail  : lecture ou export impossible.
 */
function createCropRunner({ aspectRatio, imgEl }) {
    let cropper = null;

    return {
        load(file, onReady, onFail) {
            const reader = new FileReader();

            reader.onload = (e) => {
                imgEl.src = e.target.result;

                if (cropper) cropper.destroy();

                cropper = new Cropper(imgEl, {
                    aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                    ready: onReady,
                });
            };

            reader.onerror = onFail;
            reader.readAsDataURL(file);
        },

        confirm(originalName, onCropped, onFail) {
            if (!cropper) return;

            // Petit délai : laisse le navigateur afficher le spinner avant le calcul (bloquant).
            setTimeout(() => {
                cropper.getCroppedCanvas({
                    width: 1000,
                    height: 1500, // 2:3
                    imageSmoothingQuality: 'high',
                }).toBlob((blob) => {
                    if (!blob) return onFail();
                    onCropped(new File([blob], originalName, { type: 'image/webp' }));
                }, 'image/webp', 0.85);
            }, 30);
        },

        destroy() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        },
    };
}

document.addEventListener('alpine:init', () => {
    // --- Édition d'un produit : upload direct vers Livewire ---
    Alpine.data('productImageManager', () => ({
        confirmingDelete: null,
        cropModalOpen: false,
        cropLoading: false,   // image en cours de chargement dans le cropper
        processing: false,    // recadrage en cours de calcul
        uploadProgress: 0,    // % d'envoi vers le serveur
        errorMessage: null,
        queue: [],
        queueIndex: 0,
        croppedFiles: [],
        runner: null,

        handleFiles(fileList) {
            this.queue = Array.from(fileList);
            this.queueIndex = 0;
            this.croppedFiles = [];
            this.errorMessage = null;

            if (this.queue.length === 0) return;

            this.cropModalOpen = true;
            this.$nextTick(() => this.loadCurrent());
        },

        loadCurrent() {
            this.cropLoading = true;
            this.runner = createCropRunner({ aspectRatio: 2 / 3, imgEl: this.$refs.cropImage });
            this.runner.load(
                this.queue[this.queueIndex],
                () => { this.cropLoading = false; },
                () => { this.errorMessage = 'Une image est illisible, elle a été ignorée.'; this.skipImage(); },
            );
        },

        confirmCrop() {
            if (this.cropLoading || this.processing) return;
            this.processing = true;

            this.runner.confirm(
                this.queue[this.queueIndex].name,
                (croppedFile) => {
                    this.processing = false;
                    this.croppedFiles.push(croppedFile);
                    this.advance();
                },
                () => {
                    this.processing = false;
                    this.errorMessage = 'Recadrage impossible, essaie une autre image.';
                },
            );
        },

        skipImage() {
            this.advance();
        },

        advance() {
            this.queueIndex++;
            this.queueIndex < this.queue.length ? this.loadCurrent() : this.finishCropping();
        },

        resetCropState() {
            if (this.runner) this.runner.destroy();
            this.cropModalOpen = false;
            this.cropLoading = false;
            this.processing = false;
            this.$refs.fileInput.value = '';
        },

        cancelCropping() {
            this.resetCropState();
            this.queue = [];
            this.queueIndex = 0;
            this.croppedFiles = [];
        },

        finishCropping() {
            this.resetCropState();

            if (this.croppedFiles.length === 0) return;

            this.uploadProgress = 0;
            this.$wire.uploadMultiple(
                'newImages',
                this.croppedFiles,
                () => { this.croppedFiles = []; },
                () => { this.errorMessage = "Erreur lors de l'envoi des photos."; },
                (event) => { this.uploadProgress = event.detail.progress; },
            );
        },
    }));

    // --- Création d'un produit : file locale, envoyée avec le formulaire ---
    Alpine.data('productImageQueue', () => ({
        cropModalOpen: false,
        cropLoading: false,
        processing: false,
        errorMessage: null,
        selection: [],
        selectionIndex: 0,
        items: [], // [{ id, file, previewUrl }]
        runner: null,
        confirmingDelete: null,

        handleFiles(fileList) {
            const remainingSlots = 4 - this.items.length;
            this.selection = Array.from(fileList).slice(0, Math.max(remainingSlots, 0));
            this.selectionIndex = 0;
            this.errorMessage = null;

            if (this.selection.length === 0) return;

            this.cropModalOpen = true;
            this.$nextTick(() => this.loadCurrent());
        },

        loadCurrent() {
            this.cropLoading = true;
            this.runner = createCropRunner({ aspectRatio: 2 / 3, imgEl: this.$refs.cropImage });
            this.runner.load(
                this.selection[this.selectionIndex],
                () => { this.cropLoading = false; },
                () => { this.errorMessage = 'Une image est illisible, elle a été ignorée.'; this.skipImage(); },
            );
        },

        confirmCrop() {
            if (this.cropLoading || this.processing) return;
            this.processing = true;

            this.runner.confirm(
                this.selection[this.selectionIndex].name,
                (croppedFile) => {
                    this.processing = false;
                    this.items.push({
                        id: `${Date.now()}-${this.selectionIndex}`,
                        file: croppedFile,
                        previewUrl: URL.createObjectURL(croppedFile),
                    });
                    this.advance();
                },
                () => {
                    this.processing = false;
                    this.errorMessage = 'Recadrage impossible, essaie une autre image.';
                },
            );
        },

        skipImage() {
            this.advance();
        },

        advance() {
            this.selectionIndex++;
            this.selectionIndex < this.selection.length ? this.loadCurrent() : this.finishCropping();
        },

        resetCropState() {
            if (this.runner) this.runner.destroy();
            this.cropModalOpen = false;
            this.cropLoading = false;
            this.processing = false;
            this.$refs.fileInput.value = '';
        },

        cancelCropping() {
            this.resetCropState();
            this.selection = [];
            this.selectionIndex = 0;
        },

        finishCropping() {
            this.resetCropState();
            this.syncHiddenInput();
        },

        removeItem(id) {
            this.items = this.items.filter((item) => item.id !== id);
            this.syncHiddenInput();
        },

        reorder(orderedIds) {
            this.items = orderedIds.map((id) => this.items.find((item) => item.id === id)).filter(Boolean);
            this.syncHiddenInput();
        },

        // Reconstruit l'input <file> natif à partir de la file ordonnée.
        syncHiddenInput() {
            const dataTransfer = new DataTransfer();
            this.items.forEach((item) => dataTransfer.items.add(item.file));
            this.$refs.hiddenInput.files = dataTransfer.files;
        },
    }));
});