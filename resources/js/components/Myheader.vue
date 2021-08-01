<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            
            <router-link tag="a" to="/" class="navbar-brand font-weight-bold d-block">{{sitename}}</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item" v-if="loggedIn === false">
                        <router-link tag="a" to="/login" class="nav-link font-weight-bold text-uppercase">تسجيل دخول</router-link>
                    </li>

                    <li class="nav-item" v-if="loggedIn">
                        <router-link tag="a" to="/profile" class="nav-link font-weight-bold text-uppercase">الملف الشخصي</router-link>
                    </li>

                    <li class="nav-item" v-if="loggedIn"><a href="/logout" class="nav-link font-weight-bold text-uppercase">خروج</a></li>
                    <template v-if="pages != ''">
                        <li v-for="page in pages" class="nav-item">
                            <router-link tag="a" :to="'/page/'+page.id" class="nav-link font-weight-bold text-uppercase">{{page.title}}</router-link>
                        </li>
                    </template>

                    <li class="nav-item" v-if="loggedIn">
                        <router-link tag="a" to="/support" class="nav-link font-weight-bold text-uppercase">الدعم</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
            <a class="navbar-brand font-weight-bold">الأقسام</a>
            <button type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"> 
                <span class="navbar-toggler-icon"></span>
            </button>
   
            <div id="navbarContent" class="collapse navbar-collapse">
                <ul class="navbar-nav mx-auto" id="add_header_content">

                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item ml-auto">
                        <router-link tag="a" to="/basket" class="nav-link font-weight-bold text-uppercase">
                            <span id="cartno-manually" class="p1 fa-stack fa-2x has-badge" v-bind:data-count="cartnumo">
                                <i class="p3 fa fa-shopping-cart fa-stack-1x xfa-inverse" data-count="4b"></i>
                            </span>
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>

    </div>
    
</template>

<script>
import axios from 'axios';
export default {

    name:"MyHeader",
    data() {
    return {

      loggedIn: false,
      cartnumo: 0,
      pages: "",
      sitename: "",
    }
  },
    created: function()
    {
        document.getElementById("spinner_cont").style.display="block"; 

        let self = this;

        $("#add_header_content").html("");

        axios.get('/chklogin'
        ).then(function(response)
        {
            if(response.data == "loggedin")
            {
                self.loggedIn = true;

            }
        });

        axios.get('/getheadersections'
        ).then(function(response)
        {
            $("#add_header_content").prepend(response.data);
        })
        .catch(function(error)
        {

        });

        axios.get('/getwebsitename'
        ).then(function(response)
        {
            self.sitename = response.data;
        })
        .catch(function(error)
        {

        });

        //Get Pages
        axios.get('/getpagespublic'
        ).then(function(response)
        {
            self.pages = response.data;
            document.getElementById("spinner_cont").style.display="none";
        })
        .catch(function(error)
        {

        });

        //Go To Section
        $(document).on("click", ".section_entry", function(e)
        {
            e.preventDefault();
            self.goToSection($(this).attr("HREF"));
        });


        //Set Cart Content LOCAL STORAGE
        if(localStorage.getItem("cartcontent"))
        {
            let counto = JSON.parse(localStorage.getItem("cartcontent"));
            this.cartnumo = counto.length;
        }
        else
        {
            let cartcontents = [];
            localStorage.setItem("cartcontent", JSON.stringify(cartcontents));
        }

    },
    methods:
    {
        goToSection:function(sectionid)
        {
            this.$router.push("/section/"+sectionid);
        }
    },
    computed: {
    count () {
      return this.$store.state.cartcontent.length
    }
  },
  watch: {
    count () {
      // Our fancy notification (2).
      this.cartnumo = this.$store.state.cartcontent.length;
    }
  }

}
</script>
<style scoped>
.p1[data-count]:after
{
  position:absolute;
  right:10%;
  top:8%;
  content: attr(data-count);
  font-size:40%;
  padding:.2em;
  border-radius:50%;
  line-height:1em;
  color: white;
  background:rgba(255,0,0,.85);
  text-align:center;
  min-width: 1em;
}


.fa-stack[data-count]:after
{
  position:absolute;
  right:0%;
  top:1%;
  content: attr(data-count);
  font-size:30%;
  padding:.6em;
  border-radius:50%;
  line-height:.8em;
  color: white;
  background:rgba(255,0,0,.85);
  text-align:center;
  min-width: 1em;
  font-weight:bold;
}

.fa-stack[data-count]:after
{
  position:absolute;
  right:0%;
  top:1%;
  content: attr(data-count);
  font-size:30%;
  padding:.6em;
  border-radius:999px;
  line-height:.75em;
  color: white;
  background:rgba(255,0,0,.85);
  text-align:center;
  min-width:2em;
  font-weight:bold;
}
</style>