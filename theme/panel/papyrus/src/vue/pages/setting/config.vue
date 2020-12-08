<template>
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

                <column :sm="4" :lg="3">
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

                        <!-- select post view -->
                        <div v-else-if="!!setting.type && setting.type === 'select:post'">
                            <select-post v-model="params[setting.key]" v-bind="getAttrs(setting)"></select-post>
                        </div>

                        <!-- color picker view -->
                        <div v-else-if="!!setting.type && setting.type === 'color-picker'">
                            <color-picker class="input" v-model="params[setting.key]" :color="params[setting.key]" v-bind="getAttrs(setting)"></color-picker>
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
                <router-link :to="{name:!$parent.isTheme?'setting' : 'theme-setting'}" class="btn btn-simple">
                    {{LANG.post.close}}
                </router-link>
                <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
            </div>
        </footer>
    </simplebar>
</template>

<script>
    import {mapMutations} from 'vuex';
    import SelectPost from "../../components/select-post.vue";
    import ColorPicker from "../../components/color-picker.vue";

    export default {
        components: {SelectPost,ColorPicker},
        props: ['setting_key'],
        data() {
            return {
                params: {},
            }
        },
        computed: {
            view() {
                return this.$parent.views.find(view => view.key === this.setting_key);
            },
            settings() {
                return !!this.view && !!this.view.settings ? this.view.settings : [];
            }
        },
        methods: {
            ...mapMutations(['updateDirections']),
            select(option) {
                this.params[option.setting_key] = option.key;
            },
            option(setting) {
                let value = this.params[setting.key];
                if(setting.type === 'select:post')
                {
                    return  typeof value === 'object'? value : [];
                }
                else
                {
                    let options = this.options(setting);
                    return options.find(option => option.key === value);
                }

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
                if (!this.$parent.isTheme) {
                    let params = this.CONFIG[key] ? this.CONFIG[key] : [];
                    this.params = this._clone(params);
                } else {
                    this.$parent.http.get('get/' + key).then((json) => {
                        this.params = json.data;
                    });
                }
            },
            changeLang(lang) {
                this.$parent.http.get('changeLang/' + lang).then((json) => {
                    this.LANG = json.data.lang;
                    this.updateDirections(json.data.direction);
                    this.currentLang = lang;
                    this.countTranslate++;
                    this.$parent.getViews(lang);
                });
            },
            createParams()
            {
                let params = this._clone(this.params);
                for(const item of this.settings)
                {
                    let value =  params[item.key];

                    if(!!item.type && item.type === 'select:post')
                        params[item.key] = this.getParamPost(value);
                    else
                        params[item.key] =  value;
                }

                return params;
            },
            getParamPost(value)
            {
                let values = value.filter((item)=>{
                    return !!item.post_id;
                }).map((item)=>{
                    return item.post_id;
                });

                return {
                    type:'select:post',
                    values:values,
                };
            },
            save() {
                let params = this.createParams();
                let key = this.view.key;
                let lang = this.params.lang;
                this.$parent.http.post('save/' + key, params).then((json) => {
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
            }
        }
    }
</script>