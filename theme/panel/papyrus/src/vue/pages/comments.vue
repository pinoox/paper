<template>
    <div class="page">
        <div class="menubar">
            <div class="items">
                <div class="text cursor-pointer" v-if="!!post">
                    <span @click="$router.go(-1)" class="title">{{LANG.panel.list + ' '+ LANG.panel.comments}}: <b> {{post.draft_title}}</b>    </span>
                </div>
                <div v-else class="text">
                    <span class="title">{{LANG.panel.list + ' '+ LANG.panel.comments}}</span>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <span class="icon"><i class="fa fa-search"></i></span>
            <input v-model="params.keyword" class="search-input" type="text"
                   :placeholder="LANG.comment.search_in_comments">
        </div>

        <simplebar class="simplebar">
            <div class="container">

                <ul class="section-tab">
                    <li @click="filter('all')"
                        :class="[params.status==='all' ? 'active' :'' ]">{{LANG.panel.all}}
                    </li>
                    <li @click="filter('publish')"
                        :class="[params.status==='publish'? 'active' :'']">
                        {{LANG.comment.status.publish}}
                    </li>
                    <li @click="filter('pending')"
                        :class="[params.status==='pending'? 'active' :'']">
                        {{LANG.comment.status.pending}}
                    </li>
                    <li @click="filter('suspend')"
                        :class="[params.status==='suspend'? 'active' :'']">
                        {{LANG.comment.status.suspend}}
                    </li>
                </ul>

                <div class="section compact-mode">
                    <div class="section-content">
                        <vue-good-table
                                styleClass="vgt-table table"
                                :rtl="_dir==='rtl'"
                                compactMode
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
                                :pagination-options="defaultTableOpts">
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field === 'thumb_128'">
                                    <img class="thumb thumb-round" :src="props.row.thumb_128" :alt="props.row.title">
                                </div>
                                <div v-else-if="props.column.field === 'operation'">
                                    <div @click="read(props.row)" class="btn-action">
                                        <i class="fa fa-eye"></i></div>
                                    <div @click="toggleStatus(props.row,props.index)" class="btn-action">
                                        <i class="fas"
                                           :class="[props.row.status==='publish' ? 'fa-comment-slash' : 'fa-check']"></i>
                                    </div>
                                    <div @click="remove(props.row,props.index)" class="btn-action">
                                        <i class="fa fa-trash"></i></div>

                                </div>
                                <div v-else-if="props.column.field === 'status'">
                                    <span class="badge-status" :class="props.row.status">{{LANG.comment.status[props.row.status]}}</span>
                                </div>
                                <div v-else-if="props.column.field === 'title'">
                                    <router-link :to="{name:'write',params:{'post_id':props.row.post_id}}">
                                        <span :class="props.column.style">{{props.row.title}}</span>
                                    </router-link>
                                </div>
                                <div v-else-if="props.column.field === 'message'">
                                    {{props.row.message | truncate(100, '...')}}
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

        <CommentDrawer @close="readComment = null" :comment="readComment" :open="readComment!=null"></CommentDrawer>
    </div>
</template>

<script>

    import CommentDrawer from '../drawers/comment.vue';

    export default {
        components: {CommentDrawer},
        props: ['post_id'],
        data() {
            return {
                isLoading: false,
                drawerName: null,
                items: [],
                pages: [],
                post: null,
                readComment: null,
                params: {
                    keyword: null,
                    page: 1,
                    status: 'all',
                    perPage: 10,
                    post_id: null,
                    sort: {
                        field: '',
                        type: '',
                    },
                },
            }
        },
        computed: {
            columns() {
                return [
                    {
                        label: this.LANG.panel.id,
                        field: 'comment_id',
                    },
                    {
                        label: this.LANG.panel.user,
                        field: 'full_name',
                        width: '70px',
                    },
                    {
                        label: this.LANG.panel.message,
                        field: 'message',
                        width: '300px',
                    },
                    {
                        label: this.LANG.post.post,
                        field: 'title',
                        style: 'link',
                        width: '200px',
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
            }
        },
        created() {
            this.params.post_id = this.post_id;
        },
        methods: {
            getItems() {

                this.$http.post(this.URL.API + 'comment/getAll/', this.params).then((json) => {
                    this.items = json.data.items;
                    this.pages = json.data.pages;
                    this.post = json.data.post;
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
                console.log(row);
                this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.get(this.URL.API + 'comment/delete/'+row.comment_id).then((json) => {
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
            filter(param) {
                this.updateParams({status: param});
                this.getItems();
            },
            read(comment, index) {
                this.readComment = comment;
            }

        },
        watch: {
            readComment() {
                $('.app-container').toggleClass('drawer--blur');
                $('body').toggleClass('toggle-over-flow');
            },
        }
    }
</script>