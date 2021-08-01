<template>
    <div>
        <Popmessage></Popmessage>
        <Confirmation></Confirmation>
        <div class="container-fluid">
            <div class="dash_bod dash_bod_search">
                <h5 class="main_heading">البحث عن طلبات</h5>

                <ul class="ul_search mt-3">

                    <li>
                        <a class="p_search_opx clickthis" id="shippno_search">البحث برقم العملية</a>
                    </li>

                    <li>
                        <a class="p_search_opx" id="date_search">البحث بالتاريخ</a>
                    </li>

                </ul>

                <div class="mt-3">


                    <div class="search-sections" id="search-shippno">
                        <div class="search-div-content pt-2 pb-2">
                            <label>البحث برقم العملية</label>
                            <div class="form-group row bmb-0">
                                <input type="number" id="searchbyshippnovalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="رقم الطلب">
                                <a id="searchbyshippnocodest" class="btn btn-primary a_sp search_btn_st text-light" style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>

                    <div class="search-sections" id="search-date">
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

                <div class="show_search_res mt-3">
                <template v-if="gottenprodslength > 0">
                    <table class="table img-thumbnail">
                        <thead>
                            <tr>
                            <th class="text-center" scope="col">العملية</th>
                            <th class="text-center" scope="col">خيارات</th>
                            </tr>
                        </thead>
                        <tbody ref="res_bod" id="res_bod">
                            
                        </tbody>
                    </table>

                </template>
                </div>
                <template v-if="allshown == 'false'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button id="show_more_ships_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
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
import axios from "axios";
export default {

    components: {

        Popmessage,
        axios,
        Confirmation,
    },
    data() 
    {
        return {

            lastel: 0,
            gottenprods: [],
            gottenprodslength: 0,
            allshown: "notset",
            searchval: "",
            search_sec: "",
            searchtype: "",
        }
    },
    mounted:function()
    {
        let self = this;
        $(document).ready(function()
        {
            $("#search-shippno").fadeIn(1);
            $('.clickthis').parent('li').css("background", "#c8d6e5");

            $(".dash_bod .ul_search li a").on("click", function()
            {
                $(this).parent("li").css("background", "#c8d6e5");
                $(this).parent("li").siblings().css("background", "none");
            })

            //Show Section On Click Tabs
            $("#shippno_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-shippno").fadeIn(1);
            });

            $("#date_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-date").fadeIn(1);
            });
        });

        $(".search_btn_st").on("click", function()
        {
            document.getElementById("spinner_cont").style.display="block";
            self.lastel = 0;
            let searchtype = $(this).attr("ID");
            self.searchtype = searchtype;
            let searchval = self.searchval;
            let lastelement = self.lastel;

            $(".show_search_res .noresmsg").remove();
            $(".show_search_res").fadeIn(1);

            //Search By Shippno
            if(searchtype == "searchbyshippnocodest")
            {
                self.searchval = $("#searchbyshippnovalue").val();
                let searchval = self.searchval;
                axios.post("/searchshipbyshippno", {searchval, lastelement})
                .then(function(response)
                {
                    $("#res_bod").html("");
                    self.gottenprodslength = 0;
                    self.allshown = "notset";


                    if(response.data == "nodata")
                    {
                        self.gottenprodslength = 1;
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });
                    }
                    else
                    {
                        self.lastel = response.data[0];
                        self.gottenprodslength = Object.keys(response.data[1]).length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }

                        let data_keys = Object.keys(response.data[1]);
                        let data_value = Object.values(response.data[1]);

                        for(let i=0; i<data_keys.length; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }
                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });

            }

            //Search By Date
            else if(searchtype == "searchbydatest")
            {
                self.searchval = $("#fromdate").val() + "|_Y_|" + $("#todate").val();
                let searchval = self.searchval;

                axios.post("/searchbydatevalue", {searchval, lastelement})
                .then(function(response)
                {
                    $("#res_bod").html("");
                    self.gottenprodslength = 0;
                    self.allshown = "notset";


                    if(response.data == "nodata")
                    {
                        self.gottenprodslength = 1;
                        $(document).ready(function()
                        {
                            $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                        });
                    }
                    else
                    {
                        self.lastel = response.data[0];
                        self.gottenprodslength = Object.keys(response.data[1]).length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }

                        let data_keys = Object.keys(response.data[1]);
                        let data_value = Object.values(response.data[1]);

                        for(let i=0; i<data_keys.length; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i></td></tr>');
                            });
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
        $(document).on("click", "#show_more_ships_btn", function(event)
        {
            event.preventDefault();
            document.getElementById("spinner_cont").style.display="block";
            let searchtype = self.searchtype;
            let searchval = self.searchval;
            let lastelement = self.lastel;

            if(searchtype == "searchbyshippnocodest")
            {
                axios.post("/searchshipbyshippno", {searchval, lastelement})
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
                        self.gottenprodslength = Object.keys(response.data[1]).length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }


                        let data_keys = Object.keys(response.data[1]);
                        let data_value = Object.values(response.data[1]);

                        for(let i=0; i<data_keys.length; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }
                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });
            }
            else if(searchtype == "searchbydatest")
            {

                axios.post("/searchbydatevalue", {searchval, lastelement})
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
                        self.gottenprodslength = Object.keys(response.data[1]).length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }


                        let data_keys = Object.keys(response.data[1]);
                        let data_value = Object.values(response.data[1]);

                        for(let i=0; i<data_keys.length; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                });

            }

         })

         //Go To Edit Page
         $(document).on("click", ".edit_prod_pg", function()
         {
             let shipid = $(this).attr("ID");
             self.$router.push({path: "/edit_shippment/"+shipid});
         });

    },

}
</script>
