<template>
    <div>
        <Myheader></Myheader>
            <div class="container-fluid cart-main mt-5">

				<div v-if="cart.length == 0" class="img-thumbnail empty-main">
					<div class="cart-empty">
						<p class="empty-text">السلة فارغة</p>
						<i class="fas fa-shopping-cart"></i>
					</div>
				</div>

				<div v-if="cart.length > 0" class="main-cart-list">
					<div class="cart-list w-100">

						<ul class="list-group cart-items p-0">
							<template v-for="(value, name) in cartdata">

								<li class="list-group-item img-thumbnail mt-2" :key="name">

									<div class="form-group inner-cart-div row mb-0">

										<div class="form-group text-center d-flex flex-column justify-content-center align-items-center col-lg-6 col-md-5 col-sm-12 col-12">
											<label>المنتج</label>
											<input class="form-control" type="text" v-bind:value="value.productname"  readonly>
										</div>

										<div class="form-group text-center d-flex flex-column justify-content-center align-items-center col-lg-2 col-md-2 col-sm-4 col-6">
											<label>الكمية</label>
											<input @change="changeprice($event)" v-bind:id="'qty-item-id'+value.id" class="form-control cart-prod-qty" type="number" min="1" value="1">
										</div>

										<div class="form-group fnprice text-center d-flex flex-column justify-content-center align-items-center col-lg-2 col-md-3 col-sm-4 col-6">
											<label>السعر النهائي</label>
											<input class="fnprice-hidden" type="hidden" v-bind:value="value.price - (value.price * parseFloat('0.'+value.discount.toString().padStart(2, '0')))">
											<span v-bind:id="'price-id'+value.id" v-bind:class="'form-control cart-prod-fprice el-fprice'+value.id" type="number">{{value.price - (value.price * parseFloat('0.'+value.discount.toString().padStart(2, '0')))}}</span>
										</div>

										<div class="form-group cart-del text-center d-flex flex-row justify-content-center align-items-center col-lg-1 col-md-1 col-sm-2 col-6">
											<button v-bind:data-class="value.id" class="btn btn-danger cart-remove-item">
												<i class="fas fa-trash-alt text-light"></i>
											</button>
										</div>

										<div class="form-group cart-del text-center d-flex flex-row justify-content-center align-items-center col-lg-1 col-md-1 col-sm-2 col-6">
											<button v-bind:data-class="value.id" class="btn btn-primary cart-see-item">
												<i class="fas fa-eye"></i>
											</button>
										</div>
										<div v-bind:id="'cart-qty-error-'+value.id" class="form-group text-right w-100 col-12 cart-item-qty-error">
											<span class="alert alert-danger text-right"></span>
										</div>

									</div>

								</li>

							</template>

						</ul>

						<div class="cart-general-info mt-4 row img-thumbnail">

							<div class="form-group col-md-4 col-sm-12">
								<h6>تكلفة المنتجات</h6>
								<input type="number" class="form-control" v-bind:value="parseFloat(totalprice).toFixed(2)" readonly>
							</div>

							<div class="form-group col-md-4 col-sm-12">
								<h6>مصاريف الشحن</h6>
								<input type="number" class="form-control" v-bind:value="shippmentfees" readonly>
								
							</div>

							<div class="form-group col-md-4 col-sm-12">
								<h6>إجمالي التكلفة</h6>
								<div class="d-flex flex-row-reverse justify-content-center">
									<label class="text-center m-0 mr-1" for="totalsum">ج.م</label>
									<label class="text-center m-0 border p-2" id="totalsum text-center">{{parseFloat(shippmentfees + totalprice).toFixed(2)}}</label>
								</div>
							</div>

						</div>

						<div class="addressandinfo mt-4 img-thumbnail pt-2 pb-2">
							<h6 class="font-weight-bold">بيانات العنوان والتواصل</h6>
							<p class="alert alert-warning">سيتم تسليم الشحنة إلى هذا العنوان لذا في حال كنت تريد تسليم الشحنة إلى عنوان آخر يرجى تعديل البيانات من ملفك الشخصي</p>
							<ul class="pr-0 list-group mt-2">
								<li class="list-group-item">
									<span class="font-weight-bold">رقم الهاتف:</span>&nbsp;<span>{{addressinfo.phone}}</span>
								</li>
								<li class="list-group-item">
									<span class="font-weight-bold">العنوان الأول:</span>&nbsp;<span>{{addressinfo.address1}}</span>
								</li>
								<li class="list-group-item">
									<span class="font-weight-bold">العنوان الثاني:</span>&nbsp;<span>{{addressinfo.address2}}</span>
								</li>
								<li class="list-group-item">
									<span class="font-weight-bold">المحافظة:</span>&nbsp;<span>{{addressinfo.governorate}}</span>
								</li>
								<li class="list-group-item">
									<span class="font-weight-bold">المركز:</span>&nbsp;<span>{{addressinfo.city}}</span>
								</li>
							</ul>
						</div>

						<div class="cart-payment-method mt-4 img-thumbnail">

							<div class="form-group">
								<h6>أختر وسيلة الدفع</h6>
								<select class="form-control" id="paymenmtmethod">
									<option value="paymentondeliver">الدفع عند الاستلام</option>
									<option value="electronicpayment">الدفع الإليكتروني</option>
								</select>
							</div>

						</div>

						<div class="cart-next row">
							<div class="form-group text-center col-md-6 col-12">
								<button class="btn btn-danger btn-lg completeorder mt-4" id="tocheckout">
									التالي
								</button>
							</div>
							<div class="form-group text-center col-md-6 col-12">
								<button class="btn btn-danger mr-5 btn-lg emptybasket mt-4" id="emptybasket">
									تفريغ السلة
								</button>
							</div>

						</div>


					</div>
				</div>
			<Popmessage></Popmessage>
			</div>
        <Myfooter></Myfooter>
    </div>
