<template>
    <div>
        <Supportcomp :key="renderComp"></Supportcomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Supportcomp from "./../components/Supportcomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Supportcomp,
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