<template>
    <div class="container">
        <div v-if="posts!=null && posts.length > 0">
            <div class="posts-list">
                <div v-for="(p,index) in posts" :class="[isFirst && index===0 ? 'post-first' : 'post']">
                    <router-link :to="{name:'post',params:{post_id:p.post_id,title:p.post_key}}" class="post-image">
                        <img :src="p.image" alt="p.title">
                    </router-link>
                    <div class="post-content">
                        <div class="post-meta">
                            <time :datetime="p.publish_date" class="post-date">{{p.approx_date}}</time>
                            <div class="inline-divider" v-if="p.tags!=null && p.tags.length>0"></div>
                            <div class="post-tags" v-if="p.tags!=null && p.tags.length>0">
                                <router-link :to="{name:'tag',params:{tag_name:t.tag_name}}" class="item"
                                             v-for="t in p.tags"> #{{t.tag_name}}
                                </router-link>
                            </div>
                        </div>
                        <h2 class="post-title">
                            <router-link :to="{name:'post',params:{post_id:p.post_id,title:p.post_key}}">{{p.title}}
                            </router-link>
                        </h2>
                        <p class="post-summary">{{p.summary}}</p>
                        <p class="post-bottom" v-if="isFirst && index===0">
                            <router-link :to="{name:'post',params:{post_id:p.post_id,title:p.post_key}}">
                                {{LANG.front.read_more}}
                            </router-link>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <Pagination
                        :page="pages"
                        @go="goPage">
                </Pagination>
            </div>
        </div>
        <div v-else class="posts-list-empty">
            <div class="icon"><i class="fa fa-sad-tear"></i></div>
            <div class="text">{{LANG.front.search_result_not_found}}</div>
        </div>
    </div>
</template>

<script>
    import Pagination from "./pagination.vue";

    export default {
        components: {Pagination},
        props: {
            posts: {
                type: Array
            },
            pages: {
                type: Object
            },
            isFirst: {
                default: false,
                type: Boolean
            }
        },
        data() {
            return {}
        },
        methods: {
            getTimePost($date) {
                let parts = $date.split(' ');
                return !!parts[1] ? parts[1] : '';
            },
            goPage(page) {
                this.$emit('goPage', page);
            }
        },
        watch:{
            posts(val,old)
            {
                if(!!old && old.length > 0)
                {
                    $('html,body').animate({ scrollTop: 0 }, 'fast');
                }
            }
        }
    }
</script>