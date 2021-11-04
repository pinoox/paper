<template>
    <div>
        <div v-if="state === 'list'">
            <div class="category-actions" v-if="isAdd">
                <div @click="goToAddState()" class="btn btn-primary">
                    {{LANG.panel.add}}
                </div>
            </div>
            <div v-if="isSortable">
                <vue-nestable
                        class="nestable nestable-category editable"
                        v-model="items"
                        @change="trigger"
                        :rtl="true"
                        keyProp="_id"
                        disabled
                        :maxDepth="depth">
                    <vue-nestable-handle
                            :draggable="true"
                            slot-scope="{ item,index }"
                            :item="item">
                <span class="cat-name">
                            <i class="fa fa-arrows-alt"></i> {{ item.label }}
                        </span>
                        <div class="actions">
                            <span v-if="isEdit" @click="goToEditState(index)" class="btn-action"><i
                                    class="fa fa-pen"></i></span>
                            <span v-if="isDelete" @click="deleteItem(index)" class="btn-action"><i
                                    class="fa fa-trash"></i></span>
                        </div>
                    </vue-nestable-handle>
                    <div slot="placeholder">
                        <div>
                            <b>{{LANG.panel.empty_table}}</b>
                        </div>
                    </div>

                </vue-nestable>
            </div>
            <div v-else>
                <div class="nestable nestable-category nestable-rtl">
                        <ol class="nestable-list nestable-group nestable-item-content" v-if="items.length > 0">
                            <li v-for="(item,index) in items" class="nestable-item" :class="'nestable-item-'+index">
                                <span class="cat-name">{{ item.label }}</span>
                                <div class="actions">
                                <span v-if="isEdit" @click="goToEditState(index)" class="btn-action show"><i
                                        class="fa fa-pen"></i></span>
                                    <span v-if="isDelete" @click="deleteItem(index)" class="btn-action show"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            </li>
                        </ol>
                    <div slot="placeholder" v-else>
                        <div>
                            <b>{{LANG.panel.empty_table}}</b>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div v-else-if="isAdd && state === 'add'">
            <div class="category-actions">
                <span class="back-btn" @click="goToListState()"><i
                        class="fa fa-angle-right"></i> {{LANG.panel.back}}</span>
                <div class="form-content" @keyup.enter="add()">
                    <div class="input-wrapper">
                        <label class="input-label"> {{LANG.panel.name_list}}</label>
                        <div class="input-group">
                            <input :placeholder="LANG.panel.enter_name" v-model="paramsAdd.label" type="text"
                                   class="input">
                        </div>
                    </div>
                    <form-builder :list-disable="true" :settings="settings" v-model="paramsAdd"></form-builder>
                    <div class="input-wrapper">
                        <span @click="goToListState()" class="btn btn-outline-primary">{{LANG.panel.cancel}}</span>
                        <span @click="add()" class="btn btn-primary">{{LANG.panel.add}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="isEdit && state === 'edit'">
            <div class="category-actions">
                <span class="back-btn" @click="goToListState()"><i
                        class="fa fa-angle-right"></i>  {{LANG.panel.back}}</span>
                <div class="form-content" @keyup.enter="edit()">
                    <div class="input-wrapper">
                        <label class="input-label"> {{LANG.panel.name_list}}</label>
                        <div class="input-group">
                            <input :placeholder="LANG.panel.enter_name" v-model="paramsEdit.label" type="text"
                                   class="input" :disabled="!islabelEdit">
                        </div>
                    </div>
                    <form-builder :list-disable="true" :settings="settings"
                                  v-model="paramsEdit"></form-builder>
                    <div class="input-wrapper">
                        <span @click="goToListState()" class="btn btn-outline-primary">{{LANG.panel.cancel}}</span>
                        <span @click="edit()" class="btn btn-primary">{{LANG.panel.save_changes}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FormBuilder from "./form-builder.vue";

    export default {
        components: {FormBuilder},
        props: {
            value: {
                default: null,
            },
            setting: {
                default: {},
            },
            depth: {
                default: 3,
            }
        },
        created() {
            this.items = !!this.value ? this.value : [];
            this.items = this.items.map((item) => {
                item._id = this.getId();
                return item;
            });
            this.createParamsByDefaultValue();
        },
        beforeDestroy() {
            let items = this.items.map((item) => {
                delete item._id;
                return item;
            });
            this.$emit('input', items);
        },
        data() {
            return {
                items: [],
                paramsAdd: {
                    label: null,
                },
                paramsEdit: {},
                editIndex: null,
                pointerId: 0,
                state: 'list',
            };
        },
        computed: {
            settings() {
                return !!this.setting && !!this.setting.settings ? this.setting.settings : [];
            },
            isAdd() {
                if (!!this.setting && 'add' in this.setting)
                    return this.setting.add;
                else
                    return true;
            },
            isEdit() {
                if (!!this.setting && 'edit' in this.setting)
                    return this.setting.edit;
                else
                    return true;
            },
            isDelete() {
                if (!!this.setting && 'delete' in this.setting)
                    return this.setting.delete;
                else
                    return true;
            },
            isSortable() {
                if (!!this.setting && 'sortable' in this.setting)
                    return this.setting.sortable;
                else
                    return true;
            },
            islabelEdit() {
                if (!!this.setting && 'label-edit' in this.setting)
                    return this.setting['label-edit'];
                else
                    return true;
            },
        },
        methods: {
            add() {
                if (this.checkLabel(this.paramsAdd)) {
                    let params = {
                        ...this.paramsAdd,
                        _id: this.getId(),
                    };
                    this.items.unshift(params);
                    this.goToListState();
                }
            },
            edit() {
                if (this.checkLabel(this.paramsEdit)) {
                    this.items[this.editIndex] = {
                        ...this.paramsEdit,
                    };
                    this.goToListState();
                }
            },
            deleteItem(index) {
                this.$delete(this.items, index);
            },
            checkLabel(params) {
                if (!params.label) {
                    this._notify('error', this.LANG.panel.err_empty_name_list);
                    return false;
                }
                return true;
            },
            createParamsByDefaultValue() {
                for (const setting of this.settings) {
                    this.paramsAdd[setting.key] = setting.value;
                }
            },
            getId() {
                this.pointerId++;
                return this.pointerId;
            },
            trigger(item, pathTo) {
            },
            goToAddState() {
                this._resetInitialData('paramsAdd');
                this.state = 'add';
            },
            goToEditState(index) {
                this.paramsEdit = this._clone(this.items[index]);
                this.editIndex = index;
                this.state = 'edit';
            },
            goToListState() {
                this.state = 'list';
            }
        },
    }
</script>