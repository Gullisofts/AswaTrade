<template>
    <div>
        <div class="container-fluid">
            <div class="dash_bod dash_bod_search">
                <h5 class="main_heading">منتجات بدون رصيد</h5>

                <div class="show_low_res mt-3">
                <template v-if="gottenprodslength > 0">
                    <table class="table img-thumbnail">
                        <thead>
                            <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">اسم المنتج</th>
                            <th class="text-center" scope="col">تعديل</th>
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
import axios from "axios";
export default {

    components: {

        axios,
    },
    data() 
    {
        return {

            lastel: 0,
            gottenprods: [],
            gottenprodslength: 0,
            allshown: "notset",
        }
    },
    created:function()
    {
        let self = this;
        self.lastel = 0;
        let lastelement = self.lastel;

        $(".show_low_res .noresmsg").remove();
        $(".show_low_res").fadeIn(1);

        axios.post("/getlowqtyprods", {lastelement})
        .then(function(response)
        {
            document.getElementById("spinner_cont").style.display="block";
            $("#res_bod").html("");
            self.gottenprodslength = 0;
            self.allshown = "notset";


            if(response.data == "nodata")
            {
                self.gottenprodslength = 1;
                $(document).ready(function()
                {
                    $(".show_low_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                });
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
                        $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].id+'</td><td class="text-center">'+response.data[1].data[i].productname+'</td><td class="text-center res_table_options"><i id="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_prod_pg mr-2" style="cursor:pointer;"></i></td></tr>');
                    });
                }
                
            }

            document.getElementById("spinner_cont").style.display="none";

        })
        .catch(function(error)
        {

        });


        //Show More Button Click
        $(document).on("click", "#show_more_prod_btn", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            e.stopImmediatePropagation();
            e.stopPropagation();

            lastelement = self.lastel;
            axios.post("/getlowqtyprods", {lastelement})
            .then(function(response)
            {
                self.gottenprodslength = 0;
                self.allshown = "notset";

                if(response.data == "nodata")
                {
                    self.gottenprodslength = 1;
                    $(document).ready(function()
                    {
                        $(".show_low_res").append('<div class="w-100 text-center noresmsg text-center">لا يوجد نتائج</div>');
                    });
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
                            $("#res_bod").append('<tr><td class="text-center">'+response.data[1].data[i].id+'</td><td class="text-center">'+response.data[1].data[i].productname+'</td><td class="text-center res_table_options"><i id="'+response.data[1].data[i].id+'" class="fas fa-edit text-primary edit_prod_pg mr-2" style="cursor:pointer;"></i></td></tr>');
                        });
                    }
                    
                }

                document.getElementById("spinner_cont").style.display="none";

            })
            .catch(function(error)
            {

            });
        });


         //Go To Edit Page
         $(document).on("click", ".edit_prod_pg", function()
         {
            let prodid = $(this).attr("ID");
            self.$router.push({path: "/edit_product/"+prodid});
         });

    },

}
</script>
