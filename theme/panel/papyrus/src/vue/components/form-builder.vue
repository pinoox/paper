<template>
    <div>
        <div class="input-wrapper" v-for="setting in settings" v-show="hidden(setting)">
            <label class="input-label">{{setting.label}}</label>

            <!-- textarea view -->
            <div class="input-group" v-if="!!setting.type && setting.type === 'textarea'">
                                <textarea v-bind="getAttrs(setting)" class="input"
                                          v-model="value[setting.key]"></textarea>
            </div>

            <!-- toggle view -->
            <div class="input-group" v-else-if="!!setting.type && setting.type === 'toggle'">
                <toggle-button v-bind="getAttrs(setting)"
                               v-model="value[setting.key]"
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
            <!-- list view -->
            <div v-else-if="!listDisable && !!setting.type && setting.type === 'list'">
            <span @click="_parent.openList(setting)" class="btn btn-list"> {{LANG.panel.list_builder}} <i
                    class="fa fa-cog"></i></span>
            </div>

            <!-- select post view -->
            <div v-else-if="!!setting.type && setting.type === 'select:post'">
                <select-post v-model="value[setting.key]" v-bind="getAttrs(setting)"></select-post>
            </div>

            <!-- color picker view -->
            <div v-else-if="!!setting.type && setting.type === 'color-picker'">
                <color-picker class="input" v-model="value[setting.key]" :color="value[setting.key]"
                              v-bind="getAttrs(setting)"></color-picker>
            </div>

            <!-- input view -->
            <div class="input-group" v-else>
                <input v-bind="getAttrs(setting)" v-model="value[setting.key]"
                       :type="!!setting.type?setting.type : 'text'" class="input">
            </div>

            <span v-if="!!setting.help" class="sub-label">{{setting.help}}</span>
        </div>
    </div>
</template>

<script>
    import SelectPost from "./select-post.vue";
    import ColorPicker from "./color-picker.vue";

    export default {
        name: "form-builder",
        components: {SelectPost, ColorPicker},
        props: {
            settings: {
                default: [],
            },
            value: {
                default: {},
            },
            parent: {
                default: null,
            },
            listDisable: {
                default: false,
            },

        },
        created() {
            this._parent = !!this.parent ? this.parent : this.$parent;
        },
        data() {
            return {
                _parent: null,
            }
        },
        methods: {
            select(option) {
                this.value[option.setting_key] = option.key;
            },
            option(setting) {
                let value = this.value[setting.key];
                if (setting.type === 'select:post') {
                    return typeof value === 'object' ? value : [];
                } else {
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
                    return this.value[key];
                }

                return true;
            },
            getAttrs(setting) {
                let attrs = !!setting.attrs ? setting.attrs : {};
                if (!attrs.placeholder)
                    attrs.placeholder = setting.label;
                return attrs;
            },
        },
    }
</script>