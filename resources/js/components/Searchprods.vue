<template>
  <div class="w-100">

    <div class="section_filter">
      <div class="container-fluid">
        <button class="btn btn-primary resfilter" id="resfilter">
          <h6 class="text-center m-0">فلترة النتائج</h6>
          &nbsp;
          <i class="fas fa-angle-down"></i>
        </button>

        <div class="section_filter_inner img-thumbnail" id="filter_section">
          <div class="row">

            <div class="col-lg-4 col-12">
              <h6 class="w-100 text-center img-thumbnail">الموديل</h6>
              <span id="modelscheckboxes">
                  <div v-for="prodsdataya in prodsdata" :key="prodsdataya" class="form-check">
                      <input class="form-check-input model_checkbox" :data-class="prodsdataya" type="checkbox" value="">
                      <label class="form-check-label">{{prodsdataya}}</label>
                  </div>
              </span>
            </div>

            <div class="col-lg-4 col-12">
              <h6 class="w-100 text-center img-thumbnail mt-2">السعر</h6>
              <span>
                <div class="form-group">
                    <label class="mr-0" for="pricefrom">من</label>
                    <input type="number" class="form-control" id="sec_fil_pricefrom" placeholder="السعر من">
                </div>
                <div class="form-group">
                    <label class="mr-0" for="priceto">إلى</label>
                    <input type="number" class="form-control" id="sec_fil_priceto" placeholder="السعر إلى">
                </div>
              </span>
            </div>

            <div class="col-lg-4 col-12">
              <h6 class="w-100 text-center img-thumbnail">التقييم</h6>
              <span id="ratingcheckboxes">
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="1">
                      <label class="form-check-label" for="onestar">1</label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="2">
                      <label class="form-check-label" for="twostar">2</label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="3">
                      <label class="form-check-label" for="threestar">3</label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="4">
                      <label class="form-check-label" for="fourstar">4</label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="5">
                      <label class="form-check-label" for="fivestar">5</label>
                  </div>
              </span>
            </div>
            <div class="col-12 text-center">
              <button class="btn btn-primary ml-1" id="filter_st">فلترة</button>
              <button class="btn btn-danger mr-1" id="filter_abolish">إلغاء</button>
            </div>
          </div>
        </div> 
      </div>
    </div>

    <div class="latest_offers prodsshow" id="prodsshow">

        <div class="container-fluid productsspread">

            <div class="row prods_row" id="prodsshow_row">
                
            </div>

            <div v-if="allshown == 'true'" class="w-100 mt-5 text-center showmorebtn">
              <button class="btn btn-primary showmore_section" id="normalviewsection" disabled>أعرض المزيد...</button>
            </div>

            <div v-if="allshown == 'false'" class="w-100 mt-5 text-center showmorebtn">
              <button class="btn btn-primary showmore_section" id="normalviewsection">أعرض المزيد...</button>
            </div>

        </div>
    </div>
  </div>
</template>
<script>

import axios from 'axios';


