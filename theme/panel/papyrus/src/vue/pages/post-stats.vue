<template>
    <div class="page">
        <div class="menubar">
            <div class="items">
                <div class="text m1-rl">
                    <a v-if="!!post" :href="URL.FRONT + 'post/' + post.post_id +'/'+post.post_key" target="_blank"
                       class="item">{{LANG.post.preview}}</a>
                    <router-link v-if="!!post" :to="{name:'write',params:{post_id:post.post_id}}" class="item">
                        {{LANG.panel.edit}}
                    </router-link>
                    <router-link v-if="!!post" :to="{name:'comments',params:{post_id:post.post_id}}" class="item">
                        {{LANG.panel.comments}}
                    </router-link>
                    <router-link :to="{name:'write'}" class="item">
                        {{LANG.post.write}}
                    </router-link>
                    <router-link :to="{name:'posts'}" class="item">
                        {{LANG.post.posts_list}}
                    </router-link>
                </div>
            </div>
        </div>

        <simplebar class="simplebar">
            <div class="menubar">
                <div class="items">
                    <router-link :to="{name:'write'}" tag="div" class="item">
                        {{LANG.post.write}}
                    </router-link>
                </div>
            </div>
            <div class="container" v-if=" post!=null">
                <div class="post-stats">

                    <section class="section">
                        <div class="post-title">
                            <div class="label">{{LANG.post.analysis}}</div>
                            <div class="title">{{_isNull(post.title) ? post.draft_title : post.title}}</div>
                            <div class="details">{{LANG.post.published}}: {{post.approx_insert_date}}</div>
                        </div>
                    </section>
                    <section class="list-box-stat">

                        <div class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.post.visits}}</div>
                                <div class="amount">{{post.visits}} {{LANG.panel.times}}</div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <i class="far fa-eye"></i>
                                </div>
                            </div>
                        </div>
                        <div class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.panel.total_written_words}}</div>
                                <div class="amount">{{post.words}} {{LANG.panel.word}}</div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <i class="far fa-keyboard"></i>
                                </div>
                            </div>
                        </div>
                        <div class="box-stat box" v-if="!!stats">
                            <div class="text">
                                <div class="caption">{{LANG.panel.total_written_time}}</div>
                                <div class="amount">{{stats.timeTracking.value}}
                                    {{LANG.panel.type_time_tracking[stats.timeTracking.type]}}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <i class="far fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <router-link v-if="!!stats" :to="{name:'comments',params:{post_id:post.post_id}}"
                                     class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.comment.comments}}</div>
                                <div class="amount">{{stats.comments.total}} {{LANG.comment.comment_count}}</div>
                                <div class="footnote orange" v-show="stats.comments.pending>0">
                                    {{stats.comments.pending}} {{LANG.comment.comment}}
                                    {{LANG.comment.status.pending}}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <i class="far fa-comment-dots"></i>
                                </div>
                            </div>
                        </router-link>
                    </section>
                    <div v-if="hasStats">
                        <section class="section" v-if="!!monthly">
                            <div class="section-title no-margin-bottom">
                                <h2>{{LANG.post.stats_post}}</h2>
                            </div>
                            <ul class="section-tab">
                                <li @click="changeLastDay(5)"
                                    :class="[lastDay===5 ? 'active' :'' ]"> 5 {{LANG.panel.last_few_days}}
                                </li>
                                <li @click="changeLastDay(7)"
                                    :class="[lastDay===7? 'active' :'']">
                                    7 {{LANG.panel.last_few_days}}
                                </li>
                                <li @click="changeLastDay(21)"
                                    :class="[lastDay===21? 'active' :'']">
                                    21 {{LANG.panel.last_few_days}}
                                </li>
                            </ul>
                            <div class="section-content" v-if="!isMonthlyLoading">
                                <row :gutter="0" :columns="1" class="box-group">
                                    <column :sm="1" :md="1" :lg="1">
                                        <apexchart type="bar"
                                                   :options="monthlyOpts"
                                                   :height="monthlyOpts.chart.height"
                                                   :width="monthlyOpts.chart.width"
                                                   :series="monthly"></apexchart>
                                    </column>
                                </row>
                            </div>
                        </section>

                        <section class="section" v-if="!!stats">
                            <div class="section-title">
                                <h2>{{LANG.post.stats_7_days}}</h2>
                            </div>
                            <div class="section-content list-box-stat box-group" >
                                        <div class="box box3">
                                            <div class="details">
                                                <h3>{{stats.visits.total}}</h3>
                                                <h4>{{LANG.post.visits}}</h4>
                                            </div>
                                            <apexchart type="line"
                                                       :width="220"
                                                       :height="80"
                                                       :options="miniBoxOpts"
                                                       :series="stats.visits.series"></apexchart>
                                        </div>
                                        <div class="box box2">
                                            <div class="details">
                                                <h3>{{stats.commentsDays.total}}</h3>
                                                <h4>{{LANG.post.comments}}</h4>
                                            </div>
                                            <apexchart type="line"
                                                       :width="220"
                                                       :height="80"
                                                       :options="miniBoxOpts"
                                                       :series="stats.commentsDays.series"></apexchart>
                                        </div>
                            </div>
                        </section>
                    </div>
                    <div v-else class="section">
                        <div class="empty-message">
                            <div class="text"><span class="icon"><i class="fas fa-chart-line"></i></span>
                                {{LANG.post.empty_stats}}
                            </div>
                            <div @click="$router.go(-1)" class="btn btn-simple btn-sm">{{LANG.panel.back}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </simplebar>
    </div>
</template>

<script>
    export default {
        props: ['post_id'],
        data() {
            return {
                lastDay:5,
                isMonthlyLoading:false,
                hasStats: false,
                post: null,
                stats: null,
                monthly: null,
                miniBoxOpts: {
                    chart: {
                        type: 'line',

                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            top: 1,
                            left: 1,
                            blur: 2,
                            opacity: 0.2,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '12px',
                        },
                        formatter: function (val, opts) {
                            if (val > 0)
                                return val
                        },
                        background: {
                            enabled: true,
                            foreColor: '#506E93',
                            padding: 1,
                            borderRadius: 1,
                            opacity: 1,
                        },

                    },
                    stroke: {
                        //curve: 'smooth',
                        width: 1
                    },
                    markers: {
                        size: 0
                    },
                    grid: {
                        padding: {
                            top: 20,
                            bottom: 20,
                            left: 80,
                            right: 20
                        }
                    },
                    colors: ['#fff'],
                    tooltip: {
                        x: {
                            show: false,
                        },
                        y: {
                            title: {
                                formatter: function formatter(val) {
                                    return '';
                                }
                            }
                        }
                    },

                },
                monthlyOpts: {
                    chart: {
                        type: 'bar',
                        height: 350,
                        width: '100%',
                        fontFamily: 'IranSans',
                        toolbar: {
                            show: false,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '75%',
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: []
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return " " + val
                            }
                        }
                    }
                },
            }
        },
        methods: {
            changeLastDay(day)
            {
                this.lastDay = day;
                this.getMonthly();
            },
            getPost() {
                return this.$http.post(this.URL.API + 'post/get/' + this.post_id).then((json) => {
                    this.post = json.data;
                });
            },
            getStats() {
                return this.$http.post(this.URL.API + 'post/getStats/' + this.post_id).then((json) => {
                    this.stats = json.data;
                });
            },
            getMonthly() {
                this.isMonthlyLoading = true;
                return this.$http.post(this.URL.API + 'post/getMonthly/' + this.post_id,{lastDay:this.lastDay}).then((json) => {
                    this.isMonthlyLoading = false;
                    this.monthly = json.data.series;
                    this.monthlyOpts.xaxis.categories = json.data.date;
                });
            },
            getHasStats() {
                return this.$http.post(this.URL.API + 'post/hasStats/' + this.post_id).then((json) => {
                    return this.hasStats = json.data;
                });
            },
        },
        created() {
            this.getPost().then(() => {
                return this.getHasStats();
            }).then((hasStats) => {
                if (!hasStats) return false;

                this.getMonthly().then(() => {
                    return this.getStats();
                });
            });
        },
    }
</script>

<style lang="less">
    .apexcharts-legend {
        justify-content: center;
    }

    .apexcharts-legend.apexcharts-align-center.position-right {
        position: relative !important;
        right: 0 !important;
        top: -2px !important;
        width: 100% !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;

        .apexcharts-legend-marker {
            margin: 0 2px;
        }
    }

    .apexcharts-legend-text {
        position: relative;
        right: 1px;
        top: -3px;
    }

    .center .apexcharts-canvas {
        margin: auto;
    }

    .no-margin-bottom{
        margin-bottom: 0 !important;
    }
</style>