<template>
    <div class="enter-comment" @keyup.enter="newComment()">
        <input v-model="sendComment.full_name" class="input" type="text"
               :placeholder="LANG.front.full_name">
        <input v-model="sendComment.email" class="input" type="email" :placeholder="LANG.front.email">
        <textarea v-model="sendComment.message" class="textarea"
                  :placeholder="LANG.front.write_comment_about_post"></textarea>

        <div class="btn-leave-comment">
            <div @click="newComment()" class="btn btn-primary btn-round">{{LANG.front.send_comment}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            postId: {
                type: Number,
                required: true,
            },
            parentId: {
                type: Number,
                required: false,
                default: null
            }
        },
        data() {
            return {
                sendComment: {}
            }
        },
        methods: {
            newComment() {
                this.sendComment['post_id'] = this.postId;
                this.sendComment['parent_id'] = this.parentId;
                this.$http.post(this.URL.API + 'post/leaveComment/', this.sendComment).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.sendComment = {};
                        this.$emit('refresh');
                    }
                });
            }
        }
    }
</script>