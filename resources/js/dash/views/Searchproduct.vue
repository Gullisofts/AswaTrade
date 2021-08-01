<template>
    <div>
        <Dashsidebar></Dashsidebar>
        <Searchproductcomp :key="rerendercomp"></Searchproductcomp>
        <Dfooter></Dfooter>
    </div>
</template>
<script>
import Dashsidebar from "./../components/Dashsidebar.vue";
import Dfooter from "./../components/Dfooter.vue";
import Searchproductcomp from "./../components/Searchproductcomp.vue";
export default {

    components: {

        Dashsidebar,
        Dfooter,
        Searchproductcomp,
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