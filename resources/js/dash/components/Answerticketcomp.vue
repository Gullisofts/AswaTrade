<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <div class="container-fluid">
            <div class="dash_bod">
                <h5 class="main_heading">الدعم</h5>
                
                <div class="ticketview mt-3" style="font-size: 16px;">

                    <div class="text-right">
                        <p>معرف العميل: {{customerid}}</p>

                        <div class="inner_ticket_options mt-4">
                            <h6 class="mt-2">خيارات التذكرة: </h6>
                            <ul class="list-group list-group-horizontal mr-0 pr-0">
                                <li v-if="ticketstatus == 'closed'" class="list-group-item text-success">
                                    <i class="fas fa-lock-open reopenticket" title="اعادة فتح التذكرة"></i>
                                </li>
                                <li v-if="ticketstatus != 'closed'" class="list-group-item text-success">
                                    <i class="fas fa-check-circle closeticket" title="اغلاق التذكرة"></i>
                                </li>
                                <li class="list-group-item text-danger mr-2">
                                    <i class="fas fa-trash deleteticket" title="حذف التذكرة"></i>
                                </li>
                            </ul>

                        </div>

                    </div>

                    <div class="text-right mt-4">
                        <div class="form-group mb-4">
                            <label for="ticket_sunbject">العنوان :</label>
                            <input type="text" class="form-control" id="ticket_sunbject" v-bind:value="subject" readonly>
                        </div>
                        <form class="ticket_view img-thumbnail">
                            <template v-if="texts != false">
                                <div v-for="text in texts" class="form-group mt-4">

                                    <div v-if="text.member_id != 'support'" class="d-flex flex-row mb-2">
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

                        <form v-if="ticketstatus != 'closed'" class="mt-3">
                            <div class="form-group">
                                <label for="ticket_body">اضافة رد</label>
                                <textarea v-model="messagecore" class="form-control " id="ticket_body" rows="7"></textarea>
                            </div>
                            <button v-on:click="answerticket($event)" class="btn btn-primary col-2">رد</button>
                        </form>

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

            id: this.$route.params.id,
            texts: false,
            username: "",
            subject: "",
            messagecore: "",
            customerid: "",
            ticketstatus: "",
        }
    },
    created:function()
    {
        let self = this;
        let ticketnumber = self.id;
        document.getElementById("spinner_cont").style.display="block";

        axios.get("/getticketinfo/"+ticketnumber)
        .then(function(response)
        {
            if(response.data == "fake")
            {
                self.$router.push("/");
            }
            else
            {
                self.texts = response.data[0];
                self.customerid = response.data[0][0].member_id;
                self.ticketstatus = response.data[0][0].status;
                self.username = response.data[1];
                self.subject = response.data[2];
            }
            
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });

        //Close Ticket
        $(document).on("click", ".closeticket", function()
        {
            Notiflix.Confirm.Show( 'تأكيد الاغلاق', 'هل تريد اغلاق هذه التذكرة؟', 'نعم', 'لا', function()
            { 
                axios.get("/ticket_close/"+ticketnumber)
                .then(function(response)
                {
                    self.$router.push({path:"/support"});
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });

        //Delete Ticket
        $(document).on("click", ".deleteticket", function()
        {

            Notiflix.Confirm.Show( 'تأكيد الحذف', 'هل تريد حذف هذه التذكرة نهائيا؟', 'نعم', 'لا', function()
            { 
                axios.get("/ticket_delete/"+ticketnumber)
                .then(function(response)
                {
                    self.$router.push({path:"/support"});
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });

        //Re-Open Ticket
        $(document).on("click", ".reopenticket", function()
        {

            Notiflix.Confirm.Show( 'تأكيد إعادة الفتح', 'هل تريد إعادة فتح التذكرة؟', 'نعم', 'لا', function()
            { 
                axios.get("/ticket_reopen/"+ticketnumber)
                .then(function(response)
                {
                    self.$store.state.statererendercomp++;
                })
                .catch(function(error)
                {

                }); 
            }, 
            function()
            { 
                
            }); 
        });
    },
    methods: 
    {
        answerticket:function(event)
        {
            if(event)
            {
                event.preventDefault();
            }

            let self = this;
            let messagecore = this.messagecore;
            let ticket_id = this.id;
            axios.post("/savesupportreply", {messagecore, ticket_id})
            .then(function(response)
            {
                self.$store.state.statererendercomp++;
            })
            .catch(function(error)
            {

            });
        }
    }

}
</script>