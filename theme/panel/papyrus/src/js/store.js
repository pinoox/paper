import Vue from 'vue';
import Vuex from 'vuex';
import $http from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        checkLogin:false,
        LANG: PINOOX.LANG,
        user: {},
        userSettings: {
            paperSize:75,
        },
        configs: {},
        isLoading: false,
        isPrimaryLoading: false,
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
            window.paperEditor.model.change(writer => {
                const imageElement = writer.createElement('image', {
                    src: image,
                });

                window.paperEditor.model.insertContent(imageElement, window.paperEditor.model.document.selection.getLastPosition());
            });
        },
        deleteImageEditor(state, image) {
            let context = window.paperEditor.getData();
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

            window.paperEditor.setData(context);
        },
    },
    actions: {

    }
});