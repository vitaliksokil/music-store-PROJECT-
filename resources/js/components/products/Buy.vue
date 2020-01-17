<template>
    <div class="container mt-5" v-if="shoppingCart.length">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <table class="shoppingCart mb-3">
                    <tr class="shoppingCartItem" v-for="shoppingCartItem in shoppingCart">
                        <td class="img"><img :src="`/images/products/${shoppingCartItem.product.photo}`" alt=""></td>
                        <td class="title"><h2>{{shoppingCartItem.product.title}}</h2></td>
                        <td>
                            <vue-number-input v-model="shoppingCartItem.quantity" size="small" inline center
                                              readonly></vue-number-input>
                        </td>
                        <td class="price">Price:{{shoppingCartItem.product.price * shoppingCartItem.quantity}}$</td>

                    </tr>
                </table>
                <div class="modal-footer justify-content-between">
                    <h2>Total:<span class="green">{{total}}$</span></h2>
                </div>
                <form @submit.prevent="buy">
                    <div class="region">
                        <label for="uaRegion">Region*: </label>
                        <select required class="form-control" v-model="form.area" id="uaRegion">
                            <option v-for="ukrainianArea in ukrainianAreas" :value="ukrainianArea">
                                {{ukrainianArea.Description}}
                            </option>
                        </select>
                    </div>
                    <div class="city" v-if="form.area">
                        <label for="city">City*: </label>
                        <input required type="text" v-model="form.city" class="form-control" list="city">
                        <datalist id="city">
                            <option v-for="city in cities">{{city.Description}}</option>
                        </datalist>
                    </div>
                    <div class="warehouse" v-if="isCity">
                        <label for="warehouse">Warehouse*: </label>
                        <select required class="form-control" id="warehouse" v-model="form.warehouse">
                            <option v-for="warehouse in warehouses">{{warehouse.Description}}</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="form.warehouse">
                        <div class="form-group">
                            <label for="client_name">Name of recipient*:</label>
                            <input required type="text" class="form-control" id="client_name"
                                   placeholder="Enter name" v-model="form.client.name">
                        </div>
                        <div class="form-group">
                            <label for="client_surname">Surname of recipient*:</label>
                            <input required type="text" class="form-control" id="client_surname"
                                   placeholder="Enter surname" v-model="form.client.surname">
                        </div>
                        <div class="form-group">
                            <label for="client_middlename">Middlename of recipient*:</label>
                            <input required type="text" class="form-control" id="client_middlename"
                                   placeholder="Enter middlename" v-model="form.client.middlename">
                        </div>
                        <div class="form-group">
                            <label for="client_phoneNumber">Phone number of recipient*:</label>
                            <input required type="text" class="form-control" id="client_phoneNumber"
                                   placeholder="Enter phone number" v-model="form.client.phoneNumber" >
                            <div class="red" v-if="errors.phoneNumber">{{errors.phoneNumber}}</div>

                        </div>
                        <div class="form-group">
                            <label for="client_email">Your email*:</label>
                            <input required type="email" class="form-control" id="client_email"
                                   placeholder="Enter email" v-model="form.client.email">
                        </div>

                        <div class="form-group">
                            <label for="paymentMethod">Payment method*:</label>
                            <select required class="form-control" v-model="form.paymentMethod" id="paymentMethod">
                                <option value="1">On receipt</option>
                                <option value="2">Google pay</option>
                            </select>
                        </div>


                        <div class="buttons text-right mt-5">
                            <button class="btn btn-danger" @click.prevent="cancel">Cancel</button>
                            <button class="btn btn-success" type="submit">BUY</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {priceMixin} from "../../mixins/priceMixin";

    export default {
        name: "Buy",
        mixins: [priceMixin],
        data() {
            return {
                errors:{
                    phoneNumber:''
                },
                form: {
                    area: '',
                    city: '',
                    warehouse: '',
                    client: {
                        name: '',
                        surname: '',
                        middlename: '',
                        email: '',
                        phoneNumber: '',
                    },
                    paymentMethod:''
                },
                shoppingCart: this.$route.params.shoppingCart,
                total: 0,
                ukrainianAreas: [],
                cities: [],
                warehouses: [],
                isCity: false,


            }
        },
        methods: {
            cancel() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        this.$router.push('/');
                    }
                })
            },
            buy() {
                if(!this.form.client.phoneNumber.match(/\+380[0-9]{9}/)){
                    this.errors.phoneNumber = 'Incorrect phone number format! Should be "+380xxxxxxxxx"';
                    Swal.fire('','Some data is invalid!!!','error');
                    return
                }
                Swal.fire({
                    text: "Are you sure in data you have just entered???",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        let order = [];
                        let orderItem = {};
                        for(let item of this.shoppingCart){
                            orderItem.product_id = item.product_id;
                            orderItem.quantity = item.quantity;
                            orderItem.total_price = item.quantity * item.product.price;

                            orderItem.area = this.form.area.Description;
                            orderItem.city = this.form.city;
                            orderItem.post_office = this.form.warehouse;
                            orderItem.client_name = this.form.client.name;
                            orderItem.client_surname = this.form.client.surname;
                            orderItem.client_middlename = this.form.client.middlename;
                            orderItem.client_email = this.form.client.email;
                            orderItem.client_phone_number = this.form.client.phoneNumber;

                            order.push(orderItem);
                            orderItem = {};
                        }
                        this.axios.post('/api/order',order).then(response => {
                            Swal.fire('',response.data.message,response.data.status);
                            this.$router.push('/');
                            Event.$emit('dropShoppingCartCount');
                        }).catch(error=>{
                            Swal.fire('','An error is occured!!!','error');
                        });
                    }
                })
            },
            loadCities() {
                this.isCity = false;
                this.cities = [];
                this.warehouses = [];
                this.form.warehouse = '';
                $.ajax({
                    url: "https://api.novaposhta.ua/v2.0/json/",
                    dataType: 'json',
                    data: JSON.stringify({
                        "apiKey": "3957d93ece7a43ed3d179dd4630d9cff",
                        "modelName": "Address",
                        "calledMethod": "getCities",
                        "methodProperties": {
                            "FindByString": this.form.city
                        },
                    }),
                    method: 'POST',
                    success: response => {
                        for (let city of response.data) {
                            if (city.Area == this.form.area.Ref) {
                                this.cities.push(city);
                            }
                        }
                        if (this.cities.length == 1 && this.cities[0].Description == this.form.city) {
                            this.isCity = true;
                            this.getWarehouses();
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            },
            getAreas() {
                $.ajax({
                    url: "https://api.novaposhta.ua/v2.0/json/",
                    dataType: 'json',
                    data: JSON.stringify({
                        "apiKey": "3957d93ece7a43ed3d179dd4630d9cff",
                        "modelName": "Address",
                        "calledMethod": "getAreas",
                        "methodProperties": {},
                    }),
                    method: 'POST',
                    success: response => {
                        this.ukrainianAreas = response.data;
                    },
                    error: function (error) {
                        console.log(error)
                        ;
                    }
                });
            },
            getWarehouses() {
                $.ajax({
                    url: "https://api.novaposhta.ua/v2.0/json/",
                    dataType: 'json',
                    data: JSON.stringify({
                        "apiKey": "3957d93ece7a43ed3d179dd4630d9cff",
                        "modelName": "AddressGeneral",
                        "calledMethod": "getWarehouses",
                        "methodProperties": {
                            "CityName": this.form.city,
                            "Page": 1
                        },
                    }),
                    method: 'POST',
                    success: response => {
                        this.warehouses = response.data;
                    },
                    error: function (error) {
                        console.log(error)
                        ;
                    }
                });
            }
        },
        watch: {
            'form.city': function () {
                this.loadCities();
            },
            'form.area': function () {
                this.cities = [];
                this.form.city = '';
            }
        },
        mounted() {
            this.$Progress.start();
            this.total = this.totalPrice();
            this.getAreas();
            this.$Progress.finish();
        },
        created() {
            this.loadCities = debounce(this.loadCities, 1000);
        }
    }
</script>

<style scoped>

</style>
