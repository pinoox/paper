import Vue from 'vue';
import Vuex from 'vuex';
import $http from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: {},
        ckEditor: null,
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
        addImageEditor: (state, image) => {
            state.ckEditor.model.change(writer => {
                const imageElement = writer.createElement('image', {
                    src: image,
                });

                state.ckEditor.model.insertContent(imageElement, state.ckEditor.model.document.selection.getLastPosition());
            });
        },
        deleteImageEditor(state, image) {
            let context = state.ckEditor.getData();
            const regex = /(?<tag><figure(.*?)<img[^>]+src="(?<link>[^">]+)"(.*?)<\/figure>)/gm;
            let m;
            while ((m = regex.exec(context)) !== null) {
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                }
                let groups = !!m.groups ? m.groups : {};
                if (image === groups.link) {
                    context = context.replaceAll(groups.tag, '');
                }
            }

            state.ckEditor.setData(context);
        },
    },
    actions: {}
});