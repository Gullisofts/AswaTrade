<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Editsectioncomp :key="rerendercomp"></Editsectioncomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Editsectioncomp from "./../components/Editsectioncomp.vue";
import axios from "axios";
export default {

    components: {

        Dashsidebar,
        axios,
        Editsectioncomp,
        Dfooter,
        
    },
    data()
    {
        return {

            sectionId: this.$route.params.sectionId,
            sectiondata: [],
            sectionsidsM: [],
            rerendercomp: 0,
        }
    },
    computed: 
    {
        count () {

            return this.$store.state.statererendercomp;
        }
    },
    watch: {
        count () {

            this.rerendercomp = this.$store.state.statererendercomp;
        }
    }
}
</script>
<style scoped>

</style>