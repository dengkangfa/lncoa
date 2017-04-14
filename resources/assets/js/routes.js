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
                component: require('./views/Index.vue'),
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
                path: '/system-types',
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
                component: require('./views/applicat/Applicat.vue'),
                meta: { requiresAuth: true}
            },
            {
                path: 'audit',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/applicat/Audit.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'details/:id',
                        component: require('./views/applicat/Audit-details.vue'),
                        meta: { requiresAuth: true}
                    }
                ]
            },
            // {
            //   path:'applicat-audit',
            //   component: require('./views/applicat/Applicat-audit.vue'),
            //   meta: { requiresAuth: true}
            // },
            {
                path: 'applicat-manage',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/applicat/Applicat-manage.vue'),
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'details/:id',
                        component: require('./views/applicat/Applicat-manage-details.vue'),
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'system-files',
                component: require('./views/system/Filing.vue'),
                meta: { requiresAuth: true }
            },
            {
              path: '/notice',
              component: require('./views/system/Notice.vue'),
              meta: { requiresAuth: true }
            },
            {
                path: 'log',
                component: require('./views/Log.vue'),
                meta: { requiresAuth: true }
            },
            {
                path: 'system',
                component: require('./views/System.vue'),
                meta: { requiresAuth: true }
            },
            {
                path: '/files',
                component: require('./views/user/Folder.vue'),
                meta: { requiresAuth: true }
            }
        ],
    },
    {
        path: '/login',
        beforeEnter: checkLogin,
        component: require('./views/Login.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/register',
        beforeEnter: checkLogin,
        component: require('./views/Register.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/500',
        component: require('./views/500.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/signup/confirm/:token',
        component: require('./views/activa.vue'),
        meta: { requiresAuth: false }
    },
    // {
    //     path: '/404',
    //     component: require('./views/404.vue'),
    //     meta: { requiresAuth: false }
    // },
    {
      path: '*',
      redirect: '/404'
    }
]

function checkLogin (to, from, next) {
    if(localStorage.access_token) {
        return next('/');
    }
    return next();
}
