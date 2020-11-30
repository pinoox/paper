import Vue from 'vue';
import Vuex from 'vuex';
import $http from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        LANG: PINOOX.LANG,
        user: {},
        userSettings: {},
        configs: {},
        ckEditor: null,
        isLoading: false,
        isTransition: true,
        viewSettings:[],
        countTranslate:0,
    },
    getters: {},
    mutations: {
        updateDirections: (state, direction) => {
            document.body.className = direction;
        },
        addImageEditor: (state, image) => {
            image = typeof image === 'object' ? image.link : image;
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
    actions: {

    }
});