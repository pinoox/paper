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

Vue.use(Notifications);
Vue.use(VuejsDialog);
Vue.use(VueAxios, axios);

__webpack_public_path__ = PINOOX.URL.THEME + 'dist/';

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

