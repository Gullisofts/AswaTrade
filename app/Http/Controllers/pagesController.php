<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class pagesController extends Controller
{
    //GET ALL PAGES LIST
    public function getpages()
    {
        return Page::orderBy("position", "ASC")->get(); 
    }

    //GET ALL PAGES LIST - Public
    public function getpagespublic()
    {
        return Page::orderBy("position", "ASC")->get(); 
    }
    //GET Page Details - Public
    public function getpagedetailspublic($id)
    {
        return Page::where("id", $id)->first()->content; 
    }
    
    //SAVE NEW PAGE
    public function savepage(Request $request)
    {
        $content = trim($request->content);
        $subject = trim($request->subject);

        //Validations
        if($content == NULL || $content == "")
        {
            return "nocontent";
        }
        if($subject == NULL || $subject == "")
        {
            return "nosubject";
        }

        $chk = Page::where("title", $subject)->first();

        if($chk != NULL || $chk != "")
        {
            return "alreadyexist";
        }

        //Get Last Position
        $last = Page::all()->count();

        //Save Page
        Page::create(
        [
            "title" => $subject,
            "content" => $content,
            "position" => $last+1,
        ]);

        return "success";
    }

    //GET PAGE Details To Edit
    public function getpagedetails($id)
    {
        return Page::where("id", $id)->first();
    }

    //SAVE EDITED PAGE
    public function saveeditedpage(Request $request)
    {
        $content = trim($request->content);
        $subject = trim($request->subject);
        $pageid = trim($request->pageid);

        //Validations
        if($content == NULL || $content == "")
        {
            return "nocontent";
        }
        if($subject == NULL || $subject == "")
        {
            return "nosubject";
        }

        $originalpagetitle = Page::where("id", $pageid)->first()->title;

        $chk = Page::where("title", $subject)->first();

        if($chk != NULL || $chk != "")
        {
            if($subject != $originalpagetitle)
            {
                return "alreadyexist";
            }
        }

        //Save Page
        Page::where("id", $pageid)->update(
        [
            "title" => $subject,
            "content" => $content,
        ]);

        return "success";
    }

    //Delete Page
    public function deletepage($id)
    {
        Page::where("id", $id)->forceDelete();

        return "success";
    }

    //Save Arrangement
    public function savearrangement(Request $request)
    {
        for($i=0; $i<count($request->arrange); $i++)
        {
            Page::where("id", $request->arrange[$i])->update(
            [
                "position" => $i+1,
            ]);
        }

        return "success";
    }
}
