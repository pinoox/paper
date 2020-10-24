<template>
    <div class="page">
        <div class="search-bar">
            <span class="icon"><i class="fa fa-search"></i></span>
            <input v-model="params.keyword" class="search-input" type="text"
                   :placeholder="LANG.comment.search_in_comments">
        </div>

        <div class="container">
            <div class="section">
                <div class="section-content">
                    <vue-good-table
                            styleClass="vgt-table table"
                            :line-numbers="true"
                            :rtl="true"
                            :columns="columns"
                            :rows="items"
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
                                <span @click="toggleStatus(props.row,props.index)" class="btn-action">
                                    <i class="fas"
                                       :class="[props.row.status==='publish' ? 'fa-comment-slash' : 'fa-check']"></i></span>
                                <span @click="remove(props.row,props.index)" class="btn-action">
                                    <i class="fa fa-trash"></i></span>
                            </div>
                            <div v-else-if="props.column.field === 'status'">
                                <span class="light">{{LANG.comment.status[props.row.status]}}</span>
                            </div>
                            <div v-else-if="props.column.field === 'title'">
                                <router-link :to="{name:'write',params:{'post_id':props.row.post_id}}">
                                    <span :class="props.column.style">{{props.row.title}}</span>
                                </router-link>
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

    </div>
</template>

<script>

    export default {
        data() {
            return {
                isLoading: false,
                drawerName: null,
                columns: [
                    {
                        label: PINOOX.LANG.panel.user,
                        field: 'full_name',
                    },
                    {
                        label: PINOOX.LANG.panel.subject,
                        field: 'subject',
                    },
                    {
                        label: PINOOX.LANG.panel.message,
                        field: 'message',
                    },
                    {
                        label: PINOOX.LANG.post.post,
                        field: 'title',
                        style: 'link',
                    },
                    {
                        label: PINOOX.LANG.user.mobile,
                        field: 'mobile',
                    },
                    {
                        label: PINOOX.LANG.panel.date,
                        field: 'approx_insert_date',
                        style: 'light',
                    },
                    {
                        label: PINOOX.LANG.panel.status,
                        field: 'status',
                    },
                    {
                        label: PINOOX.LANG.panel.operation,
                        field: 'operation',
                        style: 'operation',
                        sortable: false,
                    },
                ],
                items: [],
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
            }
        },
        methods: {
            getItems() {
                this.$http.post(this.URL.API + 'comment/getAll/', this.params).then((json) => {
                    this.items = json.data.items;
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
            toggleStatus(item, index) {
                this.$http.post(this.URL.API + 'comment/changeStatus/', item).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.getItems();
                    }
                });
            },
            remove(row, index) {
                let params = {contact_id: row.contact_id};
                this._confirm(PINOOX.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'comment/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.$delete(this.items, index)
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