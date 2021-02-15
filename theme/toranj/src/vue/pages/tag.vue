<template>
    <section>
        <div class="container">
            <div class="page">
                <h1 class="page-title">{{tag_name}}</h1>
            </div>
        </div>

        <PostsList @goPage="goPage" :pages="pages" :posts="posts"></PostsList>

    </section>
</template>

<script>
    import PostsList from '../components/posts-list.vue';

    export default {
        components: {PostsList},
        props: ['tag_name'],
        data() {
            return {
                posts: [],
                pages: {},
                params: {
                    page: 1,
                    tag: null,
                }
            }
        },
        methods: {
            getPosts() {
                this.params.tag = this.tag_name;
                this.$http.post(this.URL.API + 'post/getAll/', this.params).then((json) => {
                    this.posts = json.data.posts;
                    this.pages = json.data.pages;
                });
            },
            goPage(page) {
                this.params.page = page;
                this.getPosts();
            }
        },
        created() {
            this.getPosts();
            this._title(this.LANG.front.tag + ' (' +this.tag_name+')');
        }
    }
</script>