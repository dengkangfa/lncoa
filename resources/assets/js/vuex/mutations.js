import {
    TOGGLE,
    SET_ACCESS_TOKEN,
    LOGIN,
    LOGOUT,
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
		[LOGOUT] (state) {
			state.isLogin = false;
			state.user = {};
			state.access_token = '';
		},
		[SET_USER] (state, user) {
			state.user = user;
		}
}
