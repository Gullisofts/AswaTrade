<template>
    <div class="container-fluid">
        <div class="dash_bod">
            <h5 class="main_heading" style="text-indent: 3px">بيانات العميل</h5>

            <div class="customer_shipments mb-4">
                <div class="div">
                    <p class="reg_date mt-3">تاريخ التسجيل: {{regDate}}</p>
                    <h6 class="micro_heading mt-3 font-weight-bold">شحنات العميل</h6>

                    <template v-if="thereisships == true">
                        <ul class="list-group edit_cust_ships_list"></ul>
                    </template>
                    
                    <template v-if="thereisships == false">
                        <p class="alert alert-info">لا يوجد شحنات</p>
                    </template>

                </div>
            </div>
            
            <div class="prof-sections mb-5" id="prof-settings">
                <h5 class="inner_heading img-thumbnail">تعديل بيانات العميل</h5>
                <div class="profile_settings">

                    <div class="personal_settings img-thumbnail p-4">
                        <h6 class="micro_heading font-weight-bold">البيانات الرئيسية</h6>
                        <div class="form-group">
                            <label for="p_e_fn">الاسم بالكامل</label>
                            <input type="text" class="form-control" id="p_e_fn" v-model="memberdata.fullname">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_fullname"></p>
                        </div>

                        <div class="form-group">
                            <label for="p_e_email">البريد الإليكتروني</label>
                            <input type="email" class="form-control" id="p_e_email" v-bind:value="memberdata.email">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_email"></p>
                        </div>

                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" v-bind:value="memberdata.phone">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_phone"></p>
                        </div>

                    </div>

                    <div class="address_settings img-thumbnail mt-4 p-4">
                        <h6 class="micro_heading font-weight-bold">العنوان</h6>
                        <div class="form-group">
                            <label for="address1">العنوان الأول</label>
                            <input type="text" class="form-control" id="address1" v-bind:value="memberdata.address1">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_address1"></p>
                        </div>

                        <div class="form-group">
                            <label for="address2">العنوان الثاني</label>
                            <input type="text" class="form-control" id="address2" v-bind:value="memberdata.address2">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_address2"></p>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="governorate">المحافظة</label>
                                <select id="governorate" class="form-control">
                                    <option selected>أختر...</option>
                                    <option>الإسكندرية</option>
                                    <option>الإسماعيلية</option>
                                    <option>أسوان</option>
                                    <option>أسيوط</option>
                                    <option>الأقصر</option>
                                    <option>البحر الأحمر</option>
                                    <option>البحيرة</option>
                                    <option>بني سويف</option>
                                    <option>بورسعيد</option>
                                    <option>جنوب سيناء</option>
                                    <option>الجيزة</option>
                                    <option>الدقهلية</option>
                                    <option>دمياط</option>
                                    <option>سوهاج</option>
                                    <option>السويس</option>
                                    <option>الشرقية</option>
                                    <option>شمال سيناء</option>
                                    <option>الغربية</option>
                                    <option>الفيوم</option>
                                    <option>القاهرة</option>
                                    <option>القليوبية</option>
                                    <option>قنا</option>
                                    <option>كفر الشيخ</option>
                                    <option>مطروح</option>
                                    <option>المنوفية</option>
                                    <option>المنيا</option>
                                    <option>الوادي الجديد</option>
                                </select>
                                <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_governorate"></p>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="city">المركز</label>
                                <input type="text" class="form-control" id="city" v-bind:value="memberdata.city">
                                <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_city"></p>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="zip">الرمز البريدي</label>
                                <input type="number" class="form-control text-center" id="zip" v-bind:value="memberdata.zip">
                                <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_zip"></p>
                            </div>
                        </div>

                    </div>

                    <div class="password_settings img-thumbnail mt-4 p-4">
                        <h6 class="micro_heading font-weight-bold"> تعديل كلمة المرور </h6>
                            <div class="form-group">
                            <label for="newpass">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="newpass">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_newpass"></p>
                        </div>
                        <div class="form-group">
                            <label for="newpass_conf">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control" id="newpass_conf">
                            <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_newpassconf"></p>
                        </div>
                    </div>

                    <button v-on:click="updatecustomerinfo($event)" type="submit" class="btn btn-primary mt-4">تعديل البيانات</button>
                </div>
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

            custid: this.$route.params.custid,
            memberdata: [],
            regDate: "",
            thereisships: false,
            lastid: 0,
        }
    },
    mounted:function()
    {
        let self = this;
        let customer_id = self.custid;
        let lastid = self.lastid;
        document.getElementById("spinner_cont").style.display="block";
        
        axios.get("/get_customer_data/"+customer_id)
        .then(function(response)
        {
            self.memberdata = response.data[0];
            self.regDate = response.data[1];

            //Add 'Selected' To Member's Governorate
            for(let i=0; i < $("#governorate").children().length; i++)
            {
                if($("#governorate").children().eq(i).html() == self.memberdata.governorate)
                {
                    $("#governorate").children().eq(i).attr("selected", true);
                }
            }
        })
        .catch(function(error)
        {

        });
        

        axios.post("/get_customer_ships", {customer_id, lastid})
        .then(function(response)
        {

            if(response.data != "nodata")
            {
                self.thereisships = true;
                
                let dataline = "";

                for(let i=0; i<response.data[1].data.length; i++)
                {
                    dataline += '<li class="list-group-item"><span class="edit_ship_pg" id="'+response.data[1].data[i].id+'">'+response.data[1].data[i].shipmentno+'</span></li>';
                }

                $(document).ready(function()
                {
                    $(".edit_cust_ships_list").append(dataline);
                });

            }
            else
            {

            }

            document.getElementById("spinner_cont").style.display="none";

        })
        .catch(function(error)
        {

        });

        //Update Customer Info


        //Go To Edit Page
         $(document).on("click", ".edit_ship_pg", function()
         {
             let shipid = $(this).attr("ID");
             self.$router.push({path: "/edit_shippment/"+shipid});
         });
    },
    methods:
    {
        //Update Customer Info
        updatecustomerinfo(event)
        {
            let fullname = $("#p_e_fn").val();
            let email = $("#p_e_email").val();
            let phone = $("#phone").val();
            let address1 = $("#address1").val();
            let address2 = $("#address2").val();
            let governorate = $("#governorate").val();
            let city = $("#city").val();
            let zip = $("#zip").val();
            let newpass = $("#newpass").val();
            let newpass_conf = $("#newpass_conf").val();

            axios.post("/saveeditedcustomerdash", 
            {
                fullname,
                email,
                phone,
                address1,
                address2,
                governorate,
                city,
                zip,
                newpass,
                newpass_conf
            })
            .then(function(response)
            {
                switch(response.data)
                {

                    case "govincorrect":
                        Notiflix.Notify.Failure('المحافظة غير صحيحة');
                        break;

                    case "newpassshort":
                        Notiflix.Notify.Failure('كلمة المرور لابد أن لا تقل عن 8 حروف');
                        break;

                    case "newpassnotmatch":
                        Notiflix.Notify.Failure('كلمة المرور الجديدة وتأكيدها غير متطابقين');
                        break;

                    case "passandconfrequired":
                        Notiflix.Notify.Failure('يجب إدخال كلمة المرور وتأكيدها في حالة تعديل كلمة المرور');
                        break;

                    case "success":
                        Notiflix.Notify.Success('تم تعديل البيانات بنجاح');
                }
            })
            .catch(function(error)
            { 
                if(error.response.data.errors)
                {
                    let pvalues = Object.values(error.response.data.errors);
                    Notiflix.Notify.Failure(''+pvalues[0]+'');
                }
            });
        }
    }

}
</script>