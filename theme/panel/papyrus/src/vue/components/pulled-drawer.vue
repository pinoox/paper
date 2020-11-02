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
            <div class="drawer-content" v-if="$parent.historyItems.length > 0">
                <simplebar class="simplebar">
                    <div class="posts-list">
                        <div class="item" v-for="(item,index) in $parent.historyItems">
                            <div class="title">{{item.title}}</div>
                            <div class="footer-item">
                                <span class="date">{{item.approx_insert_date}}</span>
                                <span class="status">منتشر شده</span>
                            </div>
                        </div>
                    </div>
                </simplebar>
            </div>
            <div class="drawer-content" v-else>
                <div class="empty">چیزی برای نمایش وجود ندارد</div>
            </div>
        </section>
        <div class="pulled-overlay"></div>
    </section>

</template>

<script>
    export default {
        mounted() {
            this.$parent.getPostHistory();
            $('.page').addClass('pulled');
        },
        destroyed() {
            $('.page').removeClass('pulled');
        },
        methods: {
            close() {
                this.$emit('onClose', true);
            }
        }
    }
</script>