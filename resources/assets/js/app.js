
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
//表单验证
import zh_CN from 'vee-validate/dist/locale/zh_CN';
import VeeValidate, { Validator } from 'vee-validate';

import routes from './routes.js';
import locales from './lang';

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import App from './App.vue';
import server from './config/api'

Vue.use(VueI18n);
Vue.use(VueRouter);
// Add locale helper.
Validator.addLocale(zh_CN);
const validate_config = {
  errorBagName: 'errors', // change if property conflicts.
  fieldsBagName: 'validataFields',
  delay: 0,
  locale: 'zh_CN',
  dictionary: null,
  strict: true,
  enableAutoClasses: false,
  classNames: {
    touched: 'touched', // the control has been blurred
    untouched: 'untouched', // the control hasn't been blurred
    valid: 'valid', // model is valid
    invalid: 'invalid', // model is invalid
    pristine: 'pristine', // control has not been interacted with
    dirty: 'dirty' // control has been interacted with
  }
};
Vue.use(VeeValidate, validate_config);
Vue.use(ElementUI);
Vue.config.lang =localStorage.Language ? window.Language = localStorage.Language : window.Language;
Object.keys(locales).forEach(function (lang){
    Vue.locale(lang,locales[lang])
})
// Vue.validator.setLocale(window.Language);
// Validator.setLocale(window.Language);

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

Vue.component(
    'vue-table-filtra',
    require('./components/TableFiltra.vue')
);

Vue.component('avatar', require('./components/AvatarUpload.vue'));

Vue.component('type-item', require('./components/TypeItem.vue'));


const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    routes
});

router.beforeEach ((to, from, next) => {
    // localStorage.clear();
    //还原滚动条
    window.scrollTo(0, 0);
    // Auth验证
    if (to.matched.some(record => record.meta.requiresAuth)) {
       if (!store.state.isLogin) {
          if (localStorage.access_token) {
              store.commit('SET_ACCESS_TOKEN', localStorage.access_token);
              store.commit('LOGIN');
              store.commit('JUDGE_PHONE');
              axios.defaults.headers.common['Authorization'] = 'Bearer ' + store.state.access_token;
              // Vue.nextTick(() => {
              // axios.get(server.api.user + '?include=roles,menus,permissions&login').then((response) => {
              //   console.log(response);
              //     store.commit('SET_MENUS', response.data.data.menus.data);
              //     store.commit('SET_USER', response.data.data);
              //     store.commit('SET_PERMISSIONS', response.data.data.permissions.data);
              // },(response) => {
              //     return next('/login');
              // });

              //拥有后面的路由权限判断需要用到用户的菜单信息，所有将默认的异步获取
              //数据改成同步
              let settings = {
                 type: "GET",
                 async: false,
                 url: server.api.user + '?include=roles,menus,permissions&login',
                 success: function(response,textStatus) {
                   store.commit('SET_MENUS', response.data.menus.data);
                   store.commit('SET_USER', response.data);
                   store.commit('SET_PERMISSIONS', response.data.permissions.data);
                 },
                 error: function (){
                   return next('/login');
                 },
                 headers: {
                     "Authorization":'Bearer ' + store.state.access_token,
                 }
             };

              $.ajax(settings);

              axios.get(server.api.type + "?structure=tree").then((response) => {
                  store.commit('SET_TYPES', response.data);
              },(response) => {
                  return next('/login');
              });
            // })
              return next();
          }
          return next('/login');
       }
    }
    return next();
});
new Vue(Vue.util.extend({ router, store },App)).$mount('#app');
