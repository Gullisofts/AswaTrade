<template>
    <div>
        <Dashsidebar></Dashsidebar>

        <div class="container-fluid">
            <div class="dash_bod">
            <h5 class="main_heading">الاحصائيات العامة</h5>
                <div class="cards-view">
                    <div class="row">
                        <div class="col-md-4 mt-md-0 col-12">
                            <div class="card customers_info">
                                <div class="layer">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-0"><span>{{customers}}</span> عميل</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-md-0 mt-3">
                            <div class="card shippments_info">
                                <div class="layer">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-0"><span>{{shippments}}</span> شحنة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mt-md-0 mt-3">
                            <div class="card tickets_info">
                                <div class="layer">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-0"><span>{{tickets}}</span> تذكرة غير مجابة</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="charts-view mt-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-12">
                            <h6 class="mb-4 inner_heading">عدد الشحنات في الأسبوع</h6>
                            <div id="chart_shippments"></div>
                        </div>
                        <div class="col-lg-6 col-md-12 mt-lg-0 mt-md-5">
                            <h6 class="mb-4 inner_heading">عدد التسجيلات في الأسبوع</h6>
                            <div id="chart_registers"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import ApexCharts from 'apexcharts';
import axios from "axios";
export default {

    components: {

        Dashsidebar,
        ApexCharts,
        axios,
    },
    data() 
    {
        return {

            customers: "",
            shippments: "",
            tickets: "",
        }
    },
    mounted:function()
    {
        document.getElementById("spinner_cont").style.display="block";
        let self=this;
        //Get Charts
        axios.get("/dailycharts")
        .then(function(response)
        {
            var options = {
            series: [{
                name: "تسجيلات",
                data: response.data[1],
            }],
            chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
            },
            dataLabels: {
            enabled: false
            },
            stroke: {
            curve: 'smooth'
            },
            title: {
            text: '',
            align: 'right'
            },
            grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            },
            xaxis: {
            categories: ["السبت", "الأحد", "الأثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة"],
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart_registers"), options);
            chart.render();


            var options = {
            series: [{
            name: 'شحنة',
            data: response.data[0],
            }],
            chart: {
            height: 350,
            type: 'bar',
            },
            plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
            }
            },
            dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val;
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
            },
            
            xaxis: {
            categories: ["السبت", "الأحد", "الأثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة"],
            position: 'top',
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            crosshairs: {
                fill: {
                type: 'gradient',
                gradient: {
                    colorFrom: '#D8E3F0',
                    colorTo: '#BED1E6',
                    stops: [0, 100],
                    opacityFrom: 0.4,
                    opacityTo: 0.5,
                }
                }
            },
            tooltip: {
                enabled: true,
            }
            },
            yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                formatter: function (val) {
                return val;
                }
            }
            
            },
            title: {
            text: '',
            floating: true,
            offsetY: 330,
            align: 'center',
            style: {
                color: '#444'
            }
            }
            };

            var chart = new ApexCharts(document.querySelector("#chart_shippments"), options);
            chart.render();

            //Get Cards Data
            axios.get("/cardsdata")
            .then(function(response)
            {
                self.customers = response.data[0];
                self.shippments = response.data[1];
                self.tickets = response.data[2];
            })
            .catch(function(error)
            {

            });

            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });
    },

}
</script>