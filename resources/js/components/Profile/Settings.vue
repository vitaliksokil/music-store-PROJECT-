<template>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Profile settings</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" @submit.prevent="updateProfile()">
            <div class="alert alert-danger" role="alert" v-if="form.errors.has('errorMessage')">
                {{form.errors.get('errorMessage')}}
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-primary mb-3" @click.prevent="isChangeName = !isChangeName">Change
                    name
                </button>
                <div class="form-group" v-if="isChangeName">
                    <p>Your current name: {{user.name}}</p>

                    <label for="name">New name:</label>
                    <input type="text" class="form-control" id="name" v-model="form.name"
                           :class="{ 'is-invalid' : form.errors.has('name')  }">
                    <has-error :form="form" field="name"></has-error>

                </div>
                <br>
                <button type="button" class="btn btn-primary mb-3" @click.prevent="isChangePass = !isChangePass">Change
                    password
                </button>

                <div class="form-group" v-if="isChangePass">
                    <label for="newPassword">New password</label>
                    <input type="password" class="form-control" id="newPassword" v-model="form.newPassword"  :class="{ 'is-invalid' : form.errors.has('newPassword')  }">
                    <has-error :form="form" field="newPassword"></has-error>

                    <label for="confirmPass">Confirm new password</label>
                    <input type="password" class="form-control" id="confirmPass" v-model="form.confirmPassword">


                </div>


            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-success">Save changes</button>
            </div>
        </form>

        <hr>
        <form role="form" @submit.prevent="updateEmail()">
            <div class="box-body">
                <div class="form-group">
                    <label for="email">Your email address: {{user.email}}</label>
                    <input type="email" class="form-control" id="email" v-model="newEmail" placeholder="Your new email adress">
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-success">Change email</button>
            </div>
        </form>

    </div>
</template>

<script>

    export default {
        name: "Settings",
        props: ['userData'],
        data() {
            return {
                user: this.userData,
                isChangePass: false,
                isChangeName: false,
                newEmail:'',
                form: new Form({
                    name: '',
                    currentPassword: '',
                    newPassword: '',
                    confirmPassword: '',
                })
            }
        },
        methods: {
            async updateProfile() {
                if (this.form.name === '' && this.form.newPassword === '') {
                    this.form.errors.set('errorMessage', 'Some field is required')
                } else {
                    if (this.form.newPassword !== '' && this.form.newPassword !== this.form.confirmPassword) {
                        this.form.errors.set('newPassword', 'Passwords are not equals');
                        return;
                    }
                    const {value: password} = await Swal.fire({
                        title: 'Please enter your current password to confirm your changes',
                        input: 'password',
                        inputPlaceholder: 'Enter your current password',
                    });
                    if (password) {
                        this.form.currentPassword = password;
                        this.$Progress.start();
                        this.form.post('/api/update-profile').then((response) => {
                            this.$Progress.finish();
                            let messageType = response.data.messageType;
                            let message = response.data.message;
                            if (messageType === 'success') {
                                this.$parent.getUser();
                                this.form.reset();
                                Swal.fire(
                                    'Success!!!',
                                    message,
                                    'success'
                                )

                            } else {
                                this.$Progress.fail();
                                Swal.fire(
                                    'Error',
                                    message,
                                    'error'
                                )
                            }
                        }).catch(({response}) => {
                            this.$Progress.fail();

                            Swal.fire(
                                'Error',
                                response.data.message,
                                'error'
                            )
                        });
                    }
                }
            },
            async updateEmail() {
                const {value: password} = await Swal.fire({
                    title: 'Please enter your current password to confirm your changes',
                    input: 'password',
                    inputPlaceholder: 'Enter your current password',
                });
                if (password) {
                    this.$Progress.start();
                    this.axios.post('/api/update-email',{password:password,email:this.user.email,newEmail:this.newEmail}).then((response)=>{
                        this.$Progress.finish();
                        let messageType = response.data.messageType;
                        let message = response.data.message;
                        if (messageType === 'success') {
                            this.$parent.getUser(); // refreshing page data of user
                            auth.getUser(); // refreshing all user data in localstorage
                            this.newEmail = '';
                            this.$router.push('/verify/token');

                            Swal.fire(
                                'Success!!!',
                                message,
                                'success'
                            )

                        } else {
                            this.$Progress.fail();
                            Swal.fire(
                                'Error',
                                message,
                                'error'
                            )
                        }
                    }).catch(({response}) => {
                        this.$Progress.fail();

                        Swal.fire(
                            'Error',
                            response.data.message,
                            'error'
                        )
                    });
                }

            },

        },
        mounted() {


        },
        updated() {

        }

    }
</script>

<style scoped>

</style>
