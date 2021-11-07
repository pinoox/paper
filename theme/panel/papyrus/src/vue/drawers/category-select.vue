<template>
  <section>

    <ch-drawer custom-class="drawer-wrapper"
               :location='drawerPosition'
               :visible.sync='drawerOpen'
               :blur="false"
               :area="drawerArea"
               :destroy-on-close="true"
               @open="openDrawer()"
               :before-close='handleBeforeClose'>
      <div slot='header' class="drawer-header">
        <div class="title">
          <div class="text">{{ LANG.panel.category}}</div>
          <div class="subtext">{{ LANG.panel.category_info }}</div>
        </div>
      </div>
      <div class="drawer-content">

        <div class="category-actions">
          <div class="input-wrapper">
            <label class="input-label">{{ LANG.panel.search }}</label>
            <div class="input-group">
              <input type="text"
                     v-model="params.keyword"
                     :placeholder="LANG.panel.search" class="input">
            </div>
          </div>
        </div>

        <category-nest
            v-if="drawerOpen"
            @select="select"
            :value="value"
            :keyword="params.keyword"
            @load="loadCategories"
            :categories="categories"
            :no-child="noChild"
            :type="type"
            :no-category="noCategory"
            :is-edit="false">
        </category-nest>
      </div>
      <div slot='footer' class="drawer-footer">
        <div v-if="!!value && !!value.cat_name" @click="unSelect()" class="btn btn-danger">{{ LANG.panel.unselect }} ({{value.cat_name}})</div>
        <div @click="toggleDrawer()" class="btn btn-simple">{{ LANG.panel.close }}</div>
      </div>

    </ch-drawer>

  </section>
</template>

<script>

import CategoryNest from '../components/category-nest.vue';

export default {
  components: {CategoryNest},
  props: {
    value: {
      default: null,
    },
    open: {
      default: null,
    },
    noChild:{
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
      drawerPosition: 'bottom',
      drawerArea: '90%',
      selected: null,
      categories: [],
      params: {
        keyword: null,
      }
    }
  },
  computed: {
    drawerOpen: {
      get() {
        return this.open;
      },
      set(val) {
        this.$emit('onClose', val);
      }
    }
  },
  methods: {
    toggleDrawer() {
      this.drawerOpen = !this.drawerOpen;
    },
    handleBeforeClose(next) {
      this.toggleDrawer();
      next();
    },
    openDrawer() {
      this._resetInitialData();

      this.loadCategories();
    },
    loadCategories() {
      this.$http.post(this.URL.API + 'category/getTree', this.params).then((json) => {
        this.categories = !!json.data ? json.data : [];
      });
    },
    select(category){
      let data = {...category};
      data.children = [];
      this.selected = category;
      this.$emit('input', category);
      this.$emit('select', category);
      this.toggleDrawer();
    },
    unSelect() {
      this.selected = null;
      this.$emit('input', null);
      this.$emit('select', null);
      this.toggleDrawer();
    },
  },
  watch: {
    value: {
      handler() {
        if (this.selected !== this.value)
          this.selected = this.value;
      },
      immediate: true,
    },
  },

}
</script>
