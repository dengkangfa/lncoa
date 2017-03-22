import Dashboard from './views/Dashboard.vue'
import Parent from './views/Parent.vue'
import server from './config/api'

export default [
    {
        path: '/',
        component: Dashboard,
        children: [
            {
                path: '',
                // component: require('./views/user/User.vue'),
                meta: { requiresAuth: true }
            },
            {
                path: 'users',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/user/User.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: require('./views/user/Create.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: ':id/edit',
                        component: require('./views/user/Edit.vue'),
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'roles',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/role/Role.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: require('./views/role/Create.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: ':id/edit',
                        component: require('./views/role/Edit.vue'),
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'permissions',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/permission/Permission.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: require('./views/permission/Create.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: ':id/edit',
                        component: require('./views/permission/Edit.vue'),
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'system',
                component: require('./views/System.vue'),
                meta: { requiresAuth: true }
            },
        ],
    },
    {
        path: '/login',
        beforeEnter: checkLogin,
        component: require('./views/Login.vue'),
        meta: { requiresAuth: false }
    },
    // {
    //     path: '/404',
    //     component: require('./views/404.vue'),
    //     meta: { requiresAuth: false }
    // },
    // {
    //   path: '*',
    //   redirect: '/404'
    // }
]

function checkLogin (to, from, next) {
    if(localStorage.access_token) {
        return next('/');
    }
    return next();
}
