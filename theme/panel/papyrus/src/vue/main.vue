<template>
    <section class="app-container">
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
                    <div class="icon">
                        <simple-svg :src="icons.profile"
                                    customClassName="svg"
                                    fill="#A5B8CE"/>
                    </div>
                    <span class="text">پروفایل</span>
                </div>
                <div class="item">
                    <div class="icon">
                        <simple-svg :src="icons.users"
                                    customClassName="svg"
                                    fill="#A5B8CE"/>
                    </div>
                    <span class="text">کاربران</span>
                </div>
                <div class="item active">
                    <div class="icon">
                        <simple-svg :src="icons.settings"
                                    customClassName="svg"
                                    fill="#A5B8CE"/>
                    </div>
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
                    <div class="item">
                        <simple-svg :src="icons.pen"
                                    width="22px"
                                    customClassName="icon"/>
                    </div>
                </div>
            </div>

            <transition mode="out-in" enter-active-class="animated faster fadeIn"
                        leave-active-class="animated faster fadeOut">
                <router-view></router-view>
            </transition>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
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
                },
            }
        },
        created() {
            //this.$router.replace({name: 'splash'});
        }
    }
</script>