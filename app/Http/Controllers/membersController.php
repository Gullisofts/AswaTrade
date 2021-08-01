<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Blacklist;
use App\Models\Shipment;
use App\Models\Ticket;
use App\Models\Setting;
use App\Mail\resetPassMail;
use App\Mail\sendNewPassMail;
use App\Mail\sendNewRegisterMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Auth;
use Hash;

class membersController extends Controller
{
    public function chkloggedIn()
    {
        if(Auth::guard('member')->check())
        {
            return response()->json("loggedin");
        }
        else
        {
            return response()->json("not loggedin");
        }
        
    }

    public function getloginname()
    {
        if(Auth::guard('member')->check())
        {
            return Auth::guard('member')->user();
        }
        else
        {
            return response()->json("not loggedin");
        }
        
    }

    public function loginproc(Request $request)
    {
        //Check Login Data
        $password = $request->el_password;
        $email = $request->el_email;

        $rules = [

            "el_email" => "required|email",
            "el_password" => "required",
        ];

        $msg = [

            "el_email.required" => "البريد الإليكتروني مطلوب",
            "el_email.email" => "البريد الإليكتروني غير صالح",
            "el_password.required" => "كلمة المرور مطلوبة",
        ];
        
        $this->validate($request, $rules, $msg);

        //Check Recaptcha
        $res = $request->chk_recaptcha;
        $secret_key = config("app.GOOGLE_RECAPTCHA_SECRET");
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$res."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $f_response = json_decode($response, true);

        if($f_response["success"] == 1)
        {
            //Check Login Details
            $email_chk = Member::where("email", $email)->first();
            if($email_chk == NULL)
            {
                return "login faild";
            }
            else
            {
                if(Hash::check($password, $email_chk->password))
                {
                    if(Member::where("email", $email)->first()->verification_status == "false")
                    {
                        return "verification error";
                    }
                    else
                    {
                        //Check Block Status
                        $block_chk = Member::where("email", $email)->first()->block_status;
                        if($block_chk == "blocked")
                        {   
                            return "blockeduser";
                        }

                        $user = Member::where("email", $email)->first();
                        Auth::guard("member")->login($user);
                        return "login success";
                    }
                }
                else
                {
                    return "login faild";
                }
            }

        }
        else
        {
            return "captcha";
        } 

    }

    public function logout()
    {
        if(Auth::guard("member")->check())
        {
            Auth::guard("member")->logout();
            return redirect("/");
        }
        else
        {
            return redirect("/");
        }
    }

    public function reset_password(Request $request)
    {
        //Check Email
        $email = $request->el_email;

        $rules = [

            "el_email" => "required|email",
        ];

        $msg = [

            "el_email.required" => "البريد الإليكتروني مطلوب",
            "el_email.email" => "البريد الإليكتروني غير صالح",
        ];
        
        $this->validate($request, $rules, $msg);

        //Check Recaptcha
        $res = $request->chk_recaptcha;
        $secret_key = env("GOOGLE_RECAPTCHA_SECRET");
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$res."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $f_response = json_decode($response, true);
        $f_response["success"];

        if($f_response["success"] == 1)
        {
            //If Captcha Passed
            $user_email = Member::where("email", $email)->first();

            if($user_email == null)
            {
                return "email invalid";
            }
            else
            {
                //Create Reset Token And Mail Data
                $reset_code = Str::random(100);

                $data = [

                    "email" => $email,
                    "resetlink" => url('/')."/passreset/".$reset_code,
                ];

                //Send Email With The New Token
                Mail::to($email)->send(new resetPassMail($data));

                //Add Member's Reset Code and Reset Time To Database
                Member::where("email", $email)->update([

                    "reset_token" => $reset_code,
                    "reset_time" => strtotime(date("y-m-d h:i:sa")),

                ]);

                return "link sent";
            }
        }
        else
        {
            return "captcha";
        }


    }

