<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Setting;

class Siteup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $chk = Setting::where("id", 1)->first()->sitestatus;

        if($chk == 1)
        {
            if(Auth::guard("admin")->check())
            {
                return $next($request);
            }
            
            return redirect(route("index.index"));
        }

        return $next($request);
    }
}
