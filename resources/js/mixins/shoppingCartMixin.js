export const shoppingCartMixin={
    data(){
        return{
            isAlreadyInShopCart:false,
        }
    },
  methods:{
      isAlreadyInShoppingCart() {
          this.axios.get('/api/shopping-cart/is-exists/' + this.currentProductID).then((response) => {
              this.isAlreadyInShopCart = response.data.result;
          })
      },
      addToShoppingCart(id) {
          let productID = this.currentProductID ? this.currentProductID : id;
          this.axios.post('/api/shopping-cart', {'product_id': productID}).then(response => {
              Event.$emit('addToShopCart');
              this.isAlreadyInShopCart = true;
              Swal.fire(
                  '',
                  'This product was successfully added to your shopping cart',
                  'success'
              )
          }).catch(error=>{
              Swal.fire('',
                  'Error. This product probably is already in your shopping cart!!!',
                  'error'
              )
          })
      }
  }
};
