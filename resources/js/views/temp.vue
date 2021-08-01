<template>
    <div>
        <Myheader></Myheader>
		<template v-if="fakedata=='false'">
			<div class="container-fluid productview_class">
				<div class="card">
					<div class="container-fliud">
						<div class="wrapper row">

							<div class="preview col-lg-6">
								<div class="preview-pic tab-content" id="preview-pic" v-html="img_first_data">
								</div>
								<ul class="preview-thumbnail nav nav-tabs" id="preview-thumbnail" v-html="img_sec_data"></ul>
							</div>

							<div class="details col-lg-6">

								<h3 class="product-title mb-5">{{prod_title}}</h3>

								<div class="prod_reviews_count">
									<h6 class="prod_h6 w-50">المراجعات:</h6>
									<span class="text-align-right w-50">{{reviews_count}}</span>
								</div>

								<div class="prod_reviews_count mt-3">
									<h6 class="prod_h6 w-50">تقييم المنتج:</h6>
									<div class="stars w-50 text-align-right" id="prod_rating_stars" v-html="rating_stars"></div>
								</div>

								<div class="prod_reviews_count mt-3">
									<h6 class="prod_h6 w-50">الكمية المتوفرة:</h6>
									<span class="w-50 text-align-right">{{prod_qty}}</span>
								</div>


								<h6 class="prod_h6 mt-3">وصف المنتج:</h6>
								<p class="product-description mt-2">{{prod_desc}}</p>
								
								<div class="prod_spec mt-3">
									<h6 class="prod_h6">المواصفات:</h6>
									<ul class="mt-2">
										<li v-for="(value, name) in prod_spec" :key="name.id">
											<span class="prod_spec_prop">{{value[0]}}</span>
											<span style="font-weight: bold;">:</span>
											<span class="prod_spec_prop_desc">{{value[0]}}</span>
										</li>
									</ul>
								</div>

								<h6 v-if="prod_dis_price == 0" class="prod_h6 mt-2">السعر: <span>{{prod_rel_price + ' ' + currency}}</span></h6>
								<h6 v-if="prod_dis_price > 0" class="prod_h6 mt-2">السعر: <span>{{prod_dis_price  + ' ' + currency}}</span>&nbsp;&nbsp;&nbsp;<del>{{prod_rel_price  + ' ' + currency}}</del></h6>

								<div class="action mt-3">
									<button v-if="itemadded==true" @click="addtocart($event)" style="background: #e74c3c;" class="add-to-cart btn btn-default" type="button">تراجع</button>
									<button v-if="itemadded==false" @click="addtocart($event)" style="background: #ff9f1a;" class="add-to-cart btn btn-default" type="button">أضف إلى العربة</button>
								</div>

							</div>
						</div>
					</div>
				</div>
				<hr  v-if="getsimilarstatus == 1" class="hr_high">
        		<Productsslider v-if="getsimilarstatus == 1"></Productsslider>
				<hr class="hr_high">
				<Comments></Comments>
			</div>
			</template>
			<template v-if="fakedata == 'true'">
				<div class="text-center row justify-content-center align-items-center w-100" style="min-height: 400px; font-weight: bold; font-size: 20px">
					!اتبعت رابط خطأ
				</div>
			</template>
        <Myfooter></Myfooter>
    </div>
</template>
<script>
import Myheader from './../components/Myheader.vue';
import Myfooter from './../components/Myfooter.vue';
import Productsslider from '../components/Productsslider.vue';
import Comments from './../components/Comments.vue';
import axios from "axios";

