<template>
    <div class="container" :key="currentProductID">
        <div class="row mt-5 mb-5">
            <div class="col-lg-6 d-flex justify-content-center">
                <div class="img"><img :src="`/images/products/${productItem.photo}`" alt=""></div>
            </div>
            <div class="col-lg-6">
                <div class="product-info">
                    <rate :length="5" :value="productItem.productRate" :showcount="true" :readonly="true"/>
                    <div class="title d-flex justify-content-between mt-3 flex-wrap">
                        <h2>{{productItem.title}}</h2>
                        <button v-if="isAuth" @click.prevent="addToWishlist" class="btn btn-primary"
                                :disabled="isAlreadyInWishList">
                            Add to wishlist
                            <i class="fas fa-heart"></i>
                        </button>
                        <button v-if="isAuth" @click.prevent="addToShoppingCart" class="btn btn-success"
                                :disabled="isAlreadyInShopCart">Add to shopping cart <i
                            class="fas fa-shopping-basket"></i></button>
                    </div>
                    <div class="price">Price:<span>{{productItem.price}}$</span></div>
                    <hr>
                    <h2>Description</h2>
                    <div class="description" v-html="productItem.description"></div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="feedbacks-title">
                    <h1>Recommended for you</h1>
                </div>
                <products-slider :products="recommendedProducts"></products-slider>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="feedbacks-title">
                    <h1>Feedbacks</h1>
                </div>
                <div class="add-feedback" v-if="!isUserLF && isAuth">
                    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addFeedback">Add feedback
                    </button>
                </div>
                <div class="user-feedback" v-else-if="isUserLF && typeof userFeedback != 'undefined'">
                    <div class="feedback border-primary">
                        <div class="feedback-username d-flex justify-content-between align-items-center">
                            <h5>
                                <span class="text-primary">YOUR FEEDBACK</span>, {{userFeedback.user.name}}
                                <rate :length="5" :value="userFeedback.rate" :readonly="true"/>
                            </h5>
                            <div class="d-flex flex-column">
                                <small><span class="text-primary">Created at:</span>{{userFeedback.created_at}}</small>
                                <small><span class="text-primary">Updated at:</span>{{userFeedback.updated_at}}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="feedback-text" v-html="userFeedback.feedback"></div>
                        <div class="likes d-flex justify-content-end">
                            <div><i @click.prevent="like(userFeedback,1)" class="fas fa-thumbs-up cursor-pointer"
                                    :class="{'cyan':isLiked(userFeedback.likes,feedback)}"
                            >

                            </i><small>{{userFeedback.likes.length}}</small>
                            </div>
                            <div>
                                <i @click.prevent="like(userFeedback,0)" class="fas fa-thumbs-down cursor-pointer"
                                   :class="{'lightred':isLiked(userFeedback.dislikes)}"
                                ></i>
                                <small>{{userFeedback.dislikes.length}}</small>
                            </div>
                            <i class="fas fa-edit green cursor-pointer" data-toggle="modal"
                               data-target="#editFeedback"></i>
                            <i class="fas fa-trash-alt red cursor-pointer" @click.prevent="deleteFeedback"></i>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <router-link :to="{name:'login'}" class="text-primary">Login to left feedback</router-link>
                </div>
                <div class="feedbacks" v-if="Array.isArray(productItem.feedbacks) && productItem.feedbacks.length">
                    <div v-for="feedback in productItem.feedbacks" class="feedback">
                        <div class="feedback-username d-flex justify-content-between align-items-center">
                            <h5>
                                {{feedback.user.name}}
                                <rate :length="5" :value="feedback.rate" :readonly="true"/>
                            </h5>
                            <div class="d-flex flex-column">
                                <small><span class="text-primary">Created at:</span>{{feedback.created_at}}</small>
                                <small><span class="text-primary">Updated at:</span>{{feedback.updated_at}}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="feedback-text" v-html="feedback.feedback"></div>
                        <div class="likes d-flex justify-content-end">
                            <div><i @click.prevent="like(feedback,1)" class="fas fa-thumbs-up cursor-pointer"
                                    :class="{'cyan':isLiked(feedback.likes,feedback)}"
                            >

                            </i><small>{{feedback.likes.length}}</small>
                            </div>
                            <div>
                                <i @click.prevent="like(feedback,0)" class="fas fa-thumbs-down cursor-pointer"
                                   :class="{'lightred':isLiked(feedback.dislikes)}"
                                ></i>
                                <small>{{feedback.dislikes.length}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <h2 class="red text-center">
                        NO FEEDBACKS
                    </h2>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addFeedback" tabindex="-1" role="dialog" aria-labelledby="addFeedbackTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFeedbackTitle">Add new feedback</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addFeedback">
                            <div class="form-group">
                                <label for="feedback">Leave your feedback:</label>
                                Your rate:
                                <rate :length="5" :showcount="true" v-model="clientRate"/>
                                <vue-editor v-model="feedback" id="feedback"></vue-editor>
                                <div class="red" v-if="errors.feedback">{{errors.feedback[0]}}</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editFeedback" tabindex="-1" role="dialog" aria-labelledby="editFeedbackTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFeedbackTitle">Edit your feedback</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editFeedback">
                            <div class="form-group">
                                <label for="feedback">Edit your feedback</label>
                                Your rate:
                                <rate :length="5" :showcount="true" v-model="userFeedback.rate"/>
                                <vue-editor v-model="userFeedback.feedback" id="edit"></vue-editor>
                                <div class="red" v-if="errors.feedback">{{errors.feedback[0]}}</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Auth from "../../auth";
    import {filtersMixin} from "../../mixins/filtersMixin";
    import ProductsSlider from "../inserts/ProductsSlider";
    import {shoppingCartMixin} from "../../mixins/shoppingCartMixin";
    import {wishlistMixin} from "../../mixins/wishlistMixin";

    export default {
        name: "ProductItem",
        mixins: [filtersMixin,shoppingCartMixin,wishlistMixin],
        components: {
            'products-slider': ProductsSlider
        },
        watch: {
            $route: function () {
                this.currentProductID = this.$route.params.id;
                this.init();
            }
        },
        data() {
            return {
                currentProductID: this.$route.params.id,
                feedback: '',
                user_id: '',
                errors: {},
                productItem: {
                    title: '',
                    price: '',
                    description: '',
                    photo: '',
                    feedbacks: [],
                    productRate: 0,
                },
                isUserLF: false,
                userFeedback: {},
                isAuth: false,
                recommendedProducts: {},
                clientRate: 0,
            }
        },
        methods: {
            init() {
                let pi = this;
                this.getCurrentProduct();
                this.getRecommendedProducts();
                this.isAlreadyInShoppingCart();
                this.isAlreadyInWishlist();
                Event.$on('deletedFromShopCart', function () {
                    pi.isAlreadyInShoppingCart();
                });
                Event.$on('addToShopCart', function () {
                    pi.isAlreadyInShoppingCart();
                });
                Event.$on('deletedFromWishlist', function () {
                    pi.isAlreadyInWishlist();
                });
                Event.$on('isAlreadyInShopCartFalse',function () {
                    pi.isAlreadyInShopCart = false;
                });
                Event.$on('isAlreadyInWishlistFalse',function () {
                    pi.isAlreadyInWishList = false;
                })
            },

            getCurrentProduct() {
                this.axios.get('/api/product-get-current/' + this.currentProductID).then((response) => {
                    this.productItem = response.data;
                    this.isUserLeftFeedback();
                }).catch();
            },
            isLiked(likes) {
                for (let like of likes) {
                    if (like.user_id == this.user_id) {
                        return true;
                    }
                }
                return false;
            },
            like(feedback, like) {
                // if like = 1 it's like, if 0 - dislike
                this.axios.post('/api/like', {
                    'feedback_id': feedback.id,
                    'like': like
                }).then(response => {
                    this.axios.get('/api/feedback/get-likes/' + feedback.id).then(response => {
                        feedback.likes = response.data.likes;
                        feedback.dislikes = response.data.dislikes;
                    });
                }).catch(error => {
                    console.log(error)
                });
            },
            addFeedback() {
                this.axios.post('/api/add-feedback',
                    {
                        'feedback': this.feedback,
                        'product_id': this.productItem.id,
                        'rate': this.clientRate
                    }
                ).then((response) => {
                    this.errors.feedback = '';
                    $('#addFeedback').modal('hide');
                    this.feedback = '';
                    Swal.fire(
                        response.data.message,
                        '',
                        response.data.messageType
                    );
                    this.getCurrentProduct();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    if (error.response.status === 403 || error.response.status === 400) {
                        Swal.fire(
                            error.response.data.message,
                            '',
                            'error'
                        );
                        $('#addFeedback').modal('hide');
                    }
                })
            },
            isUserLeftFeedback() {
                let returnValue = false;
                this.axios.post('/api/is-user-left-feedback', {
                    'product_id': this.productItem.id,
                    'user_id': this.user_id
                }).then(response => {
                    let allFeedbacks = this.productItem.feedbacks;
                    for (let feedback of allFeedbacks) {
                        if (feedback.user_id == this.user_id) {
                            this.userFeedback = feedback;
                        }
                    }
                    this.isUserLF = true;
                }).catch(error => {
                    this.isUserLF = false;
                });

            },
            editFeedback() {
                this.axios.put('/api/feedback', this.userFeedback).then((response) => {
                    $('#editFeedback').modal('hide');
                    Swal.fire(
                        response.data.message,
                        '',
                        response.data.messageType
                    );
                    this.getCurrentProduct();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    if (error.response.status === 400) {
                        Swal.fire(
                            error.response.data.message,
                            '',
                            'error'
                        );
                        $('#editFeedback').modal('hide');
                    }
                });
            },
            deleteFeedback() {
                let pi = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        pi.axios.delete('/api/feedback/' + this.userFeedback.id).then((response) => {
                            Swal.fire(
                                'Deleted!',
                                'Your feedback has been deleted.',
                                'success'
                            );
                            this.getCurrentProduct();
                        }).catch(error => {
                            Swal.fire(
                                'Error!',
                                'Something went wrong!!!',
                                'error'
                            );
                        })
                    }
                })
            },
            getRecommendedProducts() {
                this.axios.get('/api/get-recommended-products/' + this.currentProductID).then(response => {
                    this.recommendedProducts = response.data;
                }).catch(error => {

                });
            },

        },
        mounted() {
            this.init();
        },
        created() {
            if (localStorage.getItem('user')) {
                this.user_id = JSON.parse(localStorage.getItem('user')).id;
            }
            this.isAuth = auth.check();
        },


    }
</script>

<style scoped>

    h2 {
        text-align: left;
    }

    .title {
        margin-bottom: 40px;
    }

    img {
        width: 100%;
    }

    .price {
        font-size: 30px;
        margin-bottom: 70px;
    }

    .price span {
        background: #d8d8d8;
        padding: 3px 13px;
        border-radius: 5px;
    }

    .description {
        padding: 30px 0;
    }

    .product-info {
        margin-left: 30px;
    }
</style>
