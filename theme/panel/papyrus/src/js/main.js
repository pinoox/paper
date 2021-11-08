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
import VuejsDialogMixin from 'vuejs-dialog/dist/vuejs-dialog-mixin.min.js';
import VueSimpleSVG from 'vue-simple-svg';
import VueGoodTablePlugin from 'vue-good-table';
import ChDrawer from 'ch-drawer';
import CKEditor from '@ckeditor/ckeditor5-vue2';
import VueNestable from 'vue-nestable';
import vSelect from 'vue-select';
import ToggleButton from 'vue-js-toggle-button';
import { Row, Column,Hidden } from 'vue-grid-responsive';
import VueApexCharts from 'vue-apexcharts';
import simplebar from 'simplebar-vue';
import PictureInput from 'vue-picture-input';
import Draggable from 'vuedraggable';
import VuePersianDatetimePicker from 'vue-persian-datetime-picker';

Vue.component('simplebar', simplebar);
Vue.component('apexchart', VueApexCharts);
Vue.use(VueNestable);
Vue.use(ChDrawer, {zIndex: 99999});
Vue.use(VueGoodTablePlugin);
Vue.use(VueSimpleSVG);
Vue.use(Notifications);
Vue.use(VuejsDialog);
Vue.use(VueAxios, axios);
Vue.use(CKEditor);
Vue.use(ToggleButton);
Vue.component('v-select', vSelect);
Vue.component('row', Row);
Vue.component('column', Column);
Vue.component('hidden', Hidden);
Vue.component('picture-input', PictureInput);
Vue.component('draggable', Draggable);
Vue.component('paper-date-picker', VuePersianDatetimePicker);

__webpack_public_path__ = PINOOX.URL.THEME + 'dist/paper/';

window.paperEditor = null;

new Vue({
    el: '#app',
    render: h => h(Main),
    router: router,
    store: store,
});

let filter = function(text, length, clamp){
    clamp = clamp || '...';
    let node = document.createElement('div');
    node.innerHTML = text;
    let content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', filter);

