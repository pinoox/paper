<template>
   <div class="app">
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
               <div class="sidebar mode-write">
                   <div class="brand">
                       <div class="title">PAPER</div>
                       <div class="subtitle">پنل مدیریت</div>
                   </div>
                   <div class="nav">
                       <router-link :to="{name:'dashboard'}" class="item" exact-active-class="active">
                           <simple-svg :src="_icons.dashboard"
                                       customClassName="icon"
                                       fill="#A5B8CE"/>
                           <span class="text">داشبورد</span>
                       </router-link>
                       <router-link class="item" :to="{name:'write'}" exact-active-class="active">
                           <simple-svg :src="_icons.pen"
                                       customClassName="icon stroke"/>
                           <span class="text">نوشتن</span>
                       </router-link>
                       <router-link class="item" :to="{name:'posts'}" exact-active-class="active">
                           <simple-svg :src="_icons.article"
                                       customClassName="icon"/>
                           <span class="text">نوشته ها</span>
                       </router-link>
                   </div>
                   <div class="menu">
                       <div class="item">
                           <span class="text">پروفایل</span>
                       </div>
                       <router-link :to="{name:'users'}" tag="div" class="item">
                           <span class="text">کاربران</span>
                       </router-link>
                       <div class="item">
                           <span class="text">تنظیمات</span>
                       </div>
                   </div>
               </div>
               <div class="main">
                   <div v-if="showToolbar" class="toolbar">
                       <div class="account">
                           <img src="@img/sample-user.jpg" alt="profile">
                           <span class="text">رضا رضایی</span>
                       </div>
                       <div class="quick-actions">
                           <div class="item">
                               <simple-svg :src="_icons.eye"
                                           width="25px"
                                           customClassName="icon"/>
                           </div>
                           <router-link tag="div" :to="{name:'write'}" class="item">
                               <simple-svg :src="_icons.pen"
                                           width="22px"
                                           customClassName="icon"/>
                           </router-link>
                       </div>
                   </div>

                   <div class="toolbar-drawer">
                       <div class="items">
                           <router-link :to="{name:'dashboard'}" class="item" exact-active-class="active">
                               <simple-svg :src="_icons.dashboard"
                                           customClassName="icon"
                                           fill="#A5B8CE"/>
                               <span class="text">داشبورد</span>
                           </router-link>
                           <router-link class="item" :to="{name:'write'}" exact-active-class="active">
                               <simple-svg :src="_icons.pen"
                                           width="22px"
                                           customClassName="stroke"/>
                               <span class="text">نوشتن</span>
                           </router-link>
                           <router-link class="item" :to="{name:'stats'}" exact-active-class="active">
                               <simple-svg :src="_icons.stats"
                                           customClassName="icon"
                                           fill="#A5B8CE"/>
                               <span class="text">آمار</span>
                           </router-link>
                           <router-link class="item" :to="{name:'splash'}" exact-active-class="active">
                               <simple-svg :src="_icons.more"
                                           customClassName="icon"
                                           fill="#A5B8CE"/>
                               <span class="text">بیشتر</span>
                           </router-link>
                       </div>
                   </div>

                   <transition name="fade" mode="out-in"
                               enter-active-class="animate__animated animate__fadeInUp animate__faster"
                               leave-active-class="animate__animated animate__fadeOutDown animate__faster">
                       <router-view :key="$route.fullPath"></router-view>
                   </transition>
               </div>
           </div>
           <div v-else>
               <router-view></router-view>
           </div>
       </section>
   </div>
</template>

<script>
    import {mapMutations} from 'vuex';

    export default {
        data() {
            return {
                defaultTableOpts: {
                    enabled: true,
                    mode: 'records',
                    perPage: 10,
                    perPageDropdown: [5, 10, 20, 50],
                    nextLabel: PINOOX.LANG.panel.next,
                    prevLabel: PINOOX.LANG.panel.prev,
                    rowsPerPageLabel: PINOOX.LANG.panel.rows_per_pages,
                    ofLabel: PINOOX.LANG.panel.of,
                    pageLabel: PINOOX.LANG.panel.page, // for 'pages' mode
                    allLabel: PINOOX.LANG.panel.all,
                },
                timestamp: null,
                route: {},
                numProcessing: 0,
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
            },
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
            },
        }
    }
</script>