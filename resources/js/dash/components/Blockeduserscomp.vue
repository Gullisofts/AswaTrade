<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">الأعضاء المحظوريين</h5>

                <div class="search-sections-sp">
                    <div class="search-div-content pt-2 pb-0">
                        <label for="searchbynamevalue">لإظهار سبب الحظر ادخل آي دي العميل</label>
                        <div class="form-group row mb-0">
                            <input type="text" id="searchbycustid" class="form-control col-8" style="border-radius: .0rem .25rem .25rem .0rem;" placeholder="ID">
                            <a id="getblockreason" class="btn btn-primary text-light search_btn_st"  style="cursor:pointer; border-radius: .25rem .0rem .0rem .25rem;">بحث</a>
                        </div>
                    </div>
                </div>
                <textarea v-if="showreason == 'true'" class="form-control mt-3" id="shown_block_reason"></textarea>
                <hr class="normhr">

                <div class="show_search_res mt-3">
                    <template v-if="gottencustlength > 0">
                        <table class="table img-thumbnail">
                            <thead>
                                <th class="text-center">ID</th>
                                <th class="text-center">IP</th>
                                <th class="text-center">الاسم</th>
                                <th class="text-center">الايميل</th>
                            </thead>
                            <tbody id="res_bod">
                                
                            </tbody>
                        </table>

                    </template>
                    </div>
                    <template v-if="allshown == 'false'">
                        <div class="mt-3 text-center w-100 mb-2">
                            <button id="show_more_blocked_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
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

            lastel: 0,
            gottencustlength: 0,
            allshown: "notset",
            showreason: "false",
        }
    },
    mounted:function()
    {
        let self = this;
        self.lastel = 0;
        let lastel = self.lastel;
        self.gottencustlength = 0;
        $("#res_bod").html("");

        document.getElementById("spinner_cont").style.display="block";

        //Show First 30 results
            
        axios.post("/getblockedusers", {lastel})
        .then(function(response)
        {
            self.gottencustlength = 1;

            if(response.data == "nodata")
            {
                $(document).ready(function()
                {
                    $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                });
            }
            else if(response.data.length == 3)
            {
                self.allshown = "true";
                self.lastel = response.data[0];

                for(let i=0; i<response.data[1].data.length; i++)
                {
                    $(document).ready(function()
                    {
                        $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].id+'</td><td class="text-center">'+response.data[1].data[i].ip+'</td><td class="text-center">'+response.data[1].data[i].fullname+'</td><td class="text-center">'+response.data[1].data[i].email+'</td></tr>');
                    });
                }

            }
            else if(response.data.length == 2)
            {
                self.allshown = "false";
                self.lastel = response.data[0];

                for(let i=0; i<response.data[1].data.length; i++)
                {
                    $(document).ready(function()
                    {
                        $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].id+'</td><td class="text-center">'+response.data[1].data[i].ip+'</td><td class="text-center">'+response.data[1].data[i].fullname+'</td><td class="text-center">'+response.data[1].data[i].email+'</td></tr>');
                    });

                }

            }

            document.getElementById("spinner_cont").style.display="none";

        })
        .catch(function(error)
        {

        });


        //Show More Button Click
        $(document).on("click", "#show_more_blocked_btn", function(event)
        {
            event.preventDefault();
            document.getElementById("spinner_cont").style.display="block";

            let lastel = self.lastel;

            axios.post("/getblockedusers", {lastel})
            .then(function(response)
            {
                
                if(response.data == "nodata")
                {
                    $(document).ready(function()
                    {
                        $(".show_search_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                    });
                }
                else if(response.data.length == 3)
                {
                    self.allshown = "true";
                    self.lastel = response.data[0];

                    for(let i=0; i<response.data[1].data.length; i++)
                    {
                        $(document).ready(function()
                        {
                            $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].id+'</td><td class="text-center">'+response.data[1].data[i].ip+'</td><td class="text-center">'+response.data[1].data[i].fullname+'</td><td class="text-center">'+response.data[1].data[i].email+'</td></tr>');
                        });
                    }

                }
                else if(response.data.length == 2)
                {
                    self.allshown = "false";
                    self.lastel = response.data[0];

                    for(let i=0; i<response.data[1].data.length; i++)
                    {
                        $(document).ready(function()
                        {
                            $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].id+'</td><td class="text-center">'+response.data[1].data[i].ip+'</td><td class="text-center">'+response.data[1].data[i].fullname+'</td><td class="text-center">'+response.data[1].data[i].email+'</td></tr>');
                        });

                    }

                }

                document.getElementById("spinner_cont").style.display="none";

            })
            .catch(function(error)
            {

            });

        });

        $(document).on("click", "#getblockreason", function()
        {
            let valtosearch = $("#searchbycustid").val();

            axios.post("/getblockreason", {valtosearch})
            .then(function(response)
            {
                if(response.data == "nodata")
                {
                    Notiflix.Notify.Failure('لا يوجد عميل بهذا الآي دي');
                }
                else if(response.data == "notblocked")
                {
                    Notiflix.Notify.Failure('هذا العميل غير محظور');
                }
                else
                {
                    self.showreason = "true";

                    if(response.data == null || response.data == "")
                    {
                        $(document).ready(function()
                        {
                            $("#shown_block_reason").text("لم يتم تحديد سبب");
                        })
                    }
                    else
                    {
                        $(document).ready(function()
                        {
                            $("#shown_block_reason").text(response.data);
                        })

                    }

                }

                document.getElementById("spinner_cont").style.display="none";

            })
            .catch(function(error)
            {

            });


        });

    },

}
</script>
