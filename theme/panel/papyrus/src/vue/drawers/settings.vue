<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   location="bottom"
                   :visible="open"
                   :destroy-on-close="true"
                   :blur="false"
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
                            <label class="input-label">{{LANG.post.comments_section}}</label>
                            <div class="input-group">
                                <toggle-button v-model="commentStatus"
                                               :width="70"
                                               :sync="true"
                                               :labels="{checked: LANG.post.opened, unchecked: LANG.post.closed}"/>
                            </div>
                            <span class="sub-label">{{LANG.post.help_comments_section}}</span>
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
        props: ['open'],
        data() {
            return {
                params: {},
            }
        },
        computed: {
            commentStatus: {
                get() {
                    return this.params.comment_status === 'open';
                },
                set(status) {
                    status = status ? 'open' : 'closed';
                    this.params.comment_status = status;
                }
            }
        },
        methods: {
            openDrawer() {
                this.$set(this, 'params', this._clone(this.$parent.settings));
            },
            closeDrawer() {
                this.$emit('close', false);
            },
            saveSetting() {
                this.params.post_id = this.$parent.post_id;
                this.$http.post(this.URL.API + 'post/saveSettings', this.params).then((json) => {
                    if (this._statusResponse(json.data)) {
                        this.$set(this.$parent, 'settings', this._clone(this.params));
                        this.closeDrawer();
                    }
                });
            }
        },
    }
</script>