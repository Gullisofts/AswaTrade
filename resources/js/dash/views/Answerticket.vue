<template>
    <div>
        <Answerticketcomp :key="renderComp"></Answerticketcomp>
    </div>
</template>
<script>
import Answerticketcomp from "./../components/Answerticketcomp.vue";
export default {

    components: {

        Answerticketcomp,
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