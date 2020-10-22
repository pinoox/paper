<template>
    <section>

        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">دسته بندی</div>
                    <div class="subtext">نوشته های خود را دسته بندی کنید</div>
                </div>
            </div>
            <div class="drawer-content">

                <row :gutter="12" :columns="1" v-if="isAdd" class="add-category">
                    <column :sm="1" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.title}}</label>
                            <div class="input-group">
                                <input v-model="params.cat_name" name="name" type="text"
                                       :placeholder="LANG.post.enter_title" class="input">
                            </div>
                        </div>
                    </column>
                </row>

                <div v-else>

                    <div class="header-actions">
                        <div @click="isAdd=true" class="btn btn-outline-primary btn-sm btn-add">
                            {{LANG.post.add_category}}
                        </div>
                        <div v-if="!isEdit" @click="isEdit=!isEdit" class="btn btn-outline-primary btn-sm btn-edit">
                            {{LANG.post.edit_category}}
                        </div>
                        <div v-else @click="resetChanges()" class="btn btn-outline-primary btn-sm btn-edit">
                            {{LANG.post.cancel_edit}}
                        </div>
                    </div>

                    <vue-nestable
                            class="nestable nestable-category"
                            :class="{ 'editable': isEdit}"
                            v-model="categories"
                            @change="trigger"
                            :rtl="true"
                            keyProp="cat_id"
                            :maxDepth="8">
                        <vue-nestable-handle
                                :draggable="isEdit"
                                slot-scope="{ item }"
                                :item="item">
                            <span class="cat-name" @click="selectCategory(item)">{{ item.cat_name }}</span>
                        </vue-nestable-handle>
                        <div slot="placeholder">
                            <b>{{LANG.panel.empty_table}}</b>
                            <p>{{LANG.panel.add_category_by_click}}</p>
                        </div>

                    </vue-nestable>
                </div>


            </div>
            <div slot='footer' class="drawer-footer">
                <div v-if="isAdd">
                    <div @click="isAdd=false" class="btn btn-simple">برگشت</div>
                    <div @click="add()" class="btn btn-warning">افزودن</div>
                </div>
                <div v-else>
                    <div v-if="readyToChange">
                        <div @click="resetChanges()" class="btn btn-simple">لغو</div>
                        <div @click="saveChanges()" class="btn btn-success">ذخیره تغییرات</div>
                    </div>
                    <div v-else>
                        <div @click="toggleDrawer()" class="btn btn-simple">برگشت</div>
                    </div>
                </div>

            </div>

        </ch-drawer>

    </section>
</template>

<script>

    export default {
        props: ['open'],
        data() {
            return {
                isEdit: false,
                isAdd: false,
                drawerPosition: 'bottom',
                drawerArea: '90%',
                categories: [],
                readyToChange: false,
                params: {cat_name: null},
                paramsChanges: {}
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
            toggleDrawer() {
                this.drawerOpen = !this.drawerOpen;
            },
            handleBeforeClose(next) {
                this.toggleDrawer();
                next();
            },
            loadCategories() {
                this.$http.post(this.URL.API + 'category/getTree').then((json) => {
                    this.categories = json.data;
                });
            },
            trigger(item, pathTo) {
                this.readyToChange = true;
                let parent = this.getParent(pathTo.pathTo);
                this.paramsChanges = {cat: item, parent: parent};
                //this.saveChanges();
            },
            getParent(pathTo) {
                if (pathTo === undefined || pathTo.length <= 0)
                    return false;
                let search = this.categories;
                let node;
                for (let i = 0; i < pathTo.length - 1; i++) {
                    node = search[pathTo[i]];
                    search = node.children;
                }
                return node;
            },
            selectCategory(item) {
                if (this.isEdit) return;
                this.$emit('onSelected', item);
                this.toggleDrawer();
            },
            resetChanges() {
                this.readyToChange = false;
                this.isEdit = false;
                this.loadCategories();
            },
            add() {
                this.$http.post(this.URL.API + 'category/add', this.params).then((json) => {
                    if (json.data.status) {
                        this.loadCategories();
                        this.isAdd = false;
                        this.params = {cat_name: null};
                    }
                });
            },
            saveChanges() {
                this.$http.post(this.URL.API + 'category/saveChanges', this.paramsChanges).then((json) => {
                    if (json.data) {
                        this.readyToChange = false;
                        this.paramsChanges = {};
                        this.isEdit = false;
                    }
                });
            },
        },
        created() {
            this.loadCategories();
        }
    }
</script>
