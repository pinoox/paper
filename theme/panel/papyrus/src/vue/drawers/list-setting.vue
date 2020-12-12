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
                <list-items :setting="$parent.setting" v-model="$parent.params[$parent.setting.key]" v-bind="$parent.getAttrs($parent.setting)"></list-items>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
            </div>

        </ch-drawer>

    </section>
</template>

<script>
    import ListItems from "../components/list-items.vue";
    export default {
        components: {ListItems},
        props: ['open'],
        data() {
            return {

            }
        },
        methods: {
            openDrawer() {
                this._resetInitialData();
            },
            closeDrawer() {
                this.$emit('close', false);
            },
        },

    }
</script>
