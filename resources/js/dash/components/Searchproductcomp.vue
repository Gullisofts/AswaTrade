<template>
    <div>
        <Popmessage></Popmessage>
        <Confirmation></Confirmation>
        <div class="container-fluid">
            <div class="dash_bod dash_bod_search">
                <h5 class="main_heading">البحث عن منتج</h5>

                <ul class="ul_search mt-3">

                    <li>
                        <a class="p_search_opx clickthis" id="name_search">البحث بالاسم</a>
                    </li>

                    <li>
                        <a class="p_search_opx" id="code_search">البحث بالكود</a>
                    </li>

                    <li>
                        <a class="p_search_opx" id="price_search">البحث بالسعر</a>
                    </li>

                </ul>

                <div class="mt-3">

                    <div class="search-sections" id="search-name">
                        <div class="search-div-content pt-2 pb-2">
                            <label for="searchbynamevalue">أدخل اسم المنتج</label>
                            <div class="form-group row mb-0">
                                <input type="text" id="searchbynamevalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="اسم المنتج">
                                <a id="searchbynamest" class="btn btn-primary text-light search_btn_st"  style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>

                    <div class="search-sections" id="search-code">
                        <div class="search-div-content pt-2 pb-2">
                            <label for="searchbycodevalue">ادخل كود المنتج</label>
                            <div class="form-group row mb-0">
                                <input type="number" id="searchbycodevalue" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="كود المنتج">
                                <a id="searchbycodest" class="btn btn-primary a_sp search_btn_st text-light" style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                            </div>
                        </div>
                    </div>

                    <div class="search-sections" id="search-price">
                        <div class="search-div-content search-div-content-sp">
                            <div class="mt-3">
                                <label for="searchbynamevalue">ادخل نطاق السعر</label>
                                <div class="form-group">
                                    <label class="col-4">من</label>&nbsp;<input type="text" id="fromprice" class="img-thumbnail col-8" value="100">
                                </div>
                                <div class="form-group">
                                    <label class="col-4">إلى&nbsp;</label><input type="text" id="toprice" class="img-thumbnail col-8" value="10000">
                                </div>
                            </div>

                            <div id="slider-range" class="mt-2"></div>

                            <div class="form-group text-center mt-4">
                                <a id="searchbypricest" class="btn btn-primary text-light search_btn_st text-light" style="cursor:pointer;">بحث</a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="show_search_res mt-3">
                <template v-if="gottenprodslength > 0">
                    <table class="table img-thumbnail">
                        <thead>
                            <tr>
                            <th class="text-center" scope="col">اسم المنتج</th>
                            <th class="text-center" scope="col">المبيعات</th>
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
                        <button id="show_more_prod_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
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
import Addproductcomp from "./../components/Addproductcomp.vue";
import Popmessage from "./../components/Popmessage.vue";
import Confirmation from "./../components/Confirmation.vue";
import axios from "axios";
export default {

    components: {

        Addproductcomp,
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
            $("#search-name").fadeIn(1);
            $('.clickthis').parent('li').css("background", "#c8d6e5");

            $(".dash_bod .ul_search li a").on("click", function()
            {
                $(this).parent("li").css("background", "#c8d6e5");
                $(this).parent("li").siblings().css("background", "none");
            })

            //Show Section On Click Tabs
            $("#name_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-name").fadeIn(1);
            });

            $("#code_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-code").fadeIn(1);
            });

            $("#price_search").on("click", function()
            {
                $("#res_bod").html("");
                $(".show_search_res").fadeOut(1);
                self.allshown = "noset";
                $(".search-sections").fadeOut(1);
                $("#search-price").fadeIn(1);
            });
        });

        $( function()
        {
            $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 50000,
            values: [ 100, 10000 ],
            slide: function( event, ui ) {
                $( "#fromprice" ).val(ui.values[ 0 ]);
                $( "#toprice" ).val(ui.values[ 1 ]);
            }
            });

        });

        $("#fromprice").change(function()
        {
            $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 50000,
            values: [ $("#fromprice").val(), $("#toprice").val() ],
            slide: function( event, ui ) {
                $( "#fromprice" ).val(ui.values[ 0 ]);
                $( "#toprice" ).val(ui.values[ 1 ]);
            }
            });
        });

        $("#toprice").change(function()
        {
            $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 50000,
            values: [ $("#fromprice").val(), $("#toprice").val() ],
            slide: function( event, ui ) {
                $( "#fromprice" ).val(ui.values[ 0 ]);
                $( "#toprice" ).val(ui.values[ 1 ]);
            }
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

            //Search By Name
            if(searchtype == "searchbynamest")
            {
                self.searchval = $("#searchbynamevalue").val();
                let searchval = self.searchval;
                axios.post("/searchprodbyname", {searchval, lastelement})
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
                    else if(response.data == "nullvalue")
                    {
                        Notiflix.Notify.Failure('لم يتم ادخال اي قيمة');
                    }
                    else
                    {
                        self.lastel = response.data[0];
                        self.gottenprodslength = Object.keys(response.data[1].data).length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }

                        for(let i=0; i<self.gottenprodslength; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].productname+'</td><td class="text-center">'+response.data[1].data[i].sales+'</td><td class="text-center res_table_options"><i id="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_prod_pg mr-2" style="cursor:pointer;"></i><i id="'+response.data[1].data[i].id+'" class="fas fa-trash text-danger del_prod" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });

            }

            //Search By Code
            else if(searchtype == "searchbycodest")
            {
                self.searchval = $("#searchbycodevalue").val();
                let searchval = self.searchval;

                axios.post("/searchprodbycode", {searchval, lastelement})
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
                    else if(response.data == "nullvalue")
                    {
                        Notiflix.Notify.Failure('لم يتم ادخال اي قيمة');
                    }
                    else
                    {
                        self.lastel = response.data[0];
                        self.gottenprodslength = response.data[1].data.length;

                        if(response.data.length == 3)
                        {
                            self.allshown = "true";
                        }
                        else
                        {
                            self.allshown = "false";
                        }


                        for(let i=0; i<self.gottenprodslength; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].productname+'</td><td class="text-center">'+response.data[1].data[i].sales+'</td><td class="text-center res_table_options"><i id="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_prod_pg mr-2" style="cursor:pointer;"></i><i id="'+response.data[1].data[i].id+'" class="fas fa-trash text-danger del_prod" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });
            }

            //Search By Price
            else if(searchtype == "searchbypricest")
            {
                self.searchval = $("#fromprice").val() + "|_Y_|" + $("#toprice").val();
                let searchval = self.searchval;

                axios.post("/searchprodbyprice", {searchval, lastelement})
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
                        self.gottenprodslength = response.data[1].data.length;

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

                         for(let i=0; i<self.gottenprodslength; i++)
                        {
                            $(document).ready(function()
                            {
                                $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].productname+'</td><td class="text-center">'+response.data[1].data[i].sales+'</td><td class="text-center res_table_options"><i id="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_prod_pg mr-2" style="cursor:pointer;"></i><i id="'+response.data[1].data[i].id+'" class="fas fa-trash text-danger del_prod" style="cursor:pointer;"></i></td></tr>');
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
        $(document).on("click", "#show_more_prod_btn", function(event)
        {
            event.preventDefault();
            document.getElementById("spinner_cont").style.display="block";
            let searchtype = self.searchtype;
            let searchval = self.searchval;
            let lastelement = self.lastel;

            if(searchtype == "searchbynamest")
            {
                axios.post("/searchprodbyname", {searchval, lastelement})
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
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i><i id="'+data_keys[i]+'" class="fas fa-trash text-danger del_prod" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";

                })
                .catch(function(error)
                {

                });
            }
            else if(searchtype == "searchbycodest")
            {
                document.getElementById("spinner_cont").style.display="block";
                axios.post("/searchprodbycode", {searchval, lastelement})
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
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i><i id="'+data_keys[i]+'" class="fas fa-trash text-danger del_prod" style="cursor:pointer;"></i></td></tr>');
                            });
                        }
                        
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {

                });

            }
            else if(searchtype == "searchbypricest")
            {
                document.getElementById("spinner_cont").style.display="block";
                axios.post("/searchprodbyprice", {searchval, lastelement})
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
                                $("#res_bod").append('<tr><td class="text-center">'+data_value[i]+'</td><td class="text-center res_table_options"><i id="'+data_keys[i]+'" class="fas fa-edit text-primary edit_prod_pg" style="cursor:pointer;"></i><i id="'+data_keys[i]+'" class="fas fa-trash text-danger del_prod" style="cursor:pointer;"></i></td></tr>');
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

         //Delete Product
         $(document).on("click", ".del_prod", function()
         {

             Notiflix.Confirm.Show( 'تأكيد الحذف', 'هل أنت متأكد أنك تريد حذف هذا المنتج؟', 'نعم', 'لا', function()
            { 
                axios.get("/prod_delete/"+$(this).data("class"))
                .then(function(response)
                {
                    self.$store.state.statererendercomp++;
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
            
         })
         
         //Go To Edit Page
         $(document).on("click", ".edit_prod_pg", function()
         {
             let prodid = $(this).attr("ID");
             self.$router.push({path: "/edit_product/"+prodid});
         });

    },

}
</script>
