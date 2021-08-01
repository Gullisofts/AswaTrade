<template>
    <div>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <Editcustomercomp :key="renderComp"></Editcustomercomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Editcustomercomp from "./../components/Editcustomercomp.vue";
import Popmessage from "./../components/Popmessage.vue";
export default {

    components: {

        Dashsidebar,
        Editcustomercomp,
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
