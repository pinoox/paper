<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   location="bottom"
                   :visible="open"
                   :destroy-on-close="true"
                   :blur="false"
                   area="90%"
                   @open="openDrawer()"
                   @close="closeDrawer()">
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">{{$parent.setting.label}}</div>
                </div>
            </div>
            <div class="drawer-content" v-if="open">
                <div class="form-content">
                    <form-builder :list-disable="true" :settings="settings"
                                  v-model="params"></form-builder>
                </div>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
            </div>
        </ch-drawer>

    </section>
</template>

<script>
    import FormBuilder from "../components/form-builder.vue";
    export default {
        components: {FormBuilder},
        props: ['open'],
        data() {
            return {
                params:{},
            }
        },
        methods: {
            openDrawer() {
                this._resetInitialData();
                this.createParamsByDefaultValue();
            },
            closeDrawer() {
                this.$parent.params[this.$parent.setting.key] = {
                    ...this.params,
                };
                this.$emit('close', false);
            },
            createParamsByDefaultValue() {
                let data = this.$parent.params[this.$parent.setting.key];
                if(!!data)
                    this.params = this._clone(data);
            },
        },
        computed: {
            settings() {
                return !!this.$parent.setting && !!this.$parent.setting.settings ? this.$parent.setting.settings : [];
            },
        },

    }
</script>
