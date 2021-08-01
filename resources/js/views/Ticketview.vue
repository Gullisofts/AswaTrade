<template>
    <div>
        <Ticketviewcomp :key="rerendercomp"></Ticketviewcomp>
    </div>
</template>
<script>
import Ticketviewcomp from './../components/Ticketviewcomp.vue';

export default {

    components: {

        Ticketviewcomp,
        
    },
    data()
    {
        return {

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