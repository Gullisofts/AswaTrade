<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Addsectioncomp :key="rerendercomp"></Addsectioncomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Addsectioncomp from "./../components/Addsectioncomp.vue";
import Dfooter from "./../components/Dfooter.vue";
export default {

    components: {

        Dashsidebar,
        Addsectioncomp,
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
<style scoped>

</style>