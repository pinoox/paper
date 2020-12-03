<template>
    <div class="container">
        <div class="post-single" v-if="post!=null">
            <div class="post-header">
                <div class="post-meta">
                    <time :datetime="post.publish_date_time">{{post.publish_date}}</time>
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


            <div class="post-content" v-html="post.context"></div>

            <div class="post-footer">
                <div class="post-share">
                    <div class="caption">اشتراک با دوستان:</div>
                    <div class="social-links">
                        <a class="item" :href="post.meta.socials.facebook"><i
                                class="fab fa-facebook facebook-color"></i></a>
                        <a class="item" :href="post.meta.socials.telegram"><i
                                class="fab fa-telegram telegram-color"></i></a>
                        <a class="item" :href="post.meta.socials.whatsapp"><i
                                class="fab fa-whatsapp whatsapp-color"></i></a>
                        <a class="item" :href="post.meta.socials.linkedin"><i
                                class="fab fa-linkedin linkedin-color"></i></a>
                        <a class="item" :href="post.meta.socials.twitter"><i
                                class="fab fa-twitter twitter-color"></i></a>
                    </div>
                </div>

                <div class="post-author">
                    <div class="author-image">
                        <img :src="post.author.thumb_128" :alt="post.author.full_name">
                    </div>
                    <div class="author-info">
                        <router-link :to="{name:'profile', params:{username:post.author.username}}" class="info-name">
                            {{post.author.full_name}}
                        </router-link>
                        <p class="info-bio">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                            طراحان
                            گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط
                            فعلی
                            تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. </p>
                        <div class="info-social">
                            <a href="">تویتتر</a>
                            <a href="">فیسبوک</a>
                            <a href="">تلگرام</a>
                            <a href="">اینستاگرام</a>
                        </div>
                    </div>

                </div>
            </div>

            <Comment :postId="post.post_id"></Comment>
        </div>
    </div>
</template>

<script>

    import Comment from '../components/comments.vue'

    export default {
        components: {Comment},
        props: ['post_id', 'title'],
        data() {
            return {
                post: null
            }
        },
        methods: {
            getPost() {
                this.$http.post(this.URL.API + 'post/get/' + this.post_id).then((json) => {
                    this.post = json.data;
                });
            }
        },
        created() {
            this.getPost();
        }
    }
</script>