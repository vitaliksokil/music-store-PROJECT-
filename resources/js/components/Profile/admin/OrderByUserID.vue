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
                        <th scope="col">Is payed</th>
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
                        <td >{{order.payment_method}}</td>
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
                            <button class="btn bg-green" :title="'See all information about the order'"
                                    data-toggle="modal"
                                    @click.prevent="verify(order.id)">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-success" @click.prevent="verifyAll">
                    Verify all
                </button>
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
    export default {
        name: "OrderByUserID",
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
                    created_at:'',
                }
            }
        },
        methods: {
            getOrders() {
                this.axios.get('/api/order/byUserID/'+this.$route.params.userid).then(response => {
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
            verifyAll(){
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, verify all!'
                }).then((result) => {
                    if (result.value) {
                        this.axios.put('/api/order/verifyAllByUserID/' + this.$route.params.userid).then(response => {
                            Swal.fire('',response.data.message,response.data.status);
                            this.getOrders();

                        }).catch(error => {
                            console.log(error)
                        })
                    }
                })
            },
            verify(id){
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, verify!'
                }).then((result) => {
                    if (result.value) {
                        this.axios.put('/api/order/verify/'+id).then(response => {
                            Swal.fire('',response.data.message,response.data.status);
                            this.getOrders();
                        }).catch(error => {
                            console.log(error)
                        })
                    }
                })
            }
        },
        mounted() {
            this.getOrders();
        },
        filters: {
            isVerified: function (val) {
                if (val) {
                    return 'VERIFIED';
                } else {
                    return "UNVERIFIED";
                }
            },
            isPaid: function (val) {
                if (val) {
                    return 'PAID';
                } else {
                    return "UNPAID";
                }
            }
        }
    }
</script>

<style scoped>

</style>
