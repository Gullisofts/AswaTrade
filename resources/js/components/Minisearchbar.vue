<template>
    <div class="schbar">
        <div class="row justify-content-center padding_search pt-0">
            <div class="col-md-8 ftco-animate fadeInUp ftco-animated">
                <form action="#" class="domain-form">
                    <div class="form-group d-md-flex row mb-0"> 
                        <button @click="st_search($event)" class="search-domain btn btn-primary px-4 col-lg-2 col-md-3 col-sm-3 col-12 order-sm-first order-last">بحث</button>
                        <input v-model="searchvalue" type="text" class="form-control search_box_m px-4 col-lg-10 col-md-9 col-sm-9 col-12 order-sm-last order-first" placeholder="ابحث عن........"> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    name:"Mysearchbar",
    data()
    {
        return {

            searchvalue:"",
        }
    },
    methods: 
    {
        st_search:function(event)
        {
            event.preventDefault();
            this.$router.push("/search/"+this.searchvalue);
        },
    },

}
</script>