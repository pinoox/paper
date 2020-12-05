<template>
    <section>

        <div class="container">
            <div class="contacts">
                <h2 class="title">تماس با ما</h2>
                <h5 class="subtitle">خوشحال می‌شویم پیام و نظرات شما را دریافت کنیم</h5>

                <form class="form" @keyup.enter="newContact()">
                    <div class="layout">
                        <div class="info">
                            <div class="form-group">
                                <span class="label">نام و نام خانوادگی</span>
                                <input v-model="params.full_name" class="input" type="text"
                                       placeholder="نام و نام خانوادگی">
                            </div>
                            <div class="form-group">
                                <span class="label">ایمیل</span>
                                <input v-model="params.email" class="input" type="text" placeholder="ایمیل">
                            </div>
                        </div>
                        <div class="message">
                            <div class="form-group">
                                <span class="label">پیام</span>
                                <textarea v-model="params.message" class="textarea" placeholder="پیام"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="action">
                        <div class="form-group">
                            <div @click="newContact()" class="btn btn-primary btn-round">ارسال پیام</div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </section>
</template>

<script>

    export default {
        data() {
            return {
                params: {}
            }
        },
        methods: {
            newContact() {
                this.$http.post(this.URL.API + 'newContact',this.params).then((json) => {
                    if (this._messageResponse(json.data))
                        this.params = {};
                });
            },
        },
        created() {

        }
    }
</script>