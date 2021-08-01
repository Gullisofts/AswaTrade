<template>
    <div class="similar_prods">
		<div class="container_fluid">
            <div class="row">

                <div class="col-md-12">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="0"> 

                    <!-- Wrapper for carousel items -->
                    <div class="carousel-inner" id="sildersimi">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import axios from "axios";
export default {
    name: "Productsslider",
    components: {
        axios,
    },
    data()
    {
        return {

            currency: "",
        }
    },
    created:function()
    {
        document.getElementById("spinner_cont").style.display="block";

        let prod_id = this.$store.state.currentProd;
        $("#sildersimi").html("");
        let self = this;

        //Get Currency
        axios.get("/getcurrency")
        .then(function(response)
        {
            self.currency = response.data;
        })
        .catch(function(error)
        {

        });


        axios.get("/getsimilarproducts/"+prod_id)
		.then(function(response)
		{

            let coll_line = "";
            self.similarstatus = 1;

            coll_line += '<div class="row"><div class="col"><div class="bbb_main_container pt-0"><div class="bbb_viewed_title_container d-flex flex-column justify-content-center align-items-center"><h3>منتجات <b>مشابهة</b></h3><div class="bbb_viewed_nav_container mt-4"><div class="bbb_viewed_nav bbb_viewed_prev"><i class="fas fa-chevron-left"></i></div><div class="bbb_viewed_nav bbb_viewed_next"><i class="fas fa-chevron-right"></i></div></div></div><div class="bbb_viewed_slider_container"><div class="owl-carousel owl-theme bbb_viewed_slider">';

            for(let i = 0; i<response.data.length; i++)
            {
                let ele_st = response.data[i];

                let prod_id_st = ele_st.id;


                let imgs_array_st = ele_st.productimages.split("__xYY7931YYx__");

                let prod_img_st = imgs_array_st[0];

                let prod_name_st = ele_st.productname;


                let prod_dis_price_st = "";

                let prod_rel_price_st = "";

                let discount_math_st = "";

                if(ele_st.discount > 0)
                {
                    prod_rel_price_st = ele_st.price;
                    discount_math_st = "0."+ele_st.discount.toString().padStart(2, '0');
                    prod_dis_price_st = ele_st.price - (ele_st.price * parseFloat(discount_math_st));
                }
                else
                {
                    prod_rel_price_st = ele_st.price;
                    prod_dis_price_st = 0;
                }

                let prod_rating_st = ele_st.rating;
                let rating_stars_st = "";


                for(let n=0; n < prod_rating_st; n++)
                {
                    rating_stars_st += '<i class="fa fa-star"></i>';
                }

                coll_line +='<div class="owl-item col-sm-12"><div class="bbb_viewed_item discount d-flex flex-column align-items-center justify-content-center text-center">';

                coll_line += '<div data-class='+prod_id_st+' class="bbb_viewed_image d-flex flex-row justify-content-center align-items-center"><img src="'+prod_img_st+'" alt=""></div>';
                
                if(prod_dis_price_st > 0)
                {
                    coll_line += '<div class="bbb_viewed_content text-center"><div class="bbb_viewed_price d-flex flex-row justify-content-center"><p class="mb-0 mr-1">'+self.currency+'</p><p class="mb-0">'+prod_dis_price_st+'</p><span>'+prod_rel_price_st+'</span></div>';
                }
                else
                {
                    coll_line += '<div class="bbb_viewed_content text-center"><div class="bbb_viewed_price d-flex flex-row justify-content-center"><p class="mb-0 mr-1">'+self.currency+'</p><p class="mb-0">'+prod_rel_price_st+'</p></div>';
                }

                coll_line += '<div class="bbb_viewed_name"><a id="'+prod_id_st+'" class="gotoprod">'+prod_name_st+'</a></div></div>';

                coll_line += '</div></div>';

            }
                    
            
            coll_line += '</div></div></div></div></div>';
            $("#myCarousel #sildersimi").html(coll_line);


            $(document).ready(function()
            {
                if($('.bbb_viewed_slider').length)
                {
                    var viewedSlider = $('.bbb_viewed_slider');

                    viewedSlider.owlCarousel(
                    {
                        loop:true,
                        margin:30,
                        autoplay:true,
                        autoplayTimeout:6000,
                        nav:false,
                        dots:false,
                        responsive:
                        {
                        0:{items:1},
                        575:{items:2},
                        768:{items:3},
                        991:{items:4},
                        1199:{items:6}
                    }
                    });

                    if($('.bbb_viewed_prev').length)
                    {
                    var prev = $('.bbb_viewed_prev');
                    prev.on('click', function()
                    {
                    viewedSlider.trigger('prev.owl.carousel');
                    });
                    }

                    if($('.bbb_viewed_next').length)
                    {
                    var next = $('.bbb_viewed_next');
                    next.on('click', function()
                    {
                    viewedSlider.trigger('next.owl.carousel');
                    });
                    }
                }

                document.getElementById("spinner_cont").style.display="none";
            });
              
		})
		.catch(function(error)
		{

		});
    },
    mounted:function()
    {   
        let self = this;

        //Go To Similar Product => click product title
        $(document).on("click", ".gotoprod", function()
        {
            let id = $(this).attr("ID");
            self.goToProd(id);
        });

        //Got To Similar Product => click product image
        $(document).on("click", ".bbb_viewed_image", function()
        {
            let id = $(this).data("class");
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