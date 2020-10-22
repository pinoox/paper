<template>
    <section class="page">
        <div class="float" v-if="false">
            <img src="@img/rocket.png"
                 height="100px" width="100px" alt="bubbles">
        </div>

        <div class="write-container">
            <div class="toolbox">
                <div class="items">
                    <div class="item" @click="save()">
                        {{LANG.post.save}}
                    </div>
                    <div @click="openDrawer('publish')" class="item">
                        {{LANG.post.publication}}
                    </div>
                    <div class="item" @click="openDrawer('category')">
                        {{LANG.post.category}}
                    </div>
                    <div class="item" @click="drawerName = 'image-manager'">
                        {{LANG.post.images}}
                    </div>
                    <div class="item" @click="drawerName = 'settings'">
                        {{LANG.post.settings}}
                    </div>
                </div>
            </div>
            <editor class="content"
                    :values="editor"
                    :status="status"
                    v-model="params.editor"
                    :autosave="settings.autosave.status"
                    :autosave-time="settings.autosave.time"
                    @save="save()"
                    name="description"
                    :title-placeholder="LANG.post.enter_title"
                    :placeholder="LANG.post.enter_context">
            </editor>
        </div>
        <publish @onClose="drawerName=null" :open="drawerName==='publish'"></publish>
        <category @onClose="drawerName=null" :open="drawerName==='category'"></category>
        <image-manager @onClose="drawerName = null" :open="drawerName === 'image-manager'"></image-manager>
        <settings @close="drawerName = null" :open="drawerName === 'settings'"></settings>
        <input v-show="false" ref="file" type="file" name="file-input" @change="handleFileInput" multiple>
    </section>
</template>

