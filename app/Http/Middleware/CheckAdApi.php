<?php

namespace App\Http\Middleware;

use App\User;
use App\UserLoginSessionDetail;
use Closure;
use Config;
use Illuminate\Http\Request;

class CheckAdApi
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
        $userLgInSession = UserLoginSessionDetail::where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)->first();

        $user = User::where('userId', $request->user_id)
            ->where('roleId', $request->role_id)->first();
        if (isset($userLgInSession) && $userLgInSession->apiKey == $request->token && $user && $request->role_id == Config::get('constants.ADMIN')) {
            return $next($request);
        } else {
            return redirect('/permission-forbidden');
        }
    }
}
