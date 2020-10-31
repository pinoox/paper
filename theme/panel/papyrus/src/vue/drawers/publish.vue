<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :blur="false"
                   @open="openDrawer"
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div v-if="$parent.post_type==='post'" class="text">{{LANG.post.post_publication}}</div>
                    <div v-else class="text">{{LANG.post.page_publication}}</div>
                </div>
            </div>
            <div class="drawer-content">
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">

                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.post.title}}</label>
                            <div class="input-group">
                                <input v-model="params.title" name="title" type="text"
                                       :placeholder="LANG.post.enter_title" class="input">
                            </div>
                        </div>

                        <div v-if="$parent.post_type==='page'" class="input-wrapper">
                            <label class="input-label">{{LANG.post.page_address}}</label>
                            <div class="input-group">
                                <input v-model="params.post_key" name="post_key" type="text"
                                       :placeholder="LANG.post.enter_page_address" class="input">
                            </div>
                        </div>

                        <div v-if="$parent.post_type === 'post'" class="input-wrapper">
                            <label class="input-label">{{LANG.post.summary}}</label>
                            <div class="input-group">
                                <textarea v-model="params.summary" name="summary"
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
                                    v-model="params.tags"
                                    :placeholder="LANG.post.add_tag">

                                <template slot="no-options">
                                    {{LANG.panel.nothing_found}}
                                </template>
                            </v-select>
                            <span class="sub-label">{{LANG.post.help_add_tag}}</span>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div v-if="$parent.post_type==='page'" class="input-wrapper">
                            <label class="input-label">{{LANG.post.description}}</label>
                            <div class="input-group">
                                <textarea rows="8" v-model="params.summary" name="summary"
                                          :placeholder="LANG.post.enter_description"
                                          class="input"></textarea>
                            </div>
                        </div>
                        <div v-if="$parent.post_type==='post'">
                            <div class="post-image-select" v-if="isSelectImageBox" @dragover.prevent @drop.prevent
                                 @drop="$parent.handleFileDrop">
                                <div class="input-wrapper">
                                    <label class="input-label">{{LANG.post.preview_image}}</label>
                                    <span class="close" @click="isSelectImageBox = false"><simple-svg
                                            :src="_icons.close"
                                            customClassName="icon stroke"
                                            stroke="#A5B8CE"/></span>
                                    <select-image :items="$parent.images" v-model="params.image"
                                                  @select="isSelectImageBox = false">
                                        <li class="add-item" @click="$parent.selectFile">+</li>
                                    </select-image>
                                </div>
                            </div>

                            <div class="input-wrapper" v-else>
                                <label class="input-label">{{LANG.post.preview_image}}</label>
                                <div class="img-uploader">
                                    <img :src="image">
                                    <span @click="openSelectImage()">{{labelSelectImage}}</span>
                                </div>
                            </div>
                        </div>
                    </column>
                </row>
            </div>
            <div slot='footer' class="drawer-footer" v-if="isSave">
                <div @click="cloneParams()" class="btn btn-simple">{{LANG.post.cancel}}</div>
                <div class="btn btn-primary" @click="saveParams()">
                    {{LANG.post.save_changes}}
                </div>
            </div>
            <div slot='footer' class="drawer-footer" v-else>
                <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.post.close}}</div>
                <div class="btn btn-success" @click="changeStatus('publish')" v-if="$parent.status === 'draft'">
                    {{LANG.post.publication}}
                </div>
                <div class="btn btn-primary" @click="changeStatus('publish')"
                     v-if="$parent.status === 'publish' && !$parent.isSynced">
                    {{LANG.post.sync}}
                </div>
                <div class="btn btn-danger" @click="changeStatus('draft')" v-if="$parent.status === 'publish'">
                    {{LANG.post.cancel_publication}}
                </div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    import SelectImage from '../components/select-image.vue';

    export default {
        props: ['open'],
        components: {SelectImage},
        data() {
            return {
                params: {
                    title: '',
                    post_key: '',
                    summary: '',
                    tags: [],
                    image: null,
                },
                tags: [],
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
                images: [],
                selected: {},
                isSelectImageBox: false,
                offCheckSave: true,
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
            },
            image() {
                return !!this.params.image && this.params.image.link ? this.params.image.link : this._icons.placeholder;
            },
            labelSelectImage() {
                return !!this.$parent.params.image && this.$parent.params.image.link ? this.LANG.post.change_preview_image : this.LANG.post.select_preview_image;

            },
            isSave() {
                return JSON.stringify(this.getParams()) !== JSON.stringify(this.params);
            }
        },
        methods: {
            changeStatus(status) {
                this.$parent.save(status);
            },
            openSelectImage() {
                this.isSelectImageBox = true;
            },
            toggleDrawer() {
                this.drawerOpen = !this.drawerOpen;
            },
            handleBeforeClose(next) {
                this.toggleDrawer();
                this.isSelectImageBox = false;
                this.offCheckSave = true;
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
            getParams() {
                return {
                    title: this.$parent.editor.title,
                    post_key: this.$parent.params.post_key,
                    summary: this.$parent.params.summary,
                    tags: this.$parent.params.tags,
                    image: this.$parent.params.image,
                };
            },
            cloneParams() {
                this.params = this._clone(this.getParams());
            },
            init() {
                this.cloneParams();
                this.$nextTick(() => {
                    this.offCheckSave = false;
                });
            },
            saveParams() {
                let paramsParent = this._clone(this.$parent.params)
                this.$parent.editor.title = this.params.title;
                this.$parent.params.post_key = this.params.post_key;
                this.$parent.params.summary = this.params.summary;
                this.$parent.params.tags = this._clone(this.params.tags);
                this.$parent.params.image = this._clone(this.params.image);
                this.$parent.save(this.$parent.status).then((data) => {
                    if (data.status)
                        this.$parent.isSynced = true;
                    else
                        this.$parent.params = paramsParent;
                });
            },
            openDrawer() {
                let title = this.$parent.editor.title;
                let post_key = this.$parent.params.post_key;
                post_key = !!post_key ? post_key : title.replaceAll(' ', '-');
                this.$parent.params.post_key = post_key;
                this.init();
                this.getInitTags();
            }
        },
    }
</script>