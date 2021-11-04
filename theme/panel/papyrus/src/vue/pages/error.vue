<template>
    <div class="error">
        <div class="container">
            <div class="section">
                <div class="section-content">
                    <h1 class="title">{{LANG.panel.page_not_found}}</h1>
                    <h3 class="subtitle">{{LANG.panel.page_not_found_description}}</h3>
                    <router-link :to="{name:'dashboard'}" class="btn btn-sm btn-simple" v-if="_module('panel/dashboard')">{{LANG.panel.go_to_dashboard}}</router-link>
                    <img src="@img/svg/error.svg" alt="error">
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {}
</script>