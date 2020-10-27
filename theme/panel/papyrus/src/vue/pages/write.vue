<template>
    <section class="page">
        <div class="menubar">
            <div class="items">
                <div class="item" @click="save()">
                    {{LANG.post.save}}
                </div>
                <div @click="openDrawer('publish')" class="item">
                    {{LANG.post.publication}}
                </div>
                <div class="item" @click="openDrawer('category')">
                    {{LANG.post.category}} {{params.category!=null ? '('+params.category.cat_name+')' : ''}}
                </div>
                <div class="item" @click="drawerName = 'image-manager'">
                    {{LANG.post.images}}
                </div>
                <div class="item" @click="drawerName = 'settings'">
                    {{LANG.post.settings}}
                </div>
                <div class="item" @click="openFullscreen()">
                    {{LANG.post.fullscreen}}
                </div>
            </div>
        </div>
        <div id="write" class="write-container">
            <editor class="content"
                    :values="editor"
                    :status="status"
                    :message="message"
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
        <category @onClose="drawerName=null" :open="drawerName==='category'"
                  @onSelected="setCategory"></category>
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
                isSave: false,
                isOpenFullscreen: false,
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
                    category: null,
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
                message: null,
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
                .then(() => {
                    this.$watch('params', () => {
                        this.isSave = true;
                    }, {
                        deep: true,
                    })
                });
        },
        methods: {
            getInitData() {
                this.getSettings();
                if (!!this.post_id)
                    return this.getPost();
                else
                    return this.getHashId();
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
                        this._notify('error', message);
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
            deleteFromImages(file) {
                return this.$http.post(this.URL.API + 'post/deleteImage', {
                    file_id: file.file_id,
                    hash_id: this.params.hash_id,
                }).then((json) => {
                    if (json.data.status) {
                        this.images = this.images.filter(function (image) {
                            return image.file_id !== file.file_id;
                        });
                    } else {
                        let message = json.data.result;
                        this._notify('error', message);
                    }

                });
            },
            openDrawer(drawerName) {
                if (drawerName === 'publish')
                    this.setEditorFields(this.params.editor);

                this.drawerName = drawerName;
            },
            getSettings() {
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
                this.message = PINOOX.LANG.panel.saving;

                return this.$http.post(this.URL.API + 'post/save', params).then((json) => {
                    this.message = PINOOX.LANG.panel.saved + ' (' + this._timeNow() + ')';

                    if (!json.data.status) {
                        this._notify('error', json.data.message, 'app');
                    } else if (json.data.status) {
                        this.isSave = false;
                        if (this.params.status !== this.status) {
                            this.status = this.params.status;
                        }
                        if (!this.post_id)
                            this._routerReplace({name: 'write', params: {post_id: json.data.result}});
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
                        let image = !!value.file_id ? value.file_id : '';
                        formData.append('image', image);
                    } else if (key === 'editor') {
                        formData.append('title', value.title);
                        formData.append('context', value.context);
                    } else {
                        formData.append(key, value);
                    }
                }

                return formData;
            },
            setCategory(val) {
                this.params.category = val;
            },
            openFullscreen() {
                let ckBody = $('#write').find('.ck-body-wrapper');

                if (!ckBody || ckBody.length <= 0) {
                    ckBody = $('.ck-body-wrapper');
                    ckBody.css('position', 'absolute');
                    $('#write').append(ckBody);
                }

                let el = document.getElementById('write');
                if (el.requestFullscreen) {
                    el.requestFullscreen();
                } else if (el.webkitRequestFullscreen) { /* Safari */
                    el.webkitRequestFullscreen();
                } else if (el.msRequestFullscreen) { /* IE11 */
                    el.msRequestFullscreen();
                }
            },
            closeFullscreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) { /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { /* IE11 */
                    document.msExitFullscreen();
                }
            },
            checkFullscreen() {
                let vm = this;
                this.$nextTick(() => {
                    let el = document.getElementById('write');
                    el.addEventListener('fullscreenchange', (event) => {
                        vm.isOpenFullscreen = !vm.isOpenFullscreen;
                        $('#write').toggleClass('fullscreen');
                    });
                });
            }
        },
        mounted() {
            this.checkFullscreen();
        },
        watch: {
            drawerName() {
                $('.app-container').toggleClass('drawer--blur');
            }
        }
    }
</script>