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
                path: 'user',
                component: Parent,
                children: [
                    // {
                    //     path: ':name',
                    //     component: require('./views/user/Home.vue'),
                    //     meta: { requiresAuth: true }
                    // },
                    {
                        path: 'profile',
                        component: require('./views/user/Profile'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'setting',
                        component: require('./views/user/setting'),
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
                path: 'types',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/type/Type.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: require('./views/type/Create.vue'),
                        meta: { requiresAuth: true}
                    },
                    {
                        path: ':id/edit',
                        component: require('./views/type/Edit.vue'),
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'applicat',
                component: require('./views/Applicat.vue'),
                meta: { requiresAuth: true}
            },
            {
                path:'applicat-audit',
                component: require('./views/Applicat-audit.vue'),
                meta: { requiresAuth: true}
            },
            {
                path: 'files',
                component: require('./views/File.vue'),
                meta: { requiresAuth: true }
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
    {
        path: '/500',
        component: require('./views/500.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/404',
        component: require('./views/404.vue'),
        meta: { requiresAuth: false }
    },
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
