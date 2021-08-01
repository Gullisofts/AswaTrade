<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\Member;
use App\Models\Rating;
use App\Models\Setting;
use \Illuminate\Database\Eloquent\Collection;
use Auth;
use Str;
use File;

class productsController extends Controller
{
    public function discountproducts(Request $request)
    {
        $lastid = $request->lastid;

        $chk = Product::where("discount", ">", 0)->where("id", ">", $lastid)->orderBy("id", "ASC")->where("qty", ">", 0)->get()->pluck("id")->toArray();

        if($chk != NULL || count($chk) )
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];

            //Check If Last Element Has Been Got
            $prodsquad = Product::where("discount", ">", 0)->where("id", ">", $lastid)->where("qty", ">", 0)->orderBy("id", "ASC")->paginate(20);
            $lastelement = $prodsquad[count($prodsquad) -1];
            $lastelement = $lastelement->id;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $prodsquad, "1"];
            }
            else
            {
                return [$lastelement, $prodsquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }

    }

    public function getproduct($productid)
    {
        $prod = Product::where("id", $productid)->first();
        $product_images = explode("__xYY7931YYx__", $prod->productimages);
        $product_spec = explode("597127912719", $prod->spec);
        $finalspecs = [];

        for($i=0; $i<count($product_spec); $i++)
        {
            $c = explode("762171578", $product_spec[$i]);

            array_push($finalspecs, $c);
        }

        unset($finalspecs[count($finalspecs)-1]);

        
        $prod_array = 
        [
            $prod->productname,
            $prod->manufacturer,
            $prod->price,
            $prod->discount,
            $prod->rating,
            $product_images,
            $finalspecs,
            $prod->qty,
            $prod->description,
            $prod->id,
        ];

        return $prod_array;
    }

    public function similarproducts($productid)
    {
        //Get Tags As Array
        $prod_tags = Tag::where("product_id", $productid)->pluck("tag");
        $prod_tags;
        $ids_pool = [];

        //Search other Products Has Any Of These Tags
        for($i=0; $i<count($prod_tags); $i++)
        {
            $gotted_prods_ids = Tag::where("tag", $prod_tags[$i])->pluck("product_id");

            foreach($gotted_prods_ids as $id)
            {
                array_push($ids_pool, $id);
            }
        }
        
        $checker2 = [];
        $checker3 = [];
        $checker4 = [];

        $ids_pool = array_count_values($ids_pool);

        //Check If there are Similar Products 
        if(count($ids_pool) > 0)
        {
            //Check Range If Similarity

            //First 2, 3, 4 tag similarity
            foreach($ids_pool as $key => $value)
            {
                if($value == 2)
                {
                    array_push($checker2, $key);
                }
            }
            foreach($ids_pool as $key => $value)
            {
                if($value == 3)
                {
                    array_push($checker3, $key);
                }
            }
            foreach($ids_pool as $key => $value)
            {
                if($value == 4)
                {
                    array_push($checker4, $key);
                }
            }

            //Check Wich One We Will Consider
            if(count($checker4) > 15)
            {
                //Return These Similars
                $data = new Collection;
                foreach($ids_pool as $key => $value)
                {
                    $prod = Product::where('id', $key)->where("qty", ">", 0)->first();
                    $data->push($prod);
                }
                return $data;
            }
            elseif(count($checker3) > 15)
            {
                //Return These Similars
                $data = new Collection;
                foreach($ids_pool as $key => $value)
                {
                    $prod = Product::where('id', $key)->where("qty", ">", 0)->first();
                    $data->push($prod);
                }
                return $data;
            }
            elseif(count($checker2) > 15)
            {
                //Return These Similars
                $data = new Collection;
                foreach($ids_pool as $key => $value)
                {
                    $prod = Product::where('id', $key)->where("qty", ">", 0)->first();
                    $data->push($prod);
                }
                return $data;
            }
            else
            {
                //Return These Similars
                $data = new Collection;
                foreach($ids_pool as $key => $value)
                {
                    $prod = Product::where('id', $key)->first();
                    $data->push($prod);
                }
                return $data;
            }

        }
        else
        {
            return "NoSimi";
        }

    }

    public function getprodcomments($productid)
    {

        $comments = Comment::where("product_id", $productid)->where("status", "true")->get();

        if($comments->count() < 1)
        {
            return "";
        }
        else
        {
            return $comments;
        }
    }

    public function getreviewscount($productid)
    {
        $comments = Comment::where("product_id", $productid)->where("status", "true")->get();

        if($comments->count() < 1)
        {
            return 0;
        }
        else
        {
            return $comments->count();
        }
    }

    public function store_comment(Request $request)
    {
        //Check If User Logged In
        if(!Auth::guard('member')->check())
        {
            return response()->json("not loggedin");
        }

        $user_name = Auth::guard('member')->user()->fullname;
        $user_id = Auth::guard('member')->user()->id;
        $product_id = $request->prod_id;

        //Check If Product Found
        $chk_prod = Product::where("id", $product_id)->first();

        if($chk_prod->count() <= 0)
        {
            return response()->json("no prod found");
        }
        
        //Check Comment
        $rules = 
        [
            "comment" => "required|min:3|regex:/^[1-9a-zA-Z گچپژیلفقهكمىو ء-ي]+$/",
        ];
        $msg = 
        [
            "comment.required" => "التعليق لا يجب أن يكون فارغ ولابد أن لا يقل عن 3 أحرف",
            "comment.min" => "التعليق لا يجب أن يكون فارغ ولابد أن لا يقل عن 3 أحرف",
            "comment.regex" => "التعليق ينبغي أن يتضمن أحرف وأرقام فقط",
        ];
        
        $this->validate($request, $rules, $msg);

        //Check IF Revise Comments Option Enabled or Not
        $revise_chk = Setting::where("id", 1)->first();

        if($revise_chk->revisecomments == 1)
        {
            Comment::create(
            [
                "user_id" => $user_id,
                "user_name" =>$user_name,
                "product_id" => $product_id,
                "content" => $request->comment,
                "status" => "false",
            ]);
        
            return response()->json("wait");
        }
        else
        {
            Comment::create(
            [
                "user_id" => $user_id,
                "user_name" =>$user_name,
                "product_id" => $product_id,
                "content" => $request->comment,
                "status" => "true",
            ]);
            
            return response()->json("add");
        }

    }

    public function store_rating(Request $request)
    {
        //Check If Rating Value Is Correct
        $ratings = ["rating1", "rating2", "rating3", "rating4", "rating5"];
        $productid = $request->productid;

        if(in_array($request->rating_val, $ratings))
        {
            //Convert Rating To Number
            $spillited = str_split($request->rating_val);
            $request->rating_val = intval(end($spillited));

            //Check If User Logged In
            if(Auth::guard('member')->check())
            {
                $user_id = Auth::guard("member")->user()->id;

                //Check If Product Exists
                $chk_prod = Product::where("id", $productid)->first();
                
                if($chk_prod->count() > 0)
                {
                    //Set User Rating
                    //check If User Alredy Rated This Product Before
                    $chk_user_rating = Rating::where("user_id", $user_id)->where("product_id", $productid)->first();

                    if($chk_user_rating != NULL)
                    {
                        //Upadate Rating
                        Rating::where("user_id", $user_id)->where("product_id", $productid)->update(
                        [
                            "rating" => $request->rating_val,
                        ]);
                    }
                    else
                    {
                        //New Rating
                        Rating::create(
                        [
                            "user_id" => $user_id,
                            "product_id" => $productid,
                            "rating" => $request->rating_val,
                        ]);
                    }

                    //Set Overall Product Rating
                    $number_of_persons = Rating::where("product_id", $productid)->get()->count();
                    $rating_sum = Rating::where("product_id", $productid)->sum('rating');
            
                    $rating_overall = $rating_sum / $number_of_persons;

                    Product::where("id", $productid)->update(
                    [
                        "rating" => $rating_overall,
                    ]);

                    return response()->json("success");
                }
                else
                {
                    return response()->json("wrongdata");
                }

            }
            else
            {
                return response()->json("wrongdata");
            }
        }
        else
        {
            return response()->json("wrongdata");
        }

    }
    //Get Cart Products
    public function cartproducts(Request $request)
    {
        $data = $request->cardprodsids;

        if(count($data) > 0)
        {
            $items= [];

            foreach($data as $item)
            {
                array_push($items, Product::where("id", $item)->first());
            }

            return $items;
        }
    }

    //Save New Product
    public function savenewproduct(Request $request)
    {
        //Save Data In Variables
        $prodname = $request->prodname;
        $prodmanu = $request->prodmanu;
        $mainsections_input = $request->mainsections_input;
        $brsections_input = $request->brsections_input;
        $produnit = $request->produnit;
        $prodqty = $request->prodqty;
        $prodprice = $request->prodprice;
        $prodcode = $request->prodcode;
        $proddesc = $request->proddesc;
        $prod_image = $request->prod_image;
        $proddiscount = $request->proddiscount;
        $prodspecs = $request->prodspecs;
        

        //Check - First
        $rules = 
        [
            "prodname" => "required",
            "prodqty" => "required",
            "prodprice" => "required",
            "prodcode" => "required",
            "proddesc" => "required",
            "prodmanu" => "required",
            "proddiscount" => "required",
        ];

        $validation = 
        [
            "prodname.required" => "اسم المنتج مطلوب",
            "prodqty.required" => "كمية المنتج مطلوبة",
            "prodprice.required" => "سعر المنتج مطلوب",
            "prodcode.required" => "كود المنتج مطلوب",
            "proddesc.required" => "وصف المنتج مطلوب",
            "prodmanu.required" => "اسم الشركة المصنعة مطلوبة",
            "proddiscount.required" => "الخصم مطلوب. ضع 0 إن كان لا يوجد خصم",
        ];

        $this->validate($request, $rules, $validation);

        //Check - Second
        //Detect "أختر..." 
        if($mainsections_input != "أختر...")
        {
            if($brsections_input != "أختر...")
            {
                if($produnit != "أختر...")
                {

                    //Check Specs
                    //Check If Specs are in pairs
                    $prodspecs = array_filter(explode("_xXnextarrayelxX_", $prodspecs));
                    $prodspecs_fi = "";
                    if(count($prodspecs) > 0)
                    {
                        if(count($prodspecs) % 2 != 0)
                        {
                            return "specspairs";
                        }
                        else
                        {
                            for($i=0; $i<count($prodspecs); $i=$i+2)
                            {
                                $prodspecs_fi .= $prodspecs[$i]. "762171578" .$prodspecs[$i+1]. "597127912719";
                            }
                        }

                    }
                    
                    //Check MAin IMG
                    if($request->hasFile("prod_image"))
                    {
                        //Save The Main Image
                        //Get Sections IDs
                        $msection_id = $mainsections_input;
                        $section_id = $brsections_input;

                        if(!file_exists(public_path( "/images/products/".$msection_id)))
                        {
                            mkdir(public_path( "/images/products/".$msection_id), 0777, true);
                        }
                        if(!file_exists(public_path( "/images/products/".$msection_id."/".$section_id)))
                        {
                            mkdir(public_path( "/images/products/".$msection_id."/".$section_id), 0777, true);
                        }

                        
                        $image = $request->prod_image;
                        $destinationPath = public_path( "/images/products/".$msection_id."/".$section_id."/");
                        $imageName = "main_".time().Str::random(5).'.'.$image->extension();
                        $image->move($destinationPath, $imageName);

                        $fixeddest = "/images/products/".$msection_id."/".$section_id."/";
                        $prod_images_fi = $fixeddest.$imageName;

                        if(count($request->prod_images) > 0)
                        {
                            //Save Images And Create DB Line
                            foreach($request->file("prod_images") as $image)
                            {
                                $destinationPath = public_path( "/images/products/".$msection_id."/".$section_id."/");
                                $imageName = time().Str::random(5).'.'.$image->extension();
                                $image->move($destinationPath, $imageName);
                                $prod_images_fi.="__xYY7931YYx__".$fixeddest.$imageName;
                            }
                        }

                        //Saving Data In DB
                        Product::create(
                        [
                            "mainsectionid" =>$msection_id,
                            "section_id" =>$section_id,
                            "productname" =>$prodname,
                            "description" =>$proddesc,
                            "spec" =>$prodspecs_fi,
                            "manufacturer" =>$prodmanu,
                            "productimages" =>$prod_images_fi,
                            "price" =>$prodprice,
                            "discount" =>$proddiscount,
                            "qty" =>$prodqty,
                            "produnit" =>$produnit,
                            "prodcode" =>$prodcode,
                            "rating" =>1,
                        ]);

                        //Save Tags
                        $product_id = Product::where("productname", $prodname)->orderBy('created_at', 'desc')->first()->id;
                        $tags = explode(" ", $prodname);

                        foreach($tags as $tag)
                        {
                            //Remove Special Characters
                            if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬–-]/', $tag))
                            {
                                //Check if Tag is INT
                                if(preg_match('/[0-9]/', $tag))
                                {
                                    if(mb_strlen($tag) > 2)
                                    {
                                        Tag::create(
                                        [
                                            "product_id" => $product_id,
                                            "tag" => $tag,
                                        ]);
                                    }
                                }
                                else
                                {
                                    Tag::create(
                                    [
                                        "product_id" => $product_id,
                                        "tag" => $tag,
                                    ]);
                                }
            
                            }
                        }
                            
                        return "success";

                    }
                    else
                    {
                        return "mainimgnotselected";
                    }

                }
                else
                {
                    return "noprodunit";
                }
            }
            else
            {
                return "nobrsection";
            }
        }
        else
        {
           return "nomainsection";
        }
        

    }

    //Search Prod By Name
    public function searchprodbyname(Request $request)
    {
        $searchvalue = $request->searchval;
        $lastid = $request->lastelement;
        
        if($searchvalue == "" || $searchvalue == NULL)
        {
            return "nullvalue";
        }

        $chk = Product::where("productname", "LIKE", "%".$searchvalue."%")->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];


            //Check If Last Element Has Been Got
            $prodsquad = Product::where("productname", "LIKE", "%".$searchvalue."%")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);
            $prodsidsquad = Product::where("productname", "LIKE", "%".$searchvalue."%")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20)->pluck("id");
            $lastelement = $prodsidsquad[count($prodsidsquad) -1] ;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $prodsquad, "1"];
            }
            else
            {
                return [$lastelement, $prodsquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }

    }
    //Search Prod By Code
    public function searchprodbycode(Request $request)
    {
        $searchvalue = $request->searchval;
        $lastid = $request->lastelement;

        if($searchvalue == "" || $searchvalue == NULL)
        {
            return "nullvalue";
        }

        $chk = Product::where("prodcode", $searchvalue)->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];


            //Check If Last Element Has Been Got
            $prodsnamesquad = Product::where("prodcode", $searchvalue)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2);
            $prodsidsquad = Product::where("prodcode", $searchvalue)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(2)->pluck("id");
            $lastelement = $prodsidsquad[count($prodsidsquad) -1] ;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $prodsquad, "1"];
            }
            else
            {
                return [$lastelement, $prodsquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }


    }
    //Search Prod By Price
    public function searchprodbyprice(Request $request)
    {
        $searchvalue = explode("|_Y_|", $request->searchval);
        $lastid = $request->lastelement;

        $chk = Product::where("price", ">=", $searchvalue[0])->where("price", "<=", $searchvalue[1])->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];

            //Check If Last Element Has Been Got
            $prodsnamesquad = Product::whereBetween("price", [$searchvalue[0], $searchvalue[1]])->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);
            $prodsidsquad = Product::whereBetween("price", [$searchvalue[0], $searchvalue[1]])->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20)->pluck("id");
            $lastelement = $prodsidsquad[count($prodsidsquad) -1] ;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $prodsquad, "1"];
            }
            else
            {
                return [$lastelement, $prodsquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }


    }

    //Get Product Data For Edit -> Dashboard
    public function getprodata($prodid)
    {
        $prod = Product::where("id", $prodid)->first();

        //Proccess Specs
        $product_spec = $prod->spec;
        $product_spec = explode("597127912719", $prod->spec);
        $finalspecs = [];

        for($i=0; $i<count($product_spec); $i++)
        {
            $c = explode("762171578", $product_spec[$i]);

            array_push($finalspecs, $c);
        }

        unset($finalspecs[count($finalspecs)-1]);

        //Proccess Images
        $imgsarray = explode("__xYY7931YYx__", $prod->productimages);


        return $prodarr = [$prod->id, $prod->mainsectionid, $prod->section_id, $prod->productname, $prod->price, $prod->prodcode, $prod->manufacturer,  $prod->produnit, $prod->qty, $prod->rating, $prod->discount, $prod->description, $finalspecs, $imgsarray, $prod->created_at, $prod->updated_at];


    }

    //Save Edited Product
    public function saveeditedproduct(Request $request)
    {
        //Save Data In Variables
        $prod_id = $request->prod_id;
        $prodname = $request->prodname;
        $prodmanu = $request->prodmanu;
        $mainsections_input = $request->mainsections_input;
        $brsections_input = $request->brsections_input;
        $produnit = $request->produnit;
        $prodqty = $request->prodqty;
        $prodprice = $request->prodprice;
        $prodcode = $request->prodcode;
        $proddesc = $request->proddesc;
        $prod_image = $request->prod_image;
        $proddiscount = $request->proddiscount;
        $prodspecs = $request->prodspecs;
        

        //Check - First
        $rules = 
        [
            "prodname" => "required",
            "prodqty" => "required",
            "prodprice" => "required",
            "prodcode" => "required",
            "proddesc" => "required",
            "prodmanu" => "required",
            "proddiscount" => "required",
        ];

        $validation = 
        [
            "prodname.required" => "اسم المنتج مطلوب",
            "prodqty.required" => "كمية المنتج مطلوبة",
            "prodprice.required" => "سعر المنتج مطلوب",
            "prodcode.required" => "كود المنتج مطلوب",
            "proddesc.required" => "وصف المنتج مطلوب",
            "prodmanu.required" => "اسم الشركة المصنعة مطلوبة",
            "proddiscount.required" => "الخصم مطلوب. ضع 0 إن كان لا يوجد خصم",
        ];

        $this->validate($request, $rules, $validation);

        //Check - Second
        //Detect "أختر..." 
        if($mainsections_input != "أختر...")
        {
            if($brsections_input != "أختر...")
            {
                if($produnit != "أختر...")
                {

                    //Check Specs
                    //Check If Specs are in pairs
                    $prodspecs = array_filter(explode("_xXnextarrayelxX_", $prodspecs));
                    $prodspecs_fi = "";
                    if(count($prodspecs) > 0)
                    {
                        if(count($prodspecs) % 2 != 0)
                        {
                            return "specspairs";
                        }
                        else
                        {
                            for($i=0; $i<count($prodspecs); $i=$i+2)
                            {
                                $prodspecs_fi .= $prodspecs[$i]. "762171578" .$prodspecs[$i+1]. "597127912719";
                            }
                        }

                    }

                    //Managing Images
                    //First Vriables
                    $currect_m_section = Product::where("id", $prod_id)->first()->mainsectionid;
                    $current_b_section = Product::where("id", $prod_id)->first()->section_id;
                    $new_m_section = $mainsections_input;
                    $new_b_section = $brsections_input;
                    $prod_images_fi = NULL;
                    $imageschanges = [];

                    //If Sections Modified -> Images Location Will Be Changed (Moving Files) & And Updating Any Changes In Image
                    if($currect_m_section != $new_m_section || $current_b_section != $new_b_section)
                    {
                        //Main Image 
                        //First Check If Image Changed -> Delete Old One and Create Folders In The New Path And Upload The New Image
                        if($request->hasFile("prod_image"))
                        {
                            //Delete Old Images
                            $oldMainImag = Product::where("id", $prod_id)->first();
                            $oldMainImag = explode("__xYY7931YYx__", $oldMainImag->productimages)[0];
                            if(\File::exists(public_path($oldMainImag)))
                            {
                                \File::delete(public_path($oldMainImag));
                            }

                            //Creat New Folders
                            if(!file_exists(public_path( "/images/products/".$new_m_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section), 0777, true);
                            }
                            if(!file_exists(public_path( "/images/products/".$new_m_section."/".$new_b_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section."/".$new_b_section), 0777, true);
                            }

                            //Save New Image
                            $image = $request->prod_image;
                            $destinationPath = "/images/products/".$new_m_section."/".$new_b_section."/";
                            $imageName = "main_".time().Str::random(5).'.'.$image->extension();
                            $image->move(public_path($destinationPath), $imageName);
    
                            $prod_images_fi = $destinationPath.$imageName;
                            array_push($imageschanges, "main");

                        }
                        //Second -> Image Not Changed -> Cut Images To The New Location
                        else
                        {
                            //Creat New Folders
                            if(!file_exists(public_path( "/images/products/".$new_m_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section), 0777, true);
                            }
                            if(!file_exists(public_path( "/images/products/".$new_m_section."/".$new_b_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section."/".$new_b_section), 0777, true);
                            }


                            $oldMainImag = Product::where("id", $prod_id)->first();
                            $oldMainImag = explode("__xYY7931YYx__", $oldMainImag->productimages)[0];

                            $destinationPath = "/images/products/".$new_m_section."/".$new_b_section."/";
                            $oldMainImagexten = explode(".", $oldMainImag);
                            $imageName = "main_".time().Str::random(5).'.'.end($oldMainImagexten);

                            rename(public_path($oldMainImag), public_path($destinationPath.$imageName));

                            $prod_images_fi = $destinationPath.$imageName;
                            array_push($imageschanges, "main");
                        }
                        //Other Images 
                        //First Check If Images Changed -> Delete Old Ones and Create Folders In The New Path And Upload The New Images
                        if($request->prod_images != NULL)
                        {
                            //Delete Old Images
                            $oldImages = Product::where("id", $prod_id)->first();
                            $oldImages = explode("__xYY7931YYx__", $oldImages->productimages);
                                
                            for($i=1; $i<count($oldImages); $i++)
                            {
                                if(\File::exists(public_path($oldImages[$i])))
                                {
                                    \File::delete(public_path($oldImages[$i]));
                                }
                            }

                            //Creat New Folders
                            if(!file_exists(public_path( "/images/products/".$new_m_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section), 0777, true);
                            }
                            if(!file_exists(public_path( "/images/products/".$new_m_section."/".$new_b_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section."/".$new_b_section), 0777, true);
                            }

                            //Save New Image
                            foreach($request->file("prod_images") as $image)
                            {
                                $destinationPath = "/images/products/".$new_m_section."/".$new_b_section."/";
                                $imageName = time().Str::random(5).'.'.$image->extension();
                                $image->move(public_path($destinationPath), $imageName);
                                $prod_images_fi.="__xYY7931YYx__".$destinationPath.$imageName;
                                array_push($imageschanges, "subimages");
                            }

                        }
                        //Second -> Images Not Changed -> Cut Images To The New Location
                        else
                        {
                            //Creat New Folders
                            if(!file_exists(public_path( "/images/products/".$new_m_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section), 0777, true);
                            }
                            if(!file_exists(public_path( "/images/products/".$new_m_section."/".$new_b_section)))
                            {
                                mkdir(public_path( "/images/products/".$new_m_section."/".$new_b_section), 0777, true);
                            }

                            $oldImages = Product::where("id", $prod_id)->first();
                            $oldImages = explode("__xYY7931YYx__", $oldImages->productimages);
                            $destinationPath = "/images/products/".$new_m_section."/".$new_b_section."/";

                            for($i=1; $i<count($oldImages); $i++)
                            {
                                $exten = explode(".", $oldImages[$i]);
                                $imageName = time().Str::random(5).'.'.end($exten);
                                rename(public_path($oldImages[$i]), public_path($destinationPath.$imageName));

                                if($prod_images_fi == NULL)
                                {
                                    $prod_images_fi = $destinationPath.$imageName;
                                }
                                else
                                {
                                    $prod_images_fi .= "__xYY7931YYx__".$destinationPath.$imageName;
                                }
                                
                                array_push($imageschanges, "subimages");
                            }
                            

                        }

                    }
                    //If Section Not Changed -> Images Will Still In The Same Path & And Updating Any Change In Images
                    else
                    {
                        if($request->hasFile("prod_image"))
                        {

                            //Delete Old Images
                            $oldMainImag = Product::where("id", $prod_id)->first();
                            $oldMainImag = explode("__xYY7931YYx__", $oldMainImag->productimages)[0];
                            if(\File::exists(public_path($oldMainImag)))
                            {
                                \File::delete(public_path($oldMainImag));
                            }

                            //Save New Image
                            $image = $request->prod_image;
                            $destinationPath = "/images/products/".$new_m_section."/".$new_b_section."/";
                            $imageName = "main_".time().Str::random(5).'.'.$image->extension();
                            $image->move(public_path($destinationPath), $imageName);
    
                            $prod_images_fi = $destinationPath.$imageName;
                            array_push($imageschanges, "main");

                        }
                        if($request->prod_images != NULL)
                        {
                            //Delete Old Images
                            $oldImages = Product::where("id", $prod_id)->first();
                            $oldImages = explode("__xYY7931YYx__", $oldImages->productimages);
                                
                            for($i=1; $i<count($oldImages); $i++)
                            {
                                if(\File::exists(public_path($oldImages[$i])))
                                {
                                    \File::delete(public_path($oldImages[$i]));
                                }
                            }

                            //Save New Image
                            foreach($request->file("prod_images") as $image)
                            {
                                $destinationPath = "/images/products/".$new_m_section."/".$new_b_section."/";
                                $imageName = time().Str::random(5).'.'.$image->extension();
                                $image->move(public_path($destinationPath), $imageName);
                                if($prod_images_fi == NULL)
                                {
                                    $prod_images_fi=$destinationPath.$imageName;
                                }
                                else
                                {
                                    $prod_images_fi.="__xYY7931YYx__".$destinationPath.$imageName;
                                }

                                array_push($imageschanges, "subimages");
                            }

                        }

                    }


                    //Adjust Images
                    if(in_array("main", $imageschanges) || in_array("subimages", $imageschanges))
                    {
                        if(!in_array("main", $imageschanges) && in_array("subimages", $imageschanges))
                        {
                            $completeimgsline = Product::where("id", $prod_id)->first();
                            $firstimg = explode("__xYY7931YYx__", $completeimgsline->productimages)[0];
                            $prod_images_fi = $firstimg."__xYY7931YYx__".$prod_images_fi;
                        }

                        if(in_array("main", $imageschanges) && !in_array("subimages", $imageschanges))
                        {
                            $completeimgsline = Product::where("id", $prod_id)->first();
                            $otherimages = explode("__xYY7931YYx__", $completeimgsline->productimages);
                            array_shift($otherimages);
                            $prod_images_fi = $prod_images_fi."__xYY7931YYx__".implode("__xYY7931YYx__", $otherimages);
                        }


                        //Saving Data In DB
                        Product::where("id", $prod_id)->update(
                        [
                            "mainsectionid" =>$new_m_section,
                            "section_id" =>$new_b_section,
                            "productname" =>$prodname,
                            "description" =>$proddesc,
                            "spec" =>$prodspecs_fi,
                            "manufacturer" =>$prodmanu,
                            "productimages" =>$prod_images_fi,
                            "price" =>$prodprice,
                            "discount" =>$proddiscount,
                            "qty" =>$prodqty,
                            "produnit" =>$produnit,
                            "prodcode" =>$prodcode,
                            "rating" =>1,
                        ]);
                        
                        //Save New Product Tags
                        //Remove Old Tags
                        Tag::where("product_id", $prod_id)->forceDelete();

                        //Add New Tags
                        $tags = explode(" ", $prodname);
        
                        foreach($tags as $tag)
                        {
                            //Remove Special Characters
                            if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬–-]/', $tag))
                            {
                                //Check if Tag is INT
                                if(preg_match('/[0-9]/', $tag))
                                {
                                    if(mb_strlen($tag) > 2)
                                    {
                                        Tag::create(
                                        [
                                            "product_id" => $prod_id,
                                            "tag" => $tag,
                                        ]);
                                    }
                                }
                                else
                                {
                                    Tag::create(
                                    [
                                        "product_id" => $prod_id,
                                        "tag" => $tag,
                                    ]);
                                }
            
                            }
                        }
    
                        return "success";
                    }
                    else
                    {
                        //Saving Data In DB
                        Product::where("id", $prod_id)->update(
                        [
                            "mainsectionid" =>$new_m_section,
                            "section_id" =>$new_b_section,
                            "productname" =>$prodname,
                            "description" =>$proddesc,
                            "spec" =>$prodspecs_fi,
                            "manufacturer" =>$prodmanu,
                            "price" =>$prodprice,
                            "discount" =>$proddiscount,
                            "qty" =>$prodqty,
                            "produnit" =>$produnit,
                            "prodcode" =>$prodcode,
                            "rating" =>1,
                        ]);

                        //Save New Product Tags
                        //Remove Old Tags
                        Tag::where("product_id", $prod_id)->forceDelete();

                        //Add New Tags
                        $tags = explode(" ", $prodname);
        
                        foreach($tags as $tag)
                        {
                            //Remove Special Characters
                            if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬–-]/', $tag))
                            {
                                //Check if Tag is INT
                                if(preg_match('/[0-9]/', $tag))
                                {
                                    if(mb_strlen($tag) > 2)
                                    {
                                        Tag::create(
                                        [
                                            "product_id" => $prod_id,
                                            "tag" => $tag,
                                        ]);
                                    }
                                }
                                else
                                {
                                    Tag::create(
                                    [
                                        "product_id" => $prod_id,
                                        "tag" => $tag,
                                    ]);
                                }
            
                            }
                        }
        
                        return "success";
                    }
                    

                }
                else
                {
                    return "noprodunit";
                }
            }
            else
            {
                return "nobrsection";
            }
        }
        else
        {
            return "nomainsection";
        }
        

    }

    //Delete Product
    public function deleteproduct($id)
    {
        Product::where("id", $id)->forceDelete();

        return "success";
    }

    //Search Comments
    public function searchcomments(Request $request)
    {
        $searchvalue = $request->searchval;
        $lastid = $request->lastelement;

        $chk = Comment::where("content", $searchvalue)->orderBy("id", "ASC")->get();

        if($chk != NULL && $chk->count() > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1]->id;

            //Check If Last Element Has Been Got
            $custsquad = Comment::where("content", $searchvalue)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);
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

    //Search Comments
    public function getunmanagedcomments(Request $request)
    {
        $lastid = $request->lastelement;

        $chk = Comment::where("status", "false")->orderBy("id", "ASC")->get();

        if($chk != NULL && $chk->count() > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1]->id;

            //Check If Last Element Has Been Got
            $custsquad = Comment::where("status", "false")->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(20);
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

    public function approvecomment($id)
    {
        Comment::where("id", $id)->update(
        [
            "status" => "true",
        ]);
    }

    public function destroycomment($id)
    {
        Comment::where("id", $id)->forceDelete();
    }

    //Check Product
    public function chkproduct($id)
    {
        //First Check If INT
        $chk = Product::where("id", $id)->first();

        if($chk != NULL && $chk->count() > 0)
        {
            return "valid";
        }
        else
        {
            return "invalid";
        }
    }

    //GET POPULAR PRODUCTS
    public function popularproducts()
    {
        return Product::where("sales", ">", 0)->orderBy("sales", "desc")->paginate(20);
    }

    //SEARCH LOW QTY PRODUCTS
    public function getlowqtyprods(Request $request)
    {
        $lastid = $request->lastelement;
        
        $chk = Product::where("qty", "<", 1)->orderBy("id", "ASC")->pluck("id");

        if(count($chk) > 0)
        {
            //Check If Last Element Has Been Got
            $lastelementchk = $chk[count($chk) -1];


            //Check If Last Element Has Been Got
            $prodsquad = Product::where("qty", "<", 1)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(1);
            $prodsidsquad = Product::where("qty", "<", 1)->where("id", ">", $lastid)->orderBy("id", "ASC")->paginate(1)->pluck("id");
            $lastelement = $prodsidsquad[count($prodsidsquad) -1] ;

            //Check if it's last squad
            if($lastelement == $lastelementchk)
            {
                return [$lastelement, $prodsquad, "1"];
            }
            else
            {
                return [$lastelement, $prodsquad];
            }
                
            
        }
        else
        {
            return "nodata";
        }

    }

}
