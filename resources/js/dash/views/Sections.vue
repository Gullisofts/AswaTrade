<template>
    <div>
        <Sectionscomp :key="renderComp"></Sectionscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Sectionscomp from "./../components/Sectionscomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Sectionscomp,
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