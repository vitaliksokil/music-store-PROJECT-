<template>
    <section class="hold-transition skin-yellow sidebar-mini">
        <div class="wrapper">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree"
                        v-if="!(Array.isArray(allCategories) && allCategories.length)">
                        <li v-if="currentCategory.parent_id" class="mb-3">
                            <router-link :to="{name:'category',params:{id:currentCategory.parent_id}}" class="bg-dark">
                                <i class="fas fa-arrow-left"></i> Parent category
                            </router-link>
                        </li>
                        <li class="mb-3" v-else>
                            <router-link :to="{name:'category',params:{id:'all'}}" class="bg-dark">
                                <i class="fas fa-arrow-left"></i> All categories
                            </router-link>
                        </li>
                        <li>
                            <img :src='`/images/categories/${currentCategory.photo}`' alt="">
                        </li>
                        <li>
                            <h2> {{currentCategory.title}}</h2>
                        </li>
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content" v-if="!(Array.isArray(allCategories) && allCategories.length)">
                    <div class="container"
                         v-if="Array.isArray(currentCategory.children) && currentCategory.children.length ">
                        <h2>Subcategories</h2>
                        <div class="row">
                            <router-link class="card col-lg-4 " style="width: 18rem;"
                                         v-for="category in currentCategory.children" :key="category.children"
                                         :to="{name:'category',params:{id:category.id}}">
                                <img :src='`/images/categories/${category.photo}`' class="card-img-top ">

                                <div class="card-body">
                                    <h5 class="card-title text-center">{{category.title}}</h5>
                                </div>
                            </router-link>
                        </div>
                        <hr>
                    </div>
                    <div class="container" v-if="Array.isArray(products) && products.length">
                        <h2>Products</h2>
                        <div class="row justify-content-between products">
                            <router-link :to="{name:'product-item',params:{id:product.id}}" class="col-lg-3 m-3" v-for="product in products">
                                <div class="discounts-items d-flex justify-content-around">
                                    <div class="discount-item-wrap">
                                        <div class="discount-item d-flex flex-column">
                                            <div class="discount-item-img"><img :src="`/images/products/${product.photo}`" alt=""></div>
                                            <div class="discount-items-text">
                                                <h3>
                                                    {{product.title}}
                                                </h3>
                                                {{product.description | threePoints}}
                                                <div class="price">
                                                    <span>{{product.price}}</span> {{product.price}} <i class="fas fa-ruble-sign"></i>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="action align-self-center">
                                            <ul class="d-flex justify-content-center">
                                                <li><i class="fas fa-shopping-basket"></i></li>
                                                <li><i class="fas fa-chart-bar"></i></li>
                                                <li><i class="fas fa-heart"></i></li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </router-link>
                        </div>
                    </div>
                    <div class="container" v-else>
                        <h2 class="red">There are no products</h2>
                    </div>
                </section>
                <section class="content" v-else>
                    <div class="container">

                        <div class="container">
                            <h2>Categories</h2>
                            <div class="row">
                                <router-link class="card col-lg-4 " style="width: 18rem;"
                                             v-for="category in allCategories" v-if="!category.parent_id"
                                             :to="{name:'category',params:{id:category.id}}">
                                    <img :src='`/images/categories/${category.photo}`' class="card-img-top ">

                                    <div class="card-body">
                                        <h5 class="card-title text-center">{{category.title}}</h5>
                                    </div>
                                </router-link>
                            </div>
                            <hr>
                        </div>

                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
        </div>

        <!-- ./wrapper -->
    </section>

</template>

<script>
    import {filtersMixin} from "../../mixins/filtersMixin";

    export default {
        name: "Category",
        mixins:[filtersMixin],
        data() {
            return {
                id: this.$route.params.id,
                products: {},
                currentCategory: {},
                allCategories: {},
            }
        },
        methods: {
            getCurrentCategory() {
                if (this.id == 'all') {
                    this.currentCategory = {};
                    this.axios.get('/api/category').then((response) => {
                        this.allCategories = response.data;
                    });
                } else {
                    this.allCategories = {};
                    this.axios.get('/api/category/' + this.id).then((response) => {
                        this.currentCategory = response.data;
                        if (!this.currentCategory) {
                            this.$router.push({name: '404'})
                        }
                    });
                    this.getProducts();
                }
            },
            getProducts() {
                this.axios.get('/api/get-products-by-category/' + this.id).then((response) => {
                    this.products = response.data;
                });
            }
        },
        mounted() {
            this.getCurrentCategory();
        },
        watch: {
            $route() {
                this.id = this.$route.params.id;
                this.getCurrentCategory();
            }
        },


    }
</script>

<style scoped>
    .main-sidebar {
        background: #af9559;
        width: 300px;
    }

    .sidebar-menu img {
        width: 250px;
        margin-left: 25px;
    }

    h2 {
        white-space: normal;
    }

    .card-body {
        flex: 0;
        margin-top: auto;
    }

    .card-img-top {
        margin-top: auto;
        max-height: 200px;
        max-width: 270px;
        margin-right: auto;
        margin-left: auto;
    }

    .card-text {
        color: #777;
    }

    .price {
        color: #ed1c24;
    }
    .products a:hover{
        text-decoration: none;
    }
</style>
