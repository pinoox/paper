<template>
  <div class="page">
    <div class="menubar">
      <div class="items">
        <div class="text">
          <span class="title">{{ LANG.panel.permission }} {{ group.group_name }}</span>
        </div>
        <div class="item publish-item" @click="save()">
          {{ LANG.panel.save_changes }}
        </div>
        <router-link :to="{name:'groups'}" class="item">
          {{ LANG.user.groups }}
        </router-link>
      </div>
    </div>

    <div class="search-bar" v-if="false">
      <span class="icon"><i class="fa fa-search"></i></span>
      <input v-model="params.keyword" class="search-input" type="text" :placeholder="LANG.panel.search + '...'">
    </div>
    <simplebar class="simplebar">
      <div class="container">
        <div class="section compact-mode">
          <div class="section-content" @keyup.enter="save()">
            <row :gutter="12" :columns="1" class="col-sm-order">

              <column :sm="1" :md="1" class="order-1" v-if="!!group && !!group.group_key">
                <PermissionTree @onChange="onChangePermissions" :group="group"></PermissionTree>
              </column>
            </row>
          </div>
        </div>
      </div>
    </simplebar>
  </div>
</template>

<script>

import PermissionTree from "../permission/permission-tree.vue";

export default {
  components: {PermissionTree},
  props: ['group_key'],
  created() {
    this.getGroup();
  },
  data() {
    return {
      permissions: [],
      checked: [],
      nodes: [],
      branches: [],
      params:{},
      tree: {
        nodes: [],
        branches: [],
      },
      group: {}
    }
  },
  methods: {
    getGroup() {
      this.$http.get(this.URL.API + 'group/getGroup/' + this.group_key).then((json) => {
        this.group = json.data;
      });
    },
    onChangePermissions(e) {
      this.tree = e;
    },
    getUserPermissions() {
      return this.$http.get(this.URL.API + 'permission/getUserPermissions/').then((json) => {
        this.PERMISSION = json.data;
      });
    },
    save() {
      let params = {
        group_key: this.group.group_key,
        tree: this.tree,
      };
      this.$http.post(this.URL.API + 'permission/save/', params).then((json) => {
        if (this._messageResponse(json.data)) {
          if (params.group_key === this.USER.group_key)
            this.getUserPermissions();
        }
      });
    },
  }
}
</script>