<template>
    <section>
        <div class="container">
            <div class="profile" v-if="!!user">
                <div class="profile-author">
                    <div class="profile-image">
                        <img :src="user.thumb_128" :alt="user.fullname">
                    </div>
                    <div class="profile-name">{{user.fullname}}</div>
                    <div class="profile-bio" v-if="false"></div>
                    <div class="profile-meta" v-if="false">
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

        <PostsList @goPage="goPage" :pages="pages" :posts="posts"></PostsList>

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
                posts: [],
                pages: {},
                params:{
                    page:1,
                    username:null,
                }
            }
        },
        methods: {
            getProfile() {
                this.$http.post(this.URL.API + 'user/getProfile/' + this.username).then((json) => {
                    this.user = json.data;
                    this._title(this.user.fullname);
                });
            },
            getPosts() {
                this.params.username = this.username;
                this.$http.post(this.URL.API + 'post/getAll/' , this.params).then((json) => {
                    this.posts = json.data.posts;
                    this.pages = json.data.pages;
                });
            },
            goPage(page)
            {
                this.params.page = page;
                this.getPosts();
            }
        },
        created() {
            this._title();
            this.getProfile();
            this.getPosts();
        }
    }
</script>