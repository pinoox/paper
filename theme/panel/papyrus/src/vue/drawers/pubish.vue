<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <simple-svg :src="_icons.publish"
                                width="48px"
                                customClassName="icon"/>
                    <div class="text">{{LANG.post.publication_post}}</div>
                </div>
            </div>
            <div class="drawer-content">
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">

                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.title}}</label>
                            <div class="input-group">
                                <input v-model="$parent.editor.title" name="name" type="text"
                                       :placeholder="LANG.post.enter_title" class="input">
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.summary}}</label>
                            <div class="input-group">
                                <textarea v-model="$parent.params.summary" name="summary"
                                          :placeholder="LANG.post.enter_summary"
                                          class="input"></textarea>
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.tags}}</label>
                            <v-select
                                    multiple
                                    taggable
                                    :push-tags="true"
                                    class="input"
                                    dir="rtl"
                                    :options="listTags"
                                    @search="searchTag"
                                    v-model="$parent.params.tags"
                                    :placeholder="LANG.post.add_tag"
                            >
                                <template slot="no-options">
                                    {{LANG.panel.nothing_found}}
                                </template>
                            </v-select>
                            <span class="sub-label">{{LANG.post.help_add_tag}}</span>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.preview_image}}</label>
                            <div class="img-uploader">
                                <img src="https://www.pinoox.com/apps/com_pinoox_hub/theme/blueberry/dist/images/128.de685b7e9f4a0312239b71815fe502ff.png">
                                <span>{{LANG.post.change_preview_image}}</span>
                            </div>
                        </div>
                    </column>
                </row>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.post.close}}</div>
                <div class="btn btn-success" @click="$parent.changeStatus('publish')" v-if="$parent.status === 'draft'">
                    {{LANG.post.publication}}
                </div>
                <div class="btn btn-danger" @click="$parent.changeStatus('draft')" v-if="$parent.status === 'publish'">
                    {{LANG.post.cancel_publication}}
                </div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    export default {
        props: ['open'],
        created() {
            this.getInitTags();
        },
        data() {
            return {
                tags: [],
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
            }
        },
        computed: {
            listTags() {
                return this.tags.map(function (row) {
                    return row['tag_name'];
                });
            },
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
            searchTag(keyword = '', loading) {
                this._delay(() => {
                    keyword = (keyword === undefined) ? null : keyword;
                    if (!!loading) loading(true);
                    this.$http.get(this.URL.API + 'post/searchTags/' + keyword, this.offLoading).then((json) => {
                        this.tags = !!json.data ? json.data : [];
                        if (!!loading) loading(false);
                    });
                }, 350);
            },
            getInitTags() {
                this.$http.get(this.URL.API + 'post/searchTags/').then((json) => {
                    this.tags = !!json.data ? json.data : [];
                });
            },
        },
    }
</script>

<style scoped>

</style>