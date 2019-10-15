/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');

require('owl.carousel');
require('slick-carousel');


// vue router
import axios from 'axios'
import VueAxios from 'vue-axios';
import {Form, HasError, AlertError} from 'vform'
import Auth from './auth.js';
import router from './router.js';
import Swal from 'sweetalert2'
import VueProgressBar from 'vue-progressbar'
import {VueEditor} from "vue2-editor";


import Gate from './Gate'

window.auth = new Auth();


Vue.prototype.$gate = new Gate(localStorage.getItem('user'));

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
});

window.Swal = Swal;

Vue.use(VueAxios, axios);

// vform

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);


window.Event = new Vue;

Vue.component(VueEditor.name, VueEditor);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    router,
    data() {
        return {
            categories: '',
            treeCategories:'',
            isAuth: auth.check(),
            emailVerify: auth.checkEmailVerification(),
            siteInfo: {},
        }
    },
    methods: {
        logout() {
            auth.logout();
        },
        getSiteInfo() {
            this.axios.get('/api/site-info').then((response) => {
                this.siteInfo = response.data;
                delete this.siteInfo.id;
            })
        },
        getCategories() {
            this.axios.get('/api/category').then((response) => {
                this.categories = response.data;
                this.treeCategories = this.creatingTree();
            });
        },
        creatingTree(categories = this.categories, tree = '') {
            for (let category of categories) {
                if (!category.parent_id) {
                    // category has not children
                    tree += `<div class="col-lg-4 mb-5">`;
                    tree += '<div class="categories-item">';
                    tree += '<div class="categories-img text-center">';
                    tree += `<img src='/images/categories/${category.photo}' style="max-width: 100%; height:70px" alt="">`;
                    tree += '</div>';
                    tree += '<div class="categories-title">';
                    tree += `<h2>${category.title}</h2>`;
                    tree += '</div>';
                    tree +=
                                '<div class="categories-list">' +
                                    '<ul>';
                    for (let child  of category.children){
                        tree += `<li style="text-align: center"><a href="#">${child.title}</a></li>`
                    }
                            tree += '</ul>' +
                                '</div>' +
                            '</div>' +
                        ' </div>'
                }
            }
            return tree;

        },
    },
    mounted() {
        this.getCategories();
        this.getSiteInfo();
        let app = this;

        Event.$on('userLoggedIn', () => {
            this.isAuth = auth.check();
            Vue.prototype.$gate = new Gate(localStorage.getItem('user'));

        });

        Event.$on('logout', () => {
            this.isAuth = false;
            if (router.currentRoute.path !== '/') {
                router.push('/');
            }

        });
        Event.$on('emailVerified', () => {
            window.auth = new Auth();
            // todo this.emailVerify is inside this function not inside the app.js, but it works, very weird-_- and upper as well
            this.emailVerify = auth.checkEmailVerification();

        });
        Event.$on('siteInfoUpdated', function () {
            app.getSiteInfo();
        });
        Event.$on('changesInCategories', function () {
            app.treeCategories = app.getCategories();
        });

        $(document).ready(function () {
            $('#categoriesShow').hover(function () {
                    $('#categories-items').css('display', 'flex');
                },
                function () {
                    $('#categories-items').css('display', 'none');
                }
            );
        });
    }
}).$mount('#app');
