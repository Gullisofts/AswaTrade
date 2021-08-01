<template>
    <div>
        <Myheader></Myheader>
        <div class="upper_p_header mt-4" id="">
            <div class="container">
                <p id="member_name"></p>
            </div>
        </div>
        <div class="container">
           <div class="p_header mt-3">

               <ul class="ul_prof pr-0">

                    <li>
                        <a class="clickthis" id="home_view">نظرة عامة</a>
                    </li>

                    <li>
                        <a id="orders_view">الشحنات</a>
                    </li>

                    <li>
                        <a id="settings_view">الإعدادات</a>
                    </li>

                </ul>

                <div class="">

                    <div class="prof-sections img-thumbnail mt-3" id="prof-home">
                        <h5>نظرة عامة</h5>
                        <ul class="list-group pr-0">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                عدد الشحنات
                                <span class="badge badge-primary badge-pill">{{shipments_rej.length+shipments_d.length+shipments_r.length+shipments_pre.length+shipments_p.length}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                الشحنات المسلمة
                                <span class="badge badge-primary badge-pill">{{shipments_d.length}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                شحنات قيد التوصيل والتجهيز
                                <span class="badge badge-primary badge-pill">{{shipments_r.length+shipments_pre.length}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                شحنات قيد المراجعة
                                <span class="badge badge-primary badge-pill">{{shipments_p.length}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                شحنات مرفوضة
                                <span class="badge badge-primary badge-pill">{{shipments_rej.length}}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="prof-sections img-thumbnail mt-2" id="prof-orders">
                        
                        <div class="in_road" v-if="shipments_r.length > 0">
                            <h5>شحنات قيد التوصيل</h5>
                            <ul class="list-group pr-0">

                                <li v-for="shipment_r in shipments_r" :key="shipment_r" class="list-group-item d-flex justify-content-between align-items-center">
                                    رقم الشحنة: {{shipment_r}}
                                    <button @click="shipdetails($event)" class="btn btn-primary" v-bind:id="shipment_r">التفاصيل</button>
                                </li>

                            </ul>
                        </div>

                        <div class="in_branch" v-if="shipments_pre.length > 0">
                            <h5>شحنات قيد التجهيز</h5>
                            <ul class="list-group pr-0">

                                <li v-for="shipment_b in shipments_pre" :key="shipment_b" class="list-group-item d-flex justify-content-between align-items-center">
                                    رقم الشحنة: {{shipment_b}}
                                    <button @click="shipdetails($event)" class="btn btn-primary" v-bind:id="shipment_b">التفاصيل</button>
                                </li>

                            </ul>
                        </div>

                        <div class="in_prepare" v-if="shipments_p.length > 0">
                            <h5>شحنات  قيد المراجعة</h5>
                            <ul class="list-group pr-0">

                                <li v-for="shipment_p in shipments_p" :key="shipment_p" class="list-group-item d-flex justify-content-between align-items-center">
                                    رقم الشحنة: {{shipment_p}}
                                    <button @click="shipdetails($event)" class="btn btn-primary" v-bind:id="shipment_p">التفاصيل</button>
                                </li>

                            </ul>
                        </div>

                        <div class="in_house" v-if="shipments_d.length > 0">
                            <h5>شحنات مستلمة</h5>
                            <ul class="list-group pr-0">

                                <li v-for="shipment_d in shipments_d" :key="shipment_d" class="list-group-item d-flex justify-content-between align-items-center">
                                    رقم الشحنة: {{shipment_d}}
                                    <button @click="shipdetails($event)" class="btn btn-primary" v-bind:id="shipment_d">التفاصيل</button>
                                </li>

                            </ul>
                        </div>

                        <div class="in_house" v-if="shipments_rej.length > 0">
                            <h5>شحنات مرفوضة</h5>
                            <ul class="list-group pr-0">

                                <li v-for="shipment_rej in shipments_rej" :key="shipment_rej" class="list-group-item d-flex justify-content-between align-items-center">
                                    رقم الشحنة: {{shipment_rej}}
                                    <button @click="shipdetails($event)" class="btn btn-primary" v-bind:id="shipment_rej">التفاصيل</button>
                                </li>

                            </ul>
                        </div>

                        <div class="in_house" v-if="total < 1">
                            <p class="text-center mb-0">لا يوجد أي شحنات</p>
                        </div>

                    </div>

                    <div class="prof-sections" id="prof-settings">
                        <h5>الإعدادات</h5>
                        <div class="profile_settings">

                            <div class="personal_settings img-thumbnail p-4">
                                <h6>البيانات الرئيسية</h6>
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
                                <h6>العنوان</h6>
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

                                            <option v-for="location in locations" :key="location">{{location}}</option>

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
                                        <input type="number" class="form-control" id="zip" v-bind:value="memberdata.zip">
                                        <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_zip"></p>
                                    </div>
                                </div>

                            </div>

                            <div class="password_settings img-thumbnail mt-4 p-4">
                                <h6> تعديل كلمة المرور </h6>
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

                            <div class="conf_update_settings img-thumbnail mt-4 p-4">
                                <h6>نطبيق التعديلات</h6>
                                <div class="form-group">
                                    <label for="oldpass">كلمة المرور</label>
                                    <input type="password" class="form-control" id="oldpass">
                                    <p class="alert alert-danger updatepinfoerror mt-2" id="err_up_oldpass"></p>
                                </div>
                            </div>

                            <button type="submit" @click="updateinfo" class="btn btn-primary mt-4">تعديل المعلومات</button>
                        </div>
                    </div>

                </div>
           </div>
        </div>
        <Myfooter></Myfooter>
    </div>
</template>
<script>
import Myheader from '../components/Myheader.vue';
import Myfooter from '../components/Myfooter.vue';
import axios from 'axios';

import AOS from 'aos';
import 'aos/dist/aos.css';
AOS.init();

export default {
    name: "Profile",
    components:{
        Myheader,
        Myfooter,
    },
    data() {

        return {
            saledone: this.$route.params.saledone,
            shipments_d: [],
            shipments_r: [],
            shipments_rej: [],
            shipments_pre: [],
            shipments_p: [],
            total: '',
            memberdata: "",
            locations: [],
        }
    },
    created: function()
    {
        let self = this;

        document.getElementById("spinner_cont").style.display="block"; 
        $(document).ready(function()
        {
            $("#prof-home").fadeIn(1);
            $('.clickthis').parent('li').css("background", "#c8d6e5");

            $(".p_header .ul_prof li a").on("click", function()
            {
                $(this).parent("li").css("background", "#c8d6e5");
                $(this).parent("li").siblings().css("background", "none");
            })

            //Show Section On Click Tabs
            $("#home_view").on("click", function()
            {
                $(".prof-sections").fadeOut(1);
                $("#prof-home").fadeIn(1);
            });

            $("#orders_view").on("click", function()
            {
                $(".prof-sections").fadeOut(1);
                $("#prof-orders").fadeIn(1);
            });

            $("#settings_view").on("click", function()
            {
                $(".prof-sections").fadeOut(1);
                $("#prof-settings").fadeIn(1);
            });
        });


        //Get Member FullName
        axios.get('/getloginname'
        ).then(function(response)
        {
            if(response.data != "not loggedin")
            {
                document.getElementById("member_name").innerHTML = "مرحبا "+"<span class='member_name_span'>"+response.data.fullname+"</span>";

                //Get Member Shipments
                let theLink = '/getmembershipments/'+response.data.id;

                axios.get(theLink)
                .then(function(response)
                {
                    self.total = response.data.length;

                    for(let i=0; i<response.data.length; i++)
                    {
                        if(response.data[i].status == "delivered")
                        {
                            self.shipments_d.push(response.data[i].shipmentno);
                        }
                        if(response.data[i].status == "road")
                        {
                            self.shipments_r.push(response.data[i].shipmentno);
                        }
                        if(response.data[i].status == "prepare")
                        {
                            self.shipments_pre.push(response.data[i].shipmentno);
                        }
                        if(response.data[i].status == "pending")
                        {
                            self.shipments_p.push(response.data[i].shipmentno);
                        }
                        if(response.data[i].status == "rejected")
                        {
                            self.shipments_rej.push(response.data[i].shipmentno);
                        }
                    }

                }).catch(function(error)
                {

                });
            }

        }).catch(function(error)
        {

        });

        //Get Member's Full Data
        axios.get("/getloginname")
        .then(function(response)
        {
            if(response.data != "not loggedin")
            {
                self.memberdata = response.data;

                //Add 'Selected' To Member's Governorate
                for(let i=0; i < $("#governorate").children().length; i++)
                {
                    if($("#governorate").children().eq(i).html() == self.memberdata.governorate)
                    {
                        $("#governorate").children().eq(i).attr("selected", true);
                    }
                }

            }   
             document.getElementById("spinner_cont").style.display="none"; 
        })
        .catch(function(error)
        {

        });

        //Get Locations
        axios.get("/getlocations")
        .then(function(response)
        {
            self.locations = response.data;
        })
        .catch(function(error)
        {

        });

    },
    methods:{

        shipdetails:function(event)
        {
            let el = event.target;
            this.$router.push("/shipdetails/"+el.getAttribute("id"));
        },
        updateinfo:function()
        {
            $(".updatepinfoerror").css({display: "none"});

            //Save Edited Value
            let fullname = document.getElementById("p_e_fn").value;
            let email = document.getElementById("p_e_email").value;
            let phone = document.getElementById("phone").value;
            let address1 = document.getElementById("address1").value;
            let address2 = document.getElementById("address2").value;
            let governorate = document.getElementById("governorate").value;
            let city = document.getElementById("city").value;
            let zip = document.getElementById("zip").value;
            let newpass = document.getElementById("newpass").value;
            let newpass_conf = document.getElementById("newpass_conf").value;
            let oldpass = document.getElementById("oldpass").value;
            

            axios.post("/upadteprofiledata", {fullname, email, phone, address1, address2, governorate, city, zip, newpass, newpass_conf, oldpass})
            .then(function(response)
            {
                switch(response.data)
                {

                    case "govincorrect":
                        document.getElementById("err_up_governorate").style.display="block";
                        document.getElementById("err_up_governorate").innerHTML="المحافظة غير صحيحة";
                        document.getElementById("err_up_governorate").scrollIntoView({block: "center", inline: "nearest"});
                        break;

                    case "newpassshort":
                        document.getElementById("err_up_newpass").style.display="block";
                        document.getElementById("err_up_newpass").innerHTML="كلمة المرور لابد أن لا تقل عن 8 حروف";
                        document.getElementById("err_up_newpass").scrollIntoView({block: "center", inline: "nearest"});
                        break;

                    case "newpassnotmatch":
                        document.getElementById("err_up_newpass").style.display="block";
                        document.getElementById("err_up_newpass").innerHTML="كلمة المرور الجديدة وتأكيدها غير متطابقين";
                        document.getElementById("err_up_newpass").scrollIntoView({block: "center", inline: "nearest"});
                        break;

                    case "passandconfrequired":
                        document.getElementById("err_up_newpass").style.display="block";
                        document.getElementById("err_up_newpass").innerHTML="يجب إدخال كلمة المرور وتأكيدها في حالة تعديل كلمة المرور";
                        document.getElementById("err_up_newpass").scrollIntoView({block: "center", inline: "nearest"});
                        break;

                    case "oldpassfalse":
                        document.getElementById("err_up_oldpass").style.display="block";
                        document.getElementById("err_up_oldpass").innerHTML="كلمة المرور الحالية غير صحيحة";
                        document.getElementById("err_up_oldpass").scrollIntoView({block: "center", inline: "nearest"});
                        break;

                    case "success":
                        $("#success_msg_main").fadeIn(2500);
                        $("#success_msg_main").fadeOut(2500);
                }
            })
            .catch(function(error)
            {
                if(error.response.data.errors)
                {
                    let pkeys = Object.keys(error.response.data.errors);
                    let pvalues = Object.values(error.response.data.errors);

                    for(let i=0; i<pkeys.length; i++)
                    {
                        document.getElementById("err_up_"+pkeys[i]).style.display="block";
                        document.getElementById("err_up_"+pkeys[i]).innerHTML=pvalues[i];
                    }

                    document.getElementById("err_up_"+pkeys[0]).scrollIntoView({block: "center", inline: "nearest"});
                }

            });
        
        }


    },
}
</script>