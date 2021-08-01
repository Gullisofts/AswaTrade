<template>
    <div>
        <Myheader></Myheader>
        <Minisearchbar></Minisearchbar>
        <div class="heading_main shadow-none p-3 mb-2 bg-light rounded">
             نتائج البحث عن: {{searchvalue}}
        </div>
        <div class="section_show_body">
        <Searchprods></Searchprods>
        </div>
        <Myfooter></Myfooter>
    </div>
</template>

<script>
import Myheader from './../components/Myheader';
import Myfooter from './../components/Myfooter';
import Searchprods from './../components/Searchprods';
import Minisearchbar from './../components/Minisearchbar';
import axios from "axios";

export default {

    name: "Section",
    components:{
        Myheader,
        axios,
        Myfooter,
        Searchprods,
        Minisearchbar,
    },
    data()
    {
        return {

            searchvalue: this.$route.params.searchvalue,
        }
    },
    mounted:function()
    {

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