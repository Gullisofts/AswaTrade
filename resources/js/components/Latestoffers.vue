<template>
    <div>
        <div class="latest_offers" id="latest_offers">

            <h4 class="mainh4">عروض اليوم</h4>

            <div class="container productsspread">

                <div class="row prods_row" id="prods_row">

                </div>
                
                <template v-if="allshown == 'false'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button id="show_more_prod_btn" class="btn btn-primary show_more text-light" style="cursor:pointer;">عرض المزيد...</button>
                    </div>
                </template>
                <template v-if="allshown == 'true'">
                    <div class="mt-3 text-center w-100 mb-2">
                        <button class="btn btn-primary show_more text-light" style="cursor:pointer;" disabled>نهاية النتائج</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
<script>

import axios from 'axios';

export default {
    name:"Latestoffers",
    data() 
    {
        return {

            currency: "",
            lastid: 0,
            allshown: "notset",
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

        //Get Currency
        axios.get("/getcurrency")
        .then(function(response)
        {
            self.currency = response.data;
        })
        .catch(function(error)
        {

        });

        let lastid = self.lastid;
        let gottendata = "";
        $(".latest_offers .prods_row").html("");

        axios.post("/getdiscountprods", {lastid})
        .then(function(response)
        {
            if(response.data != "nodata")
            {
                if(response.data.length == 2)
                {
                    self.lastid = response.data[0];
                    lastid = response.data[0];
                    self.allshown = "false";
                    gottendata = response.data[1].data;

                    for(let i=0; i<gottendata.length; i++)
                    {
                        let ele = gottendata[i];

                        let prod_id = ele.id;
                        let imgs_array = ele.productimages.split("__xYY7931YYx__");
                        let prod_img = imgs_array[0];
                        let prod_rel_price = ele.price;
                        let discount_math = "0."+ele.discount.toString().padStart(2, '0');
                        let prod_dis_price = ele.price - (ele.price * parseFloat(discount_math));
                        let prod_name = ele.productname;
                        let prod_qty = ele.qty;
                        let prod_rating = ele.rating;
                        let rating_stars = "";

                        for(let n=0; n < prod_rating; n++)
                        {
                            rating_stars += '<i class="fa fa-star"></i>';
                        }

                        $("#latest_offers #prods_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike>'+prod_rel_price +" "+self.currency+'</strike></div><div class="bbb_deals_item_price">'+prod_dis_price +" "+self.currency+'</div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');

                    }
                
                    document.getElementById("spinner_cont").style.display="none";

                }
                else if(response.data.length == 3)
                {
                    self.lastid = response.data[0];
                    lastid = response.data[0];
                    self.allshown = "true";
                    gottendata = response.data[1].data;

                    for(let i=0; i<gottendata.length; i++)
                    {
                        let ele = gottendata[i];

                        let prod_id = ele.id;
                        let imgs_array = ele.productimages.split("__xYY7931YYx__");
                        let prod_img = imgs_array[0];
                        let prod_rel_price = ele.price;
                        let discount_math = "0."+ele.discount.toString().padStart(2, '0');
                        let prod_dis_price = ele.price - (ele.price * parseFloat(discount_math));
                        let prod_name = ele.productname;
                        let prod_qty = ele.qty;
                        let prod_rating = ele.rating;
                        let rating_stars = "";

                        for(let n=0; n < prod_rating; n++)
                        {
                            rating_stars += '<i class="fa fa-star"></i>';
                        }

                        $("#latest_offers #prods_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike>'+prod_rel_price +" "+self.currency+'</strike></div><div class="bbb_deals_item_price">'+prod_dis_price +" "+self.currency+'</div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');

                    }
                
                    document.getElementById("spinner_cont").style.display="none";
                }
            }

        })
        .catch(function(error)
        {

        });

        //Show More Discount Products
        $(document).on("click", "#show_more_prod_btn", function(e)
        {
            document.getElementById("spinner_cont").style.display="block";
            e.stopImmediatePropagation();
            e.stopPropagation();

            lastid = self.lastid;

            axios.post("/getdiscountprods", {lastid})
            .then(function(response)
            {
                if(response.data != "nodata")
                {
                    if(response.data.length == 2)
                    {
                        self.lastid = response.data[0];
                        lastid = response.data[0];
                        self.allshown = "false";
                        gottendata = response.data[1].data;

                        for(let i=0; i<gottendata.length; i++)
                        {
                            let ele = gottendata[i];

                            let prod_id = ele.id;
                            let imgs_array = ele.productimages.split("__xYY7931YYx__");
                            let prod_img = imgs_array[0];
                            let prod_rel_price = ele.price;
                            let discount_math = "0."+ele.discount.toString().padStart(2, '0');
                            let prod_dis_price = ele.price - (ele.price * parseFloat(discount_math));
                            let prod_name = ele.productname;
                            let prod_qty = ele.qty;
                            let prod_rating = ele.rating;
                            let rating_stars = "";

                            for(let n=0; n < prod_rating; n++)
                            {
                                rating_stars += '<i class="fa fa-star"></i>';
                            }

                            $("#latest_offers #prods_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike>'+prod_rel_price +" "+self.currency+'</strike></div><div class="bbb_deals_item_price">'+prod_dis_price +" "+self.currency+'</div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');

                        }
                    
                        document.getElementById("spinner_cont").style.display="none";

                    }
                    else if(response.data.length == 3)
                    {
                        self.lastid = response.data[0];
                        lastid = response.data[0];
                        self.allshown = "true";
                        gottendata = response.data[1].data;

                        for(let i=0; i<gottendata.length; i++)
                        {
                            let ele = gottendata[i];

                            let prod_id = ele.id;
                            let imgs_array = ele.productimages.split("__xYY7931YYx__");
                            let prod_img = imgs_array[0];
                            let prod_rel_price = ele.price;
                            let discount_math = "0."+ele.discount.toString().padStart(2, '0');
                            let prod_dis_price = ele.price - (ele.price * parseFloat(discount_math));
                            let prod_name = ele.productname;
                            let prod_qty = ele.qty;
                            let prod_rating = ele.rating;
                            let rating_stars = "";

                            for(let n=0; n < prod_rating; n++)
                            {
                                rating_stars += '<i class="fa fa-star"></i>';
                            }

                            $("#latest_offers #prods_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike>'+prod_rel_price +" "+self.currency+'</strike></div><div class="bbb_deals_item_price">'+prod_dis_price +" "+self.currency+'</div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');

                        }
                    
                        document.getElementById("spinner_cont").style.display="none";
                    }
                }

                document.getElementById("spinner_cont").style.display="none";

            })
            .catch(function(error)
            {

            });
        });

    },
    mounted()
    {
        let self = this;

        //Go To Similar Product
        $(document).on("click", ".prod", function(event)
        {
            let id = event.currentTarget.getAttribute("id");
            self.goToProd(id);
        });

    },
    methods:
    {
        goToProd(id)
        {
            this.$router.push({path: "/product/"+id});
        }
    },
}
</script>