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
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios';
import {Form, HasError, AlertError} from 'vform'
import auth from './auth.js';

window.auth = auth;

Vue.use(VueAxios, axios);
Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: require('./components/Home').default,
        name: 'home'
    },
    {
        path: '/profile',
        component: require('./components/Profile').default,
        name: 'profile',
        meta: { middlewareAuth: true }


    },
    {
        path: '/login',
        component: require('./components/auth/Login').default,
        name: 'login',
        meta: { middlewareAuth: false }

    },

    {
        path: '/register',
        component: require('./components/auth/Register').default,
        name: 'register',
        meta: { middlewareAuth: false }


    },
    {
        path: '/logout',
        component: require('./components/auth/Logout').default,
        name: 'logout',
        meta: { middlewareAuth: true }


    },

    {
        path: '*',
        component: require('./components/NotFound').default,
        name: '404'
    },
];

const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
    }

    next();
});

// vform

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);



window.Event = new Vue;



// window.access_token = localStorage.getItem('access_token');
// axios.defaults.headers.common['Authorization'] = 'Bearer ' + access_token;
// axios.defaults.headers.post['Content-Type'] = 'application/json';


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    data() {
        return {
            authenticated: auth.check(),
            user: auth.user
        }
    },
    mounted() {
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    }
}).$mount('#app');
