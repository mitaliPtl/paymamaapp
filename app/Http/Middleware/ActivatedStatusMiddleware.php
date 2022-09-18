<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
use Config;
use DB;


class ActivatedStatusMiddleware extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if( Auth::user()->activated_status == 'YES') {
            // user value cannot be found in session
            return route('login');
        }
        
       // if (! $request->expectsJson()) {
         //   return route('login');
        //}
    }
}
