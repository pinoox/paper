<template>
    <div>
        <simplebar class="form-wrapper" v-if="!!view">
            <header>
                <div class="form-header">
                    <div class="title">
                        <div class="text">
                            {{view.label}}
                        </div>
                    </div>
                </div>
            </header>
            <div class="form-content" @keyup.enter="save()" v-if="!!settings && settings.length >= 0">
                <row :gutter="12" :columns="4">

                    <column :sm="4" :lg="3" v-if="isLoadConfig">
                        <form-builder :parent="parent" :settings="settings" v-model="params"></form-builder>
                    </column>
                </row>
            </div>
            <div class="footer-space"></div>
            <footer>
                <div class="form-footer">
                    <router-link :to="{name:!$parent.isTheme?'setting' : 'theme-setting'}" class="btn btn-simple">
                        {{LANG.post.close}}
                    </router-link>
                    <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
                </div>
            </footer>
        </simplebar>
        <list-setting @close="listDrawer = false" :open="listDrawer"></list-setting>
    </div>
</template>

<script>
    import {mapMutations} from 'vuex';
    import ListSetting from "../../drawers/list-setting.vue";
    import FormBuilder from "../../components/form-builder.vue";

    export default {
        components: {FormBuilder, ListSetting},
        props: ['setting_key'],
        data() {
            return {
                params: {},
                setting: {},
                paramsImage:{},
                listDrawer: false,
                isLoadConfig:false,
            }
        },
        computed: {
            parent(){
              return this;
            },
            view() {
                return this.$parent.views.find(view => view.key === this.setting_key);
            },
            settings() {
                return !!this.view && !!this.view.settings ? this.view.settings : [];
            }
        },
        methods: {
            ...mapMutations(['updateDirections']),
            openListDrawer(setting) {
                this.setting = setting;
                this.listDrawer = true;
            },
            getSettings() {
                let key = this.view.key;
                if (!this.$parent.isTheme) {
                    let params = this.CONFIG[key] ? this.CONFIG[key] : [];
                    this.params = this._clone(params);
                    this.isLoadConfig = true;
                } else {
                    this.$http.get(this.URL.API+'setting/get/' + key,this.$parent.addThemeToHeader).then((json) => {
                        this.params = json.data;
                        this.isLoadConfig = true;
                    });
                }
            },
            changeLang(lang) {
                this.$http.get(this.URL.API+'setting/changeLang/' + lang,this.$parent.addThemeToHeader).then((json) => {
                    this.LANG = json.data.lang;
                    this.updateDirections(json.data.direction);
                    this.currentLang = lang;
                    this.countTranslate++;
                    this.$parent.getViews(lang);
                });
            },
            createParams() {
                return this._clone(this.params);
            },
            getAttrs(setting) {
                let attrs = !!setting.attrs ? setting.attrs : {};
                if (!attrs.placeholder)
                    attrs.placeholder = setting.label;
                return attrs;
            },
            save() {
                let params = this.createParams();
                let key = this.view.key;
                let lang = this.params.lang;
                this.$http.post(this.URL.API+'setting/save/' + key, params,this.$parent.addThemeToHeader).then((json) => {
                    this._statusResponse(json.data);
                    if (!this.$parent.isTheme) {
                        this.CONFIG[key] = this._clone(this.params);
                        if (key === 'lang')
                            this.changeLang(lang);
                    }
                });
            }
        },
        watch: {
            view: {
                handler(val) {
                    if (!!val) {
                        this.getSettings();
                    }
                },
                immediate: true,
            },
            drawerName() {
                $('.app-container').toggleClass('drawer--blur');
                $('body').toggleClass('toggle-over-flow');
            },
        }
    }
</script>