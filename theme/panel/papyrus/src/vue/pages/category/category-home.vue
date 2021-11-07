<template>
  <div class="page">
    <div class="menubar">
      <div class="items">
        <div class="text">
          <span class="title">{{ LANG.panel.category }}</span>
        </div>
        <router-link :to="{name:'category-add'}" class="item">
          <i class="fa fa-plus"></i>
          {{ LANG.panel.add }}
        </router-link>
        <router-link :to="{name:'category-node'}" class="item">
          <i class="fa fa-sitemap"></i>
          {{ LANG.panel.tree_view }}
        </router-link>
      </div>
    </div>


    <div class="search-bar">
      <span class="icon"><i class="fa fa-search"></i></span>
      <input v-model="params.keyword" class="search-input" type="text" :placeholder="LANG.panel.search + '...'">
    </div>
    <div class="container">
      <ul class="breadcrumb-list link-into" v-if="!!breadcrumb && breadcrumb.length > 0">
        <li>
          <router-link :to="{name:'category'}"> {{ LANG.panel.all_categories }}</router-link>
        </li>
        <li v-for="item in breadcrumb" :class="item.active? 'active' : ''">
          <router-link v-if="!item.active" :to="{name:'category',params:{cat_id:item.cat_id}}">{{
              item.cat_name
            }}
          </router-link>
          <span v-else>{{ item.cat_name }}</span>
        </li>
      </ul>
    </div>


    <simplebar class="simplebar">
      <div class="container">
        <div class="section compact-mode">
          <div class="section-content">
            <vue-good-table
                styleClass="vgt-table table"
                :line-numbers="true"
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
                  <img class="thumb thumb-circle" :src="props.row.thumb_128" :alt="props.row.title">
                </div>
                <div v-else-if="props.column.field === 'operation'">
                  <router-link
                               :to="{name:'category',params:{cat_id:props.row.cat_id}}"
                               class="btn-action"><i
                      class="fa fa-eye"></i></router-link>
                  <router-link :to="{name:'category-edit',params:{cat_id:props.row.cat_id}}"
                               class="btn-action">
                    <i class="fa fa-edit"></i>
                  </router-link>
                  <span @click="remove(props.row,props.index)" class="btn-action"><i
                      class="fa fa-trash"></i></span>
                </div>
                <div v-else>
                                <span :class="props.column.style">
                                    {{ props.formattedRow[props.column.field] }}
                                </span>
                </div>
              </template>
              <div slot="emptystate">
                <div class="empty-data">
                  {{ LANG.panel.empty_table }}
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
  props: ['cat_id'],
  data() {
    return {
      isOpenViews: false,
      isLoading: false,
      drawerName: null,
      option: null,
      breadcrumb: null,
      items: [],
      pages: [],
      params: {
        keyword: null,
        page: 1,
        perPage: 20,
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
          label: this.LANG.panel.category_name,
          field: 'cat_name',
        },
        {
          label: this.LANG.panel.category_key,
          field: 'cat_key',
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
    onSearch(params) {
      this._delay(() => {
        this.updateParams({keyword: params.searchTerm});
        this.getItems();
      }, 500);
    },
    edit(row) {
      this.drawerName = 'form';
      this.option = row;
    },
    add() {
      this.drawerName = 'form';
      this.option = null;
    },
    getItems() {
      this.params.cat_id = this.cat_id;
      this.$http.post(this.URL.API + 'category/getAllByParent/', this.params).then((json) => {
        this.breadcrumb = json.data.breadcrumb;
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
    remove(row, index) {
      this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
        this.$http.post(this.URL.API + 'category/delete/', {
          cat_id: row.cat_id
        }).then((json) => {
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
}
</script>