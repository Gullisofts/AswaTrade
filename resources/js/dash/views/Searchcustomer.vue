<template>
    <div>
        <Searchcustomercomp :key="renderComp"></Searchcustomercomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Searchcustomercomp from "./../components/Searchcustomercomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Searchcustomercomp,
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