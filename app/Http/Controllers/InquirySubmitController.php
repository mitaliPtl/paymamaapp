<?php

namespace App\Http\Controllers;
use App\Complaint;
use App\BalanceRequest;
use App\KycDetail;
use App\Role;
use App\TransactionDetail;
use App\ApiSetting;
use App\User;
use App\OperatorSetting;
use App\OffersNotice;
use App\Http\Controllers;
use Auth;
use DB;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\UserLoginSessionDetail;
use Illuminate\Support\Facades\Http;


class HomeController extends Controller
{
	public function inquirysubmit(Request $request)
	{
		return $inquiryinsert=DB::table('tbl_inquiry')->insert([
			"name"=>$request->name,
			"mobile"=>$request->number,
			"email"=>$request->email,
			"identity"=>$request->identity,
			"message"=>$request->message
		]);
	}
}

?>