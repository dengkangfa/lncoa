
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

Vue.use(VueI18n);
Vue.use(VueRouter);
Vue.use(ElementUI);
Vue.config.lang = window.Language;
Object.keys(locales).forEach(function (lang){
    Vue.locale(lang,locales[lang])
})

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    routes
});
new Vue(Vue.util.extend({ router, store },App)).$mount('#app');
