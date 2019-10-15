<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form @submit.prevent="submit()" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" aria-describedby="emailHelp"
                               v-model="form.title" :class="{ 'is-invalid' : form.errors.has('title')  }">
                        <has-error :form="form" field="title"></has-error>

                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <vue-editor v-model="form.description" id="description"
                                    :class="{ 'border-danger' : form.errors.has('description')  }"></vue-editor>
                        <div class="red">{{form.errors.get('description')}}</div>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" min="0"
                               oninput="validity.valid||(value='');" v-model="form.price"
                               :class="{ 'is-invalid' : form.errors.has('price')  }">
                        <has-error :form="form" field="price"></has-error>

                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <CategoriesList  :categories="categories"  :class="{ 'is-invalid' : form.errors.has('category')  }"></CategoriesList>
                        <has-error :form="form" field="category"></has-error>

                    </div>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" class="form-control-file" id="photo"
                               @change="onAttachmentChange" :class="{ 'is-invalid' : form.errors.has('photo')  }"
                        >
                        <has-error :form="form" field="photo"></has-error>
                    </div>
                    <button type="submit" class="btn btn-success">Add new product</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import CategoriesList from './CategoriesList'

    export default {
        name: "AddProduct",

        data() {
            return {
                categories: {},
                form: new Form({
                    title: '',
                    description: '',
                    price: '',
                    photo: '',
                    category: ''
                })
            }
        },
        methods: {
            // todo fix duplicate of code, here getAllCategories and in Categories.vue
            getAllCategories() {
                this.axios.get('/api/category').then((response) => {
                    this.categories = response.data;
                });
            },
            submit() {
                this.$Progress.start();
                this.form.post('/api/product-create').then((response) => {
                    this.$Progress.finish();
                    let message = response.data.message;
                    let messageType = response.data.messageType;
                    Swal.fire({
                        position: 'top-end',
                        type: messageType,
                        title: message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    this.form.reset();
                }).catch((response) => {
                    this.$Progress.fail();
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    })
                });
            },
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
        },

        mounted() {
            let categoriesComponent = this;
            Event.$on('formParentID', function ([parentID, selectedID]) {
                if (selectedID) {
                    // categoriesComponent.formEdit.parent_id = parentID;
                } else {
                    categoriesComponent.form.category = parentID;
                }
            })
        },
        created(){
            this.getAllCategories();
        },
        components: {
            CategoriesList,
        },
    }
</script>

<style>
    .ql-editor p {
        text-transform: unset;
    }

    .border-danger {
        border: 1px solid red;
    }
</style>
