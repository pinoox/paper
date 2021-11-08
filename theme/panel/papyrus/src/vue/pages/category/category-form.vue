<template>
  <div class="page">
    <div class="menubar">
      <div class="items">
        <span @click="backToList()" class="item back-item"><i
            class="fa fa-chevron-right "></i> {{ LANG.panel.back }}
        </span>
        <div class="item publish-item" @click="save()">
          {{ !isEdit ? LANG.panel.add_category : LANG.panel.edit_category }}
        </div>
      </div>
    </div>
    <simplebar class="simplebar">
      <div class="container">
        <div class="section">
          <div class="section-content">
            <row :gutter="12" :columns="2" class="col-sm-order">

              <column :sm="2" :md="1" class="order-1">
                <div class="input-wrapper">
                  <label class="input-label">{{ LANG.panel.category_name }}</label>
                  <div class="input-group">
                    <input v-model="params.cat_name" type="text"
                           :placeholder="LANG.panel.category_name" class="input">
                  </div>
                </div>

                <div class="input-wrapper">
                  <label class="input-label">{{ LANG.panel.category_key }}</label>
                  <div class="input-group">
                    <input v-model="params.cat_key" type="text"
                           :placeholder="LANG.panel.category_key" class="input">
                  </div>
                </div>

                <div class="input-wrapper">
                  <label class="input-label">{{ LANG.panel.category_parent }}</label>
                  <div class="input-group">
                    <input :value="!!params.parent? params.parent.cat_name : ''" type="text"
                           @click="openDrawer('category')"
                           :placeholder="LANG.panel.category_parent" class="input drawer-toggler">
                  </div>
                </div>

              </column>
              <column :sm="2" :md="1" class="order-0">
                <div class="input-wrapper">
                  <label class="input-label center">{{ LANG.panel.category_image }}</label>
                  <div class="input-group">
                    <picture-input
                        ref="pictureInput"
                        width="200"
                        height="200"
                        radius="50"
                        size="5"
                        :alert-on-error="false"
                        accept="image/jpeg,image/png"
                        :prefill="image"
                        :removable="true"
                        @change="changeImage"
                        @remove="removeImage"
                        @error="errorImage"
                        button-class="btn btn-sm btn-primary"
                        remove-button-class="btn btn-sm btn-danger"
                        :custom-strings="LANG.panel.picture_input">
                    </picture-input>
                  </div>
                </div>
              </column>
            </row>
          </div>
        </div>
      </div>
    </simplebar>

    <category-select :open="drawerName==='category'"
                     :no-category="cat_id"
                     v-model="params.parent"
                     type="off"
                     @onClose="drawerName=null"></category-select>
  </div>
</template>

<script>
import CategorySelect from '../../drawers/category-select.vue';

export default {
  components: {CategorySelect},
  props: ['cat_id'],
  beforeRouteEnter: (to, from, next) => {
    next(vm => {
      vm.routeName = !!from && !!from.name && from.name === 'category-node' ? 'category-node' : 'category';
    });
  },
  data() {
    return {
      drawerName: null,
      image: null,
      category: null,
      routeName: 'category',
      params: {
        cat_name: null,
        cat_key: null,
        parent: null,
        imageData: null,
      }
    }
  },
  created() {
    if (this.isEdit) {
      this.params.cat_id = this.cat_id;
      this.getCategory();
    }
  },
  computed:
      {
        isEdit() {
          return !!this.cat_id;
        },
      },
  methods: {
    save() {
      this.$http.post(this.URL.API + 'category/save', this.params).then((json) => {
        if (this._messageResponse(json.data)) {
          this.backToList();
        }
      });
    },
    getCategory() {
      this.$http.get(this.URL.API + 'category/get/' + this.cat_id, this.params).then((json) => {
        this.category = json.data;

        if (!!this.category) {
          this.setParams(this.category);
        } else {
          this._resetInitialData('params');
        }
      });
    },
    setParams(data) {
      for (let key in this.params) {
        if (!!data[key])
          this.params[key] = data[key];
      }

      if (!!data.image_id)
        this.image = data.image;
    },
    changeImage(dataImage) {
      let file = this.$refs.pictureInput.file;

      this.params.imageData = {
        data: dataImage,
        name: file.name,
        size: file.size,
      };
    },
    removeImage() {
      this.$delete(this.params, 'imageData');
      this.params.delete_image = true;
    },
    errorImage(error) {
      this._notify('error', error.message);
    },
    openDrawer(drawerName) {
      this.drawerName = drawerName;
    },
    backToList() {
      let cat_id = !!this.params && !!this.params.parent ? this.params.parent.cat_id : null;

      if (!!cat_id)
        this._routerReplace({name: this.routeName, params: {cat_id: cat_id}})
      else
        this._routerReplace({name: this.routeName})
    }
  },
}
</script>