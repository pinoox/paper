<template>
    <section class="pulled-drawer">
        <section class="drawer-inside">
            <div class="close-drawer" @click="close()">
                <div class="close"><i class="fa fa-chevron-right"></i> {{LANG.panel.close}}</div>
            </div>
            <div class="drawer-header">
                <div class="title">تاریخچه تغییرات</div>
                <div class="clear">حذف همه</div>
            </div>
            <div class="drawer-content">
                <simplebar class="simplebar" v-if="true">
                    <div class="posts-list">
                        <div class="item">
                            <div class="title">بهترین داروهای تقویت سیستم ایمنی بدن</div>
                            <div class="footer-item">
                                <span class="date">2 روز قبل</span>
                                <span class="status">منتشر شده</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="title">بهترین داروهای تقویت سیستم ایمنی بدن</div>
                            <div class="footer-item">
                                <span class="date">2 روز قبل</span>
                                <span class="status">منتشر شده</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="title">بهترین داروهای تقویت سیستم ایمنی بدن</div>
                            <div class="footer-item">
                                <span class="date">2 روز قبل</span>
                                <span class="status">منتشر شده</span>
                            </div>
                        </div>
                        <div class="item">
                            <div class="title">بهترین داروهای تقویت سیستم ایمنی بدن</div>
                            <div class="footer-item">
                                <span class="date">2 روز قبل</span>
                                <span class="status">منتشر شده</span>
                            </div>
                        </div>
                    </div>
                </simplebar>
                <div v-else>
                    <div class="empty">چیزی برای نمایش وجود ندارد</div>
                </div>

            </div>
        </section>
        <div class="pulled-overlay"></div>
    </section>

</template>

<script>
    export default {
        mounted() {
            document.getElementsByClassName('page')[0].classList.add("pulled");
        },
        destroyed() {
            document.getElementsByClassName('page')[0].classList.remove("pulled");
        },
        methods: {
            close() {
                this.$emit('onClose', true);
            }
        }
    }
</script>

<style lang="less">

</style>