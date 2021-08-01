<template>
    <div>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <Prepareorderscomp :key="renderComp"></Prepareorderscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Popmessage from "./../components/Popmessage.vue";
import Prepareorderscomp from "./../components/Prepareorderscomp";

export default {

    components: {

        Dashsidebar,
        Prepareorderscomp,
        Popmessage,
        Dfooter,
    },
    data() 
    {
        return {

            renderComp: 0,
        }
    },
    computed:
    {
        count()
        {
            return this.$store.state.statererendercomp;
        }
    },
    watch:
    {
        count()
        {
            this.renderComp = this.$store.state.statererendercomp;
        }
    }

}
</script>
