<template>
    <div :id="getId" class="select-container" :class="isSelectSection? 'section-select':''">
        <div class="select-header" v-if="!isSelectSection">
            <span class="btn btn-primary" :class="!isAllowSelect? 'disabled' : ''" @click="openSectionSelect()">{{LANG.post.select_post}}</span>
            <div class="revert" v-if="!!limit && limit >= 0">
                <span class="text">{{!!value? value.length : 0}} {{LANG.panel.of}} {{limit}}</span>
            </div>
        </div>
        <div class="select-header" v-else-if="isSelectSection">
            <span class="cancel" @click="cancelSectionSelect()"><i class="fa fa-times"></i></span>
            <div class="search-bar">
                <span class="icon"><i class="fa fa-search"></i></span>
                <input v-model="params.keyword" class="search-input" type="text"
                       :placeholder="LANG.post.search_in_posts">
            </div>
        </div>
        <simplebar class="select-post">
            <div v-if="!isSelectSection && !!value && value.length > 0">
                <draggable
                        :scroll-sensitivity="200"
                        :force-fallback="true"
                        :list="value"
                        :animation="10"
                        class="select-items"
                        ghost-class="ghost">
                        <div v-for="(item,index) in value" class="item">
                            <img :src="item.thumb_128">
                            <span class="text">{{item.title}}</span>
                            <span class="close" @click="removeItem(index)"><i class="fa fa-times"></i></span>
                        </div>
                </draggable>
            </div>

            <div class="no-selected" v-else-if="!isSelectSection">
                {{LANG.post.no_selected_post}}
            </div>
            <div class="select-table" v-else>
                <vue-good-table
                        styleClass="vgt-table table"
                        :rtl="_dir==='rtl'"
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
                            <span v-if="checkSelectable(props.row.post_id)"@click="selectPost(props.row)" class="btn btn-primary">{{LANG.post.select}}</span>
                            <span v-else class="checked"><i class="fa fa-check"></i></span>
                        </div>
                        <div v-else-if="props.column.field === 'status'">
                            <span class="light">{{LANG.user.status[props.row.status]}}</span>
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
        </simplebar>

        <div class="select-footer"></div>
    </div>
</template>

<script>
    export default {
        name: "select-post",
        props: {
            value: {
                type:Array,
                default: null,
            },
            limit:{
                type:Number,
                default:-1,
            }
        },
        data() {
            return {
                isLoading: false,
                isSelectSection:false,
                posts: [],
                pages: [],
                params: {
                    keyword: null,
                    page: 1,
                    status: 'publish',
                    perPage: 10,
                    sort: {
                        field: '',
                        type: 'post',
                    },
                },
            }
        },
        computed: {
            isAllowSelect()
            {
                let length = !!this.value? this.value.length : 0;
                return this.limit < 0 || (this.limit > 0 && this.limit > length);
            },
            getId()
            {
              return 'select-post-'+this._uid;
            },
            items(){
              return this.posts.map((item)=>{

              });
            },
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
                        label: this.LANG.panel.operation,
                        field: 'operation',
                        style: 'operation',
                        sortable: false,
                    },
                ];
            }
        },
        methods: {
            checkSelectable(post_id)
            {
                for (let item of this.value)
                {
                    if(!!item.post_id && item.post_id === post_id)
                        return false;
                }

                return true;
            },
            selectPost(row){
              this.value.push({
                  post_id:row.post_id,
                  title:row.title,
                  thumb_128:row.thumb_128,
              });
              this.$emit('input',this.value);
              this.cancelSectionSelect();
            },
            movePaginationToFooter()
            {
                this.$nextTick(()=>{
                    $('#'+this.getId+' .vgt-wrap__footer').appendTo('#'+this.getId+' .select-footer');
                });
            },
            openSectionSelect()
            {
                if(!this.isAllowSelect)
                    return;
                this.isSelectSection = true;
                this.movePaginationToFooter();
            },
            cancelSectionSelect(){
               $('#'+this.getId+' .select-footer').empty();
                this.isSelectSection = false;
            },
            removeItem(index) {
                this.$delete(this.value, index);
            },
            getItems() {
                this.$http.post(this.URL.API + 'post/getAll/', this.params).then((json) => {
                    this.posts = json.data.posts;
                    this.pages = json.data.pages;
                    this.movePaginationToFooter();
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
               this._delay(()=>{
                   this.updateParams({keyword: params.searchTerm});
                   this.getItems();
               },500)
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
