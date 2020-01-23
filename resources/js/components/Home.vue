<template>
    <section class="main-wrap">
        <section class="main">
            <div class="main-carousel">
                <div class="owl-carousel owl-theme main-slide">
                    <div class="visual slide-item owl-lazy" data-src="images/main-picture1.png"></div>
                    <div class="visual slide-item owl-lazy" data-src="images/main-picture2.png"></div>
                    <div class="visual slide-item owl-lazy" data-src="images/main-picture3.jpg"></div>
                    <div class="visual slide-item owl-lazy" data-src="images/main-picture4.jpg"></div>
                </div>
            </div>
            <div class="main-title">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-6">
                            <div class="visual-text">
                                <div class="visual-title">
                                    <p>Новинка</p>
                                    <h1>ARIA MAC-STD</h1>
                                </div>
                                <div class="visual-description">
                                    <p>Станьте ближе к любимой музыке вместе с P5 Series 2. У последней версии полностью
                                        обновлена
                                        конструкция динамиков, что подняло качество звука на новый уровень, а материалы
                                        высокого
                                        качества обеспечивают комфорт. </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-2 offset-5 d-flex justify-content-around">

                            <div id="customDots"></div>


                        </div>


                    </div>

                </div>
            </div>
        </section>
        <section class="brands">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="brands-items owl-carousel owl-theme brands-slide d-flex">
                            <div class="brands-item"><img src="images/aria.png" alt=""></div>
                            <div class="brands-item"><img src=" images/d'addario.png" alt=""></div>
                            <div class="brands-item"><img src="images/jts.png" alt=""></div>
                            <div class="brands-item"><img src="images/ABK.png" alt=""></div>
                            <div class="brands-item"><img src="images/Robe.png" alt=""></div>
                            <div class="brands-item"><img src="images/pioneer.png" alt=""></div>
                            <div class="brands-item"><img src="images/schecter.png" alt=""></div>
                            <div class="brands-item"><img src="images/aria.png" alt=""></div>
                            <div class="brands-item"><img src=" images/d'addario.png" alt=""></div>
                            <div class="brands-item"><img src="images/jts.png" alt=""></div>
                            <div class="brands-item"><img src="images/ABK.png" alt=""></div>
                            <div class="brands-item"><img src="images/Robe.png" alt=""></div>
                            <div class="brands-item"><img src="images/pioneer.png" alt=""></div>
                            <div class="brands-item"><img src="images/schecter.png" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 ">
                        <h4 class="text-center">Все производители</h4>
                        <div class="line"><img src="images/----.png" alt=""></div>

                    </div>
                </div>
            </div>
        </section>
        <section class="discount" >
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="discount-menu d-flex align-items-center flex-column">
                            <h2 class="mb-5">Новинки</h2>
                        </div>

                    </div>
                </div>
                <products-slider :products="newProducts"></products-slider>
            </div>
        </section>
        <section class="mb-5">
            <div class="container">
                <div class="discount-menu d-flex align-items-center flex-column" >
                    <h2 class="mb-5">Some products</h2>
                    <div class="row">
                        <router-link class="card col-lg-4 " style="width: 18rem;"
                                     v-for="product in randProducts"
                                     :to="{name:'product-item',params:{id:product.id}}">
                            <img :src="`/images/products/${product.photo}`" class="card-img-top ">

                            <div class="card-body text-center">
                                <h5 class="card-title text-center">{{product.title}}</h5>
                                {{product.description | threePoints}}
                                <div class="price">
                                    {{product.price}} $
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </section>
    </section>
</template>

<script>
    import {filtersMixin} from "../mixins/filtersMixin";
    import ProductsSlider from "./inserts/ProductsSlider";

    export default {
        name: "Home",
        data(){
            return{
                newProducts:{},
                randProducts:[],
            }
        },
        components:{
            'products-slider':ProductsSlider
        },
        methods:{
            getNewProducts(){
                this.axios.get('/api/new-products').then(response => {
                    this.newProducts = response.data;

                });
            },
            getRandomProducts(){
              this.axios.get('/api/products-random').then(response => {
                this.randProducts = response.data;
              }).catch(error => {
                  console.log(error)
              })
            },
        },
        mixins:[filtersMixin],
        mounted: function () {
            this.getNewProducts();
            this.getRandomProducts();
            $(document).ready(function () {
                $('.main-slide').owlCarousel({
                    lazyLoad:true,
                    items:1,
                    autoplay: true,
                    smartSpeed: 1500,
                    nav:false,
                    dots: true,
                    loop : true,
                    animateOut: "fadeOut",
                    mouseDrag: false,
                    touchDrag:false,

                });
                $('.brands-slide').owlCarousel({
                    loop:true,
                    nav:true,
                    dots:false,
                    items:7,
                    margin:35
                });

            });
        }
    }
</script>

<style scoped>

</style>
