// resources/js/single-image-crop.js
//
// Widget générique : sélection d'un fichier -> recadrage (ratio configurable)
// -> upload de l'image recadrée vers une propriété Livewire donnée.
// Utilisé pour les 3 photos de la devanture (couverture, carte, ronde),
// chacune avec son propre ratio, via partials/storefront-image-picker.blade.php.

import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

document.addEventListener('alpine:init', () => {
    Alpine.data('singleImageCropper', ({ aspectRatio = 1, wireModel, initialUrl = null }) => ({
        cropModalOpen: false,
        cropper: null,
        pendingFile: null,
        previewUrl: initialUrl,
        uploading: false,

        handleFile(fileList) {
            const file = fileList[0];
            if (!file) return;

            this.pendingFile = file;
            this.cropModalOpen = true;
            this.$nextTick(() => this.initCropper());
        },

        initCropper() {
            const img = this.$refs.cropImage;
            const reader = new FileReader();

            reader.onload = (e) => {
                img.src = e.target.result;

                if (this.cropper) {
                    this.cropper.destroy();
                }

                this.cropper = new Cropper(img, {
                    aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                });
            };

            reader.readAsDataURL(this.pendingFile);
        },

        confirmCrop() {
            if (!this.cropper) return;

            // Cible une largeur max raisonnable, hauteur calculée selon le ratio propre
            // à ce champ (16/7 pour la couverture, 2/3 pour la carte, 1 pour l'avatar).
            const maxWidth = 1200;
            const targetHeight = Math.round(maxWidth / aspectRatio);

            this.cropper.getCroppedCanvas({
                width: maxWidth,
                height: targetHeight,
                imageSmoothingQuality: 'high',
            }).toBlob((blob) => {
                const croppedFile = new File([blob], this.pendingFile.name, { type: 'image/webp' });
                this.previewUrl = URL.createObjectURL(blob);
                this.finishCropping(croppedFile);
            }, 'image/webp', 0.85);
        },

        cancelCropping() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            this.pendingFile = null;
            this.cropModalOpen = false;
            this.$refs.fileInput.value = '';
        },

        finishCropping(croppedFile) {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            this.cropModalOpen = false;
            this.uploading = true;

            this.$wire.upload(
                wireModel,
                croppedFile,
                () => { this.uploading = false; this.$refs.fileInput.value = ''; },
                () => { this.uploading = false; alert("Erreur lors de l'envoi de la photo."); },
                () => {}
            );
        },
    }));
});