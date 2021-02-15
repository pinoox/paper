<template>
    <div class="container">
        <div class="widgets-box">
            <div class="row">
                <div class="col-md-5">
                    <div class="widget">
                        <div class="widget-header">
                            <h2 class="title">{{LANG.front.most_visited_post}}</h2>
                        </div>
                        <div class="widget-content">
                            <div class="posts">
                                <div class="post" v-for="p in mostVisitedPosts">
                                    <router-link :to="{name:'post',params:{post_id:p.post_id,title:p.post_key}}" class="post-image">
                                        <img :src="p.thumb_128" :alt="p.title">
                                    </router-link>
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <time :datetime="p.publish_date">{{p.approx_date}}</time>
                                        </div>
                                        <h4 class="post-title"><router-link :to="{name:'post',params:{post_id:p.post_id,title:p.post_key}}">{{p.title}}</router-link></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="widget">
                        <div class="widget-header">
                            <h2 class="title">{{LANG.front.latest_comments}}</h2>
                        </div>
                        <div class="widget-content">
                            <div class="comments">
                                <div class="comment" v-for="(c,index) in latestComments">
                                    <div class="comment-meta">
                                        <div class="user">{{c.full_name}}</div>
                                        <time :datetime="c.insert_date">{{c.approx_date}}</time>
                                    </div>
                                    <router-link :to="{name:'post',params:{post_id:c.post.post_id,title:c.post.post_key}}" class="comment-link">{{c.post.title}}</router-link>
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
                                <a v-for="s in contactSetting.socials" class="item" :class="s.label+'-color'" href=""><i :class="s.icon"></i></a>
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
            getTime($date)
            {
                let parts = $date.split(' ');
                return !!parts[1]? parts[1] : '';
            }
        },
        created() {
            this.getMostVisitedPosts();
            this.getLatestComments();
        }
    }
</script>