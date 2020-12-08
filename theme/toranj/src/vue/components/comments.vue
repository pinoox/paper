<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="comments">
                    <div class="cm-caption">{{LANG.comment.comments}} <span v-if="count>0">({{count}})</span></div>

                    <CommentForm :post-id="postId"></CommentForm>

                    <div class="cm-list">
                        <div class="comment" v-for="(c,index) in comments">
                            <div class="cm-header">
                                <div class="cm-author">
                                    <img :src="c.image"
                                         :alt="c.full_name">
                                </div>
                                <div class="cm-meta">
                                    <div class="meta-user">{{c.full_name}}</div>
                                    <div class="meta-date">{{c.approx_insert_date}}</div>
                                </div>
                                <div class="cm-reply">
                                    <div v-if="c.isReply" @click="c.isReply=false" class="btn-reply"><i class="fa fa-times"></i></div>
                                    <div v-else @click="c.isReply=true" class="btn-reply"><i class="fa fa-reply"></i></div>
                                </div>
                            </div>
                            <div class="cm-content">{{c.message}}</div>
                            <div class="leave-reply" v-if="c.isReply">

                                <CommentForm :post-id="postId" :parent-id="c.comment_id"></CommentForm>

                            </div>

                            <div class="cm-inner-comments"
                                 v-if="!!c.children && c.children.length>0">
                                <div class="comment" v-for="child in c.children">
                                    <div class="cm-header">
                                        <div class="cm-author">
                                            <img :src="child.image"
                                                 :alt="child.full_name">
                                        </div>
                                        <div class="cm-meta">
                                            <div class="meta-user">{{child.full_name}}</div>
                                            <div class="meta-date">{{child.approx_insert_date}}</div>
                                        </div>
                                    </div>
                                    <div class="cm-content">{{child.message}}</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import CommentForm from '../components/comment-form.vue';

    export default {
        components: {CommentForm},
        props: ['postId'],
        data() {
            return {
                count: 0,
                comments: [],
            }
        },
        methods: {
            getComments() {
                this.$http.post(this.URL.API + 'post/getComments/' + this.postId).then((json) => {
                    this.comments = json.data.items;
                    this.count = json.data.count;
                });
            },
        },
        created() {
            this.getComments();
        }
    }
</script>