import Dashboard from '../vue/pages/dashboard.vue';
import Articles from '../vue/pages/articles.vue';
import Stats from '../vue/pages/stats.vue';
import Splash from '../vue/pages/splash.vue';

export const routes = [
    {
        path: PINOOX.URL.BASE,
        name: 'splash',
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

];