export default {

    name: "Productview",
    components:{
        Myheader,
        Myfooter,
		Productsslider,
		Comments,
		axios,
    },
	data() {

		return {

			prod_id: this.$route.params.productid,
			prod_title:"",
			prod_reviews:"",
			prod_rating:"",
			prod_desc:"",
			prod_spec:"",
			prod_manu:"",
			prod_rel_price:"",
			prod_dis_price:"",
			getsimilarstatus: 0,
			reviews_count: 0,
			prod_qty: 0,
			itemadded: false,
			currency: "",
			fakedata: "notset",
			img_first_data: "",
			img_sec_data: "",
			rating_stars: "",
		}
	},
	created:function()
	{
		
		let self = this;
		let prodid = self.prod_id;
		document.getElementById("spinner_cont").style.display="block";

		//Check Product Validity
		axios.get("/prodvalid/"+prodid)
		.then(function(response)
		{
			if(response.data == "invalid")
			{
				self.fakedata = "true";
			}
			else
			{
				self.fakedata = "false";
			}
		})
		.catch(function(error)
		{

		});




		this.$store.state.currentProd = self.prod_id;

		//Check if there are similars
		axios.get("/getsimilarproducts/"+self.prod_id)
		.then(function(response)
		{
			if(response.data != "NoSimi")
			{
				self.getsimilarstatus = 1;
			}

		})
		.catch(function(error)
		{

		});


		axios.get("/getproductdata/"+self.prod_id)
		.then(function(response)
		{
			self.prod_title = response.data[0];
			self.prod_rating = response.data[4];
			self.prod_desc = response.data[8];
			self.prod_spec = response.data[6];
			self.prod_manu = response.data[1];
			self.prod_qty = response.data[7];

			if(eval(response.data[3]) > 0)
			{
				let discount_math = "0."+response.data[3].toString().padStart(2, '0');
				self.prod_rel_price = response.data[2];
				self.prod_dis_price = response.data[2] - (response.data[2] * parseFloat(discount_math));
			}
			else
			{
				self.prod_rel_price = response.data[2];
			}
			
			let img_first_data = "";
			let img_sec_data = "";
			let rating_stars = "";

			for (const [key, value] of Object.entries(response.data[5]))
			{

				if(key == 0)
				{
					img_first_data += '<div class="tab-pane img-thumbnail active" id="pic-'+(eval(key)+1)+'"><img src="'+value+'"></div>';
					img_sec_data += '<li class="active img-thumbnail mt-2"><a data-target="#pic-'+(eval(key)+1)+'" data-toggle="tab"><img src="'+value+'"></a></li>';
				}
				else
				{
					img_first_data += '<div class="tab-pane img-thumbnail" id="pic-'+(eval(key)+1)+'"><img src="'+value+'"></div>';	
					img_sec_data += '<li class="img-thumbnail mt-2" ><a data-target="#pic-'+(eval(key)+1)+'" data-toggle="tab"><img src="'+value+'"></a></li>';
				}
			}

			for(let i=1; i<=5; i++)
			{
				if(i <= self.prod_rating)
				{
					rating_stars += '<span class="fa fa-star checked"></span>';
				}
				else
				{
					rating_stars += '<span class="fa fa-star"></span>';
				}
			}

			self.img_first_data = img_first_data;
			self.img_sec_data = img_sec_data;
			self.rating_stars = rating_stars;

		})
		.catch(function(error)
		{

		});

		//Reviews Count
		axios.get("/getreviewscount/" + self.prod_id)
		.then(function(response)
		{
			document.getElementById("spinner_cont").style.display="none";
			self.reviews_count = response.data;
		})
		.catch(function(error)
		{

		});

		//Check If Element Added To Cart
        let chk_cart_el2 = JSON.parse(localStorage.getItem("cartcontent"));

        if(chk_cart_el2.indexOf(self.prod_id) == -1)
        {
			self.itemadded = false;
        }
        else
        {
			self.itemadded = true;
        }

	},
	methods:
	{
		//Add Element To Cart
		addtocart:function(event)
		{
			let chk_cart_el = JSON.parse(localStorage.getItem("cartcontent"));

			if(chk_cart_el.indexOf(this.prod_id) == -1)
			{
				event.target.style.background="#e74c3c";
				event.target.innerText="تراجع";

				let cartContent = JSON.parse(localStorage.getItem("cartcontent"));

				cartContent.push(this.prod_id);
				localStorage.setItem("cartcontent", JSON.stringify(cartContent));

				this.$store.state.cartcontent = cartContent;
			}
			else
			{
				event.target.style.background="#ff9f1a";
				event.target.innerText="أضف إلى العربة";

				let cartContent = JSON.parse(localStorage.getItem("cartcontent"));

				cartContent.splice(chk_cart_el.indexOf(this.prod_id), 1,);

				localStorage.setItem("cartcontent", JSON.stringify(cartContent));

				this.$store.state.cartcontent = cartContent;

				$("#cartno-manually").data("count", cartContent.length);
			}
		}
	},
    
}
</script>