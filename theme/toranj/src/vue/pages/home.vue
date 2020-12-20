<template>
    <div>
        <PostsList :posts="posts" :isFirst="true"></PostsList>
    </div>
</template>

<script>

    import PostsList from "../components/posts-list.vue";

    export default {
        components: {PostsList},
        data() {
            return {
                posts: [],
            }
        },
        created() {
            this.getPosts();
        },
        methods:{
            getPosts() {
                this.$http.post(this.URL.API + 'post/getAll/', this.params).then((json) => {
                    this.posts = json.data;
                });
            },
        }
    }
</script>