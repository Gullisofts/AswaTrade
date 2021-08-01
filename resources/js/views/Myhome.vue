<template>
    <div>
        <Myheader></Myheader>
        <Mysearchbar></Mysearchbar>
        <hr class="hr_high">
        <Latestoffers></Latestoffers>
        <Myfooter></Myfooter>
    </div>
</template>
<script>
import Myheader from './../components/Myheader.vue';
import Mysearchbar from './../components/Mysearchbar.vue';
import Latestoffers from './../components/Latestoffers.vue';
import Myfooter from './../components/Myfooter.vue';

export default {

    name: "Myhome",
    components:{
        Myheader,
        Mysearchbar,
        Latestoffers,
        Myfooter,
    }, 
}
</script>