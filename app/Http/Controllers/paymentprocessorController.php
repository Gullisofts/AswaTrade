<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Member;
use App\Models\Setting;
use Auth;
use Str;

class paymentprocessorController extends Controller
{
    public function checkout(Request $request)
    {
        //Check If User Complete All Address and Phone Number
        $address1= Auth::guard('member')->user()->address1;
        $address2= Auth::guard('member')->user()->address2;
        $governorate= Auth::guard('member')->user()->governorate;
        $city= Auth::guard('member')->user()->city;
        $zip= Auth::guard('member')->user()->zip;
        $phone = Auth::guard('member')->user()->phone;

        $chk_data = [$address1, $address2, $governorate, $city, $zip, $phone];

        if(in_array("", $chk_data) || in_array(null, $chk_data, true))
        {
            return "profile incomplete";
        }
        else
        {
        
            $qty = $request->cartreadyf;
            $cost = $request->totalsumf;
            $paymentmethod = $request->paymentmethod;

            //Errors Variables
            $qty_errors = [];
            $qty_zero = FALSE;
            $costincorrect = FALSE;
            
            //Check Quantities
            foreach($qty as $key => $value)
            {
                $prodqty = Product::where("id", $key)->first()->qty;

                if($prodqty < $value)
                {
                    array_push($qty_errors, $key);
                    array_push($qty_errors, $prodqty);
                }
                if($value <= 0)
                {
                    $qty_zero = TRUE;
                }
            }
            
            //Check Cost in light of quantities
            $costs = [];
            foreach($qty as $key => $value)
            {
                $prodprice = Product::where("id", $key)->first()->price;
                $proddiscount = Product::where("id", $key)->first()->discount;

                $el_final_price = $prodprice - (($proddiscount / 100) * $prodprice);

                $el_cost = $el_final_price * $value;

                array_push($costs, $el_cost);
            }

            //Get Fees Amount
            $member_id = Auth::guard("member")->user()->id;
            $address = Member::where("id", $member_id)->select(["phone", "address1", "address2", "governorate", "city"])->first();

            $regions = Setting::where("id", 1)->first()->regions;

            if($regions != "")
            {
                $regions = explode("_xXnextarrayelxX_", $regions);
                $regions = array_filter($regions);
                $fees = $regions[(array_search($address->governorate, $regions)) + 1];
            }
            else
            {
                $fees = 0;
            }

            $correctcost = array_sum($costs) + intval($fees);

            if($correctcost != $cost)
            {
                $costincorrect = TRUE;
            }

            //Check Errors If Found
            if(count($qty_errors) <= 0)
            {
                if($qty_zero == FALSE)
                {
                    if($costincorrect == FALSE)
                    {
                        //Check Payment Method
                        if($paymentmethod == "paymentondeliver" || $paymentmethod == "electronicpayment")
                        {
                            if($paymentmethod == "paymentondeliver")
                            {
                                //Add Shippment To Database
                                //# First: Prepare Products and Quantities Line
                                $prods = "";
                                $arr_keys = array_keys($qty);
                                $last_key = end($arr_keys);

                                foreach($qty as $key => $value)
                                {
                                    if($key == $last_key)
                                    {
                                        $prods.=$key."qty_xX913172xX_".$value;
                                    }
                                    else
                                    {
                                        $prods.=$key."qty_xX913172xX_".$value."+";
                                    }

                                    //Deduct Quantities From Database
                                    $proddata = Product::where("id", $key)->first();

                                    $realqty = $proddata->qty;

                                    $newqty = $realqty - $value;

                                    Product::where("id", $key)->update(
                                    [
                                        "qty" => $newqty,
                                    ]);
                                }

                                $prods_ids = array_keys($qty);

                                //Increase Sales Value
                                for($i=0; $i<count($prods_ids); $i++)
                                {
                                    $proddata = Product::where("id", $prods_ids[$i])->first();

                                    $prodsales = $proddata->sales;

                                    $newsales = $prodsales + 1;

                                    Product::where("id", $prods_ids[$i])->update(
                                    [
                                        "sales" => $newsales,
                                    ]); 
                                }

                                Shipment::create(
                                [
                                    "member_id" => Auth::guard("member")->user()->id,
                                    "shipmentno" => rand(11111, 99999).rand(111111111111111, 999999999999999),
                                    "transaction_id" => "pod",
                                    "content" => $prods,
                                    "cost" => $cost,
                                    "status" => "pending",

                                ]);

                                return "payment on deliver";
                            }
                            else
                            { 
                                function request($correctcost)
                                {
                                    $correctcost = number_format((float)$correctcost, 2, '.', '');
                                    
                                    $url = "https://test.oppwa.com/v1/checkouts";
                        
                                    $data = "entityId=8a8294174b7ecb28014b9699220015ca"."&amount=".$correctcost."&currency=EUR"."&paymentType=DB";
                                
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $responseData = curl_exec($ch);
                                    if(curl_errno($ch)) {
                                        return curl_error($ch);
                                    }
                                    curl_close($ch);
                                    return $responseData;
                                }
                        
                                $responseData = request($correctcost);

                                return ["success", json_decode($responseData, TRUE)["id"]];
                            }
                        }
                        else
                        {
                            return "unfoundpaymentmethod";
                        }

                    }
                    else
                    {
                        return "cost";
                    }
                }
                else
                {
                    return "zero";
                }
            }
            else
            {
                return ["qty", $qty_errors];
            }

        }

    }
    public function checkout_view($checkout_id)
    {
        return view("checkout", compact("checkout_id"));
    }
    public function afterpayment(Request $request)
    {
        $resourcePath = "v1/checkouts/".$request->wantedcode."/payment";
        $cart = $request->cartreadyf2;
        $cost = $request->costreadyf2;
        $url = "https://test.oppwa.com/";
        $url .= $resourcePath;
        $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $finalresponse = json_decode($responseData, TRUE);
        if(array_key_exists("id", $finalresponse))
        {
            $check_rep_array = explode(" ", $finalresponse["result"]["description"]);

            if(in_array("successfully", $check_rep_array))
            {
                //Add Shippment To Database
                //# First: Prepare Products and Quantities Line
                $prods = "";
                
                $arr_keys = array_keys($cart);
                $last_key = end($arr_keys);

                foreach($cart as $key => $value)
                {
                    if($key == $last_key)
                    {
                        $prods.=$key."qty_xX913172xX_".$value;
                    }
                    else
                    {
                        $prods.=$key."qty_xX913172xX_".$value."+";
                    }

                    //Deduct Quantities From Database
                    $proddata = Product::where("id", $key)->first();

                    $realqty = $proddata->qty;

                    $newqty = $realqty - $value;

                    Product::where("id", $key)->update(
                    [
                        "qty" => $newqty,
                    ]);
                }

                $prods_ids = $arr_keys;
                //Increase Sales Value
                for($i=0; $i<count($prods_ids); $i++)
                {
                    $proddata = Product::where("id", $prods_ids[$i])->first();

                    $prodsales = $proddata->sales;

                    $newsales = $prodsales + 1;

                    Product::where("id", $prods_ids[$i])->update(
                    [
                        "sales" => $newsales,
                    ]); 
                }

                Shipment::create(
                [
                    "member_id" => Auth::guard("member")->user()->id,
                    "shipmentno" => rand(11111, 99999).rand(111111111111111, 999999999999999),
                    "transaction_id" => $finalresponse["id"],
                    "content" => $prods,
                    "cost" => $cost,
                    "status" => "pending",
                ]);

                return "success";
            }
            else
            {
                return "error";
            }

        }
        else
        {
            return "error";
        }

    }

}
