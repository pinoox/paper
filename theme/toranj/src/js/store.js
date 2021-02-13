import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        LANG: PINOOX.LANG,
        menu: PINOOX.MENU,
        isLoading: false,
        isTransition: true,
    },
    getters: {},
    mutations: {
        updateDirections: (state, direction) => {
            document.body.className = direction;
        },
    },
    actions: {

    }
});