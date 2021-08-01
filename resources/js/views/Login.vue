<template>
    <div>
        <Myheader></Myheader>

            <div class="registration-form">
                <form data-aos="fade-right">

                    <div class="log_type" @click="get_reg">إنشاء حساب جديد</div>

                    <hr class="hr-text" data-content="أو">
                    <h5 class="mainh5">تسجيل الدخول</h5>
                    <div class="main_errors">
                        <p class="alert alert-danger" ref="login_error" id="login_error"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control item" v-model="data.email" id="email" placeholder="البريد الإليكتروني">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control item" v-model="data.password" id="password" placeholder="كلمة المرور">
                    </div>
                    <div class="form-group recaptcha_carrier">
                        <vue-recaptcha ref="googleRecaptcha" sitekey="6Ldw28QaAAAAABoh4iYeqh0GMcgxQDUrEjJrjU8L" data-callback="recaptchaCallback"></vue-recaptcha>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-block create-account" @click="login_proc($event)">تسجيل دخول</button>
                    </div>
                    <div class="form-group forget_pass_view">
                        <router-link tag="a" to="/resetpassword">هل نسيت كلمة المرور؟</router-link >
                    </div>
                </form>
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

    name: "Login",
    components:{
        Myheader,
        Myfooter,
        VueRecaptcha,
    },
    data() {
        return {

            data: {

                email:"",
                password:"",
            },
            fromProductlogin: this.$store.state.fromProduct,
        }

    },
    methods:{

        get_reg:function()
        {

            this.$router.push("/register");
        },
        async login_proc(event)
        {
            document.getElementById("spinner_cont").style.display="block";

            let self = this;
            if (event)
            {
                event.preventDefault();
                let el_email = this.data.email;
                let el_password = this.data.password;
                let chk_recaptcha = grecaptcha.getResponse();
                
                axios.post('/login', {el_email, el_password, chk_recaptcha}
                ).then(function(response)
                {
                    document.getElementById("spinner_cont").style.display="block";

                    if(response.data == "captcha")
                    {
                       document.getElementById("login_error").style.display="flex";
                       document.getElementById("login_error").innerHTML="";
                       document.getElementById("login_error").innerHTML="لم تقم بحل الكابتشا!";
                    }
                    else if(response.data == "login faild")
                    {
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="البيانات غير صحيحة";

                        window.grecaptcha.reset();
                    }
                    else if(response.data == "blockeduser")
                    {
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="تم حظر حسابك نظرا لوجود مخالفة، إذا كنت تعتقد أنه خطأ يرجى التواصل معنا.";

                        window.grecaptcha.reset();
                    }
                    else if(response.data == "verification error")
                    {
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="يرجى تفعيل حسابك من الرابط المرسل لبريدك الإليكتروني لتتمكن من تسجيل الدخول";

                        window.grecaptcha.reset();
                    }
                    else if(response.data == "login success")
                    {
                        if(self.fromProductlogin == "")
                        {
                            window.location.href="/";

                        }
                        else
                        {
                            self.$router.push({path: "/product/"+self.fromProductlogin});
                        }
                    }

                    document.getElementById("spinner_cont").style.display="none";
                })
                .catch(function(error)
                {
                    if(error.response.data.errors["el_email"])
                    {
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="البريد الإليكتروني مطلوب";
                    }
                    else if(error.response.data.errors["el_password"])
                    {
                        document.getElementById("login_error").style.display="flex";
                        document.getElementById("login_error").innerHTML="";
                        document.getElementById("login_error").innerHTML="كلمة المرور مطلوبة";
                    }
                })
                .then(function () {
                    
                }); 
            }
        },

    },
    
}
</script>