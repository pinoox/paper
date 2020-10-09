import Vue from 'vue';
import Vuex from 'vuex';
import $http from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: {},
        isLoading: false,
    },
    getters: {},
    mutations: {
        getUser: (state) => {
            $http.get(PINOOX.URL.API + 'user/get').then((json) => {
                if (!!json.data && json.data.status && json.data.status !== 404) {
                    let data = json.data.result;
                    data.isLogin = true;
                    state.user = data;
                } else {
                    state.user = {isLogin: false}
                }

            });
        },
    },
    actions: {}
});