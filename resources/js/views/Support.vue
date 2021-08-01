<template>
    <div>
        <Myheader></Myheader>
        <div class="support container mt-5" style="font-size: 16px;">
            <div class="row">
                <div class="col-md-6 col-12 text-right">
                    <template v-if="emailstatus == 1">
                        <p class="text-right mb-1 font-weight-bold" style="color: #00C995">
                        <i class="fas fa-envelope" style="color: #343A40"></i>
                        <span>عن طريق البريد الاليكتروني</span>
                        </p>

                        <ul class="mr-0 pr-0 list-unstyled">
                            <li v-for="email in emails" :key="email" class="pt-1 pb-1">{{email}}</li>
                        </ul>
                    </template>
                    <template v-if="phonestatus == 1">
                        <p class="text-right mb-1 font-weight-bold" style="color: #00C995">
                        <i class="fas fa-envelope" style="color: #343A40"></i>
                        <span>عن طريق الهاتف</span>
                        </p>

                        <ul class="mr-0 pr-0 list-unstyled">
                            <li v-for="phone in phones" :key="phone" class="pt-1 pb-1">{{phone}}</li>
                        </ul>
                    </template>
                </div>
                <div v-if="phonestatus==0 && emailstatus==0" class="col-12 text-right">
                    <p class="text-right">يمكنك الاتصال وطلب الدعم عن طريق فتح تذكرة ليتلقاها المسؤولون وسوف يتم الرد على رسالتك في أسرع وقت ممكن.</p>
                    <div class="form-group">
                        <button v-on:click="openticket" class="btn btn-primary">افتح تذكرة</button>
                    </div>

                    <p class="text-right mt-5">هل فتحت تذكرة بالفعل؟ أدخل رقم التذكرة</p>
                    <div class="form-group d-flex flex-row">
                        <input type="number" id="ticket_input" v-model="ticketnumber" class="form-control col-5">
                        <button class="btn btn-primary mr-1 gototicket" style="min-width: 50px">أذهب</button>
                    </div>
                    
                </div>
                <div v-if="phonestatus==1 || emailstatus==1" class="col-md-6 col-12 text-right">
                    <p class="text-right">يمكنك الاتصال وطلب الدعم عن طريق فتح تذكرة ليتلقاها المسؤولون وسوف يتم الرد على رسالتك في أسرع وقت ممكن.</p>
                    <div class="form-group">
                        <button v-on:click="openticket" class="btn btn-primary">افتح تذكرة</button>
                    </div>

                    <p class="text-right mt-5">هل فتحت تذكرة بالفعل؟ أدخل رقم التذكرة</p>
                    <div class="form-group d-flex flex-row">
                        <input type="number" id="ticket_input" v-model="ticketnumber" class="form-control col-5">
                        <button class="btn btn-primary mr-1 gototicket" style="min-width: 50px">أذهب</button>
                    </div>
                    
                </div>
            </div>
        </div>
        <Myfooter></Myfooter>
    </div>
</template>

<script>
import Myheader from './../components/Myheader';
import Myfooter from './../components/Myfooter';
import axios from "axios";

export default {

    name: "Support",
    components:{

        Myheader,
        axios,
        Myfooter,  
    },
    data()
    {
        return {

            ticketnumber: "",
            emailstatus: "",
            phonestatus: "",
            emails: "",
            phones: "",
        }
    },
    mounted:function()
    {
        document.getElementById("spinner_cont").style.display="block";

        let self = this;
        $(".gototicket").on("click", function(event)
        {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();

            let ticketnumber = self.ticketnumber;

            axios.get("/checkticket/"+ticketnumber)
            .then(function(response)
            {
                if(response.data == "noticket")
                {
                    Notiflix.Notify.Failure('لا يوجد تذكرة');
                }
                else
                {
                    self.$router.push({path: "/viewticket/"+ticketnumber});
                }

            })
            .catch(function(error)
            {

            });
        });

        //Get Support Information
        axios.get("/getsupportinfo")
        .then(function(response)
        {
            self.emailstatus= response.data[0].emailstatus;
            self.phonestatus= response.data[0].phonestatus;
            self.emails= response.data[0].emails.split("_xDxCv_");
            self.phones= response.data[0].phones.split("_xDxCv_");
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });
    },
    methods:
    {
        openticket:function()
        {
            this.$router.push({path:"/openticket"});
        },
    }
    
}
</script>
