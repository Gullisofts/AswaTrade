<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Popmessage></Popmessage>
        <Confirmation></Confirmation>
        <Confirmationcustomer></Confirmationcustomer>
        <Confirmationcustomerunblock></Confirmationcustomerunblock>
        <div class="container-fluid">
            <div class="dash_bod dash_bod_search">
                <h5 class="main_heading">البحث عن عميل</h5>

                <ul class="ul_search mt-3">

                    <li>
                        <a class="p_search_opx clickthis" id="id_search">البحث بالمعرف</a>
                    </li>

                    <li>
                        <a class="p_search_opx" id="email_search">البحث بالإيميل</a>
                    </li>

                    <li>
                        <a class="p_search_opx" id="ip_search">البحث بالأي بي</a>
                    </li>

                </ul>

                <div class="mt-3">

                    <div class="search-sections" id="search-id">
                        <div class="search-div-content pt-2 pb-2">
                            <label>البحث بالمعرف</label>
                            <div class="form-group row mb-0">
                                <input type="text" id="searchbyidvalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="رقم المعرف">
                                <a id="searchbyidst" class="btn btn-primary text-light search_btn_st"  style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>

                    <div class="search-sections" id="search-email">
                        <div class="search-div-content pt-2 pb-2">
                            <label>البحث بالايميل</label>
                            <div class="form-group row mb-0">
                                <input type="email" id="searchbyemailvalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="ايميل العميل">
                                <a id="searchbyemailst" class="btn btn-primary a_sp search_btn_st text-light" style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>

                    <div class="search-sections" id="search-ip">
                        <div class="search-div-content pt-2 pb-2">
                            <label>البحث بالأي بي</label>
                            <div class="form-group row mb-0">
                                <input type="text" id="searchbyipvalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="الأي بي">
                                <a id="searchbyipst" class="btn btn-primary a_sp search_btn_st text-light" style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="show_search_res mt-3">
                <template v-if="gottencustlength > 0">
                    <table class="table img-thumbnail">
                        <tbody id="res_bod">

                        </tbody>
                    </table>

                </template>
                </div>
                <template v-if="allshown == 'false'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button id="show_more_cust_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
                    </div>
                </template>
                <template v-if="allshown == 'true'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button class="btn btn-primary show_more text-light" style="cursor:pointer;" disabled>نهاية النتائج</button>
                    </div>
                </template>
            </div>
        </div>
    </div>  
</template>
<script>
import Popmessage from "./../components/Popmessage.vue";
import Confirmation from "./../components/Confirmation.vue";
import Confirmationcustomer from "./../components/Confirmationcustomer.vue";
import Dashsidebar from "./../components/Dashsidebar.vue";
import Confirmationcustomerunblock from "./../components/Confirmationcustomerunblock.vue";


