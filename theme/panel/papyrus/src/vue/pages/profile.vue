<template>
    <div class="form-wrapper">
            <section>
                <div class="form-content row" @keyup.enter="saveUserProfile()">
                    <row :gutter="12" :columns="4" class="col-sm-order">
                        <div class="header order-1" >
                            <div class="title">
                                <div class="text">
                                    {{LANG.panel.user_profile}}
                                </div>
                            </div>
                        </div>
                        <column :sm="3" :lg="2" class="order-2">
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
                        <column :sm="1" :lg="2" class="order-0">
                            <div class="input-wrapper">
                                <label class="input-label center">تصویر پروفایل</label>
                                <div class="input-group">
                                    <picture-input
                                            ref="pictureInput"
                                            width="200"
                                            height="200"
                                            radius="50"
                                            accept="image/jpeg,image/png"
                                            :hide-change-button="false"
                                            :crop="true"
                                            :removable="true"

                                            button-class="btn btn-sm btn-primary"
                                            remove-button-class="btn btn-sm btn-danger"
                                            :custom-strings="{
        upload: 'آپلود',
        drag: 'ضربه بزنید یا تصویر را بکشید و اینجا رها کنید',
        remove: 'حذف',
        select: 'انتخاب تصویر',
        change: 'تغییر تصویر',
      }">
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
                    <row :gutter="12" :columns="4">
                        <div class="header">
                            <div class="title">
                                <div class="text">
                                    {{LANG.user.change_password}}
                                </div>
                            </div>
                        </div>
                        <column :sm="3" :lg="2">
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
            </section>
        </div>
</template>

<script>
    import ImageUpload from '../components/image-upload.vue';

    export default {
        components:{ImageUpload},
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
                }
            }
        },
        methods: {
            save() {

            },
            saveUserProfile() {
                this.$http.post(this.URL.API + 'user/saveUserProfile', this.paramsUserProfile).then((json) => {
                    if (this._statusResponse(json.data))
                        this.setUserProfile();

                });
            },
            changePassword() {
                this.$http.post(this.URL.API + 'user/changePassword', this.paramsChangePassword).then((json) => {
                    if(this._statusResponse(json.data))
                        this._resetInitialData('paramsChangePassword');
                });
            },
            getUserProfile() {
                this.paramsUserProfile = {
                    ...this.paramsUserProfile,
                    ...this.USER,
                };
            },
            setUserProfile() {
                this.USER = {
                    ...this.USER,
                    ...this.paramsUserProfile,
                };
            }
        }
    }
</script>