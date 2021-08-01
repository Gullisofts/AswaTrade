<template>
    <div>
        <Myheader></Myheader>

            <div class="registration-form">

                <form data-aos="fade-right" id="reset_pass_form">
                    <div class="log_type" @click="get_log">تسجيل الدخول</div>

                    <hr class="hr-text" data-content="أو">
                    <h5 class="mainh5">إستعادة كلمة المرور</h5>
                    <div class="main_errors">
                        <p class="alert alert-danger" ref="login_error" id="login_error"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control item" v-model="data.email" id="email" placeholder="البريد الإليكتروني">
                    </div>
                    <div class="form-group recaptcha_carrier">
                        <vue-recaptcha ref="googleRecaptcha" sitekey="6Ld2FZEaAAAAAOsqJqgfnzU4KoXVKnzoYwGFhx-u" data-callback="recaptchaCallback"></vue-recaptcha>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-block create-account" @click="reset_pass($event)">استعادة كلمة المرور</button>
                    </div>
                </form>

                <div data-aos="fade-right"  id="sent_success_reset" class="sent_success_reset">
                    <div class="inner_cont img-thumbnail">
                        <i class="fas fa-check-square" data-aos="flip-left" data-aos-delay="500"></i>
                        <p class="mt-3" data-aos="zoom-out-down" data-aos-delay="1000">تم ارسال رابط استعادة كلمة المرور إلى بريدك الإليكتروني</p>
                        <a href="/" type="button" class="btn btn-primary mt-3 reset_done_btn" data-aos="fade-up" data-aos-delay="1500">الذهاب للرئيسية</a>
                    </div>
                </div>

            </div>

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

    name: "Resetpassword",
    components: {

        Myheader,
        Myfooter,
        VueRecaptcha,
    },
    data() {
        return {

            data: {

                email:"",
            },

        }

    },
    methods:{

        get_log:function(){

            this.$router.push("/login");
        },
        async reset_pass(event)
        {
            document.getElementById("login_error").style.display="none";
            document.getElementById("login_error").innerHTML="";
            document.getElementById("spinner_cont").style.display="block"

            if (event)
            {
                event.preventDefault();
                let el_email = this.data.email;
                let chk_recaptcha = grecaptcha.getResponse();
                
                axios.post('/reset_pass_proc', {el_email, chk_recaptcha}
                ).then(function(response)
                {
                    if(response.data == "captcha")
                    {
                        document.getElementById("spinner_cont").style.display="none"
                       document.getElementById("login_error").style.display="flex";
                       document.getElementById("login_error").innerHTML="";
                       document.getElementById("login_error").innerHTML="لم تقم بحل الكابتشا!";
                    }
                    else if(response.data == "email invalid")
                    {
                        document.getElementById("spinner_cont").style.display="none"
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="هذا ابريد الإليكتروني غير مسجل";

                        window.grecaptcha.reset();
                    }
                    else if(response.data == "link sent")
                    {
                        document.getElementById("spinner_cont").style.display="none"
                        document.getElementById("reset_pass_form").style.display="none";
                        document.getElementById("sent_success_reset").style.display="flex";
                        //window.location.href="/";
                    }
                })
                .catch(function(error)
                {
                    if(error.response.data.errors["el_email"])
                    {
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="البريد الإليكتروني صالح مطلوب";
                    }
                })
                .then(function () {
                    
                }); 
            }
        },

    },
    
}
</script>