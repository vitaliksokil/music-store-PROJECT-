<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">User id</th>
                        <th scope="col">Orders count</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(val,key) in usersOrders">
                        <th>{{key}}</th>
                        <td>{{val.length}}</td>
                        <td>
                            <router-link :to="{name:'profile-orders-by-user-id',params:{'userid':key}}">
                                <button class="btn bg-orange" :title="'See all user orders '">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Orders",
        data(){
            return{
                usersOrders:[],
            }
        },
        methods:{
            getUsersOrders(){
                this.axios.get('/api/order/unverified-orders').then(response=>{
                    this.usersOrders = response.data;
                });
            }
        },
        mounted() {
            this.getUsersOrders();
        }
    }
</script>

<style scoped>

</style>
