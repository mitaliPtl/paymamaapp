<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;
use Session;
use Route;
use DB;
use App\UserLoginSessionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class GeneralMiddleware
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
        // if( (Session::has('payment_status')) && ($request->path() != 'transfer_money'))  {
        //     Session::forget('payment_status');
        // }
        // $requestBody = [
        //     'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
        //     'user_id' => Auth::user()->userId,
        //     'role_id' => Auth::user()->roleId,
        // ];

        // $notification_list = Http::post(Config::get('constants.USER_NOTIFICATION_LIST'), $requestBody);
        // $notification_list = isset($notification_list) && $notification_list ? $notification_list->json() : [];
        $notification_list = DB::table('tbl_notifiaction_log')->where('user_id', Auth::user()->userId)->limit(4)->offset(0)->orderBy('id', 'DESC')->get();
        $notification_list = (count($notification_list) > 0) ? $notification_list : [];
        $notification_count = DB::table('tbl_notifiaction_log')
                                ->where('user_id', Auth::user()->userId)
                                ->where('isViewed', '0')
                                ->count();

        Session::put('user_notification', json_encode($notification_list));
        Session::put('user_notification_count', $notification_count);
        return $next($request);
    }
}
