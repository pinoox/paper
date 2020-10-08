import Dashboard from '../vue/pages/dashboard.vue';
import Articles from '../vue/pages/articles.vue';
import Stats from '../vue/pages/stats.vue';

export const routes = [
    {
        path: PINOOX.URL.BASE,
        name: 'dashboard',
        component: Dashboard
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

];