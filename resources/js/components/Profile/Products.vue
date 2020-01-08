<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" v-model="query" @keydown.enter.prevent="search" >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click.prevent="search"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Photo</th>
                        <th scope="col">ID</th>
                        <th scope="col">Category title</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="product in products.data">
                        <td><img :src="`/images/products/${product.photo}`" alt=""></td>
                        <th>{{product.id}}</th>
                        <th>{{product.category[0].title}}</th>
                        <td>{{product.title}}</td>
                        <td>{{product.description | threePoints}}</td>
                        <td>{{product.price}}</td>
                        <td>
                            <button class="btn btn-primary" :title="'Edit'"
                                    @click.prevent="getProduct(product.id)"
                                    data-toggle="modal" data-target="#editMode"

                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" :title="'Delete'"
                                    @click.prevent="deleteProduct(product.id)"
                            >
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <router-link :to="{name:'product-item',params:{id:product.id}}">
                                <button class="btn btn-info" :title="'View'">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </router-link>

                        </td>

                    </tr>
                    </tbody>
                    <tfoot>
                    <tr><pagination :data="products" @pagination-change-page="getProducts"></pagination></tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="editMode" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitEdit(currentProductId)" enctype="multipart/form-data">
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
                                <CategoriesList  :categories="categories" :selectedID="form.category"  :class="{ 'is-invalid' : form.errors.has('category')  }"></CategoriesList>
                                <has-error :form="form" field="category"></has-error>

                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" class="form-control-file" id="photo"
                                       @change="onAttachmentChange"
                                       :class="{ 'is-invalid' : form.errors.has('photo')  }"
                                >
                                <has-error :form="form" field="photo"></has-error>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    import CategoriesList from './CategoriesList'
    import {attachmentMixin} from "../../mixins/attachmentMixin";

    export default {
        name: "AddProduct",
        mixins:[attachmentMixin],
        data() {
            return {
                categories:'',
                products: {},
                form: new Form({
                    title: '',
                    description: '',
                    price: '',
                    photo: '',
                    category:''
                }),
                currentProductId: '',
                query:''
            }
        },
        methods: {
            search(){
                this.$Progress.start();

                let query = this.query;
                this.axios.get('/api/findProduct?q=' + query)
                    .then((data) => {
                        this.$Progress.finish();
                        this.products = data.data;
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
            createProduct() {
                this.axios.post('/api/products-create').then((response) => {
                    console.log(response);
                })
            },
            getProducts(page = 1) {
                this.$Progress.start();
                this.axios.get('/api/products-get?page='+page).then(response => {
                    this.$Progress.finish();
                    this.products = response.data;
                }).catch((response) => {

                    this.$Progress.fail();
                })
            },
            getProduct(id) {
                this.form.get('/api/product-get/' + id).then((response) => {
                    this.form.fill(response.data[0]);
                    this.form.category = this.form.category[0].id;
                });
                this.currentProductId = id;

            },
            deleteProduct(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.axios.delete('/api/product-destroy/' + id).then(() => {
                            this.getProducts();

                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )

                        })
                    }
                })
            },
            submitEdit(id) {
                this.$Progress.start();
                this.form.put('/api/product-edit/' + id).then((response) => {
                    let message = response.data.message;
                    let messageType = response.data.messageType;
                    if (messageType === 'success') {
                        this.$Progress.finish();
                        Swal.fire({
                            position: 'top-end',
                            type: messageType,
                            title: message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#editMode').modal('hide');
                        this.getProducts();
                    } else {
                        this.$Progress.fail();
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                }).catch((response) => {
                    this.$Progress.fail();
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 3000
                    })
                })

            },

        },

        mounted() {
            this.getProducts();
            this.getAllCategories();
            let pr = this;
            Event.$on('formParentID',function (data) {
                pr.form.category = data[0];
            });
        },
        filters: {
            threePoints: function (value) {
                if (value.length > 20) {
                    return value.slice(0, 20) + '...';
                } else {
                    return value;
                }
            }
        },
        components: {
            CategoriesList,
        },
    }
</script>

<style scoped>
    tr:hover {
        background: #e4e4e4;
        cursor: pointer;
    }

    img {
        width: 150px;
    }

    td, th {
        vertical-align: unset;
    }
</style>
