import Vue from 'vue'
window.Vue = require("vue");
import VueRouter from 'vue-router'
Vue.use(VueRouter)

//Views
import Myhome from "./views/Myhome.vue";
import Pageview from "./views/Pageview.vue";
import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import Profile from "./views/Profile.vue";
import Resetpassword from "./views/Resetpassword.vue";
import Shipmentdetails from "./views/Shipmentdetails.vue";
import Productview from "./views/Productview.vue";
import Section from "./views/Section.vue";
import Basket from "./views/Basket.vue";
import Search from "./views/Search.vue";
import Support from "./views/Support.vue";
import Openticket from "./views/Openticket.vue";
import Ticketview from "./views/Ticketview.vue";

//Tools
import axios from 'axios';
import store from "./store/store";


//Routes
const routes = [
    { 
        path: '/', 
        component: Myhome, 
        name: "homepage",
    },
    { 
        path: '/page/:id',
        component: Pageview, 
    },
    { 
        path: '/login',
        component: Login,
        name: "login",
        beforeEnter: (to, from, next) =>
        {

            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next({name:"homepage"});
                }
                else
                {
                    next();
                }
            });

        }
    },
    { 
        path: '/register',
        component: Register, 
    },
    { 
        path: '/resetpassword',
        component: Resetpassword,
        beforeEnter: (to, from, next) =>
        {

            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next({name:"homepage"});
                }
                else
                {
                    next();
                }
            });

        }
    },
    { 
        path: '/support',
        component: Support, 
        beforeEnter: (to, from, next) =>
        {
            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
                else
                {
                    next({name:"login"});
                }
            });
        }
    },
    { 
        path: '/openticket',
        component: Openticket, 
        beforeEnter: (to, from, next) =>
        {
            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
                else
                {
                    next({name:"login"});
                }
            });
        }
    },
    { 
        path: '/viewticket/:ticketnumber',
        component: Ticketview, 
        beforeEnter: (to, from, next) =>
        {
            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
                else
                {
                    next({name:"login"});
                }
            });
        }
    },
    { 
        path: '/profile',
        component: Profile, 
        beforeEnter: (to, from, next) =>
        {
            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
                else
                {
                    next({name:"homepage"});
                }
            });
        }
    },
    { 
        path: '/shipdetails/:shipmentno',
        component: Shipmentdetails, 
        beforeEnter: (to, from, next) =>
        {
            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
                else
                {
                    next({name:"homepage"});
                }
            });
        }
    },
    { 
        path: '/product/:productid',
        component: Productview,
    },
    { 
        path: '/section/:sectionid',
        component: Section,
    },
    { 
        path: '/search/:searchvalue',
        component: Search,
    },
    { 
        path: '/basket',
        component: Basket, 
        beforeEnter: (to, from, next) =>
        {
            axios.get('/chklogin'
            ).then(function (response)
            {
                if(response.data == "loggedin")
                {
                    next();
                }
                else
                {
                    next({name:"login"});
                }
            });
        }
    },

]
const router = new VueRouter({
    routes,
})

const app = new Vue({
    el: "#app",
    components:{

    },
    store,
    router,

});