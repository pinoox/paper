<template>
    <div class="page">
        <div class="container" v-if="post!=null && stats!=null">
            <div class="post-stats">

                <section class="section">
                    <div class="post-title">
                        <div class="label">{{LANG.post.analysis}}</div>
                        <div class="title">{{post.title}}</div>
                        <div class="details">{{LANG.post.published}}: {{post.approx_insert_date}}</div>
                    </div>
                </section>

                <section class="section">
                    <div class="section-title">
                        <h2>{{LANG.post.stats_7_days}}</h2>
                    </div>
                    <div class="section-content">
                        <row :gutter="50" :columns="3" class="box-group">
                            <column :sm="4" :md="4" :lg="1">
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
                            </column>
                            <column :sm="4" :md="4" :lg="1">
                                <div class="box box4">
                                    <div class="details">
                                        <h3>{{stats.visitors.total}}</h3>
                                        <h4>{{LANG.post.visitors}}</h4>
                                    </div>
                                    <apexchart type="line"
                                               :width="220"
                                               :height="80"
                                               :options="miniBoxOpts"
                                               :series="stats.visitors.series"></apexchart>
                                </div>
                            </column>
                        </row>
                    </div>
                </section>

                <section class="section" v-if="devices.total > 0">
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


    </div>
</template>

<script>
    export default {
        props: ['post_id'],
        data() {
            return {
                stats: null,
                devices: null,
                post: null,
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
                            show: false
                        },
                        y: {
                            title: {
                                formatter: function formatter(val) {
                                    return '';
                                }
                            }
                        }
                    }
                },
                radialOpts: {
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
                                    label: "مجموع",
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => {
                                            return a + b;
                                        }, 0) + ' %';
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
                            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                        },
                    },
                },
            }
        },
        methods: {
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
            getDevices() {
                return this.$http.post(this.URL.API + 'post/getDevices/' + this.post_id).then((json) => {
                    this.devices = json.data;
                    this.radialOpts.labels = json.data.labels;
                });
            },
        },
        created() {
            this.getPost().then(() => {
                return this.getStats();
            }).then(() => {
                this.getDevices();
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

</style>