<template>
    <div>
        <Blockeduserscomp :key="renderComp"></Blockeduserscomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Blockeduserscomp from "./../components/Blockeduserscomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Blockeduserscomp,
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