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
        <section class="discount">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="discount-menu d-flex align-items-center flex-column">
                            <h2 class="mb-5">Новинки</h2>
                        </div>

                    </div>
                </div>
                <products-slider :products="newProducts"></products-slider>
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <hr>
                    </div>
                    <div class="col-lg-4 offset-1 d-flex justify-content-between">
                        <h4 class="text-center">Все Предложения</h4>
                        <h4 class="text-center">Подписаться на распродажи</h4>
                    </div>
                    <div class="col-lg-3 offset-1">
                        <hr>
                    </div>
                </div>
            </div>
        </section>
        <section class="offers">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="offers-wrap">
                            <div class="offers-title">
                                <h2>Услуги</h2>
                            </div>
                            <div class="offers-items">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="offers-item">
                                            <div class="offers-item-title">
                                                <img src="images/Group%204.png" alt="">
                                                <h6>Доставка до дверей</h6>
                                            </div>
                                            <div class="offers-item-text">
                                                <p>Цикл, в том числе, выстраивает пласт. Как мы уже знаем, форшлаг
                                                    регрессийно
                                                    образует звукорядный хорус.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="offers-item">
                                            <div class="offers-item-title">
                                                <img src="images/Group%204.png" alt="">
                                                <h6>Доставка до дверей</h6>
                                            </div>
                                            <div class="offers-item-text">
                                                <p>Цикл, в том числе, выстраивает пласт. Как мы уже знаем, форшлаг
                                                    регрессийно
                                                    образует звукорядный хорус.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="offers-item">
                                            <div class="offers-item-title">
                                                <img src="images/Group%204.png" alt="">
                                                <h6>Доставка до дверей</h6>
                                            </div>
                                            <div class="offers-item-text">
                                                <p>Цикл, в том числе, выстраивает пласт. Как мы уже знаем, форшлаг
                                                    регрессийно
                                                    образует звукорядный хорус.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="offers-item">
                                            <div class="offers-item-title">
                                                <img src="images/Group%204.png" alt="">
                                                <h6>Доставка до дверей</h6>
                                            </div>
                                            <div class="offers-item-text">
                                                <p>Цикл, в том числе, выстраивает пласт. Как мы уже знаем, форшлаг
                                                    регрессийно
                                                    образует звукорядный хорус.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="offers-item">
                                            <div class="offers-item-title">
                                                <img src="images/Group%204.png" alt="">
                                                <h6>Доставка до дверей</h6>
                                            </div>
                                            <div class="offers-item-text">
                                                <p>Цикл, в том числе, выстраивает пласт. Как мы уже знаем, форшлаг
                                                    регрессийно
                                                    образует звукорядный хорус.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="offers-item">
                                            <div class="offers-item-title">
                                                <img src="images/Group%204.png" alt="">
                                                <h6>Доставка до дверей</h6>
                                            </div>
                                            <div class="offers-item-text">
                                                <p>Цикл, в том числе, выстраивает пласт. Как мы уже знаем, форшлаг
                                                    регрессийно
                                                    образует звукорядный хорус.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="news">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="news-title">
                            <h5>Новости</h5>
                        </div>
                        <div class="news-item">
                            <div class="news-item-img">
                                <img src="images/6.png" alt="">
                            </div>
                            <div class="news-item-text">
                                <div class="news-item-title">
                                    <h3>Мастер-класс Данила Прокопьева в Краснодаре</h3>
                                </div>
                                <div class="news-item-desc">
                                    <p>Мастер-класс состоится 18 октября 2014 года в 15.30 в музыкальном центре
                                        SOROKA.</p>
                                    <small>18 июня 2014 г.</small>
                                </div>
                            </div>
                        </div>
                        <div class="news-item">
                            <div class="news-item-img">
                                <img src="images/6.png" alt="">
                            </div>
                            <div class="news-item-text">
                                <div class="news-item-title">
                                    <h3>Мастер-класс Данила Прокопьева в Краснодаре</h3>
                                </div>
                                <div class="news-item-desc">
                                    <p>Мастер-класс состоится 18 октября 2014 года в 15.30 в музыкальном центре
                                        SOROKA.</p>
                                    <small>18 июня 2014 г.</small>
                                </div>
                            </div>
                        </div>
                        <div class="align-items-center">
                            <div class="d-flex justify-content-start">
                                <h4 class="text-center mr-5">Все Новости</h4>
                                <h4 class="text-center">Подписаться</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="news-title">
                            <h5>Обзоры</h5>
                        </div>
                        <div class="news-item">
                            <div class="news-item-img">
                                <img src="images/6.png" alt="">
                            </div>
                            <div class="news-item-text">
                                <div class="news-item-title">
                                    <h3>Мастер-класс Данила Прокопьева в Краснодаре</h3>
                                </div>
                                <div class="news-item-desc">
                                    <p>Мастер-класс состоится 18 октября 2014 года в 15.30 в музыкальном центре
                                        SOROKA.</p>
                                    <small>18 июня 2014 г.</small>
                                </div>
                            </div>
                        </div>
                        <div class="news-item">
                            <div class="news-item-img">
                                <img src="images/6.png" alt="">
                            </div>
                            <div class="news-item-text">
                                <div class="news-item-title">
                                    <h3>Мастер-класс Данила Прокопьева в Краснодаре</h3>
                                </div>
                                <div class="news-item-desc">
                                    <p>Мастер-класс состоится 18 октября 2014 года в 15.30 в музыкальном центре
                                        SOROKA.</p>
                                    <small>18 июня 2014 г.</small>
                                </div>
                            </div>
                        </div>
                        <div class="align-items-center">
                            <div class="d-flex justify-content-start">
                                <h4 class="text-center mr-5">Все Обзоры</h4>
                                <h4 class="text-center">Подписаться</h4>
                            </div>
                        </div>
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
                newProducts:{}
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
            }
        },
        mixins:[filtersMixin],
        mounted: function () {
            this.getNewProducts();
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
