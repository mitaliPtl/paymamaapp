<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;
use Session;
use Route;

class CheckPayment
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
        // Session::put('payment_status',$request->path() );
        if( (Session::has('payment_status')) && ($request->path() != 'transfer_money'))  {
            Session::forget('payment_status');
        }
        return $next($request);
    }
}
