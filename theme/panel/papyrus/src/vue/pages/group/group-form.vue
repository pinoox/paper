<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   location="bottom"
                   :visible="open"
                   :destroy-on-close="true"
                   :blur="false"
                   area="90%"
                   @close="closeDrawer">
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">
                        {{!!group ? LANG.user.edit_group + ' ' + group.group_name : LANG.user.add_group}}
                    </div>
                </div>
            </div>
            <div class="drawer-content" @keyup.enter="save()">
                <row :gutter="12" :columns="2" class="col-sm-order">

                    <column :sm="1" :md="1" class="order-1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.group_name}}</label>
                            <div class="input-group">
                                <input v-model="params.group_name" type="text"
                                       :placeholder="LANG.user.group_name" class="input">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.group_key}}</label>
                            <div class="input-group">
                                <input v-model="params.group_key" type="text"
                                       :placeholder="LANG.user.group_key" class="input">
                            </div>
                        </div>
                    </column>
                </row>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
                <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    export default {
        props: ['open', 'group'],
        data() {
            return {
                params: {
                },
            }
        },
        methods: {
            closeDrawer() {
                this.$emit('close', false);
            },
            save() {
                let params = this.getFormParams(this.params);
                this.$http.post(this.URL.API + 'group/save', params).then((json) => {
                    if (this._messageResponse(json.data)) {
                      this.group.group_name = this.params.group_name;
                      this.group.group_key = this.params.group_key;
                        this.$emit('onSuccess', true);
                        this.closeDrawer();
                    }
                });
            },
            getFormParams(params) {
                let data = new FormData();
                for (let key in params) {
                    let value = params[key];

                    data.append(key, value);
                }
                return data;
            },
            errorAvatar(error) {
                this._notify('error', error.message);
            }
        },
        watch: {
            group: {
                handler(data) {
                    this._resetInitialData();
                    if (!!data) {
                        this.params = {
                            old_group_key: JSON.parse(JSON.stringify(data.group_key)),
                            group_key: data.group_key,
                            group_name: data.group_name,
                        };
                    }
                },
                deep: true,
            }
        }
    }
</script>