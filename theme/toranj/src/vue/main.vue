<template>
    <div>
        <notifications group="app" classes="notification">
            <template slot="body" slot-scope="props">
                <div class="notification" :class="props.item.type" @click="props.close">
                    <a class="title">
                        {{props.item.title}}
                    </a>
                    <div class="text" v-html="props.item.text"></div>
                </div>
            </template>
        </notifications>

        <Header></Header>
        <transition name="fade" mode="out-in"
                    :enter-active-class="'animated faster fadeIn'"
                    :leave-active-class="'animated faster fadeOut'">
            <router-view :key="$route.fullPath"></router-view>
        </transition>
        <Widgets v-if="!$route.meta.hideWidgets"></Widgets>
        <Footer></Footer>

    </div>
</template>

<script>
    import Header from './components/header.vue';
    import Footer from "./components/footer.vue";
    import Widgets from "./components/widgets.vue";

    export default {
        components: {Header, Footer, Widgets},
        created() {
        }
    }
</script>