export default {
    name:"Prodsshow",
    data() 
    {
        return {
            
            prodsdata: [],
            lastprodid: 0,
            elementsgot: false,
            searchvalue: this.$route.params.searchvalue,
            last_filter_ele: 0,
            models_arr: [],
            price_arr: [],
            rating_arr: [],
            currency: "",
            allshown: "notset",
        }
    },
    components:
    {
        axios,
    },
    created:function()
    {
        let self = this;
        $(".latest_offers .prods_row").html("");

        //Get Currency
        axios.get("/getcurrency")
        .then(function(response)
        {
            self.currency = response.data;
        })
        .catch(function(error)
        {

        });

        ////////////////// Normal Show ////////////////////
        /////////////////////////////////////////////////////////
        axios.get("/getsearchproducts/"+self.searchvalue+"__"+self.lastprodid)
        .then(function(response)
        {
          self.lastprodid = 0;
          self.last_filter_ele = 0;
          self.elementsgot = false;
          
          $("#filterviewsection").attr("ID", "normalviewsection");
          self.allshown = "true";

          if(response.data == "")
          {
            $("#prodsshow #prodsshow_row").html('<div class="w-100 mt-5 fs-3 text-center">لا توجد نتائج</div>');
            self.allshown = "true";
          }
          else if(response.data.length == 2)
          {
            self.lastprodid = response.data[0][response.data[0].length - 1].id;
            self.allshown = "true";
            self.elementsgot = true;

            for(let i=0; i<response.data[0].length; i++)
            {
                let ele = response.data[0][i];

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
                if(prod_rel_price == prod_dis_price)
                {
                    $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                }
                else
                {
                    $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                }

            }

          }
          else if(response.data.length == 1)
          {
            self.lastprodid = response.data[0][response.data[0].length - 1].id;
            self.allshown = "false";
            for(let i=0; i<response.data[0].length; i++)
            {
                let ele = response.data[0][i];

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

                if(prod_rel_price == prod_dis_price)
                {
                    $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                }
                else
                {
                    $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                }
            }
          }

            
        })
        .catch(function(error)
        {

        });

        ////////////////// Normal Show More ////////////////////
        /////////////////////////////////////////////////////////
        $(document).on("click", "#normalviewsection", function()
        {
          //Determmine If show more in Normal View Section Or Filtered View Section
          //check if All Elements Has been Got

              axios.get("/getsearchproducts/"+self.searchvalue+"__"+self.lastprodid)
              .then(function(response)
              {  
                self.lastprodid = response.data[0][response.data[0].length - 1].id;

                if(response.data == "")
                {
                  self.elementsgot = true;
                  self.allshown = "true";
                }
                else if(response.data.length == 2)
                {
                  self.elementsgot = true;
                  self.allshown = "true";

                  for(let i=0; i<response.data[0].length; i++)
                  {
                      let ele = response.data[0][i];

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

                      if(prod_rel_price == prod_dis_price)
                      {
                          $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                      }
                      else
                      {
                          $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                      }
                  }
                }
                else if(response.data.length == 1)
                {
                  self.allshown = "false";

                  for(let i=0; i<response.data[0].length; i++)
                  {
                      let ele = response.data[0][i];

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

                      if(prod_rel_price == prod_dis_price)
                      {
                          $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                      }
                      else
                      {
                          $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                      }
                  }
                }
  
              })
              .catch(function(error)
              {

              });
    
        });

      ////////////////// Filter Show ////////////////////
      /////////////////////////////////////////////////////////
      $(document).on('click', "#filter_st", function()
      { 
          self.elementsgot == false;
          self.last_filter_ele= 0;

          $("#prodsshow #prodsshow_row").html('');
          $("#filterviewsection").html("");
          $(".showmore_section").attr("ID", "filterviewsection");


          let models_arr = [];
          let price_arr = [];
          let rating_arr = [];

          //Get Models
          $("#modelscheckboxes input[type=checkbox]:checked").each(function()
          {
              models_arr.push($(this).data("class"));
          })

          //Get Price
          let pricefrom = $("#sec_fil_pricefrom").val();
          let priceto = $("#sec_fil_priceto").val();

          if(pricefrom != "")
          {
            price_arr.push(pricefrom);
          }
          if(priceto != "")
          {
            price_arr.push(priceto);
          }
         
          //Get Rating
          $("#ratingcheckboxes input[type=checkbox]:checked").each(function()
          {
            rating_arr.push($(this).attr("ID"));
          })


          if(models_arr.length == 0 && price_arr.length == 0 && rating_arr.length == 0 )
          {
            $("#prodsshow #prodsshow_row").html('<div class="w-100 mt-5 fs-3 text-center">لا توجد نتائج</div>');
            self.allshown = "true";
            self.elementsgot == false;
            self.last_filter_ele = 0;
            self.lastprodid = 0;
          }
          else
          {
              self.models_arr = models_arr;
              self.price_arr = price_arr;
              self.rating_arr = rating_arr;
              self.rating_arr = rating_arr;
              self.last_filter_ele= self.last_filter_ele;
              let psearchvalue = self.searchvalue;

              axios.post("/filtersearchdata/", {models_arr, price_arr, rating_arr, psearchvalue})
              .then(function(response)
              {
                if(response.data == "")
                {
                  $("#prodsshow #prodsshow_row").html('<div class="w-100 mt-5 fs-3 text-center">لا توجد نتائج</div>');
                  self.allshown = "true";
                }
                else
                {
                  if(response.data.length == 2)
                  {
                    self.elementsgot = true;
                    self.allshown = "true";
                  }
                  else
                  {
                    self.elementsgot = false;
                    self.allshown = "false";
                  }

                  self.last_filter_ele = response.data[0][response.data[0].length - 1].id; 

                  for(let i=0; i<response.data[0].length; i++)
                  {
                      let ele = response.data[0][i];

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

                      if(prod_rel_price == prod_dis_price)
                      {
                          $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                      }
                      else
                      {
                          $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                      }
                  }
                }
              })
              .catch(function(error)
              {

              });
          }
        });

      ////////////////// Filter Show More ////////////////////
      /////////////////////////////////////////////////////////
      $(document).on("click", "#filterviewsection", function()
      {
        //Determmine If show more in Normal View Section Or Filtered View Section
        //check if All Elements Has been Got

            let psearchvalue = self.searchvalue;
            let last_filter_ele= self.last_filter_ele;
            let price_arr = self.price_arr;
            let models_arr = self.models_arr;
            let rating_arr = self.rating_arr;
            
            axios.post("/filtersearchdata/", {psearchvalue, last_filter_ele, price_arr, models_arr, rating_arr})
            .then(function(response)
            {
                if(response.data.length == 2)
                {
                  self.elementsgot = true;
                  self.allshown = "true";
                }

                self.last_filter_ele = response.data[0][response.data[0].length - 1].id;

                for(let i=0; i<response.data[0].length; i++)
                {
                    let ele = response.data[0][i];

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

                    if(prod_rel_price == prod_dis_price)
                    {
                        $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                    }
                    else
                    {
                        $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                    }
                }

            })
            .catch(function(error)
            {

            });
 
      });
      ////////////////// Abolish Filter And Back To Normal ////////////////////
      /////////////////////////////////////////////////////////
      $(document).on("click", "#filter_abolish", function()
      {
        self.lastprodid = 0;
        self.last_filter_ele = 0;
        self.elementsgot = false;

        $("#prodsshow #prodsshow_row").html("");
        $("#filterviewsection").attr("ID", "normalviewsection");

        self.allshown = "true";
        

        axios.get("/getsearchproducts/"+self.searchvalue+"__"+self.lastprodid)
        .then(function(response)
        {
          self.lastprodid = response.data[0][response.data[0].length - 1].id;

          if(response.data == "")
          {
            $("#prodsshow #prodsshow_row").html('<div class="w-100 mt-5 fs-3 text-center">لا توجد نتائج</div>');
            self.allshown = "true";
          }

          else if(response.data.length == 2)
          {
            self.elementsgot = true;
            self.allshown = "true";

          }
          else if(response.data.length == 1)
          {
            self.allshown = "false";
          }

            for(let i=0; i<response.data[0].length; i++)
            {
                let ele = response.data[0][i];

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

                if(prod_rel_price == prod_dis_price)
                {
                    $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                }
                else
                {
                    $("#prodsshow #prodsshow_row").append('<div class="col-lg-4 col-sm-6 col-12 prod" id="'+prod_id+'"><div class="bbb_deals"><div class="bbb_deals_slider_container"><div class="bbb_deals_item"><div class="img-wrap"><img src="'+prod_img+'"></div><div class="bbb_deals_content"><div class="bbb_deals_info_line mt-1 mb-2"><div class="bbb_deals_item_name w-100 text-center">'+prod_name+'</div></div><div class="bbb_deals_info_line"><div class="bbb_deals_item_price_a"><strike class="d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_rel_price+'<p></strike></div><div class="bbb_deals_item_price d-flex flex-row-reverse"><p class="mr-1">'+self.currency+'</p><p>'+prod_dis_price+'</p></div></div><div class="available"><div class="available_line"><div class="available_title">الكمية المتاحة: <span>'+prod_qty+'</span></div><div class="sold_stars">'+rating_stars+'</div></div><div class="available_bar"><span style="width:17%"></span></div></div></div></div></div></div></div>');
                }
            }
            
        })
        .catch(function(error)
        {

        });
        
      });

    },
    mounted:function()
    {
      let self = this;
      document.getElementById("spinner_cont").style.display="block";

      //Go To Similar Product
      $(document).on("click", ".prod", function(event)
      {
          let id = event.currentTarget.getAttribute("id");
          self.goToProd(id);
      });
   
      //Get Filter Data
      axios.get("/getmanus_search/" + self.searchvalue)
      .then(function(response)
      { 
        self.prodsdata = response.data;
        document.getElementById("spinner_cont").style.display="none";
      })
      .catch(function(error)
      {

      })

      //Show Hide Filter
      $("#resfilter").on("click", function()
      {
        $(".section_filter_inner").slideToggle();
      })


    },
    methods:
    {
        goToProd(id)
        {
            this.$router.push({path: "/product/"+id});
        },
        rerendermethod()
        {

        },
    },
}
</script>
<style scoped>
/* SideBar */
.section_filter
{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.section_filter .container-fluid
{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.section_filter .resfilter
{
  cursor: pointer;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
}
.section_filter_inner
{
  width: 100%;
  margin-top: 20px;
  display: none;
}
.section_filter_inner > div
{
  direction: rtl;
  text-align: right;
}
.section_filter_inner h6
{
  background: #eee;
}
.section_filter_inner #ratingcheckboxes label
{
  margin-right: 20px;
}
.section_filter_inner #modelscheckboxes label
{
  margin-right: 20px;
}

.prodfilter
{
    position: absolute;
    width: 60px;
    height: 100%;
    background: #282846;
    transition: 0.5s ease-in-out;
    overflow: hidden;
    z-index: 10;
    margin-top: 20px;
}
.prodfilter h6
{
    background: #dcdde1;
    color: #fff;
    width: 100%;
    padding-top:7px;
    padding-bottom:7px;
    text-align: center;
    color: #000;
}
.prodfilter *
{
    direction: rtl;
}
.prodfilter .form-control
{
   font-size: 16px !important;
}
.prodfilter:hover
{
    width: 300px;
}
.prodfilter .main_list
{
    position: absolute;
    top: 0;
    right: 0;
    padding-right: 0;
    width: 100%;
}

.prodfilter .main_list li
{
    position: relative;
    width: 100%;
    list-style-type: none;
}
.prodfilter .main_list li:not(:first-child)
{
    margin-top: 7px;
}

.prodfilter .main_list li a
{
    position: relative;
    width: 100%;
    display: flex;
    text-decoration: none;
    color: #000;
}
.prodfilter .main_list li a .icon
{
    min-width: 60px;
    max-width: 60px;
    height: 100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    text-align: center;
    color:#fed049;
    padding-top: 10px;
    cursor: pointer;
}
.prodfilter .main_list li a .icon .fas
{
    font-size: 24px;
}
.prodfilter .main_list li a > div
{
    position: relative;
    padding: 0 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    color: #000;
    width: 100%;
    padding-top:10px;
    padding-bottom:10px;
}
.prodfilter .main_list li a > div span
{
    direction: rtl;
    text-align: right;
}
.prodfilter .main_list li .model > div span label
{
    margin-right: 20px;
}
.prodfilter .main_list li .rating_sidebar > div span label
{
    margin-right: 20px;
}
.prodsshow
{
    padding-top: 0px;
}
.prodsshow .productsspread
{
    margin-top: 0px;
}

</style>