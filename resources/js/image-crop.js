import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

document.addEventListener('alpine:init', () => {
    Alpine.data('productImageManager', () => ({
        confirmingDelete: null,
        cropModalOpen: false,
        queue: [],
        queueIndex: 0,
        croppedFiles: [],
        cropper: null,

        handleFiles(fileList) {
            this.queue = Array.from(fileList);
            this.queueIndex = 0;
            this.croppedFiles = [];

            if (this.queue.length === 0) return;

            this.cropModalOpen = true;
            this.$nextTick(() => this.loadImageIntoCropper());
        },

        loadImageIntoCropper() {
            const file = this.queue[this.queueIndex];
            const img = this.$refs.cropImage;
            const reader = new FileReader();

            reader.onload = (e) => {
                img.src = e.target.result;

                if (this.cropper) {
                    this.cropper.destroy();
                }

                this.cropper = new Cropper(img, {
                    aspectRatio: 2 / 3,
                    viewMode: 1,
                    autoCropArea: 1,
                    background: false,
                });
            };

            reader.readAsDataURL(file);
        },

        confirmCrop() {
            if (!this.cropper) return;

            this.cropper.getCroppedCanvas({ width: 800, height: 1200 }).toBlob((blob) => {
                const originalName = this.queue[this.queueIndex].name;
                const croppedFile = new File([blob], originalName, { type: 'image/jpeg' });
                this.croppedFiles.push(croppedFile);

                this.queueIndex++;
                this.queueIndex < this.queue.length ? this.loadImageIntoCropper() : this.finishCropping();
            }, 'image/jpeg', 0.9);
        },

        skipImage() {
            this.queueIndex++;
            this.queueIndex < this.queue.length ? this.loadImageIntoCropper() : this.finishCropping();
        },

        cancelCropping() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
            this.queue = [];
            this.queueIndex = 0;
            this.croppedFiles = [];
            this.cropModalOpen = false;
            this.$refs.fileInput.value = '';
        },

        finishCropping() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
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
});