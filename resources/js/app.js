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
import Auth from './auth.js';
import Swal from 'sweetalert2'
import VueProgressBar from 'vue-progressbar'
import {VueEditor} from "vue2-editor";

import VueBus from 'vue-bus';

import Gate from './Gate'

window.auth = new Auth();

Vue.prototype.$gate = new Gate(localStorage.getItem('user'));
Vue.use(VueBus);

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
});

window.Swal = Swal;

Vue.use(VueAxios, axios);
Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: require('./components/Home').default,
        name: 'home',

    },
    {
        path: '/profile',
        component: require('./components/Profile/Profile').default,
        name: 'profile',
        meta:
            {
                requiresAuth: true,
                emailVerify: true,

            },
        children: [
            {
                path: 'settings',
                component: require('./components/Profile/Settings').default,
                name: 'profile-settings',
                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,

                    },
            },
            {
                path: 'products',
                component: require('./components/Profile/Products').default,
                name: 'profile-products',
                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'add-product',
                component: require('./components/Profile/AddProduct').default,
                name: 'profile-add-product',
                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'site-info',
                component: require('./components/Profile/SiteInfo').default,
                name: 'profile-site-info',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            }
        ]

    },
    {
        path: '/login',
        component: require('./components/auth/Login').default,
        name: 'login',
        meta: {requiresAuth: false},

    },

    {
        path: '/register',
        component: require('./components/auth/Register').default,
        name: 'register',

        meta: {
            requiresAuth: false,
        },

    },

    {
        path: '/verify/:token',
        component: require('./components/auth/Verify').default,
        name: 'verify',
        meta:
            {
                requiresAuth: true,
                emailVerify: false,
            },

    },
    {
        path: '/reset-password/:token-:email',
        component: require('./components/auth/Reset').default,
        name: 'reset-password',

        meta: {
            requiresAuth: false,
        },

    },
    {
        path: '/product-item/:id',
        component: require('./components/products/ProductItem').default,
        name: 'product-item',

        meta: {
            requiresAuth: false,
        },

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
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // этот путь требует авторизации, проверяем залогинен ли
        // пользователь, и если нет, перенаправляем на страницу логина
        if (!auth.check()) {
            next({
                path: '/login',
            })
        } else {
            next();
        }
        if (to.matched.some(record => record.meta.emailVerify)) {
            if (auth.check()) {
                if (!auth.checkEmailVerification()) {
                    next('/verify/token');
                }
            }
            if (to.matched.some(record => record.meta.isAdmin)) {
                if (auth.check()) {
                    if (auth.checkEmailVerification()) {
                        if (!Vue.prototype.$gate.isAdmin()) {
                            next({name: '404'})
                        }
                    }
                }
            } else {
                next();
            }
        } else {
            if (auth.check()) {
                if (auth.checkEmailVerification()) {
                    if (to.path === '/verify/' + router.history.current.params.token) {
                        // go to /verify/token if we are from settings, have just changed our email address
                        if (from.path === '/profile/settings') {
                            next(to.fullPath);
                        } else {
                            next('/profile');
                        }
                    }
                }
            }
        }
        next();
    } else {

        if (auth.check()) {
            if (to.path !== '/' && to.path === '/login' || to.path === '/register') {
                next('/profile')
            }
        }
        next();
        // всегда так или иначе нужно вызвать next()!
    }
});

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
    },
    mounted() {
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
            this.emailVerify = auth.checkEmailVerification();

        });
        Event.$on('siteInfoUpdated',function () {
            app.getSiteInfo();
        })
    }
}).$mount('#app');
