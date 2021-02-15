<template>
    <div class="posts-list-empty">
        <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
        <div class="text">{{LANG.front.not_found_page}}</div>
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
            }
        },
        created() {
            this.getPosts();
        },
        methods:{
            getPosts() {
                this.$http.post(this.URL.API + 'post/getAll/', this.params).then((json) => {
                    this.posts = json.data.posts;
                    this.pages = json.data.page;
                });
            },
        }
    }
</script>