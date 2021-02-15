<template>
    <div class="container">
        <div class="post-single" v-if="!isErrPage && !!post">
            <div class="post-header">
                <h1 class="post-title">{{post.title}}</h1>
            </div>
            <div class="post-content paper-article">
                <div v-html="post.context"></div>
            </div>
        </div>
        <error-page v-if="isErrPage"></error-page>
    </div>
</template>

<script>
    import ErrorPage from "./errorPage.vue";

    export default {
        components: {ErrorPage},
        props: ['post_key'],
        created() {
            this.getPage();
        },
        data() {
            return {
                post: null,
                isErrPage: false,
            }
        },
        methods: {
            getPage() {
                if (!this.post_key) {
                    this.isErrPage = true;
                    this._title(this.LANG.front.not_found_page);
                    return;
                }

                this.$http.get(this.URL.API + 'post/getPage/' + this.post_key).then((json) => {
                    this.post = {};
                    if (json.data.status) {
                        this.post = json.data.result;
                        this._title(this.post.title);
                    } else {
                        this.isErrPage = true;
                        this._title(this.LANG.front.not_found_page);
                    }
                });
            },
            getTimePost($date) {
                let parts = $date.split(' ');
                return !!parts[1] ? parts[1] : '';
            },
        },
    }
</script>