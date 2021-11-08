<template>
    <div>
        <div class="sidebar">
            <div class="sidebar-content">
                <div class="brand">
                    <div class="title">PAPER</div>
                    <div class="subtitle"></div>
                </div>
                <div class="nav">

                    <router-link v-if="_module('panel/dashboard')" :to="{name:'dashboard'}" class="item" exact-active-class="active">
                        <simple-svg :src="_icons.dashboard"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.dashboard}}</span>
                    </router-link>
                    <router-link v-if="_module('panel/write')" class="item" :to="{name:'write'}" exact-active-class="active">
                        <simple-svg :src="_icons.pen"
                                    customClassName="icon stroke"/>
                        <span class="text">{{LANG.post.write}}</span>
                    </router-link>
                    <router-link v-if="_module('panel/posts')" class="item" :to="{name:'posts'}" exact-active-class="active">
                        <simple-svg :src="_icons.article"
                                    customClassName="icon"/>
                        <span class="text">{{LANG.post.posts}}</span>
                    </router-link>
                    <div class="item" @click="openMenu()">
                        <simple-svg :src="_icons.more_square"
                                    customClassName="icon stroke"/>
                        <span class="text">{{LANG.panel.menus}}</span>
                    </div>
                </div>
                <div class="menu">
                    <router-link v-if="_module('panel/profile')" :to="{name:'profile'}" class="item">
                        <span class="text">{{LANG.panel.profile}}</span>
                    </router-link>
                    <router-link v-if="_module('panel/setting')" :to="{name:'setting'}" class="item">
                        <span class="text">{{LANG.panel.settings}}</span>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            openMenu() {
                this.$parent.openMenu = true;
            }
        }
    }
</script>