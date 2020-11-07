<template>
    <section class="page">
        <simplebar class="simplebar">
            <div class="dashboard">
                <row :gutter="10" :columns="5">
                    <column :xs="5" :sm="5" :md="5" :lg="3">
                        <div class="greeting" :class="{'night':isNight}">
                            <div class="art">
                                <img v-if="isNight" src="@img/svg/art_moon.svg" alt="moon">
                                <img v-else src="@img/svg/art_sun.svg" alt="sun">
                            </div>
                            <div class="text">
                                <div class="title">
                                    <span class="hello">{{LANG.panel.hello}}</span>
                                    <span class="user">{{USER.fname}}</span>
                                    <span class="date">{{LANG.panel.today}} {{todayDate}}</span>
                                </div>
                                <div class="message">{{LANG.panel.greeting_message}}</div>
                            </div>
                            <div class="action">
                                <router-link :to="{name:'write'}" class="btn btn-sm btn-outline-primary">
                                    {{LANG.panel.get_started}}
                                </router-link>
                            </div>
                        </div>
                    </column>
                    <column :xs="2" :sm="2" :md="2" :lg="1">
                        <div class="single-stat purple">
                            <div class="caption">کل زمان سپری شده برای نوشتن</div>
                            <div class="amount">2</div>
                            <div class="unit">ساعت</div>
                        </div>
                    </column>
                    <column :xs="3" :sm="3" :md="3" :lg="1">
                        <div class="single-stat red">
                            <div class="caption">{{LANG.panel.total_written_words}}</div>
                            <div class="amount">{{stats.words}}</div>
                            <div class="unit">{{LANG.panel.word}}</div>
                        </div>
                    </column>
                </row>
                <br>
                <row :gutter="10" :columns="3" v-if="!!stats.postStats">
                    <column :sm="1" :md="1" :lg="1">
                        <div class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.panel.visits}} {{LANG.panel.today}}</div>
                                <div class="amount">{{stats.postStats.visits}} {{LANG.panel.times}}</div>
                                <div class="footnote"
                                     :class="{'green' : stats.postProgress.visits>0,'red': stats.postProgress.visits<0 }"
                                     v-if="stats.postProgress.visits !== 0">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="ltr-text">% {{stats.postProgress.visits}}</span>    {{stats.postProgress.visits > 0 ?
                                    LANG.panel.asc_progress : LANG.panel.desc_progress }}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <simple-svg :src="_icons.eye"
                                                customClassName="stroke"/>
                                </div>
                            </div>
                        </div>
                    </column>
                    <column :sm="1" :md="1" :lg="1">
                        <div class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.panel.visitors}} {{LANG.panel.today}}</div>
                                <div class="amount">{{stats.postStats.visitors}} {{LANG.panel.persons}}</div>
                                <div class="footnote"
                                     :class="{'green' : stats.postProgress.visitors>0,'red': stats.postProgress.visitors<0 }"
                                     v-if="stats.postProgress.visitors !== 0">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="ltr-text">% {{stats.postProgress.visitors}}</span>  {{stats.postProgress.visitors > 0 ?
                                    LANG.panel.asc_progress : LANG.panel.desc_progress }}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <simple-svg :src="_icons.users"
                                                customClassName="fill ic_users"/>
                                </div>
                            </div>
                        </div>
                    </column>
                    <column :sm="1" :md="1" :lg="1">
                        <router-link :to="{name:'comments'}" class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.comment.comments}}</div>
                                <div class="amount">{{stats.commentStats.total}} {{LANG.comment.comment}}</div>
                                <div class="footnote orange" v-show="stats.commentStats.pending>0">
                                    {{stats.commentStats.pending}} {{LANG.comment.comment}}
                                    {{LANG.comment.status.pending}}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <simple-svg :src="_icons.comment"
                                                customClassName="stroke ic_comments"/>
                                </div>
                            </div>
                        </router-link>
                    </column>
                </row>

                <div class="container">
                    <section class="section" v-if="monthly!=null">
                        <div class="section-title">
                            <h2>{{LANG.panel.total_posts_stats}}</h2>
                        </div>
                        <div class="section-content">
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
                </div>
            </div>
        </simplebar>
    </section>
</template>

<script>

    export default {
        data() {
            return {
                todayDate: null,
                monthly: null,
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
                            columnWidth: '40%',
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
                unseen: {contacts: null, comments: null},
                stats: {words: 0},
            }
        },
        computed: {
            isNight: {
                get() {
                    let hour = new Date().getHours();
                    return hour < 6 && hour > 18
                }
            }
        },
        methods: {
            getTime() {
                this.$http.post(this.URL.API + 'dashboard/getTime').then((json) => {
                    this.todayDate = json.data;
                });
            },
            getCountNotifies() {
                this.$http.post(this.URL.API + 'dashboard/getCountNotifies').then((json) => {
                    this.unseen = json.data;
                });
            },
            getStats() {
                return this.$http.post(this.URL.API + 'dashboard/getStats').then((json) => {
                    this.stats = json.data;
                });
            },
            getMonthly() {
                return this.$http.post(this.URL.API + 'post/getMonthly/').then((json) => {
                    this.monthly = json.data.series;
                    this.monthlyOpts.xaxis.categories = json.data.date;
                });
            },
        },
        created() {
            this.getTime();
            this.getCountNotifies();
            this.getStats();
            this.getMonthly();
        }
    }
</script>
