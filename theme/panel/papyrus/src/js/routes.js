import Dashboard from '../vue/pages/dashboard.vue';
import Posts from '../vue/pages/posts.vue';
import Stats from '../vue/pages/stats.vue';
import Splash from '../vue/pages/splash.vue';
import Login from '../vue/pages/login.vue';
import Write from '../vue/pages/write.vue';
import PostStats from '../vue/pages/post-stats.vue';
import Users from '../vue/pages/users.vue';
import Contacts from '../vue/pages/contacts.vue';
import Comments from '../vue/pages/comments.vue';

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

];