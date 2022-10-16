<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;

class CheckMasterDistributor
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
        if (Auth::check() && Auth::user()->roleId != Config::get('constants.MASTER_DISTRIBUTOR')) {
            return redirect('/permission-denied');
        }
        return $next($request);
    }
}
