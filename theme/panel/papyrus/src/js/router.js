import Vue from "vue";
import VueRouter from "vue-router";
import {routes} from "./routes";
import Store from './store';

Vue.use(VueRouter);

// router
const router = new VueRouter({
    mode: 'history',
    routes: routes,
    scrollBehavior(to, from, savedPosition) {
        return {x: 0, y: 0}
    },
});

// router.beforeEach((to, from, next) => {
//     let token = localStorage.paper_user;
//     if (!token && to.name !== 'login') {
//         next({'name': 'login'});
//     } else {
//         next()
//     }
// });

// router.beforeEach((to, from, next) => {
//     let checkLogin = Store.state.checkLogin;
//     let user = Store.state.user;
//     let isLogin = !!user && !!user.isLogin;
//     let token = localStorage.paper_user;
//
//     if (checkLogin && !token && !isLogin && (!to.name || (to.name !== 'login'))) {
//         next({name: 'login'});
//     } else if (!to.name) {
//         next({name: 'error'});
//     } else {
//         next();
//     }
// });

export default router;