<template>
    <div class="page">
        <div class="menubar">
            <div class="items">
                <div class="item" @click="add()">
                    {{LANG.post.add}}
                </div>
            </div>
        </div>

        <div class="search-bar">
            <span class="icon"><i class="fa fa-search"></i></span>
            <input v-model="params.keyword" class="search-input" type="text" :placeholder="LANG.user.search_in_users">
        </div>

        <div class="container">
            <div class="section compact-mode">
                <div class="section-content">
                    <vue-good-table
                            styleClass="vgt-table table"
                            :line-numbers="true"
                            :rtl="_dir==='rtl'"
                            compactMode
                            :columns="columns"
                            :rows="users"
                            mode="remote"
                            :search-options="{
                                 externalQuery: params.keyword,
                            }"
                            @on-search="onSearch"
                            @on-page-change="onPageChange"
                            @on-sort-change="onSortChange"
                            @on-per-page-change="onPerPageChange"
                            :isLoading.sync="isLoading"
                            :totalRows="pages.count"
                            :pagination-options="$parent.defaultTableOpts">
                        <template slot="table-row" slot-scope="props">
                            <div v-if="props.column.field === 'thumb_128'">
                                <img class="thumb thumb-round" :src="props.row.thumb_128" :alt="props.row.title">
                            </div>
                            <div v-else-if="props.column.field === 'operation'">
                                <span @click="edit(props.row)" class="btn-action"><i class="fa fa-edit"></i></span>
                                <span @click="remove(props.row,props.index)" class="btn-action"><i
                                        class="fa fa-trash"></i></span>
                            </div>
                            <div v-else-if="props.column.field === 'status'">
                                <span class="light">{{LANG.user.status[props.row.status]}}</span>
                            </div>
                            <div v-else>
                                <span :class="props.column.style">
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </div>
                        </template>
                        <div slot="emptystate">
                            <div class="empty-data">
                                {{LANG.panel.empty_table}}
                            </div>
                        </div>
                        <template slot="loadingContent">
                            <div class="loading-message spinner"></div>
                        </template>

                    </vue-good-table>
                </div>
            </div>
        </div>

        <UserForm @onClose="drawerName=null"
                  @onSuccess="getItems()"
                  :user="this.user"
                  :open="drawerName==='user-form'"></UserForm>
    </div>
</template>

<script>
    import UserForm from "../drawers/user-form.vue";

    export default {
        components: {UserForm},
        data() {
            return {
                isLoading: false,
                drawerName: null,
                columns: [
                    {
                        label: PINOOX.LANG.panel.image,
                        field: 'thumb_128',
                        sortable: false,
                    },
                    {
                        label: PINOOX.LANG.user.email,
                        field: 'email',
                    },
                    {
                        label: PINOOX.LANG.panel.user,
                        field: 'full_name',
                    },
                    {
                        label: PINOOX.LANG.panel.status,
                        field: 'status',
                        style: 'light',
                    },
                    {
                        label: PINOOX.LANG.panel.date,
                        field: 'approx_register_date',
                        style: 'light',
                    },
                    {
                        label: PINOOX.LANG.panel.operation,
                        field: 'operation',
                        style: 'operation',
                        sortable: false,
                    },
                ],
                users: [],
                pages: [],
                params: {
                    keyword: null,
                    page: 1,
                    perPage: 10,
                    sort: {
                        field: '',
                        type: '',
                    },
                },
                user: null
            }
        },
        methods: {
            getItems() {
                this.$http.post(this.URL.API + 'user/getAll/', this.params).then((json) => {
                    this.users = json.data.users;
                    this.pages = json.data.pages;
                });
            },
            updateParams(newProps) {
                this.params = Object.assign({}, this.params, newProps);
            },
            onPageChange(params) {
                this.updateParams({page: params.currentPage});
                this.getItems();
            },
            onPerPageChange(params) {
                this.updateParams({perPage: params.currentPerPage});
                this.getItems();
            },
            onSearch(params) {
                this.updateParams({keyword: params.searchTerm});
                this.getItems();
            },
            edit(row) {
                this.drawerName = 'user-form';
                this.user = row;
            },
            add() {
                this.drawerName = 'user-form';
                this.user = null;
            },
            remove(row, index) {
                let params = {user_id: row.user_id};
                this._confirm(PINOOX.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'user/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.$delete(this.users, index)
                        }
                    });
                });
            },
            onSortChange(params) {
                let first = params.slice(0, 1).shift();
                this.updateParams({
                    sort: {
                        type: first.type,
                        field: first.field,
                    },
                });
                this.getItems();
            },
        },
        created() {
            this.getItems();
        }
    }
</script>