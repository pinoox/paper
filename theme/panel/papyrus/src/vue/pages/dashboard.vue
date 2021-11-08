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
                    <column v-if="!!stats" :xs="2" :sm="2" :md="2" :lg="1">
                        <div class="single-stat purple">
                            <div class="caption">{{LANG.panel.total_written_time}}</div>
                            <div class="amount">{{stats.timeTracking.value}}</div>
                            <div class="unit">{{LANG.panel.type_time_tracking[stats.timeTracking.type]}}</div>
                        </div>
                    </column>
                    <column v-if="!!stats" :xs="3" :sm="3" :md="3" :lg="1">
                        <div class="single-stat red">
                            <div class="caption">{{LANG.panel.total_written_words}}</div>
                            <div class="amount">{{stats.words}}</div>
                            <div class="unit">{{LANG.panel.word}}</div>
                        </div>
                    </column>
                </row>
                <br>
                <div class="container">
                    <section v-if="!!stats" class="list-box-stat">
                        <div class="box-stat box" v-if="!!stats.progress">
                            <div class="text">
                                <div class="caption">{{LANG.panel.visits}} {{LANG.panel.today}}</div>
                                <div v-if="!!stats.stats" class="amount">{{stats.stats.visits}} {{LANG.panel.times}}</div>
                                <div class="footnote"
                                     :class="{'green' : stats.progress.visits>0,'red': stats.progress.visits<0 }"
                                     v-if="stats.progress.visits !== 0">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="ltr-text">% {{stats.progress.visits}}</span>
                                    {{stats.progress.visits > 0 ?
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
                        <div class="box-stat box" v-if="!!stats.progress">
                            <div class="text">
                                <div class="caption">{{LANG.panel.visitors}} {{LANG.panel.today}}</div>
                                <div v-if="!!stats.stats" class="amount">{{stats.stats.visitors}} {{LANG.panel.persons}}</div>
                                <div class="footnote"
                                     :class="{'green' : stats.progress.visitors>0,'red': stats.progress.visitors<0 }"
                                     v-if="stats.progress.visitors !== 0">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="ltr-text">% {{stats.progress.visitors}}</span>
                                    {{stats.progress.visitors > 0 ?
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
                        <router-link v-if="!!stats.commentStats" :to="{name:'comments'}" class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.comment.comments}}</div>
                                <div class="amount">{{stats.commentStats.total}} {{LANG.comment.comment_count}}</div>
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
                        <router-link v-if="!!stats.contactStats" :to="{name:'contacts'}" class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.panel.contacts}}</div>
                                <div class="amount">{{stats.contactStats.total}} {{LANG.panel.contact}}</div>
                                <div class="footnote orange" v-show="stats.contactStats.unseen>0">
                                    {{stats.contactStats.unseen}} {{LANG.panel.contact}}
                                    {{LANG.panel.contact_status.unseen}}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <simple-svg :src="_icons.call"
                                                customClassName="stroke ic_comments"/>
                                </div>
                            </div>
                        </router-link>
                    </section>

                    <section class="section" v-if="monthlyPost!=null">
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
                                               :series="monthlyPost"></apexchart>
                                </column>
                            </row>
                        </div>
                    </section>

                    <section class="section" v-if="monthly!=null">
                        <div class="section-title">
                            <h2>{{LANG.panel.total_site_stats}}</h2>
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

                    <section class="section" v-if="devices!=null">
                        <div class="section-title">
                            <h2>{{LANG.post.stats_devices}}</h2>
                        </div>
                        <div class="section-content">
                            <row :gutter="12" :columns="1" class="box-group">
                                <column :sm="1" :md="1" :lg="1">
                                    <div class="box center" v-if="devices!=null">
                                        <apexchart
                                                ref="devicesChart"
                                                type="radialBar"
                                                :width="350"
                                                :height="350"
                                                :options="radialOpts"
                                                :series="devices.percents"></apexchart>
                                    </div>
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
                monthlyPost: null,
                monthly: null,
                devices:null,
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
                unseen: {contacts: null, comments: null},
                stats: {
                    words: 0,
                    timeTracking: {
                        value: 0,
                        type: 'sec',
                    },
                },
            }
        },
        computed: {
            isNight: {
                get() {
                    let hour = new Date().getHours();
                    return hour <= 6 || hour >= 18
                }
            },
            radialOpts() {
                return {
                    chart: {
                        type: 'radialBar',
                        fontFamily: 'IranSans',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: [],
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                name: {
                                    fontSize: "22px"
                                },
                                value: {
                                    fontSize: "16px"
                                },
                                total: {
                                    show: true,
                                    label: this.LANG.panel.total,
                                    formatter: function (w) {
                                        return '100%';
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        show: true,
                        floating: true,
                        fontSize: '10px',
                        position: 'center',
                        offsetX: 0,
                        offsetY: 0,
                        formatter: function (seriesName, opts) {
                            return '%' + seriesName + ":  " + opts.w.globals.series[opts.seriesIndex];
                        },
                    },
                };
            },
        },
        methods: {
            getTime() {
                this.$http.get(this.URL.API + 'dashboard/getTime').then((json) => {
                    this.todayDate = json.data;
                });
            },
            getCountNotifies() {
                this.$http.get(this.URL.API + 'dashboard/getCountNotifies').then((json) => {
                    this.unseen = json.data;
                });
            },
            getStats() {
                return this.$http.get(this.URL.API + 'dashboard/getStats').then((json) => {
                    this.stats = json.data;
                });
            },
            getMonthly() {
                return this.$http.get(this.URL.API + 'dashboard/getMonthly/').then((json) => {
                    this.monthly = json.data.series;
                    this.monthlyOpts.xaxis.categories = json.data.date;
                });
            },
            getMonthlyPosts() {
                return this.$http.get(this.URL.API + 'dashboard/getPostMonthly/').then((json) => {
                    this.monthlyPost = json.data.series;
                    this.monthlyOpts.xaxis.categories = json.data.date;
                });
            },
            getDevices() {
                return this.$http.get(this.URL.API + 'dashboard/getDevices/').then((json) => {
                    this.devices = json.data;
                    this.radialOpts.labels = json.data.labels;
                });
            },
        },
        created() {
            this.getTime();
            this.getCountNotifies();
            this.getStats();
            this.getMonthly();
            this.getMonthlyPosts();
            this.getDevices();
        }
    }
</script>
