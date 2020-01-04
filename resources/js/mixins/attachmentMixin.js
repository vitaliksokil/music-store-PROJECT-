export const attachmentMixin = {
    methods:{
        onAttachmentChange(e) {
            let file = e.target.files[0];
            let reader = new FileReader();
            if (!file.type.match(/image/)) {
                this.form.errors.set('photo', 'File should be an image');
                this.form.photo = '';
                return;
            }
            if (file.size / 1024 > 2048) {
                this.form.errors.set('photo', 'Size should be less then 2048KB');
                this.form.photo = '';
                return;
            }
            let ap = this;
            reader.onloadend = function (file) {
                ap.form.photo = reader.result;
            };
            reader.readAsDataURL(file);
        },
    }
}
