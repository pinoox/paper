<template>
    <div v-if="enable" :id="elName" class="swiper-container">
        <div class="swiper-wrapper">
            <slot></slot>
        </div>

        <div v-if="prev" class="swiper-button-prev"></div>
        <div v-if="next" class="swiper-button-next"></div>
        <div v-if="pagination" class="swiper-pagination"></div>
    </div>
</template>

<script>
    import Swiper from 'swiper/js/swiper.min.js';
    import 'swiper/css/swiper.css';

    export default {
        props: {
            items: {
                type: Array,
                default() {
                    return [];
                },
            },
            enable: {
                type: Boolean,
                default: true,
            },
            pagination: {
                type: Boolean,
                default: true,
            },
            next: {
                type: Boolean,
                default: true,
            },
            prev: {
                type: Boolean,
                default: true,
            },
            options: {
                type: Object,
                default() {
                    return {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        observer: true,
                        freeMode: false,
                        centeredSlides: true,
                    };
                }
            }
        },
        data() {
            return {
                elName: 'swiper_' + this.generateUniqueId(),
            }
        },
        mounted() {
            this.initSwiper();
        },
        methods: {
            initSwiper() {
                if (!this.enable) return;
                let options = this.options;
                if (this.pagination) {
                    options.pagination = {
                        el: '.swiper-pagination',
                        clickable: true,
                    };
                }
                if (options.navigation === undefined) options.navigation = {};

                if (this.next)
                    options.navigation.nextEl = '.swiper-button-next';
                if (this.prev)
                    options.navigation.prevEl = '.swiper-button-prev';
                this.$nextTick(() => {
                    new Swiper('#' + this.elName, this.options);
                });
            },
            generateUniqueId() {
                return Math.random().toString(36).substring(2, 15);
            },
        },
        watch: {
            'enable': function () {
                this.initSwiper();
            }
        }
    }
</script>