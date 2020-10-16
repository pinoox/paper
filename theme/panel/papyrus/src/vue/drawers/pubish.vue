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
                    <div class="text">انتشار نوشته</div>
                </div>
            </div>
            <div class="drawer-content">
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">

                        <div class="input-wrapper">
                            <label class="input-label">عنوان</label>
                            <div class="input-group">
                                <input v-model="$parent.editor.title" name="name" type="text"
                                       placeholder="عنوان را وارد کنید" class="input">
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label class="input-label">خلاصه</label>
                            <div class="input-group">
                                <textarea v-model="$parent.params.summary" name="summary" placeholder="خلاصه کوتاه را وارد کنید"
                                          class="input"></textarea>
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <label class="input-label">برچسب ها</label>
                            <v-select
                                    multiple
                                    taggable
                                    class="input"
                                    dir="rtl"
                                    label="tag_name"
                                    :options="tags"
                                    @search="searchTag"
                                    v-model="$parent.params.tags"
                                    placeholder="برچسب ها را وارد کنید"
                            >
                                <template slot="no-options">
                                    {{LANG.panel.nothing_found}}
                                </template>
                            </v-select>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">تصویر پیش نمایش</label>
                            <div class="img-uploader">
                                <img src="https://www.pinoox.com/apps/com_pinoox_hub/theme/blueberry/dist/images/128.de685b7e9f4a0312239b71815fe502ff.png">
                                <span>تغییر تصویر پیش نمایش</span>
                            </div>
                        </div>
                    </column>
                </row>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="toggleDrawer()" class="btn btn-simple">برگشت</div>
                <div class="btn btn-success" @click="$parent.changeStatus('publish')" v-if="$parent.status === 'draft'">انتشار</div>
                <div class="btn btn-danger" @click="$parent.changeStatus('draft')" v-if="$parent.status === 'publish'">لغو انتشار</div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    export default {
        props: ['open'],
        created(){
          this.getInitTags();
        },
        data() {
            return {
                tags:[],
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
        computed: {
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
            }
        },
    }
</script>

<style scoped>

</style>