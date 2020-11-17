<template>
    <div class="login-page">
        <div class="bg animated slideInLeft"></div>
        <div class="title animated slideInLeft">
            <h2>{{LANG.panel.login_to_account}}</h2>
            <img src="@img/logo/logo-256.png" alt="login to account">
        </div>


        <div class="login-form  animated fadeInRight" @keypress.enter="login()">
            <div class="form-item animated slideInRight">
                <input v-model="params.user_key" type="text" :placeholder="LANG.panel.user_key" name="username">
                <i class="fa fa-user"></i>
            </div>
            <div class="form-item animated fast slideInRight">
                <input v-model="params.password" type="password" :placeholder="LANG.panel.password" name="password">
                <i class="fa fa-lock"></i>
            </div>
            <button @click="login()" type="submit" class="btn btn-primary">{{LANG.panel.login}}</button>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                params: {
                    user_key: null,
                    password: null,
                }
            }
        },
        methods:
            {
                login() {
                    this.$http.post(PINOOX.URL.API + 'account/login', this.params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            localStorage.pinoox_user = json.data.result;
                            this.getInitUser();
                        }
                    });
                }
            },
    }
</script>
