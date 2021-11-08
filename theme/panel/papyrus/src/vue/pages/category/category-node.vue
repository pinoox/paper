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
        <router-link :to="{name:'category'}" class="item">
          <i class="fa fa-list"></i>
          {{ LANG.panel.list_view }}
        </router-link>
      </div>
    </div>

    <div class="search-bar">
      <span class="icon"><i class="fa fa-search"></i></span>
      <input v-model="params.keyword" class="search-input" type="text"
             :placeholder="LANG.panel.search + '...'">
    </div>

    <simplebar class="simplebar">
      <div class="container">
        <div class="section compact-mode">
          <div class="section-content">
            <category-list :keyword="params.keyword" @edit="edit" :isDraggable="!params.keyword" :categories="categories" @load="loadCategories" :is-edit="true" type="off">
            </category-list>
          </div>
        </div>
      </div>
    </simplebar>
  </div>
</template>

<script>
import CategoryList from '../../components/category-nest.vue';

export default {
  components: {CategoryList},
  created() {
    this.loadCategories();
  },
  data() {
    return {
      isLoading: false,
      drawerName: null,
      option: null,
      categories: [],
      params: {
        keyword: null,
      }
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
    searchCategories() {
      this._delay(() => {
        this.loadCategories();
      }, 500);
    },
    loadCategories() {
      this.$http.post(this.URL.API + 'category/getTree', this.params).then((json) => {
        this.categories = json.data;
      });
    },
  },
}
</script>