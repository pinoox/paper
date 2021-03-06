<template>
    <div class="page">
        <div class="menubar">
            <div class="items">
                <div class="text">
                    <span class="title">{{LANG.panel.list + ' '+ LANG.panel.contacts}}</span>
                </div>
            </div>
        </div>


        <div class="search-bar">
            <span class="icon"><i class="fa fa-search"></i></span>
            <input v-model="params.keyword" class="search-input" type="text"
                   :placeholder="LANG.panel.search_in_contacts">
        </div>
        <simplebar class="simplebar">
            <div class="container">

                <ul class="section-tab">
                    <li @click="filter('all')"
                        :class="[params.status==='all' ? 'active' :'' ]">{{LANG.panel.all}}
                    </li>
                    <li @click="filter('seen')"
                        :class="[params.status==='seen'? 'active' :'']">
                        {{LANG.panel.contact_status.seen}}
                    </li>
                    <li @click="filter('unseen')"
                        :class="[params.status==='unseen'? 'active' :'']">
                        {{LANG.panel.contact_status.unseen}}
                    </li>
                </ul>

                <div class="section compact-mode">
                    <div class="section-content">
                        <vue-good-table
                                styleClass="vgt-table table"
                                :rtl="_dir==='rtl'"
                                compactMode
                                :columns="columns"
                                :rows="contacts"
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
                                    <img class="thumb thumb-round" :src="props.row.thumb_128" :alt="props.row.title">
                                </div>
                                <div v-else-if="props.column.field === 'operation'">
                                <span @click="remove(props.row,props.index)" class="btn-action"><i
                                        class="fa fa-trash"></i></span>
                                </div>
                                <div v-else-if="props.column.field === 'status'">
                                    <span class="light">{{LANG.panel.contact_status[props.row.status]}}</span>
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
    </div>
</template>

<script>

    export default {
        data() {
            return {
                isLoading: false,
                drawerName: null,
                contacts: [],
                pages: [],
                params: {
                    keyword: null,
                    page: 1,
                    perPage: 10,
                    status: 'all',
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
                        label: this.LANG.panel.id,
                        field: 'contact_id',
                    },
                    {
                        label: this.LANG.panel.subject,
                        field: 'subject',
                    },
                    {
                        label: this.LANG.panel.message,
                        field: 'message',
                    },
                    {
                        label: this.LANG.panel.user,
                        field: 'full_name',
                    },
                    {
                        label: this.LANG.user.mobile,
                        field: 'mobile',
                    },
                    {
                        label: this.LANG.user.email,
                        field: 'email',
                    },
                    {
                        label: this.LANG.panel.date,
                        field: 'approx_insert_date',
                        style: 'light',
                    },
                    {
                        label: this.LANG.panel.status,
                        field: 'status',
                    },
                    {
                        label: this.LANG.panel.operation,
                        field: 'operation',
                        style: 'operation',
                        sortable: false,
                    },
                ];
            },
        },
        methods: {
            getItems() {
                this.$http.post(this.URL.API + 'contact/getAll/', this.params).then((json) => {
                    this.contacts = json.data.contacts;
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
            remove(row, index) {
                let params = {contact_id: row.contact_id};
                this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'contact/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.$delete(this.contacts, index)
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