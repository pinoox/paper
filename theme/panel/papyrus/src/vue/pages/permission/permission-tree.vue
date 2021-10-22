<template>
  <div>
    <span v-if="isAdministrator" class="alert"> {{ _replaceData(LANG.user.alert_administrator_not_change,group.group_name) }}</span>
    <div id="tree">
    </div>
  </div>
</template>

<script>
import Tree from '@widgetjs/tree';
import Permission from '../../../js/permission';

export default {
  mixins: [Permission],
  name: 'PermissionTree',
  props: ['group'],
  data() {
    return {
      checked: [],
      tree: {
        nodes: [],
        branches: [],
      }
    }
  },
  mounted() {
    this.getPermissions();
  },
  computed: {
    isAdministrator() {
      return !!this.group && this.group.group_key === 'administrator';
    },
  },
  methods: {
    getPermissions() {
      let params = {'group_key': this.group.group_key};
      this.$http.post(this.URL.API + 'permission/getAll', params).then((json) => {
        if (!!json.data) {
          this.tree.nodes = !!json.data.nodes ? json.data.nodes : [];
          this.tree.branches = !!json.data.branches ? json.data.branches : [];
        }
        this.createTree();
      });
    },
    createTree() {
      let $this = this;
      let tree = new Tree('#tree', {
        data: this.permissions,
        values: this.tree.nodes,
        disables: this.getDisableNods(),
        onChange: function () {
          $this.tree.nodes = this.values;
          $this.tree.branches = this.selectedNodes;
          $this.onChange();
        },
      })
    },
    getDisableNods() {
      return this.isAdministrator ? this.tree.nodes : null;
    },
    onChange() {
      this.$emit('onChange', this.tree);
    }
  },
}
</script>

