import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions.js';
import mutations from './mutations';

Vue.use(Vuex);

const state = {
    //右侧导航状态
    sidebar: {
        opened: false
    },
    //登录状态
    isLogin: false,
    //用户信息
    user: {},
    //用户令牌
    access_token: '',
    //所有角色信息
    roles: [],
    //场地信息
    types: [],
    //是否是手机
    isPhone: false,
    //通知
    notificats: []
};

export default new Vuex.Store({
    state,
    actions,
    mutations
});
