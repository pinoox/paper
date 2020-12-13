<template>
    <div>
        <div class="category-actions">
            <div v-if="categories.length>0 && isEdit" class="text-info">{{LANG.post.edit_category_info}}</div>

            <row :gutter="12" :columns="1" v-if="isEdit">
                <column :sm="1" :md="1">
                    <div class="input-wrapper" @keyup.enter="add()">
                        <label class="input-label" v-if="!!params.cat_id">{{LANG.post.edit_category}}</label>
                        <label class="input-label" v-else>{{LANG.post.add_new_category}}</label>
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
                        <span class="selected" @click="unSelectCategory()"
                              v-if="!isEdit && !!selected && selected.cat_id===item.cat_id">
                            <i class="fa fa-check"></i>
                            <i class="fa fa-times"></i>
                        </span>
                <span class="cat-name" @click="selectCategory(item)">
                            <i v-if="isEdit" class="fa fa-arrows-alt"></i> {{ item.cat_name }}
                        </span>
                <div class="actions">
                    <span @click="editItem(item)" class="btn-action"><i class="fa fa-pen"></i></span>
                    <span @click="remove(item)" class="btn-action"><i class="fa fa-trash"></i></span>
                </div>
            </vue-nestable-handle>
            <div slot="placeholder">
                <div v-if="isEdit">
                    <p>{{LANG.panel.empty_table}}</p>
                </div>
            </div>

        </vue-nestable>
    </div>
</template>

<script>
    export default {
        props: {
            post: {
                default: null,
            },
            selected: {
                default: null,
            },
            isEdit: {
                type: Boolean,
                default: false,
            },
        },
        created() {
            this.loadCategories();
        },
        data() {
            return {
                categories: [],
                params: {cat_name: null},
                paramsChanges: {},
            };
        },
        methods: {
            loadCategories() {
                this.$http.post(this.URL.API + 'category/getTree').then((json) => {
                    this.categories = json.data;
                });
            },
            trigger(item, pathTo) {
                let parent = null;
                if (!!pathTo && !!pathTo.pathTo) parent = this.getParent(pathTo.pathTo);
                parent = !!parent && !!parent.cat_id && parent.cat_id!== item.cat_id? parent : null;
                this.paramsChanges = this._clone({cat: item, parent: parent});
                this.saveChanges();
            },
            getParent(pathTo) {
                if (pathTo === undefined || pathTo.length <= 0)
                    return null;

                let search = this.categories;
                let node;
                pathTo.pop();
                for (let index of pathTo) {
                    node = search[index];
                    search = node.children;
                }
                return node;
            },
            selectCategory(item) {
                if (this.isEdit) return;
                this.changeCategory(item);
            },
            unSelectCategory() {
                if (this.isEdit) return;
                this.changeCategory(null);
            },
            editItem(item) {
                this.params.cat_name = item.cat_name;
                this.params.cat_id = item.cat_id;
            },
            cancelEdit() {
                this.params = {cat_id: null, cat_name: null};
            },
            changeCategory(item) {
                this.$http.post(this.URL.API + 'post/changeCategory/', {
                    cat_id: !!item && !!item.cat_id ? item.cat_id : '',
                    post_id: this.post,
                }).then((json) => {
                    if (json.data.status) {
                        this.$emit('onSelected', item);
                    } else {
                        this._notify('error', json.data.result);
                    }
                });
            },
            edit() {
                this.$http.post(this.URL.API + 'category/edit/', this.params).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.loadCategories();
                        this.params = {cat_id: null, cat_name: null};
                    }
                });
            },
            remove(item) {
                let params = {cat_id: item.cat_id};
                this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'category/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.loadCategories();
                            this.params = {};
                        }
                    });
                });
            },
            add() {
                this.$http.post(this.URL.API + 'category/add', this.params).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.categories.push(json.data.result);
                        this.params = {cat_name: null};
                    }
                });
            },
            saveChanges() {
                this.$http.post(this.URL.API + 'category/saveChanges', this.paramsChanges).then((json) => {
                    if (json.data) {
                        this.paramsChanges = {};
                    }
                });
            },
        },
    }
</script>