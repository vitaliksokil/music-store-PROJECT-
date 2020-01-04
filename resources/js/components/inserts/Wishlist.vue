<template>
    <div class="cart" v-if="isauth">
        <!-- Button trigger modal -->
        <button @click.prevent="getWishlist" class="btn w-100" data-toggle="modal"
                data-target="#wishlist">
            <i class="fas fa-heart"></i>
            Wishlist
            <span v-text="wishlistCount"></span>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="wishlist" tabindex="-1" role="dialog"
             aria-labelledby="wishlistTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wishlistTitle">Wishlist</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="wishlist.length">
                        <table class="shoppingCart mb-3">
                            <tr class="shoppingCartItem" v-for="wishlistItem in wishlist">
                                <td class="img"><img :src="`/images/products/${wishlistItem.photo}`" alt=""></td>
                                <td class="title"><h2>{{wishlistItem.title}}</h2></td>
                                <td class="price">Price:{{wishlistItem.price}}$</td>
                                <td @click.prevent="deleteFromWishlist(wishlistItem.id)">
                                    <div class="delete"><i class="fas fa-trash red"></i></div>
                                </td>
                                <td @click.prevent="addToShoppingCart(wishlistItem.id)" title="Add to shopping cart">
                                    <div class="delete"><i class="fas fa-plus green"></i></div>
                                </td>
                                <td @click.prevent="addToWishlist"></td>
                            </tr>
                        </table>
                        <div class="modal-footer justify-content-between">
                            <h2>Total:<span class="green">{{totalPrice()}}$</span></h2>
                            <div class="buttons">
                                <button class="btn btn-danger" @click.prevent="removeAll">Remove all</button>
                                <button class="btn btn-success" @click.prevent="addAllToShoppingCart">Add all to shopping cart</button>
                            </div>

                        </div>
                    </div>
                    <div v-else><h2>Your wishlist is empty</h2></div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Wishlist",
        props: ['isauth'],


        data() {
            return {
                wishlistCount: 0,
                wishlist: [],
            }
        },
        methods: {
            getWishlistCount() {
                this.axios.get('/api/wishlist/count').then(response => {
                    this.wishlistCount = response.data;
                });
            },
            totalPrice() {
                let total = 0;
                for (let item of this.wishlist) {
                    total += item.price;
                }
                return total;
            },
            getWishlist() {
                this.axios.get('/api/wishlist').then(response => {
                    this.wishlist = response.data;
                });
            },
            deleteFromWishlist(product_id) {
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
                        this.axios.delete('/api/wishlist/' + product_id).then(response => {
                            Swal.fire('', 'Successfully deleted!!!', 'success');
                            this.wishlistCount--;
                            this.getWishlist();
                            Event.$emit('deletedFromWishlist');
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
                        this.axios.post('/api/wishlist/delete-all').then(response => {
                            Swal.fire(
                                '',
                                response.data.message,
                                response.data.status
                            );
                            this.wishlist = {};
                            this.wishlistCount = 0;
                            Event.$emit('isAlreadyInWishlistFalse');
                            $('#wishlist').modal('hide');
                        });
                    }
                })
            },
            addAllToShoppingCart(){
                this.axios.post('/api/wishlist/add-all-to-shopping-cart').then(response=>{
                    Swal.fire('',response.data.message,response.data.status);
                    Event.$emit('addToShopCart'); // checking if this prod is alredy in shop cart / product item comp
                    Event.$emit('deletedFromWishlist');
                    this.wishlist = {};
                    this.wishlistCount = 0;
                }).catch(error=>{
                    Swal.fire('',error.response.data.message,'error')
                });
            },
            addToShoppingCart(id){
                this.axios.post('/api/wishlist/add-to-shopping-cart',{product_id:id}).then(response=>{
                    this.getWishlist();
                    this.wishlistCount--;
                    Event.$emit('addToShopCart'); // checking if this prod is already in shop cart / product item comp
                    Event.$emit('deletedFromWishlist');

                    Swal.fire('',response.data.message,response.data.status)
                }).catch(error=>{
                    Swal.fire('',error.response.data.message,'error')
                });
            },
            init() {
                if (this.isauth) {

                    let sc = this;
                    this.getWishlistCount();
                    Event.$on('addedToWishlist', function () {
                        sc.wishlistCount++;
                    });
                }
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
