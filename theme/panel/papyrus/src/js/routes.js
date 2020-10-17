import Dashboard from '../vue/pages/dashboard.vue';
import Posts from '../vue/pages/posts.vue';
import Stats from '../vue/pages/stats.vue';
import Splash from '../vue/pages/splash.vue';
import Login from '../vue/pages/login.vue';
import Write from '../vue/pages/write.vue';

export const routes = [
    {
        path: PINOOX.URL.BASE,
        name: 'splash',
        meta: {
            customView: true,
        },
        component: Splash
    },
    {
        path: PINOOX.URL.BASE + '/posts',
        name: 'posts',
        component: Posts
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
        path: PINOOX.URL.BASE + '/write/:post_id?',
        name: 'write',
        component: Write,
        meta: {
            hideToolbar: true,
        },
        props: true,
    },
    {
        path: PINOOX.URL.BASE + '/login',
        name: 'login',
        meta: {
            customView: true,
        },
        component: Login,
    },

];