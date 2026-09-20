import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

document.addEventListener('alpine:init', () => {
    Alpine.data('singleImageCropper', ({ aspectRatio = 1, wireModel, initialUrl = null }) => ({
        cropModalOpen: false,
        cropLoading: false,   // image en cours de chargement dans le cropper
        processing: false,    // recadrage en cours de calcul
        uploading: false,     // envoi vers le serveur
        uploadProgress: 0,
        errorMessage: null,
        cropper: null,
        pendingFile: null,
        previewUrl: initialUrl,

        handleFile(fileList) {
            const file = fileList[0];
            if (!file) return;

            this.pendingFile = file;
            this.errorMessage = null;
            this.cropModalOpen = true;
            this.$nextTick(() => this.initCropper());
        },

        initCropper() {
            const img = this.$refs.cropImage;
            const reader = new FileReader();

            this.cropLoading = true;

            reader.onload = (e) => {
                img.src = e.target.result;

                if (this.cropper) this.cropper.destroy();

                this.cropper = new Cropper(img, {
                    aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                    ready: () => { this.cropLoading = false; },
                });
            };

            reader.onerror = () => {
                this.cancelCropping();
                this.errorMessage = 'Impossible de lire cette image.';
            };

            reader.readAsDataURL(this.pendingFile);
        },

        confirmCrop() {
            if (!this.cropper || this.cropLoading || this.processing) return;

            this.processing = true;

            // Ratio propre à chaque champ (16/7 couverture, 2/3 carte, 1 avatar).
            const maxWidth = 1200;
            const targetHeight = Math.round(maxWidth / aspectRatio);

            // Petit délai : laisse le navigateur afficher le spinner avant le calcul (bloquant).
            setTimeout(() => {
                this.cropper.getCroppedCanvas({
                    width: maxWidth,
                    height: targetHeight,
                    imageSmoothingQuality: 'high',
                }).toBlob((blob) => {
                    this.processing = false;

                    if (!blob) {
                        this.errorMessage = 'Recadrage impossible, essaie une autre image.';
                        return;
                    }

                    const croppedFile = new File([blob], this.pendingFile.name, { type: 'image/webp' });
                    this.previewUrl = URL.createObjectURL(blob);
                    this.finishCropping(croppedFile);
                }, 'image/webp', 0.85);
            }, 30);
        },

        cancelCropping() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            this.pendingFile = null;
            this.cropModalOpen = false;
            this.cropLoading = false;
            this.processing = false;
            this.$refs.fileInput.value = '';
        },

        finishCropping(croppedFile) {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            this.cropModalOpen = false;
            this.uploading = true;
            this.uploadProgress = 0;

            this.$wire.upload(
                wireModel,
                croppedFile,
                () => { this.uploading = false; this.$refs.fileInput.value = ''; },
                () => { this.uploading = false; this.errorMessage = "Erreur lors de l'envoi de la photo."; },
                (event) => { this.uploadProgress = event.detail.progress; },
            );
        },
    }));
});