<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Editshippmentcom :key="rerendercomp"></Editshippmentcom>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Editshippmentcom from "./../components/Editshippmentcom.vue";
export default {

    components: {

        Dashsidebar,
        Editshippmentcom,
        Dfooter,
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