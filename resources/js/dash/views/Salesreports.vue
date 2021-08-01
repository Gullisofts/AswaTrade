<template>
    <div>
        <Salesreportscomp :key="renderComp"></Salesreportscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Salesreportscomp from "./../components/Salesreportscomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Salesreportscomp,
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