<template>
    <div class="container-fluid">
        <div class="dash_bod dash_pending_orders">
            <h5 class="main_heading">طلبات التجهيز</h5>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">رقم العملية</th>
                        <th class="text-center" scope="col">عرض التفاصيل</th>
                        <th class="text-center" scope="col">خيارات</th>
                    </tr>
                </thead>
                <tbody class="ps_table">

                </tbody>
            </table>
            <hr>
            <template v-if="allshown == 'false'">
                <div class="mt-3 text-center w-100 mb-2">
                    <button id="show_more_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
                </div>
            </template>
            <template v-if="allshown == 'true'">
                <div class="mt-3 text-center w-100 mb-2">
                    <button class="btn btn-primary show_more text-light" style="cursor:pointer;" disabled>نهاية النتائج</button>
                </div>
            </template>
            <div class="opened_ps_details">
                <div class="ps_details_inner mr-0 ml-0">
                    <div class="closer_btn">
                        <i class="fas fa-times-circle closer_icon text-danger"></i>
                    </div>
                    <div class="ps_details_inner-content">
                        <div class="ps_details_inner_body text-right">

                            <div class="ps_items img-thumbnail">
                                <h6 class="ps_details_h6">الاصناف</h6>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">الصنف</th>
                                            <th class="text-center" scope="col">الكود</th>
                                            <th class="text-center" scope="col">الكمية</th>
                                        </tr>
                                    </thead>
                                    <tbody id="spx_details_items">
                                        
                                    </tbody>
                                </table>
                            </div>

                            <div class="ps_cost img-thumbnail">
                                <h6 class="ps_details_h6">التكلفة</h6>
                                <ul>
                                    <li class="pt-1 pb-1">
                                        <p class="mb-0">إجمالي التكلفة</p>
                                        <p class="item_cost_p img-thumbnail mb-0" id="ps_sum"></p>
                                    </li>
                                </ul>
                            </div>

                            <div class="ps_address img-thumbnail">
                                <h6 class="ps_details_h6">بيانات العميل</h6>
                                <ul>
                                    <li class="pt-1 pb-1">
                                        <p class="mb-0 font-weight-bold mb-1">المعرف:</p>
                                        <p class="mb-0" id="ps_member_id"></p>
                                    </li>
                                    <li class="pt-1 pb-1">
                                        <p class="mb-0 font-weight-bold mb-1">العنوان الأول:</p>
                                        <textarea class="form-control mb-0" id="ps_address1" readonly></textarea>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold mb-1">العنوان الثاني:</p>
                                        <textarea class="form-control mb-0" readonly id="ps_address2"></textarea>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">المحافظة:</p>
                                        <p class="mb-0" id="ps_governorate"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">المركز:</p>
                                        <p class="mb-0" id="ps_city"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">الرمز البريدي:</p>
                                        <p class="mb-0" id="ps_zip"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">رقم الهاتف:</p>
                                        <p class="mb-0" id="ps_phone"></p>
                                    </li>
                                </ul>
                            </div>

                            <div class="ps_payment img-thumbnail">
                                <h6 class="ps_details_h6">معلومات الدفع</h6>

                                <div class="form-group">
                                    <h6 style="display: inline-block" class="font-weight-bold">حالة الدفع</h6>
                                    <p class="mb-0 p-2" id="ps_paymenttype" style="display: inline-block; background: #f39c12;">هنا حالة الدفع</p>
                                </div>
                                
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</template>
<script>
import Popmessage from "./Popmessage.vue";
import axios from "axios";
export default {

    components: {
        
        Popmessage,
        axios,
    },
    data()
    {
        return {

            lastshipid: 0, 
            allshown: "notset",
        }
    },
    mounted:function()
    {

        document.getElementById("spinner_cont").style.display="block";

        let self = this;
        let lastshipid_a = self.lastshipid;
        let gottendata = "";

        axios.get("/getprepareships/"+lastshipid_a)
        .then(function(response)
        {   
            document.getElementById("spinner_cont").style.display="none";

            if(response.data == "nodata")
            {
                $(".ps_table").append('<tr><td colspan="3" class="text-center">لا يوجد أي شحنات</td></tr>');
                self.allshown = "true";
                $("#show_more_btn").text("لا يوجد مزيد");
                $("#show_more_btn").attr("disabled", true);
            }
            else if(typeof(response.data) == "object" && response.data.length == 3)
            {
                self.lastshipid = response.data[0];
                self.allshown = "true";
                gottendata = response.data[1].data;

                for(let i=0; i<gottendata.length; i++)
                {
                    $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center"><i class="fas fa-shipping-fast text-danger trans_ship" style="cursor:pointer;" id="transship_'+gottendata[i].id+'"></i></td></tr>');
                }

                $("#show_more_btn").text("لا يوجد مزيد");
                $("#show_more_btn").attr("disabled", true);
                
            }
            else if(typeof(response.data) == "object" && response.data.length == 2)
            {
                self.lastshipid = response.data[0];
                self.allshown = "false";
                gottendata = response.data[1].data;
                
                for(let i=0; i<gottendata.length; i++)
                {
                    $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center"><i class="fas fa-shipping-fast text-danger trans_ship" style="cursor:pointer;" id="transship_'+gottendata[i].id+'"></i></td></tr>');
                }

            }
        })
        .catch(function(error)
        {

        })

        //Show More Ships
        $(document).on("click", "#show_more_btn", function(e)
        {
            e.preventDefault();
            document.getElementById("spinner_cont").style.display="block";

            let lastshipid_a2 = self.lastshipid;

            axios.get("/getprepareships/"+lastshipid_a2)
            .then(function(response)
            {
                document.getElementById("spinner_cont").style.display="none";

                if(response.data == "nodata")
                {
                    $(".ps_table").append('<tr><td colspan="3" class="text-center">لا يوجد أي شحنات</td></tr>');
                    self.allshown = "true";
                    $("#show_more_btn").text("لا يوجد مزيد");
                    $("#show_more_btn").attr("disabled", true);
                }
                else if(typeof(response.data) == "object" && response.data.length == 3)
                {
                    self.lastshipid = response.data[0];
                    self.allshown = "true";
                    gottendata = response.data[1].data;

                    for(let i=0; i<gottendata.length; i++)
                    {
                        $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center"><i class="fas fa-shipping-fast text-danger trans_ship" style="cursor:pointer;" id="transship_'+gottendata[i].id+'"></i></td></tr>');
                    }

                    $("#show_more_btn").text("لا يوجد مزيد");
                    $("#show_more_btn").attr("disabled", true);
                    
                }
                else if(typeof(response.data) == "object" && response.data.length == 2)
                {
                    self.lastshipid = response.data[0];
                    self.allshown = "false";
                    gottendata = response.data[1].data;
                    
                    for(let i=0; i<gottendata.length; i++)
                    {
                        $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center"><i class="fas fa-shipping-fast text-danger trans_ship" style="cursor:pointer;" id="transship_'+gottendata[i].id+'"></i></td></tr>');
                    }

                }
            })
            .catch(function(error)
            {

            })

        });

        $(document).on("click", ".show_ps_details", function()
        {
            document.getElementById("spinner_cont").style.display="block";

            let idofdet = $(this).attr("id");

            axios.get("/getprinfo/"+idofdet)
            .then(function(response)
            {
                $(".opened_ps_details").slideDown();

                document.getElementById("spinner_cont").style.display="none";
                $("#spx_details_items").html("");

                if(response.data[3] == "pod")
                {
                    $(".ps_payment_cond").fadeOut(1);
            
                    for(let i=0; i<response.data[0].length; i++)
                    {
                        document.getElementById("spx_details_items").innerHTML += '<tr><td class="text-center">'+response.data[0][i][0]+'</td><td class="text-center">'+response.data[0][i][1]+'</td><td class="text-center">'+response.data[0][i][2]+'</td></tr>';
                    }

                    $("#ps_sum").text(response.data[1]);
                    $("#ps_address1").text(response.data[2][0]);
                    $("#ps_address2").text(response.data[2][1]);
                    $("#ps_governorate").text(response.data[2][2]);
                    $("#ps_city").text(response.data[2][3]);
                    $("#ps_zip").text(response.data[2][4]);
                    $("#ps_phone").text(response.data[2][5]);
                    $("#ps_member_id").text(response.data[2][6]);
                    $("#ps_paymenttype").text("الدفع عند الاستلام");
                }
                else
                {
                    $(".ps_payment_cond").fadeIn(1);

                    for(let i=0; i<response.data[0].length; i++)
                    {
                        document.getElementById("spx_details_items").innerHTML += '<tr><td class="text-center">'+response.data[0][i][0]+'</td><td class="text-center">'+response.data[0][i][1]+'</td><td class="text-center">'+response.data[0][i][2]+'</td></tr>';
                    }

                    $("#ps_sum").text(response.data[1]);
                    $("#ps_address1").text(response.data[2][0]);
                    $("#ps_address2").text(response.data[2][1]);
                    $("#ps_governorate").text(response.data[2][2]);
                    $("#ps_city").text(response.data[2][3]);
                    $("#ps_zip").text(response.data[2][4]);
                    $("#ps_phone").text(response.data[2][5]);
                    $("#ps_member_id").text(response.data[2][6]);
                    $("#ps_paymenttype").text("تم الدفع");
                }
            })   
            .catch(function(error)
            {

            });

            
        })

        $(document).on("click", ".closer_icon", function()
        {
            $(".opened_ps_details").slideUp();
        });

        //Transport Shippment
        $(document).on("click", ".trans_ship", function()
        {
            let eletransid = $(this).attr("id").split("transship_")[1];

            axios.get("/transship/"+eletransid)
            .then(function(response)
            {
                if(response.data == "success")
                {
                    $("#transship_"+eletransid).closest("tr").fadeOut().remove();
                    //self.$store.state.statererendercomp++;
                }
            })
            .catch(function(error)
            {

            });
            
        })


    },
    methods:
    {

    }

}
</script>
<style scoped>
.add-new-product a i
{
    color: #fff !important;
}
</style>