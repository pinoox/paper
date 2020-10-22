<template>
    <section>

        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :blur="false"
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">{{LANG.post.category}}</div>
                    <div class="subtext">{{LANG.post.category_info}}</div>
                </div>
            </div>
            <div class="drawer-content">


                <div class="category-actions">
                    <div v-if="!isEdit" @click="isEdit=!isEdit" class="btn btn-outline-primary btn-sm btn-edit">
                        {{LANG.post.edit_category}}
                    </div>
                    <div v-else @click="resetChanges()" class="btn btn-outline-primary btn-sm btn-edit btn-danger">
                        {{LANG.post.cancel_edit}}
                    </div>
                    <div v-if="categories.length>0 && isEdit" class="text-info">{{LANG.post.edit_category_info}}</div>

                    <row :gutter="12" :columns="1" v-if="isEdit">
                        <column :sm="1" :md="1">
                            <div class="input-wrapper" @keyup.enter="add()">
                                <label class="input-label">{{LANG.post.add_new_category}}</label>
                                <div class="input-group">
                                    <input v-model="params.cat_name" type="text"
                                           :placeholder="LANG.post.enter_cat_name" class="input">
                                    <div class="btn-insides">
                                        <div v-if="!params.cat_id" @click="add()" class="btn btn-primary">
                                            {{LANG.panel.add}}
                                        </div>
                                        <div v-if="!!params.cat_id" @click="cancelEdit" class="btn btn-outline-primary">
                                            {{LANG.panel.cancel}}
                                        </div>
                                        <div v-if="!!params.cat_id" @click="edit()" class="btn btn-primary">
                                            {{LANG.panel.edit}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </column>
                    </row>
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
                        <div class="actions">
                            <span @click="editItem(item)" class="btn-action"><i class="fa fa-pen"></i></span>
                            <span @click="remove(item)" class="btn-action"><i class="fa fa-trash"></i></span>
                        </div>
                    </vue-nestable-handle>
                    <div slot="placeholder">
                        <div v-if="!isEdit">
                            <b>{{LANG.panel.empty_table}}</b>
                            <p>{{LANG.post.add_category_by_click}}</p>
                        </div>
                    </div>

                </vue-nestable>
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
            editItem(item) {
                this.params.cat_name = item.cat_name;
                this.params.cat_id = item.cat_id;
            },
            cancelEdit() {
                this.params = {cat_id: null, cat_name: null};
            },
            edit() {
                this.$http.post(this.URL.API + 'category/edit/', this.params).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.loadCategories();
                    }
                });
            },
            remove(item) {
                let params = {cat_id: item.cat_id};
                this._confirm(PINOOX.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'category/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.loadCategories();
                            this.params = {};
                        }
                    });
                });
            },
            resetChanges() {
                this.readyToChange = false;
                this.isEdit = false;
                this.loadCategories();
            },
            add() {
                this.$http.post(this.URL.API + 'category/add', this.params).then((json) => {
                    if (this._statusResponse(json.data)) {
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
                    }
                });
            },
        },
        created() {
            this.loadCategories();
        }
    }
</script>
