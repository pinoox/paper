<template>
  <div id="tree">
  </div>
</template>

<script>
import Tree from '@widgetjs/tree';
import Permission from '../../../js/permission';

export default {
  mixins:[Permission],
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
        onChange: function () {
          $this.tree.nodes = this.values;
          $this.tree.branches = this.selectedNodes;
          $this.onChange();
        },
      })
    },
    onChange() {
      this.$emit('onChange', this.tree);
    }
  },
}
</script>

