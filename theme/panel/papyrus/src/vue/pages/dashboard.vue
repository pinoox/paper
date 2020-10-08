<template>
    <section class="page">
        <div class="container">
            <div class="dashboard">
                <div class="section">
                    <div class="section-title">
                        <h2>آخرین نوشته ها</h2>
                        <div class="more">همه <i class="fa fa-chevron-left"></i></div>
                    </div>
                    <div class="section-content">
                        <swiper class="swiper-articles"
                                v-if="recentArticles!=null && recentArticles.length>0"
                                :options="swiperOpts"
                                :next="false"
                                :prev="false"
                                :pagination="false">
                            <div class="swiper-slide" v-for="i in recentArticles">
                                <img class="thumb" src="@img/placeholder.png" alt="title">
                                <div class="title">{{i.title}}</div>
                            </div>
                        </swiper>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">
                        <h2>آخرین نظرات</h2>
                        <div class="more">همه <i class="fa fa-chevron-left"></i></div>
                    </div>
                    <div class="section-content">
                        <vue-good-table
                                styleClass="vgt-table table"
                                :rtl="true"
                                :columns="columns"
                                :rows="rows"/>
                        <!--<table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>کاربر</th>
                                <th>نظر</th>
                                <th>نوشته</th>
                                <th>تاریخ</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>علی علیزاده</td>
                                <td>مطلب بسیار جذابی بود</td>
                                <td><a href="#">روزه داری در شرایط بحران ویروس کووید19</a></td>
                                <td>شنبه 2 روز پیش</td>
                                <td>منتشر شده</td>
                                <td>
                                    <simple-svg
                                            :src="$parent.icons.delete"
                                                width="22px"
                                                customClassName="icon"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import Swiper from '../components/swiper.vue';

    export default {
        components: {Swiper},
        data() {
            return {
                recentArticles: [
                    {title: 'دَمَر خوابیدن، موجب بهبود عملکرد ریه‌ها در بیماران مبتلا به کرونا می‌شود!'},
                    {title: 'مکملی به نام اینوفولیک : خواص و کارکردها'},
                    {title: 'همه چیز درباره کرونا ویروس و بیماری کرونا'},
                    {title: 'روزه داری در شرایط بحران ویروس کووید19'},
                    {title: 'دَمَر خوابیدن، موجب بهبود عملکرد ریه‌ها در بیماران مبتلا به کرونا می‌شود!'},
                    {title: 'مکملی به نام اینوفولیک : خواص و کارکردها'},
                    {title: 'همه چیز درباره کرونا ویروس و بیماری کرونا'},
                ],
                swiperOpts: {
                    loop: true,
                    slidesPerView: 5,
                    spaceBetween: 50,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false
                    },
                },
                columns: [
                    {
                        label: 'کاربر',
                        field: 'user',
                    },
                    {
                        label: 'نظر',
                        field: 'comment',
                    },
                    {
                        label: 'نوشته',
                        field: 'article',
                    },
                    {
                        label: 'تاریخ',
                        field: 'insert_date',
                    },
                ],
                rows: [
                    {
                        id: 1,
                        user: "علی علیزاده",
                        comment: 'مطلب بسیار جذابی بود',
                        article: 'روزه داری در شرایط بحران ویروس کووید19',
                        insert_date: 'شنبه 2 روز پیش',
                    },
                    {
                        id: 2,
                        user: "رضا رضایی",
                        comment: 'مطلب خوبیه',
                        article: 'روزه داری در شرایط بحران ویروس کووید19',
                        insert_date: 'شنبه 3 روز پیش',
                    },
                ],
            }
        }
    }
</script>