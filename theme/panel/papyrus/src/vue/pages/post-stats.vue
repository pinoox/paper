<template>
    <div class="page">
        <div class="container">
            <div class="post-stats">

                <section class="section">
                    <div class="post-title">
                        <div class="label">تحلیل و آمار</div>
                        <div class="title"> روزه داری در شرایط بحران ویروس کووید19</div>
                        <div class="details">منتشر شده در 5 مرداد 1399</div>
                    </div>
                </section>

                <section class="section">
                    <div class="section-title">
                        <h2>آمار 7 روز اخیر</h2>
                    </div>
                    <div class="section-content">
                        <row :gutter="12" :columns="4" class="box-group">
                            <column :sm="2" :md="2" :lg="1">
                                <div class="box box1">
                                    <div class="details">
                                        <h3>120</h3>
                                        <h4>بازدیدها</h4>
                                    </div>
                                    <apexchart type="line"
                                               :width="220"
                                               :height="80"
                                               :options="miniBoxOpts"
                                               :series="visitorsSeries"></apexchart>
                                </div>
                            </column>
                            <column :sm="2" :md="2" :lg="1">

                                <div class="box box2">
                                    <div class="details">
                                        <h3>22</h3>
                                        <h4>بازدید کننده ها</h4>
                                    </div>
                                    <apexchart type="line"
                                               :width="220"
                                               :height="80"
                                               :options="miniBoxOpts"
                                               :series="visitorsSeries"></apexchart>
                                </div>
                            </column>
                            <column :sm="2" :md="2" :lg="1">
                                <div class="box box3">
                                    <div class="details">
                                        <h3>311</h3>
                                        <h4>نرخ مطالعه</h4>
                                    </div>
                                    <apexchart type="line"
                                               :width="220"
                                               :height="80"
                                               :options="miniBoxOpts"
                                               :series="visitorsSeries"></apexchart>
                                </div>
                            </column>
                            <column :sm="2" :md="2" :lg="1">
                                <div class="box box4">
                                    <div class="details">
                                        <h3>22</h3>
                                        <h4>لایک</h4>
                                    </div>
                                    <apexchart type="line"
                                               :width="220"
                                               :height="80"
                                               :options="miniBoxOpts"
                                               :series="visitorsSeries"></apexchart>
                                </div>
                            </column>
                        </row>
                    </div>
                </section>

                <section class="section">
                    <div class="section-content">
                        <row :gutter="12" :columns="2" class="box-group">
                            <column :sm="1" :md="1" :lg="2">

                            </column>
                            <column :sm="1" :md="1" :lg="1">
                                <div class="box">
                                    <apexchart type="radialBar"
                                               :width="350"
                                               :height="350"
                                               :options="radialOpts"
                                               :series="radialSeries"></apexchart>
                                </div>
                            </column>
                        </row>
                    </div>
                </section>

            </div>
        </div>


        <!-- <div id="wrapper">
             <div class="content-area">
                 <div class="container-fluid">
                     <div class="text-right mt-3 mb-3 d-fixed">
                         <a
                                 href="https://github.com/apexcharts/apexcharts.js/tree/master/samples/vanilla-js/dashboards/dark"
                                 target="_blank"
                                 class="btn btn-outline-warning mr-2"
                         >
                             <span class="btn-text">View Code</span>
                         </a>
                         <button class="btn btn-success" id="fusionexport-btn">
               <span
                       class="spinner-border spinner-border-sm"
                       role="status"
                       aria-hidden="true"
               ></span>
                             <span class="btn-text">Download as PDF</span>
                         </button>
                     </div>
                     <div class="main">


                         <div class="row mt-4">
                             <div class="col-md-5">
                                 <div class="box shadow mt-4">
                                     <div id="radialBarBottom"></div>
                                 </div>
                             </div>
                             <div class="col-md-7">
                                 <div class="box shadow mt-4">
                                     <div id="line-adwords" class=""></div>
                                 </div>
                             </div>
                         </div>

                         <div class="row mt-4">
                             <div class="col-md-5">
                                 <div class="box shadow mt-4">
                                     <div id="barchart"></div>
                                 </div>
                             </div>
                             <div class="col-md-7">
                                 <div class="box shadow mt-4">
                                     <div id="areachart"></div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>-->

    </div>
</template>

<script>
    export default {
        data() {
            return {
                radialOpts: {
                    chart: {
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            size: undefined,
                            inverseOrder: true,
                            hollow: {
                                margin: 5,
                                size: '50%',
                                background: 'transparent',

                            },
                            track: {
                                show: true,
                            },
                            startAngle: -180,
                            endAngle: 180

                        },
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                    labels: ['موبایل', 'تبلت', 'دسکتاپ'],
                    legend: {
                        show: true,
                        floating: true,
                        fontSize: '10px',
                        position: 'right',
                        offsetX: 0,
                        offsetY: 0,
                        formatter: function (seriesName, opts) {
                            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                        },
                    },
                },
                radialSeries: [2, 30, 68],

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
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    markers: {
                        size: 0
                    },
                    grid: {
                        padding: {
                            top: 20,
                            bottom: 10,
                            left: 110,
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
                visitorsSeries: [{
                    data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
                }],
            }
        },
        methods: {},
    }
</script>

<style lang="less">

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
</style>