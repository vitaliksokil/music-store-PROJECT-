<template>
    <div class="container">
        <form @submit.prevent="login">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                       placeholder="Enter email"
                       v-model="form.email" :class="{ 'is-invalid' : form.errors.has('email')  }">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                    else.</small>
                <has-error :form="form" field="email"></has-error>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password"
                       v-model="form.password" :class="{ 'is-invalid' : form.errors.has('password')  }">
                <has-error :form="form" field="password"></has-error>

            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button :disabled="form.busy" type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                form: new Form({
                    email: '',
                    password: '',
                }),
                token: null,
            }
        },
        methods: {
            login() {
                this.form.post('api/login')
                    .then(({data}) => {
                        if (data.status === 'error') {
                            this.form.errors.set(data.field, data.message);
                            return 0;
                        }
                        auth.login(data.data.token, data.data.user);

                        this.$router.push('/');
                    }).catch(() => {

                })
            }
        }
    }
</script>

<style scoped>

</style>
