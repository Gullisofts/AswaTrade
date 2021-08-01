<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Coffe;
use App\Models\Shipment;
use App\Models\Ticket;
use App\Models\Member;
use Hash;
use Session;
use Auth;
use Carbon\Carbon;

class settingsController extends Controller
{
    public function dashboard()
    {
        return view("dashboard");
    }

    public function dashboardlogin()
    {
        return view("dashlogin");
    }

    public function chkadmin()
    {
        if(Auth::guard('admin')->check())
        {
            return response()->json("loggedin");
        }
        else
        {
            return response()->json("not loggedin");
        }
        
    }
    
    public function dashboardloginproc(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        //Check IF Invalid Login Times == 5
        $logintimes = Coffe::where("id", 1)->first()->invalidlogins;

        if($logintimes == 5)
        {
            $sttime = Coffe::where("id", 1)->first()->starttime;
            $now = strtotime(date("h:i:sa"));
            
            //1800 => 30 mints
            if(($now - $sttime) >= 1800)
            {
                Coffe::where("id", 1)->update(
                [
                    "invalidlogins" => 0,
                    "starttime" => NULL,
                ]);
            }
            else
            {
                Session::flash("loginwait", "عليك أن تنتظر لـ 30 دقيقة كي تتمكن من المحاولة مرة أخرى");
                return redirect("/dashboardlogin");
            }
        }
        else
        {
            //Check Inserted Data
            $rules = 
            [
                "username" => "required|alpha_num",
                "password" => "required|regex:/(^[A-Za-z0-9*]+$)+/",
            ];
            $msgs = 
            [
                "username.required" => "اسم المستخدم مطلوب",
                "username.alpha_num" => "حدث خطأ ما",
                "password.required" => "كلمة المرور مطلوبة",
                "password.regex" => "حدث خطأ ما",
            ];

            $this->validate($request, $rules, $msgs);

            //Login Proccess
            $chk = Coffe::where("id", 1)->first();

            if(Hash::check($password, $chk->password) && $username == $chk->username)
            {
                Auth::guard("admin")->login($chk);

                Coffe::where("id", 1)->update(
                [
                    "invalidlogins" => 0,
                ]);

                return redirect("/dashboard");
            }
            else
            {
                $newlogintimes = $logintimes + 1;

                Coffe::where("id", 1)->update(
                [
                    "invalidlogins" => $newlogintimes,
                ]);

                if($newlogintimes == 5)
                {
                    Coffe::where("id", 1)->update(
                    [
                        "starttime" => strtotime(date("h:i:sa")),
                    ]);
                }

                Session::flash("loginerror", "البيانات غير صحيحة");
                return redirect()->to('/dashboardlogin')->with('newlogintimes', $newlogintimes);
            }
        
        }
    }

    public function admin_logout()
    {
        Auth::guard("admin")->logout();
        return redirect("/dashboardlogin");
    }

    public function getcurrency()
    {
        return Setting::where("id", 1)->first()->currency;
    }

    public function getsettings()
    {
        $gen = Setting::where("id", 1)->select("sitename", "sitelink", "footerdescrip", "sitestatus", "registerstatus", "revisecomments", "currency", "facebooklink", "twitterlink", "instagramlink")->first();

        $supp = Setting::where("id", 1)->select("phonestatus", "emailstatus", "phones", "emails")->first();
        
        //Convert Regions To
        $regions = Setting::where("id", 1)->pluck('regions')->first();
        $regions = explode("_xXnextarrayelxX_", $regions);
        array_pop($regions);

        return [$gen, $regions, $supp];
    }

    public function saveapplysettings(Request $request)
    {
        $sitename = trim($request->sitename);
        $currency = trim($request->currency);
        $sitelink = trim($request->sitelink);
        $footerdescrip = trim($request->footerdescrip);
        $currentpassword = $request->currentpassword_gen;
        $sitestatus = $request->sitestatus;
        $registerstatus = $request->registerstatus;
        $revisecomments = $request->revisecomments;
        $facebooklink = $request->facebooklink;
        $twitterlink = $request->twitterlink;
        $instagramlink = $request->instagramlink;

        //check current Password
        $getpass = Coffe::where("id", 1)->first()->password;
        if(!Hash::check($currentpassword, $getpass))
        {
            return "wrongpass";
        }

        //Check If Something Empty
        if(mb_strlen($sitename) < 1  || mb_strlen($currency) < 1 || mb_strlen($sitelink) < 1  || mb_strlen($footerdescrip) < 1)
        {
            return "empty";
        }

        //Save Data
        Setting::where("id", 1)->update(
        [
            "sitename" => $sitename,
            "currency" => $currency,
            "sitelink" => $sitelink,
            "footerdescrip" => $footerdescrip,
            "sitestatus" => $sitestatus,
            "registerstatus" => $registerstatus,
            "revisecomments" => $revisecomments,
            "facebooklink" => $facebooklink,
            "twitterlink" => $twitterlink,
            "instagramlink" => $instagramlink,
        ]);

        //Apply Data
        //Wait
    }

