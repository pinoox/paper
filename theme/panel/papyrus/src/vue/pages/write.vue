<template>
    <section class="page">
        <div class="write-container">
            <div class="toolbox">
                <div class="items">
                    <div class="item">
                        ذخیره
                    </div>
                    <div @click="openDrawer('publish')" class="item">
                        انتشار
                    </div>
                    <div class="item">
                        سئو
                    </div>
                    <div class="item" @click="openDrawer('category')">
                        دسته بندی
                    </div>
                </div>
            </div>
            <editor class="content"
                    :values="editor"
                    v-model="params"
                    name="description"
                    :title-placeholder="LANG.panel.enter_title_post"
                    :placeholder="LANG.panel.enter_context_post">
            </editor>
        </div>
        <publish @onClose="drawerName=null" :open="drawerName==='publish'"></publish>
        <category @onClose="drawerName=null" :open="drawerName==='category'"></category>
    </section>
</template>

<script>

    import Editor from "../components/editor.vue";
    import Publish from "../drawers/pubish.vue";
    import Category from "../drawers/category.vue";

    export default {
        name: 'write',
        components: {Editor,Category,Publish},
        beforeRouteLeave(to, from, next) {
            this._confirm('تست می شود', () => {
                next();
            });
        },
        data() {
            return {
                editor: {
                    title: null,
                    context: null,
                },
                params: {},

                drawerName: false,
                stats:{
                    word:0,
                    charecter:0,
                }
            };
        },
        methods: {
            openToolbox() {
                this.editor.title = this.params.title;
                this.drawerVisibility = true;
            },
            handleBeforeClose(next) {
                next();
            },
            openDrawer(drawerName) {
                this.drawerName = drawerName;
            },

        }
    }
</script>
