<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">الدعم</h5>

                <div class="search_ticket">
                    <form class="main-gen_form pl-0 pr-0">

                        <h6 class="inner_heading pt-2 pb-2">البحث عن تذكرة</h6>

                        <ul class="nav nav-tabs p-0" id="myTab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="ticketnum-tab" data-toggle="tab" href="#ticketnum" role="tab" aria-controls="ticketnum" aria-selected="true">البحث برقم التذكرة</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="ticketdate-tab" data-toggle="tab" href="#ticketdate" role="tab" aria-controls="ticketdate" aria-selected="false">البحث بالتاريخ</a>
                            </li>

                        </ul>

                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="ticketnum" role="tabpanel" aria-labelledby="ticketnum-tab">
                                <div class="form-group row">

                                    <div class="col-md-3 col-8">
                                        <input type="number" class="form-control" id="ticket_id_input" placeholder="أدخل رقم التذكرة">
                                    </div>

                                    <button id="searchticketnumst" class="btn btn-primary search_btn_st col-md-1 col-2">ابحث</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ticketdate" role="tabpanel" aria-labelledby="ticketdate-tab">
                                <div class="search-div-content search-div-content-sp pt-2 pb-2">
                                    <div class="mt-3">
                                        <p class="mb-0 col-12 mb-2">نطاق التاريخ:</p>
                                        <div class="form-group">
                                            <label class="col-4 font-weight-bold">من</label><input class="form-control" type="datetime-local" id="fromdate">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-4 font-weight-bold">إلى</label><input class="form-control" type="datetime-local" id="todate">
                                        </div>
                                    </div>

                                    <div class="form-group text-center mt-4">
                                        <a id="searchbydatest" class="btn btn-primary text-light search_btn_st text-light" style="cursor:pointer;">بحث</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="unanswered_tickets mt-3 mb-5">
                    <h6 class="inner_heading pt-2 pb-2">تذاكر لم يتم الرد عليها</h6>

                    <table class="table img-thumbnail">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">رقم التذكرة</th>
                                <th class="text-center" scope="col">خيارات</th>
                            </tr>
                        </thead>
                        <tbody class="tickets_bod" id="tickets_bod">
                            
                        </tbody>
                    </table>
                </div>
                <template v-if="allshown == 'false'">
                    <div class="mt-3 text-center w-100 mb-5">
                        <button id="show_more_tickets_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
                    </div>
                </template>
                <template v-if="allshown == 'true'">
                    <div class="mt-3 text-center w-100 mb-5">
                        <button class="btn btn-primary show_more text-light" style="cursor:pointer;" disabled>نهاية النتائج</button>
                    </div>
                </template>
            </div>
        </div> 
    </div> 
</template>
<script>

import Dashsidebar from "./../components/Dashsidebar.vue";
import axios from "axios";

