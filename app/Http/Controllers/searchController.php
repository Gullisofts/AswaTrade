<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Mainsection;
use Auth;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class searchController extends Controller
{
    public function searchproducts($searchlastelementid)
    {   
        $last_prod = explode("__", $searchlastelementid)[1];
        $searchvalue = explode("__", $searchlastelementid)[0];

        $data = Product::where("productname", "like", "%" . $searchvalue . "%")->where("id", ">", $last_prod)->where("qty", ">", 0)->take(20)->get();

        if($data->count() > 0)
        {
            //Check IF All Products Got
            $chk = Product::where("productname", "like", "%" . $searchvalue . "%")->where("qty", ">", 0)->get();
            $chk2 = Product::where("productname", "like", "%" . $searchvalue . "%")->where("qty", ">", 0)->orderBy('id', 'desc')->first();
            $chk2_ = Product::where("productname", "like", "%" . $searchvalue . "%")->where("id", ">", $last_prod)->where("qty", ">", 0)->take(20)->pluck("id")->toArray();
            
            if($chk->count() == $data->count() || in_array($chk2->id, $chk2_))
            {
                return [$data, "lastelement"];
            }
            else
            {
                return [$data];
            }
            
        }
        else
        {
            return "";
        }
    }

    public function getprodmanus_search($searchvalue)
    {
        $allmanus = Product::where("productname", "like", "%" . $searchvalue . "%")->where("qty", ">", 0)->pluck("manufacturer")->toArray();
        return array_unique($allmanus);
    }

    public function filter_searchdata(Request $request)
    {
        $models = $request->models_arr;
        $pricerate = $request->price_arr;
        $ratings = $request->rating_arr;
        $searchvalue = $request->psearchvalue;

        $first=[];
        $second=[];
        $third=[];

        $allarrays = [];
        $arrays_num_chk = [];

        //Check If All Empty
        if(empty($models) && empty($pricerate) && empty($ratings))
        {
            return "";
        }
        else
        {
            if(!empty($pricerate))
            {
                if(count($pricerate) == 2)
                {
                    $first = DB::table("products")->where("productname", "like", "%" . $searchvalue . "%")->where("price", ">=", (float)$pricerate[0])->where("price", "<=", (float)$pricerate[1])->where("qty", ">", 0)->pluck("id")->toArray();
                    $allarrays = array_merge($allarrays, $first);
                    array_push($arrays_num_chk, $first);   
                }
            }
            if(!empty($models))
            {
                $second = DB::table("products")->where("productname", "like", "%" . $searchvalue . "%")->whereIn("manufacturer", $models)->where("qty", ">", 0)->pluck("id")->toArray();
                $allarrays = array_merge($allarrays, $second);
                array_push($arrays_num_chk, $second);
            }

            if(!empty($ratings))
            {
                $third = DB::table("products")->where("productname", "like", "%" . $searchvalue . "%")->whereIn("rating", $ratings)->where("qty", ">", 0)->pluck("id")->toArray();
                
                $allarrays = array_merge($allarrays, $third);
                array_push($arrays_num_chk, 1);
            }

            //Now Filter Empty Arrays

            //Now Get Common Values
            $final_ids = [];

            $alldata_r = array_count_values($allarrays);

            if(!empty($alldata_r))
            {
                foreach($alldata_r as $key => $value)
                {
                    if($value == count($arrays_num_chk))
                    {
                        array_push($final_ids, $key);
                    }
                }

                sort($final_ids, SORT_NUMERIC);

                if(isset($request->last_filter_ele))
                {
                    $point_target = array_search($request->last_filter_ele, $final_ids);
                    $final_ids = array_slice($final_ids, $point_target+1);
                }

                $data_final = DB::table("products")->where("productname", "like", "%" . $searchvalue . "%")->whereIn("id", $final_ids)->where("qty", ">", 0)->take(20)->get();
                //Check If All Data Is Shown
                if($data_final->count() == count($final_ids)) 
                {
                    return [$data_final, "lastelement"];
                }
                elseif(isset($request->last_filter_ele) && $final_ids[count($final_ids) - 1] == $request->last_filter_ele )
                {
                    return [$data_final, "lastelement"];
                }
                else
                {
                    return [$data_final];
                }
            
            }
            else
            {
                return "";
            }
             
        }

    }
}
