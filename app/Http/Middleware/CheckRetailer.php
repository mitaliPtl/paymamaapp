<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Config;

class CheckRetailer
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
        if (Auth::check() && Auth::user()->roleId != Config::get('constants.RETAILER')) {
            return redirect('/permission-denied');
        }
        return $next($request);
    }
}
