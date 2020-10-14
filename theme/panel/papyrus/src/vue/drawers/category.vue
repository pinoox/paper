<template>
    <section class="drawer-category">

        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">دسته بندی</div>
                    <div class="subtext">نوشته های خود را می‌توانید دسته بندی کنید</div>
                </div>
            </div>
            <div class="drawer-content">

                <div class="row">
                    <div class="add-category">
                        <input class="input" type="text" placeholder="نام دسته جدید را بنویسید">
                    </div>
                    <vue-nestable class="nestable nestable-category" v-model="nestableItems" :rtl="true"
                                  :maxDepth="10">
                        <div slot="placeholder">
                            <b>لیست خالی است</b>
                            <p>می توانید با + آیتم جدید اضافه کنید</p>
                        </div>
                        <vue-nestable-handle
                                slot-scope="{ item }"
                                :item="item">
                            {{ item.text }}
                        </vue-nestable-handle>
                    </vue-nestable>
                </div>

            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="toggleDrawer()" class="btn btn-simple">برگشت</div>
                <div class="btn btn-primary">ذخیره</div>
            </div>

        </ch-drawer>

    </section>
</template>

<script>
    export default {
        props: ['open'],
        data() {
            return {
                drawerPosition: 'bottom',
                drawerArea: '90%',
                nestableItems: [
                    {
                        id: 0,
                        text: 'اصلی',
                    },
                    {
                        id: 1,
                        text: 'مایکروسافت',
                        children: [
                            {
                                id: 2,
                                text: 'اخبار'
                            },
                            {
                                id: 22,
                                text: 'رویدادها'
                            },
                            {
                                id: 222,
                                text: 'محصولات'
                            },
                        ]
                    }, {
                        id: 3,
                        text: 'فناوری'
                    }
                ]
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
            }
        }
    }
</script>
