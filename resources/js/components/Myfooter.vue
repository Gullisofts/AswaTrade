<template>
    <div id="footerid">
        <!-- Footer -->
        <footer class="bg-dark text-center text-white mt-5">
        <!-- Grid container -->
            <div class="container p-4">
                <div class="row">
                    <!-- Section: Text -->
                    <section class="col-12  footer_desc">
                        <p>
                            {{sitedescrip}}
                        </p>
                    </section>
                    <!-- Section: Text -->
                </div>
                <hr>
                <!-- Section: Links -->
                <section class="footer_links">
                    <!--Grid row-->
                    <div class="row">
                        <!--Grid column-->
                        <div v-if="pages != ''" class="col-md-6 col-12 mb-4 mb-md-0">
                            <h5 class="text-uppercase">الصفحات</h5>

                            <ul class="list-unstyled mb-0 pr-0">
                                <li v-for="page in pages" :key="page.id">
                                    <router-link tag="a" :to="'/page/'+page.id" class="nav-link pr-0 text-light">{{page.title}}</router-link>
                                </li>
                            </ul>
                        </div>
                        <!--Grid column-->
                        <!--Grid column-->
                        <!-- Section: Social media -->
                        <section class="footer_social mb-4 col-md-6 col-12">
                            <!-- Facebook -->
                            <a class="btn btn-outline-light btn-floating m-1" v-bind:href="facebooklink" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>

                            <!-- Twitter -->
                            <a class="btn btn-outline-light btn-floating m-1" v-bind:href="twitterlink" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>

                            <!-- Instagram -->
                            <a class="btn btn-outline-light btn-floating m-1" v-bind:href="instagramlink" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </section>
                        <!-- Section: Social media -->
                        <!--Grid column-->
                    </div>
                    <!--Grid row-->
                </section>
                <!-- Section: Links -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2020 جميع الحقوق محفوظة: <a class="text-white" v-bind:href="sitelink">{{sitelink}}</a>
            </div>
            <!-- Copyright -->

        </footer>
        <!-- Footer -->
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name:"Myfooter",
    data()
    {
        return {

            sitelink: "",
            sitedescrip: "",
            facebooklink: "",
            twitterlink: "",
            instagramlink: "",
            pages: "",
            sections: "",
        }
    },
    components:
    {
        axios,
    },
    created: function()
    {
        let self = this;
        document.getElementById("spinner_cont").style.display="block"; 

        //GET WEBSITE DESCRIPTION
        axios.get('/getwebsitedescrip')
        .then(function(response)
        {
            self.sitedescrip = response.data;
        })
        .catch(function(error)
        {

        });

        //GET WEBSITE LINK
        axios.get('/getwebsitelink')
        .then(function(response)
        {
            self.sitelink = response.data;
            document.getElementById("spinner_cont").style.display="none"; 
        })
        .catch(function(error)
        {

        }); 

        //GET FOOTER LINKS
        axios.get("/sociallinks")
        .then(function(response)
        {
            self.facebooklink = response.data[0];
            self.twitterlink = response.data[1];
            self.instagramlink = response.data[2];
        })
        .catch(function(error)
        {

        });

        //GET PAGES
        axios.get('/getpagespublic'
        ).then(function(response)
        {
            self.pages = response.data;
        })
        .catch(function(error)
        {

        });

        //GET SECTIONS
        axios.get('/getheadersections'
        ).then(function(response)
        {
            self.sections = response.data;
        })
        .catch(function(error)
        {

        });

    },

}
</script>