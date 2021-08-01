<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Searchshippmentcom :key="rerendercomp"></Searchshippmentcom>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Searchshippmentcom from "./../components/Searchshippmentcom.vue";
export default {

    components: {

        Dashsidebar,
        Searchshippmentcom,
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