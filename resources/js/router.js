import Vue from 'vue'
import VueRouter from 'vue-router'

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
                component: require('./components/Profile/admin/Products').default,
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
                component: require('./components/Profile/admin/AddProduct').default,
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
                component: require('./components/Profile/admin/SiteInfo').default,
                name: 'profile-site-info',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'categories',
                component: require('./components/Profile/admin/Categories').default,
                name: 'profile-categories',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'users',
                component: require('./components/Profile/admin/Users').default,
                name: 'profile-users',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'orders',
                component: require('./components/Profile/admin/Orders').default,
                name: 'profile-orders',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'orders/:userid',
                component: require('./components/Profile/admin/OrderByUserID').default,
                name: 'profile-orders-by-user-id',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'payments',
                component: require('./components/Profile/admin/Payments').default,
                name: 'profile-payments',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
                        isAdmin: true
                    },
            },
            {
                path: 'my-orders',
                component: require('./components/Profile/MyOrders').default,
                name: 'profile-my-orders',

                meta:
                    {
                        requiresAuth: true,
                        emailVerify: true,
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
        path: '/category/:id',
        component: require('./components/products/Category').default,
        name: 'category',
        meta: {
            requiresAuth: false
        },

    },
    {
        path: '/search/:query',
        component: require('./components/Search').default,
        name: 'search',
        meta: {
            requiresAuth: false
        },

    },
    {
        path: '/buy',
        component: require('./components/products/Buy').default,
        name: 'buy',
        meta:
            {
                requiresAuth: true,
                emailVerify: true,
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
                // buy page
                if(to.name == 'buy' && Object.entries(to.params).length === 0 && to.params.constructor === Object){
                    next({name: 'home'});
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

export default router
