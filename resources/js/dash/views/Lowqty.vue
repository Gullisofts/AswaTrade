<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Lowqtycomp :key="rerendercomp"></Lowqtycomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Lowqtycomp from "./../components/Lowqtycomp.vue";
export default {

    components: {

        Dashsidebar,
        Dfooter,
        Lowqtycomp,
    },
    data()
    {
        return {
            
            rerendercomp:0,
        }
    },
    computed: {
        count()
        {
            return this.$store.state.statererendercomp;
        }
    },
    watch: {
        count()
        {
            this.rerendercomp = this.$store.state.statererendercomp;
        }
    }

}
</script>