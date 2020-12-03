<template>
    <div class="container">
        <div class="posts-list" v-if="posts!=null && posts.length > 0">
            <div v-for="(p,index) in posts" :class="[isFirst && index===0 ? 'post-first' : 'post']">
                <a :href="p.meta.url" class="post-image">
                    <img :src="p.image" alt="p.title">
                </a>
                <div class="post-content">
                    <div class="post-meta">
                        <time :datetime="p.publish_date_time" class="post-date">{{p.publish_date}}</time>
                        <div class="inline-divider"></div>
                        <div class="post-tags" v-if="p.tags!=null && p.tags.length>0">
                            <router-link :to="{name:'tag',params:{tag_name:t.tag_name}}" class="item"
                                         v-for="t in p.tags"> #{{t.tag_name}}
                            </router-link>
                        </div>
                    </div>
                    <h2 class="post-title"><a :href="p.meta.url">{{p.title}}</a></h2>
                    <p class="post-summary">{{p.summary}}</p>
                    <p class="post-bottom" v-if="isFirst && index===0">
                        <a :href="p.meta.url">بیشتر بخوانید</a>
                    </p>
                </div>
            </div>
            <div v-if="false" class="post">
                <div class="post-content">
                    <div class="post-meta">
                        <div class="post-tags">
                            <a href="#" class="item">ماری جوانا</a>
                            <a href="#" class="item">مواد مخدر</a>
                        </div>
                        <time datetime="2020-11-19" class="post-date">1399/05/02</time>
                    </div>
                    <h2 class="post-title"><a href="#">آیا مصرف ماری جوانا بر قدرت باروری مردان تاثیر دارد ؟</a></h2>
                    <p class="post-summary">اری جوانا (Marijuana) از گلها و برگهای شاهدانه تهیه می شود و در بسیاری از
                        نقاط
                        دنیا شایعترین...</p>
                    <p class="post-bottom">
                        <a href="#">ادامه مطلب</a>
                    </p>
                </div>
                <a href="#" class="post-image">
                    <img src="res/01.jpg" alt="">
                </a>
            </div>
        </div>
        <div v-else class="posts-list-empty">
            <div class="icon"><i class="fa fa-sad-tear"></i></div>
            <div class="text">مطلبی پیدا نشد</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            posts: {
                type: Array
            },
            isFirst: {
                default: false,
                type: Boolean
            }
        },
        data() {
            return {}
        },
    }
</script>