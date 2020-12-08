<template>
    <simplebar class="form-wrapper">
        <section>
            <div class="form-content row" @keyup.enter="saveUserProfile()">
                <row :gutter="12" :columns="2" class="col-sm-order">
                    <div class="header order-1">
                        <div class="title">
                            <div class="text">
                                {{LANG.panel.user_profile}}
                            </div>
                        </div>
                    </div>
                    <column :sm="2" :md="1" class="order-2">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.fname}}</label>
                            <div class="input-group">
                                <input v-model="paramsUserProfile.fname" type="text"
                                       :placeholder="LANG.user.fname" class="input">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.lname}}</label>
                            <div class="input-group">
                                <input v-model="paramsUserProfile.lname" type="text"
                                       :placeholder="LANG.user.lname" class="input">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.username}}</label>
                            <div class="input-group">
                                <input v-model="paramsUserProfile.username" type="text"
                                       :placeholder="LANG.user.username" class="input">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.email}}</label>
                            <div class="input-group">
                                <input v-model="paramsUserProfile.email" type="text"
                                       :placeholder="LANG.user.email" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1" class="order-0">
                        <div class="input-wrapper">
                            <label class="input-label center">{{LANG.panel.profile_image}}</label>
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
                    </column>
                    <div class="footer order-3">
                        <div class="btn btn-primary" @click="saveUserProfile()">{{LANG.panel.save}}</div>
                    </div>
                </row>
            </div>
            <div class="form-content row" @keyup.enter="changePassword()">
                <row :gutter="12" :columns="2">
                    <div class="header">
                        <div class="title">
                            <div class="text">
                                {{LANG.user.change_password}}
                            </div>
                        </div>
                    </div>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.old_password}}</label>
                            <div class="input-group">
                                <input v-model="paramsChangePassword.old_password" type="password"
                                       :placeholder="LANG.user.enter_old_password" class="input">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.new_password}}</label>
                            <div class="input-group">
                                <input v-model="paramsChangePassword.password" type="password"
                                       :placeholder="LANG.user.enter_new_password" class="input">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.re_new_password}}</label>
                            <div class="input-group">
                                <input v-model="paramsChangePassword.re_password" type="password"
                                       :placeholder="LANG.user.enter_re_new_password" class="input">
                            </div>
                        </div>
                    </column>
                    <div class="footer">
                        <div class="btn btn-primary" @click="changePassword()">{{LANG.panel.save}}</div>
                        <br/>
                        <br/>
                    </div>
                </row>
            </div>

            <div class="form-content row">
                <row :gutter="12" :columns="1">
                    <div class="header">
                        <div class="title">
                            <div class="text">
                                {{LANG.panel.logout_account}}
                            </div>
                        </div>
                    </div>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <div class="btn btn-danger" @click="logout()">{{LANG.panel.logout}}</div>
                        </div>
                        <br><br>
                    </column>

                </row>
            </div>
        </section>
    </simplebar>
</template>

<script>
    export default {
        name: "profile",
        created() {
            this.getUserProfile();
        },
        data() {
            return {
                paramsUserProfile: {
                    username: null,
                    email: null,
                    fname: null,
                    lname: null,
                },
                paramsChangePassword: {
                    password: null,
                    re_password: null,
                    old_password: null,
                },
                avatar: null,
            }
        },
        methods: {
            saveUserProfile() {
                this.$http.post(this.URL.API + 'user/saveUserProfile', this.paramsUserProfile).then((json) => {
                    if (this._statusResponse(json.data))
                        this.setUserProfile();
                });
            },
            changePassword() {
                this.$http.post(this.URL.API + 'user/changePassword', this.paramsChangePassword).then((json) => {
                    if (this._statusResponse(json.data))
                        this._resetInitialData('paramsChangePassword');
                });
            },
            getUserProfile() {
                this.paramsUserProfile = {
                    ...this.paramsUserProfile,
                    ...this.USER,
                };

                if (this.USER.is_avatar)
                    this.avatar = this.USER.avatar;
            },
            setUserProfile() {
                let params = this._clone(this.paramsUserProfile);
                params.full_name = params.fname + ' '+ params.lname;
                for(let key in params)
                {
                    let value = params[key];
                    this.USER[key] = value;
                }
            },
            changeAvatar(file) {
                let data = new FormData();
                data.append('avatar', this.$refs.pictureInput.file);
                this.$http.post(this.URL.API + 'user/changeAvatar/', data).then((json) => {
                    if (json.data.status) {
                        this.USER.avatar = json.data.result.avatar;
                        this.USER.avatar_thumb = json.data.result.avatar_thumb;
                        this.USER.is_avatar = true;
                    } else {
                        this._notify('error', json.data.result);
                        this.avatar = null;
                    }
                })
            },
            removeAvatar() {
                if (!this.USER.is_avatar)
                    return;
                this.$http.get(this.URL.API + 'user/removeAvatar/').then((json) => {
                    if (json.data.status) {
                        this.avatar = null;
                        this.USER.avatar = json.data.result.avatar;
                        this.USER.avatar_thumb = json.data.result.avatar_thumb;
                        this.USER.is_avatar = false;
                    } else {
                        this._notify('error', json.data.result);
                    }
                });
            },
            errorAvatar(error){
                this._notify('error', error.message);
            },
            logout() {
                this._confirm(this.LANG.panel.are_you_sure_logout_account, () => {
                    this.$http.get(this.URL.API + 'user/logout').then((json) => {
                        if (json.data.status) {
                            this.USER.user = {isLogin: false};
                            this.$router.replace({name:'login'});
                        }
                    });
                });

            }
        }
    }
</script>