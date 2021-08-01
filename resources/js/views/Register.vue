<template>
    <div>
        <Myheader></Myheader>
            <template v-if="registerstatus == 'opened'">
                <div class="registration-form">
                    <form data-aos="fade-right" id="registration_form">

                        <div class="reg_type" @click="get_log">هل لديك حساب بالفعل؟ تسجيل دخول</div>

                        <hr class="hr-text" data-content="أو">
                        <h5 class="mainh5">إنشاء حساب</h5>


                        <div class="form-group">
                            <input type="text" v-model="data.fullname" class="form-control item" id="fullname" placeholder="الإسم بالكامل">
                            <p class="alert alert-danger reg_error" id="reg_error_fullname"></p>
                        </div>
                        <div class="form-group">
                            <input type="password" v-model="data.password" class="form-control item" id="password" placeholder="كلمة المرور">
                            <p class="alert alert-danger reg_error" id="reg_error_password"></p>
                        </div>
                        <div class="form-group">
                            <input type="password" v-model="data.password_rep" class="form-control item" id="password_rep" placeholder="كلمة المرور">
                            <p class="alert alert-danger reg_error" id="reg_error_password_rep"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" v-model="data.email" class="form-control item" id="email" placeholder="البريد الإليكتروني">
                            <p class="alert alert-danger reg_error" id="reg_error_email"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" v-model="data.email_rep" class="form-control item" id="email_rep" placeholder="البريد الإليكتروني">
                            <p class="alert alert-danger reg_error" id="reg_error_email_rep"></p>
                        </div>
                        <div class="form-group">
                            <input type="number" v-model="data.phone" class="form-control item" id="phone-number" placeholder="رقم الهاتف">
                            <p class="alert alert-danger reg_error" id="reg_error_phone"></p>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" v-model="data.address" id="address" placeholder="العنوان" rows="3"></textarea>
                            <p class="alert alert-danger mt-2 reg_error" id="reg_error_address"></p>
                        </div>
                        <div class="form-group recaptcha_carrier">
                            <vue-recaptcha ref="googleRecaptcha" sitekey="6Ld2FZEaAAAAAOsqJqgfnzU4KoXVKnzoYwGFhx-u" data-callback="recaptchaCallback"></vue-recaptcha>
                        </div>
                        <p class="alert alert-danger mt-2 reg_error" id="reg_error_captcha"></p>
                        
                        <div class="form-group">
                            <button type="button" @click="register_proc($event)" class="btn btn-block create-account">إنشاء حساب</button>
                        </div>
                    </form>
                    <div data-aos="fade-right"  id="sent_success_register" class="sent_success_register">
                        <div class="inner_cont img-thumbnail">
                            <i class="fas fa-check-square" data-aos="flip-left" data-aos-delay="500"></i>
                            <p class="mt-3" data-aos="zoom-out-down" data-aos-delay="1000">باق خطوة واحدة لإكمال تسجيل</p>
                            <p class="mt-3" data-aos="zoom-out-down" data-aos-delay="1500">تم إرسال لينك تفعيل حسابك إلى بريدك الإليكتروني</p>
                            <p class="mt-3" data-aos="zoom-out-down" data-aos-delay="2000">يرجى تتبع ذلك الرابط لإكمال تسجيلك</p>
                            <a @click="go_logon($event)" type="button" class="btn btn-primary mt-3 reset_done_btn text-light" data-aos="fade-up" data-aos-delay="2500">تسجيل الدخول</a>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="registerstatus == 'closed'">
                <div style="min-height: 200px" class="reg_closed w-100 d-flex flex-column justify-content-center align-items-center">
                    <p class="font-weight-bold">عفوا! التسجيل مغلق حاليا</p>
                    <div class="form-group">
                        <a @click="go_logon($event)" type="button" class="btn btn-primary mt-3 text-light">تسجيل الدخول</a>
                    </div>
                </div>
            </template>

        <Myfooter></Myfooter>
    </div>
