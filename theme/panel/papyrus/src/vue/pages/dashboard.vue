<template>
    <section class="page">
        <simplebar class="simplebar">
            <div class="dashboard">
                <row :gutter="10" :columns="5">
                    <column :sm="4" :md="3" :lg="3">
                        <div class="greeting">
                            <div class="art">
                                <img src="@img/svg/art_sun.svg" alt="moon">
                            </div>
                            <div class="text">
                                <div class="title">
                                    <span class="hello">{{LANG.panel.hello}}</span>
                                    <span class="user">{{USER.fname}}</span>
                                    <span class="date">{{LANG.panel.today}} سه شنبه 13 آبان 1399</span>
                                </div>
                                <div class="message">{{LANG.panel.greeting_message}}</div>
                            </div>
                            <div class="action">
                                <div class="btn btn-sm btn-outline-primary">{{LANG.panel.get_started}}</div>
                            </div>
                        </div>
                    </column>
                    <column :sm="4" :md="1" :lg="1">
                        <div class="single-stat purple">
                            <div class="caption">کل زمان سپری شده برای نوشتن</div>
                            <div class="amount">2</div>
                            <div class="unit">ساعت</div>
                        </div>
                    </column>
                    <column :sm="4" :md="1" :lg="1">
                        <div class="single-stat red">
                            <div class="caption">{{LANG.panel.total_written_words}}</div>
                            <div class="amount">{{stats.words}}</div>
                            <div class="unit">{{LANG.panel.word}}</div>
                        </div>
                    </column>
                </row>
                <br>
                <row :gutter="10" :columns="3">
                    <column :sm="1" :md="1" :lg="1">
                        <div class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.panel.visits}} {{LANG.panel.today}}</div>
                                <div class="amount">2265 {{LANG.panel.times}}</div>
                                <div class="footnote red"><i class="fas fa-chart-line"></i> 12% کاهش نسبت به دیروز</div>
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
                                <div class="amount">265 {{LANG.panel.persons}}</div>
                                <div class="footnote green"><i class="fas fa-chart-line"></i> 36% افزایش نسبت به دیروز
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
                        <div class="box-stat box">
                            <div class="text">
                                <div class="caption">{{LANG.comment.comments}} {{LANG.panel.today}}</div>
                                <div class="amount">2 {{LANG.comment.comment}}</div>
                                <div class="footnote orange">2 {{LANG.comment.comment}}
                                    {{LANG.comment.status.pending}}
                                </div>
                            </div>
                            <div class="icon">
                                <div class="bg">
                                    <simple-svg :src="_icons.comment"
                                                customClassName="stroke ic_comments"/>
                                </div>
                            </div>
                        </div>
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
        methods: {
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
            this.getCountNotifies();
            this.getStats();
            this.getMonthly();
        }
    }
</script>
