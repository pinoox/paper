<template>
    <div class="page">
        <div class="menubar">
            <div class="items">
                <div class="text">
                    <span class="title">{{LANG.panel.list + ' '+ LANG.panel.users}}</span>
                </div>
                <div class="item" @click="add()">
                    {{LANG.post.add}}
                </div>
            </div>
        </div>

        <div class="search-bar">
            <span class="icon"><i class="fa fa-search"></i></span>
            <input v-model="params.keyword" class="search-input" type="text" :placeholder="LANG.user.search_in_users">
        </div>
        <simplebar class="simplebar">
            <div class="container">

                <ul class="section-tab">
                    <li @click="filter('all')"
                        :class="[params.status==='all' ? 'active' :'' ]">{{LANG.panel.all}}
                    </li>
                    <li @click="filter('active')"
                        :class="[params.status==='active'? 'active' :'']">
                        {{LANG.user.status.active}}
                    </li>
                    <li @click="filter('suspend')"
                        :class="[params.status==='suspend'? 'active' :'']">
                        {{LANG.user.status.suspend}}
                    </li>
                </ul>

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
                                :pagination-options="defaultTableOpts">
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field === 'thumb_128'">
                                    <img class="thumb thumb-circle" :src="props.row.thumb_128" :alt="props.row.title">
                                </div>
                                <div v-else-if="props.column.field === 'operation'">
                                    <span @click="edit(props.row)" class="btn-action"><i class="fa fa-edit"></i></span>
                                    <span @click="remove(props.row,props.index)" class="btn-action"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                                <div v-else-if="props.column.field === 'status'">
                                    <span class="badge-status" :class="props.row.status">{{LANG.user.status[props.row.status]}}</span>
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
        </simplebar>

        <UserForm @close="drawerName=null"
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
                users: [],
                pages: [],
                params: {
                    keyword: null,
                    page: 1,
                    status: 'all',
                    perPage: 10,
                    sort: {
                        field: '',
                        type: '',
                    },
                },
                user: null
            }
        },
        computed:{
            columns(){
                return [
                    {
                        label: this.LANG.panel.image,
                        field: 'thumb_128',
                        sortable: false,
                    },
                    {
                        label: this.LANG.user.email,
                        field: 'email',
                    },
                    {
                        label: this.LANG.panel.user,
                        field: 'full_name',
                    },
                    {
                        label: this.LANG.panel.status,
                        field: 'status',
                        style: 'light',
                    },
                    {
                        label: this.LANG.panel.date,
                        field: 'approx_register_date',
                        style: 'light',
                    },
                    {
                        label: this.LANG.panel.operation,
                        field: 'operation',
                        style: 'operation',
                        sortable: false,
                    },
                ];
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
                this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
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
            filter(param) {
                this.updateParams({status: param});
                this.getItems();
            },
        },
    }
</script>