</template>
<script>
import axios from 'axios';
import VueRecaptcha from 'vue-recaptcha';

import Myheader from '../components/Myheader.vue';
import Myfooter from '../components/Myfooter.vue';

import AOS from 'aos';
import 'aos/dist/aos.css';
AOS.init();

export default {

    name: "Register",
    components:{
        Myheader,
        Myfooter,
        VueRecaptcha,
    },
    data() {

        return {

            data: {

                fullname: "",
                password: "",
                password_rep: "",
                email: "",
                email_rep: "",
                phone: "",
                address: "",
            },
            registerstatus: "",
        }
    },
    mounted()
    {
        let self = this;
        document.getElementById("spinner_cont").style.display="block";

        axios.get("/chkregisterstatus")
        .then(function(response)
        {
            if(response.data == 1)
            {
                self.registerstatus = "opened";
                document.getElementById("spinner_cont").style.display="none";
            }
            else
            {
                self.registerstatus = "closed";
                document.getElementById("spinner_cont").style.display="none";
            }
        })
        .catch(function()
        {

        });
    },
    methods:
    {

        get_log:function()
        {
            this.$router.push("/login");
        },
        go_logon:function(event)
        {
            if(event)
            {
                event.preventDefault();
                this.$router.push("/login");
            }
        },
        register_proc:function(event)
        {
            document.getElementById("spinner_cont").style.display="block";
            $(".reg_error").fadeOut(1);

            if(event)
            {
                event.preventDefault();
                let fullname = this.data.fullname;
                let password = this.data.password;
                let password_rep = this.data.password_rep;
                let email = this.data.email;
                let email_rep = this.data.email_rep;
                let phone = this.data.phone;
                let address = this.data.address;

                let recaptcha_ready = grecaptcha.getResponse();

                axios.post("/register", {fullname, password, password_rep, email, email_rep, phone, address, recaptcha_ready}
                ).then(function(response)
                {
                    if(response.data == "Proxydetected")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_password").html("غير مسموح بالتسجيل باستخدام بروكسي!");
                        $("#reg_error_password").fadeIn(1);
                    }
                    if(response.data == "blockeduser")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_password").html("عفوا!، حدث خطأ ما يرجى المحاولة لاحقاً.");
                        $("#reg_error_password").fadeIn(1);
                    }
                    if(response.data == "password not match")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_password").html("كلمة المرور وتأكيدها غير متطابقين");
                        $("#reg_error_password").fadeIn(1);
                    }
                    else if(response.data == "email not match")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_email").html("البريد الإليكتروني وتأكيده غير متطابقين");
                        $("#reg_error_email").fadeIn(1);
                    }
                    else if(response.data == "email exists")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_email").html("هذا البريد الإليكتروني مسجل بالفعل");
                        $("#reg_error_email").fadeIn(1);
                    }
                    else if(response.data == "wrong captcha")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_captcha").html("لم تقم بحل الكابتشا");
                        $("#reg_error_captcha").fadeIn(1);
                    }
                    else if(response.data == "email invalid")
                    {
                        window.grecaptcha.reset();
                        $("#reg_error_captcha").html("البريد الاليكتروني غير صالح");
                        $("#reg_error_captcha").fadeIn(1);
                    }
                    else if(response.data == "success")
                    {
                        $("#registration_form").fadeOut(1);
                        document.getElementById("sent_success_register").style.display="flex";
                    }
                    document.getElementById("spinner_cont").style.display="none";

                }).catch(function(error)
                {
                    if(error.response.data.errors)
                    {
                        let keys = Object.keys(error.response.data.errors);
                        let values = Object.values(error.response.data.errors);

                        window.grecaptcha.reset();

                        for(let i =0; i < keys.length; i++)
                        {
                            document.getElementById("reg_error_"+keys[i]).innerHTML=values[i];
                            document.getElementById("reg_error_"+keys[i]).style.display="block";
                        }
                    }

                });
            }


        },

    },
    
}
</script>