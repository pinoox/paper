<template>
    <div class="form-wrapper" v-if="!!view">
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

                <column :sm="3" :lg="2">
                    <div class="input-wrapper" v-for="setting in settings" v-show="hidden(setting)">
                        <label class="input-label">{{setting.label}}</label>

                        <!-- textarea view -->
                        <div class="input-group" v-if="!!setting.type && setting.type === 'textarea'">
                            <textarea v-bind="getAttrs(setting)" class="input" v-model="params[setting.key]"></textarea>
                        </div>

                        <!-- toggle view -->
                        <div class="input-group" v-else-if="!!setting.type && setting.type === 'toggle'">
                            <toggle-button v-bind="getAttrs(setting)"
                                           v-model="params[setting.key]"
                                           :sync="true"
                                           :width="70"
                                           :labels="{checked: LANG.post.active, unchecked: LANG.post.inactive}"/>
                        </div>

                        <!-- select view -->
                        <div v-else-if="!!setting.type && setting.type === 'select'">
                            <v-select
                                    class="input"
                                    dir="rtl"
                                    index="label"
                                    label="label"
                                    :options="options(setting)"
                                    :value="option(setting)"
                                    @input="select"
                                    v-bind="getAttrs(setting)">
                                <template slot="no-options">
                                    {{LANG.panel.nothing_found}}
                                </template>
                            </v-select>
                        </div>

                        <!-- input view -->
                        <div class="input-group" v-else>
                            <input v-bind="getAttrs(setting)" v-model="params[setting.key]"
                                   :type="!!setting.type?setting.type : 'text'" class="input">
                        </div>

                        <span v-if="!!setting.help" class="sub-label">{{setting.help}}</span>
                    </div>
                </column>
            </row>
        </div>
        <div class="footer-space"></div>
        <footer>
            <div class="form-footer">
                <router-link :to="{name:'setting'}" class="btn btn-simple">{{LANG.post.close}}</router-link>
                <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
            </div>
        </footer>
    </div>
</template>

<script>
    export default {
        props: ['setting_key'],
        data() {
            return {
                params: {}
            }
        },
        computed: {
            view() {
                return this.viewSettings.find(view => view.key === this.setting_key);
            },
            settings() {
                return !!this.view && !!this.view.settings ? this.view.settings : [];
            }
        },
        methods: {
            select(option) {
                this.params[option.setting_key] = option.key;
            },
            option(setting) {
                let key = this.params[setting.key];
                let options = this.options(setting);
                return options.find(option => option.key === key);
            },
            options(setting) {
                return $.map(setting.options, function (value, index) {
                    return {
                        key: index,
                        label: value,
                        setting_key: setting.key,
                    };
                });
            },
            hidden(setting) {
                if (!!setting.hiddenBy) {
                    let key = setting.hiddenBy;
                    return this.params[key];
                }

                return true;
            },
            getAttrs(setting) {
                let attrs = !!setting.attrs ? setting.attrs : {};
                if (!attrs.placeholder)
                    attrs.placeholder = setting.label;
                return attrs;
            },
            getSettings() {
                let key = this.view.key;
                let params = this.CONFIG[key] ? this.CONFIG[key] : [];
                this.params = this._clone(params);
            },
            changeLang(lang) {
                this.$http.get(this.URL.API + 'setting/getLang/' + lang).then((json) => {
                    this.currentLang = lang;
                    this.LANG = json.data;
                    PINOOX.LANG = json.data;
                    this.countTranslate++;
                    this.$parent.getViews(lang);
                    this.$forceUpdate();
                    //location.reload();
                });
            },
            save() {
                let key = this.view.key;
                let lang = this.params.lang;
                this.$http.post(this.URL.API + 'setting/save/' + key, this.params).then((json) => {
                    this.CONFIG[key] = this._clone(this.params);
                    PINOOX.CONFIG[key] = this._clone(this.params);
                    this._statusResponse(json.data);
                    if (key === 'general')
                        this.changeLang(lang);
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
            }
        }
    }
</script>