    public function saveregions(Request $request)
    {
        $regions = $request->regions;
        $region_copy = $request->regions;
        $currentpassword = $request->currentpassword_region;

        //check current Password
        $getpass = Coffe::where("id", 1)->first()->password;
        if(!Hash::check($currentpassword, $getpass))
        {
            return "wrongpass";
        }

        //Check Regions
        //Empty
        if($region_copy == "")
        {
            return "empty";
        }

        //Lack of Data
        $region_copy = explode("_xXnextarrayelxX_", $region_copy);
        array_pop($region_copy);
        for($i=0; $i<count($region_copy); $i++)
        {
            if($region_copy[$i] == "")
            {
                return "lack";
            }
        }
        
        //Save New Regions Data
        Setting::where("id", 1)->update(
        [
            "regions" => $regions,
        ]);

        return "success";
    }

    //Save Support Settings
    public function savesupportsettings(Request $request)
    {
        $emails = $request->emails;
        $phones = $request->phones;
        $phonestatus = $request->phonestatus;
        $emailstatus = $request->emailstatus;
        $currentpassword = $request->currentpassword_supp;

        //check current Password
        $getpass = Coffe::where("id", 1)->first()->password;
        if(!Hash::check($currentpassword, $getpass))
        {
            return "wrongpass";
        }

        //Filter Phones and Emails
        $emails_arr = explode("_xXnextarrayelxX_", $emails);
        $phones_arr = explode("_xXnextarrayelxX_", $phones);
        
        $emails_arr = array_filter($emails_arr);
        $phones_arr = array_filter($phones_arr);

        $emails = implode("_xDxCv_", $emails_arr);
        $phones = implode("_xDxCv_", $phones_arr);

        
        //Save New Regions Data
        Setting::where("id", 1)->update(
        [
            "emails" => $emails,
            "phones" => $phones,
            "phonestatus" => $phonestatus,
            "emailstatus" => $emailstatus,
        ]);

        return "success";
    }

    //Change Admin Password
    public function chgadminpassword(Request $request)
    {
        $currentpassword = $request->currentpassword_sec;
        $newpassword = $request->newpassword;
        $newpasswordrep = $request->newpasswordrep;

        //check current Password
        $getpass = Coffe::where("id", 1)->first()->password;
        if(!Hash::check($currentpassword, $getpass))
        {
            return "wrongpass";
        }

        //Check If Something Empty
        if(mb_strlen($newpassword) < 1 || mb_strlen($newpasswordrep) < 1)
        {
            return "empty";
        }

        //Check If New password != Its confirmation
        if($newpassword != $newpasswordrep)
        {
            return "notmatch";
        }

        //Save Data
        Coffe::where("id", 1)->update(
        [
            "password" => Hash::make($newpassword),
        ]);

        return "success";
    }

    //Incoming Week Chart Data
    public function dailycharts()
    {
        $sums_ships = [];
        $sums_custs = [];

        //Shippments
        $days = 
        [
            1=>"Saturday",
            2=>"Sunday",
            3=>"Monday",
            4=>"Tuesday",
            5=>"Wednesday",
            6=>"Thursday",
            7=>"Friday",
        ];

        $todaynum = array_search(Carbon::now()->format('l'), $days);

        $counter = 0;

        for($i=$todaynum; $i>=1; $i--)
        {
            $data = json_decode(json_encode(
                    Shipment::whereDate('created_at', date('Y-m-d',strtotime("-".$counter."days")))
                    ->where("status", "!=", "rejected")
                    ->get()->count()));

            array_unshift($sums_ships, $data);

            $counter++;
        }

        for($i=$todaynum+1; $i<=7; $i++)
        {
            array_push($sums_ships, 0);
        }

        //Customers
        $days = 
        [
            1=>"Saturday",
            2=>"Sunday",
            3=>"Monday",
            4=>"Tuesday",
            5=>"Wednesday",
            6=>"Thursday",
            7=>"Friday",
        ];

        $todaynum = array_search(Carbon::now()->format('l'), $days);

        $counter = 0;

        for($i=$todaynum; $i>=1; $i--)
        {
            $data = json_decode(json_encode(
                    Member::whereDate('created_at', date('Y-m-d',strtotime("-".$counter."days")))
                    ->get()->count()));

            array_unshift($sums_custs, $data);

            $counter++;
        }

        for($i=$todaynum+1; $i<=7; $i++)
        {
            array_push($sums_custs, 0);
        }
        

        return [$sums_ships, $sums_custs];

    }
    public function maintenance()
    {
        return response()->view("undermaintenance");
    }
    public function getwebsitename()
    {
        return Setting::where("id", 1)->first()->sitename;
    }
    public function getwebsitedescrip()
    {
        return Setting::where("id", 1)->first()->footerdescrip;
    }
    public function getwebsitelink()
    {
        return Setting::where("id", 1)->first()->sitelink;
    }
    public function chkregisterstatus()
    {
        return Setting::where("id", 1)->first()->registerstatus;
    }

    public function getsupportinfo()
    {
        $data = Setting::where("id", 1)->select(["emailstatus", "phonestatus", "emails", "phones"])->get();

        return $data;
    }

    public function cardsdata()
    {
        $customers = Member::all()->count();
        $shippments = Shipment::where("status", "!=", "rejected")->where("status", "!=", "pending")->get()->count();
        $tickets = Ticket::where("status", "unanswered")->get()->count();

        return [$customers, $shippments, $tickets];
    }

    public function getfootersociallinks()
    {
        $facebooklink = Setting::where("id", 1)->first()->facebooklink;
        $twitterlink = Setting::where("id", 1)->first()->twitterlink;
        $instagramlink = Setting::where("id", 1)->first()->instagramlink;

        return [$facebooklink, $twitterlink, $instagramlink];
    }
}
