<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :blur="false"
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">
                        {{!!user ? LANG.user.edit_user + ' ' + user.fullName :LANG.user.add_user}}
                    </div>
                </div>
            </div>
            <div class="drawer-content" @keyup.enter="save()">
                <row :gutter="12" :columns="2">

                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.fname}}</label>
                            <div class="input-group">
                                <input v-model="params.fname" type="text"
                                       :placeholder="LANG.user.fname" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.lname}}</label>
                            <div class="input-group">
                                <input v-model="params.lname" type="text"
                                       :placeholder="LANG.user.lname" class="input">
                            </div>
                        </div>
                    </column>
                </row>

                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.username}}</label>
                            <div class="input-group">
                                <input v-model="params.username" type="text"
                                       :placeholder="LANG.user.username" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.email}}</label>
                            <div class="input-group">
                                <input v-model="params.email" type="text"
                                       :placeholder="LANG.user.email" class="input">
                            </div>
                        </div>
                    </column>
                </row>

                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.password}}</label>
                            <div class="input-group">
                                <input v-model="params.password" type="password"
                                       :placeholder="LANG.user.password" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.re_password}}</label>
                            <div class="input-group">
                                <input v-model="params.re_password" type="password"
                                       :placeholder="LANG.user.re_password" class="input">
                            </div>
                        </div>
                    </column>
                </row>


            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
                <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    export default {
        props: ['open', 'user'],
        created() {
        },
        data() {
            return {
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
                params: {}
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
            save() {
                let action = !!this.user ? 'edit' : 'add';
                this.$http.post(this.URL.API + 'user/' + action, this.params).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.toggleDrawer();
                        this.params = {};
                        this.$emit('onSuccess', true);
                    }
                });
            }
        },
        watch: {
            user: {
                handler(val) {
                    if (val === null) val = {};
                    this.params = val;
                },
                deep: true
            }
        }
    }
</script>