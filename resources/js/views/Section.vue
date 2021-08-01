<template>
    <div>
        <Myheader></Myheader>
        <Minisearchbar></Minisearchbar>
        <div class="heading_main shadow-none p-3 mb-2 bg-light rounded">
            {{sectionname}}
        </div>
        <div class="section_show_body">
            <Prodsshow v-if="rerendercomponent"></Prodsshow>
        </div>
        <Myfooter></Myfooter>
    </div>
</template>

<script>
import Myheader from './../components/Myheader';
import Myfooter from './../components/Myfooter';
import Prodsshow from './../components/Prodsshow';
import Minisearchbar from './../components/Minisearchbar';

import axios from "axios";

export default {

    name: "Section",
    components:{
        Myheader,
        axios,
        Myfooter,
        Prodsshow,
        Minisearchbar,  
    },
    data()
    {
        return {

            sectionid: this.$route.params.sectionid,
            sectionname: "",
            rerendercomponent: true,
        }
    },
    mounted:function()
    {
        let self = this;
        document.getElementById("spinner_cont").style.display="block";
        // Get Section Name
        axios.get("/sectionname/"+self.sectionid)
        .then(function(response)
        {
            self.sectionname = response.data[1] + " / " + response.data[0];
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });

    },
    
}
</script>

<style scoped>
.heading_main
{
    direction: rtl;
    text-align: right;
    font-weight: bold;
}
.section_show_body
{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
</style>