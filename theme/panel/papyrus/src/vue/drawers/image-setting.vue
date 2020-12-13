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
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">{{LANG.post.images}}</div>
                    <div class="subtext">{{LANG.post.images_management}}</div>
                </div>
            </div>
            <div class="drawer-content" @dragover.prevent @drop.prevent @drop="handleFileDrop">
                <select-image file_id="id" :items="images" v-model="selected">
                    <li class="add-item" @click="selectFile">+</li>
                </select-image>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
                <div v-if="!!selected" @click="addImage()" class="btn btn-primary">{{LANG.post.add}}</div>
                <div v-if="!!selected" @click="deleteImage()" class="btn btn-danger right">{{LANG.panel.delete}}</div>
            </div>
        </ch-drawer>
        <input v-show="false" ref="uploadImage" type="file" name="file-input" @change="handleFileInput" multiple>
    </section>
</template>

<script>
    import SelectImage from '../components/select-image.vue';

    export default {
        components: {SelectImage},
        props: ['open'],
        data() {
            return {
                selected: null,
                images: [],
            }
        },
        methods: {
            getImages() {
                return this.$http.get(this.URL.API + 'setting/getImages/').then((json) => {
                    this.images = json.data;
                })
            },
            deleteImage() {
                this._confirm(this.LANG.post.confirm_delete_images, () => {
                    this.$http.get(this.URL.API + 'setting/deleteImage/' + this.selected.id).then((json) => {
                        this.images = this.images.filter((item) => {
                            return item.id !== this.selected.id;
                        });
                        this.selected = null;
                        this.$parent.params[this.$parent.setting.key] = null;
                    });
                });
            },
            addImage() {
                this.$parent.params[this.$parent.setting.key] = this.selected.img;
                this.closeDrawer();
            },
            findImageSelected() {
                let img = this.$parent.params[this.$parent.setting.key];
                let imageSelected = this.images.find((item) => {
                    return item.img === img;
                });
                this.selected = !!imageSelected ? imageSelected : null;
            },
            openDrawer() {
                this._resetInitialData();
                this.getImages()
                    .then(() => {
                        this.findImageSelected();
                    });
            },
            closeDrawer() {
                this.$emit('close', false);
            },
            selectFile() {
                this.$refs.uploadImage.click();
            },
            handleFileInput(e) {
                let files = e.target.files;
                if (!files) return;
                ([...files]).forEach(f => {
                    this.uploadImage(f);
                });
            },
            handleFileDrop(e) {
                let droppedFiles = e.dataTransfer.files;
                if (!droppedFiles) return;
                ([...droppedFiles]).forEach(f => {
                    this.uploadImage(f);
                });
            },
            uploadImage(file) {
                let data = new FormData();
                data.append('image', file);
                this.$http.post(this.URL.API + 'setting/uploadImage/', data).then((json) => {
                    if (json.data.status) {
                        this.images.unshift(json.data.result);
                    } else {
                        this._notify('error', json.data.result);
                    }
                }).catch(function (thrown) {
                });
            },
        },
    }
</script>