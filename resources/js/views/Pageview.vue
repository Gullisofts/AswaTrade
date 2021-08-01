<template>
    <div>
        <Myheader></Myheader>

            <div class="page-view">
                <div class="container">
                    <div class="pagecontent" v-html="pagedetails"></div>
                </div>
            </div>

        <Myfooter></Myfooter>
    </div>
</template>
<script>
import axios from 'axios';
import Myheader from '../components/Myheader.vue';
import Myfooter from '../components/Myfooter.vue';

export default {

    name: "Login",
    components:{
        Myheader,
        Myfooter,
        axios,
    },
    data() {
        return {

            id: this.$route.params.id,
            pagedetails: "",
        }

    },
    mounted()
    {
        let self = this;

        //Get Page Details
        let pageid = self.id;
        document.getElementById("spinner_cont").style.display="block";

        axios.get("/getpagedetailspublic/"+pageid)
        .then(function(response)
        {
            self.pagedetails = response.data;
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        })
    },
    
}
</script>
<style scoped>
.pagecontent
{
    direction: rtl;
    padding-top: 40px;
    text-align: right;
}
</style>