import axios from "axios";
export default {

    components: {
        Popmessage,
        Dashsidebar,
        axios,
        Confirmation,
        Confirmationcustomer,
        Confirmationcustomerunblock,
    },
    data() 
    {
        return {

            lastel: 0,
            gottencustlength: 0,
            allshown: "notset",
            searchval: "",
            search_sec: "",
            searchtype: "",
            blockid: "",
            blockreason: "",
        }
    },
    mounted:function()
    {
        let self = this;

        $(document).ready(function()
        {
            $("#search-id").fadeIn(1);
            $('.clickthis').parent('li').css("background", "#c8d6e5");

            $(".dash_bod .ul_search li a").on("click", function()
            {
                $(this).parent("li").css("background", "#c8d6e5");
                $(this).parent("li").siblings().css("background", "none");
            })

            //Show Section On Click Tabs
            $("#id_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-id").fadeIn(1);
            });

            $("#email_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-email").fadeIn(1);
            });

            $("#ip_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-ip").fadeIn(1);
            });
        });


        $(document).on("click", ".search_btn_st", function()
        {
            document.getElementById("spinner_cont").style.display="block";

            self.lastel = 0;
            let searchtype = $(this).attr("ID");
            self.searchtype = searchtype;
            self.gottencustlength = 0;

            $(".show_search_res .noresmsg").remove();
            $(".show_search_res").fadeIn(1);

            //Search By Name
            if(searchtype == "searchbyidst")
            {
                self.gottencustlength = 1;
                self.searchval = $("#searchbyidvalue").val();
                let searchval = self.searchval;
                
                axios.post("/searchcustbyid", {searchval})
                .then(function(response)
                {
                    $("#res_bod").html("");
                    self.allshown = "notset";


                    if(response.data == "nodata")
                    {
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });
                    }
                    else
                    {
                        self.allshown = "true";
                        self.lastel = response.data["id"];

                        if(response.data["block_status"] == "blocked")
                        {
                            $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">معرف العميل</th><td class="text-center">'+response.data["id"]+'</td></tr><tr><th class="text-center" scope="col">اسم العميل</th><td class="text-center">'+response.data["fullname"]+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data["email"]+'</td></tr><tr><th class="text-center" scope="col">أي بي العميل</th><td class="text-center">'+response.data["ip"]+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data["id"]+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data["id"]+'" class="fas fa-unlock text-warning unblock_cust" style="cursor:pointer;"></i></td></tr>');
                        }
                        else
                        {
                            $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">معرف العميل</th><td class="text-center">'+response.data["id"]+'</td></tr><tr><th class="text-center" scope="col">اسم العميل</th><td class="text-center">'+response.data["fullname"]+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data["email"]+'</td></tr><tr><th class="text-center" scope="col">أي بي العميل</th><td class="text-center">'+response.data["ip"]+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data["id"]+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data["id"]+'" class="fas fa-ban text-warning block_cust" style="cursor:pointer;"></i></td></tr>');
                        }
                        
                    }
                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });

            }

            //Search By Code
            else if(searchtype == "searchbyemailst")
            {
                self.searchval = $("#searchbyemailvalue").val();
                let searchval = self.searchval;
                self.gottencustlength = 1;
                document.getElementById("spinner_cont").style.display="block";

                axios.post("/searchcustbyemail", {searchval})
                .then(function(response)
                {
                    $("#res_bod").html("");
                    self.allshown = "notset";

                    if(response.data == "nodata")
                    {
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });
                    }
                    else
                    {
                        self.lastel = response.data["id"];
                        self.allshown = "true";

                        if(response.data["block_status"] == "blocked")
                        {
                            $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">معرف العميل</th><td class="text-center">'+response.data["id"]+'</td></tr><tr><th class="text-center" scope="col">اسم العميل</th><td class="text-center">'+response.data["fullname"]+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data["email"]+'</td></tr><tr><th class="text-center" scope="col">أي بي العميل</th><td class="text-center">'+response.data["ip"]+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data["id"]+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data["id"]+'" class="fas fa-unlock text-warning unblock_cust" style="cursor:pointer;"></i></td></tr>');
                        }
                        else
                        {
                            $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">معرف العميل</th><td class="text-center">'+response.data["id"]+'</td></tr><tr><th class="text-center" scope="col">اسم العميل</th><td class="text-center">'+response.data["fullname"]+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data["email"]+'</td></tr><tr><th class="text-center" scope="col">أي بي العميل</th><td class="text-center">'+response.data["ip"]+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data["id"]+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data["id"]+'" class="fas fa-ban text-warning block_cust" style="cursor:pointer;"></i></td></tr>');
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                });
            }

            //Search By Price
            else if(searchtype == "searchbyipst")
            {
                document.getElementById("spinner_cont").style.display="block";
                self.searchval = $("#searchbyipvalue").val();
                let searchval = self.searchval;
                let lastelement = self.lastel;
                self.gottencustlength = 1;

                axios.post("/searchcustbyip", {searchval, lastelement})
                .then(function(response)
                {
                    $("#res_bod").html("");
                    self.allshown = "notset";

                    if(response.data == "nodata")
                    {
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });
                    }
                    else
                    {   
                        self.lastel = response.data[0];

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }

                        
                        for(let i=0; i<response.data[1].data.length; i++)
                        {
                            if(response.data["block_status"] == "blocked")
                            {
                                $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">ID</th><td class="text-center">'+response.data[1].data[i].id+'</td></tr><tr><th class="text-center" scope="col">الاسم</th><td class="text-center">'+response.data[1].data[i].fullname+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data[1].data[i].email+'</td></tr><tr><th class="text-center" scope="col">IP</th><td class="text-center">'+response.data[1].data[i].ip+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data[1].data[i].id+'" class="fas fa-unlock text-warning block_cust" style="cursor:pointer;"></i></td></tr>');
                            }
                            else
                            {
                                $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">ID</th><td class="text-center">'+response.data[1].data[i].id+'</td></tr><tr><th class="text-center" scope="col">الاسم</th><td class="text-center">'+response.data[1].data[i].fullname+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data[1].data[i].email+'</td></tr><tr><th class="text-center" scope="col">IP</th><td class="text-center">'+response.data[1].data[i].ip+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data[1].data[i].id+'" class="fas fa-ban text-warning block_cust" style="cursor:pointer;"></i></td></tr>');
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

        //Show More Button Click
        $(document).on("click", "#show_more_cust_btn", function(event)
        {
            event.preventDefault();
            document.getElementById("spinner_cont").style.display="block";
            let searchtype = self.searchtype;
            let searchval = self.searchval;
            let lastelement = self.lastel;

            if(searchtype == "searchbyipst")
            {
                axios.post("/searchcustbyip", {searchval, lastelement})
                .then(function(response)
                {

                    if(response.data == "nodata")
                    {
                        $(document).ready(function()
                        {
                            $("#res_bod").append('<tr><td class="text-center">لا يوجد نتائج</td></tr>');
                        });
                    }
                    else
                    {
                        self.lastel = response.data[0];
                        self.gottencustlength = Object.keys(response.data[1]).length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }

                        for(let i=0; i<response.data[1].data.length; i++)
                        {
                            if(response.data["block_status"] == "blocked")
                            {
                                $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">ID</th><td class="text-center">'+response.data[1].data[i].id+'</td></tr><tr><th class="text-center" scope="col">الاسم</th><td class="text-center">'+response.data[1].data[i].fullname+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data[1].data[i].email+'</td></tr><tr><th class="text-center" scope="col">IP</th><td class="text-center">'+response.data[1].data[i].ip+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data[1].data[i].id+'" class="fas fa-unlock text-warning block_cust" style="cursor:pointer;"></i></td></tr>');
                            }
                            else
                            {
                                $("#res_bod").append('<tr style="background: #ecf0f1;"><th class="text-center" scope="col">ID</th><td class="text-center">'+response.data[1].data[i].id+'</td></tr><tr><th class="text-center" scope="col">الاسم</th><td class="text-center">'+response.data[1].data[i].fullname+'</td></tr><tr><th class="text-center" scope="col">الايميل</th><td class="text-center">'+response.data[1].data[i].email+'</td></tr><tr><th class="text-center" scope="col">IP</th><td class="text-center">'+response.data[1].data[i].ip+'</td></tr><tr><th class="text-center" scope="col">خيارات</th><td class="text-center res_table_cust_options"><i data-class="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_cust_pg" style="cursor:pointer;"></i><i data-class="'+response.data[1].data[i].id+'" class="fas fa-ban text-warning block_cust" style="cursor:pointer;"></i></td></tr>');
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

        //Block Customer
         $(document).on("click", ".block_cust", function()
         {
            $(".confirmation_msg_custsp p").text("هل أنت متأكد أنك تريد حظر هذا الحساب؟");
            $(".confirmation_msg_custsp #yes_acc").attr("data-class", $(this).data("class"));
            $(".confirmation_msg_custsp").fadeIn();

         });

        //Block (This Account Only) Customer Confirmation MSG - Approve
        $(document).on("click", ".confirmation_msg_custsp #yes_acc", function(e)
        {
            e.stopImmediatePropagation();
            e.stopPropagation();

            let cust_id = $(this).data("class")
            let block_reason = $("#cust_reason").val();
 
            axios.post("/cust_block", {cust_id, block_reason})
            .then(function(response)
            {
                $(".confirmation_msg_custsp").fadeOut();
                Notiflix.Notify.Success('تم حظر الحساب بنجاح');
                self.$store.state.statererendercomp++;
            })
            .catch(function(error)
            {

            });
            
        });

        //Unblock Customer
         $(document).on("click", ".unblock_cust", function()
         {
            let cust_id = $(this).data("class");

            Notiflix.Confirm.Show( 'تأكيد الحذف', 'هل تريد إلغاء حظر هذا الحساب؟', 'نعم', 'لا', function()
            { 
                axios.post("/cust_unblock", {cust_id})
                .then(function(response)
                {
                    Notiflix.Notify.Success('تم فك حظر هذا الحساب بنجاح');
                    self.$store.state.statererendercomp++;
                })
                .catch(function(error)
                {

                });
            }, 
            function()
            { 
                
            }); 
            
         });

        //Delete/Block Rejection Action - Cancel
        $(document).on("click", "#noicancel", function()
        {
            $(".confirmation_msg_custsp").fadeOut();
            $(".confirmation_msg").fadeOut();
            $(".confirmation_msg_unblock").fadeOut();
        });

         //Go To Edit Page
         $(document).on("click", ".edit_cust_pg", function()
         {
             let custid = $(this).data("class");
             self.$router.push({path: "/edit_customer/"+custid});
         });

    },

}
</script>
