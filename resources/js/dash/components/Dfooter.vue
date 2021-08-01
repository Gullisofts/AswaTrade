<template>
    <div class="footercont text-center mt-5" style="padding-right: 60px">
        <footer class="text-center text-lg-start">
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color:#31353D; color: #ecf0f1;">
                © 2021 جميع الحقوق محفوظة
                <a class="text-light" v-bind:href="websitelink">{{websitename}}</a>
            </div>
            <!-- Copyright -->
        </footer>
    </div>
</template>
<script>
import axios from "axios";
export default {

    name: "Dfooter",
    components: 
    {
        axios,
    },
    data()
    {
        return {

            websitename: "",
            websitelink: "",
        }
    },
    mounted:function()
    {
        let self = this;

        axios.get("/getwebsitename")
        .then(function(response)
        {
            self.websitename = response.data;
        })
        .catch(function(error)
        {

        });

        axios.get("/getwebsitelink")
        .then(function(response)
        {
            self.websitelink = response.data;
        })
        .catch(function(error)
        {

        });
    }

}
</script>
<style scoped>
@media(max-width: 757px)
{
    footer
    {
        font-size: 12px !important;
    }
}
</style>