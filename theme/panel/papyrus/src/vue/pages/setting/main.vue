<template>
    <div class="form-wrapper">
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
                        <div class="text">
                            {{LANG.panel.settings}}
                        </div>
                        <div class="subtext">
                            {{LANG.panel.subtitle_settings}}
                        </div>
                    </div>
                </div>
            </header>
            <simplebar class="simplebar">
                    <div class="form-content" @keyup.enter="save()">
                        <div class="menus setting">
                            <div class="item" @click="goTo('general')">
                                <i class="fa fa-cog"></i>
                                <span class="text">{{LANG.panel.general_settings}}</span>
                            </div>
                            <div class="item">
                                <i class="fa fa-user"></i>
                                <span class="text">{{LANG.panel.user_settings}}</span>
                            </div>
                            <div class="item">
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
    </div>
</template>

<script>
    export default {
        data() {
            return {
                params: {}
            }
        },
        methods: {
            goTo(name) {
                if (name === 'general')
                    this._routerReplace({name: 'setting-general'});
            }
        }
    }
</script>