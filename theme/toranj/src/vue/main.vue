<template>
    <div>
        <div class="blur-loading animate__animated animate__fadeIn animate__faster" v-show="_isLoading">
            <div class="spinner"></div>
        </div>
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
            this.customInterceptors()
        },
        methods:{
            customInterceptors() {
                this.numProcessing = 0;
                this.$http.interceptors.request.use((request) => {

                    let isLoading = true;
                    if (request.params !== undefined && request.params.isLoading !== undefined) {
                        isLoading = request.params.isLoading;
                    }
                    if (isLoading) {
                        this.numProcessing++;
                        this._isLoading = true;
                    }
                    request.headers.pinoox_lang = this.currentLang;
                    request.isLoading = isLoading;

                    return request;
                });

                this.$http.interceptors.response.use((response) => {
                    if (response.config.isLoading) {
                        this.numProcessing--;
                        let isStop = (this.numProcessing === 0);
                        if (isStop) {
                            this._isLoading = false;
                        }
                    }
                    return response;
                });
            },
        }
    }
</script>