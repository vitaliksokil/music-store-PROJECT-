<template>
   <div class="container p-5">
       <form v-if="messageType==='success'" @submit.prevent="updatePassword()">
           <div class="form-group">
               <label for="newPassword">New password</label>
               <input type="password" class="form-control" id="newPassword" aria-describedby="emailHelp" placeholder="New password"
               v-model="form.password" :class="{ 'is-invalid' : form.errors.has('password')}">
               <has-error :form="form" field="password"></has-error>

           </div>
           <div class="form-group">
               <label for="confirmNewPassword">Confirm password</label>
               <input type="password" class="form-control" id="confirmNewPassword" placeholder="Confirm password"
                      v-model="form.confirmNewPassword">
           </div>
           <button type="submit" class="btn btn-primary">Submit</button>
       </form>
       <div v-else-if="messageType==='error'" class="alert alert-error" role="alert">
           <h4 class="alert-heading">{{messageType}}</h4>
           <hr>
           <p>{{message}}</p>
       </div>
       <div v-else-if="messageType==='updated'" class="alert alert-success" role="alert">
           <h4 class="alert-heading">{{messageType}}</h4>
           <hr>
           <p>{{message}}</p>
       </div>
   </div>

</template>

<script>
    export default {
        name: "Reset",
        data() {
            return {
                messageType: '',
                message: '',
                token: this.$route.params.token,
                email: this.$route.params.email,
                form:new Form({
                    password:'',
                    confirmNewPassword:'',
                    email:this.$route.params.email,
                    token:this.$route.params.token,
                })
            }
        },
        methods:{
            updatePassword(){
                if(this.form.password === this.form.confirmNewPassword){
                    this.form.post('/api/reset-password').then((response)=>{
                        this.messageType = response.data.messageType;
                        this.message = response.data.message;
                    }).catch(({response})=>{});
                }else{
                    this.form.errors.set('password','Passwords are not equals');
                }
            }
        },
        mounted() {
            this.axios.get('/api/verify-reset-token/'+this.token+'-'+this.email).then((response)=>{
                this.messageType = response.data.messageType;
                this.message = response.data.message;
            })
        }
    }
</script>

<style scoped>

</style>
