<template>
  <div class="page">
    <div class="menubar">
      <div class="items">
        <div class="text">
          <span class="title">{{ LANG.panel.list + ' ' + LANG.user.groups }}</span>
        </div>
        <div class="item" @click="add()">
          {{ LANG.panel.add }}
        </div>
      </div>
    </div>

    <div class="search-bar">
      <span class="icon"><i class="fa fa-search"></i></span>
      <input v-model="params.keyword" class="search-input" type="text" :placeholder="LANG.panel.search + '...'">
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
                :rows="groups"
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
                  <router-link :to="{name:'permissions',params:{group_key:props.row.group_key}}"  class="btn-action">
                    <i class="fas fa-user-lock"></i>
                  </router-link>
                  <span @click="edit(props.row)" class="btn-action"><i class="fa fa-edit"></i></span>
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

    <GroupForm @close="drawerName=null"
               @onSuccess="getItems()"
               :group="this.group"
               :open="drawerName==='group-form'"></GroupForm>
  </div>
</template>

<script>
import GroupForm from "./group-form.vue";

export default {
  components: {GroupForm},
  data() {
    return {
      isLoading: false,
      drawerName: null,
      groups: [],
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
      group: null
    }
  },
  computed: {
    columns() {
      return [
        {
          label: this.LANG.user.group_name,
          field: 'group_name',
        },
        {
          label: this.LANG.user.group_key,
          field: 'group_key',
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
      this.$http.post(this.URL.API + 'group/getAll/', this.params).then((json) => {
        this.groups = json.data.groups;
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
      this._delay(() => {
        this.updateParams({keyword: params.searchTerm});
        this.getItems();
      }, 500);
    },
    permission(row) {
      this.drawerName = 'permission-form';
      this.group = row;
    },
    edit(row) {
      this.drawerName = 'group-form';
      this.group = row;
    },
    add() {
      this.drawerName = 'group-form';
      this.group = null;
    },
    remove(row, index) {
      this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
        this.$http.post(this.URL.API + 'group/delete/' + row.group_key).then((json) => {
          if (this._messageResponse(json.data)) {
            this.$delete(this.groups, index)
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