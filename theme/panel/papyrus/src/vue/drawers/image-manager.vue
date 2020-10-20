<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">تصاویر</div>
                    <div class="subtext">تصویر مورد نظر را انتخاب کنید</div>
                </div>
            </div>
            <div class="drawer-content" @dragover.prevent @drop.prevent @drop="$parent.handleFileDrop">
                    <select-image :items="$parent.images" v-model="selected" @select="selectImage()" multiple>
                        <li class="add-item" @click="$parent.selectFile">+</li>
                    </select-image>
            </div>
            <div slot='footer' class="drawer-footer" >
                <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.post.close}}</div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    import SelectImage from '../components/select-image.vue';
    export default {
        components:{SelectImage},
        props: ['open'],
        data() {
            return {
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
                selected:null,
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
            selectImage()
            {
                if(this.$parent.stateImageManager === 'publish')
                    this.editImage();
            },
            editImage()
            {
              this.$parent.image =  this.selected;
              this.$parent.stateImageManager = false;
            },
            hash_id() {
                return this.$parent.params.hash_id;
            },
            toggleDrawer() {
                this.drawerOpen = !this.drawerOpen;
            },
            handleBeforeClose(next) {
                this.toggleDrawer();
                next();
            },
        },
    }
</script>