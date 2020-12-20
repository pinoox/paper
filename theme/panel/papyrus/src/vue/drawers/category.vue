<template>
    <section>

        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :blur="false"
                   :area="drawerArea"
                   :destroy-on-close="true"
                   @open="openDrawer()"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">{{LANG.post.category}}</div>
                    <div class="subtext">{{LANG.post.category_info}}</div>
                </div>
            </div>
            <div class="drawer-content">
                <category-nest v-if="drawerOpen"
                        :selected="$parent.category"
                        @onSelected="setCategory"
                        :post="$parent.post_id"
                        :is-edit="isEdit">
                </category-nest>
            </div>
            <div slot='footer' class="drawer-footer">
                    <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
                    <div v-if="!isEdit" @click="isEdit=!isEdit" class="btn btn-danger right">
                        {{LANG.post.edit_category}}
                    </div>
                    <div v-else @click="resetChanges()" class="btn btn-success right">
                        {{LANG.post.return_to_selection}}
                    </div>
            </div>

        </ch-drawer>

    </section>
</template>

<script>

    import CategoryNest from '../components/category-nest.vue';

    export default {
        components:{CategoryNest},
        props: ['open', 'selected'],
        data() {
            return {
                isEdit: false,
                drawerPosition: 'bottom',
                drawerArea: '90%',
            }
        },
        computed: {
            drawerOpen: {
                get() {
                    return this.open;
                },
                set(val) {
                    this.$emit('onClose', val);
                }
            }
        },
        methods: {
            setCategory(val) {
                this.$parent.category = val;
                this.toggleDrawer();
            },
            toggleDrawer() {
                this.drawerOpen = !this.drawerOpen;
            },
            handleBeforeClose(next) {
                this.toggleDrawer();
                next();
            },
            openDrawer() {
                this._resetInitialData();
            },
            resetChanges() {
                this.isEdit = false;
            },
        },

    }
</script>
