<template>
    <div>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <Addproductcomp :key="renderComp"></Addproductcomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Addproductcomp from "./../components/Addproductcomp.vue";
import Popmessage from "./../components/Popmessage.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Dashsidebar,
        Addproductcomp,
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
