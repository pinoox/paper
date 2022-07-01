<template>
  <section>
    <ch-drawer custom-class="drawer-wrapper"
               location="bottom"
               :visible="open"
               :destroy-on-close="true"
               :blur="false"
               area="90%"
               @open="openDrawer"
               @close="closeDrawer">
      <div slot='header' class="drawer-header">
        <div class="title">
          <div class="text">
            {{ !!user ? LANG.user.edit_user + ' ' + user.full_name : LANG.user.add_user }}
          </div>
        </div>
      </div>
      <div class="drawer-content" @keyup.enter="save()">
        <row :gutter="12" :columns="2" class="col-sm-order">

          <column :sm="2" :md="1" class="order-1">
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.fname }}</label>
              <div class="input-group">
                <input v-model="params.fname" type="text"
                       :placeholder="LANG.user.fname" class="input">
              </div>
            </div>
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.lname }}</label>
              <div class="input-group">
                <input v-model="params.lname" type="text"
                       :placeholder="LANG.user.lname" class="input">
              </div>
            </div>
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.select_user_group }}</label>
              <v-select
                  class="input"
                  :placeholder="LANG.user.select_user_group"
                  dir="rtl"
                  v-model="params.group"
                  label="group_name"
                  :options="groups">
                <div slot="no-options"><span class="no-options">{{ LANG.panel.nothing_found }}</span></div>
              </v-select>
            </div>
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.username }}</label>
              <div class="input-group">
                <input v-model="params.username" type="text"
                       :placeholder="LANG.user.username" class="input">
              </div>
            </div>
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.email }}</label>
              <div class="input-group">
                <input v-model="params.email" type="text"
                       :placeholder="LANG.user.email" class="input">
              </div>
            </div>
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.password }}</label>
              <div class="input-group">
                <input v-model="params.password" type="password"
                       :placeholder="LANG.user.password" class="input">
              </div>
            </div>
            <div class="input-wrapper">
              <label class="input-label">{{ LANG.user.re_password }}</label>
              <div class="input-group">
                <input v-model="params.re_password" type="password"
                       :placeholder="LANG.user.re_password" class="input">
              </div>
            </div>
          </column>
          <column :sm="2" :md="1" class="order-0">
            <div class="input-wrapper">
              <label class="input-label center">{{ LANG.user.user_image }}</label>
              <div class="input-group">
                <picture-input
                    ref="pictureInput"
                    width="200"
                    height="200"
                    radius="50"
                    size="5"
                    :alert-on-error="false"
                    accept="image/jpeg,image/png"
                    :prefill="avatar"
                    :removable="true"
                    @change="changeAvatar"
                    @remove="removeAvatar"
                    @error="errorAvatar"
                    button-class="btn btn-sm btn-primary"
                    remove-button-class="btn btn-sm btn-danger"
                    :custom-strings="LANG.panel.picture_input">
                </picture-input>
              </div>
            </div>
            <div class="input-wrapper center">
              <label class="input-label center">{{ LANG.user.user_status }}</label>
              <div class="input-group center">
                <toggle-button v-model="status"
                               :sync="true"
                               :width="70"
                               :labels="{checked: LANG.post.active, unchecked: LANG.post.inactive}"/>
              </div>
            </div>
          </column>
        </row>
      </div>
      <div slot='footer' class="drawer-footer">
        <div @click="closeDrawer()" class="btn btn-simple">{{ LANG.panel.close }}</div>
        <div class="btn btn-primary" @click="save()">{{ LANG.panel.save }}</div>
      </div>
    </ch-drawer>
  </section>
</template>

<script>
export default {
  props: ['open', 'user'],
  data() {
    return {
      params: {
        status: true,
      },
      avatar: null,
      groups: [],
    }
  },
  computed: {
    status: {
      get() {
        return this.params.status === 'active';
      }
      ,
      set(val) {
        this.params.status = !!val? 'active' : 'inactive';
      }
    }
  },
  methods: {
    closeDrawer() {
      this.$emit('close', false);
    },
    save() {
      let params = this.getFormParams(this.params);
      let action = !!this.user ? 'edit' : 'add';
      this.$http.post(this.URL.API + 'user/' + action, params).then((json) => {
        if (this._messageResponse(json.data)) {
          this.$emit('onSuccess', true);
          this.closeDrawer();
        }
      });
    },
    getFormParams(params) {
      let data = new FormData();
      for (let key in params) {
        let value = params[key];
        if (key === 'delete_avatar')
          value = !!value ? 1 : 0;
        else if (key === 'group') {
          key = 'group_key';
          value = !!value && typeof value === "object" && !!value.group_key ? value.group_key : '';
        }

        data.append(key, value);
      }
      return data;
    },
    changeAvatar(file) {
      this.params.avatar = this.$refs.pictureInput.file;
    },
    removeAvatar() {
      this.$delete(this.params, 'avatar');
      this.params.delete_avatar = true;
    },
    errorAvatar(error) {
      this._notify('error', error.message);
    },
    getGroups(keyword = null) {
      return this.$http.post(
          this.URL.API + 'user/getGroups/',
          {
            keyword: keyword
          }).then((json) => {
        this.groups = !!json.data? json.data : [];
      });
    },
    openDrawer() {
      this._resetInitialData();
      this.getGroups();
      let data = this.user;
      if (!!data) {
        this.params = {
          user_id: data.user_id,
          status: data.status,
          fname: data.fname,
          lname: data.lname,
          username: data.username,
          email: data.email,
          group: data.group,
        };

        if (!!data.avatar_id) {
          this.avatar = data.image;
          this.params.avatar_id = data.avatar_id;
        }
      }
    }
  },
}
</script>