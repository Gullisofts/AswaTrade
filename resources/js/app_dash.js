import Vue from 'vue'
window.Vue = require("vue");
import VueRouter from 'vue-router'
Vue.use(VueRouter)

//Views
import Myhome from "./dash/views/Myhome.vue";
import Addproducts from "./dash/views/Addproducts.vue";
import Addnewsection from "./dash/views/Addnewsection.vue";
import Sections from "./dash/views/Sections.vue";
import Editsection from "./dash/views/Editsection.vue";
import Searchproduct from "./dash/views/Searchproduct.vue";
import Editproduct from "./dash/views/Editproduct.vue";
import Neworders from "./dash/views/Neworders.vue";
import Prepareorders from "./dash/views/Prepareorders.vue";
import Searchshippment from "./dash/views/Searchshippment.vue";
import Editshippment from "./dash/views/Editshippment.vue";
import Salesreports from "./dash/views/Salesreports.vue";
import Searchcustomer from "./dash/views/Searchcustomer.vue";
import Editcustomer from "./dash/views/Editcustomer.vue";
import Blockedusers from "./dash/views/Blockedusers.vue";
import Customercomments from "./dash/views/Customercomments.vue";
import Pages from "./dash/views/Pages.vue";
import Createpage from "./dash/views/Createpage.vue";
import Editpage from "./dash/views/Editpage.vue";
import Support from "./dash/views/Support.vue";
import Answerticket from "./dash/views/Answerticket.vue";
import Settings from "./dash/views/Settings.vue";
import Lowqty from "./dash/views/Lowqty.vue";

import store from "./store/store";
import axios from 'axios';

const routes = [

    {
        path: '/',
        component: Myhome,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/addnewproduct',
        component: Addproducts,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }

    },
    { 
        path: '/sections',
        component: Sections,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/addnewsection',
        component: Addnewsection,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/edit_section/:sectionId',
        component: Editsection,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/searchproduct',
        component: Searchproduct,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/lowqty',
        component: Lowqty,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/edit_product/:prodid',
        component: Editproduct,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/neworders',
        component: Neworders,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/prepareorders',
        component: Prepareorders,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },  
    { 
        path: '/searchshippment',
        component: Searchshippment,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    }, 
    { 
        path: '/edit_shippment/:shipid',
        component: Editshippment,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/salesrepo',
        component: Salesreports,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/searchcustomer',
        component: Searchcustomer,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/edit_customer/:custid',
        component: Editcustomer,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/blockedusers',
        component: Blockedusers,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/customercomments',
        component: Customercomments,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/pages',
        component: Pages,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/createpage',
        component: Createpage,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/edit_page/:id',
        component: Editpage,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/support',
        component: Support,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/answerticket/:id',
        component: Answerticket,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
    { 
        path: '/Settings',
        component: Settings,
        beforeEnter:(to, from, next) =>
        {
            axios.get("/chkadmin")
            .then(function(response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
            })
            .catch(function(error)
            {

            });
        }
    },
]

const router = new VueRouter({
    routes,
})

const app = new Vue({
    el: "#app_dash",
    components:{

    },
    router,
    store,

});