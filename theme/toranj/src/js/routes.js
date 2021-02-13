import Home from '../vue/pages/home.vue';
import Profile from '../vue/pages/profile.vue';
import Post from '../vue/pages/post.vue';
import Search from '../vue/pages/search.vue';
import Tag from '../vue/pages/tag.vue';
import Contact from '../vue/pages/contact.vue';
import ErrorPage from '../vue/pages/errorPage.vue';

export const routes = [
    {
        path: PINOOX.URL.BASE,
        name: 'home',
        component: Home
    },
    {
        path: PINOOX.URL.BASE + 'profile/:username',
        name: 'profile',
        component: Profile,
        props: true
    },
    {
        path: PINOOX.URL.BASE + 'post/:post_id/:title?',
        name: 'post',
        component: Post,
        props: true
    },
    {
        path: PINOOX.URL.BASE + 'search',
        name: 'search',
        component: Search,
    },
    {
        path: PINOOX.URL.BASE + 'tag/:tag_name',
        name: 'tag',
        component: Tag,
        props: true
    },
    {
        path: PINOOX.URL.BASE + 'contact',
        name: 'contact',
        component: Contact,
        meta: {
            hideWidgets: true
        }
    },
    {
        path: PINOOX.URL.BASE + 'error',
        name: 'error',
        component: ErrorPage,
    },
];