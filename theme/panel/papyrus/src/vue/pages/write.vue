<template>
    <section class="page">
        <div class="write-container">
            <div class="toolbox">
                <div class="item" @click="openToolbox('category')">
                    <simple-svg :src="$parent.icons.category"
                                width="22px"
                                customClassName="icon"/>
                    <span class="label">دسته بندی</span>
                </div>
                <div class="item" @click="openToolbox('seo')">
                    <simple-svg :src="$parent.icons.seo"
                                width="22px"
                                customClassName="icon"/>
                    <span class="label">سئو</span>
                </div>
                <div class="item" @click="openToolbox('publish')">
                    <simple-svg :src="$parent.icons.publish"
                                width="22px"
                                customClassName="icon"/>
                    <span class="label">انتشار</span>
                </div>
            </div>
            <div class="paper">
                <div class="content">
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
                </div>
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
                    <div class="text">انتشار نوشته</div></div>
            </div>
            <div class="drawer-content">
                <h1> Lorem ipsum dolor sit amet, consectetur adipisicing elit. A adipisci aperiam commodi deleniti eius
                    error, explicabo incidunt, ipsam itaque iusto natus necessitatibus, vero vitae voluptate voluptates.
                    Debitis eligendi minima officiis.</h1>

            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="drawerVisibility=false" class="btn btn-simple">برگشت</div>
                <div class="btn btn-primary">ذخیره</div>
            </div>

        </ch-drawer>
    </section>
</template>

<script>

    export default {
        data() {
            return {
                drawerPosition: 'bottom',
                drawerVisibility: false,
                drawerArea: '90%'
            };
        },
        methods: {
            openToolbox() {
                this.drawerVisibility = true;
            },
            handleBeforeClose(next) {
                next();
            }
        }
    }
</script>