</template>
<script>
import Myheader from './../components/Myheader.vue';
import Myfooter from './../components/Myfooter.vue';
import axios from "axios";
import Popmessage from "./../components/Popmessage";

export default {

    name: "Basket",
    components:{
        Myheader,
        Myfooter,
		axios,
		Popmessage,
    },
	data() {

		return {

			cart: JSON.parse(localStorage.getItem("cartcontent")),
			cartdata: "",
			cartready: {},
			totalprice: 0,
			shippmentfees: 0,
			id_price: [],
			addressinfo:
			{
				phone:"",
				address1: "",
				address2: "",
				governorate:"",
				city:"",
			},
		}
	},
	mounted:function()
	{
		document.getElementById("spinner_cont").style.display="block";
		//#### Normal Access To Basket ####//
		let self = this;
		let cardprodsids = self.cart;

		//Reload Page If Not Reloaded
		let chkUrl = window.location.href;
		chkUrl = chkUrl.split("/");
		if(chkUrl[chkUrl.length - 1] == "basket")
		{
			if(localStorage.getItem('reloaded'))
			{
				localStorage.removeItem('reloaded');
			} 
			else
			{
				localStorage.setItem('reloaded', '1');
				location.reload();
			}
		}

		//Get Cart Prods Details
		axios.post("/cartprods", {cardprodsids})
		.then(function(response)
		{
			self.cartdata = response.data;

			let temptotalprice = 0;

			if(response.data.length > 1)
			{
				for(let i=0; i<response.data.length; i++)
				{
					temptotalprice += response.data[i].price - (response.data[i].price * parseFloat('0.'+response.data[i].discount.toString().padStart(2, '0')));

					self.id_price.push(response.data[i].id);

					self.id_price.push(response.data[i].price - (response.data[i].price * parseFloat('0.'+response.data[i].discount.toString().padStart(2, '0'))));
				}
			}
			else
			{
				temptotalprice += response.data[0].price - (response.data[0].price * parseFloat('0.'+response.data[0].discount.toString().padStart(2, '0')));

				self.id_price.push(response.data[0].id);

				self.id_price.push(response.data[0].price - (response.data[0].price * parseFloat('0.'+response.data[0].discount.toString().padStart(2, '0'))));
			}
			self.totalprice = temptotalprice;

			document.getElementById("spinner_cont").style.display="none";
		})
		.catch(function(error)
		{

		});

		//Get Member Address and Fees
		axios.get("/getmemberdata")
		.then(function(response)
		{
			self.shippmentfees = response.data[0];
			self.addressinfo.phone=response.data[1].phone;
			self.addressinfo.address1=response.data[1].address1;
			self.addressinfo.address2=response.data[1].address2;
			self.addressinfo.governorate=response.data[1].governorate;
			self.addressinfo.city=response.data[1].city;
		})
		.catch(function(error)
		{

		});	


		//Make Cart Ready
		for(let i=0; i<cardprodsids.length; i++)
		{
			self.cartready[cardprodsids[i]] = 1;
		}

		//Go To Item
		$(document).on("click", ".cart-see-item", function(e)
		{
			e.stopImmediatePropagation();
			e.stopPropagation();
			self.$router.push("/product/"+$(this).data("class"));
		});

		//#### Check If Redirected From Payment ####//
		let splitedhref = window.location.href.split("resourcePath=");
		if(splitedhref.length > 1)
		{
			$(".main-cart-list").html('');
			let readyurl = decodeURIComponent(window.location.href).split("resourcePath=/v1/checkouts/");
			let readyurl2 = readyurl[1].split("/payment");
			let wantedcode = readyurl2[0];
			let cartreadyf2 = self.$store.state.tempcart;
			let costreadyf2 = self.$store.state.tempcost;

			axios.post("/after_payment/", {wantedcode, costreadyf2, cartreadyf2})
			.then(function(response)
			{

				if(response.data == "error")
				{
					Notiflix.Notify.Failure('العملية فاشلة! يرجى مراجعة البيانات المدخلة');
				}
				else if(response.data == "success")
				{
					window.history.pushState({}, null, window.location.origin);

					$(".main-cart-list").html('<div class="p-3 w-75 d-flex flex-column justify-content-center align-items-center img-thumbnail"><i style="color: #2ecc71;" class="fas fa-check-square fa-5x"></i><p class="mt-3 mb-0" style="font-size: 22px;">تمت العملية بنجاح في انتظار المراجعة والتأكيد.</p></div>');

					self.$store.state.cartcontent = [];
					$("#cartno-manually").data("count", 0);

					let itemstd = JSON.parse(localStorage.getItem("cartcontent"));
					itemstd = [];
					localStorage.setItem("cartcontent", JSON.stringify(itemstd));
					self.$store.state.tempcost = "";
					self.$store.state.tempcart = "";
				}
				
			})
			.catch(function(error)
			{

			});
		}

		//Going To Check Out
		$(document).on("click", "#tocheckout", function(e)
		{
			let cartreadyf = self.cartready;
			let totalsumf = eval(self.totalprice) + eval(self.shippmentfees);
			let paymentmethod = $("#paymenmtmethod").val();

			$(".cart-item-qty-error").fadeOut(1);

			document.getElementById("spinner_cont").style.display="block";
			e.stopImmediatePropagation();
			e.stopPropagation();

			axios.post("/checkout_process", {cartreadyf, totalsumf, paymentmethod})
			.then(function(response)
			{	

				
				if(response.data[0] == "success")
				{
					self.$store.state.tempcost = totalsumf;
					self.$store.state.tempcart = cartreadyf;


					let scriptt = document.createElement('script');
					scriptt.async = true;
					scriptt.src = 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId='+response.data[1];
   					document.head.appendChild(scriptt);
					$(".cart-main").css({'direction': 'ltr', 'text-align': 'left'});

					$(".main-cart-list").html('<form action="'+window.location.href+'" class="paymentWidgets" data-brands="VISA MASTER"></form>');
				}
				else if(response.data == "cost")
				{

					Notiflix.Notify.Failure('حدث خطأ ما يرجى مراجعة البيانات المدخلة!');
				}
				else if(response.data == "zero")
				{

					Notiflix.Notify.Failure('حدث خطأ ما يرجى مراجعة البيانات المدخلة!');
				}
				else if(response.data[0] == "qty")
				{


					for(let i=0; i<response.data[1].length; i=i+2)
					{
						$("#cart-qty-error-"+response.data[1][i]).fadeIn(1);
						$("#cart-qty-error-"+response.data[1][i]+" span").html(`الكمية المتوفرة ${response.data[1][i+1]} فقط.`);
					}
				}
				else if(response.data == "profile incomplete")
				{


					Notiflix.Notify.Failure('يرجى إكمال تفاصيل عنوانك ورقم الهاتف بالكامل في ملفك الشخصي قبل المتابعة!');
				}
				else if(response.data == "unfoundpaymentmethod")
				{

					Notiflix.Notify.Failure('يرجى اختيار طريقة الدفع');
				}
				else if(response.data == "payment on deliver")
				{
					/*
					self.$store.state.cartcontent = [];
					$("#cartno-manually").data("count", 0);

					let itemst = JSON.parse(localStorage.getItem("cartcontent"));
					itemst = [];
					localStorage.setItem("cartcontent", JSON.stringify(itemst));

					self.$store.state.tempcost = "";
					self.$store.state.tempcart = "";
					

					$("#cartno-manually").data("count", 0);
					self.$store.state.tempcost = "";
					self.$store.state.tempcart = "";
					self.cartready = '',
					localStorage.setItem("cartcontent", '');
					self.$store.state.cartcontent = '';
					*/

					$(".main-cart-list").html('<div class="p-3 w-75 d-flex flex-column justify-content-center align-items-center img-thumbnail"><i style="color: #2ecc71;" class="fas fa-check-square fa-5x"></i><p class="mt-3 mb-0" style="font-size: 22px;">تمت العملية بنجاح في انتظار المراجعة والتأكيد.</p></div>');
					localStorage.cartcontent = '';
					localStorage.setItem("cartcontent", '');
					$("#cartno-manually").data("count", '');
					self.$store.state.cartcontent = '';
				}

				document.getElementById("spinner_cont").style.display="none";
				

			})
			.catch(function(error)
			{

			});
			
		});

		//Remove Item From Cart
		$(document).on("click", ".cart-remove-item", function()
		{

			let itemstr = JSON.parse(localStorage.getItem("cartcontent"));


			$(this).closest("li").remove();
			let neededid2 = $(this).data("class");
			delete self.cartready[neededid2];

			itemstr.splice(itemstr.indexOf(neededid2), 1);

			localStorage.setItem("cartcontent", JSON.stringify(itemstr));

			self.$store.state.cartcontent = itemstr;

			$("#cartno-manually").data("count", itemstr.length);

			let elsr = document.getElementsByClassName("cart-prod-fprice");
			let totalsumr = 0;
			Array.from(elsr).forEach(function (el)
			{
				totalsumr+= eval(el.innerHTML);

			});
			$("#totalsum").val(totalsumr);
			self.totalprice = totalsumr;

			if(itemstr.length ==0)
			{
				self.$router.push("/");
			}
			
		});

		//Empty Basket
		$(document).on("click", "#emptybasket", function()
		{
			localStorage.setItem("cartcontent", '');
			self.$store.state.cartcontent = '';
			self.$router.push("/");
		});

	},
	methods:
	{
		changeprice:function(event)
		{
			let self = this;
			
			let item_id1 = event.target.id;
			let item_id2 = item_id1.split("qty-item-id");
			let item_id = item_id2[1];
			let elecount = event.target.value;
			let priceval = $("#price-id"+item_id).siblings(".fnprice-hidden").val();
			
			let newsum = elecount * priceval;

			$("#price-id"+item_id).text(newsum);

			let neededid1 = event.target.id;
			let neededid2 = neededid1.split("qty-item-id");
			self.cartready[neededid2[1]] = elecount;

			let els = document.getElementsByClassName("cart-prod-fprice");
			let totalsum = 0;
			Array.from(els).forEach(function (el)
			{
				totalsum+= eval(el.innerHTML);

			});
			$("#totalsum").val(totalsum);
			self.totalprice = totalsum;
			
		}
	},
    
}
</script>
<style scoped>
.cart-main
{
	direction: rtl;
	text-align: right;
}
.empty-main
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}
.empty-main .cart-empty
{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
}
.cart-empty p
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	text-align: center;
	margin: 0;
	font-size: 20px;
	padding-top: 10px;
	padding-bottom: 10px;
	font-weight: bold;
}
.cart-empty i
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	text-align: center;
	font-size: 100px;
	padding-top: 10px;
	padding-bottom: 10px;
}
.main-cart-list
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	padding-top: 10px;
	padding-bottom: 10px;
}
.cart-items li
{
	width: 100%;
	min-width: 100%;
	max-width: 100%;
	margin-right: 0px;
    margin-left: 0px;
}
.inner-cart-div
{
	justify-content: center;
	align-items: flex-end;
	text-align: center;
	padding-top: 10px;
	padding-bottom: 10px;	
}
.inner-cart-div input
{
	text-align: center;
}
.inner-cart-div label
{
	width: 100%;
	text-align: center;
}
.cart-del
{
	height: 52px !important;
}
.cart-general-info
{
	justify-content: center;
	align-items: center;
	margin-right: 0px;
    margin-left: 0px;
}
.cart-general-info div
{
	text-align: right;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	padding-top: 20px;
	padding-bottom: 20px;
	margin-bottom: 0;
	width: 100% !important;
	max-width: 100% !important;
}
.cart-general-info div h6
{
	font-weight: bold;
	display: flex;
	flex-direction: row;
	align-items: center;
	padding-left: 0;
}
.cart-general-info div input
{
	width: auto;
	text-align: center;
}
.cart-payment-method div
{
	margin-bottom: 0;
	padding-top: 20px;
	padding-bottom: 20px;
	display: flex;
	flex-direction: column;
}
.cart-payment-method div h6
{
	font-weight: bold;
	display: flex;
	flex-direction: row;
	align-items: center;
	padding-left: 0;
	margin-bottom: 10px;
	width: auto;
}
.cart-paymentvisamaster 
{
	display: none;
	direction: rtl;
}
.cart-paymentvisamaster .paymentoptions
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}
.cart-paymentvisamaster span
{
	cursor: pointer;
	margin-right: 5px;
	margin-left: 5px;
}
.cart-paymentvisamaster span i
{
	font-size: 70px;
}

.cart-paymentvisamaster .mastercard-data
{
	display: none;
}
.cart-paymentvisamaster .visa-data
{
	display: none;
}
.cart-next
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}
.cart-item-qty-error
{
	display: none;
	width: 100%;
}
.cart-item-qty-error span
{
	width: 100%;
}
.paymentWidgets
{
	direction: ltr !important;
	text-align: left !important;
}
.paymentWidgets *
{
	direction: ltr !important;
	text-align: left !important;
}
.cart-prod-fprice
{
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}

</style>