export default {

    components: {
        Dashsidebar,
        axios,
    },
    data() 
    {
        return {

            lastticket: 0,
            allshown: 'notset',

        }
    },
    mounted:function()
    {
        document.getElementById("spinner_cont").style.display="block";
        let self = this;
        $("#tickets_bod").html("");
        let lastticket = self.lastticket;
        let tickets = "";
        let thisticket = "";
        let searchtype ="";
        let datefrom = "";
        let dateto = "";
        let ticketnum = "";
        let getmoretype ="normal";


        //Get First Tickets
        axios.post("/getunansweredtickets", {lastticket})
        .then(function(response)
        {
            if(response.data == "nodata")
            {
                $("#tickets_bod").append('<tr><td colspan="2" class="text-center">لا يوجد تذاكر</td></tr>');
                self.allshown = "true";
            }
            else
            {

                if(response.data.length == 2)
                {
                    self.lastticket = response.data[0];
                    lastticket = response.data[0];
                    tickets = response.data[1].data;
                    self.allshown = "false";

                    for(let i=0; i<tickets.length; i++)
                    {
                        $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                   }

                }
                else
                {
                    self.lastticket = response.data[0];
                    lastticket = response.data[0];
                    self.allshown = "true";
                    tickets = response.data[1].data;

                    for(let i=0; i<tickets.length; i++)
                    {

                        $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options"><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                    }
                }

                
            }

            document.getElementById("spinner_cont").style.display="none";

        })
        .catch(function(error)
        {

        });

        //Get More Tickets
        $(document).on("click", "#show_more_tickets_btn", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            e.stopImmediatePropagation();
            e.stopPropagation();

            if(getmoretype == "normal")
            {
                //Get First Tickets
                axios.post("/getunansweredtickets", {lastticket})
                .then(function(response)
                {
                    if(response.data == "nodata")
                    {
                        $("#tickets_bod").append('<tr><td colspan="2" class="text-center">لا يوجد تذاكر</td></tr>');
                        
                        self.allshown = "true";
                    }
                    else
                    {
                        if(response.data.length == 2)
                        {
                            self.lastticket = response.data[0];
                            lastticket = response.data[0];
                            self.allshown = "false";
                            tickets = response.data[1].data;

                            for(let i=0; i<tickets.length; i++)
                            {

                                $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options"><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                            }
                        }
                        else
                        {
                            self.lastticket = response.data[0];
                            lastticket = response.data[0];
                            self.allshown = "true";
                            tickets = response.data[1].data;

                            for(let i=0; i<tickets.length; i++)
                            {

                                $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options"><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                            }
                        }
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                });
            }
            else if(getmoretype == "sp")
            {

                //Start Search By Tickets Date Range
                //Get Dates
                axios.post("/getticketsbydate", {lastticket, datefrom, dateto})
                .then(function(response)
                {
                    if(response.data == "nodata")
                    {
                        $("#tickets_bod").append('<tr><td colspan="2" class="text-center">لا يوجد تذاكر</td></tr>');

                        self.allshown = "true";
                    }
                    else
                    {
                        if(response.data.length == 2)
                        {
                            self.lastticket = response.data[0];
                            lastticket = response.data[0];
                            self.allshown = "false";
                            tickets = response.data[1].data;

                            for(let i=0; i<tickets.length; i++)
                            {

                                if(tickets[i].status == "closed")
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="fas text-success fa-lock-open mr-3 reopenticket" title="اعادة فتح التذكرة"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                                else
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                            }
                        }
                        else
                        {
                            self.lastticket = response.data[0];
                            lastticket = response.data[0];
                            self.allshown = "true";
                            tickets = response.data[1].data;

                            for(let i=0; i<tickets.length; i++)
                            {

                                if(tickets[i].status == "closed")
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="fas text-success fa-lock-open mr-3 reopenticket" title="اعادة فتح التذكرة"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                                else
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                            }
                        }
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                });
                
            }
        })

        //Search
        $(document).on("click", ".search_btn_st", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            e.stopImmediatePropagation();
            e.stopPropagation();
            e.preventDefault();
            self.lastticket = 0;
            lastticket = self.lastticket;
            tickets = "";
            thisticket = "";
            $("#tickets_bod").html("");
            getmoretype = "sp";

            //search type
            searchtype = $(this).attr("ID");

            if(searchtype == "searchticketnumst")
            {
                //Start Search By Ticket Number
                ticketnum = $("#ticket_id_input").val();
            
                axios.post("/getticketsbyticketnum", {ticketnum})
                .then(function(response)
                {
                    if(response.data == "nodata")
                    {
                        $("#tickets_bod").append('<tr><td colspan="2" class="text-center">لا يوجد تذاكر</td></tr>');
                        self.allshown = "true";
                    }
                    else
                    {
                        self.lastticket = response.data[0];
                        lastticket = response.data[0];
                        self.allshown = "true";
                        tickets = response.data[1];

                        if(tickets.status == "closed")
                        {
                            $("#tickets_bod").append('<tr><td class="text-center">'+tickets.ticket_id+'</td><td class="text-center ticket_options"><i data-class="'+tickets.ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets.ticket_id+'" class="fas text-success fa-lock-open mr-3 reopenticket" title="اعادة فتح التذكرة"></i><i data-class="'+tickets.ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                        }
                        else
                        {
                            $("#tickets_bod").append('<tr><td class="text-center">'+tickets.ticket_id+'</td><td class="text-center ticket_options"><i data-class="'+tickets.ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets.ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets.ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                });
                
            }
            else
            {
                //Start Search By Tickets Date Range
                //Get Dates
                datefrom = $("#fromdate").val();
                dateto = $("#todate").val();
            
                axios.post("/getticketsbydate", {lastticket, datefrom, dateto})
                .then(function(response)
                {
                    if(response.data == "nodata")
                    {
                        $("#tickets_bod").append('<tr><td colspan="2" class="text-center">لا يوجد تذاكر</td></tr>');
                        self.allshown = "true";
                    }
                    else
                    {

                        if(response.data.length == 2)
                        {
                            self.lastticket = response.data[0];
                            lastticket = response.data[0];
                            tickets = response.data[1].data;
                            self.allshown = "false";

                            for(let i=0; i<tickets.length; i++)
                            {
                                if(tickets[i].status == "closed")
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="fas text-success fa-lock-open mr-3 reopenticket" title="اعادة فتح التذكرة"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                                else
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                            }

                        }
                        else
                        {
                            self.lastticket = response.data[0];
                            lastticket = response.data[0];
                            self.allshown = "true";
                            tickets = response.data[1].data;

                            for(let i=0; i<tickets.length; i++)
                            {

                                if(tickets[i].status == "closed")
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="fas text-success fa-lock-open mr-3 reopenticket" title="اعادة فتح التذكرة"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                                else
                                {
                                    $("#tickets_bod").append('<tr><td class="text-center">'+tickets[i].ticket_id+'</td><td class="text-center ticket_options" ><i data-class="'+tickets[i].ticket_id+'" class="fas text-primary viewticket fa-eye"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-success closeticket fa-check-circle"></i><i data-class="'+tickets[i].ticket_id+'" class="mr-3 fas text-danger deleteticket fa-trash"></i></td></tr>');
                                }
                            }
                        }

                        
                    }

                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });

            }
            
        });


        //View Ticket
        $(document).on("click", ".viewticket", function()
        {
            self.$router.push({path:"/answerticket/"+$(this).data("class")});
        });

        //Close Ticket
        $(document).on("click", ".closeticket", function()
        {
            thisticket = $(this).data("class");
            Notiflix.Confirm.Show( 'تأكيد الاغلاق', 'بإغلاق التذكرة سيتم اعتبارها كمجابة، هل تريد اغلاق هذه التذكرة؟', 'نعم', 'لا', function()
            { 
                axios.get("/ticket_close/"+thisticket)
                .then(function(response)
                {
                   location.reload();
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });

        //Delete Ticket
        $(document).on("click", ".deleteticket", function()
        {
            thisticket = $(this).data("class");
            Notiflix.Confirm.Show( 'تأكيد الحذف', 'هل تريد حذف هذه التذكرة نهائيا؟', 'نعم', 'لا', function()
            { 
                axios.get("/ticket_delete/"+thisticket)
                .then(function(response)
                {
                    location.reload();
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });

        //Re-Open Ticket
        $(document).on("click", ".reopenticket", function()
        {
            thisticket = $(this).data("class");
            Notiflix.Confirm.Show( 'تأكيد إعادة الفتح', 'هل تريد إعادة فتح التذكرة؟', 'نعم', 'لا', function()
            { 
                axios.get("/ticket_reopen/"+thisticket)
                .then(function(response)
                {
                    location.reload();
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });

    },
    methods:
    {
        refreshpg:function(event)
        {
            event.preventDefault();
            location.reload();
        },
    }

}
</script>