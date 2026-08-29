import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

/**
 * Cœur partagé du recadrage : prend une file de fichiers, ouvre le cropper
 * pour chacun tour à tour, et appelle onCropped(file) pour chaque image
 * validée. Utilisé à la fois par la gestion de photos en édition
 * (upload direct vers Livewire) et par la file d'attente en création
 * (assemblée dans un input natif avant soumission du formulaire).
 */
function createCropRunner({ aspectRatio, imgEl }) {
    let cropper = null;

    return {
        load(file, onReady) {
            const reader = new FileReader();

            reader.onload = (e) => {
                imgEl.src = e.target.result;

                if (cropper) cropper.destroy();

                cropper = new Cropper(imgEl, {
                    aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                });

                onReady();
            };

            reader.readAsDataURL(file);
        },

        confirm(originalName, onCropped) {
            if (!cropper) return;

            cropper.getCroppedCanvas().toBlob((blob) => {
                onCropped(new File([blob], originalName, { type: 'image/jpeg' }));
            }, 'image/jpeg', 0.9);
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
    // --- Édition d'un produit existant : upload direct vers Livewire ---
    Alpine.data('productImageManager', () => ({
        confirmingDelete: null,
        cropModalOpen: false,
        queue: [],
        queueIndex: 0,
        croppedFiles: [],
        runner: null,

        handleFiles(fileList) {
            this.queue = Array.from(fileList);
            this.queueIndex = 0;
            this.croppedFiles = [];

            if (this.queue.length === 0) return;

            this.cropModalOpen = true;
            this.$nextTick(() => this.loadCurrent());
        },

        loadCurrent() {
            this.runner = createCropRunner({ aspectRatio: 2 / 3, imgEl: this.$refs.cropImage });
            this.runner.load(this.queue[this.queueIndex], () => {});
        },

        confirmCrop() {
            this.runner.confirm(this.queue[this.queueIndex].name, (croppedFile) => {
                this.croppedFiles.push(croppedFile);
                this.advance();
            });
        },

        skipImage() {
            this.advance();
        },

        advance() {
            this.queueIndex++;
            this.queueIndex < this.queue.length ? this.loadCurrent() : this.finishCropping();
        },

        cancelCropping() {
            if (this.runner) this.runner.destroy();
            this.queue = [];
            this.queueIndex = 0;
            this.croppedFiles = [];
            this.cropModalOpen = false;
            this.$refs.fileInput.value = '';
        },

        finishCropping() {
            if (this.runner) this.runner.destroy();
            this.cropModalOpen = false;
            this.$refs.fileInput.value = '';

            if (this.croppedFiles.length > 0) {
                this.$wire.uploadMultiple(
                    'newImages',
                    this.croppedFiles,
                    () => { this.croppedFiles = []; },
                    () => { alert("Erreur lors de l'envoi des photos."); },
                    () => {}
                );
            }
        },
    }));

    // --- Création d'un produit : file d'attente locale + réorganisation,
    //     assemblée dans un <input type="file"> natif avant soumission du
    //     formulaire (pas de produit en base pour uploader via Livewire).
    Alpine.data('productImageQueue', () => ({
        cropModalOpen: false,
        selection: [],
        selectionIndex: 0,
        items: [], // [{ id, file, previewUrl }]
        runner: null,
        confirmingDelete: null,

        handleFiles(fileList) {
            const remainingSlots = 4 - this.items.length;
            this.selection = Array.from(fileList).slice(0, Math.max(remainingSlots, 0));
            this.selectionIndex = 0;

            if (this.selection.length === 0) return;

            this.cropModalOpen = true;
            this.$nextTick(() => this.loadCurrent());
        },

        loadCurrent() {
            this.runner = createCropRunner({ aspectRatio: 2 / 3, imgEl: this.$refs.cropImage });
            this.runner.load(this.selection[this.selectionIndex], () => {});
        },

        confirmCrop() {
            this.runner.confirm(this.selection[this.selectionIndex].name, (croppedFile) => {
                this.items.push({
                    id: `${Date.now()}-${this.selectionIndex}`,
                    file: croppedFile,
                    previewUrl: URL.createObjectURL(croppedFile),
                });
                this.advance();
            });
        },

        skipImage() {
            this.advance();
        },

        advance() {
            this.selectionIndex++;
            this.selectionIndex < this.selection.length ? this.loadCurrent() : this.finishCropping();
        },

        cancelCropping() {
            if (this.runner) this.runner.destroy();
            this.selection = [];
            this.selectionIndex = 0;
            this.cropModalOpen = false;
            this.$refs.fileInput.value = '';
        },

        finishCropping() {
            if (this.runner) this.runner.destroy();
            this.cropModalOpen = false;
            this.$refs.fileInput.value = '';
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

        // Reconstruit le <input type="file" multiple> réel à partir de la file
        // d'attente ordonnée : c'est ce que le formulaire natif enverra.
        syncHiddenInput() {
            const dataTransfer = new DataTransfer();
            this.items.forEach((item) => dataTransfer.items.add(item.file));
            this.$refs.hiddenInput.files = dataTransfer.files;
        },
    }));
});