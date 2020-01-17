<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="discounts-items d-flex justify-content-around">
                <router-link :to="{name:'product-item',params:{id:product.id}}" class="discount-item-wrap"
                             v-for="product in products">
                    <div class="discount-item d-flex flex-column">
                        <div class="discount-item-img"><img :src="`/images/products/${product.photo}`" alt=""></div>
                        <div class="discount-items-text">
                            <h3>
                                {{product.title | threePointsTitle}}
                            </h3>
                            {{product.description | threePoints}}
                            <div class="price">
                                {{product.price}} $
                            </div>

                        </div>
                    </div>
                    <div class="action align-self-center">
                        <ul class="d-flex justify-content-center">
                            <li @click.prevent="addToShoppingCart(product.id)"><i class="fas fa-shopping-basket"></i>
                            </li>
                            <li @click.prevent="addToWishlist(product.id)"><i class="fas fa-heart"></i></li>
                        </ul>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import {filtersMixin} from "../../mixins/filtersMixin";
    import {shoppingCartMixin} from "../../mixins/shoppingCartMixin";
    import {wishlistMixin} from "../../mixins/wishlistMixin";

    export default {
        name: "slider",
        props: ['products'],
        mixins: [filtersMixin, shoppingCartMixin, wishlistMixin],
        methods: {
            setSlick() {
                let sliders = document.getElementsByClassName('discounts-items');
                for(let item of sliders){
                    let slidesToShow = 5;
                    if (this.products.length < 5) {
                        slidesToShow = this.products.length;
                    }
                    setTimeout(function () {
                        $('.discounts-items').slick({
                            infinite: true,
                            slidesToShow: slidesToShow,
                            slidesToScroll: 1,
                            zIndex: 0,
                            prevArrow: "<button type=\"button\" class=\"btn-prev\"><i class=\"fas fa-chevron-left\"></i></button>",
                            nextArrow: "<button type=\"button\" class=\"btn-next\"><i class=\"fas fa-chevron-right\"></i></button>",
                            adaptiveHeight: true

                        });
                    }, 0)
                }
            }
        },
        updated(){
            this.setSlick();
        },
    }
</script>

<style scoped>

</style>