<script>

    import Editor from "../components/editor.vue";
    import Publish from "../drawers/publish.vue";
    import Category from "../drawers/category.vue";
    import ImageManager from "../drawers/image-manager.vue";
    import Settings from "../drawers/settings.vue";

    export default {
        name: 'write',
        props: ['post_id'],
        components: {Editor, Category, Publish, ImageManager, Settings},
        beforeRouteLeave(to, from, next) {
            // this._confirm('confirm?', () => {
            next();
            // });
        },
        data() {
            return {
                isShowRocket: false,
                post: {},
                editor: {
                    title: '',
                    context: '',
                },
                params: {
                    editor: {},
                    summary: '',
                    status: 'draft',
                    tags: [],
                    hash_id: null,
                    image: null,
                },

                images: [],
                status: 'draft',
                settings: {
                    autosave: {
                        status: false,
                        time: 10,
                    },
                },
                drawerName: false,

            };
        },
        created() {
            this.getInitData()
                .then(() => {
                    return this.getImages();
                })
                .then(() => {
                    this.getImage();
                })
        },
        methods: {
            getInitData() {
                this.getSettings();
                if (!!this.post_id)
                    return this.getPost();
                else
                    return this.getHashId();
            },
            showRocket(status) {
                if (this.isShowRocket || status !== 'publish')
                    return;

                this.isShowRocket = true;
                this._delay(() => {
                    this.isShowRocket = false;
                }, 1500);
            },
            handleFileDrop(e) {
                let droppedFiles = e.dataTransfer.files;
                if (!droppedFiles) return;
                ([...droppedFiles]).forEach(f => {
                    this.uploadFiles(f);
                });
            },
            selectFile() {
                this.$refs.file.click();
            },
            handleFileInput(e) {
                let files = e.target.files;
                if (!files) return;
                ([...files]).forEach(f => {
                    this.uploadFiles(f);
                });
            },
            uploadFiles(file) {
                let data = new FormData();
                data.append('upload', file);
                data.append('hash_id', this.params.hash_id);
                let xhr = this.$http.CancelToken.source();
                this.$http.post(this.URL.API + 'post/imageUpload/', data, {
                    cancelToken: xhr.token,
                    onUploadProgress: (progressEvent) => {
                        let percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                }).then((json) => {
                    if (!json.data.error) {
                        this.pushToImages(json.data.file);
                    } else {
                        let message = json.data.error.message;
                        this._notify('error', this.message);
                    }
                }).catch(function (thrown) {
                });
            },
            pushToImages(file) {
                this.images.push({
                    file_id: file.file_id,
                    link: file.link,
                })
            },
            openDrawer(drawerName) {
                if (drawerName === 'publish')
                    this.setEditorFields(this.params.editor);

                this.drawerName = drawerName;
            },
            getSettings() {
                let hash_id = this.params.hash_id;
                return this.$http.get(this.URL.API + 'post/getSettings/').then((json) => {
                    this.settings = !!json.data ? json.data : this.settings;
                });
            },
            getImages() {
                let hash_id = this.params.hash_id;
                return this.$http.get(this.URL.API + 'post/getImages/' + hash_id).then((json) => {
                    this.images = !!json.data ? json.data : [];
                });
            },
            setEditorFields(data) {
                this.editor = {
                    title: !!data['title'] ? data['title'] : '',
                    context: !!data['context'] ? data['context'] : '',
                };
            },
            getPost() {
                return this.$http.get(this.URL.API + 'post/get/' + this.post_id).then((json) => {
                    this.post = json.data;
                    this.params.post_id = this.post_id;
                    this.setEditorFields(json.data);
                    this.params.tags = this.createTags(json.data.tags);
                    this.params.summary = !!json.data.summary ? json.data.summary : '';
                    this.params.status = json.data.status;
                    this.status = json.data.status;
                    this.params.hash_id = json.data.hash_id;
                });
            },
            getImage() {
                if (!this.post.image_id)
                    return;

                for (const image of this.images) {
                    if (image.file_id === this.post.image_id) {
                        this.$set(this.params, 'image', image);
                        break;
                    }
                }
            },
            getHashId() {
                return this.$http.get(this.URL.API + 'post/getHashId/').then((json) => {
                    this.params.hash_id = json.data.result;
                });
            },
            changeStatus(status) {
                this.drawerName = null;
                this.params.status = status;
                this.save();
            },
            save() {
                let params = this.getFormData(this.params);

                return this.$http.post(this.URL.API + 'post/save', params).then((json) => {
                    if (this._messageResponse(json.data)) {

                        if (this.params.status !== this.status) {
                            this.status = this.params.status;
                            this.showRocket(this.status);
                        }

                        if (!this.post_id)
                            this._routerReplace({name: 'post-edit', params: {post_id: json.data.result}});
                    } else {
                        this.params.status = this.status;
                    }
                }).catch(function (error) {
                    this.params.status = this.status;
                });
            },
            createTags(tags) {
                tags = !!tags ? tags : [];
                return tags.map(function (tag) {
                    return (typeof tag === "object") ? tag['tag_name'] : tag;
                });
            },
            addFormTags(tags, formData) {
                for (let index in tags) {
                    if (!tags[index] === undefined || !tags[index] === null)
                        continue;
                    let tag = tags[index];
                    tag = (typeof tag === "object") ? tag.tag_name : tag;
                    formData.append('tags[' + index + ']', tag);
                }
            },
            getFormData(params) {
                let formData = new FormData();
                for (let key in params) {
                    let value = (params[key] !== null) ? params[key] : '';

                    if (key === 'tags') {
                        this.addFormTags(params[key], formData);
                    } else if (key === 'image') {
                        formData.append('image', value.file_id);
                    } else if (key === 'editor') {
                        formData.append('title', value.title);
                        formData.append('context', value.context);
                    } else {
                        formData.append(key, value);
                    }
                }

                return formData;
            },
        },
        watch: {
            'params.hash_id': {
                handler() {

                }
            },
        }
    }
</script>

<style>
    .float {
        position: fixed;
        -webkit-animation: floatBubble 1.5s infinite normal ease-in;
        animation: floatBubble 1.5s infinite normal ease-in;
        right: 120px;
        z-index: 9999999;
    }

    @-webkit-keyframes floatBubble {
        0% {
            top: calc(100% - 100px);
        }
        100% {
            top: 0;
        }
    }

    @keyframes floatBubble {
        0% {
            top: calc(100% - 0px);
        }
        100% {
            top: -10%;
        }
    }
</style>