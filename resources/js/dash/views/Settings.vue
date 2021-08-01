<template>
    <div>
        <Settingscomp :key="renderComp"></Settingscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Settingscomp from "./../components/Settingscomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Settingscomp,
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