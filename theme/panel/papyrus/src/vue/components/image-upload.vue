<template>
    <div class="upload-image">
            <div class="btn-upload-image" v-if="!image" @click="$refs.imgFile.click()">
                <div class="icon"><i class="fa fa-user"></i></div>
            </div>
            <div v-else class="" @click="$refs.imgFile.click()">
                <figure>
                    <img :src="image">
                </figure>
            </div>
            <button type="button" class="link btn btn-outline-primary"
                    @click="$refs.imgFile.click()">
                upload
            </button>
            <button v-if="!!image" type="button" class="link btn btn-outline-danger"
                    @click="deleteImage()">delete
            </button>
            <input ref="imgFile" type="file"
                   v-show="false"
                   @change="selectImage"
                   accept=".jpg, .jpeg, .png"/>
        </div>
</template>

<script>
    export default {
        data(){
            return {
                image:null,
                params:{

                },
            }
        },
        methods:{
            selectImage() {
                var file = this.$refs.imgFile.files[0];
                this.$delete(this.params, "avatar_id");
                this.createImage(file);
            },
            createImage(file) {
                var reader = new FileReader();
                var vm = this;
                vm.$set(this.params, "image", file);
                reader.onload = (e) => {
                    vm.$set(this, "image", e.target.result);
                };
                reader.readAsDataURL(file)
            },
            deleteImage() {
                this.$delete(this.params, "avatar");
                this.$delete(this.params, "avatar_id");
                this.$set(this, "image", '');
            },
        }
    }
</script>