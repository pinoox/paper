<template>
    <div>
        <ch-drawer custom-class="drawer-wrapper" :location='drawerPosition'
                   :visible.sync='drawerVisibility'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <simple-svg :src="$parent.$parent.icons.publish"
                                width="48px"
                                customClassName="icon"/>
                    <div class="text">انتشار نوشته</div>
                </div>
            </div>
            <div class="drawer-content">
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">

                        <div class="input-wrapper">
                            <label class="input-label">عنوان</label>
                            <div class="input-group">
                                <input name="name" type="text" placeholder="عنوان را وارد کنید" class="input">
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label class="input-label">خلاصه</label>
                            <div class="input-group">
                                <textarea name="summary" placeholder="خلاصه کوتاه را وارد کنید" class="input"></textarea>
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label class="input-label">برچسب ها</label>
                            <v-select
                                    taggable
                                    multiple
                                    label="title"
                                    dir="rtl"
                                    placeholder="برچسب ها را وارد کنید"
                                    :options="options"
                            />
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">تصویر پیش نمایش</label>
                            <div class="img-uploader">
                                <img src="https://files.virgool.io/upload/users/48205/posts/z9ghb5gtejlr/vu5gmjm4gyrl.jpeg">
                                <span>تغییر تصویر پیش نمایش</span>
                            </div>
                        </div>
                    </column>
                </row>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="drawerVisibility=false" class="btn btn-simple">برگشت</div>
                <div class="btn btn-success">انتشار</div>
                <div class="btn btn-danger">لغو انتشار</div>
            </div>

        </ch-drawer>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
                options: [
                    {
                        title: "HTML5",
                        author: {
                            firstName: "Remy",
                            lastName: "Sharp"
                        }
                    }
                ],
            }
        },
        methods: {
            openToolbox() {
                this.drawerVisibility = true;
            },
            handleBeforeClose(next) {
                next();
            },
        },
    }
</script>

<style scoped>

</style>