<template>
    <div class="page p-0">
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
                    <router-link tag="div" class="item"
                                 :to="{name:!$parent.isTheme? 'setting-config' : 'theme-setting-config',params:{setting_key:view.key}}"
                                 v-for="view in menus">
                        <i :class="view.icon"></i>
                        <span class="text">{{view.label}}</span>
                    </router-link>
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
        computed: {
            menus() {
                return this.$parent.views.sort(function (a, b) {
                    let sortA = !!a.sort ? a.sort : 0;
                    let sortB = !!b.sort ? b.sort : 0;
                    return sortA - sortB;
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