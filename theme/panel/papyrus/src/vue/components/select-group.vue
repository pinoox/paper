<template>
  <v-select
      class="input"
      :placeholder="LANG.user.select_user_group"
      dir="rtl"
      :value="selected"
      v-model="selected"
      label="group_name"
      :options="groups">
    <div slot="no-options"><span class="no-options">{{ LANG.panel.nothing_found }}</span></div>
  </v-select>
</template>

<script>
export default {
  props: {
    value: {
      default: null,
    },
  },
  data() {
    return {
      groups: [],
      selected: null,
    }
  },
  created() {
    this.getGroups();
    this.selected = this.value;
  },
  methods: {
    getGroups(keyword = null) {
      return this.$http.post(
          this.URL.API + 'setting/getGroups/',
          {
            keyword: keyword
          }).then((json) => {
        this.groups = !!json.data? json.data : [];
      });
    },
  },
  watch: {
    selected: {
      handler(oldVal, newVal) {
        if (newVal === oldVal)
          return;

        this.$emit('input', this.selected);
      },
      immediate: true,
    }
  }
}
</script>

<style scoped>

</style>