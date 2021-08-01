<template>
    <div>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">تقارير المبيعات</h5>

                <div class="form-group main-gen_form">
                    <label for="reportstype" class="col-lg-2 col-md-12 nopadding-col col-form-label">أختر نوع التقارير</label>
                    <div class="col-lg-10 col-md-12 nopadding-col">
                        <select class="form-control" id="reportstype">
                            <option value="dayreports">اليوم</option>
                            <option value="weekreports">الاسبوع</option>
                            <option value="monthreports">الشهر</option>
                            <option value="yearreports">السنة</option>
                        </select>
                    </div>
                </div>

                <div class="form-group main-gen_form">
                    <p class="col-12 col-form-label nopadding-col mb-0">بحث مخصص</p>
                    <div class="col-md-6 col-sm-12 nopadding-col mb-md-0 mb-2">
                        <label for="searchreportsfrom">من</label>
                        <input class="form-control" type="date" id="searchreportsfrom">
                    </div>
                    <div class="col-md-6 col-sm-12 nopadding-col">
                        <label for="searchreportsto">إلى</label>
                        <input class="form-control" type="date" id="searchreportsto">
                    </div>
                    <div class="form-group col-12 nopadding-col mt-2">
                        <button class="btn btn-primary" id="searchspecreports">بحث</button>
                    </div>
                </div>

                <div>
                    <h6 class="mt-3 mb-4 subsection_heading">المبيعات</h6>
                    <p class="font-weight-bold img-thumbnail">عدد المبيعات: {{salescount}}</p>
                    <div class="row chart_cont">
                        <div class="chart_view chart_view_chartsales col-lg-4 col-md-6 col-12">
                            <canvas id="chartsales" dir="rtl"></canvas>
                        </div>
                    </div>
                </div>


                <div>
                    <h6 class="mt-3 mb-4 subsection_heading">الأرباح التقديرية</h6>
                    <div class="row chart_cont">
                        <div class="chart_view chart_view_chartprofits col-12">
                            <canvas id="chartprofits" dir="rtl"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h6 class="mt-3 mb-4 subsection_heading">العملاء</h6>
                    <div class="row chart_cont">
                        <div class="chart_view chart_view_chartcustomers col-12">
                            <canvas id="chartcustomers" dir="rtl"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h6 class="mt-3 mb-4 subsection_heading">المنتجات الأكثر مبيعاً</h6>
                    <div class="row ">
                        <div class="most_selling_products col-12">
                            <ul v-if="popularprods != ''" class="list-group">
                                <li v-for="prod in popularprods" :key="prod.id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold">{{prod.productname}}</span>
                                    <span style="min-width: 30px" class="badge bg-primary text-light rounded-pill">{{prod.sales}}</span>
                                </li>
                            </ul>
                            <p v-if="popularprods == ''" class="font-weight-bold mb-0 text-center">لا يوجد مبيعات حتى الآن</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Popmessage from "./../components/Popmessage.vue";
import axios from "axios";
import ApexCharts from 'apexcharts';
import {
  Chart,
  ArcElement,
  LineElement,
  BarElement,
  PointElement,
  BarController,
  BubbleController,
  DoughnutController,
  LineController,
  PieController,
  PolarAreaController,
  RadarController,
  ScatterController,
  CategoryScale,
  LinearScale,
  LogarithmicScale,
  RadialLinearScale,
  TimeScale,
  TimeSeriesScale,
  Decimation,
  Filler,
  Legend,
  Title,
  Tooltip,
} from 'chart.js';

Chart.register(
  ArcElement,
  LineElement,
  BarElement,
  PointElement,
  BarController,
  BubbleController,
  DoughnutController,
  LineController,
  PieController,
  PolarAreaController,
  RadarController,
  ScatterController,
  CategoryScale,
  LinearScale,
  LogarithmicScale,
  RadialLinearScale,
  TimeScale,
  TimeSeriesScale,
  Decimation,
  Filler,
  Legend,
  Title,
  Tooltip
);

