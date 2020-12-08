<template>
    <div class="container">
        <div class="widgets-box">
            <div class="row">
                <div class="col-md-5">
                    <div class="widget">
                        <div class="widget-header">
                            <h2 class="title">{{LANG.post.most_visited_post}}</h2>
                        </div>
                        <div class="widget-content">
                            <div class="posts">
                                <div class="post" v-for="p in mostVisitedPosts">
                                    <a :href="p.meta.url" class="post-image">
                                        <img :src="p.thumb_128" :alt="p.title">
                                    </a>
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <time :datetime="p.publish_date_time">{{p.publish_date}}</time>
                                        </div>
                                        <h4 class="post-title"><a :href="p.meta.url">{{p.title}}</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="widget">
                        <div class="widget-header">
                            <h2 class="title">{{LANG.comment.latest_comments}}</h2>
                        </div>
                        <div class="widget-content">
                            <div class="comments">
                                <div class="comment" v-for="(c,index) in latestComments">
                                    <div class="comment-meta">
                                        <div class="user">{{c.full_name}}</div>
                                        <time datetime="">{{c.approx_insert_date}}</time>
                                    </div>
                                    <a :href="c.post_url" class="comment-link">{{c.post_title}}</a>
                                    <p class="comment-text">{{c.message | truncate(100, '...')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="widget">
                        <div class="widget-header no-bg">
                            <h2 class="title color-primary" v-html="LANG.front.follow_us"></h2>
                        </div>
                        <div class="widget-content">
                            <div class="socials">
                                <a class="item facebook-color" href=""><i class="fab fa-facebook"></i></a>
                                <a class="item instagram-color" href=""><i class="fab fa-instagram"></i></a>
                                <a class="item telegram-color" href=""><i class="fab fa-telegram"></i></a>
                                <a class="item whatsapp-color" href=""><i class="fab fa-whatsapp"></i></a>
                                <a class="item linkedin-color" href=""><i class="fab fa-linkedin"></i></a>
                                <a class="item twitter-color" href=""><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                mostVisitedPosts: [],
                latestComments: [],
            }
        },
        methods: {
            getMostVisitedPosts() {
                this.$http.post(this.URL.API + 'post/getMostVisited/').then((json) => {
                    this.mostVisitedPosts = json.data;
                });
            },
            getLatestComments() {
                this.$http.post(this.URL.API + 'post/getLatestComments/').then((json) => {
                    this.latestComments = json.data;
                });
            },
        },
        created() {
            this.getMostVisitedPosts();
            this.getLatestComments();
        }
    }
</script>