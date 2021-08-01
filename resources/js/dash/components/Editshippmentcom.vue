<template>
    <div class="container-fluid">
        <div class="dash_bod dash_pending_orders">
            <h5 class="main_heading">تعديل حالة طلب</h5>

            <div class="basic_info mb-3 mt-3">
                <div class="row">
                    <p class="mb-0 font-weight-bold">رقم الطلب:</p>
                    <p class="mb-0 mr-1" id="shippment_shipno"></p>
                </div>
                <div class="row mt-3">
                    <p class="mb-0 font-weight-bold">الحالة الحالية:</p>
                    <p class="mb-0 mr-1" id="shippment_status"></p>
                </div>
            </div>

            <div class="change_shippment_status mb-3">
                <div class="form-group row">
                    <label for="changeshipstatus" class="col-sm-2 col-form-label p-0">أختر الحالة</label>
                    <div class="col-sm-10">
                        <select class="form-control col-8" id="changeshipstatus">
                            <option value="pending">تحت المراجعة</option>
                            <option value="prepare">قيد التجهيز</option>
                            <option value="road">قيد التوصيل</option>
                            <option value="delivered">تم التسليم</option>
                            <option value="rejected">مرفوضة</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-3 p-0">
                    <button class="btn btn-primary" id="chngshipstatusst">تعديل</button>
                </div>
            </div>

            <div class="opened_ps_details_viewed">
                <div class="ps_details_inner mr-0 ml-0">
                    <div class="ps_details_inner-content">
                        <div class="ps_details_inner_body text-right">

                            <div class="ps_items img-thumbnail">
                                <h6 class="ps_details_h6x">الاصناف</h6>
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
                                <h6 class="ps_details_h6x">التكلفة</h6>
                                <ul>
                                    <li class="pt-1 pb-1">
                                        <p class="mb-0">إجمالي التكلفة</p>
                                        <p class="item_cost_p img-thumbnail mb-0" id="ps_sum"></p>
                                    </li>
                                </ul>
                            </div>

                            <div class="ps_address img-thumbnail">
                                <h6 class="ps_details_h6x">بيانات العميل</h6>
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
                                <h6 class="ps_details_h6x">معلومات الدفع</h6>

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

            shipid: this.$route.params.shipid, 
        }
    },
    mounted:function()
    {
        let self = this;
        let shipid = self.shipid;

        //Get Shippment Details

        document.getElementById("spinner_cont").style.display="block";

        axios.get("/getpsinfox/"+shipid)
        .then(function(response)
        {
            document.getElementById("spinner_cont").style.display="none";

            if(response.data[4] == "pod")
            {
                $(".ps_payment_cond").fadeOut(1);

                for(let i=0; i<response.data[0].length; i++)
                {
                    document.getElementById("spx_details_items").innerHTML += '<tr><td class="text-center">'+response.data[0][i][0]+'</td><td class="text-center">'+response.data[0][i][1]+'</td><td class="text-center">'+response.data[0][i][2]+'</td></tr>';
                }

                $("#shippment_shipno").text(response.data[1][0]);
                $("#shippment_status").text(response.data[1][1]);
                $("#ps_sum").text(response.data[2]);
                $("#ps_address1").text(response.data[3][0]);
                $("#ps_address2").text(response.data[3][1]);
                $("#ps_governorate").text(response.data[3][2]);
                $("#ps_city").text(response.data[3][3]);
                $("#ps_zip").text(response.data[3][4]);
                $("#ps_phone").text(response.data[2][5]);
                $("#ps_member_id").text(response.data[2][5]);
                $("#ps_paymenttype").text("الدفع عند الاستلام");
            }
            else
            {
                $(".ps_payment_cond").fadeIn(1);

                for(let i=0; i<response.data[0].length; i++)
                {
                    document.getElementById("spx_details_items").innerHTML += '<tr><td class="text-center">'+response.data[0][i][0]+'</td><td class="text-center">'+response.data[0][i][1]+'</td><td class="text-center">'+response.data[0][i][2]+'</td></tr>';
                }

                $("#shippment_shipno").text(response.data[1][0]);
                $("#shippment_status").text(response.data[1][1]);
                $("#ps_sum").text(response.data[2]);
                $("#ps_address1").text(response.data[3][0]);
                $("#ps_address2").text(response.data[3][1]);
                $("#ps_governorate").text(response.data[2][2]);
                $("#ps_city").text(response.data[3][3]);
                $("#ps_zip").text(response.data[3][4]);
                $("#ps_phone").text(response.data[3][5]);
                $("#ps_member_id").text(response.data[2][5]);
                $("#ps_paymenttype").text("تم الدفع");
                $("#ps_transaction_id").text(response.data[4]["transaction_id"]);
                $("#ps_paymentbrand").text(response.data[4]["paymentbrand"]);
                $("#ps_amount").text(response.data[4]["amount"]);
                $("#ps_currency").text(response.data[4]["currency"]);
                $("#ps_bin").text(response.data[4]["bin"]);
                $("#ps_last4digits").text(response.data[4]["last4digits"]);
                $("#ps_holdernme").text(response.data[4]["holdernme"]);
                $("#ps_expire").text(response.data[4]["expire"]);
                $("#ps_ip").text(response.data[4]["ip"]);
                $("#ps_customer_country").text(response.data[4]["customer_country"]);
                $("#ps_timestmp").text(response.data[4]["timestmp"]);
            }
        })   
        .catch(function(error)
        {

        });

        //Change Shippment Status (Action)
        $("#chngshipstatusst").on("click", function()
        {
            let idval = shipid +"__"+ $("#changeshipstatus").val();

            axios.get("/changeshipmentstatus/"+ idval)
            .then(function(response)
            {
                self.$store.state.statererendercomp++;
            })
            .catch(function(error)
            {

            })
        });


    },
    methods:
    {

    }

}
</script>