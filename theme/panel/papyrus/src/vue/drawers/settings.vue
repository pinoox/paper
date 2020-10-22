<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   location="bottom"
                   :visible="open"
                   :destroy-on-close="true"
                   area="90%"
                   @open="openDrawer()"
                   @close="closeDrawer()">
            <div slot="header" class="drawer-header">
                <div class="title">
                    <div class="text">{{LANG.post.settings}}</div>
                    <div class="subtext">{{LANG.post.page_setting_management}}</div>
                </div>
            </div>
            <div class="drawer-content">
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">

                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.auto_save}}</label>
                            <div class="input-group">
                                <toggle-button v-model="params.autosave.status"
                                               :width="70"
                                               :labels="{checked: LANG.post.active, unchecked: LANG.post.inactive}"/>
                            </div>
                            <span class="sub-label">{{LANG.post.help_auto_save}}</span>
                        </div>
                        <div class="input-wrapper" v-if="params.autosave.status">
                            <label class="input-label">{{LANG.post.auto_save_time}}</label>
                            <div class="input-group">
                                <input v-model="params.autosave.time" name="name" type="text"
                                       :placeholder="LANG.post.enter_auto_save_time" class="input">
                            </div>
                            <span class="sub-label">{{LANG.post.help_auto_save_time}}</span>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                    </column>
                </row>
            </div>
            <div slot="footer" class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.post.close}}</div>
                <div class="btn btn-primary" @click="saveSetting()">
                    {{LANG.post.save}}
                </div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>

    export default {
        props:['open'],
        data() {
            return {
                params:{
                    autosave:{
                        time:10,
                        status:false,
                    },
                }
            }
        },
        methods: {
            openDrawer() {
                this.$set(this,'params',this._clone(this.$parent.settings));
            },
            closeDrawer() {
                this.$emit('close', false);
            },
            saveSetting(){
                this.$http.post(this.URL.API + 'post/saveSettings',this.params).then((json)=>{
                   if(this._statusResponse(json.data))
                   {
                       this.$set(this.$parent,'settings',this._clone(this.params));
                       this.close();
                   }
                });
            }
        },
    }
</script>