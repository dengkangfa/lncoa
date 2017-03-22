import {
    TOGGLE,
    SET_ACCESS_TOKEN,
    LOGIN,
    OUT_LOGIN,
    SET_USER,
} from './mutation-types'

export default {
    [TOGGLE] (state) {
      return state.sidebar.opened = !state.sidebar.opened
    },
    [SET_ACCESS_TOKEN] (state, access_token) {
			state.access_token = access_token;
		},
		[LOGIN] (state) {
			state.isLogin = true;
		},
    //退出登陆
		[OUT_LOGIN] (state) {
			state.isLogin = false;
			state.user = null;
			state.access_token = '';
      localStorage.clear();
		},
		[SET_USER] (state, user) {
			state.user = user;
		}
}
