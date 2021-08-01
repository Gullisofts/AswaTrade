<template>
    <div class="container-fluid">
        <div class="dash_bod dash_pending_orders">
            <h5 class="main_heading">الطلبات الجديدة</h5>
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
                                
                                <h6 class="font-weight-bold ps_payment_cond">معلومات الدفع</h6>
                                <ul class="ps_payment_cond">
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">رقم عملية التحويل:</p>
                                        <p class="mb-0" id="ps_transaction_id"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">نوع البطاقة:</p>
                                        <p class="mb-0" id="ps_paymentbrand"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">المبلغ المدفوع:</p>
                                        <p class="mb-0" id="ps_amount"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">العملة:</p>
                                        <p class="mb-0" id="ps_currency"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">البين:</p>
                                        <p class="mb-0" id="ps_bin"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">آخر أربعة أرقام:</p>
                                        <p class="mb-0" id="ps_last4digits"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">الإسم على البطاقة:</p>
                                        <p class="mb-0" id="ps_holdernme"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">تاريخ الانتهاء:</p>
                                        <p class="mb-0" id="ps_expire"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">عنوان الاي بي:</p>
                                        <p class="mb-0" id="ps_ip"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">بلد العميل:</p>
                                        <p class="mb-0" id="ps_customer_country"></p>
                                    </li>
                                    <li class="pt-1 pb-1 mt-2">
                                        <p class="mb-0 font-weight-bold">تاريخ التحويل:</p>
                                        <p class="mb-0" id="ps_timestmp"></p>
                                    </li>
                                </ul>
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

        axios.get("/getpendingships/"+lastshipid_a)
        .then(function(response)
        {
            document.getElementById("spinner_cont").style.display="none";

            if(typeof(response.data) == "object" && response.data.length == 3)
            {
                self.lastshipid = response.data[0];
                self.allshown = "true";
                gottendata = response.data[1].data;

                for(let i=0; i<gottendata.length; i++)
                {
                    $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center d-flex flex-row sp_td"><i class="fas fa-check-circle text-success approve_ship" style="cursor:pointer;" id="appr_'+gottendata[i].id+'"></i><i class="fas fa-times-circle text-danger reject_ship" style="cursor:pointer;" id="dec_'+gottendata[i].id+'"></i></td></tr>');
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
                    $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center d-flex flex-row sp_td"><i class="fas fa-check-circle text-success approve_ship" style="cursor:pointer;" id="appr_'+gottendata[i].id+'"></i><i class="fas fa-times-circle text-danger reject_ship" style="cursor:pointer;" id="dec_'+gottendata[i].id+'"></i></td></tr>');
                }
            }
            else
            {
                $(".ps_table").append('<tr><td colspan="3" class="text-center">لا يوجد أي شحنات</td></tr>');
                self.allshown = "true";
                $("#show_more_btn").text("لا يوجد مزيد");
                $("#show_more_btn").attr("disabled", true);
            }
        })
        .catch(function(error)
        {

        });

        //Show More Ships
        $(document).on("click", "#show_more_btn", function(e)
        {
            e.preventDefault();
            document.getElementById("spinner_cont").style.display="block";

            let lastshipid_a2 = self.lastshipid;

            axios.get("/getpendingships/"+lastshipid_a2)
            .then(function(response)
            {
                document.getElementById("spinner_cont").style.display="none";

                if(typeof(response.data) == "object" && response.data.length == 3)
                {
                    self.lastshipid = response.data[0];
                    self.allshown = "true";
                    gottendata = response.data[1].data;

                    for(let i=0; i<gottendata.length; i++)
                    {
                        $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center d-flex flex-row sp_td"><i class="fas fa-check-circle text-success approve_ship" style="cursor:pointer;" id="appr_'+gottendata[i].id+'"></i><i class="fas fa-times-circle text-danger reject_ship" style="cursor:pointer;" id="dec_'+gottendata[i].id+'"></i></td></tr>');
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
                        $(".ps_table").append('<tr><td class="text-center">'+gottendata[i].shipmentno+'</td><td class="text-center"><button class="btn btn-primary show_ps_details" id="'+gottendata[i].id+'">عرض</button></td><td class="text-center d-flex flex-row sp_td"><i class="fas fa-check-circle text-success approve_ship" style="cursor:pointer;" id="appr_'+gottendata[i].id+'"></i><i class="fas fa-times-circle text-danger reject_ship" style="cursor:pointer;" id="dec_'+gottendata[i].id+'"></i></td></tr>');
                    }
                }
                else
                {
                    $(".ps_table").append('<tr><td colspan="3" class="text-center">لا يوجد أي شحنات</td></tr>');
                    self.allshown = "true";
                    $("#show_more_btn").text("لا يوجد مزيد");
                    $("#show_more_btn").attr("disabled", true);
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

            axios.get("/getpsinfo/"+idofdet)
            .then(function(response)
            {
                $(".opened_ps_details").slideDown();
                $("#spx_details_items").html("");
                
                document.getElementById("spinner_cont").style.display="none";

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
                    $("#ps_transaction_id").text(response.data[3]["transaction_id"]);
                    $("#ps_paymentbrand").text(response.data[3]["paymentbrand"]);
                    $("#ps_amount").text(response.data[3]["amount"]);
                    $("#ps_currency").text(response.data[3]["currency"]);
                    $("#ps_bin").text(response.data[3]["bin"]);
                    $("#ps_last4digits").text(response.data[3]["last4digits"]);
                    $("#ps_holdernme").text(response.data[3]["holdernme"]);
                    $("#ps_expire").text(response.data[3]["expire"]);
                    $("#ps_ip").text(response.data[3]["ip"]);
                    $("#ps_customer_country").text(response.data[3]["customer_country"]);
                    $("#ps_timestmp").text(response.data[3]["timestmp"]);
                }
            })   
            .catch(function(error)
            {

            });

            
        })

        $(".closer_icon").on("click", function()
        {
            $(".opened_ps_details").slideUp();
        });

        //Accepting Shippment
        $(document).on("click", ".approve_ship", function()
        {
            let shipid = $(this).attr("id").split('_')[1];
            axios.get("/approveship/"+shipid)
            .then(function(response)
            {
                if(response.data == "success")
                {
                    $("#appr_"+shipid).closest("tr").fadeOut().remove();
                }

                //self.$store.state.statererendercomp++;
            })
            .catch(function(error)
            {

            });
                
        })

        //Reject Shippment
        $(document).on("click", ".reject_ship", function()
        {
            if($('.rejec_ps_'+$(this).attr('id')).length > 0)
            {
                $('.rejec_ps_'+$(this).attr('id')).remove();
            }
            else
            {
                $(this).closest("tr").after("");
                $(this).closest("tr").after("<tr class='rejec_ps_"+$(this).attr('id')+"'><td colspan='3'><p>يرجى كتابة سبب الرفض:</p><p>ملحوظة: يمكنك تركها فارغة إذا لم ترد إبداء أي أسباب</p><textarea class='form-control'></textarea><button id='ps_rej_action_"+$(this).attr('id')+"' class='ps_rej_action mt-3 btn btn-danger'>رفض</button></td></tr>");
            }

        })
        $(document).on("click", ".ps_rej_action", function()
        {
            let elerejid = $(this).attr("id").split("ps_rej_action_dec_")[1];
            let cause = $(this).siblings().eq(2).val();


            axios.post("/rejecship", {elerejid, cause})
            .then(function(response)
            {
                if(response.data == "success")
                {
                    $('.rejec_ps_dec_'+elerejid).remove();
                    $("#dec_"+elerejid).closest("tr").fadeOut().remove();
                }

               // self.$store.state.statererendercomp++;
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