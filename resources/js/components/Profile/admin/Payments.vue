<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <b-table striped hover :items="payments"  @row-clicked="showOrder"></b-table>
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
    import {filtersMixin} from "../../../mixins/filtersMixin";

    export default {
        name: "Payments",
        mixins:[filtersMixin],
        data() {
            return {
                payments: [],
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
            getPayments() {
                this.axios.get('/api/payment').then(response => this.payments = response.data).catch(error => console.log(error));
            },
            showOrder(payment) {
                let id = payment.order_id;
                this.axios.get('/api/order/by-id/' + id).then(response => {
                    this.orderDetails = response.data;
                    $('#moreInfo').modal('show');
                }).catch(error => console.log(error));
            }
        },
        mounted() {
            this.getPayments();
        }
    }
</script>

<style scoped>

</style>
