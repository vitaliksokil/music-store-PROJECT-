<template>
    <div class="wrap">
        <div class="container">
            <div class="col-lg-12">
                <h2>Search results: </h2>
                <div class="container" v-if="Array.isArray(products) && products.length">
                    <h2>Products</h2>
                    <div class="container" v-if="products">
                        <h2>Products</h2>
                        <div class="dropdown mb-3 text-center">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="sort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Sort by
                            </button>
                            <div class="dropdown-menu" aria-labelledby="sort">
                                <button class="dropdown-item" type="button" @click.prevent="sort='title';order='asc';search()">title(ascending)</button>
                                <button class="dropdown-item" type="button" @click.prevent="sort='title';order='desc';search()">title(descending)</button>
                                <button class="dropdown-item" type="button" @click.prevent="sort='price';order='asc';search()">price(ascending)</button>
                                <button class="dropdown-item" type="button" @click.prevent="sort='price';order='desc';search()">price(descending)</button>
                                <button class="dropdown-item" type="button" @click.prevent="sort='created_at';order='asc';search()">date(ascending)</button>
                                <button class="dropdown-item" type="button" @click.prevent="sort='created_at';order='desc';search()">date(descending)</button>
                            </div>
                        </div>
                        <div class="row">
                            <router-link class="card col-lg-4 " style="width: 18rem;"
                                         v-for="product in products"
                                         :to="{name:'product-item',params:{id:product.id}}">
                                <img :src="`/images/products/${product.photo}`" class="card-img-top ">

                                <div class="card-body text-center">
                                    <h5 class="card-title text-center">{{product.title}}</h5>
                                    {{product.description | threePoints}}
                                    <div class="price">
                                        {{product.price}} <i class="fas fa-ruble-sign"></i>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="container" v-else>
                    <h2 class="red">There are no products</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {filtersMixin} from "../mixins/filtersMixin";

    export default {
        name: "Search",
        mixins:[filtersMixin],
        data() {
            return {
                products: {},
                query: this.$route.params.query,
                sort:'created_at',
                order:'desc'
            }
        },
        methods: {
            search() {
                this.$Progress.start();
                let query = this.query;
                this.axios.get('/api/findProduct?q=' + this.query +'&sort='+this.sort+'&order='+this.order)
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
            }
        },
        mounted() {
            this.search();
            let s = this;
            Event.$on('mainSearchQuery', function (query) {
                if (s.query == query) {
                    return
                }
                s.query = query;
                s.$route.params.query = s.query;
                s.$router.replace({name: 'search', params: {query: s.query}});
                s.search();
            })
        }
    }
</script>

<style scoped>

</style>
