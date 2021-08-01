<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\membersController;
use App\Http\Controllers\shipmentsController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\mainController;
use App\Http\Controllers\sectionsController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\paymentprocessorController;
use App\Http\Controllers\pagesController;
use App\Http\Controllers\settingsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\member;
use App\Http\Middleware\admin;
use App\Http\Middleware\Siteup;
use App\Http\Middleware\Sitedown;
use App\Http\Middleware\MBGA; //Must Not Be Admin
use App\Http\Middleware\MBGM; //Must Not Be Member
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Under Maintenance Layer

// Must Not Be Admin
Route::middleware([MBGA::class])->group(function ()
{
    //ADMIN LOGIN
    Route::get('/dashboardlogin', [settingsController::class, 'dashboardlogin'])->name("admin.login");
    Route::post('/dashboardloginproc', [settingsController::class, 'dashboardloginproc']);
});

Route::middleware([Sitedown::class])->group(function()
{
    /////////////// General Routes ///////////////////
    //MAIN
    Route::get('/', [mainController::class, 'index'])->name("index.index");
    Route::get('/getwebsitename', [settingsController::class, 'getwebsitename']);
    Route::get('/getwebsitedescrip', [settingsController::class, 'getwebsitedescrip']);
    Route::get('/getwebsitelink', [settingsController::class, 'getwebsitelink']);
    Route::get('/sociallinks', [settingsController::class, 'getfootersociallinks']);

    //PRODUCTS
    Route::post("/getdiscountprods", [productsController::class, 'discountproducts']);
    Route::get("/getsimilarproducts/{productid}", [productsController::class, 'similarproducts']);
    Route::get("/getproductdata/{productid}", [productsController::class, 'getproduct']);
    Route::get("/getproductcomments/{productid}", [productsController::class, 'getprodcomments']);
    Route::get("/getreviewscount/{productid}", [productsController::class, 'getreviewscount']);

    //SECTIONS
    Route::get("/getsectionproducts/{sectionlastelementid}", [sectionsController::class, 'sectionproducts']);
    Route::get("/getmanus/{sectionid}", [sectionsController::class, 'getprodmanus']);
    Route::post("/filtersectiondata", [sectionsController::class, 'filter_sectiondata']);
    Route::get("/sectionname/{sectionid}", [sectionsController::class, 'get_sectionname']);
    Route::get("/getheadersections", [sectionsController::class, 'get_header_sections']);

    //SEARCH
    Route::get("/getsearchproducts/{searchlastelementid}", [searchController::class, 'searchproducts']);
    Route::get("/getmanus_search/{searchvalue}", [searchController::class, 'getprodmanus_search']);
    Route::post("/filtersearchdata", [searchController::class, 'filter_searchdata']);

    //PAGES
    Route::get("/getpagespublic", [pagesController::class, 'getpagespublic']);
    Route::get("/getpagedetailspublic/{id}", [pagesController::class, 'getpagedetailspublic']);

    //SETTINGS & Checkers
    Route::get("/getcurrency", [settingsController::class, 'getcurrency']);
    Route::get("/chklogin", [membersController::class, 'chkloggedIn']);
    Route::get("/chkadmin", [settingsController::class, 'chkadmin']);

    // Must Not Be Member
    Route::middleware([MBGM::class])->group(function ()
    {
        //MEMBER LOGIN - REGISTER - RESET 
        Route::post("/login", [membersController::class, 'loginproc']);
        Route::post("/register", [membersController::class, 'registerproc']);
        Route::get("/chkregisterstatus", [settingsController::class, 'chkregisterstatus']);
        Route::post("/reset_pass_proc", [membersController::class, 'reset_password']);
        Route::get("/passreset/{token}", [membersController::class, 'reset_password_page']);
        Route::get("/verify/{token}", [membersController::class, 'verify_registration']);
    });

    /////////////// Members Routes ///////////////////
    Route::middleware([member::class])->group(function ()
    {
        //PROFILE
        Route::get("/logout", [membersController::class, 'logout']);
        Route::get("/getloginname", [membersController::class, 'getloginname']);
        Route::post("/upadteprofiledata", [membersController::class, 'updateprofileinfo']);
        Route::get("/getlocations", [membersController::class, 'getlocations']);

        //BASKET
        Route::get("/getmemberdata", [membersController::class, 'getmemberdata']);

        //SHIPPMENTS
        Route::get("/getmembershipments/{member_id}", [shipmentsController::class, 'getshipments']);
        Route::get("/shipment/{shipmentno}", [shipmentsController::class, 'getshipment']);
        Route::get("/shipmentproducts/{prods}", [shipmentsController::class, 'getshipmentproducts']);

        //PRODUCTS
        Route::post("/storecomment", [productsController::class, 'store_comment']);
        Route::post("/storerating", [productsController::class, 'store_rating']);
        Route::post("/cartprods", [productsController::class, 'cartproducts']);

        //PAYMENT PROCCESS
        Route::post("/checkout_process", [paymentprocessorController::class, 'checkout']);
        Route::get("/payment/{checkout_id}", [paymentprocessorController::class, 'checkout_view']);
        Route::post("/after_payment", [paymentprocessorController::class, 'afterpayment']);

        //TICKETS
        Route::post("/openticket", [membersController::class, 'openticket']);
        Route::get("/checkticket/{tid}", [membersController::class, 'checkticket']);
        Route::get("/ticketauthandget/{tid}", [membersController::class, 'ticketauthandget']);
        Route::get("/getsupportinfo", [settingsController::class, 'getsupportinfo']);
    });

    /////////////// Admin Routes ///////////////////
    Route::middleware([admin::class])->group(function ()
    {
        //MAIN PAGE
        Route::get('/dashboard', [settingsController::class, 'dashboard']);

        //MAIN CHARTS
        Route::get('/dailycharts', [settingsController::class, 'dailycharts']);
        //Main Cards Data
        Route::get('/cardsdata', [settingsController::class, 'cardsdata']);

        //SECTIONS
        Route::get("/getallsections", [sectionsController::class, 'getallsectionsids']);
        Route::get("/getsectiondata/{section_id}", [sectionsController::class, 'getsectiondata']);
        Route::get("/geteditsectiondata/{id}", [sectionsController::class, 'geteditsectiondata']);
        Route::post("/saveeditedsection", [sectionsController::class, 'saveeditedsection']);
        Route::get("/section_delete/{id}", [sectionsController::class, 'section_delete']);
        Route::post("/savenewsection", [sectionsController::class, 'savenewsection']);
        Route::get("/getmainsections", [sectionsController::class, 'getmainsections']);
        Route::get("/getbrsections/{id}", [sectionsController::class, 'getbrsections']);

        //PRODUCTS
        Route::post("/savenewproduct", [productsController::class, 'savenewproduct']);
        Route::post("/searchprodbyname", [productsController::class, 'searchprodbyname']);
        Route::post("/searchprodbycode", [productsController::class, 'searchprodbycode']);
        Route::post("/searchprodbyprice", [productsController::class, 'searchprodbyprice']);
        Route::get("/getproddata/{prodid}", [productsController::class, 'getprodata']);
        Route::get("/getsubsections/{msectionid}", [sectionsController::class, 'getsubsections']);
        Route::post("/saveeditedproduct", [productsController::class, 'saveeditedproduct']);
        Route::get("/prod_delete/{id}", [productsController::class, 'deleteproduct']);
        Route::get("/prodvalid/{id}", [productsController::class, 'chkproduct']);
        Route::get("/popularprods", [productsController::class, 'popularproducts']);
        Route::post("/getlowqtyprods", [productsController::class, 'getlowqtyprods']);

        //COMMENTS
        Route::post("/searchcommentsbytext", [productsController::class, 'searchcomments']);
        Route::post("/getunmanagedcomments", [productsController::class, 'getunmanagedcomments']);
        Route::get("/approvecomment/{id}", [productsController::class, 'approvecomment']);
        Route::get("/destroycomment/{id}", [productsController::class, 'destroycomment']);

        //SHIPPMENTS
        Route::get("/getpendingships/{lastshipid}", [shipmentsController::class, 'getpendingships']);
        Route::get("/getpsinfo/{ps}", [shipmentsController::class, 'getpsinfo']);
        Route::get("/getpsinfox/{ps}", [shipmentsController::class, 'getpsinfox']);
        Route::get("/approveship/{id}", [shipmentsController::class, 'approveship']);
        Route::post("/rejecship", [shipmentsController::class, 'rejectshipment']);
        Route::get("/getprepareships/{lastshipid}", [shipmentsController::class, 'getprepareships']);
        Route::get("/getprinfo/{ps}", [shipmentsController::class, 'getprinfo']);
        Route::get("/transship/{id}", [shipmentsController::class, 'transportshippment']);
        Route::post("/searchshipbyshippno", [shipmentsController::class, 'searchshipbyshippno']);
        Route::post("/searchbydatevalue", [shipmentsController::class, 'searchshipbydate']);
        Route::get("/changeshipmentstatus/{idval}", [shipmentsController::class, 'changeshipmentstatus']);
        Route::get("/daycharts", [shipmentsController::class, 'daycharts']);
        Route::get("/weekcharts", [shipmentsController::class, 'weekcharts']);
        Route::get("/monthcharts", [shipmentsController::class, 'monthcharts']);
        Route::get("/yearcharts", [shipmentsController::class, 'yearcharts']);
        Route::post("/getspecreports", [shipmentsController::class, 'getspecreports']);

        //MEMBERS
        Route::post("/searchcustbyid", [membersController::class, 'searchcustbyidfunc']);
        Route::post("/searchcustbyemail", [membersController::class, 'searchcustbyemail']);
        Route::post("/searchcustbyip", [membersController::class, 'searchcustbyip']);
        Route::post("/cust_block", [membersController::class, 'blockcustomer']);
        Route::post("/cust_unblock", [membersController::class, 'unblockcustomer']);
        Route::get("/get_customer_data/{id}", [membersController::class, 'getcustomerdata']);
        Route::post("/get_customer_ships", [membersController::class, 'getcustomerships']);
        Route::post("/getblockedusers", [membersController::class, 'getblockedusers']);
        Route::post("/getblockreason", [membersController::class, 'getblockreason']);
        Route::post("/saveeditedcustomerdash", [membersController::class, 'saveeditedcustomerdash']);

        //TICKETS
        Route::post("/getunansweredtickets", [membersController::class, 'getunansweredtickets']);
        Route::post("/getticketsbydate", [membersController::class, 'getticketsbydate']);
        Route::post("/getticketsbyticketnum", [membersController::class, 'getticketsbyticketnum']);
        Route::get("/ticket_close/{id}", [membersController::class, 'ticket_close']);
        Route::get("/ticket_delete/{id}", [membersController::class, 'ticket_delete']);
        Route::get("/getticketinfo/{id}", [membersController::class, 'getticketinfo']);
        Route::get("/ticket_reopen/{id}", [membersController::class, 'ticketreopen']);
        Route::post("/savesupportreply", [membersController::class, 'savesupportreply']);
        Route::post("/savecustomerrteply", [membersController::class, 'savecustomerrteply']);


        //PAGES
        Route::get("/getpages", [pagesController::class, 'getpages']);
        Route::post("/savepage", [pagesController::class, 'savepage']);
        Route::post("/saveeditedpage", [pagesController::class, 'saveeditedpage']);
        Route::get("/page_delete/{id}", [pagesController::class, 'deletepage']);
        Route::post("/savearrangement", [pagesController::class, 'savearrangement']);
        Route::get("/getpagedetails/{id}", [pagesController::class, 'getpagedetails']);

        //SETTINGS
        Route::post("/savegeneralsettings", [settingsController::class, 'saveapplysettings']);
        Route::get("/getsettings", [settingsController::class, 'getsettings']);
        Route::post("/saveregions", [settingsController::class, 'saveregions']);
        Route::post("/chgadminpassword", [settingsController::class, 'chgadminpassword']);
        Route::post("/savesupportsettings", [settingsController::class, 'savesupportsettings']);

        //LOGOUT
        Route::get("/admin_logout", [settingsController::class, 'admin_logout']);
    });
});

// Maintenance ///
Route::middleware([Siteup::class])->group(function()
{
    Route::get("/maintenance", [settingsController::class, 'maintenance'])->name("maintenance.show");
});