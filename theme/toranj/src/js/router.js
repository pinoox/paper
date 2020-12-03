import Vue from "vue";
import VueRouter from "vue-router";
import {routes} from "./routes";

Vue.use(VueRouter);

// router
const router = new VueRouter({
    mode: 'history',
    routes: routes,
  /*  scrollBehavior(to, from, savedPosition) {
        return {x: 0, y: 0}
    },*/
});


export default router;