export default {

    components: {

        Dashsidebar,
        axios,
        ApexCharts,
        Popmessage,
    },
    data() 
    {
        return {

            popularprods: "",
            salescount: "",
        }
    },
    mounted:function()
    {
        let self = this;
        var ctx = document.getElementById('chartsales');
        var ctx2 = document.getElementById('chartprofits');
        var ctx3 = document.getElementById('chartcustomers');
        document.getElementById("spinner_cont").style.display="block";

        //Get Day Chart
        axios.get("/daycharts")
        .then(function(response)
        {
            self.salescount = response.data[response.data.length - 1];
            if(response.data[0] == 0)
            {
                $("#chartsales").remove();
                $(".chart_view_chartsales p").remove();
                $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
            }
            else
            {
                var saleschart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                        datasets: [
                            {
                                label: 'set',
                                data: response.data[0],
                                backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                            },
                        ],


                    },
                    options: {
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: ''
                        }
                        }
                    },
                });
            }

            ////////////////////////////////
            var profitschart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
                    datasets: [
                        {
                        label: "المبيعات",
                        backgroundColor: "#2ecc71",
                        borderColor: "#2ecc71",
                        borderWidth: 2,
                        data: response.data[1],
                        tension: 0.1,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: "top"
                    },
                    title: {
                        display: true,
                        text: "Chart.js Bar Chart"
                    },
                    scales: {
                        yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                        }]
                    },
                    legend: {
                        rtl: true,
                    },
                }
            });

            ////////////////////////////////
            var customerschart = new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
                    datasets: [
                        {
                        label: "التسجيلات الجديدة",
                        backgroundColor: "#2ecc71",
                        borderColor: "#2ecc71",
                        borderWidth: 2,
                        data: response.data[2],
                        tension: 0.1,
                        },

                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: "top"
                    },
                    title: {
                        display: true,
                        text: "Chart.js Bar Chart"
                    },
                    scales: {
                        yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                        }]
                    },
                    legend: {
                        rtl: true,
                    },
                }
            });
            
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {   

        });


        //On Change Reports Type
        $("#reportstype").on("change", function()
        {
            document.getElementById("spinner_cont").style.display="block";
            $(".chart_view_chartsales p").remove();

            $("#chartsales").remove();
            $("#chartprofits").remove();
            $("#chartcustomers").remove();

            $(".chart_view_chartsales").append('<canvas id="chartsales" dir="rtl"></canvas>');
            $(".chart_view_chartprofits").append('<canvas id="chartprofits" dir="rtl"></canvas>');
            $(".chart_view_chartcustomers").append('<canvas id="chartcustomers" dir="rtl"></canvas>');

            var ctx = document.getElementById('chartsales');
            var ctx2 = document.getElementById('chartprofits');
            var ctx3 = document.getElementById('chartcustomers');
            
            if($(this).val() == "dayreports")
            {
                axios.get("/daycharts")
                .then(function(response)
                {
                    self.salescount = response.data[response.data.length - 1];
                    document.getElementById("spinner_cont").style.display="none";

                    if(response.data[0] == 0)
                    {
                        $("#chartsales").remove();
                        $(".chart_view_chartsales p").remove();
                        $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
                    }
                    else
                    {
                        var saleschart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                                datasets: [
                                    {
                                        label: 'set',
                                        data: response.data[0],
                                        backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                                    },
                                ],


                            },
                            options: {
                                responsive: true,
                                plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: ''
                                }
                                }
                            },
                        });
                    }
                    ////////////////////////////////
                    var profitschart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
                            datasets: [
                                {
                                label: "المبيعات",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[1],
                                tension: 0.1,
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });

                    ////////////////////////////////
                    var customerschart = new Chart(ctx3, {
                        type: 'line',
                        data: {
                            labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
                            datasets: [
                                {
                                label: "المسجلين",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[2],
                                tension: 0.1,
                                },

                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });
                })
                .catch(function(error)
                {   
                    document.getElementById("spinner_cont").style.display="none";
                });
            }
            else if($(this).val() == "weekreports")
            {

                axios.get("/weekcharts")
                .then(function(response)
                {
                    self.salescount = response.data[response.data.length - 1];
                    document.getElementById("spinner_cont").style.display="none";

                    if(response.data[0] == 0)
                    {
                        $("#chartsales").remove();
                        $(".chart_view_chartsales p").remove();
                        $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
                    }
                    else
                    {
                        var saleschart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                                datasets: [
                                    {
                                        label: 'set',
                                        data: response.data[0],
                                        backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                                    },
                                ],


                            },
                            options: {
                                responsive: true,
                                plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: ''
                                }
                                }
                            },
                        });
                    }

                    ////////////////////////////////
                    var profitschart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                            datasets: [
                                {
                                label: "المبيعات",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[1],
                                tension: 0.1,
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });

                    ////////////////////////////////
                    var customerschart = new Chart(ctx3, {
                        type: 'line',
                        data: {
                            labels: ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                            datasets: [
                                {
                                label: "المسجلين",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[2],
                                tension: 0.1,
                                },

                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });
                
                })
                .catch(function(error)
                {   
                    document.getElementById("spinner_cont").style.display="none";
                });
            }
            else if($(this).val() == "monthreports")
            {
                axios.get("/monthcharts")
                .then(function(response)
                {
                    self.salescount = response.data[response.data.length - 1];
                    document.getElementById("spinner_cont").style.display="none";

                    if(response.data[0] == 0)
                    {
                        $("#chartsales").remove();
                        $(".chart_view_chartsales p").remove();
                        $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
                    }
                    else
                    {
                        var saleschart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                                datasets: [
                                    {
                                        label: 'set',
                                        data: response.data[0],
                                        backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                                    },
                                ],


                            },
                            options: {
                                responsive: true,
                                plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: ''
                                }
                                }
                            },
                        });
                    }
                    ////////////////////////////////

                    var profitschart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: response.data[3],
                            datasets: [
                                {
                                label: "المبيعات",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[1],
                                tension: 0.1,
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });

                    ////////////////////////////////
                    var customerschart = new Chart(ctx3, {
                        type: 'line',
                        data: {
                            labels: response.data[3],
                            datasets: [
                                {
                                label: "المسجلين",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[2],
                                tension: 0.1,
                                },

                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });
                })
                .catch(function(error)
                {
                    document.getElementById("spinner_cont").style.display="none";
                });
            }
            else if($(this).val() == "yearreports")
            {
                axios.get("/yearcharts")
                .then(function(response)
                {
                    self.salescount = response.data[response.data.length - 1];
                    document.getElementById("spinner_cont").style.display="none";

                    if(response.data[0] == 0)
                    {
                        $("#chartsales").remove();
                        $(".chart_view_chartsales p").remove();
                        $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
                    }
                    else
                    {
                        var saleschart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                                datasets: [
                                    {
                                        label: 'set',
                                        data: response.data[0],
                                        backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                                    },
                                ],


                            },
                            options: {
                                responsive: true,
                                plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: ''
                                }
                                }
                            },
                        });
                    }

                    ////////////////////////////////

                    var profitschart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Dec'],
                            datasets: [
                                {
                                label: "المبيعات",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[1],
                                tension: 0.1,
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });

                    ////////////////////////////////
                    var customerschart = new Chart(ctx3, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Dec'],
                            datasets: [
                                {
                                label: "المسجلين",
                                backgroundColor: "#2ecc71",
                                borderColor: "#2ecc71",
                                borderWidth: 2,
                                data: response.data[2],
                                tension: 0.1,
                                },

                            ]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: "top"
                            },
                            title: {
                                display: true,
                                text: "Chart.js Bar Chart"
                            },
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                            legend: {
                                rtl: true,
                            },
                        }
                    });
                })
                .catch(function(error)
                {
                    document.getElementById("spinner_cont").style.display="none";
                });
            }

        });

        $("#searchspecreports").on("click", function()
        {
            let fromdate = $("#searchreportsfrom").val();
            let todate = $("#searchreportsto").val();

            document.getElementById("spinner_cont").style.display="block";

            $(".chart_view_chartsales p").remove();

            $("#chartsales").remove();
            $("#chartprofits").remove();
            $("#chartcustomers").remove();

            $(".chart_view_chartsales").append('<canvas id="chartsales" dir="rtl"></canvas>');
            $(".chart_view_chartprofits").append('<canvas id="chartprofits" dir="rtl"></canvas>');
            $(".chart_view_chartcustomers").append('<canvas id="chartcustomers" dir="rtl"></canvas>');

            var ctx = document.getElementById('chartsales');
            var ctx2 = document.getElementById('chartprofits');
            var ctx3 = document.getElementById('chartcustomers');

            axios.post("/getspecreports", {fromdate, todate})
            .then(function(response)
            {
                self.salescount = response.data[response.data.length - 1];
                document.getElementById("spinner_cont").style.display="none";

                if(response.data == "exceeded")
                {
                    Notiflix.Notify.Failure('ينبغي أن يكون نطاق البحث 30 يوم كحد أقصى');
                }
                else if(response.data == "datesempty")
                {
                    Notiflix.Notify.Failure('تأكد من إدخال تاريخ البداية والنهاية');
                }
                else if(response.data == "datelogic")
                {
                    Notiflix.Notify.Failure('يبنبغي أن لا يكون تاريخ البداية أكبر من تاريخ النهاية!');
                }
                else
                {
    
                    if(response.data[3] == "day")
                    {
                        if(response.data[0] == 0)
                        {
                            $("#chartsales").remove();
                            $(".chart_view_chartsales p").remove();
                            $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
                        }
                        else
                        {
                            var saleschart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                                    datasets: [
                                        {
                                            label: 'set',
                                            data: response.data[0],
                                            backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                                        },
                                    ],


                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: ''
                                    }
                                    }
                                },
                            });
                        }

                        ////////////////////////////////
                        var profitschart = new Chart(ctx2, {
                            type: 'line',
                            data: {
                                labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
                                datasets: [
                                    {
                                    label: "المبيعات",
                                    backgroundColor: "#2ecc71",
                                    borderColor: "#2ecc71",
                                    borderWidth: 2,
                                    data: response.data[1],
                                    tension: 0.1,
                                    },
                                ]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    position: "top"
                                },
                                title: {
                                    display: true,
                                    text: "Chart.js Bar Chart"
                                },
                                scales: {
                                    yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                    }]
                                },
                                legend: {
                                    rtl: true,
                                },
                            }
                        });

                        ////////////////////////////////
                        var customerschart = new Chart(ctx3, {
                            type: 'line',
                            data: {
                                labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
                                datasets: [
                                    {
                                    label: "التسجيلات الجديدة",
                                    backgroundColor: "#2ecc71",
                                    borderColor: "#2ecc71",
                                    borderWidth: 2,
                                    data: response.data[2],
                                    tension: 0.1,
                                    },

                                ]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    position: "top"
                                },
                                title: {
                                    display: true,
                                    text: "Chart.js Bar Chart"
                                },
                                scales: {
                                    yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                    }]
                                },
                                legend: {
                                    rtl: true,
                                },
                            }

                        });
                    }
                    else
                    {
                        
                        if(response.data[0] == 0)
                        {
                            $("#chartsales").remove();
                            $(".chart_view_chartsales p").remove();
                            $(".chart_view_chartsales").append("<p class='text-center'>لا يوجد بيانات</p>");
                        }
                        else
                        {
                            var saleschart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ["ناجحة", "مرفوضة", "قيد المراجعة"],
                                    datasets: [
                                        {
                                            label: 'set',
                                            data: response.data[0],
                                            backgroundColor: ["#1dd1a1", "#ff6b6b", "#c8d6e5"],
                                        },
                                    ],


                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: ''
                                    }
                                    }
                                },
                            });
                        }

                        ////////////////////////////////
                        var profitschart = new Chart(ctx2, {
                            type: 'line',
                            data: {
                                labels: response.data[4],
                                datasets: [
                                    {
                                    label: "المبيعات",
                                    backgroundColor: "#2ecc71",
                                    borderColor: "#2ecc71",
                                    borderWidth: 2,
                                    data: response.data[1],
                                    tension: 0.1,
                                    },
                                ]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    position: "top"
                                },
                                title: {
                                    display: true,
                                    text: "Chart.js Bar Chart"
                                },
                                scales: {
                                    yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                    }]
                                },
                                legend: {
                                    rtl: true,
                                },
                            }
                        });

                        ////////////////////////////////
                        var customerschart = new Chart(ctx3, {
                            type: 'line',
                            data: {
                                labels: response.data[4],
                                datasets: [
                                    {
                                    label: "التسجيلات الجديدة",
                                    backgroundColor: "#2ecc71",
                                    borderColor: "#2ecc71",
                                    borderWidth: 2,
                                    data: response.data[2],
                                    tension: 0.1,
                                    },

                                ]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    position: "top"
                                },
                                title: {
                                    display: true,
                                    text: "Chart.js Bar Chart"
                                },
                                scales: {
                                    yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                    }]
                                },
                                legend: {
                                    rtl: true,
                                },
                            }

                        });
                        
                    }
                }

            })
            .catch(function(error)
            {

            });

        });

        //GET MOST POPULAR PRODUCTS
        axios.get('/popularprods')
        .then(function(response) 
        {
            self.popularprods = response.data.data;
        })
        .catch(function(error)
        {

        })

    },

}
</script>