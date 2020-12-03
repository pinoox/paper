<template>
    <div class="enter-comment" @keyup.enter="newComment()">
        <input v-model="sendComment.full_name" class="input" type="text"
               placeholder="نام و نام خانوادگی">
        <input v-model="sendComment.email" class="input" type="email" placeholder="ایمیل">
        <textarea v-model="sendComment.message" class="textarea"
                  placeholder="نظر خود را در مورد این پست بنویسید..."></textarea>

        <div class="btn-leave-comment">
            <div @click="newComment()" class="btn btn-primary btn-round">ارسال نظر</div>
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
                    }
                });
            }
        }
    }
</script>