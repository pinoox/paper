<template>
    <section class="page">
        <div class="container">
            <div class="dashboard">
                <div class="section"
                     v-if="latestPosts!=null && latestPosts.length>0">
                    <div class="section-title">
                        <h2>{{LANG.post.latest_posts}}</h2>
                        <router-link :to="{name:'posts'}" class="more">{{LANG.post.all}} <i
                                class="fa fa-chevron-left"></i></router-link>
                    </div>
                    <div class="section-content">
                        <swiper class="swiper-posts"
                                :options="swiperOpts"
                                :next="false"
                                :prev="false"
                                :pagination="false">
                            <router-link tag="div" :to="{name:'write',params:{post_id:i.post_id}}" class="swiper-slide"
                                         v-for="i in latestPosts">
                                <img class="thumb" :src="i.image" alt="title">
                                <div class="title">{{i.title}}</div>
                            </router-link>
                        </swiper>
                    </div>
                </div>
                <div class="section" v-else>
                    <div class="section-content">
                        <div class="encourage-writing">
                            <div class="greeting">
                                {{LANG.post.hello}}
                                <b>{{USER.fname}}</b>
                                {{LANG.post.dear}}
                            </div>
                            <br>
                            <div class="write-guide">
                                {{LANG.post.first_post}}
                            </div>
                            <br>
                            <div class="btn-wrapper">
                                <router-link :to="{name:'write'}" tag="div" class="btn btn-primary btn-write">
                                    <simple-svg
                                            :src="_icons.pen"
                                            width="22px"
                                            customClassName="icon"/>
                                    {{LANG.post.write_first_post}}
                                </router-link>
                                <img :src="_icons.arrow_right" class="arrow">
                            </div>

                            <img :src="_icons.first_post" class="art">

                        </div>
                    </div>
                </div>

                <div class="section" v-if="latestComments!=null && latestComments.length > 0">
                    <div class="section-title">
                        <h2>{{LANG.comment.latest_comments}}</h2>
                        <div class="more"> {{LANG.panel.all}} <i class="fa fa-chevron-left"></i></div>
                    </div>
                    <div class="section-content">
                        <vue-good-table
                                styleClass="vgt-table table"
                                :rtl="true"
                                :columns="commentCols"
                                :rows="latestComments">
                            <template slot="table-row" slot-scope="props">
                                <div v-if="props.column.field === 'thumb_128'">
                                    <img class="thumb thumb-round" :src="props.row.thumb_128" :alt="props.row.title">
                                </div>
                                <div v-else-if="props.column.field === 'operation'">
                                <span @click="toggleStatusComment(props.row,props.index)" class="btn-action">
                                    <i class="fas"
                                       :class="[props.row.status==='publish' ? 'fa-comment-slash' : 'fa-check']"></i></span>
                                    <span @click="removeComment(props.row,props.index)" class="btn-action">
                                    <i class="fa fa-trash"></i></span>
                                </div>
                                <div v-else-if="props.column.field === 'status'">
                                    <span class="light">{{LANG.comment.status[props.row.status]}}</span>
                                </div>
                                <div v-else-if="props.column.field === 'title'">
                                    <router-link :to="{name:'write',params:{'post_id':props.row.post_id}}">
                                        <span :class="props.column.style">{{props.row.title}}</span>
                                    </router-link>
                                </div>
                                <div v-else>
                                <span :class="props.column.style">
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                                </div>
                            </template>
                            <div slot="emptystate">
                                <div class="empty-data">
                                    {{LANG.panel.empty_table}}
                                </div>
                            </div>
                            <template slot="loadingContent">
                                <div class="loading-message spinner"></div>
                            </template>

                        </vue-good-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import Swiper from '../components/swiper.vue';

    export default {
        components: {Swiper},
        data() {
            return {
                latestPosts: null,
                latestComments: null,
                swiperOpts: {
                    loop: false,
                    slidesPerView: 1,
                    spaceBetween: 50,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false
                    },
                    breakpoints: {
                        // when window width is >= 320px
                        320: {
                            slidesPerView: 1,
                            spaceBetween: 20
                        },
                        480: {
                            slidesPerView: 2,
                            spaceBetween: 30
                        },
                        640: {
                            slidesPerView: 3,
                            spaceBetween: 40
                        },
                        768: {
                            slidesPerView: 4,
                            spaceBetween: 40
                        }
                    }
                },
                commentCols: [
                    {
                        label: PINOOX.LANG.panel.id,
                        field: 'comment_id',
                    },
                    {
                        label: PINOOX.LANG.panel.user,
                        field: 'full_name',
                    },
                    {
                        label: PINOOX.LANG.panel.subject,
                        field: 'subject',
                    },
                    {
                        label: PINOOX.LANG.panel.message,
                        field: 'message',
                    },
                    {
                        label: PINOOX.LANG.post.post,
                        field: 'title',
                        style: 'link',
                    },
                    {
                        label: PINOOX.LANG.panel.date,
                        field: 'approx_insert_date',
                        style: 'light',
                    },
                    {
                        label: PINOOX.LANG.panel.status,
                        field: 'status',
                    },
                    {
                        label: PINOOX.LANG.panel.operation,
                        field: 'operation',
                        style: 'operation',
                        sortable: false,
                    },
                ],
            }
        },
        methods: {
            getLatestPosts() {
                this.$http.post(this.URL.API + 'post/getLatestPosts/').then((json) => {
                    this.latestPosts = json.data;
                });
            },
            getLatestComments() {
                this.$http.post(this.URL.API + 'comment/getLatestComments/').then((json) => {
                    this.latestComments = json.data;
                });
            },
            toggleStatusComment(item, index) {
                this.$http.post(this.URL.API + 'comment/changeStatus/', item).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.getLatestComments();
                    }
                });
            },
            removeComment(row, index) {
                let params = {contact_id: row.contact_id};
                this._confirm(PINOOX.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.post(this.URL.API + 'comment/delete/', params).then((json) => {
                        if (this._messageResponse(json.data)) {
                            this.$delete(this.latestComments, index)
                        }
                    });
                });
            },
        },
        created() {
            this.getLatestPosts();
            this.getLatestComments();
        }
    }
</script>