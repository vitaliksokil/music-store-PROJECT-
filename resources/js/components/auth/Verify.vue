<template>
    <div class="container">
        <div class="pt-4 pb-4">
            <div v-if='token==="token"' class="alert alert-primary" role="alert">
                <h4 class="alert-heading">Verify your email!!!</h4>
                <p v-if="messageType==='resend'">{{message}}
                </p>
                <p v-else>We have sent a message to your email address, please go to your email and follow the instruction to
                    verify your email!!!
                </p>

                <hr>
                <p class="mb-0">If you have not received an email, press here to resend ->
                    <button @click.prevent="resend" class="btn btn-primary">RESEND</button>
                </p>
            </div>

            <div v-else-if="messageType==='success'" class="alert alert-success" role="alert">
                <h4 class="alert-heading">{{messageType}}</h4>
                <hr>
                <p>{{message}}</p>
            </div>

            <div v-else-if="messageType==='error'" class="alert alert-error" role="alert">
                <h4 class="alert-heading">{{messageType}}</h4>
                <hr>
                <p>{{message}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                messageType: '',
                message: '',
                token: this.$route.params.token,
            }
        },
        methods: {
            resend() {
                this.axios.post('/api/send-verification-email').then(({message}) => {
                    this.message = 'Email is resent';
                    this.messageType = 'resend';
                });
            }
        },
        name: "Verify",
        mounted() {
            if(this.token === 'token'){
                this.axios.post('/api/send-verification-email').then(({message})=>{

                });
            }else{
                this.axios.get('/api/verify-email/' + this.token).then((response) => {
                    this.messageType = response.data.messageType;
                    this.message = response.data.message;
                    if(response.data.messageType === 'success'){
                        Event.$emit('emailVerified');
                    }
                })
            }
        }

    }
</script>

<style scoped>

</style>
