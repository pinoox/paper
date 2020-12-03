<template>
    <section>

        <div class="container">
            <div class="page">
                <div class="search-box">
                    <input v-model="keyword" @keyup="searchPosts()" type="text"
                           placeholder="دنبال چی میگردی؟ اینجا بنویس...">
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
                keyword: null,
                posts: []
            }
        },
        methods: {
            searchPosts() {
                this.$http.post(this.URL.API + 'post/search/', {keyword: this.keyword}).then((json) => {
                    this.posts = json.data;
                });
            }
        },
        created() {
            this.searchPosts();
        }
    }
</script>