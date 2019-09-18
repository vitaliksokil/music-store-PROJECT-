<template>
    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-lg-6 d-flex justify-content-center">
                <div class="img"><img :src="`/images/products/${productItem.photo}`" alt=""></div>
            </div>
            <div class="col-lg-6">
                <div class="product-info">
                    <div class="title"><h2>{{productItem.title}}</h2></div>
                    <div class="price">Price:<span>{{productItem.price}}$</span></div>
                    <hr>
                    <h2>Description</h2>
                    <div class="description" v-html="productItem.description"></div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductItem",
        data() {
            return {
                currentProductID: this.$route.params.id,
                productItem:{
                    title:'',
                    price:'',
                    description:'',
                    photo:''
                }
            }
        },
        methods: {
            getCurrentProduct() {
                this.axios.get('/api/product-get-current/' + this.currentProductID).then((response) => {
                    this.productItem.title = response.data.title;
                    this.productItem.price = response.data.price;
                    this.productItem.description = response.data.description;
                    this.productItem.photo = response.data.photo;
                }).catch();
            }
        },
        mounted() {
            this.getCurrentProduct();
        }

    }
</script>

<style scoped>
    h2{
        text-align: left;
    }
    .title{
        margin-bottom: 40px;
    }
    img{
        width: 100%;
    }
.price{
    font-size: 30px;
    margin-bottom: 70px;
}
    .price span{
background: #d8d8d8;
        padding: 3px 13px;
        border-radius: 5px;
    }
    .description{
        padding: 30px 0;
    }
    .product-info{
        margin-left: 30px;
    }
</style>
