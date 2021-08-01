<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\Member;
use Carbon\Carbon;

class shipmentsController extends Controller
{
    public function getshipments($member_id)
    {
        return Shipment::where("member_id", $member_id)->get();
    }

    public function getshipment($shiomentno)
    {
        $shipment = Shipment::where("shipmentno", "like", "%".$shiomentno."%")->first();
        return $shipment;

    }

    public function getshipmentproducts($prods)
    {
        $prods_list = [];
        $prods_ids_list = explode("+", $prods);
        
        for($i=0; $i<count($prods_ids_list); $i++)
        {
            $getidonly = explode("qty_xX913172xX_", $prods_ids_list[$i]);
            array_push($prods_list, [Product::where("id", $getidonly[0])->first()->productname, $getidonly[1]]);
        }

        return $prods_list;
        
    }
    public function getpendingships($lastshipid)
    {
        $lastid = $lastshipid;

        $chk = Shipment::where("status", "pending")->where("id", ">", $lastid)->orderBy("id", "ASC")->get();

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];
            $lastelementchk = $lastelementchk->id;

            //Check If Last Element Has Been Got
            $pendingshipsquad = Shipment::where("status", "pending")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2);
            $pendingshipsquadids = Shipment::where("status", "pending")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2)->pluck("id")->toArray();
            $lastelement = end($pendingshipsquadids);

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $pendingshipsquad, "1"];
            }
            else
            {
                return [$lastelement, $pendingshipsquad];
            }
                
        }
        else
        {
            return "nodata";
        }

    }

    public function getpsinfo($ps)
    {

        //Get Products Array
        $prods = Shipment::where("id", $ps)->first()->content;

        $prodslist = [];
        $prods_ids_list = explode("+", $prods);
        
        for($i=0; $i<count($prods_ids_list); $i++)
        {
            $getidonly = explode("qty_xX913172xX_", $prods_ids_list[$i]);
            $prodcode = Product::where("id", $getidonly[0])->first()->prodcode;
            $name = Product::where("id", $getidonly[0])->first()->productname;

            array_push($prodslist, [$name, $prodcode, $getidonly[1]]);
        }


        //Get Total Sum
        $sum = Shipment::where("id", $ps)->first()->cost;


        //User Info Array
        $memberId = Shipment::where("id", $ps)->first()->member_id;
        $memberinfo = Member::where("id", $memberId)->first();
        $memberinfo = [$memberinfo->address1, $memberinfo->address2, $memberinfo->governorate, $memberinfo->city, $memberinfo->zip, $memberinfo->phone, $memberinfo->id];

        //Get Payment Type
        $ptype = Shipment::where("id", $ps)->first()->transaction_id;

        if($ptype == "pod")
        {
            return [$prodslist, $sum, $memberinfo, "pod"];
        }
        else
        {
            //Get Payment Info Array
            $paymentid = $ptype;

            function request($paymentid) {
                $url = "https://test.oppwa.com/v1/query/".$paymentid;
                $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $responseData = curl_exec($ch);
                if(curl_errno($ch)) {
                    return curl_error($ch);
                }
                curl_close($ch);
                return json_decode($responseData, TRUE);
            }

            $responseData = request($paymentid);
            
            $payment_data = [

                "transaction_id" => $responseData["id"],
                "paymentbrand" => $responseData["paymentBrand"],
                "amount" => $responseData["amount"],
                "currency" => $responseData["currency"],
                "bin" => $responseData["card"]["bin"],
                "last4digits" => $responseData["card"]["last4Digits"],
                "holdernme" => $responseData["card"]["holder"],
                "expire" => $responseData["card"]["expiryMonth"] ."/". $responseData["card"]["expiryYear"],
                "ip" => $responseData["customer"]["ip"],
                "customer_country" => $responseData["customer"]["ipCountry"],
                "timestmp" => Carbon::parse($responseData["timestamp"])->format("d/m/y h:i:s"),

            ];
            
            return [$prodslist, $sum, $memberinfo, $payment_data];

        }

    }

    public function approveship($id)
    {

        Shipment::where("id", $id)->update(
        [
            "status" => "prepare"
        ]);
        
        return "success";

    }

    public function rejectshipment(Request $request)
    {
        //2qty_xX913172xX_1+18qty_xX913172xX_1

        //Deduct Amouts
        $content = Shipment::where("id", $request->elerejid)->first()->content;
        $contentArray = explode("+", $content);

        for($i=0; $i<count($contentArray); $i++)
        {
            $x = explode("qty_xX913172xX_", $contentArray[$i]); 
            $proddata = Product::where("id", $x[0])->first();

            $prodrealqty = $proddata->qty;

            $newqty = $prodrealqty + $x[1];
            
            Product::where("id", $x[0])->update(
            [
                "qty" => $newqty,
            ]);
        }

        //Increase Sales Value
        for($i=0; $i<count($contentArray); $i++)
        {
            $x = explode("qty_xX913172xX_", $contentArray[$i]); 
            $proddata = Product::where("id", $x[0])->first();

            $prodsales = $proddata->sales;

            $newsales = $prodsales - 1;

            Product::where("id", $x[0])->update(
            [
                "sales" => $newsales,
            ]); 
        }

        if($request->cause == NULL)
        {
            Shipment::where("id", $request->elerejid)->update(
            [
                "status" => "rejected",
            ]);
                
            return "success";
        }
        else
        {
            Shipment::where("id", $request->elerejid)->update(
            [
                "status" => "rejected",
                "rejectcause" => $request->cause,
            ]);
                
            return "success";
        }


    }

    public function getprepareships($lastshipid)
    {
        $lastid = $lastshipid;

        $chk = Shipment::where("status", "prepare")->where("id", ">", $lastid)->orderBy("id", "ASC")->get();

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];
            $lastelementchk = $lastelementchk->id;

            //Check If Last Element Has Been Got
            $pendingshipsquad = Shipment::where("status", "prepare")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);
            $pendingshipsquadids = Shipment::where("status", "prepare")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20)->pluck("id")->toArray();
            $lastelement = end($pendingshipsquadids);

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $pendingshipsquad, "1"];
            }
            else
            {
                return [$lastelement, $pendingshipsquad];
            }
                
        }
        else
        {
            return "nodata";
        }
    }
    
    public function getprinfo($ps)
    {
        //Get Products Array
        $prods = Shipment::where("id", $ps)->first()->content;

        $prodslist = [];
        $prods_ids_list = explode("+", $prods);
        
        for($i=0; $i<count($prods_ids_list); $i++)
        {
            $getidonly = explode("qty_xX913172xX_", $prods_ids_list[$i]);
            $prodcode = Product::where("id", $getidonly[0])->first()->prodcode;
            $name = Product::where("id", $getidonly[0])->first()->productname;

            array_push($prodslist, [$name, $prodcode, $getidonly[1]]);
        }


        //Get Total Sum
        $sum = Shipment::where("id", $ps)->first()->cost;


        //User Info Array
        $memberId = Shipment::where("id", $ps)->first()->member_id;
        $memberinfo = Member::where("id", $memberId)->first();
        $memberinfo = [$memberinfo->address1, $memberinfo->address2, $memberinfo->governorate, $memberinfo->city, $memberinfo->zip, $memberinfo->phone, $memberinfo->id];

        //Get Payment Type
        $ptype = Shipment::where("id", $ps)->first()->transaction_id;

        if($ptype == "pod")
        {
            return [$prodslist, $sum, $memberinfo, "pod"];
        }
        else
        {
            
            return [$prodslist, $sum, $memberinfo, "paid"];

        }

    }

    //Transport Shippment
    public function transportshippment($id)
    {
        Shipment::where("id", $id)->update(
        [
                "status" => "road"
        ]);
            
        return "success";
    }

    //Search Shippment By Shippment No
    public function searchshipbyshippno(Request $request)
    {
        $searchvalue = $request->searchval;
        $lastid = $request->lastelement;

        $chk = Shipment::where("shipmentno", "LIKE", "%".$searchvalue."%")->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];

            
            //Check If Last Element Has Been Got
            $shipmentno = Shipment::where("shipmentno", "LIKE", "%".$searchvalue."%")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2)->pluck("shipmentno");
            $shipsidsquad = Shipment::where("shipmentno", "LIKE", "%".$searchvalue."%")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2)->pluck("id");
            $lastelement = $shipsidsquad[count($shipsidsquad) -1] ;

            for($i=0; $i<count($shipmentno); $i++)
            {
                $shipssquad[$shipsidsquad[$i]] = $shipmentno[$i];
            }
            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $shipssquad, "1"];
            }
            else
            {
                return [$lastelement, $shipssquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }


    }
    //Search Shippment By Date
    public function searchshipbydate(Request $request)
    {
        $searchvalue = explode("|_Y_|", $request->searchval);
        $lastid = $request->lastelement;

        //return $searchvalue;

        $chk = Shipment::whereBetween("created_at", [$searchvalue[0], $searchvalue[1]])->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];

            //Check If Last Element Has Been Got
            $shipmentno = Shipment::whereBetween("created_at", [$searchvalue[0], $searchvalue[1]])->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20)->pluck("shipmentno");
            $shipsidsquad = Shipment::whereBetween("created_at", [$searchvalue[0], $searchvalue[1]])->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20)->pluck("id");
            $lastelement = $shipsidsquad[count($shipsidsquad) -1] ;

            for($i=0; $i<count($shipmentno); $i++)
            {
                $shipssquad[$shipsidsquad[$i]] = $shipmentno[$i];
            }
            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $shipssquad, "1"];
            }
            else
            {
                return [$lastelement, $shipssquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }


    }

    public function getpsinfox($ps)
    {
        //Get Products Array
        $prods = Shipment::where("id", $ps)->first()->content;

        $prodslist = [];
        $prods_ids_list = explode("+", $prods);
        
        for($i=0; $i<count($prods_ids_list); $i++)
        {
            $getidonly = explode("qty_xX913172xX_", $prods_ids_list[$i]);
            $prodcode = Product::where("id", $getidonly[0])->first()->prodcode;
            $name = Product::where("id", $getidonly[0])->first()->productname;

            array_push($prodslist, [$name, $prodcode, $getidonly[1]]);
        }

        //Get Status & Shippno
        $shipno = Shipment::where("id", $ps)->first()->shipmentno;
        $status = Shipment::where("id", $ps)->first()->status;
        
        switch ($status) {
            case "pending":
                $status = "تحت المراجعة";
                break;

            case "prepare":
                $status = "قيد التجهيز";
                break;

            case "road":
                $status = "قيد التوصيل";
                break;

            case "delivered":
                $status = "تم التسليم";
                break;

            case "rejected":
                $status = "مرفوضة";
                break;
          }

        $shipno_status = [$shipno, $status];

        //Get Total Sum
        $sum = Shipment::where("id", $ps)->first()->cost;


        //User Info Array
        $memberId = Shipment::where("id", $ps)->first()->member_id;
        $memberinfo = Member::where("id", $memberId)->first();
        $memberinfo = [$memberinfo->address1, $memberinfo->address2, $memberinfo->governorate, $memberinfo->city, $memberinfo->zip, $memberinfo->phone, $memberinfo->id];

        //Get Payment Type
        $ptype = Shipment::where("id", $ps)->first()->transaction_id;

        if($ptype == "pod")
        {
            return [$prodslist, $shipno_status, $sum, $memberinfo, "pod"];
        }
        else
        {
            //Get Payment Info Array
            $paymentid = $ptype;

            function request($paymentid) {
                $url = "https://test.oppwa.com/v1/query/".$paymentid;
                $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $responseData = curl_exec($ch);
                if(curl_errno($ch)) {
                    return curl_error($ch);
                }
                curl_close($ch);
                return json_decode($responseData, TRUE);
            }

            $responseData = request($paymentid);
            
            $payment_data = [

                "transaction_id" => $responseData["id"],
                "paymentbrand" => $responseData["paymentBrand"],
                "amount" => $responseData["amount"],
                "currency" => $responseData["currency"],
                "bin" => $responseData["card"]["bin"],
                "last4digits" => $responseData["card"]["last4Digits"],
                "holdernme" => $responseData["card"]["holder"],
                "expire" => $responseData["card"]["expiryMonth"] ."/". $responseData["card"]["expiryYear"],
                "ip" => $responseData["customer"]["ip"],
                "customer_country" => $responseData["customer"]["ipCountry"],
                "timestmp" => Carbon::parse($responseData["timestamp"])->format("d/m/y h:i:s"),

            ];
            
            return [$prodslist, $shipno_status, $sum, $memberinfo, $payment_data];

        }

    }

    //Change Shippment Status
    public function changeshipmentstatus($idval)
    {
        $id = explode("__", $idval)[0];
        $status = explode("__", $idval)[1];
        
        //Deduct Amounts If Rejected
        if($status == "rejected")
        {
            $content = Shipment::where("id", $id)->first()->content;
            $contentArray = explode("+", $content);
    
            for($i=0; $i<count($contentArray); $i++)
            {
                $x = explode("qty_xX913172xX_", $contentArray[$i]); 
                $proddata = Product::where("id", $x[0])->first();

                $prodrealqty = $proddata->qty;
    
                $newqty = $prodrealqty + $x[1];
    
                Product::where("id", $x[0])->update(
                [
                    "qty" => $newqty,
                ]);
            }

            //Increase Sales Value
            for($i=0; $i<count($contentArray); $i++)
            {
                $x = explode("qty_xX913172xX_", $contentArray[$i]); 
                $proddata = Product::where("id", $x[0])->first();

                $prodsales = $proddata->sales;

                $newsales = $prodsales - 1;

                Product::where("id", $x[0])->update(
                [
                    "sales" => $newsales,
                ]); 
            }
        }

        //Change Status
        Shipment::where("id", $id)->update(
        [
            "status" => $status,
        ]);

        return "success";
    }

    //########## CHARTS ##########//
    //Day Charts
    public function daycharts()
    {
        
        //First Sales Volume
        $day_sales_volume = [];
        for($i=0; $i<24; $i++)
        {
            $num = str_pad($i, 2, "0", STR_PAD_LEFT);
            $num2 = str_pad($i+1, 2, "0", STR_PAD_LEFT);

            $data = Shipment::where("updated_at", ">=", Carbon::today())
            ->whereTime("updated_at", ">=", $num.":00:00")
            ->whereTime("updated_at", "<", $num2.":00:00")->where("status", "!=", "rejected")->pluck("cost")->toArray();

            
            if(!empty($data))
            {
                array_push($day_sales_volume, floor(array_sum($data)));
            }
            else
            {
                array_push($day_sales_volume, 0);
            }


        }

        //Second Shippments (Sales)
        $day_ships = [];
   
        $day_sales_made = Shipment::where("updated_at", ">=", Carbon::today())->get()->count();
        $day_sales_approved = Shipment::where("updated_at", ">=", Carbon::today())->where("status", "!=", "pending")->where("status", "!=", "rejected")->get()->count();
        $day_sales_rejected = Shipment::where("updated_at", ">=", Carbon::today())->where("status", "rejected")->get()->count();

        if($day_sales_approved > 0)
        {
            $approved_percentage = ($day_sales_approved / $day_sales_made) * 100;
        }
        else
        {
            $approved_percentage = 0;
        }

        if($day_sales_rejected > 0)
        {
            $rejected_percentage = ($day_sales_rejected / $day_sales_made) * 100;
        }
        else
        {
            $rejected_percentage = 0;
        }

        if($day_sales_made > 0)
        {
            $pending_percentage = 100 - ($approved_percentage + $rejected_percentage);
        }
        else
        {
            $pending_percentage = 0;
        }

        if($pending_percentage == 0 && $rejected_percentage == 0 && $approved_percentage == 0)
        {
            $day_ships = 0;
        }
        else
        {
            array_push($day_ships, round($approved_percentage, 2));
            array_push($day_ships, round($rejected_percentage, 2));
            array_push($day_ships, round($pending_percentage, 2));
        }


        //Third Members Volume
        $day_members_volume = [];
        for($i=0; $i<24; $i++)
        {
            $num = str_pad($i, 2, "0", STR_PAD_LEFT);
            $num2 = str_pad($i+1, 2, "0", STR_PAD_LEFT);

            $data = Member::where("updated_at", ">=", Carbon::today())
            ->whereTime("updated_at", ">=", $num.":00:00")
            ->whereTime("updated_at", "<", $num2.":00:00")->count();

      
            array_push($day_members_volume, $data);

        }

        //Fourth Number Of Sales
        $nos = Shipment::where("created_at", ">=", Carbon::today())->where("status", "!=", "rejected")->get()->count();

        return $all_data = [$day_ships, $day_sales_volume, $day_members_volume, $nos];
    }

    //Week Charts
    public function weekcharts()
    {

        //First Sales Volume
        $week_sales_volume = [];

        $today = Carbon::now()->format("l");

        $days_letters = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        $days_nums = [1, 2, 3, 4, 5, 6, 7];

        $today_pos = array_search($today, $days_letters) + 1;

        //Days Before
        $d_before = $today_pos - 1;
        //Days After
        $d_after = 7 - $today_pos;

        $dates = [];

        //Get Dates Before Today
        if($d_before != 0)
        {
            for($i=1; $i<=$d_before; $i++)
            {
                array_unshift($dates, Carbon::now()->subDays($i)->format('Y-m-d'));
            }
        }

        //Get Today Date
        array_push($dates, Carbon::now()->format('Y-m-d'));

        //Get Dates After Today
        if($d_after != 0)
        {
            for($i=1; $i<=$d_after; $i++)
            {
                array_push($dates, Carbon::now()->addDays($i)->format('Y-m-d'));
            }
        }


        for($i=0; $i<count($dates); $i++)
        {

            $data = Shipment::whereDate("updated_at", $dates[$i])->where("status", "!=", "rejected")->pluck("cost")->toArray();

            
            if(!empty($data))
            {
                array_push($week_sales_volume, floor(array_sum($data)));
            }
            else
            {
                array_push($week_sales_volume, 0);
            }


        }

        //Second Shippments (Sales)
        $week_ships = [];
     
        $week_sales_made = Shipment::whereBetween("updated_at", [$dates[0], $dates[6]])->get()->count();
        $week_sales_approved = Shipment::whereBetween("updated_at", [$dates[0], $dates[6]])->where("status", "!=", "pending")->where("status", "!=", "rejected")->get()->count();
        $week_sales_rejected = Shipment::whereBetween("updated_at", [$dates[0], $dates[6]])->where("status", "rejected")->get()->count();

        if($week_sales_approved > 0)
        {
            $approved_percentage = ($week_sales_approved / $week_sales_made) * 100;
        }
        else
        {
            $approved_percentage = 0;
        }

        if($week_sales_rejected > 0)
        {
            $rejected_percentage = ($week_sales_rejected / $week_sales_made) * 100;
        }
        else
        {
            $rejected_percentage = 0;
        }

        if($week_sales_made > 0)
        {
            $pending_percentage = 100 - ($approved_percentage + $rejected_percentage);
        }
        else
        {
            $pending_percentage = 0;
        }

        if($pending_percentage == 0 && $rejected_percentage == 0 && $approved_percentage == 0)
        {
            $week_ships = 0;
        }
        else
        {
            array_push($week_ships, round($approved_percentage, 2));
            array_push($week_ships, round($rejected_percentage, 2));
            array_push($week_ships, round($pending_percentage, 2));
        }


        //Third Members Volume
        $week_members_volume = [];

        for($i=0; $i<count($dates); $i++)
        {

            $data = Member::whereDate("created_at", $dates[$i])->get()->count();
         
            array_push($week_members_volume, $data);

        }

        //Fourth Number Of Sales
        $nos = Shipment::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where("status", "!=", "rejected")->get()->count();

        return $all_data = [$week_ships, $week_sales_volume, $week_members_volume, $nos];
        
    }

    public function monthcharts()
    {
        //First Sales Volume
        $month_sales_volume = [];

        $today = Carbon::now()->day;
        $totaldaysinmonth = Carbon::now()->daysInMonth;
        $monthdays = range(1, $totaldaysinmonth);

        //Days Before
        $d_before = $today - 1;
        //Days After
        $d_after = $totaldaysinmonth - $today;

        $dates = [];

        //Get Dates Before Today
        if($d_before != 0)
        {
            for($i=1; $i<=$d_before; $i++)
            {
                array_unshift($dates, Carbon::now()->subDays($i)->format('Y-m-d'));
            }
        }

        //Get Today Date
        array_push($dates, Carbon::now()->format('Y-m-d'));

        //Get Dates After Today
        if($d_after != 0)
        {
            for($i=1; $i<=$d_after; $i++)
            {
                array_push($dates, Carbon::now()->addDays($i)->format('Y-m-d'));
            }
        }

        for($i=0; $i<count($dates); $i++)
        {

            $data = Shipment::whereDate("updated_at", $dates[$i])->where("status", "!=", "rejected")->pluck("cost")->toArray();

            
            if(!empty($data))
            {
                array_push($month_sales_volume, floor(array_sum($data)));
            }
            else
            {
                array_push($month_sales_volume, 0);
            }


        }

        //Second Shippments (Sales)
        $month_ships = [];
    
        $month_sales_made = Shipment::whereBetween("updated_at", [$dates[0], $dates[$totaldaysinmonth-1]])->get()->count();
        $month_sales_approved = Shipment::whereBetween("updated_at", [$dates[0], $dates[$totaldaysinmonth-1]])->where("status", "!=", "pending")->where("status", "!=", "rejected")->get()->count();
        $month_sales_rejected = Shipment::whereBetween("updated_at", [$dates[0], $dates[$totaldaysinmonth-1]])->where("status", "rejected")->get()->count();

        if($month_sales_approved > 0)
        {
            $approved_percentage = ($month_sales_approved / $month_sales_made) * 100;
        }
        else
        {
            $approved_percentage = 0;
        }

        if($month_sales_rejected > 0)
        {
            $rejected_percentage = ($month_sales_rejected / $month_sales_made) * 100;
        }
        else
        {
            $rejected_percentage = 0;
        }

        if($month_sales_made > 0)
        {
            $pending_percentage = 100 - ($approved_percentage + $rejected_percentage);
        }
        else
        {
            $pending_percentage = 0;
        }

        if($pending_percentage == 0 && $rejected_percentage == 0 && $approved_percentage == 0)
        {
            $month_ships = 0;
        }
        else
        {
            array_push($month_ships, round($approved_percentage, 2));
            array_push($month_ships, round($rejected_percentage, 2));
            array_push($month_ships, round($pending_percentage, 2));
        }


        //Third Members Volume
        $month_members_volume = [];

        for($i=0; $i<count($dates); $i++)
        {

            $data = Member::whereDate("created_at", $dates[$i])->get()->count();
            
            array_push($month_members_volume, $data);

        }

        //Fourth Number Of Sales
        $nos = Shipment::whereMonth('created_at', Carbon::now()->month)->where("status", "!=", "rejected")->get()->count();

        return $all_data = [$month_ships, $month_sales_volume, $month_members_volume, $monthdays, $nos];
        
    }

    public function yearcharts()
    {
        //First Sales Volume
        $year_sales_volume = [];

        for($i=1; $i<= 12; $i++)
        {

            $data = Shipment::whereYear("updated_at", Carbon::now())->whereMonth("updated_at", $i)->where("status", "!=", "rejected")->pluck("cost")->toArray();

            
            if(!empty($data))
            {
                array_push($year_sales_volume, floor(array_sum($data)));
            }
            else
            {
                array_push($year_sales_volume, 0);
            }


        }

        //Second Shippments (Sales)
        $year_ships = [];
    
        $year_sales_made = Shipment::whereYear("updated_at", Carbon::now())->get()->count();
        $year_sales_approved = Shipment::whereYear("updated_at", Carbon::now())->where("status", "!=", "pending")->where("status", "!=", "rejected")->get()->count();
        $year_sales_rejected = Shipment::whereYear("updated_at", Carbon::now())->where("status", "rejected")->get()->count();

        if($year_sales_approved > 0)
        {
            $approved_percentage = ($year_sales_approved / $year_sales_made) * 100;
        }
        else
        {
            $approved_percentage = 0;
        }

        if($year_sales_rejected > 0)
        {
            $rejected_percentage = ($year_sales_rejected / $year_sales_made) * 100;
        }
        else
        {
            $rejected_percentage = 0;
        }

        if($year_sales_made > 0)
        {
            $pending_percentage = 100 - ($approved_percentage + $rejected_percentage);
        }
        else
        {
            $pending_percentage = 0;
        }

        if($pending_percentage == 0 && $rejected_percentage == 0 && $approved_percentage == 0)
        {
            $year_ships = 0;
        }
        else
        {
            array_push($year_ships, round($approved_percentage, 2));
            array_push($year_ships, round($rejected_percentage, 2));
            array_push($year_ships, round($pending_percentage, 2));
        }

        //Third Members Volume
        $year_members_volume = [];

        for($i=1; $i<=12; $i++)
        {

            $data = Member::whereYear("updated_at", Carbon::now())->whereMonth("updated_at", $i)->count();
            
            array_push($year_members_volume, $data);

        }

        //Fourth Number Of Sales
        $nos = Shipment::whereYear('created_at', Carbon::now()->year)->where("status", "!=", "rejected")->get()->count();

        return $all_data = [$year_ships, $year_sales_volume, $year_members_volume, $nos];
    }
    
    public function getspecreports(Request $request)
    {
        //Check If One Of Dates Are Empty
        if($request->fromdate == "" || $request->todate == "")
        {
            return "datesempty";
        }

        if(strtotime($request->fromdate) > strtotime($request->todate))
        {
            return "datelogic";
        }

        //Check If "To Date" Is Bigger Than "From Date"

        $sorted = date("Y-m-d", strtotime($request->fromdate));
        $sorted2 = date("Y-m-d", strtotime($request->todate));
        $sorted = Carbon::parse($sorted);
        $sorted2 = Carbon::parse($sorted2);
        
        if($sorted == $sorted2)
        {
            //First Sales Volume
            $dayspec_sales_volume = [];
            for($i=0; $i<24; $i++)
            {
                $num = str_pad($i, 2, "0", STR_PAD_LEFT);
                $num2 = str_pad($i+1, 2, "0", STR_PAD_LEFT);

                $data = Shipment::whereDate("updated_at", $sorted)
                ->whereTime("updated_at", ">=", $num.":00:00")
                ->whereTime("updated_at", "<", $num2.":00:00")->where("status", "!=", "rejected")->pluck("cost")->toArray();

                
                if(!empty($data))
                {
                    array_push($dayspec_sales_volume, floor(array_sum($data)));
                }
                else
                {
                    array_push($dayspec_sales_volume, 0);
                }


            }

            //Second Shippments (Sales)
            $dayspec_ships = [];
    
            $dayspec_sales_made = Shipment::whereDate("updated_at", $sorted)->get()->count();
            $dayspec_sales_approved = Shipment::whereDate("updated_at", $sorted)->where("status", "!=", "pending")->where("status", "!=", "rejected")->get()->count();
            $dayspec_sales_rejected = Shipment::whereDate("updated_at", $sorted)->where("status", "rejected")->get()->count();

            if($dayspec_sales_approved > 0)
            {
                $approved_percentage = ($dayspec_sales_approved / $dayspec_sales_made) * 100;
            }
            else
            {
                $approved_percentage = 0;
            }

            if($dayspec_sales_rejected > 0)
            {
                $rejected_percentage = ($dayspec_sales_rejected / $dayspec_sales_made) * 100;
            }
            else
            {
                $rejected_percentage = 0;
            }

            if($dayspec_sales_made > 0)
            {
                $pending_percentage = 100 - ($approved_percentage + $rejected_percentage);
            }
            else
            {
                $pending_percentage = 0;
            }

            if($pending_percentage == 0 && $rejected_percentage == 0 && $approved_percentage == 0)
            {
                $dayspec_ships = 0;
            }
            else
            {
                array_push($dayspec_ships, round($approved_percentage, 2));
                array_push($dayspec_ships, round($rejected_percentage, 2));
                array_push($dayspec_ships, round($pending_percentage, 2));
            }


            //Third Members Volume
            $dayspec_members_volume = [];
            for($i=0; $i<24; $i++)
            {
                $num = str_pad($i, 2, "0", STR_PAD_LEFT);
                $num2 = str_pad($i+1, 2, "0", STR_PAD_LEFT);

                $data = Member::whereDate("updated_at", $sorted)
                ->whereTime("updated_at", ">=", $num.":00:00")
                ->whereTime("updated_at", "<", $num2.":00:00")->count();

        
                array_push($dayspec_members_volume, $data);

            }

            //Fourth Number Of Sales
            $num = str_pad(0, 2, "0", STR_PAD_LEFT);
            $num2 = str_pad(24, 2, "0", STR_PAD_LEFT);

            $nos = Shipment::whereDate("updated_at", $sorted)
            ->whereTime("updated_at", ">=", $num.":00:00")
            ->whereTime("updated_at", "<", $num2.":00:00")->where("status", "!=", "rejected")->get()->count();

            return $all_data = [$dayspec_ships, $dayspec_sales_volume, $dayspec_members_volume, "day", $nos];

        }
        else
        {  
            //First Check If Number Of Days Between The Two Dates Not Exceeding 30 Days.
            $datechecker = Carbon::parse($sorted)->addDays(30);
            $datechecker = date("Y-m-d", strtotime($datechecker));
            $datechecker = strtotime($datechecker);

            if(strtotime($sorted2) > $datechecker)
            {
                return "exceeded";
            }
            else
            {
                //First Sales Volume
                $dates = [];
                $numofdays = (strtotime($sorted2) - strtotime($sorted)) / 86400;
                $daysarray = [];
                $monthspec_sales_volume = [];

                $today = Carbon::parse($sorted)->day;

                array_push($dates, Carbon::parse($sorted)->format('Y-m-d'));


                array_push($daysarray, Carbon::parse($sorted)->day);

                for($i=1; $i<=$numofdays; $i++)
                {
                    array_push($dates, Carbon::parse($sorted)->addDays($i)->format('Y-m-d'));
                    array_push($daysarray, Carbon::parse($sorted)->addDays($i)->day);
                }
                

                for($i=0; $i<count($dates); $i++)
                {

                    $data = Shipment::whereDate("updated_at", $dates[$i])->where("status", "!=", "rejected")->pluck("cost")->toArray();

                    
                    if(!empty($data))
                    {
                        array_push($monthspec_sales_volume, floor(array_sum($data)));
                    }
                    else
                    {
                        array_push($monthspec_sales_volume, 0);
                    }


                }

                //Second Shippments (Sales)
                $monthspec_ships = [];
            
                $monthspec_sales_made = Shipment::whereBetween("updated_at", [$sorted, $sorted2])->get()->count();
                $monthspec_sales_approved = Shipment::whereBetween("updated_at", [$sorted, $sorted2])->where("status", "!=", "pending")->where("status", "!=", "rejected")->get()->count();
                $monthspec_sales_rejected = Shipment::whereBetween("updated_at", [$sorted, $sorted2])->where("status", "rejected")->get()->count();

                if($monthspec_sales_approved > 0)
                {
                    $approved_percentage = ($monthspec_sales_approved / $monthspec_sales_made) * 100;
                }
                else
                {
                    $approved_percentage = 0;
                }

                if($monthspec_sales_rejected > 0)
                {
                    $rejected_percentage = ($monthspec_sales_rejected / $monthspec_sales_made) * 100;
                }
                else
                {
                    $rejected_percentage = 0;
                }

                if($monthspec_sales_made > 0)
                {
                    $pending_percentage = 100 - ($approved_percentage + $rejected_percentage);
                }
                else
                {
                    $pending_percentage = 0;
                }

                if($pending_percentage == 0 && $rejected_percentage == 0 && $approved_percentage == 0)
                {
                    $monthspec_ships = 0;
                }
                else
                {
                    array_push($monthspec_ships, round($approved_percentage, 2));
                    array_push($monthspec_ships, round($rejected_percentage, 2));
                    array_push($monthspec_ships, round($pending_percentage, 2));
                }


                //Third Members Volume
                $monthspec_members_volume = [];

                for($i=0; $i<count($dates); $i++)
                {

                    $data = Member::whereDate("created_at", $dates[$i])->get()->count();
                    
                    array_push($monthspec_members_volume, $data);
                }

                //Fourth Number Of Sales
                $nos = Shipment::whereBetween("updated_at", [$sorted, $sorted2])->where("status", "!=", "rejected")->get()->count();
                return $all_data = [$monthspec_ships, $monthspec_sales_volume, $monthspec_members_volume, "month", $daysarray, $nos];
            }

        }
    }


}
