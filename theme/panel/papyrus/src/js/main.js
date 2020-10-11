import Vue from 'vue';
import './global';
import axios from 'axios';

axios.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded';
import axiosMethodOverride from 'axios-method-override';

axiosMethodOverride(axios);
const instance = axios.create();
axiosMethodOverride(instance);
import VueAxios from 'vue-axios';
import store from './store';
import Main from '../vue/main.vue';
import router from './router';
import Notifications from 'vue-notification';
import VuejsDialog from 'vuejs-dialog';
import VueSimpleSVG from 'vue-simple-svg';
import VueGoodTablePlugin from 'vue-good-table';
import ChDrawer from 'ch-drawer';
import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use(ChDrawer, { zIndex: 1000 });
Vue.use(VueGoodTablePlugin);
Vue.use(VueSimpleSVG);
Vue.use(Notifications);
Vue.use(VuejsDialog);
Vue.use(VueAxios, axios);
Vue.use(CKEditor);

__webpack_public_path__ = PINOOX.URL.THEME + 'dist/';

new Vue({
    el: '#app',
    render: h => h(Main),
    router: router,
    store: store,
});

