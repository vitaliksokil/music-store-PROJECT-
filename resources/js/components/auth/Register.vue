<template>
    <div class="container">
        <form @submit.prevent="register">
            <div class="form-group">

                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name"
                       v-model="form.name" :class="{ 'is-invalid' : form.errors.has('name')  }">
                <has-error :form="form" field="name"></has-error>

            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" placeholder="Email"
                       v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }">
                <has-error :form="form" field="email"></has-error>

            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password"
                       v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }">
                <has-error :form="form" field="password"></has-error>

            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm password"
                       v-model="form.password_confirmation"
                       :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                <has-error :form="form" field="password_confirmation"></has-error>

            </div>
            <button :disabled="form.busy" type="submit" class="btn btn-primary">Sign up</button>

        </form>
    </div>

</template>

<script>
    export default {
        name: "Register",
        data() {
            return {
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }),

            }
        },
        methods: {
            register() {
                this.form.post('api/register')
                    .then(({data}) => {
                        auth.login(data.data.access_token, data.user);
                        this.$router.push('/');
                    }).catch(({data}) => {

                })
            }
        }
    }
</script>

<style scoped>

</style>
