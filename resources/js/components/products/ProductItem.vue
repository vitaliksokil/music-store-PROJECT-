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
                            <h5><span class="text-primary">YOUR FEEDBACK</span>, {{userFeedback.user.name}}</h5>
                            <div class="d-flex flex-column">
                                <small><span class="text-primary">Created at:</span>{{userFeedback.created_at}}</small>
                                <small><span class="text-primary">Updated at:</span>{{userFeedback.updated_at}}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="feedback-text" v-html="userFeedback.feedback"></div>
                        <div class="likes d-flex justify-content-end">
                            <i class="fas fa-thumbs-up"></i>
                            <i class="fas fa-thumbs-down"></i>
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
                            <h5>{{feedback.user.name}}</h5>
                            <div class="d-flex flex-column">
                                <small><span class="text-primary">Created at:</span>{{feedback.created_at}}</small>
                                <small><span class="text-primary">Updated at:</span>{{feedback.updated_at}}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="feedback-text" v-html="feedback.feedback"></div>
                        <div class="likes d-flex justify-content-end">
                            <i class="fas fa-thumbs-up"></i>
                            <i class="fas fa-thumbs-down"></i>
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
                        <h5 class="modal-title" id="editFeedbackTitle">Add new feedback</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="editFeedback">
                            <div class="form-group">
                                <label for="feedback">Edit your feedback</label>
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

    export default {
        name: "ProductItem",
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
                    feedbacks: []
                },
                isUserLF: false,
                userFeedback: {},
                isAuth: false
            }
        },
        methods: {
            getCurrentProduct() {
                this.axios.get('/api/product-get-current/' + this.currentProductID).then((response) => {
                    this.productItem = response.data;
                    this.isUserLeftFeedback();
                }).catch();
            },
            addFeedback() {
                this.axios.post('/api/add-feedback',
                    {
                        'feedback': this.feedback,
                        'product_id': this.productItem.id,
                        'user_id': this.user_id
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
                    this.userFeedback = response.data.userFeedback;
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
            deleteFeedback(){
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
                        pi.axios.delete('/api/feedback/'+this.userFeedback.id).then((response)=>{
                            Swal.fire(
                                'Deleted!',
                                'Your feedback has been deleted.',
                                'success'
                            );
                            this.getCurrentProduct();
                        }).catch(error=>{
                            Swal.fire(
                                'Error!',
                                'Something went wrong!!!',
                                'error'
                            );
                        })
                    }
                })
            }
        },
        mounted() {
            this.getCurrentProduct();
        },
        created() {
            if (localStorage.getItem('user')) {
                this.user_id = JSON.parse(localStorage.getItem('user')).id;
            }
            this.isAuth = auth.check();
        }

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
