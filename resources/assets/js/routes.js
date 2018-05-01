import Dashboard from './views/Dashboard.vue'
import Parent from './views/Parent.vue'
import BeforeParent from './views/BeforeParent.vue'
import server from './config/api'
import store from './vuex/store.js'

const Index = (resolve) => {
  import('./views/Index.vue').then(module => {
    resolve(module)
  })
}

const User = (resolve) => {
    import('./views/user/User.vue').then(module => {
        resolve(module)
    })
}

const UserCreate = (resolve) => {
  import('./views/user/Create.vue').then(module => {
    resolve(module)
  })
}

const UserEdit = (resolve) => {
  import('./views/user/Edit.vue').then(module => {
    resolve(module)
  })
}
//
// const User = (resolve) => {
//   import('./views/user/User.vue').then(module => {
//     resolve(module)
//   })
// }

const Profile = (resolve) => {
  import('./views/user/Profile').then(module => {
    resolve(module)
  })
}

const Setting = (resolve) => {
  import('./views/user/Setting').then(module => {
    resolve(module)
  })
}

const Role = (resolve) => {
  import('./views/role/Role.vue').then(module => {
    resolve(module)
  })
}

const RoleCreate = (resolve) => {
  import('./views/role/Create.vue').then(module => {
    resolve(module)
  })
}

const RoleEdit = (resolve) => {
  import('./views/role/Edit.vue').then(module => {
    resolve(module)
  })
}

const Permission = (resolve) => {
  import('./views/permission/Permission.vue').then(module => {
    resolve(module)
  })
}

const PermissionCreate = (resolve) => {
  import('./views/permission/Create.vue').then(module => {
    resolve(module)
  })
}

const PermissionEdit = (resolve) => {
  import('./views/permission/Edit.vue').then(module => {
    resolve(module)
  })
}

const Type = (resolve) => {
  import('./views/type/Type.vue').then(module => {
    resolve(module)
  })
}

const TypeCreate = (resolve) => {
  import('./views/type/Create.vue').then(module => {
    resolve(module)
  })
}

const TypeEdit = (resolve) => {
  import('./views/type/Edit.vue').then(module => {
    resolve(module)
  })
}

const Applicat = (resolve) => {
  import('./views/applicat/Applicat.vue').then(module => {
    resolve(module)
  })
}

const Review = (resolve) => {
  import('./views/applicat/Review.vue').then(module => {
    resolve(module)
  })
}

const ReviewDetails = (resolve) => {
  import('./views/applicat/Review-details.vue').then(module => {
    resolve(module)
  })
}

const ApplicatManage = (resolve) => {
  import('./views/applicat/Applicat-manage.vue').then(module => {
    resolve(module)
  })
}

const ApplicatManageDetails = (resolve) => {
  import('./views/applicat/Applicat-manage-details.vue').then(module => {
    resolve(module)
  })
}

const ReviewManage = (resolve) => {
  import('./views/applicat/Review-manage.vue').then(module => {
    resolve(module)
  })
}

const Filing = (resolve) => {
  import('./views/system/Filing.vue').then(module => {
    resolve(module)
  })
}

const Notice = (resolve) => {
  import('./views/system/Notice.vue').then(module => {
    resolve(module)
  })
}

const Log = (resolve) => {
  import('./views/Log.vue').then(module => {
    resolve(module)
  })
}

const System = (resolve) => {
  import('./views/System.vue').then(module => {
    resolve(module)
  })
}

const Folder = (resolve) => {
  import('./views/user/Folder.vue').then(module => {
    resolve(module)
  })
}

const Login = (resolve) => {
  import('./views/auth/Login.vue').then(module => {
    resolve(module)
  })
}

const Register = (resolve) => {
  import('./views/auth/Register.vue').then(module => {
    resolve(module)
  })
}

const ResetPassword = (resolve) => {
  import('./views/auth/Password.vue').then(module => {
    resolve(module)
  })
}

const Reset = (resolve) => {
  import('./views/auth/Reset.vue').then(module => {
    resolve(module)
  })
}

export default [
    {
        path: '/',
        component: Dashboard,
        children: [
            {
                path: '',
                component: Index,
                meta: { requiresAuth: true }
            },
            {
                path: 'users',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: User,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: UserCreate,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: ':id/edit',
                        component: UserEdit,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'user',
                component: Parent,
                children: [
                    {
                        path: 'profile',
                        component: Profile,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'setting',
                        component: Setting,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'roles',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: Role,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: RoleCreate,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: ':id/edit',
                        component: RoleEdit,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'permissions',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: Permission,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: PermissionCreate,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: ':id/edit',
                        component: PermissionEdit,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: '/system-types',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: Type,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'create',
                        component: TypeCreate,
                        meta: { requiresAuth: true}
                    },
                    {
                        path: ':id/edit',
                        component: TypeEdit,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'applicat',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: Applicat,
                        meta: { requiresAuth: true }
                    },
                ]
            },
            {
                path: 'review',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: Review,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'details/:id',
                        component: ReviewDetails,
                        meta: { requiresAuth: true}
                    },
                    {
                        path: 'details',
                        component: ReviewDetails,
                        meta: { requiresAuth: true}
                    }
                ]
            },
            // {
            //   path:'applicat-review',
            //   component: require('./views/applicat/Applicat-review.vue'),
            //   meta: { requiresAuth: true}
            // },
            {
                path: 'applicat-manage',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: ApplicatManage,
                        meta: { requiresAuth: true }
                    },
                    {
                        path: 'details/:id',
                        component: ApplicatManageDetails,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: '/review-manage',
                component: Parent,
                beforeEnter: checkUrl,
                children: [
                    {
                        path: '/',
                        component: ReviewManage,
                        meta: { requiresAuth: true }
                    }
                ]
            },
            {
                path: 'system-files',
                component: Filing,
                beforeEnter: checkUrl,
                meta: { requiresAuth: true }
            },
            {
              path: '/notice',
              component: Notice,
              beforeEnter: checkUrl,
              meta: { requiresAuth: true }
            },
            {
                path: 'log',
                component: Log,
                meta: { requiresAuth: true }
            },
            {
                path: 'system',
                component: System,
                beforeEnter: checkUrl,
                meta: { requiresAuth: true }
            },
            {
                path: '/files',
                component: Folder,
                beforeEnter: checkUrl,
                meta: { requiresAuth: true }
            },
            {
                path: '/logout',
                meta: { requiresAuth: false }
            }
        ],
    },
    {
        path: '/',
        component: BeforeParent,
        children: [
            {
                path: '/login',
                beforeEnter: checkLogin,
                component: Login,
                meta: { requiresAuth: false }
            },
            {
                path: '/register',
                beforeEnter: checkLogin,
                component: Register,
                meta: { requiresAuth: false }
            },
            {
                path: '/password_reset',
                beforeEnter: checkLogin,
                component: ResetPassword,
                meta: { requiresAuth: false }
            },
            {
                path: 'password/reset/:token',
                beforeEnter: checkLogin,
                component: Reset,
                meta: { requiresAuth: false }
            }
        ]
    },
    {
      path: '*',
      redirect: '/404'
    }
]

function checkLogin (to, from, next) {
    if(localStorage.access_token || sessionStorage.access_token) {
        return next('/');
    }
    return next();
}

function checkUrl (to, form, next) {
  //遍历判断当前用户是否可以访问to的路由
  for (var i = 0; i < store.state.menus.length; i++) {
    if(to.path.indexOf(store.state.menus[i].uri) == 0) {
        return next();
    }
  }
  return next('/');
}
