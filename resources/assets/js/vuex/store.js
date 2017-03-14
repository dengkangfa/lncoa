import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions.js';
import mutations from './mutations';

Vue.use(Vuex);

const state = {
    sidebar: {
        opened: false
    },
    isLogin: false,
    user: {},
    access_token: ''
};

export default new Vuex.Store({
    state,
    actions,
    mutations
});
