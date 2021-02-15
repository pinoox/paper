<template>
    <section>

        <div class="container">
            <div class="page">
                <div class="search-box">
                    <input v-model="params.keyword" type="text"
                           :placeholder="LANG.front.searching_for_what_write_down_here">
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
                posts: [],
                pages: {},
                params: {
                    page: 1,
                    rows:12,
                    keyword: null,
                }
            }
        },
        methods: {
            getPosts() {
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
            this._title(this.LANG.front.search);
        },
        watch: {
            'params.keyword': {
                handler() {
                    this._delay(() => {
                        this.params.page = 1;
                        this.getPosts();
                    }, 500);
                }
            }
        }
    }
</script>