<template>
    <div class="app">
        <div class="blur-loading animate__animated animate__fadeIn animate__faster" v-show="_isLoading">
            <div class="spinner"></div>
        </div>
        <notifications group="app" classes="notification">
            <template slot="body" slot-scope="props">
                <div class="notification" :class="props.item.type" @click="props.close">
                    <a class="title">
                        {{props.item.title}}
                    </a>
                    <div class="text" v-html="props.item.text"></div>
                </div>
            </template>
        </notifications>
        <section class="app-container">
            <div v-if="!hasCustomView">
                <sidebar></sidebar>
                <div class="main">
                    <div v-if="showToolbar" class="toolbar">
                        <router-link tag="div" :to="{name:'profile'}" class="account">
                            <img :src="USER.avatar_thumb" alt="profile">
                            <span class="text">{{USER.full_name}}</span>
                        </router-link>
                        <div class="quick-actions">
                            <div class="item" @click="showFront()">
                                <simple-svg :src="_icons.eye"
                                            width="25px"
                                            customClassName="icon"/>
                            </div>
                        </div>
                    </div>
                    <div class="toolbar-drawer">
                        <div class="items">
                            <router-link :to="{name:'dashboard'}" class="item" exact-active-class="active">
                                <simple-svg :src="_icons.dashboard"
                                            customClassName="icon"
                                            fill="#A5B8CE"/>
                                <span class="text">{{LANG.panel.dashboard}}</span>
                            </router-link>
                            <router-link class="item" :to="{name:'write'}" exact-active-class="active">
                                <simple-svg :src="_icons.pen"
                                            width="22px"
                                            customClassName="stroke"/>
                                <span class="text">{{LANG.post.write}}</span>
                            </router-link>
                            <router-link class="item" :to="{name:'posts'}" exact-active-class="active">
                                <simple-svg :src="_icons.article"
                                            customClassName="icon"
                                            fill="#A5B8CE"/>
                                <span class="text">{{LANG.panel.posts}}</span>
                            </router-link>
                            <div class="item" @click="openMenu=true">
                                <simple-svg :src="_icons.more"
                                            customClassName="icon"
                                            fill="#A5B8CE"/>
                                <span class="text">{{LANG.panel.more}}</span>
                            </div>
                        </div>
                    </div>
                    <transition name="fade" mode="out-in"
                                :enter-active-class="isTransition?'animate__animated animate__fadeInUp animate__faster':''"
                                :leave-active-class="isTransition?'animate__animated animate__fadeOutDown animate__faster':''">
                        <router-view :key="$route.fullPath"></router-view>
                    </transition>
                </div>
            </div>
            <div v-else>
                <router-view></router-view>
            </div>

            <Menu :open="openMenu" @onClose="openMenu=false"></Menu>
        </section>
    </div>
</template>

<script>
    import Menu from "./drawers/menu.vue";
    import Sidebar from "./components/sidebar.vue";

    export default {
        components: {Menu, Sidebar},
        data() {
            return {
                timestamp: null,
                route: {},
                numProcessing: 0,
                openMenu: false,
            }
        },
        computed: {
            hasCustomView() {
                return !!this.$route.meta.customView;
            },
            showToolbar() {
                return !!this.$route.meta.showToolbar;
            }
        },
        methods: {
            customInterceptors() {
                this.numProcessing = 0;
                this.$http.interceptors.request.use((request) => {

                    let isLoading = true;
                    if (request.params !== undefined && request.params.isLoading !== undefined) {
                        isLoading = request.params.isLoading;
                    }
                    if (isLoading) {
                        this.numProcessing++;
                        this._isLoading = true;
                    }
                    request.headers.Authorization = this.tokenAuth();
                    request.isLoading = isLoading;

                    return request;
                });

                this.$http.interceptors.response.use((response) => {
                    if (response.config.isLoading) {
                        this.numProcessing--;
                        let isStop = (this.numProcessing === 0);
                        if (isStop) {
                            this._isLoading = false;
                        }
                    }
                    return response;
                });
            },
            checkUser() {
                let token = this.tokenAuth();
                if ((!token || !this.isLogin()) && (!this.$route.name || this.$route.name !== 'login')) {
                    this._routerReplace({name: 'login'});
                } else if (!!this.route.name && (this.route.name === 'login' || this.route.name === 'splash')) {
                    this._routerReplace({name: 'dashboard'});
                } else {
                    this._routerReplace(this.route);
                }
            },
            getTimeStamp(date = null) {
                return new Date(date).getTime();
            },
            showFront() {
                window.open(PINOOX.URL.FRONT, '_blank');
            }
        },
        created() {
            this.timestamp = this.getTimeStamp();
            this.customInterceptors();
            this.getInitUser();
            this.route = this._clone({
                ...this.$route,
            });
            this._routerReplace({name: 'splash'});
        },
        watch: {
            USER() {
                if (!!this.$route.name && this.$route.name === 'splash') {
                    let time = this.getTimeStamp() - this.timestamp;
                    time = 3000 - time;
                    if (time > 0) {
                        setTimeout(() => {
                            this.checkUser();
                        }, time);
                        return;
                    }
                }
                this.checkUser();
            },
            // '$route': {
            //     handler(to, from) {
            //
            //         let token = localStorage.paper_user;
            //         let checkLogin = this.$store.state.checkLogin;
            //         console.log('checkLogin: ',checkLogin);
            //         console.log('token: ',!token);
            //         console.log('isLogin: ',!this.isLogin());
            //         console.log('login: ',(!to.name || (to.name !== 'login')));
            //         if (checkLogin && !token && !this.isLogin() && (!to.name || (to.name !== 'login'))) {
            //             this.exitUser();
            //         }
            //     },
            //     deep: true,
            //     immediate: true,
            // }
        }
    }
</script>