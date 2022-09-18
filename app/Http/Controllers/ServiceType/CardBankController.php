<?php

namespace App\Http\Controllers\ServiceType;

use App\Http\Controllers\Controller;
use App\ServicesType;
use App\Ccsenders;
use App\Ccbanks;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;
use DB;

class CardBankController extends Controller
{
    public function index()
    {
        return view('modules.card_transfer.mobile');
    }
    
    public function check_mobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobileNumber' => 'required|string|max:10',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please enter required fields to continue');
        }
        $check = Ccsenders::where('mobile',$request->mobileNumber)->count();
        if($check > 0) {
            //directly goto list page
            return redirect()->route('cc_bank_list',['mobile'=>$request->mobileNumber]);
        } else {
            Session::put('mobile',$request->mobileNumber);
            return redirect()->route('card_pan_verify');
        }
    }
    
    public function pan_verify(Request $request)
    {
        if(Session::has('mobile')) {
            return view('modules.card_transfer.pan_verify');
        }
        abort(404);
    }
    
    public function verify_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panNumber' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please enter required fields to continue');
        }
        $response = array('code'=>200,'status'=>'success','response'=>array('pan_no'=>$request->panNumber,'registered_name'=>'SRIKANTH NAIDU TATHI'));
        if ($response['code'] == 200 && $response['status'] == 'success') {
            $name = $response['response']['registered_name'];
            Session::put('pan_name',$name);
            Session::put('pan_no',$request->panNumber);
            $digits = 5;
            $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $username = "NAIDUSOFTWARE";
            $password = "4049102";
            $message = $rand." is your verification code,Helpline : 040-29563154 www.paymamaapp.in";
            Session::put('otp',$rand);
            $sender = "PYMAMA";
            $mobile_number = Session::get('mobile');
            $template_id = '1207163818393765034';
            $url = "https://bulksms.co/sendmessage.php?user=$username&password=$password&mobile=$mobile_number&message=".urlencode($message)."&sender=$sender&type=3&template_id=$template_id";
            $ch = curl_init();  
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
            return redirect()->route('card_otp')->with('success', 'We have sent an OTP to your mobile number.');
        } else {
            return redirect()->back()->with('error', $response['response']);
        }
        exit;
        
        $response = Http::withHeaders([
            'API-KEY' => Config::get('constants.APICLUB_API_KEY'),
            'Referer' => $_SERVER['HTTP_REFERER']
        ])->post('https://api.apiclub.in/api/v1/verify_pan', array('pan_no' => $request->panNumber));
        if($response->successful()) {
            $response = $response->body();
            if ($response['code'] == 200 && $response['status'] == 'success') {
                $name = $response['response']['registered_name'];
                Session::put('pan_name',$name);
                Session::put('pan_no',$request->panNumber);
                $digits = 5;
                $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
                $username = "NAIDUSOFTWARE";
                $password = "4049102";
                $message = $rand." is your verification code,Helpline : 040-29563154 www.paymamaapp.in";
                Session::put('otp',$rand);
                $sender = "PYMAMA";
                $mobile_number = Session::get('mobile');
                $template_id = '1207163818393765034';
                $url = "https://bulksms.co/sendmessage.php?user=$username&password=$password&mobile=$mobile_number&message=".urlencode($message)."&sender=$sender&type=3&template_id=$template_id";
                $ch = curl_init();  
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $output=curl_exec($ch);
                curl_close($ch);
                return redirect()->route('card_otp')->with('success', 'We have sent an OTP to your mobile number.');
            } else {
                return redirect()->back()->with('error', $response['response']);
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    
    public function card_otp() {
        if(Session::has('success')) {
            return view('modules.card_transfer.otp');
        }
        return abort(404);
    }
    
    public function validate_otp(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|max:5',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please enter required fields to continue');
        }
        if(Session::has('otp')) {
            if(Session::get('otp') == $request->otp) {
                $mobile = Session::get('mobile');
                $data = [
                    'user_id' => Auth::user()->userId,
                    'name' => Session::get('pan_name'),
                    'mobile' => $mobile,
                    'pan' => Session::get('pan_no'),
                    'trans_limit' => 5000
                ];
                $insert = Ccsenders::insert($data);
                Session::forget('otp');
                Session::forget('pan_name');
                Session::forget('mobile');
                Session::forget('pan_no');
                if($insert) {
                    return redirect()->route('cc_bank_list',['mobile'=>$mobile]);
                } else {
                    return redirect()->back()->with('error', 'Something went wrong');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid OTP please try again');
            }
        }
        return redirect()->back()->with('error', 'Please resend OTP to continue');
    }
    
    public function resend_otp(Request $request) {
        $digits = 5;
        $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $username = "NAIDUSOFTWARE";
        $password = "4049102";
        $message = $rand." is your verification code,Helpline : 040-29563154 www.paymamaapp.in";
        Session::forget('otp');
        Session::put('otp',$rand);
        $sender = "PYMAMA";
        $mobile_number = Session::get('mobile');
        $template_id = '1207163818393765034';
        $url = "https://bulksms.co/sendmessage.php?user=$username&password=$password&mobile=$mobile_number&message=".urlencode($message)."&sender=$sender&type=3&template_id=$template_id";
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        return redirect()->route('card_otp')->with('success', 'We have sent an OTP to your mobile number.');
    }
    
    public function cc_bank_list($mobile)
    {
        $ccsender = Ccsenders::where('mobile',$mobile)->first();
        if($ccsender) {
            $ccbanks = Ccbanks::where('cc_sender_id',$ccsender->id)->get();
            return view("modules.card_transfer.bank_list", compact('ccbanks', 'ccsender'));
        } else {
            return redirect()->route('card_bank')->with('error', 'Mobile number not found');
        }
    }
    
    public function newBeneficiary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|max:10',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please enter required fields to continue');
        }
        $ccsender = Ccsenders::where('mobile',$request->mobile_no)->first();
        if($ccsender) {
            $ccbanks = Ccbanks::where('cc_sender_id',$ccsender->id)->get();
        } else {
            return redirect()->back()->with('error', 'Invalid data');
        }
        $banks = $this->getAllBankList();
        // return $banks;
        return view("modules.card_transfer.add_beneficiary", compact('ccbanks', 'banks','request'));
    }
    
    public function addBeneficiary(Request $request)
    {
        return $request->all();
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|max:10',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please enter required fields to continue');
        }
        $ccsender = Ccsenders::where('mobile',$request->mobile_no)->first();
        if($ccsender) {
            $ccbanks = Ccbanks::where('cc_sender_id',$ccsender->id)->get();
        } else {
            return redirect()->back()->with('error', 'Invalid data');
        }
        $banks = $this->getAllBankList();
        // return $banks;
        return view("modules.card_transfer.add_beneficiary", compact('ccbanks','banks'));
    }
    
    public function getAllBankList()
    {
        $bank_list = DB::table('tbl_bank_list')->orderBy(trim('BANK_NAME'),'ASC')->get();
        $bank_array = [];
        foreach ($bank_list as $key => $val) {
           $bank_array[$key] =  [   "bank_id"=>trim($val->BankID),
                                    "bank_name"=>trim($val->BANK_NAME),
                                    "bank_code"=>trim($val->ShortCode),
                                    "bank_icon"=>$val->bank_icon,
                                    "neft_allowed"=>(trim($val->NEFT_Status)=="Enabled")?'Y':'N',
                                    "imps_allowed"=>(trim($val->IMPS_Status)=="Enabled")?'Y':'N',
                                    "account_verification_allowed"=>(trim($val->IsVerficationAvailable)=="On")?'Y':'N',
                                    "ifsc_prefix"=>$val->ifsc_prefix,
                                ];
        }

        $response['result']['bank_list'] = $bank_array;

        return $response;
    }
    
}