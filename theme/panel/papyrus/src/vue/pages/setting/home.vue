<template>
    <div class="page p-0">
        <div v-if="false" class="menubar">
            <div class="items">
                <router-link exact-active-class="active" :to="{name:'setting'}" tag="div" class="item">
                    {{LANG.post.write}}
                </router-link>
                <router-link exact-active-class="active" :to="{name:'write'}" tag="div" class="item">
                    {{LANG.post.write}}
                </router-link>
            </div>
        </div>
        <header>
            <div class="form-header">
                <div class="title">
                    <div class="text" v-if="!$parent.isTheme">
                        {{LANG.panel.settings}}
                    </div>
                    <div class="text" v-else>
                        {{LANG.panel.theme_settings}} {{$parent.theme_name}}
                    </div>
                    <div class="subtext">
                        {{LANG.panel.subtitle_settings}}
                    </div>
                </div>
            </div>
        </header>
        <simplebar class="simplebar">
            <div class="form-content">
                <div class="menus setting">
                    <router-link tag="div" class="item" :to="{name:!$parent.isTheme? 'setting-config' : 'theme-setting-config',params:{setting_key:view.key}}" v-for="view in menus">
                        <i :class="view.icon"></i>
                        <span class="text">{{view.label}}</span>
                    </router-link>
                    <div class="item" @click="goTo('general')">
                        <i class="fa fa-cog"></i>
                        <span class="text">{{LANG.panel.general_settings}}</span>
                    </div>
                    <div class="item">
                        <i class="fa fa-user"></i>
                        <span class="text">{{LANG.panel.user_settings}}</span>
                    </div>
                    <div class="item" @click="goTo('write')">
                        <i class="fa fa-keyboard"></i>
                        <span class="text">{{LANG.panel.write_settings}}</span>
                    </div>
                    <div class="item">
                        <i class="fa fa-book-open"></i>
                        <span class="text">{{LANG.panel.read_settings}}</span>
                    </div>
                    <div class="item">
                        <i class="fa fa-mountain"></i>
                        <span class="text">{{LANG.panel.theme_settings}}</span>
                    </div>
                    <div class="item">
                        <i class="fa fa-language"></i>
                        <span class="text">{{LANG.panel.lang_settings}}</span>
                    </div>
                    <div class="item">
                        <i class="fa fa-quidditch"></i>
                        <span class="text">{{LANG.panel.interface_settings}}</span>
                    </div>
                    <div class="item">
                        <i class="fa fa-user-secret"></i>
                        <span class="text">{{LANG.panel.privacy_settings}}</span>
                    </div>
                </div>
            </div>
        </simplebar>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                params: {}
            }
        },
        computed:{
            menus(){
                return this.$parent.views.sort(function(a, b) {
                    let sortA = !!a.sort? a.sort : 0;
                    let sortB = !!b.sort? b.sort : 0;
                    return sortA - sortB ;
                });
            }
        },
        methods: {
            goTo(name) {
                this._routerReplace({name: 'setting-' + name});
            }
        }
    }
</script>