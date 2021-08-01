<template>
    <div>
        <Createpagecomp :key="renderComp"></Createpagecomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Createpagecomp from "./../components/Createpagecomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Createpagecomp,
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
        count() {

            return this.$store.state.statererendercomp;

        }

    },
    watch:
    {
        count() {

            this.renderComp = this.$store.state.statererendercomp;

        }
    },

}
</script>