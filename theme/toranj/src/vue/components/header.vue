<template>
    <div class="header">
        <div class="container">
            <div class="header-inner">
                <router-link :to="{name:'home'}" class="brand">{{generalSetting.site_title}}</router-link>
                <nav>
                    <router-link v-for="m in generalSetting.menu" :to="URL.BASE + m.link" class="item">{{m.label}}</router-link>
                    <router-link :to="{name:'search'}" class="item"><i class="fa fa-search"></i></router-link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>