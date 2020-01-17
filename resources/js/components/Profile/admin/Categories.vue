<template>
    <div class="container">
        <div class="row justify-content-between">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search" v-model="query"
                       @keydown.enter.prevent="search">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" @click.prevent="search"><i
                        class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-lg-4">
                <form @submit.prevent="addNewCategory">
                    <h3 class="alert alert-success text-center">Add new category</h3>
                    <div class="form-group">
                        <label for="title">Category name</label>
                        <input type="text" class="form-control" id="title"
                               placeholder="Category name" v-model="form.title"
                               :class="{ 'is-invalid' : form.errors.has('title')  }">
                        <has-error :form="form" field="title"></has-error>

                    </div>
                    <div class="form-group">
                        <label for="parent_id">Parent category</label>
                        <CategoriesList :categories="categories"></CategoriesList>

                    </div>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" class="form-control-file" id="photo"
                               @change="onAttachmentChange(form)"
                               :class="{ 'is-invalid' : form.errors.has('photo')  }"
                        >
                        <has-error :form="form" field="photo"></has-error>

                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="col-lg-7">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Category photo</th>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Parent id</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="category in categories">
                        <td><img :src="`/images/categories/${category.photo}`" alt=""></td>
                        <th>{{category.id}}</th>
                        <td>{{category.title}}</td>
                        <td>{{category.parent_id}}</td>
                        <td>
                            <button class="btn btn-primary" :title="'Edit'"
                                    @click.prevent="getCategory(category.id)"
                                    data-toggle="modal" data-target="#editMode"

                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" :title="'Delete'"
                                    @click.prevent="deleteCategory(category.id)"
                            >
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <router-link :to="{name:'category',params:{id:category.id}}">
                                <button class="btn btn-info" :title="'View'">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </router-link>

                        </td>


                    </tr>
                    </tbody>
                </table>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="editMode" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="editCategory">
                                <h3 class="alert alert-primary text-center">Edit {{formEdit.title}} category</h3>
                                <div class="form-group">
                                    <label for="titleE">Category name</label>
                                    <input type="text" class="form-control" id="titleE"
                                           placeholder="Category name" v-model="formEdit.title"
                                           :class="{ 'is-invalid' : formEdit.errors.has('title')  }">
                                    <has-error :form="formEdit" field="title"></has-error>

                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Parent category</label>
                                    <CategoriesList :categories="categories"
                                                    :selectedID="formEdit.parent_id"></CategoriesList>

                                </div>
                                <div class="form-group">
                                    <label for="photoE">Photo</label>
                                    <input type="file" class="form-control-file" id="photoE"
                                           @change="onAttachmentChange(formEdit)"
                                           :class="{ 'is-invalid' : form.errors.has('photo')  }"
                                    >
                                    <has-error :form="form" field="photo"></has-error>

                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
    import CategoriesList from "./CategoriesList";
    import VueBus from "vue";

    export default {
        name: "Categories",
        data() {
            return {
                categories: {},
                query: '',
                form: new Form({
                    title: '',
                    photo: '',
                    parent_id: ''
                }),
                formEdit: new Form({
                    id: '',
                    title: '',
                    photo: '',
                    parent_id: ''
                }),
            }
        },
        methods: {
            search() {
                this.$Progress.start();

                let query = this.query;
                this.axios.get('/api/findCategory?q=' + query)
                    .then((data) => {
                        this.$Progress.finish();
                        this.categories = data.data;
                    }).catch(() => {

                    this.$Progress.fail();

                    Swal.fire(
                        'Error!',
                        'Something\'s gone wrong.',
                        'error'
                    )
                });
            },
            getAllCategories() {
                this.axios.get('/api/category').then((response) => {
                    this.categories = response.data;
                });
            },
            getCategory(id) {
                this.axios.get('/api/category/' + id).then((response) => {
                    this.formEdit.fill(response.data);
                });
            },
            editCategory() {
                this.formEdit.put('/api/category/' + this.formEdit.id).then((response) => {
                    this.getAllCategories();
                    $('#editMode').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    Event.$emit('changesInCategories')
                });
            },
            addNewCategory() {
                this.$Progress.start();
                this.form.post('/api/category').then((response) => {
                    this.$Progress.finish();
                    this.getAllCategories();
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.form.reset();
                    Event.$emit('changesInCategories')
                }).catch(() => {
                    this.$Progress.fail();

                })
            },
            deleteCategory(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this! All products and subcategories(including products) of the category will be deleted!!!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.axios.delete('/api/category/' + id).then((response) => {
                            this.getAllCategories();
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: 'Category was deleted successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            Event.$emit('changesInCategories')
                        });
                    }
                })
            },
            onAttachmentChange(form) {
                let file = event.target.files[0];
                let reader = new FileReader();
                if (!file.type.match(/image/)) {
                    form.errors.set('photo', 'File should be an image');
                    form.photo = '';
                    return;
                }
                if (file.size / 1024 > 2048) {
                    form.errors.set('photo', 'Size should be less then 2048KB');
                    form.photo = '';
                    return;
                }
                reader.onloadend = function (file) {
                    form.photo = reader.result;
                };
                reader.readAsDataURL(file);
            }
        },
        mounted() {
            let categoriesComponent = this;
            Event.$on('formParentID', function ([parentID, selectedID]) {
                if (selectedID || selectedID === null) {
                    categoriesComponent.formEdit.parent_id = parentID;
                } else {
                    categoriesComponent.form.parent_id = parentID;
                }
            })

        },
        components: {
            CategoriesList,
        },

        created() {
            this.getAllCategories();
        }
    }
</script>

<style scoped>
    form {
        padding: 0;
    }

    img {
        width: 150px;
    }
</style>
