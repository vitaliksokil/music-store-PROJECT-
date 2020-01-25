export const wishlistMixin={
    data(){
        return{
            isAlreadyInWishList:false,
        }
    },
    methods:{
        isAlreadyInWishlist() {
            this.axios.get('/api/wishlist/is-exists/' + this.currentProductID).then((response) => {
                this.isAlreadyInWishList = response.data.result;
            })
        },
        addToWishlist(id) {
            let productID = this.currentProductID ? this.currentProductID : id;
            this.axios.post('/api/wishlist', {'product_id': productID}).then(response => {
                Event.$emit('addedToWishlist');
                this.isAlreadyInWishList = true;
                Swal.fire(
                    '',
                    'This product was successfully added to your wishlist',
                    'success'
                )
            }).catch(error=>{
                Swal.fire('',
                    error.response.data.message,
                    'error'
                )
            })
        }
    }
};
