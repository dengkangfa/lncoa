
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//路由
import VueRouter from 'vue-router';
//状态管理
import store from './vuex/store.js';
//国际化
import VueI18n from 'vue-i18n';

import routes from './routes.js';
import locales from './lang';

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import App from './App.vue';
import server from './config/api'

Vue.use(VueI18n);
Vue.use(VueRouter);
Vue.use(ElementUI);
Vue.config.lang =localStorage.Language ? window.Language = localStorage.Language : window.Language;
Object.keys(locales).forEach(function (lang){
    Vue.locale(lang,locales[lang])
})

//消息提示框对象
window.toastr = require('toastr/build/toastr.min.js');

window.toastr.options = {
    positionClass: "toast-bottom-right",
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

Vue.component(
    'vue-table-pagination',
    require('./components/TablePagination.vue')
);

Vue.component(
    'vue-table',
    require('./components/Table.vue')
);

Vue.component(
    'vue-form',
    require('./components/Form.vue')
);

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    routes
});

router.beforeEach ((to, from, next) => {
    //还原滚动条
    window.scrollTo(0, 0);
    // Auth验证
    if (to.matched.some(record => record.meta.requiresAuth)) {
       if (!store.state.isLogin) {
          if (localStorage.access_token) {
              store.commit('SET_ACCESS_TOKEN', localStorage.access_token);
              store.commit('LOGIN');
              axios.defaults.headers.common['Authorization'] = 'Bearer ' + store.state.access_token;
              axios.get(server.api.user + '?include=roles').then((response) => {
                  store.commit('SET_USER', response.data.data);
              },(response) => {
                  return next('/login');
              });
              return next();
          }
          return next('/login');
       }
    }
    return next();
});
new Vue(Vue.util.extend({ router, store },App)).$mount('#app');
