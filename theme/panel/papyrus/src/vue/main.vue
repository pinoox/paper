<template>
    <section class="app-container">
        <notifications group="app" classes="notification"
                       style="right:unset!important;top:unset!important;left:1%!important;bottom: 1%!important;">
            <template slot="body" slot-scope="props">
                <div class="notification" :class="props.item.type" @click="props.close">
                    <a class="title">
                        {{props.item.title}}
                    </a>
                    <div class="text" v-html="props.item.text"></div>
                </div>
            </template>
        </notifications>
        <div v-if="!hasCustomView">
            <div class="sidebar">
                <div class="brand">
                    <div class="title">PAPER</div>
                    <div class="subtitle">پنل مدیریت</div>
                </div>
                <div class="nav">
                    <router-link :to="{name:'dashboard'}" class="item" exact-active-class="active">
                        <simple-svg :src="icons.dashboard"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">داشبورد</span>
                    </router-link>
                    <router-link class="item" :to="{name:'articles'}" exact-active-class="active">
                        <simple-svg :src="icons.article"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">نوشته ها</span>
                    </router-link>
                    <router-link class="item" :to="{name:'stats'}" exact-active-class="active">
                        <simple-svg :src="icons.stats"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">آمار</span>
                    </router-link>
                </div>
                <div class="menu">
                    <div class="item">
                        <span class="text">پروفایل</span>
                    </div>
                    <div class="item">
                        <span class="text">کاربران</span>
                    </div>
                    <div class="item">
                        <span class="text">تنظیمات</span>
                    </div>
                </div>
            </div>
            <div class="main">
                <div class="toolbar">
                    <div class="account">
                        <img src="@img/sample-user.jpg" alt="profile">
                        <span class="text">رضا رضایی</span>
                    </div>
                    <div class="quick-actions">
                        <div class="item">
                            <simple-svg :src="icons.eye"
                                        width="25px"
                                        customClassName="icon"/>
                        </div>
                        <router-link tag="div" :to="{name:'write'}" class="item">
                            <simple-svg :src="icons.pen"
                                        width="22px"
                                        customClassName="icon"/>
                        </router-link>
                    </div>
                </div>

                <transition mode="out-in" enter-active-class="animated faster fadeIn"
                            leave-active-class="animated faster fadeOut">
                    <router-view></router-view>
                </transition>
            </div>
        </div>
        <div v-else>
            <router-view></router-view>
        </div>
    </section>
</template>

<script>
    import {mapMutations} from 'vuex';

    export default {
        data() {
            return {
                timestamp: null,
                route: {},
                numProcessing: 0,
                icons: {
                    dashboard: require(`@img/svg/ic_dashboard.svg`),
                    article: require(`@img/svg/ic_article.svg`),
                    stats: require(`@img/svg/ic_stats.svg`),
                    settings: require(`@img/svg/ic_settings.svg`),
                    users: require(`@img/svg/ic_users.svg`),
                    profile: require(`@img/svg/ic_profile.svg`),
                    eye: require(`@img/svg/ic_eye.svg`),
                    pen: require(`@img/svg/ic_pen_square.svg`),
                    delete: require(`@img/svg/ic_delete.svg`),
                    publish: require(`@img/svg/ic_publish.svg`),
                    seo: require(`@img/svg/ic_seo.svg`),
                    category: require(`@img/svg/ic_category.svg`),
                },
            }
        },
        computed: {
            hasCustomView() {
                return !!this.$route.meta.customView;
            }
        },
        methods: {
            ...mapMutations(['getUser']),
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
            tokenAuth() {
                let token = localStorage.pinoox_user;
                if (!!token) {
                    return `${token}`;
                }
                return null;
            },
            checkUser() {
                let token = this.tokenAuth();
                if ((!token || !this.isLogin) && (!this.$route.name || this.$route.name !== 'login')) {
                    this._routerReplace({name: 'login'});
                } else if (!!this.route.name && (this.route.name === 'login' || this.route.name === 'splash')) {
                    this._routerReplace({name: 'dashboard'});
                } else {
                    this._routerReplace(this.route);
                }
            },
            getTimeStamp(date = null) {
                return new Date(date).getTime();
            }
        },
        created() {
            this.timestamp = this.getTimeStamp();
            this.customInterceptors();
            this.getUser();
            this.route = this._clone({
                ...this.$route,
            });
            this._routerReplace({name: 'splash'});
        },
        watch: {
            USER() {
                if (!!this.$route.name && this.$route.name === 'splash') {
                    let time = this.getTimeStamp() - this.timestamp;
                    time = 0 - time;
                    if (time > 0) {
                        setTimeout(() => {
                            this.checkUser();
                        }, time);
                        return;
                    }
                }

                this.checkUser();
            }
        }
    }
</script>