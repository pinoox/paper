<template>
  <div>
    <vue-nestable
        class="nestable nestable-category"
        :class="[isEdit ? 'editable' : '']"
        v-model="items"
        @change="trigger"
        :rtl="true"
        keyProp="cat_id"
        :maxDepth="8">
      <vue-nestable-handle
          :draggable="isDraggable"
          slot-scope="{ item }"
          :item="item">

        <span class="selected" @click="unSelectCategory()"
              v-if="!isEdit && !!value && value.cat_id===item.cat_id">
                            <i class="fa fa-check"></i>
                            <i class="fa fa-times"></i>
                        </span>

        <span class="cat-name"
              :class="classCategoryName(item)" @click="selectCategory(item)">
         {{ item.cat_name }}
                </span>
        <div class="actions">
          <router-link :to="{name:'category-edit',params:{cat_id:item.cat_id}}" class="btn-action">
            <i class="fa fa-pen"></i>
          </router-link>
          <span @click="remove(item)" class="btn-action"><i class="fa fa-trash"></i></span>
        </div>
      </vue-nestable-handle>
      <div slot="placeholder">
        <div>
          <p>{{ LANG.panel.empty_table }}</p>
        </div>
      </div>
    </vue-nestable>
  </div>
</template>

<script>
export default {
  props: {
    keyword: {
      type: String,
      default: null,
    },
    value: {
      default: null,
    },
    isDraggable: {
      type: Boolean,
      default: true,
    },
    isEdit: {
      type: Boolean,
      default: false,
    },
    categories: {
      type: Array,
      default: [],
    },
    noChild: {
      default: null,
    },
    noCategory: {
      default: null,
    },
    type: {
      default: 'primary',
    },
  },
  data() {
    return {
      params: {cat_name: null},
      paramsChanges: {},
      toggleStyle: '',
      items: [],
    };
  },
  methods: {
    tree(items, parent_id = null) {
      let result = [];
      let tmpItems = this._clone(items);
      for (let index in items) {
        let node = items[index];

        let noCategory = !!this.noCategory ? parseInt(this.noCategory) : null;
        if (!!noCategory && node['cat_id'] === noCategory) {
          continue;
        }

        node['children'] = [];

        if (node['parent_id'] === parent_id) {
          //if (!!parent_id)
          //tmpItems.splice(index, 1);

          let noChild = !!this.noChild ? parseInt(this.noChild) : null;
          if (!noChild || node['cat_id'] !== noChild) {
            let children = this.tree(tmpItems, node['cat_id']);
            children = this._clone(children);
            node['children'] = children;
          }

          result.push(node);
        }
      }
      return result;
    },
    trigger(item, pathTo) {
      let parent = null;
      if (!!pathTo && !!pathTo.pathTo) parent = this.getParent(pathTo.pathTo);
      parent = !!parent && !!parent.cat_id && parent.cat_id !== item.cat_id ? parent : null;
      this.paramsChanges = this._clone({cat: item, parent: parent});
      this.saveChanges();
    },
    getParent(pathTo) {
      if (pathTo === undefined || pathTo.length <= 0)
        return null;

      let search = this.items;
      let node;
      pathTo.pop();
      for (let index of pathTo) {
        node = search[index];
        search = node.children;
      }
      return node;
    },
    editItem(item) {
      this.$emit('edit', item);
    },
    refresh() {
      this.$emit('load');
    },
    remove(item) {
      let params = {cat_id: item.cat_id};
      this._confirm(this.LANG.panel.are_you_sure_to_delete, () => {
        this.$http.post(this.URL.API + 'category/delete/', params).then((json) => {
          if (this._messageResponse(json.data)) {
            this.refresh();
            this.params = {};
          }
        });
      });
    },
    saveChanges() {
      this.$http.post(this.URL.API + 'category/saveChanges', this.paramsChanges).then((json) => {
        if (json.data) {
          this.paramsChanges = {};
        }
      });
    },
    checkType(item) {
      return (this.type === 'primary' || this.type === 'group' || this.type === 'off');
    },
    toggleList(toggle) {
      this.toggleStyle = toggle;
    },
    classCategoryName(item) {
      if (!this.checkType(item))
        return 'no-selected';
      if (!!this.keyword && item.cat_name.includes(this.keyword))
        return 'pointer highlight';
      else if (!!this.keyword)
        return 'pointer';
      else
        return '';
    },
    unSelectCategory() {
      this.$emit('input', null);
      this.$emit('select', null);
    },
    selectCategory(item) {
      if (!this.checkType(item) || this.isEdit) return;

      let cat_id = !!item && !!item.cat_id ? item.cat_id : null;
      this.$emit('input', item);
      this.$emit('select', item);
    },
  },
  watch: {
    keyword: function (newVal, oldVal) {
      this._delay(() => {
        this.refresh();
      }, 500);
    },
    categories: {
      handler() {
        this.items = this.tree(this._clone(this.categories));
      },
      immediate: true,
      deep: true,
    },
  }
}
</script>