import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({

    state: 
    {
        currentProd: "",
        fromProduct: "",
        currentsection: "",
        cartcontent:[],
        tempcost: "",
        tempcart: "",
        statererendercomp:0,
    },
})

export default store;