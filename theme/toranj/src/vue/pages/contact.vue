<template>
    <section>
        <div class="container">
            <div class="contacts">
                <h2 class="title">{{LANG.contact.contact_title}}</h2>
                <h5 class="subtitle">{{LANG.contact.contact_subtitle}}</h5>
                <form class="form" @keyup.enter="newContact()">
                    <div class="layout">
                        <div class="info">
                            <div class="form-group">
                                <span class="label">{{LANG.contact.full_name}}</span>
                                <input v-model="params.full_name" class="input" type="text"
                                       :placeholder="LANG.contact.full_name">
                            </div>
                            <div class="form-group">
                                <span class="label">{{LANG.contact.email}}</span>
                                <input v-model="params.email" class="input" type="text" :placeholder="LANG.contact.email">
                            </div>
                        </div>
                        <div class="message">
                            <div class="form-group">
                                <span class="label">{{LANG.contact.message}}</span>
                                <textarea v-model="params.message" class="textarea" :placeholder="LANG.contact.message"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="action">
                        <div class="form-group">
                            <div @click="newContact()" class="btn btn-primary btn-round">{{LANG.contact.send_message}}</div>
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
    }
</script>