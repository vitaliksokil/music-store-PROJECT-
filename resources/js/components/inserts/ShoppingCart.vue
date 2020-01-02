<template>
    <div class="cart">
        <!-- Button trigger modal -->
        <button @click.prevent="getShoppingCart" class="btn" data-toggle="modal"
                data-target="#shoppingCart"><i class="fas fa-shopping-basket"></i> Корзина <span
            v-text="shoppingCartCount"></span></button>

        <!-- Modal -->
        <div class="modal fade" id="shoppingCart" tabindex="-1" role="dialog"
             aria-labelledby="shoppingCartTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shoppingCartTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="shoppingCart.length">
                        <table class="shoppingCart mb-3">
                            <tr class="shoppingCartItem" v-for="shoppingCartItem in shoppingCart">
                                <td class="img"><img :src="`/images/products/${shoppingCartItem.photo}`" alt=""></td>
                                <td class="title"><h2>{{shoppingCartItem.title}}</h2></td>
                                <td class="price">Price:{{shoppingCartItem.price}}</td>
                                <td @click.prevent="deleteFromShoppingCart(shoppingCartItem.id)">
                                    <div class="delete"><i class="fas fa-trash red"></i></div>
                                </td>
                            </tr>
                        </table>
                        <div class="modal-footer justify-content-between">
                            <h2>Total:<span class="green">{{totalPrice()}}</span></h2> <button class="btn btn-success">BUY</button>
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
        data() {
            return {
                shoppingCartCount: 0,
                shoppingCart: [],
            }
        },
        methods: {
            getShoppingCartCount() {
                this.axios.get('/api/shopping-cart/count').then(response => {
                    this.shoppingCartCount = response.data;
                });
            },
            totalPrice(){
              let total = 0;
              for (let item of this.shoppingCart){
                  total += item.price;
              }
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
            }
        },
        mounted() {
            let sc = this;
            this.getShoppingCartCount();
            Event.$on('addedToShoppingCart', function () {
                sc.shoppingCartCount++;
            });
        }
    }
</script>

<style scoped>

</style>
