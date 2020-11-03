<template>
    <section class="pulled-drawer animate__animated animate__fadeInRight animate__faster">
        <section class="drawer-inside">
            <div class="close-drawer" @click="close()">
                <div class="close"><i class="fa fa-chevron-right"></i> {{LANG.panel.close}}</div>
            </div>
            <div class="drawer-header">
                <div class="title">{{LANG.post.change_history}}</div>
                <div v-if="!!historyItems && historyItems.length > 0 " class="clear" @click="deleteAllHistory()">{{LANG.post.delete_all}}</div>
            </div>
            <div class="drawer-content" v-if="historyItems.length > 0">
                <simplebar class="simplebar">
                    <div class="posts-list">
                        <div class="item" v-for="(item,index) in historyItems" @click="preview(item)">
                            <div class="title">{{item.title}}</div>
                            <div class="footer-item">
                                <span class="date">{{item.approx_insert_date}}</span>
                                <span class="status">{{printStatus(item)}}</span>
                            </div>
                        </div>
                    </div>
                </simplebar>
            </div>
            <div class="drawer-content" v-else>
                <div class="empty">{{LANG.panel.empty_table}}</div>
            </div>
        </section>
        <div class="pulled-overlay"></div>
    </section>

</template>

<script>
    export default {
        mounted() {
            this.getPostHistory();
            $('.page').addClass('pulled');
        },
        destroyed() {
            $('.page').removeClass('pulled');
        },
        computed: {
            historyItems: {
                get() {
                    return this.$parent.historyItems;
                },
                set(val) {
                    this.$parent.historyItems = val;
                }
            }
        },
        methods: {
            printStatus(item) {
                let status = !!item.status ? item.status : 'draft';
                return this.LANG.post.status[status];
            },
            close() {
                this.$emit('onClose', true);
            },
            preview(item) {
                this.$parent.preview = item;
                this.$parent.drawerName = 'preview';
            },
            deleteAllHistory() {
                this._confirm(PINOOX.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.get(this.URL.API + 'post/deleteAllHistory/' + this.$parent.post_id).then((json) => {
                        this.historyItems = [];
                    });
                });
            },
            getPostHistory() {
                if (!this.$parent.post_id)
                    return;

                return this.$http.get(this.URL.API + 'post/getPostHistory/' + this.$parent.post_id).then((json) => {
                    this.historyItems = !!json.data ? json.data : [];
                });
            },
        }
    }
</script>