<template>
    <section>
        <div class="container">
            <div class="profile">
                <div class="profile-author">
                    <div class="profile-image">
                        <img :src="user.thumb_128" :alt="user.full_name">
                    </div>
                    <div class="profile-name">{{user.full_name}}</div>
                    <div class="profile-bio" v-if="false">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                        طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای
                        شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد.
                    </div>
                    <div class="profile-meta">
                        <div class="meta-socials">
                            <a href="">{{LANG.front.telegram}}</a>
                            <a href="">{{LANG.front.facebook}}</a>
                            <a href="">{{LANG.front.instagram}}</a>
                            <a href="">{{LANG.front.telegram}}</a>
                            <a href="">{{LANG.front.linkedin}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <PostsList :posts="posts"></PostsList>

    </section>
</template>

<script>
    import PostsList from '../components/posts-list.vue';

    export default {
        components: {PostsList},
        props: ['username'],
        data() {
            return {
                user: null,
                posts: []
            }
        },
        methods: {
            getProfile() {
                this.$http.post(this.URL.API + 'user/getProfile/' + this.username).then((json) => {
                    this.user = json.data;
                });
            },
            getPosts() {
                this.$http.post(this.URL.API + 'post/getAllByUsername/' + this.username).then((json) => {
                    this.posts = json.data;
                });
            }
        },
        created() {
            this.getProfile();
            this.getPosts();
        }
    }
</script>