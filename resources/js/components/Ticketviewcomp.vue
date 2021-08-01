<template>
    <div>
        <Myheader></Myheader>
        <div class="ticketview container mt-5" style="font-size: 16px;">
            <div class="text-right">

                <div class="form-group mb-4">
                    <label for="ticket_sunbject" class="font-weight-bold">العنوان:</label>
                    <input type="text" class="form-control" id="ticket_sunbject" v-bind:value="subject" readonly>
                </div>

                <form class="ticket_view img-thumbnail">
                    <template v-if="texts != false">
                        <div v-for="text in texts" class="form-group mt-4">
                            <div v-if="text.member_id != 'support'" class="d-flex flex-row">
                                <p class="font-weight-bold mb-0">{{username}}</p>
                                <small class="d-flex flex-row justify-content-center align-items-center mr-3">{{text.created_at}}</small>
                            </div>

                            <div v-if="text.member_id == 'support'" class="d-flex flex-row mb-2">
                                <p class="font-weight-bold mb-0">الدعم</p>
                                <small class="d-flex flex-row justify-content-center align-items-center mr-3">{{text.created_at}}</small>
                            </div>

                            <textarea class="form-control " id="ticket_body" readonly>{{text.content}}</textarea>
                        </div>
                    </template>
                </form>

                <form v-if="ticketstatus == 'answered'" class="mt-3">
                    <div class="form-group">
                        <label for="ticket_body">اضافة رد</label>
                        <textarea v-model="messagecore" class="form-control " id="ticket_body" rows="7"></textarea>
                    </div>
                    <button v-on:click="addtoticket($event)" class="btn btn-primary" style="min-width: 100px;">رد</button>
                </form>

                <p class="font-weight-bold mt-3" v-if="ticketstatus == 'unanswered'">
                    سيتم الرد في أقرب وقت ممكن
                </p>
                <p class="font-weight-bold mt-3" v-if="ticketstatus == 'closed'">
                    تم اغلاق التذكرة من قبل الادارة
                </p>

            </div>
        </div>
        <Myfooter></Myfooter>
    </div>
</template>
<script>
import Myheader from './Myheader.vue';
import Myfooter from './Myfooter.vue';
import axios from "axios";

export default {

    components: {

        Myheader,
        axios,
        Myfooter,
    },
    data()
    {
        return {

            ticketnumber: this.$route.params.ticketnumber,
            texts: false,
            username: "",
            subject: "",
            ticketstatus: "",
            messagecore: "",
        }
    },
    mounted: function()
    {
        document.getElementById("spinner_cont").style.display="block";

        let self = this;
        //Security Layer (Auth of this ticket)
        let ticketnumber = self.ticketnumber;
        axios.get("/ticketauthandget/"+ticketnumber)
        .then(function(response)
        {
            if(response.data == "fake")
            {
                self.$router.push("/");
            }
            else
            {
                self.texts = response.data[0];
                self.username = response.data[1];
                self.subject = response.data[2];
                self.ticketstatus= response.data[0][0].status;
            }

            document.getElementById("spinner_cont").style.display="none";
        

        })
        .catch(function(error)
        {

        });

    },
    methods:
    {
        addtoticket:function(event)
        {
            event.preventDefault();

            //Add Customer Reply
             let self = this;
            let customerreply = this.messagecore;
            let ticketnumber = this.ticketnumber;

            axios.post("/savecustomerrteply", {customerreply, ticketnumber})
            .then(function(response)
            {
                if(response.data == "somethingwrong")
                {
                    Notiflix.Notify.Failure('حدث خطأ ما');
                }
                else if(response.data == "bodylength")
                {
                    Notiflix.Notify.Failure('الرد يجب أن لا يقل عن 5 أحرف ولا يزيد عن 500');
                }
                else
                {
                    self.$store.state.statererendercomp++;
                }
            })
            .catch(function(error)
            {

            })
        }
    }
}
</script>