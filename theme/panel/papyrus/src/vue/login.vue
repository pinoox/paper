<template>
    <div class="login-page">
        <div class="bg animated slideInLeft"></div>
        <div class="title animated slideInLeft">
            <h2>{{LANG.user.login_to_account}}</h2>
            <img src="@img/logo/logo-256.png" alt="login to account">
        </div>


        <form class="login-form  animated fadeInRight" method="post">
            <div class="form-item animated slideInRight">
                <input type="text" :placeholder="LANG.user.username" name="username">
                <i class="fa fa-user"></i>
            </div>
            <div class="form-item animated fast slideInRight">
                <input type="password" :placeholder="LANG.user.password" name="password">
                <i class="fa fa-lock"></i>
            </div>
            <button type="submit" class="ptn ptn-secondary">{{LANG.user.login}}</button>
            <div class="message animated bounceIn">err</div>
        </form>

    </div>
</template>

<script>
    export default {
        name: "login"
    }
</script>

<style scoped>

</style>