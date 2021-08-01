<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Mainsection;
use Auth;

class mainController extends Controller
{
   public function index()
   {
       return view("welcome");
   } 

}
