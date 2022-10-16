<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;

class CheckDTRT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ((Auth::check() && Auth::user()->roleId == Config::get('constants.DISTRIBUTOR')) || (Auth::check() && Auth::user()->roleId == Config::get('constants.RETAILER')) || (Auth::check() && Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR')) || (Auth::check() && Auth::user()->roleId == Config::get('constants.ADMIN'))) {
            return $next($request);
        }
        return redirect('/permission-denied');
    }
}
