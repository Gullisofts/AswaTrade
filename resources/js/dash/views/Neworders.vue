<template>
    <div>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <Neworderscomp :key="renderComp"></Neworderscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Popmessage from "./../components/Popmessage.vue";
import Neworderscomp from "./../components/Neworderscomp.vue";

export default {

    components: {

        Dashsidebar,
        Neworderscomp,
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
