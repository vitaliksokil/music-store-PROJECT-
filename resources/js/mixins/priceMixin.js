export const priceMixin ={
    methods:{
        totalPrice() {
            let total = 0;
            for (let item of this.shoppingCart) {
                total += item.product.price * item.quantity;
            }
            this.total = total;
            return total;
        },
    }
};
