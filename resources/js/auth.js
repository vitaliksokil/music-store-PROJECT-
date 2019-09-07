import axios from "axios";

class Auth {
    constructor() {
        this.token = window.localStorage.getItem('token');
        this.user = window.localStorage.getItem('user');

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
            //when using console create an auth (auth.login('token',{}) reloading the page will logout fake user

            this.getUser();


        }
    }
    logout(){
        Swal.fire({
            title: 'Are you sure you want to logout?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout'
        }).then((result) => {
            if (result.value) {
                axios.post('api/logout').then(({data})=>{
                    localStorage.clear();
                    Event.$emit('logout');
                    this.token = null;
                    this.user = null;
                    Swal.fire({
                        type: 'success',
                        title: 'Logout successful',
                    });
                }).catch(()=>{
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                });
            }
        })

    }
    getUser(callback) {

        axios.get('api/get-user')
            .then(({data}) => {
                this.user = data;
                callback();
            })
            .catch(({response}) => {
                if(response){
                    if (response.status === 401) {
                        localStorage.clear();
                    }
                }
            });
    }
    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        this.getUser(()=>{
            if(this.token && this.user) {
                Event.$emit('userLoggedIn');
            }
        });




    }
    check () {
        return  !! this.token;
    }

}

export default Auth;
