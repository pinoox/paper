<template>
    <section class="page">
        <div class="write-container">
            <div class="toolbox">
                <div class="items">
                    <div class="item">
                        ذخیره
                    </div>
                    <div class="item">
                        انتشار
                    </div>
                    <div class="item">
                        سئو
                    </div>
                    <div class="item" @click="openDrawer('category')">
                        دسته بندی
                    </div>
                </div>

            </div>
            <div class="toolbar-editor"></div>
            <div :style="{'width':paperSize + '%', 'margin-top':marginTop}" class="paper">
                <editor class="content"
                        :values="editor"
                        v-model="params"
                        name="description"
                        placeholder="متن را وارد کنید">
                </editor>
            </div>
            <div class="statusbar">
                <div class="item">
                    <div class="counter">15 کلمه</div>
                </div>
                <div class="item">
                    <span class="label no-select">اندازه صفحه</span>
                    <div class="zoom in" @click="resizePaper('in')">
                        <simple-svg :src="$parent.icons.zoomIn"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                    </div>
                    <div class="zoom out" @click="resizePaper('out')">
                        <simple-svg :src="$parent.icons.zoomOut"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                    </div>
                </div>

            </div>
        </div>

        <category @onClose="drawerName=null" :open="drawerName==='category'"></category>

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
                paperSize: 75,
                marginTop: '64px',
                drawerName: false,
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
                this.drawerName = drawerName;
            },
            resizePaper(zoom) {
                if ((zoom === 'in' && this.paperSize < 100)) this.paperSize += 5;
                if ((zoom === 'out' && this.paperSize > 50)) this.paperSize -= 5;
                this.marginTop = this.paperSize >= 100 ? 0 : '64px';
            },
        }
    }
</script>
