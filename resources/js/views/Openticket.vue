<template>
    <div>
        <Popmessage></Popmessage>
        <Myheader></Myheader>
            <div class="ticket-view mt-4">
                <div class="container text-right">
                    <div class="row justify-content-center align-items-center">

                        <form class="col-lg-7 ticket_form col-md-10 col-sm-12">
                            
                            <div class="form-group">
                                <label for="ticket_sunbject">عنوان التذكرة</label>
                                <input v-model="messagehead" type="text" class="form-control" id="ticket_sunbject">
                            </div>

                            <div class="form-group">
                                <label for="ticket_body">نص التذكرة</label>
                                <textarea v-model="messagecore" class="form-control " id="ticket_body" rows="7"></textarea>
                            </div>

                            <div class="form-group recaptcha_carrier">
                                <vue-recaptcha ref="googleRecaptcha" sitekey="6Ldw28QaAAAAABoh4iYeqh0GMcgxQDUrEjJrjU8L" data-callback="recaptchaCallback"></vue-recaptcha>
                            </div>

                            <button v-on:click="openticketaction($event)" class="btn btn-primary">فتح التذكرة</button>

                        </form>

                    </div>
                </div>
            </div>

        <Myfooter></Myfooter>
    </div>
</template>
<script>
import axios from 'axios';
import Myheader from '../components/Myheader.vue';
import Myfooter from '../components/Myfooter.vue';
import Popmessage from '../components/Popmessage.vue';

import VueRecaptcha from 'vue-recaptcha';

import AOS from 'aos';

AOS.init();

export default {

    name: "Login",
    components:{
        Myheader,
        Myfooter,
        axios,
        VueRecaptcha,
        Popmessage,
    },
    data() {
        return {
            
            messagehead: "",
            messagecore: "",
        }

    },
    mounted()
    {
        let self = this;
        $(document).on("click", ".close_pop_msg", function()
        {
            self.$router.push({path:"/support"});
        });

    },
    methods:
    {
        openticketaction:function(event)
        {
            if(event)
            {
                event.preventDefault();
            }

            let messagehead = this.messagehead;
            let messagecore = this.messagecore;
            let chk_recaptcha = grecaptcha.getResponse();

            //Start Send & Open Ticket
            axios.post("/openticket", {messagehead, messagecore, chk_recaptcha})
            .then(function(response)
            {
                if(response.data == "subjectlength")
                {
                    Notiflix.Notify.Failure('يجب أن لا يقل عنوان التذكرة عن 10 أحرف ولا يزيد عن 50');
                    window.grecaptcha.reset();
                }
                else if(response.data == "bodylength")
                {
                    Notiflix.Notify.Failure('يجب أن لا يقل نص التذكر عن 20 حرف ولا يزيد عن 500');
                    window.grecaptcha.reset();
                }
                else if(response.data == "captcha")
                {
                    Notiflix.Notify.Failure('يجب حل الكابتشا');
                    window.grecaptcha.reset();
                }
                else
                {
                    $(".pop_overlay_success").fadeIn();
                    $(".pop_overlay_success .message .alert").text("رقم التذكرة: "+response.data[1]+"");
                }


            })
            .catch(function(error)
            {

            });
        }
    }
    
}
</script>