<template>
    <div>
        <Commentscomp :key="rerendercomp"></Commentscomp>
    </div>
</template>
<script>
import Commentscomp from './Commentscomp.vue';

export default {

    components: {

        Commentscomp,
        
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