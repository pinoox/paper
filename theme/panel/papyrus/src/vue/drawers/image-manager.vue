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
                    <div class="text">{{LANG.post.images}}</div>
                    <div class="subtext">{{LANG.post.images_management}}</div>
                </div>
            </div>
            <div class="drawer-content" @dragover.prevent @drop.prevent @drop="$parent.handleFileDrop">
                <select-image :items="$parent.images" v-model="selected"
                              @select="selectImage()"
                              multiple>
                    <li class="add-item" @click="$parent.selectFile">+</li>
                </select-image>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.post.close}}</div>
                <div v-if="!!selected" @click="addImages()" class="btn btn-primary">{{LANG.post.add_to_post}}</div>
                <div v-if="!!selected" @click="deleteImages()" class="btn btn-danger right">{{LANG.post.delete}}</div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    import SelectImage from '../components/select-image.vue';
    import {mapMutations} from 'vuex';

    export default {
        components: {SelectImage},
        props: ['open'],
        data() {
            return {
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
                selected: null,
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
            },
        },
        methods: {
            ...mapMutations(['addImageEditor']),
            ...mapMutations(['deleteImageEditor']),
            selectImage() {
                if (this.$parent.stateImageManager === 'publish')
                    this.editImage();
            },
            addImages() {
                for (let image of this.selected) {
                    this.addImageEditor(image.link);
                }
                this.toggleDrawer();
            },
            addImage(image)
            {
                this.addImageEditor(image.link);
                this.toggleDrawer();
            },
            deleteImages() {
                this._confirm(this.LANG.post.confirm_delete_images, () => {
                    for(const image of this.selected)
                    {
                        this.$parent.deleteFromImages(image).then(()=>{
                            this.deleteImageEditor(image.link);
                            this.$parent.deleteImageFeature(image);
                        });
                    }
                    this.selected = null;
                });
            },
            editImage() {
                this.$parent.image = this.selected;
                this.$parent.stateImageManager = false;
            },
            hash_id() {
                return this.$parent.params.hash_id;
            },
            toggleDrawer() {
                this.drawerOpen = !this.drawerOpen;
            },
            handleBeforeClose(next) {
                this.selected = null;
                this.toggleDrawer();
                next();
            },
        },
    }
</script>