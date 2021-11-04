import Dashboard from '../vue/pages/dashboard.vue';
import Posts from '../vue/pages/posts.vue';
import Pages from '../vue/pages/pages.vue';
import Splash from '../vue/pages/splash.vue';
import Login from '../vue/pages/login.vue';
import Write from '../vue/pages/write.vue';
import PostStats from '../vue/pages/post-stats.vue';
import Users from '../vue/pages/users.vue';
import Contacts from '../vue/pages/contacts.vue';
import Comments from '../vue/pages/comments.vue';
import Profile from '../vue/pages/profile.vue';
import Setting from '../vue/pages/setting/main.vue';
import SettingHome from '../vue/pages/setting/home.vue';
import SettingConfig from '../vue/pages/setting/config.vue';
import Template from '../vue/pages/template/main.vue';
import TemplateHome from '../vue/pages/template/home.vue';
import Category from '../vue/pages/category.vue';
import Group from '../vue/pages/group/group-home.vue';
import Permissions from '../vue/pages/permission/permission-home.vue';
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
        path: PINOOX.URL.BASE + '/pages/write/:post_id?',
        name: 'page-write',
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
        path: PINOOX.URL.BASE + '/groups',
        name: 'groups',
        component: Group,
    },
    {
        path: PINOOX.URL.BASE + '/permission/:group_key',
        name: 'permissions',
        component: Permissions,
        props: true,
    },
    {
        path: PINOOX.URL.BASE + '/contacts',
        name: 'contacts',
        component: Contacts
    },
    {
        path: PINOOX.URL.BASE + '/comments/:post_id?',
        name: 'comments',
        component: Comments,
        props: true,
    },
    {
        path: PINOOX.URL.BASE + '/profile',
        name: 'profile',
        component: Profile,
    },
    {
        path: PINOOX.URL.BASE + '/category',
        name: 'category',
        component: Category,
    },
    {
        path: PINOOX.URL.BASE + '/theme/:theme_name/setting',
        component: Setting,
        props: true,
        children: [
            {
                path: '',
                name: 'theme-setting',
                component: SettingHome,
            },
            {
                path: ':setting_key',
                name: 'theme-setting-config',
                component: SettingConfig,
                props: true,
            },
        ],
    },
    {
        path: PINOOX.URL.BASE + '/setting',
        component: Setting,
        children: [
            {
                path: '',
                name: 'setting',
                component: SettingHome,
            },
            {
                path: ':setting_key',
                name: 'setting-config',
                component: SettingConfig,
                props: true,
            },
        ],
    },
    {
        path: PINOOX.URL.BASE + '/templates',
        component: Template,
        children: [
            {
                path: '',
                name: 'template',
                component: TemplateHome,
            },
        ],
    },
    {
        path: PINOOX.URL.BASE + '/error',
        name: 'error',
        component: Error,
    },
    {
        path: PINOOX.URL.BASE + "*",
        component: Error,
    },
];