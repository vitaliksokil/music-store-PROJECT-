<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Photo</th>
                        <th scope="col">Product title</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Payment method</th>
                        <th scope="col">Is paid</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="order in orders">
                        <td><img :src="`/images/products/${order.product.photo}`" alt=""></td>
                        <th>{{order.product.title}}</th>
                        <td>{{order.quantity}}</td>
                        <td>{{order.total_price}}</td>
                        <td :class="{'red':!order.is_verified, 'green':order.is_verified}">{{order.is_verified |
                            isVerified}}
                        </td>
                        <td>{{order.payment_method}}</td>
                        <td :class="{'red':!order.is_paid, 'green':order.is_paid}">{{order.is_paid | isPaid}}</td>
                        <td>
                            <router-link :to="{name:'product-item',params:{id:order.product_id}}">
                                <button class="btn btn-info" :title="'View'">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </router-link>
                            <button class="btn bg-orange" :title="'See all information about the order'"
                                    data-toggle="modal" data-target="#moreInfo"
                                    @click.prevent="findOrderByID(order.id)">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>

                            <form v-if="!order.is_paid && order.payment_method == 'webmoney'"  method="POST" action="https://merchant.webmoney.ru/lmi/payment_utf.asp" accept-charset="utf-8" id="webmoney">
                                <input type="hidden" name="LMI_PAYMENT_AMOUNT" :value="order.total_price">
                                <input type="hidden" name="LMI_PAYMENT_DESC" value="Music store payment">
                                <input type="hidden" name="LMI_PAYEE_PURSE" value="Z942256158258">
                                <input type="hidden" name="LMI_SIM_MODE" value="0">
                                <input type="hidden" name="orders_ids" :value="order.id">
                                <button class="btn bg-green" :title="'Pay with webmoney'"
                                        @click="piecePayment(order.id,order.total_price)">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="moreInfo" tabindex="-1" role="dialog" aria-labelledby="moreInfoTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moreInfoTitle">Detail info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Product title</td>
                                <td>{{orderDetails.product.title}}</td>
                            </tr>
                            <tr>
                                <td>Product quantity</td>
                                <td>{{orderDetails.quantity}}</td>
                            </tr>
                            <tr>
                                <td>Product price</td>
                                <td>{{orderDetails.product.price}}</td>
                            </tr>
                            <tr>
                                <td>Product total price (quantity * price)</td>
                                <td>{{orderDetails.total_price}}</td>
                            </tr>
                            <tr>
                                <td>Area</td>
                                <td>{{orderDetails.area}}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>{{orderDetails.city}}</td>
                            </tr>
                            <tr>
                                <td>Post office</td>
                                <td>{{orderDetails.post_office}}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{orderDetails.client_name}}</td>
                            </tr>
                            <tr>
                                <td>Surname</td>
                                <td>{{orderDetails.client_surname}}</td>
                            </tr>
                            <tr>
                                <td>Middlename</td>
                                <td>{{orderDetails.client_middlename}}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{orderDetails.client_email}}</td>
                            </tr>
                            <tr>
                                <td>Phone number</td>
                                <td>{{orderDetails.client_phone_number}}</td>
                            </tr>
                            <tr>
                                <td>Is verified</td>
                                <td :class="{'red':!orderDetails.is_verified, 'green':orderDetails.is_verified}">{{orderDetails.is_verified | isVerified}}</td>
                            </tr>
                            <tr>
                                <td>Payment method</td>
                                <td>{{orderDetails.payment_method}}</td>
                            </tr>
                            <tr>
                                <td>Is paid</td>
                                <td :class="{'red':!orderDetails.is_paid, 'green':orderDetails.is_paid}">{{orderDetails.is_paid | isPaid}}</td>
                            </tr>
                            <tr>
                                <td>Created at</td>
                                <td>{{orderDetails.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {filtersMixin} from "../../mixins/filtersMixin";

    export default {
        name: "MyOrders",
        mixins:[filtersMixin],
        data() {
            return {
                orders: [],
                orderDetails: {
                    product: {
                        title: '',
                        price: ''
                    },
                    quantity: '',
                    total_price:'',
                    area:'',
                    city:'',
                    post_office:'',
                    client_name:'',
                    client_surname:'',
                    client_middlename:'',
                    client_email:'',
                    client_phone_number:'',
                    is_verified:'',
                    payment_method:'',
                    is_paid:'',
                    created_at:'',
                }
            }
        },
        methods: {
            getOrders() {
                this.axios.get('/api/order').then(response => {
                    this.orders = response.data;
                });
            },
            findOrderByID(id) {
                for (let item of this.orders) {
                    if (item.id == id) {
                        this.orderDetails = item;
                        return
                    }
                }
            },
            piecePayment(order_id,total_price){
                // doing fake(piece) payment, because free hosting doesn't allow to make payments
                let payer_purse = 'Z'; // random purse
                let payer_wm = ''; // and wm id
                for(let i =0;i<12;i++) {
                    payer_purse += Math.floor(Math.random() * 10);
                    payer_wm += Math.floor(Math.random() * 10);
                }
                this.axios.post('/api/payment/piece-payment',{
                    'orders_ids':[order_id], // should be array
                    'amount':total_price,
                    'payer_purse':payer_purse,
                    'payer_wm':payer_wm,
                });
                //send wm form
                let wmForm = $('#webmoney');
                wmForm.submit();
            }
        },
        mounted() {
            this.getOrders();
        },
    }
</script>

<style scoped>

</style>
