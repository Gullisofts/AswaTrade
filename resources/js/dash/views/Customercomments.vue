<template>
    <div>
        <Customercommentscomp :key="renderComp"></Customercommentscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Customercommentscomp from "./../components/Customercommentscomp";
import Dfooter from "./../components/Dfooter";
export default {

    components: {

        Customercommentscomp,
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