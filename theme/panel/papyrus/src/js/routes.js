import Dashboard from '../vue/pages/dashboard.vue';
import Posts from '../vue/pages/posts.vue';
import Pages from '../vue/pages/pages.vue';
import Stats from '../vue/pages/stats.vue';
import Splash from '../vue/pages/splash.vue';
import Login from '../vue/pages/login.vue';
import Write from '../vue/pages/write.vue';
import PostStats from '../vue/pages/post-stats.vue';
import Users from '../vue/pages/users.vue';
import Contacts from '../vue/pages/contacts.vue';
import Comments from '../vue/pages/comments.vue';
import PageWrite from '../vue/pages/page-write.vue';
import Profile from '../vue/pages/profile.vue';
import Setting from '../vue/pages/setting/main.vue';
import SettingGeneral from '../vue/pages/setting/general.vue';
import Error from '../vue/pages/error.vue';

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
        path: PINOOX.URL.BASE + '/pages',
        name: 'pages',
        component: Pages
    },
    {
        path: PINOOX.URL.BASE + '/stats',
        name: 'stats',
        component: Stats
    },
    {
        path: PINOOX.URL.BASE + '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            showToolbar: true,
        }
    },
    {
        path: PINOOX.URL.BASE + '/write/:post_id?',
        name: 'write',
        component: Write,
        props: true,
    },
    {
        path: PINOOX.URL.BASE + '/page/write/:post_id?',
        name: 'page-write',
        component: PageWrite,
        props: true,
    },
    {
        path: PINOOX.URL.BASE + '/post/stats/:post_id',
        name: 'post-stats',
        component: PostStats,
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
    {
        path: PINOOX.URL.BASE + '/users',
        name: 'users',
        component: Users,
    },
    {
        path: PINOOX.URL.BASE + '/contacts',
        name: 'contacts',
        component: Contacts
    },
    {
        path: PINOOX.URL.BASE + '/comments',
        name: 'comments',
        component: Comments
    },
    {
        path: PINOOX.URL.BASE + '/profile',
        name: 'profile',
        component: Profile,
    },
    {
        path: PINOOX.URL.BASE + '/setting',
        name: 'setting',
        component: Setting,
    },
    {
        path: PINOOX.URL.BASE + '/setting/general',
        name: 'setting-general',
        component: SettingGeneral,
    },
    {
        path: PINOOX.URL.BASE + '/error',
        name: 'error',
        component: Error,
    },
];