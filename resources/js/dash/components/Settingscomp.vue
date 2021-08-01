<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">الإعدادات</h5>
                <!---- GENERAL SETTINGS ------->
                <div class="general_settins">
                    <h6 class="inner_heading">العامة</h6>
                    <div class="form-group">
                        <label for="sitename" class="col-form-label font-weight-bold">اسم الموقع</label>
                        <div class="col-md-6 col-10">
                            <input type="text" class="form-control" id="sitename" v-model="gensettings.sitename">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sitelink" class="col-form-label font-weight-bold">رابط الموقع</label>
                        <div class="col-md-6 col-10">
                            <input type="text" class="form-control" id="sitelink" v-model="gensettings.sitelink">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="footerdescrip" class="col-form-label font-weight-bold">وصف الفوتر</label>
                        <div class="col-md-6 col-10">
                            <textarea type="text" class="form-control" id="footerdescrip" v-model="gensettings.footerdescrip">
                            </textarea>
                        </div>
                    </div>
                    <h6 class="font-weight-bold mt-3">أضف روابط صفحات التواصل الاجتماعي</h6>
                    <div class="form-group">
                        <label for="facebooklink" class="col-form-label font-weight-bold">رابط فيسبوك</label>
                        <div class="col-md-6 col-10">
                            <input type="text" class="form-control" id="facebooklink" v-model="gensettings.facebooklink">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="twitterlink" class="col-form-label font-weight-bold">رابط تويتر</label>
                        <div class="col-md-6 col-10">
                            <input type="text" class="form-control" id="twitterlink" v-model="gensettings.twitterlink">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="instagramlink" class="col-form-label font-weight-bold">رابط انستاجرام</label>
                        <div class="col-md-6 col-10">
                            <input type="text" class="form-control" id="instagramlink" v-model="gensettings.instagramlink">
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="sitestatus" class="col-form-label font-weight-bold">حالة الموقع</label>
                        <div class="col-md-6 col-10">
                            <div class="tg-list-item">
                                <label class="switch">
                                <input v-if="gensettings.sitestatus == 0" type="checkbox" id="sitestatus">
                                <input v-if="gensettings.sitestatus == 1" type="checkbox" id="sitestatus" checked>
                                <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="registerstatus" class="col-form-label font-weight-bold">حالة التسجيل</label>
                        <div class="col-md-6 col-10">
                            <label class="switch">
                                <input v-if="gensettings.registerstatus == 0" type="checkbox" id="registerstatus">
                                <input v-if="gensettings.registerstatus == 1" type="checkbox" id="registerstatus" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="revisecomments" class="col-form-label font-weight-bold">مراجعة التعليقات قبل النشر</label>
                        <div class="col-md-6 col-10">
                            <label class="switch">
                                <input v-if="gensettings.revisecomments == 0" type="checkbox" id="revisecomments">
                                <input v-if="gensettings.revisecomments == 1" type="checkbox" id="revisecomments" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sitecurrency" class="col-form-label font-weight-bold">العملة</label>
                        <div class="col-md-6 col-10">
                            <input type="text" class="form-control" id="sitecurrency" v-model="gensettings.currency">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="currentpassword_gen" class="col-form-label font-weight-bold">كلمة المرور الحالية</label>
                        <div class="col-md-6 col-9">
                            <input type="password" class="form-control" id="currentpassword_gen" v-model="currentpassword_gen">
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="savegeneralsettings">حفظ</button>
                    </div>
                </div>

                <!---- Support SETTINGS ------->

                <div class="support_settins">
                    <h6 class="inner_heading">الدعم</h6>

                    <div class="phone_support">
                        <div class="form-group">
                            <label for="sitestatus" class="col-form-label font-weight-bold">تفعيل الدعم عن طريق الهاتف</label>
                            <div class="col-md-6 col-10">
                                <div class="tg-list-item">
                                    <label class="switch">
                                    <input v-if="supportsettings.phonestatus == 0" type="checkbox" id="phonestatus">
                                    <input v-if="supportsettings.phonestatus == 1" type="checkbox" id="phonestatus" checked>
                                    <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="phones_list" id="phones_list">

                        </div>

                        <a @click="add_new_phone" style="cursor:pointer;" class="btn btn-primary col-md-1 col-sm-2 col-4">
                            <i class="fas fa-plus text-light"></i>
                        </a>
                    </div>

                    <div class="email_support mt-4">
                        <div class="form-group">
                            <label for="sitestatus" class="col-form-label font-weight-bold">تفعيل الدعم عن طريق البريد الاليكتروني</label>
                            <div class="col-md-6 col-10">
                                <div class="tg-list-item">
                                    <label class="switch">
                                    <input v-if="supportsettings.emailstatus == 0" type="checkbox" id="emailstatus">
                                    <input v-if="supportsettings.emailstatus == 1" type="checkbox" id="emailstatus" checked>
                                    <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="emails_list" id="emails_list">

                    </div>

                    <a @click="add_new_email" style="cursor:pointer;" class="btn btn-primary col-md-1 col-sm-2 col-4">
                        <i class="fas fa-plus text-light"></i>
                    </a>

                    <div class="form-group mt-3">
                        <label for="currentpassword_supp" class="col-form-label font-weight-bold">كلمة المرور الحالية</label>
                        <div class="col-md-6 col-9">
                            <input type="password" class="form-control" id="currentpassword_supp" v-model="currentpassword_supp">
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary" id="savesupportsettings">حفظ</button>
                    </div>
                </div>

                <!---- REGIONS SETTINGS ------->
                <div class="regions_settins mt-4">
                    <h6 class="inner_heading">الأقاليم</h6>
                    <p class="text-right font-weight-bold">يمكنك إضافة الأقاليم المراد توصيل الطلبات إليها مع تحديد سعر الشحن إليها.</p>
                    
                    <div class="form-group col-md-12 mt-4 pr-0">
                            <label for="regions_list font-weight-bold">قائمة الأقاليم</label>

                            <div class="regions_list" id="regions_list">

                            </div>
                            
                        <a @click="add_new_region" style="cursor:pointer;" class="btn btn-primary col-md-1 col-sm-2 col-4">
                            <i class="fas fa-plus text-light"></i>
                        </a>

                        <div class="form-group mt-4">
                            <label for="currentpassword_region" class="col-form-label font-weight-bold">كلمة المرور الحالية</label>
                            <div class="col-md-6 col-9 pr-0">
                                <input type="password" class="form-control" id="currentpassword_region" v-model="currentpassword_region">
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button class="btn btn-primary" id="saveregionssettings">حفظ</button>
                        </div>
                    </div>
                </div>
                <!---- SECURITY SETTINGS ------->
                <div class="security_settins">
                    <h6 class="inner_heading">الأمان</h6>
                    <p class="text-right font-weight-bold">تغيير كلمة المرور من خلال ملئ الحقول التالية</p>

                    <div class="form-group">
                        <label for="currentpassword_sec" class="col-form-label font-weight-bold">كلمة المرور الحالية</label>
                        <div class="col-md-6 col-9">
                            <input type="password" class="form-control" id="currentpassword_sec" v-model="currentpassword_sec">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newpassword" class="col-form-label font-weight-bold">كلمة المرور الجديدة</label>
                        <div class="col-md-6 col-9">
                            <input type="password" class="form-control" id="newpassword" v-model="newpassword">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newpasswordrep" class="col-form-label font-weight-bold">تأكيد كلمة المرور الجديدة</label>
                        <div class="col-md-6 col-9">
                            <input type="password" class="form-control" id="newpasswordrep" v-model="newpasswordrep">
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button class="btn btn-primary" id="savesecuritysettings">حفظ</button>
                    </div>
                </div>

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

            gensettings: "",
            supportsettings: "",
            currentpassword_gen: "",
            currentpassword_region: "",
            currentpassword_sec: "",
            currentpassword_supp: "",
            newpassword: "",
            newpasswordrep: "",
        }
    },
    mounted:function()
    {
        let self = this;
        $(".regions_list").html("");
        //Get Curreent Data
        axios.get("/getsettings")
        .then(function(response)
        {
            self.gensettings = response.data[0];
            self.supportsettings = response.data[2];
            let regions = response.data[1];
            let supportsettingse_email = response.data[2].emails.split("_xDxCv_");
            let supportsettingse_phone = response.data[2].phones.split("_xDxCv_");

            for(let i=0; i<regions.length; i=i+2)
            {
                $(".regions_list").append('<div class="form-group pr-0 d-flex flex-row justify-content-center align-content-center region_unit col-lg-7 col-md-9 col-12"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-8 col-7 regiontitle" value="'+regions[i]+'" placeholder="اسم الاقليم"><input type="text" style="border-radius: 0rem 0rem 0rem 0rem;" class="form-control col-3 regionhipfees" value="'+regions[i+1]+'" placeholder="تكلفة الشحن"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-sm-1 col-2 remove_region"><i class="fas fa-minus text-light"></i></a></div>');
            }

            for(let i=0; i<supportsettingse_email.length; i++)
            {
                $(".emails_list").append('<div class="form-group pr-0 d-flex flex-row align-content-center email_unit col-lg-7 col-md-9 col-12"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-8 col-7 regiontitle" placeholder="الايميل" value="'+supportsettingse_email[i]+'"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-sm-1 col-2 remove_email"><i class="fas fa-minus text-light"></i></a></div>');
            }

            for(let i=0; i<supportsettingse_phone.length; i++)
            {
                $(".phones_list").append('<div class="form-group pr-0 d-flex flex-row align-content-center phone_unit col-lg-7 col-md-9 col-12"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-8 col-7 regiontitle" placeholder="رقم الهاتف" value="'+supportsettingse_phone[i]+'"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-sm-1 col-2 remove_phone"><i class="fas fa-minus text-light"></i></a></div>');
            }
    
        })
        .catch(function(error)
        {

        });

        //////////////// GENERAL SEETINGS //////////////////
        //Save General Settings
        $(document).on("click", "#savegeneralsettings", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            e.stopImmediatePropagation();
            e.stopPropagation();
            let regions="";

            $('.regions_list .regiontitle').map(function()
            {
                regions += $(this).val() + "_xXnextarrayelxX_" + $(this).siblings().eq(0).val()+ "_xXnextarrayelxX_";

            }).get();


            let currentpassword_region = self.currentpassword_region;

            let sitename = $("#sitename").val();
            let currency = $("#sitecurrency").val();
            let sitelink = $("#sitelink").val();
            let footerdescrip = $("#footerdescrip").val();
            let facebooklink = $("#facebooklink").val();
            let twitterlink = $("#twitterlink").val();
            let instagramlink = $("#instagramlink").val();
            let currentpassword_gen = self.currentpassword_gen;
            let sitestatus = "";
            let registerstatus = "";
            let revisecomments = "";

            if($("#sitestatus:checked").length > 0)
            {
                sitestatus = 1;
            }
            else
            {
                sitestatus = 0;
            }

            if($("#registerstatus:checked").length > 0)
            {
                registerstatus = 1;
            }
            else
            {
                registerstatus = 0;
            }

            if($("#revisecomments:checked").length > 0)
            {
                revisecomments = 1;
            }
            else
            {
                revisecomments = 0;
            }

            axios.post("/savegeneralsettings", {facebooklink, twitterlink, instagramlink, sitename, sitelink, footerdescrip, currency, sitestatus, registerstatus, revisecomments, currentpassword_gen})
            .then(function(response)
            {

                if(response.data == "empty")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('هناك بيانات ناقصة!');
                }
                else if(response.data == "wrongpass")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('كلمة المرور غير صحيحة!');
                }
                else
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Success('تم حفظ البيانات بنجاح');
                    self.currentpassword_gen = "";
                    $("#currentpassword_gen").val("");
                }

            })
            .catch(function(error)
            {

            })

        });

        //////////////// SUPPORT SEETINGS //////////////////
        $(document).on("click", "#savesupportsettings", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            e.stopImmediatePropagation();
            e.stopPropagation();

            let emails = "";
            let phones = "";
            let phonestatus = "";
            let emailstatus = "";
            let currentpassword_supp = self.currentpassword_supp;
            let el = "";

            for(let i=0; i<$('.emails_list').children().length; i++)
            {
                el = $('.emails_list').children().eq(i).children().eq(0).val();
                emails += el + "_xXnextarrayelxX_";
            }

            for(let i=0; i<$('.phones_list').children().length; i++)
            {
                el = $('.phones_list').children().eq(i).children().eq(0).val();
                phones += el + "_xXnextarrayelxX_";
            }

            //SET STATUS
            if($("#phonestatus:checked").length > 0)
            {
                phonestatus = 1;
            }
            else
            {
                phonestatus = 0;
            }

            if($("#emailstatus:checked").length > 0)
            {
                emailstatus = 1;
            }
            else
            {
                emailstatus = 0;
            }

         
            axios.post("/savesupportsettings", {emails, phones, phonestatus, emailstatus, currentpassword_supp})
            .then(function(response)
            {

                if(response.data == "wrongpass")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('كلمة المرور غير صحيحة!');
                }
                else
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Success('تم حفظ البيانات بنجاح');
                    self.currentpassword_supp = "";
                    $("#currentpassword_supp").val("");
                }

            })
            .catch(function(error)
            {

            });
        
        });

        //Remove Phone
        $(document).on("click", ".remove_phone", function()
        {
            $(this).closest(".phone_unit").remove();

        });
        //Remove Email
        $(document).on("click", ".remove_email", function()
        {
            $(this).closest(".email_unit").remove();

        });

        //////////////// REGIONS SEETINGS //////////////////
        $(document).on("click", "#saveregionssettings", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";

            e.stopImmediatePropagation();
            e.stopPropagation();

            let regions = "";
            $('.regions_list .regiontitle').map(function()
            {
                regions += $(this).val() + "_xXnextarrayelxX_" + $(this).siblings().eq(0).val()+ "_xXnextarrayelxX_";

            }).get();

            let currentpassword_region = self.currentpassword_region;
            
         
            axios.post("/saveregions", {regions, currentpassword_region})
            .then(function(response)
            {
                if(response.data == "lack")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('هناك بيانات ناقصة');
                }
                else if(response.data == "empty")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('لم يتم تحديد أي مناطق');
                }
                else if(response.data == "wrongpass")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('كلمة المرور غير صحيحة!');
                }
                else
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Success('تم حفظ البيانات بنجاح');
                    self.currentpassword_region = "";
                    $("#currentpassword_region").val("");
                }

            })
            .catch(function(error)
            {

            });
        
        });

        //Remove Region
        $(document).on("click", ".remove_region", function()
        {
            $(this).closest(".region_unit").remove();
        });

        //////////////// SECURITY SEETINGS //////////////////
        $(document).on("click", "#savesecuritysettings", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            
            e.stopImmediatePropagation();
            e.stopPropagation();

            let currentpassword_sec = self.currentpassword_sec;
            let newpassword = $("#newpassword").val();
            let newpasswordrep = $("#newpasswordrep").val();
         
            axios.post("/chgadminpassword", {currentpassword_sec, newpassword, newpasswordrep})
            .then(function(response)
            {

                if(response.data == "wrongpass")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('كلمة المرور الحالية غير صحيحة');
                }
                else if(response.data == "empty")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('كلمة المرور الجديدة وتأكيدها مطلوبتين');
                }
                else if(response.data == "notmatch")
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Failure('كلمة المرور الجديدة وتأكيدها غير متطابقتين');
                }
                else
                {
                    document.getElementById("spinner_cont").style.display="none";
                    Notiflix.Notify.Success('تم حفظ البيانات بنجاح');
                    self.currentpassword_sec = "";
                    $("#currentpassword_sec").val("");
                    $("#newpassword").val("");
                    $("#newpasswordrep").val("");
                }
            })
            .catch(function(error)
            {

            });
        
        });
    },
    methods:
    {
        add_new_region:function()
        {
            $(".regions_list").append('<div class="form-group pr-0 d-flex flex-row justify-content-center align-content-center region_unit col-lg-7 col-md-9 col-12"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-8 col-7 regiontitle" placeholder="اسم الاقليم"><input type="text" style="border-radius: 0rem 0rem 0rem 0rem;" class="form-control col-3 regionhipfees" placeholder="تكلفة الشحن"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-sm-1 col-2 remove_region"><i class="fas fa-minus text-light"></i></a></div>');
            
        },
        add_new_phone:function()
        {
            $(".phones_list").append('<div class="form-group pr-0 d-flex flex-row align-content-center phone_unit col-lg-7 col-md-9 col-12"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-8 col-7 phonetitle" placeholder="رقم الهاتف"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-sm-1 col-2 remove_phone"><i class="fas fa-minus text-light"></i></a></div>');
            
        },
        add_new_email:function()
        {
            $(".emails_list").append('<div class="form-group d-flex pr-0 flex-row align-content-center email_unit col-lg-7 col-md-9 col-12"><input type="text" style="border-radius: 0rem 0.25rem 0.25rem 0rem;" class="form-control col-sm-8 col-7 emailtitle" placeholder="الايميل"><a type="submit" style="border-radius: 0.25rem 0rem 0rem 0.25rem; cursor:pointer;" class="btn btn-danger col-sm-1 col-2 remove_email"><i class="fas fa-minus text-light"></i></a></div>');
            
        },
    }

}
</script>
<style scoped>
.general_settins div, .security_settins div, .region_settins div, .support_settins div
{
    padding-right: 0 !important;
}
</style>