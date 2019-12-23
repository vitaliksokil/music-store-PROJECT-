<template>
    <div class="wrap">
        <div class="container">
            <div class="col-lg-12">
                <h2>Search results: </h2>
                <div class="container" v-if="Array.isArray(products) && products.length">
                    <h2>Products</h2>
                    <div class="row justify-content-between products">
                        <router-link :to="{name:'product-item',params:{id:product.id}}" class="col-lg-3 m-3"
                                     v-for="product in products">
                            <div class="discounts-items d-flex justify-content-around">
                                <div class="discount-item-wrap">
                                    <div class="discount-item d-flex flex-column">
                                        <div class="discount-item-img"><img :src="`/images/products/${product.photo}`"
                                                                            alt=""></div>
                                        <div class="discount-items-text">
                                            <h3>
                                                {{product.title}}
                                            </h3>
                                            <p v-html="product.description"></p>
                                            <div class="price">
                                                <span>{{product.price}}</span> {{product.price}} <i
                                                class="fas fa-ruble-sign"></i>
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
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Search",
        data() {
            return {
                products: {},
                query: this.$route.params.query
            }
        },
        methods: {
            search() {
                this.$Progress.start();
                let query = this.query;
                this.axios.get('/api/findProduct?q=' + this.query)
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
