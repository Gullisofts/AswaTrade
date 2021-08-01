<template>
    <div>
        <Pagescomp :key="renderComp"></Pagescomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Pagescomp from "./../components/Pagescomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Pagescomp,
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