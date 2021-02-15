<template>
    <div>
        <PostsList @goPage="goPage" :pages="pages" :posts="posts" :isFirst="true"></PostsList>
    </div>
</template>

<script>

    import PostsList from "../components/posts-list.vue";

    export default {
        components: {PostsList},
        data() {
            return {
                posts: [],
                pages: {},
                params:{
                    page:1,
                }
            }
        },
        created() {
            this._title();
            this.getPosts();
        },
        methods:{
            getPosts() {
                this.$http.post(this.URL.API + 'post/getAll/', this.params).then((json) => {
                    this.posts = json.data.posts;
                    this.pages = json.data.pages;
                });
            },
            goPage(page)
            {
                this.params.page = page;
                this.getPosts();
            }
        }
    }
</script>