    public function reset_password_page($token)
    {
        //Check If Token Exists - Return Error
        $token_chk = Member::where("reset_token", $token)->first();

        if($token_chk == NULL)
        {
            $invalid = 1;
            return view("resetpassword", compact("invalid"));
        }
        else
        {
            //Check If Token Valid (Time) - Return Error
            $reset_time = Member::where("reset_token", $token)->first()->reset_time;
            $time_now = strtotime(date("y-m-d h:i:sa"));
            $interval_time = intval($time_now) - intval($reset_time);

            if($interval_time > 3600)
            {
                $expired = 1;
                return view("resetpassword", compact("expired"));
            }
            else
            {
                //Change Password And Send It To The New Email - Return Success
                $new_password = Str::random(15);

                $data = [
                    "newpassword" => $new_password,
                ];

                $member_email = Member::where("reset_token", $token)->first()->email;

                Member::where("reset_token", $token)->update([

                    "reset_token" => "",
                    "reset_time" => "",
                    "password" => Hash::make($new_password),

                ]);

                Mail::to($member_email)->send(new sendNewPassMail($data));

                return view("resetpassword");
            }
        }

    }

    public function registerproc(Request $request)
    {
        //Check If Registeration Is Closed
        $chkreg = Setting::where("id", 1)->first()->registerstatus;

        if($chkreg == 1)
        {
            //Check Proxy - VPN
            $ip = $_SERVER["REMOTE_ADDR"];
            $apiKey = config('app.IPHUNTER_API_KEY');

            $headers = ['X-Key: '.$apiKey,];

            $ch = curl_init("https://www.iphunter.info:8082/v1/ip/".$ip);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $output = json_decode(curl_exec($ch), 1);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($output["data"]["block"] == 0)
            {
                return "Proxydetected";
            }


            //Validate Enteries - Main
            $rules =[

                "fullname" => "required|regex:/^[a-zA-Z گچپژیلفقهكمىو ء-ي]+$/|min:10",
                "password" => "required|min:8",
                "password_rep" => "required|min:8",
                "email" => "required|email",
                "email_rep" => "required|email",
                "phone" => "required|regex:/^[0-9]+$/",
                "address" => "required",
            ];

            $msg =[

                "fullname.required" => "الإسم بالكامل مطلوب",
                "fullname.regex" => "الاسم ينبغي أن يتكون من مسافات وحروف فقط",
                "fullname.min" => "الاسم ينبغي أن يتكون من 10 أحرف على الأقل",
                "password.required" => "كلمة المرور مطلوبة",
                "password.min" => "كلمة المرور يجب أن لا تقل عن 8 أحرف",
                "password_rep.required" => "تأكيد كلمة المرور مطلوب",
                "password_rep.min" => "تأكيد كلمة المرور يجب أن لا يقل عن 8 أحرف",
                "email.required" => "البريد الإليكتروني مطلوب",
                "email.email" => "البريد الإليكتروني غير صالح",
                "email_rep.required" => "تاكيد البريد الإليكتروني مطلوب",
                "email_rep.email" => "تأكيد البريد الإليكتروني غير صالح",
                "phone.required" => "رقم الهاتف مطلوب وينبغي أن يتكون من أرقام فقط",
                "phone.regex" => "رقم الهاتف يجب أن يتكون من أرقام فقط",
                "address.required" => "العنوان مطلوب",
            ];

            $this->validate($request, $rules, $msg);


            //Validate Enteries - Secondary
            //Check If Password And Confirmation Are The Same
            if($request->password !== $request->password_rep)
            {
                return "password not match";
            }
            else
            {
                //Check If Email And Confirmation Are The Same
                if($request->email !== $request->email_rep)
                {
                    return "email not match";
                }
                else
                {
                    //Check Captcha
                    $res = $request->recaptcha_ready;
                    $secret_key = config('app.GOOGLE_RECAPTCHA_SECRET');
                    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$res."&remoteip=".$_SERVER['REMOTE_ADDR']);
                    $f_response = json_decode($response, true);
                    $f_response["success"];

                    if($f_response["success"] == 1)
                    {
                        //Check If Email Exists
                        $email_chk = Member::where("email", $request->email)->first();
                        if($email_chk != NULL)
                        {
                            return "email exists";
                        }
                        else
                        {
                            //All Restrictions Passed

                            //Verification Token 
                            $verification_token = Str::random(50);

                            //Register Verification Status
                            $verification_status = "false";

                            $data = [

                                "verificationlink" => url('/')."/verify/".$verification_token,

                            ];

                            //Send Verification Mail
                            Mail::to($request->email)->send(new sendNewRegisterMail($data));

                            //Add Data To Database
                            Member::create([
                                "fullname" => $request->fullname,
                                "email" => $request->email,
                                "ip" => $ip,
                                "password" => Hash::make($request->password),
                                "phone" => $request->phone,
                                "address" => $request->address,
                                "verification_token" => $verification_token,
                                "verification_status" => $verification_status,
                            ]); 
                            
                            return "success";
                            

                        }
                    }
                    else
                    {
                        return "wrong captcha";
                    }
                }
            }
        }
    }

    public function verify_registration($token)
    {
        //Check If Token Exists - Return Error
        $token_chk = Member::where("verification_token", $token)->first();

        if($token_chk == NULL)
        {
            $invalid = 1;
            return view("verifyregistration", compact("invalid"));
        }
        else
        {
            //Check If Already Verified
            $token_verify_chk = Member::where("verification_token", $token)->first()->verification_status;

            if($token_verify_chk == "true")
            {
                $verified = 1;
                return view("verifyregistration", compact("verified"));
            }
            else
            {
                //Change Verification Status
                Member::where("verification_token", $token)->update([

                    "verification_status" => "true",

                ]);

                return view("verifyregistration");
            }
            
        }
    }

    public function updateprofileinfo(Request $request)
    {
        //Get User Data 
        $user = Auth::guard("member")->user();

        //Check If Old Password Is Set & True
        if(Hash::check($request->oldpass, $user->password))
        {
            //Check All Required Data
            $rules =[

                "fullname" => "required|regex:/^[a-zA-Z گچپژیلفقهكمىو ء-ي]+$/|min:10",
                "email" => "required|email",
                "phone" => "required|regex:/^[0-9]+$/",
                "address1" => "required",
            ];
    
            $msg =[
    
                "fullname.required" => "الإسم بالكامل مطلوب",
                "fullname.regex" => "الاسم ينبغي أن يتكون من مسافات وحروف فقط",
                "fullname.min" => "الاسم ينبغي أن يتكون من 10 أحرف على الأقل",
                "email.required" => "البريد الإليكتروني مطلوب",
                "email.email" => "البريد الإليكتروني غير صالح",
                "phone.required" => "رقم الهاتف مطلوب وينبغي أن يتكون من أرقام فقط",
                "phone.regex" => "رقم الهاتف يجب أن يتكون من أرقام فقط",
                "address1.required" => "العنوان مطلوب",
            ];
    
            $this->validate($request, $rules, $msg);

            //Check Logical Matters
            //>>> Check If New Password And Confirmation Are Both Set In Case Of Password Change
            if($request->newpass == "" && $request->newpass_conf =="")
            {
                //Case No New Password Has Been Set
                //Check Secondry Data
                //>> Governorate -> Check If Entered Governorate Is Correct
                $governorates = Setting::where("id", 1)->pluck('regions')->first();
                $governorates = explode("_xXnextarrayelxX_", $governorates);
                array_pop($governorates);
                
                if(in_array($request->governorate, $governorates))
                {

                    //Inserting Data To DB
                    Member::where("id", $user->id)->update([

                        "fullname" => $request->fullname,
                        "email" => $request->email,
                        "phone" => $request->phone,
                        "address1" => $request->address1,
                        "address2" => $request->address2,
                        "governorate" => $request->governorate,
                        "city" => $request->city,
                        "zip" => $request->zip,
                    ]);

                    return "success";
                }
                else
                {
                    return "govincorrect";
                }

            }
            elseif($request->newpass != "" && $request->newpass_conf !="")
            {
                //Case New Password Has Been Set
                //Check New Password
                //>> New Password And Confirmation Must Be Equal
                if($request->newpass === $request->newpass_conf)
                {
                    $passchk = strval($request->newpass);

                    //>> Password Should Not Be lesser Than 8 Chars
                    if(strlen($passchk) >= 8)
                    {
                        //Check Secondry Data
                        //>> Governorate -> Check If Entered Governorate Is Correct

                        $governorates = Setting::where("id", 1)->pluck('regions')->first();
                        $governorates = explode("_xXnextarrayelxX_", $governorates);
                        array_pop($governorates);
                        
                        if(in_array($request->governorate, $governorates))
                        {
                            //Inserting Data To DB
                            Member::where("id", $user->id)->update([

                                "fullname" => $request->fullname,
                                "email" => $request->email,
                                "password" => Hash::make($request->newpass),
                                "phone" => $request->phone,
                                "address1" => $request->address1,
                                "address2" => $request->address2,
                                "governorate" => $request->governorate,
                                "city" => $request->city,
                                "zip" => $request->zip,
                            ]);

                            return "success";
                        }
                        else
                        {
                            return "govincorrect";
                        }
                    }
                    else
                    {
                        return "newpassshort";
                    }
                }
                else
                {
                    return "newpassnotmatch";
                }
            }
            else
            {
                //Case Needed Data Is Needed Regarding The New Password

                return "passandconfrequired";
            }

        }
        else
        {
            return "oldpassfalse";
        }
    }

    //#### Customer Search Methods #####//
    //Search Customer By ID
    public function searchcustbyidfunc(Request $request)
    {
        $searchvalue = $request->searchval;

        $chk = Member::where("id", $searchvalue)->first();

        if($chk != NULL && $chk->count() > 0)
        {
            return $chk;
        }
        else
        {
            return "nodata";
        }

    }


    //Search Customer By Email
    public function searchcustbyemail(Request $request)
    {
        $searchvalue = $request->searchval;

        $chk = Member::where("email", $searchvalue)->first();

        if($chk != NULL && $chk->count() > 0)
        {
            return $chk;
        }
        else
        {
            return "nodata";
        }
    }

    //Search Customer By IP
    public function searchcustbyip(Request $request)
    {
        $searchvalue = $request->searchval;
        $lastid = $request->lastelement;

        $chk = Member::where("ip", $searchvalue)->orderBy("id", "ASC")->get();

        if($chk != NULL && $chk->count() > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1]->id;

            //Check If Last Element Has Been Got
            $custsquad = Member::where("ip", $searchvalue)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2);
            $lastelement = $custsquad[count($custsquad) -1]->id;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $custsquad, "1"];
            }
            else
            {
                return [$lastelement, $custsquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }
    }
    
    //Block Customer
    public function blockcustomer(Request $request)
    {
        $cust_id = $request->cust_id;
        $block_reason = $request->block_reason;

        $member_data = Member::where("id", $cust_id)->first();

        Blacklist::create(
        [
            "customer_id" => $cust_id,
            "ip" => $member_data->ip,
            "email" => $member_data->email,
            "reason" => $block_reason,
        ]);

        Member::where("id", $cust_id)->update(
        [
            "block_status" => "blocked",
        ]);

        //Logout Customer
        $user = Member::where("id", $cust_id)->first();
        Auth::guard("member")->logout($user);

        return "success";
    }


    //Unblock Customer
    public function unblockcustomer(Request $request)
    {
        $cust_id = $request->cust_id;

        Blacklist::where("customer_id", $cust_id)->forceDelete();

        Member::where("id", $cust_id)->update(
        [
            "block_status" => NULL,
        ]);

        return "success";
    }

    //Get Customer Data #Dashboard
    public function getcustomerdata($id)
    {
        //Get Basic Info
        $data = Member::where("id", $id)->first();

        //Get Reg Date
        $reg_date = Member::where("id", $id)->first()->created_at;
        $reg_date = date_format($reg_date, "d-m-Y h:i:s");

        return [$data, $reg_date];
    }

    //Get Customer Ship #Dashboard
    public function getcustomerships(Request $request)
    {
        $id = $request->customer_id;
        $lastid = $request->lastid;

        $chk = Shipment::where("member_id", $id)->orderBy("id", "ASC")->get();

        if($chk != NULL && $chk->count() > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1]->id;

            //Check If Last Element Has Been Got
            $shipquad = Shipment::where("member_id", $id)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);

            $lastelement = $shipquad[count($shipquad) -1]->id;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $shipquad, "1"];
            }
            else
            {
                return [$lastelement, $shipquad];
            }
                
        }
        else
        {
            return "nodata";
        }
    }

    public function getblockedusers(Request $request)
    {
        $lastid = $request->lastel;

        $chk = Member::where("block_status", "blocked")->orderBy("id", "ASC")->get();

        if($chk != NULL && $chk->count() > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1]->id;

            //Check If Last Element Has Been Got
            $shipquad = Member::where("block_status", "blocked")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);

            $lastelement = $shipquad[count($shipquad) -1]->id;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $shipquad, "1"];
            }
            else
            {
                return [$lastelement, $shipquad];
            }
                
        }
        else
        {
            return "nodata";
        }
    }

    public function getblockreason(Request $request)
    {
        $chek_if_blocked = Member::where("id", $request->valtosearch)->first();

        //Check If Customer Exist
        if($chek_if_blocked == NULL)
        {
            return "nodata";
        }

        //Check If Customer Blocked
        if($chek_if_blocked->block_status != "blocked")
        {
            return "notblocked";
        }

        return Blacklist::where("customer_id", $chek_if_blocked->id)->first()->reason;
    }

    //Tickets Control
    public function openticket(Request $request)
    {
        $head = trim($request->messagehead);
        $content = trim($request->messagecore);
        
        //Remove Special Chars
        $head = preg_replace('/[!#$%^&=*().-]/u','', strip_tags($head));
        $head = preg_replace('/[\/]/u','', $head);
        $head = preg_replace('/[\\\\]/u','', $head);

        $content = preg_replace('/[!#$%^&=*().-]/u','', strip_tags($content));
        $content = preg_replace('/[\/]/u','', $content);
        $content = preg_replace('/[\\\\]/u','', $content);
        

        //Check Message Subject
        if(mb_strlen($head) > 100 || mb_strlen($head) < 10)
        {
            return "subjectlength";
        }

        //Check Message Body
        if(mb_strlen($content) < 20)
        {
            return "bodylength";
        }
        

        //Get Member ID
        $id= Auth::guard('member')->user()->id;

        //Check Recaptcha
        $res = $request->chk_recaptcha;
        $secret_key = config("app.GOOGLE_RECAPTCHA_SECRET");
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$res."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $f_response = json_decode($response, true);

        if($f_response["success"] != 1)
        {
            return "captcha";
        }

        $ticket_id = mt_rand(111111111111111, 999999999999999);


        //Tickes Status:
        //answered => Answered and not closed so waiting for client reply
        //unanswred => Created and or unnswered new reply from customer and not closed
        //closed => closed ticket for completion or any other reason

        Ticket::create(
        [
            "ticket_id" => $ticket_id,
            "member_id" => $id,
            "subject" => $head,
            "content" => $content,
            "status" => "unanswered"
        ]);


        return ["ticketid", $ticket_id];

    }

    public function checkticket($tid)
    {
        $chk = Ticket::where("ticket_id", $tid)->where("member_id", Auth::guard('member')->user()->id)->orderBy("created_at", "ASC")->get();

        if($chk == NULL || $chk->count() <= 0)
        {
            return "noticket";
        }
        else
        {
            return ["found", $chk];
        }
    }

    public function ticketauthandget($tid)
    {
        $uer_id = Auth::guard("member")->user()->id;
        $ticket_user = Ticket::where("ticket_id", $tid)->orderBy("created_at", "ASC")->first()->member_id;

        if($uer_id != $ticket_user)
        {
            return "fake";
        }

        $username = Member::where("id", $uer_id)->first()->fullname;
        $subject = Ticket::where("ticket_id", $tid)->orderBy("created_at", "ASC")->first()->subject;

        return [Ticket::where("ticket_id", $tid)->orderBy("created_at", "ASC")->get(), $username, $subject];
    }

    public function getunansweredtickets(Request $request)
    {
        $lastid = $request->lastticket;

        $chk = Ticket::where("status", "unanswered")->where("subject", "!=", NULL)->orderBy("id", "ASC")->get();

        if($chk != NULL && $chk->count() > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1]->id;

            //Check If Last Element Has Been Got
            $ticketquad = Ticket::where("status", "unanswered")->where("subject", "!=", NULL)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);

            $lastelement = $ticketquad[count($ticketquad) -1]->id;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $ticketquad, "1"];
            }
            else
            {
                return [$lastelement, $ticketquad];
            }
                
        }
        else
        {
            return "nodata";
        }
    }

    public function ticket_close($id)
    {
        Ticket::where("ticket_id", $id)->update(
        [
            "status" => "closed",
        ]);

        return $id;
    }

    public function ticket_delete($id)
    {
        Ticket::where("ticket_id", $id)->forceDelete();
    }

    public function ticketreopen($id)
    {
        $lastreply = Ticket::where("ticket_id", $id)->orderBy("created_at", "DESC")->first()->member_id;

        if($lastreply == "support")
        {
            Ticket::where("ticket_id", $id)->update(
            [
                    "status" => "answered",
            ]);
        }
        else
        {
            Ticket::where("ticket_id", $id)->update(
            [
                    "status" => "unanswered",
            ]);
        }
    }

    public function getticketinfo($id)
    {
        $uer_id = Ticket::where("ticket_id", $id)->orderBy("created_at", "ASC")->first()->member_id;

        $username = Member::where("id", $uer_id)->first()->fullname;
        $subject = Ticket::where("ticket_id", $id)->orderBy("created_at", "ASC")->first()->subject;

        return [Ticket::where("ticket_id", $id)->orderBy("created_at", "ASC")->get(), $username, $subject];
    }

    public function savesupportreply(Request $request)
    {
        if(mb_strlen($request->messagecore) < 1)
        {
            return "emptyreply";
        }
        
        Ticket::create(
        [
            "ticket_id" => $request->ticket_id,
            "member_id" => "support",
            "content" => $request->messagecore,
            "status" => "answered"
        ]);

        Ticket::where("ticket_id", $request->ticket_id)->update(
        [
            "status" => "answered"
        ]);
        
    }

    public function getticketsbydate(Request $request)
    {
        $datefrom = $request->datefrom;
        $dateto = $request->dateto;
        $lastid = $request->lastticket;


        $chk = Ticket::whereBetween("created_at", [$datefrom, $dateto])->where("subject", "!=", NULL)->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];

            //Check If Last Element Has Been Got
            $ticketssquad = Ticket::whereBetween("created_at", [$datefrom, $dateto])->where("subject", "!=", NULL)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);
            $lastelement = $ticketssquad[count($ticketssquad) -1];

            $lastelement = $lastelement->id;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $ticketssquad, "1"];
            }
            else
            {
                return [$lastelement, $ticketssquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }
    }

    public function getticketsbyticketnum(Request $request)
    {
        $ticketnum = $request->ticketnum;

        $chk = Ticket::where("ticket_id", $ticketnum)->where("subject", "!=", NULL)->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {

            $ticket = Ticket::where("ticket_id", $ticketnum)->where("subject", "!=", NULL)->first();

            //Return Required Ticket
            return [$ticket->id, $ticket, "1"];

        }
        else
        {
            return "nodata";
        }
    }

    //Save Customer Reply
    public function savecustomerrteply(Request $request)
    {
        //Check Real Status
        $chk = Ticket::where("ticket_id", $request->ticketnumber)->first()->status;

        if($chk == "closed" || $chk == "unanswered")
        {
            return "somethingwrong";
        }

        //Filter and Check Content
        $content = trim($request->customerreply);
        
        //Remove Special Chars
        $content = preg_replace('/[!#$%^&=*().-]/u','', strip_tags($content));
        $content = preg_replace('/[\/]/u','', $content);
        $content = preg_replace('/[\\\\]/u','', $content);
        
        //Check Message Body
        if(mb_strlen($content) < 5 || mb_strlen($content) > 500)
        {
            return "bodylength";
        }

        //Fast Sec Layer
        $chk = Ticket::where("ticket_id", $request->ticketnumber)->orderBy("created_at", "ASC")->first();
        $chk_mem_id = $chk->member_id;
        $mem_id = Auth::guard("member")->user()->id;


        if($mem_id != $chk_mem_id)
        {
            return "somethingwrong";
        }

        if($chk->count() < 1)
        {
            return "somethingwrong";
        }
        
        Ticket::create(
        [
            "ticket_id" => $request->ticketnumber,
            "member_id" => $mem_id,
            "content" => $request->customerreply,
            "status" => "unanswered"
        ]);

        Ticket::where("ticket_id", $request->ticketnumber)->update(
        [
            "status" => "unanswered"
        ]);
        
    }

    //Get Locations (Governorates)
    public function getlocations()
    {
        $regions = Setting::where("id", 1)->first()->regions;
        $regions = explode("_xXnextarrayelxX_", $regions);
        $regions = array_filter($regions);
        $onlyregions = [];
        for($i=0; $i<count($regions); $i=$i+2)
        {
            array_push($onlyregions, $regions[$i]);
        }

        return $onlyregions;
        
    }
    //Get Shippment Fees
    public function getmemberdata()
    {
        //Get Address
        $member_id = Auth::guard("member")->user()->id;
        $address = Member::where("id", $member_id)->select(["phone", "address1", "address2", "governorate", "city"])->first();
         
        //Get Fees Amount
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

        return [intval($fees), $address];
    }

    //Save Edited Customer Data -- From Dashboard
    public function saveeditedcustomerdash(Request $request)
    {
        //Get User Data 
        $user = Auth::guard("member")->user();

        //Check All Required Data
        $rules =
        [

            "fullname" => "required|regex:/^[a-zA-Z گچپژیلفقهكمىو ء-ي]+$/|min:10",
            "email" => "required|email",
            "phone" => "required|regex:/^[0-9]+$/",
            "address1" => "required",
        ];

        $msg =
        [

            "fullname.required" => "الإسم بالكامل مطلوب",
            "fullname.regex" => "الاسم ينبغي أن يتكون من مسافات وحروف فقط",
            "fullname.min" => "الاسم ينبغي أن يتكون من 10 أحرف على الأقل",
            "email.required" => "البريد الإليكتروني مطلوب",
            "email.email" => "البريد الإليكتروني غير صالح",
            "phone.required" => "رقم الهاتف مطلوب وينبغي أن يتكون من أرقام فقط",
            "phone.regex" => "رقم الهاتف يجب أن يتكون من أرقام فقط",
            "address1.required" => "العنوان مطلوب",
        ];

        $this->validate($request, $rules, $msg);

        //Check Logical Matters
        //>>> Check If New Password And Confirmation Are Both Set In Case Of Password Change
        if($request->newpass == "" && $request->newpass_conf =="")
        {
            //Case No New Password Has Been Set
            //Check Secondry Data
            //>> Governorate -> Check If Entered Governorate Is Correct

            $governorates = Setting::where("id", 1)->pluck('regions')->first();
            $governorates = explode("_xXnextarrayelxX_", $governorates);
            array_pop($governorates);

            if(in_array($request->governorate, $governorates))
            {
                //Inserting Data To DB
                Member::where("id", $user->id)->update([

                    "fullname" => $request->fullname,
                    "email" => $request->email,
                    "phone" => $request->phone,
                    "address1" => $request->address1,
                    "address2" => $request->address2,
                    "governorate" => $request->governorate,
                    "city" => $request->city,
                    "zip" => $request->zip,
                ]);

                return "success";
            }
            else
            {
                return "govincorrect";
            }

        }
        elseif($request->newpass != "" && $request->newpass_conf !="")
        {
            //Case New Password Has Been Set
            //Check New Password
            //>> New Password And Confirmation Must Be Equal
            if($request->newpass === $request->newpass_conf)
            {
                $passchk = strval($request->newpass);

                //>> Password Should Not Be lesser Than 8 Chars
                if(strlen($passchk) >= 8)
                {
                    //Check Secondry Data
                    //>> Governorate -> Check If Entered Governorate Is Correct
                    //Convert Regions To Array
                    $governorates = Setting::where("id", 1)->pluck('regions')->first();
                    $governorates = explode("_xXnextarrayelxX_", $governorates);
                    array_pop($governorates);

                    if(in_array($request->governorate, $governorates))
                    {
                        //Inserting Data To DB
                        Member::where("id", $user->id)->update([

                            "fullname" => $request->fullname,
                            "email" => $request->email,
                            "password" => Hash::make($request->newpass),
                            "phone" => $request->phone,
                            "address1" => $request->address1,
                            "address2" => $request->address2,
                            "governorate" => $request->governorate,
                            "city" => $request->city,
                            "zip" => $request->zip,
                        ]);

                        return "success";
                    }
                    else
                    {
                        return "govincorrect";
                    }
                }
                else
                {
                    return "newpassshort";
                }
            }
            else
            {
                return "newpassnotmatch";
            }
        }
        else
        {
            //Case Needed Data Is Needed Regarding The New Password

            return "passandconfrequired";
        }

    }

}

