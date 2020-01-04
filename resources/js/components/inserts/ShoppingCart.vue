<template>
    <div class="cart" v-if="isauth">
        <!-- Button trigger modal -->
        <button @click.prevent="getShoppingCart" class="btn w-100" data-toggle="modal"
                data-target="#shoppingCart"><i class="fas fa-shopping-basket"></i> Корзина <span
            v-text="shoppingCartCount"></span></button>

        <!-- Modal -->
        <div class="modal fade" id="shoppingCart" tabindex="-1" role="dialog"
             aria-labelledby="shoppingCartTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shoppingCartTitle">Shopping cart</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="shoppingCart.length">
                        <table class="shoppingCart mb-3">
                            <tr class="shoppingCartItem" v-for="shoppingCartItem in shoppingCart">
                                <td class="img"><img :src="`/images/products/${shoppingCartItem.product.photo}`" alt=""></td>
                                <td class="title"><h2>{{shoppingCartItem.product.title}}</h2></td>
                                <td><vue-number-input :inputtable="false" v-model="shoppingCartItem.quantity" :min="1" :max="10" size="small" inline center controls @change="e =>{ updateShoppingCart(e,shoppingCartItem.product_id) }" ></vue-number-input></td>
                                <td class="price">Price:{{shoppingCartItem.product.price * shoppingCartItem.quantity}}$</td>
                                <td @click.prevent="deleteFromShoppingCart(shoppingCartItem.product.id)">
                                    <div class="delete"><i class="fas fa-trash red"></i></div>
                                </td>
                            </tr>
                        </table>
                        <div class="modal-footer justify-content-between">
                            <h2>Total:<span class="green">{{totalPrice()}}$</span></h2>
                            <div class="buttons">
                                <button class="btn btn-danger" @click.prevent="removeAll">Remove all</button>
                                <button class="btn btn-success">BUY</button>
                            </div>

                        </div>
                    </div>
                    <div v-else><h2>Your shopping cart is empty</h2></div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ShoppingCart",
        props: ['isauth'],
        data() {
            return {
                shoppingCartCount: 0,
                shoppingCart: [],
                total:0,
            }
        },
        methods: {
            getShoppingCartCount() {
                this.axios.get('/api/shopping-cart/count').then(response => {
                    this.shoppingCartCount = response.data;
                });
            },
            totalPrice() {
                let total = 0;
                for (let item of this.shoppingCart) {
                    total += item.product.price * item.quantity;
                }
                this.total = total;
                return total;
            },
            getShoppingCart() {
                this.axios.get('/api/shopping-cart').then(response => {
                    this.shoppingCart = response.data;
                });
            },
            deleteFromShoppingCart(product_id) {
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
                        this.axios.delete('/api/shopping-cart/' + product_id).then(response => {
                            Swal.fire('', 'Successfully deleted!!!', 'success');
                            this.shoppingCartCount--;
                            this.getShoppingCart();
                            Event.$emit('deletedFromShopCart');
                        });
                    }
                })
            },
            removeAll(){
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
                        this.axios.post('/api/shopping-cart/delete-all').then(response => {
                            Swal.fire(
                                '',
                                response.data.message,
                                response.data.status
                            );
                            this.shoppingCart = {};
                            this.shoppingCartCount = 0;
                            Event.$emit('isAlreadyInShopCartFalse');
                            $('#shoppingCart').modal('hide');
                        });
                    }
                })
            },
            init() {
                if (this.isauth) {

                    let sc = this;
                    this.getShoppingCartCount();
                    Event.$on('addToShopCart', function () {
                        sc.getShoppingCartCount();
                    });
                }
            },
            updateShoppingCart(e,id){
                this.$Progress.start();
                this.axios.put('/api/shopping-cart/'+e+'&'+id).then(response => {
                    this.$Progress.finish();
                }).catch(error=>{
                    this.$Progress.fail();
                })
            }
        },
        mounted() {
            this.init();
        },
        watch: {
            isauth: function () {
                this.init();
            }
        }
    }
</script>

<style scoped>

</style>
