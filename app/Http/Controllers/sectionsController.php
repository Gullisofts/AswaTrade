<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Mainsection;
use Auth;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class sectionsController extends Controller
{
    public function get_header_sections()
    {
        $all_filled_sections_ids = Section::select('mainsection_id')->distinct()->pluck("mainsection_id");
 
        $data = "";
 
        for($i=0; $i<$all_filled_sections_ids->count(); $i++)
        {
            $msectionname = Mainsection::where("id", $all_filled_sections_ids[$i])->first()->msection_name;
 
            $data .= '<li class="nav-item dropdown megamenu"><a id="megamneu" href="" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false" class="nav-link dropdown-toggle font-weight-bold text-uppercase">'.$msectionname.'<a><div aria-labelledby="megamneu" class="dropdown-menu border-0 p-0 m-0"><div class="container"><div class="row bg-white rounded-0 m-0 shadow-sm"><div class="col-lg-7 col-xl-8"><div class="p-4"><div class="row"><div class="col-lg-6 mb-4"><ul class="list-unstyled">';
 
            $getnestedsections_ids = Section::where("mainsection_id", $all_filled_sections_ids[$i])->pluck("id");
            $getnestedsections_names = Section::where("mainsection_id", $all_filled_sections_ids[$i])->pluck("section_name");
            $getnestedsection_img = Mainsection::where("id", $all_filled_sections_ids[$i])->pluck("section_img");

            for($n=0; $n<$getnestedsections_ids->count(); $n++)
            {
                 $data .= '<li class="nav-item"><a href="'.$getnestedsections_ids[$n].'" class="nav-link text-small pb-0 section_entry">'.$getnestedsections_names[$n].'</a></li>';
            }
 
            $data .= '</ul></div></div></div></div>';
            $data .= '<div class="col-lg-5 col-xl-4 px-0 d-none d-lg-block" style="background: center center url(../../images/sections/'.$getnestedsection_img[0].')no-repeat; background-size: cover;">';
            $data .= '</div></div></div></div></li>';
        }
 
        return $data;
 
    } 
 
    public function get_sectionname($sectionid)
    {
        $section_name = Section::where("id", $sectionid)->first()->section_name;
        $msection_id = Section::where("id", $sectionid)->first()->mainsection_id;
        
        $msection_name = Mainsection::where("id", $msection_id)->first()->msection_name;
 
        return [$section_name, $msection_name];
    }

    public function sectionproducts($sectionlastelementid)
    {   
        $last_prod = explode("__", $sectionlastelementid)[1];
        $sectionid = explode("__", $sectionlastelementid)[0];

        $data = Product::where("section_id", $sectionid)->where("id", ">", $last_prod)->where("qty", ">", 0)->take(20)->get();
        if($data->count() > 0)
        {
            //Check Of All Products Got
            $chk1 = $data->last()->id;
            $chk2 = Product::where("section_id", $sectionid)->where("id", ">", $last_prod)->where("qty", ">", 0)->get();
            $chk2 = $chk2->last()->id;

            if($chk1 == $chk2)
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

    public function getprodmanus($sectionid)
    {
        $allmanus = Product::where("section_id", $sectionid)->where("qty", ">", 0)->pluck("manufacturer")->toArray();
        return array_unique($allmanus);
    }

    public function filter_sectiondata(Request $request)
    {
        $models = $request->models_arr;
        $pricerate = $request->price_arr;
        $ratings = $request->rating_arr;
        $psection_id = $request->psectionid;

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
                    $first = DB::table("products")->where("section_id", $psection_id)->where("price", ">=", (float)$pricerate[0])->where("price", "<=", (float)$pricerate[1])->where("qty", ">", 0)->pluck("id")->toArray();
                    $allarrays = array_merge($allarrays, $first);
                    array_push($arrays_num_chk, $first);   
                }
            }
            if(!empty($models))
            {
                $second = DB::table("products")->where("section_id", $psection_id)->whereIn("manufacturer", $models)->where("qty", ">", 0)->pluck("id")->toArray();
                $allarrays = array_merge($allarrays, $second);
                array_push($arrays_num_chk, $second);
            }

            if(!empty($ratings))
            {
                $third = DB::table("products")->where("section_id", $psection_id)->whereIn("rating", $ratings)->where("qty", ">", 0)->pluck("id")->toArray();
                $allarrays = array_merge($allarrays, $third);
                array_push($arrays_num_chk, 1);
            }

            //Now Filter Empty Arrays
            //$arrays_num_chk = array_filter($arrays_num_chk);

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

                $data_final = DB::table("products")->where("section_id", $psection_id)->whereIn("id", $final_ids)->where("qty", ">", 0)->take(20)->get();
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

    /*##### DASHBOARD #####*/
    public function getallsectionsids()
    {
      return Mainsection::all()->pluck("id");
    }
    public function getsectiondata($section_id)
    {
      $mainsectionname = Mainsection::where("id", $section_id)->first()->msection_name;
      $sectionsids = Section::where("mainsection_id", $section_id)->get()->pluck("id");

      $sectionname_numprod = [];
      $sum_all_prods = [];
      $data = "";

      for($i=0; $i<count($sectionsids); $i++)
      {
          $prods_count_in_section = Product::where("section_id", $sectionsids[$i])->get()->count();
          $section_name = Section::where("id", $sectionsids[$i])->first()->section_name;
          array_push($sectionname_numprod, $section_name);
          array_push($sectionname_numprod, $prods_count_in_section);
          array_push($sum_all_prods, $prods_count_in_section);
      }

      if(count($sectionname_numprod) == 0)
      {
        $data .= '<li class="list-group-item">لا يوجد أقسام فرعية</li>';
      }
      else
      {
        for($i=0; $i<count($sectionname_numprod); $i=$i+2)
        {
              $data .= '<li class="list-group-item"><span class="bsection_name">'.$sectionname_numprod[$i].'</span><span class="bsection_prod_num">'.$sectionname_numprod[$i+1].'</span></li>';
        }
      }


      return [$mainsectionname, array_sum($sum_all_prods) ,$data, $section_id];
    }

    public function geteditsectiondata($id)
    {
        $sectionname = Mainsection::where("id", $id)->first()->msection_name;
        $sectiondesc = Mainsection::where("id", $id)->first()->description;
        $subsections = [];
        $msectionimg = Mainsection::where("id", $id)->first()->section_img;

        $subsectionscoll = Section::where("mainsection_id", $id)->get();

        foreach($subsectionscoll as $section)
        {
            $subsections[$section->section_name] = $section->description."identifier_xXxX85215_".$section->id;

        }

        return [$sectionname, $sectiondesc, $subsections, $msectionimg];

    }

    public function saveeditedsection(Request $request)
    {
        if(strlen($request->subsectionnames) > 0 || strlen($request->subsectiondescs) > 0 || strlen($request->subsectionids) > 0)
        {
            //Prepare Main Variables
            $subsectionnames = $request->subsectionnames;
            $subsectiondescs = $request->subsectiondescs;
            $subsectionids = $request->subsectionids;
            
            $subsectionnames = array_filter(explode("_xXnextarrayelxX_", $subsectionnames));
            $subsectiondescs = array_filter(explode("_xXnextarrayelxX_", $subsectiondescs));
            $subsectionids = array_filter(explode("_xXnextarrayelxX_", $subsectionids));

            //If Request Has Image
            if($request->hasFile('sectionimg'))
            {
                $destinationPath = public_path( '/images/sections/');
                $image = $request->sectionimg;
                $imageName = time() . '.'.$image->extension();
                $image->move($destinationPath, $imageName);

                //Check If Section Name Empty
                if(strlen(trim($request->msectionname)) > 0)
                {
                    //Check If Section Name Is Used Before
                    //Get Current Section Name
                    $curr_sec_nm = Mainsection::where("id", $request->sectionId)->first()->msection_name;
                    $name_chk = Mainsection::where("msection_name", $request->msectionname)->first();

                    if($curr_sec_nm != $request->msectionname)
                    {
                        if($name_chk != "")
                        {
                            return "usedsectionname";
                        }
                    }
                    //Check If subsection names == subsection descs
                    if(count($subsectionnames) == count($subsectiondescs) && count($subsectionnames) > 0)
                    {

                        //Modify Main Secion Into DB
                        Mainsection::where("id", $request->sectionId)->update(
                        [
                            "msection_name" => $request->msectionname,
                            "description" => $request->msectiondesc,
                            "section_img" => $imageName,
                        ]);

                        //Delete Unwanted Sections / //Delete Other Subsections
                        $allsectionsid = Section::where("mainsection_id", $request->sectionId)->pluck("id");
                        for($i=0; $i<count($allsectionsid); $i++)
                        {
                            if(!in_array($allsectionsid[$i], $subsectionids))
                            {
                                Section::where("id", $allsectionsid[$i])->forceDelete();
                            }
                        }

                        //Delate Unused Images
                        $section_images_dir = public_path('/images/sections/');

                        $uploaded_images = array_diff(scandir($section_images_dir), array('.', '..')); 

                        $usedimgs = Mainsection::all()->pluck("section_img")->toArray();
                
                        $usedfinal= array_diff($uploaded_images, $usedimgs);
                            
                        foreach($usedfinal as $img)
                        {
                            if(\File::exists(public_path('/images/sections/'.$img)))
                            {
                                \File::delete(public_path('/images/sections/'.$img));
                            }
                        }

                        for($i=0; $i < count($subsectionnames); $i++)
                        {
                            if(trim($subsectiondescs[$i]) == "لا يوجد" || trim($subsectiondescs[$i]) == "لايوجد")
                            {
                                $subsectiondesc = "";
                            }
                            else
                            {
                                $subsectiondesc = $subsectiondescs[$i];
                            }
                            if(preg_match("/[a-z]/i", $subsectionids[$i]))
                            {
                                Section::create(
                                [
                                    "section_name" => $subsectionnames[$i],
                                    "mainsection_id" => $request->sectionId,
                                    "description" => $subsectiondesc,
                                ]);
                            }
                            else
                            {
                                Section::where("mainsection_id", $request->sectionId)->where("id", $subsectionids[$i])->update(
                                [
                                    "section_name" => $subsectionnames[$i],
                                    "mainsection_id" => $request->sectionId,
                                    "description" => $subsectiondesc,
                                ]);
                            }
                        }

                        //Delete Unwanted Sections / //Delete Other Subsections
                        $allsectionsid = Section::where("mainsection_id", $request->sectionId)->pluck("id");
                        for($i=0; $i<count($allsectionsid); $i++)
                        {
                            if(!in_array($allsectionsid[$i], $subsectionids))
                            {
                                Section::where("id", $allsectionsid[$i])->forceDelete();
                            }
                        }
                        

                        return "success";
                        
                    }
                    {
                        return "subsectionsmissingsomething";
                    }

                }
                else
                {
                    return "nosectionname";
                }
                
            }
            //If Request Does Not Has Image
            else
            {
                //Check If Section Name Empty
                if(strlen(trim($request->msectionname)) > 0)
                {
                    //Check If Section Name Is Used Before
                    //Get Current Section Name
                    $curr_sec_nm = Mainsection::where("id", $request->sectionId)->first()->msection_name;
                    $name_chk = Mainsection::where("msection_name", $request->msectionname)->first();

                    if($curr_sec_nm != $request->msectionname)
                    {
                        if($name_chk != "")
                        {
                            return "usedsectionname";
                        }
                    }
                    //Check If subsection names == subsection descs
                    if(count($subsectionnames) == count($subsectiondescs) && count($subsectionnames) > 0)
                    {
                        

                        //Modify Main Secion Into DB
                        Mainsection::where("id", $request->sectionId)->update(
                        [
                            "msection_name" => $request->msectionname,
                            "description" => $request->msectiondesc,
                        ]);
                        
                        //Delete Unwanted Sections / //Delete Other Subsections
                        $allsectionsid = Section::where("mainsection_id", $request->sectionId)->pluck("id");
                        for($i=0; $i<count($allsectionsid); $i++)
                        {
                            if(!in_array($allsectionsid[$i], $subsectionids))
                            {
                                Section::where("id", $allsectionsid[$i])->forceDelete();
                            }
                        }
                        for($i=0; $i < count($subsectionnames); $i++)
                        {
                            if(trim($subsectiondescs[$i]) == "لا يوجد" || trim($subsectiondescs[$i]) == "لايوجد")
                            {
                                $subsectiondesc = "";
                            }
                            else
                            {
                                $subsectiondesc = $subsectiondescs[$i];
                            }

                            if(preg_match("/[a-z]/i", $subsectionids[$i]))
                            {
                                Section::create(
                                [
                                    "section_name" => $subsectionnames[$i],
                                    "mainsection_id" => $request->sectionId,
                                    "description" => $subsectiondesc,
                                ]);
                            }
                            else
                            {
                                Section::where("mainsection_id", $request->sectionId)->where("id", $subsectionids[$i])->update(
                                [
                                    "section_name" => $subsectionnames[$i],
                                    "mainsection_id" => $request->sectionId,
                                    "description" => $subsectiondesc,
                                ]);
                            }

                            
                        }


                        return "success";
                        
                    }
                    {
                        return "subsectionsmissingsomething";
                    }   

                }
                else
                {
                    return "nosectionname";
                }
            }
        }
        else
        {
            return "subsectionsmissingsomething";
        }
          
    }

    public function section_delete($id)
    {
        //Delete Section
        Mainsection::where("id", $id)->forceDelete();

        //Delete Subsections
        Section::where("mainsection_id", $id)->forceDelete();

        //Delete Products - Added Later

        return "deleted";
    }

    public function savenewsection(Request $request)
    {
        if(strlen($request->subsectionnames) > 0 || strlen($request->subsectiondescs) > 0)
        {
            //Prepare Main Variables
            $subsectionnames = $request->subsectionnames;
            $subsectiondescs = $request->subsectiondescs;
            
            $subsectionnames = array_filter(explode("_xXnextarrayelxX_", $subsectionnames));
            $subsectiondescs = array_filter(explode("_xXnextarrayelxX_", $subsectiondescs));

            //If Request Has Image
            if($request->hasFile('sectionimg'))
            {
                $destinationPath = public_path( '/images/sections/');
                $image = $request->sectionimg;
                $imageName = time() . '.'.$image->extension();
                $image->move($destinationPath, $imageName);

                //Check If Section Name Empty
                if(strlen(trim($request->msectionname)) > 0)
                {
                    //Check If Section Name Is Used Before
                    $name_chk = Mainsection::where("msection_name", $request->msectionname)->first();
                    if($name_chk == "" || $name_chk == NULL)
                    {
                        //Check If subsection names == subsection descs
                        if(count($subsectionnames) == count($subsectiondescs) && count($subsectionnames) > 0)
                        {

                            //Modify Main Secion Into DB
                            Mainsection::create(
                            [
                                "msection_name" => $request->msectionname,
                                "description" => $request->msectiondesc,
                                "section_img" => $imageName,
                            ]);

                            //Delete Unwanted Sections / //Delete Other Subsections
                            $allsectionsid = Section::where("mainsection_id", $request->sectionId)->pluck("id");
                            for($i=0; $i<count($allsectionsid); $i++)
                            {
                                if(!in_array($allsectionsid[$i], $subsectionids))
                                {
                                    Section::where("id", $allsectionsid[$i])->forceDelete();
                                }
                            }

                            //Delate Unused Images
                            $section_images_dir = public_path('/images/sections/');

                            $uploaded_images = array_diff(scandir($section_images_dir), array('.', '..')); 

                            $usedimgs = Mainsection::all()->pluck("section_img")->toArray();
                    
                            $usedfinal= array_diff($uploaded_images, $usedimgs);
                             
                            foreach($usedfinal as $img)
                            {
                                if(\File::exists(public_path('/images/sections/'.$img)))
                                {
                                    \File::delete(public_path('/images/sections/'.$img));
                                }
                            }

                            $msection_id = Mainsection::where("msection_name", $request->msectionname)->first();
                            for($i=0; $i < count($subsectionnames); $i++)
                            {
                                if(trim($subsectiondescs[$i]) == "لا يوجد" || trim($subsectiondescs[$i]) == "لايوجد")
                                {
                                    $subsectiondesc = "";
                                }
                                else
                                {
                                    $subsectiondesc = $subsectiondescs[$i];
                                }
                                
                                Section::create(
                                [
                                    "section_name" => $subsectionnames[$i],
                                    "mainsection_id" => $msection_id->id,
                                    "description" => $subsectiondesc,
                                ]);
 
                            }

                            //Delete Unwanted Sections / //Delete Other Subsections
                            $allsectionsid = Section::where("mainsection_id", $request->sectionId)->pluck("id");
                            for($i=0; $i<count($allsectionsid); $i++)
                            {
                                if(!in_array($allsectionsid[$i], $subsectionids))
                                {
                                    Section::where("id", $allsectionsid[$i])->forceDelete();
                                }
                            }
                            

                            return "success";
                            
                        }
                        {
                            return "subsectionsmissingsomething";
                        }
                    }
                    else
                    {
                        return "usedsectionname";
                    }    

                }
                else
                {
                    return "nosectionname";
                }
                
            }
            //If Request Does Not Has Image
            else
            {
                //Check If Section Name Empty
                if(strlen(trim($request->msectionname)) > 0)
                {
                    //Check If Section Name Is Used Before
                    $name_chk = Mainsection::where("msection_name", $request->msectionname)->first();
                    if($name_chk == "" || $name_chk == NULL)
                    {
                        //Check If subsection names == subsection descs
                        if(count($subsectionnames) == count($subsectiondescs) && count($subsectionnames) > 0)
                        {
                            

                            //Modify Main Secion Into DB
                            Mainsection::create(
                            [
                                "msection_name" => $request->msectionname,
                                "description" => $request->msectiondesc,
                            ]);
                            
                            //Delete Unwanted Sections / //Delete Other Subsections
                            $allsectionsid = Section::where("mainsection_id", $request->sectionId)->pluck("id");
                            for($i=0; $i<count($allsectionsid); $i++)
                            {
                                if(!in_array($allsectionsid[$i], $subsectionids))
                                {
                                    Section::where("id", $allsectionsid[$i])->forceDelete();
                                }
                            }
                            $msection_id = Mainsection::where("msection_name", $request->msectionname)->first();
                            for($i=0; $i < count($subsectionnames); $i++)
                            {
                                if(trim($subsectiondescs[$i]) == "لا يوجد" || trim($subsectiondescs[$i]) == "لايوجد")
                                {
                                    $subsectiondesc = "";
                                }
                                else
                                {
                                    $subsectiondesc = $subsectiondescs[$i];
                                }

                                Section::create(
                                [
                                    "section_name" => $subsectionnames[$i],
                                    "mainsection_id" => $msection_id->id,
                                    "description" => $subsectiondesc,
                                ]);
                                
                            }


                            return "success";
                            
                        }
                        {
                            return "subsectionsmissingsomething";
                        }
                    }
                    else
                    {
                        return "usedsectionname";
                    }    

                }
                else
                {
                    return "nosectionname";
                }
            }
        }
        else
        {
            return "subsectionsmissingsomething";
        }
    }

    public function getmainsections()
    {
        $first_arr = Mainsection::all()->pluck("msection_name");
        $second_arr = Mainsection::all()->pluck("id");

        $total = [];
        for($i=0; $i<count($first_arr); $i++)
        {
            $total[$first_arr[$i]] = $second_arr[$i];
        }

        return $total;
    }
    public function getbrsections($id)
    {
        $first_arr = Section::where("mainsection_id", $id)->pluck("section_name");
        $second_arr = Section::where("mainsection_id", $id)->pluck("id");

        $total = [];
        for($i=0; $i<count($first_arr); $i++)
        {
            $total[$first_arr[$i]] = $second_arr[$i];
        }

        return $total;
    }

    //Get SubSections From Main Section ID
    public function getsubsections($msectionid)
    {

        $first_arr = Section::where("mainsection_id", $msectionid)->pluck("section_name");
        $second_arr = Section::where("mainsection_id", $msectionid)->pluck("id");

        $total = [];
        for($i=0; $i<count($first_arr); $i++)
        {
            $total[$first_arr[$i]] = $second_arr[$i];
        }

        return $total;
    }

}
