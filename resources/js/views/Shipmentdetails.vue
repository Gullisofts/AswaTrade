<template>
    <div>
        <Myheader></Myheader>
            <div class="ship_details mt-4">
                <div class="container">
                    <h5>تفاصيل الشحنة</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><span class="li_head">رقم الشحنة: </span> {{shipmentdata.shipmentno}}</li>
                        <li class="list-group-item"><span class="li_head">تاريخ الطلب: </span> {{shipmentdata.created_at}}</li>
                        
                        <li class="list-group-item">
                            <span class="li_head">المنتجات المطلوبة: </span>

                            <div class="prods_det mt-2" v-for="prod in shipmentproducts" :key="prod">
                                <div class="ship_details_prod">
                                    {{prod[0]}} | الكمية:  {{prod[1]}}
                                </div>
                            </div>

                        </li>
                        <li class="list-group-item"><span class="li_head">التكلفة: </span>{{(parseFloat(shipmentdata.cost).toFixed(2))+' '+currency}}</li>
                        <li class="list-group-item"><span class="li_head">الحالة: </span>{{shipmentstatus}}</li>
                        <li v-if="shipmentdata.rejectcause != null" class="list-group-item"><span class="li_head">سبب الرفض: </span>
                            <textarea class="form-control" rows="3" readonly>{{shipmentdata.rejectcause}}</textarea>
                        </li>
                    </ul>
                </div>
            </div>
        <Myfooter></Myfooter>
    </div>
</template>
<script>
import Myheader from '../components/Myheader.vue';
import Myfooter from '../components/Myfooter.vue';
import axios from 'axios';

export default {
    name: "Shipmentdetails",
    components:{
        Myheader,
        Myfooter,
    },
    data() {

        return {

            shipmentno: this.$route.params.shipmentno,
            shipmentdata: '',
            shipmentstatus: '',
            shipmentproducts: "",
            currency: "",
        }
    },
    created: function() {

        let self = this;
        document.getElementById("spinner_cont").style.display="block";
        //Get Currency
        axios.get("/getcurrency")
        .then(function(response)
        {
            self.currency = response.data;
        })
        .catch(function(error)
        {

        });

        //Get Shipment Details
        axios.get("/shipment/"+self.shipmentno)
        .then(function(response)
        {
            self.shipmentdata = response.data;

            //Adjust Shippment Status
            switch (self.shipmentdata.status)
            {
                case "road":
                    self.shipmentstatus = "الشحنة في الطريق إليك";
                    break;
                case "delivered":
                    self.shipmentstatus = "استلمت الشحنة";
                    break;
                case "pending":
                    self.shipmentstatus = "قيد المراجعة";
                    break;
                case "prepare":
                    self.shipmentstatus = "قيد التجهيز";
                    break;
                case "rejected":
                    self.shipmentstatus = "مرفوضة";
                break;
            }
            //Get Shipment Products
            axios.get("/shipmentproducts/"+self.shipmentdata.content)
            .then(function(response)
            {
                self.shipmentproducts = response.data;
            })
            .catch(function(error)
            {

            });

            document.getElementById("spinner_cont").style.display="none";
            
        }).catch(function(error)
        {

        });
        
    }
}
</script>