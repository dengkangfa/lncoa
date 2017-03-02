import Dashboard from './views/Dashboard.vue'
import Parent from './views/Parent.vue'

export default [
    {
        path: '/',
        component: Dashboard,
        beforeEnter: requireAuth,
        children: [
            {
                path: '/',
                redirect: '/home'
            },
            {
                path: 'home',
                // component: require('./views/dashboard/Home.vue')
            },
            {
                path: 'users',
                component: Parent,
                children: [
                    {
                        path: '/',
                        component: require('./views/user/User.vue')
                    },
                    {
                        path: 'create',
                        // component: require('./views/dashboard/user/Create.vue')
                    },
                    {
                        path: ':id/edit',
                        // component: require('./views/dashboard/user/Edit.vue')
                    }
                ]
            },
            {
                path: 'tags',
                component: Parent,
                children: [
                    {
                        path: '/',
                        // component: require('./views/dashboard/tag/Tag.vue')
                    },
                    {
                        path: 'Create',
                        // component: require('./views/dashboard/tag/Create.vue')
                    },
                    {
                        path: ':id/edit',
                        // component: require('./views/dashboard/tag/Edit.vue')
                    }
                ]
            },
            {
                path: 'files',
                // component: require('./views/dashboard/File.vue')
            },
            {
                path: 'categories',
                component: Parent,
                children: [
                    {
                        path: '/',
                        // component: require('./views/dashboard/category/Category.vue')
                    },
                    {
                        path: 'create',
                        // component: require('./views/dashboard/category/Create.vue')
                    },
                    {
                        path: ':id/edit',
                        // component: require('./views/dashboard/category/Edit.vue')
                    }
                ]
            },
            {
                path: 'visitors',
                // component: require('./views/dashboard/Visitor.vue')
            },
            {
                path: 'system',
                // component: require('./views/dashboard/System.vue')
            },
            {
                path: 'characters',
            },
            {
                path: 'authoritys'
            },
            {
                path: '*',
                redirect: '/'
            }
        ]
    }
]

function requireAuth (to, from, next) {
    if (window.User) {
        return next()
    } else {
        return next('/')
    }
}
