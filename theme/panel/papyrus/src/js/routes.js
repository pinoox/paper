import Dashboard from '../vue/pages/dashboard.vue';
import Articles from '../vue/pages/articles.vue';
import Stats from '../vue/pages/stats.vue';
import Splash from '../vue/pages/splash.vue';
import Login from '../vue/pages/login.vue';
import Write from '../vue/pages/write.vue';

export const routes = [
    {
        path: PINOOX.URL.BASE,
        name: 'splash',
        meta:{
            'box-view': false,
        },
        component: Splash
    },
    {
        path: PINOOX.URL.BASE + '/articles',
        name: 'articles',
        component: Articles
    },
    {
        path: PINOOX.URL.BASE + '/stats',
        name: 'stats',
        component: Stats
    },
    {
        path: PINOOX.URL.BASE + '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: PINOOX.URL.BASE + '/write',
        name: 'write',
        component: Write
    },
    {
        path: PINOOX.URL.BASE + '/login',
        name: 'login',
        meta:{
            'box-view': false,
        },
        component: Login,
    },

];