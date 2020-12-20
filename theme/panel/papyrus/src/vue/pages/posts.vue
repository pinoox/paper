<template>
    <div class="page">
        <div class="menubar">
            <div class="items">
                <div class="text">
                    <span class="title">{{LANG.panel.list + ' '+ LANG.panel.posts}}</span>
                </div>
                <router-link :to="{name:'write'}" tag="div" class="item">
                    {{LANG.post.write}}
                </router-link>
            </div>
        </div>
        <div class="search-bar">
            <span class="icon"><i class="fa fa-search"></i></span>
            <input v-model="params.keyword" class="search-input" type="text" :placeholder="LANG.post.search_in_posts">
        </div>
        <simplebar class="simplebar">
            <div class="container">
                <ul class="section-tab">
                    <li @click="filter('all')"
                        :class="[params.status==='all' ? 'active' :'' ]">{{LANG.panel.all}}
                    </li>
                    <li @click="filter('publish')"
                        :class="[params.status==='publish'? 'active' :'']">
                        {{LANG.post.status.publish}}
                    </li>
                    <li @click="filter('draft')"
                        :class="[params.status==='draft'? 'active' :'']">
                        {{LANG.post.status.draft}}
                    </li>
                </ul>

                <div class="section compact-mode">
                    <div class="section-content">
                        <vue-good-table
                                styleClass="vgt-table table"
                                :rtl="_dir === 'rtl'"
                                compactMode
                                :columns="columns"
                                :rows="posts"
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
                                    <a :href="URL.FRONT + 'post/' + props.row.post_id +'/'+props.row.post_key" target="_blank"
                                       class="btn-action"><i class="fa fa-link"></i></a>
                                    <router-link :to="{name:'post-stats',params:{post_id:props.row.post_id}}"
                                                 class="btn-action"><i class="fa fa-chart-pie"></i></router-link>
                                    <router-link :to="{name:'comments',params:{post_id:props.row.post_id}}"
                                                 class="btn-action"><i class="fa fa-comments"></i></router-link>
                                    <router-link :to="{name:'write',params:{post_id:props.row.post_id}}"
                                                 class="btn-action">
                                        <i class="fa fa-edit"></i></router-link>
                                    <span @click="remove(props.row,props.index)" class="btn-action"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                                <div v-else>
                                <span :class="typeof props.column.style === 'function'? props.column.style(props.row) : props.column.style">
                                    <i v-if="!!props.column.icon" :class="props.column.icon"></i> {{props.formattedRow[props.column.field]}}
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
                posts: [],
                pages: [],
                params: {
                    keyword: null,
                    page: 1,
                    perPage: 10,
                    type: 'post',
                    status: 'all',
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
                        field: 'post_id',
                        sortable: true,
                    },
                    {
                        label: this.LANG.panel.image,
                        field: 'thumb_128',
                        sortable: false,
                    },
                    {
                        label: this.LANG.panel.title,
                        field: (item) => {
                            return this._isNull(item.title, this.LANG.post.no_title);
                        },
                        style: (item) => {
                            return !item.title ? 'light' : '';
                        },
                    },
                    {
                        label: this.LANG.post.author,
                        field: 'username',
                        style: 'light',
                    },
                    {
                        label: this.LANG.panel.date,
                        field: 'approx_insert_date',
                        style: 'light',
                    },
                    {
                        label: this.LANG.panel.status,
                        field: (item) => {
                            return this.LANG.post.status[item.status];
                        },
                        style: (item) => {
                            return 'badge-status ' + item.status;
                        },
                    },
                    {
                        label: this.LANG.post.visits,
                        icon: 'fa fa-eye',
                        field: (item) => {
                            return this._isNull(item.visits, '0');
                        },
                        style: 'light',
                    },
                    {
                        label: this.LANG.post.visitors,
                        icon: 'fa fa-users',
                        field: (item) => {
                            return this._isNull(item.visitors, '0');
                        },
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
                this.$http.post(this.URL.API + 'post/getAll/', this.params).then((json) => {
                    this.posts = json.data.posts;
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
            stats(row) {
                this.$router.push({name: 'post-stats', params: {post_id: row.post_id}});
            },
            edit(row) {
                this.$router.push({name: 'write', params: {post_id: row.post_id}});
            },
            remove(row, index) {
                let params = {post_id: row.post_id};
                this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'post/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.$delete(this.posts, index);
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