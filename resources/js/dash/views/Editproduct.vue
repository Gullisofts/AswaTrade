<template>
    <div>
        <Popmessage></Popmessage>
        <Dashsidebar></Dashsidebar>
        <Editproductcomp :key="renderComp"></Editproductcomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Editproductcomp from "./../components/Editproductcomp.vue";
import Popmessage from "./../components/Popmessage.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Dashsidebar,
        Editproductcomp,
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
