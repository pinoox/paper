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
                :rows="groupList"
                :search-options="{
                                 externalQuery: params.keyword,
                            }"
                :totalRows="groupList.count"
                :pagination-options="defaultTableOpts">
              <template slot="table-row" slot-scope="props">
                <div v-if="props.column.field === 'thumb_128'">
                  <img class="thumb thumb-circle" :src="props.row.thumb_128" :alt="props.row.title">
                </div>
                <div v-if="props.column.field === 'group_name'">
                 <span>
                                    {{ props.row.group_name }}
                                </span>
                  <span class="badge-status draft" v-if="!!props.row.is_main">{{ LANG.panel.default_group }}</span>
                </div>
                <div v-else-if="props.column.field === 'operation'">
                  <router-link :to="{name:'permissions',params:{group_key:props.row.group_key}}" class="btn-action">
                    <i class="fas fa-user-lock"></i>
                  </router-link>
                  <span @click="edit(props.row)" class="btn-action"><i class="fa fa-edit"></i></span>
                  <span v-if="!props.row.is_main" @click="remove(props.row,props.index)" class="btn-action"><i
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
      group: null,
      params: {
        keyword: null,
      }
    }
  },
  created() {
    this.getItems();
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
    },
    groupList() {
      return this.groups.filter((item) => {
        return !item.hide || item.hide !== true;
      })
    },
  },
  methods: {
    getItems() {
      this.$http.get(this.URL.API + 'group/getAll/').then((json) => {
        this.groups = json.data;
      });
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
            let _index = this.groups.findIndex((group) => {
              return group.group_key === row.group_key;
            });
            this.$delete(this.groups, _index);
          }
        });
      });
    },
  },
}
</script>