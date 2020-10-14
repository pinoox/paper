<template>
    <section class="page">
        <div class="write-container">
            <div class="toolbox">
                <div class="items">
                    <div class="item" @click="openDrawer('')">
                        ذخیره
                    </div>
                    <div class="item" @click="openDrawer('')">
                        انتشار
                    </div>
                    <div class="item" @click="openDrawer('')">
                        سئو
                    </div>
                    <div class="item" @click="openDrawer('category')">
                        دسته بندی
                    </div>
                </div>

            </div>
            <div class="toolbar-editor"></div>
            <div class="paper">
                <editor class="content"
                        :values="editor"
                        v-model="params"
                        name="description"
                        placeholder="متن را وارد کنید">
                </editor>
            </div>
        </div>

        <ch-drawer custom-class="drawer-wrapper" :location='drawerPosition'
                   :visible.sync='drawerVisibility'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <simple-svg :src="$parent.icons.publish"
                                width="48px"
                                customClassName="icon"/>
                    <div class="text">انتشار نوشته</div>
                </div>
            </div>
            <div class="drawer-content">
                <category></category>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="drawerVisibility=false" class="btn btn-simple">برگشت</div>
                <div class="btn btn-primary">ذخیره</div>
            </div>

        </ch-drawer>
    </section>
</template>

<script>

    import editor from "../components/editor.vue";
    import category from "../drawers/category.vue";

    export default {
        components: {editor, category},
        created() {
        },
        data() {
            return {
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%',
                editor: {
                    title: 'تست',
                    context: '<p>آزمایش می شود</p>',
                },
                params: {

                    description: `
<h2>تست می شود</h2>
<p style="padding: 20px">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                            طراحان گرافیک
                            است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی
                            تکنولوژی
                            مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه
                            درصد
                            گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را
                            برای
                            طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می
                            توان
                            امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد
                            نیاز
                            شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده
                            قرار
                            گیرد.</p>

 <p style="padding: 20px">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
                            طراحان گرافیک
                            است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی
                            تکنولوژی
                            مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه
                            درصد
                            گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را
                            برای
                            طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می
                            توان
                            امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد
                            نیاز
                            شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده
                            قرار
                            گیرد.</p>`,
                },
            };
        },
        methods: {
            openDrawer(drawerName) {
                this.drawerVisibility = true;
            },
            handleBeforeClose(next) {
                next();
            }
        }
    }
</script>
