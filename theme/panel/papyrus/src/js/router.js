import Vue from "vue";
import VueRouter from "vue-router";
import {routes} from "./routes";

Vue.use(VueRouter);

// router
const router = new VueRouter({
    mode: 'history',
    routes: routes,
    scrollBehavior(to, from, savedPosition) {
        return {x: 0, y: 0}
    },
});

router.beforeEach((to, from, next) => {
    let token = localStorage.getItem('pinoox_user');
    if (to.meta.requireAuth !== undefined) {
        if (to.meta.requireAuth) {
            if (token == null) {
                next({name: 'home'});
            } else {
                next();
            }
        } else {
            if (token == null) {
                next();
            } else {
                next({name: 'profile'});
            }
        }

    } else {
        next();
    }
});

export default router;