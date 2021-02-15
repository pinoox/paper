<template>
    <div class="container">
        <div class="post-single" v-if="!isErrPage && !!post">
            <div class="post-header">
                <div class="post-meta">
                    <time :datetime="post.publish_date">{{post.approx_date}}</time>
                    <div class="post-tags" v-if="post.tags!=null && post.tags.length > 0">
                        <router-link :to="{name:'tag',params:{tag_name:t.tag_name}}" class="item"
                                     v-for="t in post.tags"> #{{t.tag_name}}
                        </router-link>
                    </div>
                </div>
                <h1 class="post-title">{{post.title}}</h1>
            </div>
            <div class="post-image">
                <img :src="post.image" alt="post.title">
            </div>


            <div class="post-content paper-article">
                <div v-html="post.context"></div>
            </div>

            <div class="post-footer">
                <div class="post-share">
                    <div class="caption">{{LANG.front.share}}</div>
                    <div class="social-links">
                        <a class="item" :href="_share(post.url,'facebook')"><i
                                class="fab fa-facebook facebook-color"></i></a>
                        <a class="item" :href="_share(post.url,'telegram')"><i
                                class="fab fa-telegram telegram-color"></i></a>
                        <a class="item" :href="_share(post.url,'whatsapp')"><i
                                class="fab fa-whatsapp whatsapp-color"></i></a>
                        <a class="item" :href="_share(post.url,'linkedin')"><i
                                class="fab fa-linkedin linkedin-color"></i></a>
                        <a class="item" :href="_share(post.url,'twitter')"><i
                                class="fab fa-twitter twitter-color"></i></a>
                    </div>
                </div>

                <div class="post-author">
                    <div class="author-image">
                        <img :src="post.avatar" :alt="post.full_name">
                    </div>
                    <div class="author-info">
                        <router-link :to="{name:'profile',params:{username:post.username}}" class="info-name">
                            {{post.full_name}}
                        </router-link>
                        <p v-if="false" class="info-bio"></p>
                        <div v-if="false" class="info-social">
                            <a href="">{{LANG.front.telegram}}</a>
                            <a href="">{{LANG.front.facebook}}</a>
                            <a href="">{{LANG.front.instagram}}</a>
                            <a href="">{{LANG.front.telegram}}</a>
                            <a href="">{{LANG.front.linkedin}}</a>
                        </div>
                    </div>

                </div>
            </div>

            <Comment v-if="!!post && !!post.post_id" :postId="post.post_id"></Comment>
        </div>
        <error-page v-if="isErrPage"></error-page>
    </div>
</template>

<script>

    import Comment from '../components/comments.vue'
    import ErrorPage from "./errorPage.vue";

    export default {
        components: {ErrorPage, Comment},
        props: ['post_id', 'title'],
        data() {
            return {
                post: null,
                isErrPage: false,
            }
        },
        methods: {
            getPost() {
                this.$http.post(this.URL.API + 'post/get/', {
                    'post_id': this.post_id,
                    'post_key': this.title,
                }).then((json) => {
                    this.post = {};
                    if (json.data.status) {
                        this.post = json.data.result;
                        this._title(this.post.title);
                    } else {
                        if (!!json.data.result) {
                            let post = json.data.result;
                            this.$router.push({name: 'post', params: {post_id: post.post_id, title: post.post_key}});
                        } else {
                            this.isErrPage = true;
                            this._title(this.LANG.front.not_found_page);
                        }
                    }
                });
            },
            getTimePost($date) {
                let parts = $date.split(' ');
                return !!parts[1] ? parts[1] : '';
            }
        },
        created() {
            this.getPost();
        }
    }
</script>