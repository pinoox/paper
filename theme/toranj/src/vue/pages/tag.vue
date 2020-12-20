<template>
    <section>
        <div class="container">
            <div class="page">
                <h1 class="page-title">{{tag_name}}</h1>
            </div>
        </div>

        <PostsList :posts="posts"></PostsList>

    </section>
</template>

<script>
    import PostsList from '../components/posts-list.vue';

    export default {
        components: {PostsList},
        props: ['tag_name'],
        data() {
            return {
                keyword: null,
                posts: []
            }
        },
        methods: {
            searchPosts() {
                this.$http.post(this.URL.API + 'post/getByTag/', {tag_name: this.tag_name}).then((json) => {
                    this.posts = json.data;
                });
            }
        },
        created() {
            this.searchPosts();
        }
    }
</script>