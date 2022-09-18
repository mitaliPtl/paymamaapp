<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BankAccount;
use App\User;
use Config;
use Auth;
use DB;
use App\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Packages\Cashfree\CfAutoCollect;

class BankAcController extends Controller
{
    /**
     * Bank Account View 
     */
    public function index() {
        $user = User::find((int) Auth::user()->userId);
        /*if( Auth::user()->roleId != Config::get('constants.ADMIN')) {    
            if($user->va_id == "") {
                $this->create_va(Auth::user()->userId);
            }
            $user = User::find((int) Auth::user()->userId);
        }*/
        $va = array(
         'bank_name' => 'YES BANK',
         'ifsc_code' => $user->va_ifsc_code,
         'account_number' => $user->va_account_number,
         'account_holder' => 'PAYMAMA',
         'upi_address' => $user->va_upi_id
        );
        /*if( Auth::user()->roleId != Config::get('constants.ADMIN')) {
            
            $key = Config::get('constants.RAZORPAY_KEY');
            $secret = Config::get('constants.RAZORPAY_SECRET');
            $user = User::where('userId' , Auth::user()->userId)->get();
            if(!empty($user[0]['account_number']) && !empty($user[0]['ifsc_code']) && !empty($user[0]['upi_id'])) {
                
                $response = Http::withBasicAuth($key, $secret)->post('https://api.razorpay.com/v1/virtual_accounts',[
                    'receivers' => [
                        'types' => [
                            'bank_account',
                            'vpa'
                        ]
                    ],
                    'notes' => [
                        'store_name' => Auth::user()->store_name,
                        'username' => Auth::user()->username
                    ],
                    'close_by' => 2147483647
                ]);
                if($response->status() == 200) {
                    $body = $response->json();
                } else {
                    // print_r($response->status());
                    return back()->with('error', "Something went wrong !!");
                }
                
                // $acc_name = $body['id'];
                $bank_info = $body['receivers'][0];
                $upi_info = $body['receivers'][1];
                
                $bank_name = $bank_info['bank_name'];
                $ifsc_code = $bank_info['ifsc'];
                $acc_number = $bank_info['account_number'];
                $holder_name = $bank_info['name'];
                $upi_id = $upi_info['address'];
                
                $update_user = User::where('userId', Auth::user()->userId)->update([
                    'account_number' => $acc_number,
                    'ifsc_code' => $ifsc_code,
                    'upi_id' => $upi_id,
                    'va_id' => $body['id'],
                    'updatedDtm'=>now()
                ]);
            } else {
                $bank_name = 'RBL Bank';
                $ifsc_code = $user[0]['ifsc_code'];
                $acc_number = $user[0]['account_number'];
                $holder_name = 'SMARTPAY TECHNOLOGIES';
                $upi_id = $user[0]['upi_id'];
                
            }
             $va = array(
                 'bank_name' => $bank_name,
                 'ifsc_code' => $ifsc_code,
                 'account_number' => $acc_number,
                 'account_holder' => $holder_name,
                 'upi_address' => $upi_id
            );
        }*/
        
        $bank_acc = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
            
        $bankAccounts  = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        $toatl_amt = $this->totalAmount($bankAccounts);

        return view('modules.bank.bank_account',compact('bankAccounts', 'bank_acc', 'toatl_amt', 'va'));
    }
    
    public function create_va($userId)
    {
        include_once(app_path() .'/Packages/Cashfree.php');
        $user = User::find((int) $userId);
        $username = $user->username;
        $name = $user->first_name. " ".$user->last_name;
        $phone = $user->mobile;
        // $phone=rand(1111111111,9999999999);
        $email = $user->email;
        $account_no = $user->account_number;
        $ifsc = $user->ifsc_code;
        $store_name = $user->store_name;
        $clientId = Config::get('constants.CASHFREE_COLLECT_KEY');
        $clientSecret = Config::get('constants.CASHFREE_COLLECT_SECRET');
        $stage = "PROD"; //TEST/PROD
        $authParams["clientId"] = $clientId;
        $authParams["clientSecret"] = $clientSecret;
        $authParams["stage"] = $stage;
        try {
            $autoCollect = new CfAutoCollect($authParams);
        } catch (Exception $e) {
            return $e;
        }
        $min = 1000;
        $max = 10000;
        if($autoCollect) {
            $vid = rand(0000,99999999);
            $acc['vAccountId'] = $vid;
            $vpa['virtualVpaId'] = rand(1111111111,9999999999);
            $account['name'] = $name;
            $account['email'] = $email;
            $account['phone'] = $phone;
            $account['remitterAccount'] = $account_no;
            $account['remitterIfsc'] = $ifsc;
            $acc['minAmount'] = $min;
            $acc['maxAmount'] = $max;
            $resp_acc = $autoCollect->createVirtualAccount(array_merge($acc,$account));
            $resp_vpa = $autoCollect->createVirtualAccount(array_merge($vpa,$account));
            Log::info('Cashfree Bank Request: '.json_encode(array_merge($acc,$account)));
            Log::info('Cashfree VPA Request: '.json_encode(array_merge($vpa,$account)));
            Log::info('Cashfree Bank Response: '.json_encode($resp_acc));
            Log::info('Cashfree VPA Response: '.json_encode($resp_vpa));
            if($resp_acc['status'] == 'SUCCESS' && $resp_acc['subCode'] == 200 && $resp_vpa['status'] == 'SUCCESS' && $resp_vpa['subCode'] == 200) {
                $qr_id = "";
                $data = 'name='.$store_name.'&vpa='.$resp_vpa['data']['vpa'].'&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama&'; //customtemplate id given by apiclub
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.apiclub.in/api/v1/generate_qr',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => $data,
                  CURLOPT_HTTPHEADER => array(
                      'Referer: '.Config::get('constants.WEBSITE_BASE_URL'),
                      'API-KEY: '.Config::get('constants.APICLUB_API_KEY'),
                      'Content-Type: application/x-www-form-urlencoded'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                $resp = json_decode($response,true);
                if(isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {
                    $qr_id = $resp['response']['qr_id'];
                }
                
                $vdata = array(
                    'va_id' => $vid,
                    'va_account_number' => $resp_acc['data']['accountNumber'],
                    'va_ifsc_code' => $resp_acc['data']['ifsc'],
                    'va_upi_id' => $resp_vpa['data']['vpa'],
                    'qr_id' => $qr_id,
                );
                $user_info = User::where('username', $username)->get()->first();
                $user_info->va_id = $vid;
                $user_info->va_account_number = $resp_acc['data']['accountNumber'];
                $user_info->va_ifsc_code = $resp_acc['data']['ifsc'];
                $user_info->va_upi_id = $resp_vpa['data']['vpa'];
                $user_info->qr_id = $qr_id;
                if($user_info->save()) {
                    return $vdata;
                } else {
                    return "No";
                }
            } else {
                return $resp_vpa;
            }
        }
        return "NA";
    }

    

    /**
     * Store Bank Account 
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('id')) {

            $resultById = BankAccount::find((int) $request->get('id'));

            $resultById->bank_name = $request->get('bank_name');
            $resultById->account_no = $request->get('account_no');
            $resultById->ifsc_code = $request->get('ifsc_code');
            $resultById->address = $request->get('address');

            $response = $resultById->save();

            $action_message = "Service Type Updated";
        } else {
            $response = BankAccount::create([
                'bank_name' => $request->get('bank_name'),
                'account_no' => $request->get('account_no'),
                'ifsc_code' => $request->get('ifsc_code'),
                'address' => $request->get('address'),
            ]);
            $action_message = "Service Type Added";
        }

        if ($response) {
            return redirect('/bank_account')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = BankAccount::where('id', $request->get('id'))->first();
        return $resultById;
    }
     public function deletebank($id)
    {
        
        $resultById = DB::table('tbl_bank_list')->where('BankID', $id)->delete();
        $action_message = "Bank Deleted";
        return back()->with('success', $action_message);
    }

    /**
     * Change Operator active status
     */
    public function changeActiveStatus(Request $request)
    {
        $id = $request->get('id');
        $activeStatus = $request->get('status');

        $setStatus = Config::get('constants.IN-ACTIVE');
        if ($activeStatus == 'true') {
            $setStatus = Config::get('constants.ACTIVE');
        }

        $result = false;
        if ($id) {
            $resultById = BankAccount::find((int) $id);
            $resultById->activated_status = $setStatus;
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change Service Type delete status
     */
    public function changeDeleteStatus(Request $request)
    {
        $id = $request->get('id');

        $result = false;
        if ($id) {
            $resultById = BankAccount::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Get all Bank Accounts Api
     */
    public function getBankAccoounts(Request $request)
    {
        $bankAcs = BankAccount::select(['id', 'bank_name', 'bank_icon','account_no','ifsc_code','address'])->where('is_deleted', Config::get('constants.NOT-DELETED'))->get()->toArray();
        $user = User::find((int) $request->user_id);
        /*if($user->va_id == "") {
            $this->create_va($user->userId);
        }*/
        $user = User::find((int) $request->user_id);
        $va[] = array(
            "id" => 0,
            "bank_name" => "YES BANK (Virtual Account)",
            "bank_icon" => "https://paymamaapp.in/public/storage/uploads/rbl_logo.png",
            "account_no" => $user->va_account_number,
            "ifsc_code" => $user->va_ifsc_code,
            "address" => ""
        );
        $whitelisted[] = array(
            "id" => 1,
            "bank_name" => "Whitelisted Account",
            "bank_icon" => "",
            "account_no" => $user->account_number,
            "ifsc_code" => $user->ifsc_code,
            "address" => ""
        );
        $merge = array_merge($va,array_merge($bankAcs,$whitelisted));
        
        $statusMsg = "Success!!";
        if ($bankAcs) {
            return $this->sendSuccess($merge, $statusMsg);
        } else {
            return $this->sendError($merge, $statusMsg);
        }
    }


    public function addMoney(Request $request){
        // print_r($request->all());

        // print_r($bank_dtls[0]['balance']);
        // exit();
        if($request->bank_acc == 'Wallet'){

            $user_id =Auth::user()->userId;
            $updated_blnc = $this->update_UserBalance($user_id, $request->amount);

            if($updated_blnc){
                return back()->with('success', "Wallet Balance Updated Successfully !!");
            }else{
                return back()->with('error', "Wallet Balance Not Updated !!");
            }
        }else{

             //upadate balance
            $bank_dtls = BankAccount::select('balance')->where('id', $request->bank_acc)->get();
        
            if($bank_dtls){
                //check bank balance
                $new_blnc = (float) $bank_dtls[0]['balance'] + (float) $request->amount;
                $bnk_blnce = BankAccount::where('id', $request->bank_acc)->update([ "balance" =>$new_blnc ]);

                if($bnk_blnce){
                    return back()->with('success', "Money Added Successfully !!");
                }
            }
        }

       
        return back()->with('error' ,"Money Not Added !!");
    }

    public function getBalanceByUser($user_id){

        
        $getbalance = User::where('userId' , $user_id)->get();
        return $getbalance[0]['wallet_balance'];

    }
    
    public function update_UserBalance($user_id, $amount){
        $user_blnc = $this->getBalanceByUser($user_id);

        $balance = (float)$user_blnc + (float)$amount;

        $update_balance = User::where('userId', $user_id)
                                ->update([
                                    'wallet_balance' => $balance,
                                    'updatedDtm'=>now()
                                ]);
        return $update_balance;

    }

    public function totalAmount($bnk_acnt){

        $total = 0.000;

        if (count($bnk_acnt)>0) {
           foreach ($bnk_acnt as $key => $value) {
              $total = (float) $total + (float) $value['balance'];
           }
        }

        return $total;
    }

    public function uploadLogo(Request $request){
        // print_r($request->all());

        $select_icon = File::where('id', $request->logo_id)->get();
        $img_path = Config::get('constants.WEBSITE_BASE_URL').$select_icon[0]['file_path'];
       
        $update_icon = BankAccount::where('id', $request->acc_id)->update([ 'bank_icon' =>  $img_path ]);
       
        if($update_icon){
            return back()->with('success', 'Icon is Updated Succesfully!!!');
        }else{
            return back()->with('error', 'Failed... Icon is Not Updated !!');

        }
    }

    public function getAllBanks(Request $request){
        // $all_banks = DB::table('tbl_bank_list')->get();
        $all_banks = DB::table('tbl_bank_list')->orderBy('BANK_NAME', 'asc')->get();
        $all_banks = (count($all_banks) > 0) ? $all_banks : [];
        // print_r($all_banks);
        return view('modules.bank.bank_list',compact('all_banks'));
    }
    public function editBank(Request $request)
    {
        // print_r($request->get('id'));
        $resultById = DB::table('tbl_bank_list')->where('BankID', $request->get('id'))->first();
        // print_r($resultById);
        return json_encode($resultById);
    }

    public function addUpdateBannk(Request $request){
        print_r($request->all());

        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:255',
            'ifsc_prefix' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('bank_id')) {

            // $resultById = BankAccount::find((int) $request->get('bank_id'));
            $resultById = DB::table('tbl_bank_list')->where('BankID', $request->get('bank_id'))->first();
            if ($resultById) {
                $update_bank =  DB::table('tbl_bank_list')->where('BankID', $request->get('bank_id'))
                                        ->update(['BANK_NAME' => $request->get('bank_name'),
                                                    'ShortCode' => $request->get('shortcode'),
                                                    'ifsc_prefix'=> $request->get('ifsc_prefix') ]);
                $action_message = "Bank Info Updated";
                return back()->with('success', $action_message);
            }else {
                $action_message = "BANK NOT FOUND";
                return back()->with('error', $action_message);
            }
           
           

            
        } else {

            $inser_bank = DB::table('tbl_bank_list')->insert([
                                                'BANK_NAME' => $request->get('bank_name'),
                                                'ShortCode' => $request->get('shortcode'),
                                                'ifsc_prefix'=> $request->get('ifsc_prefix')
                                            ]);

            
            $action_message = "Bank Added";
            return back()->with('success', $action_message);
        }

        
    }

    public function uploadBankLogo(Request $request){
        // print_r($request->all());
        $select_icon = File::where('id', $request->logo_id)->get();
        $img_path = Config::get('constants.WEBSITE_BASE_URL').$select_icon[0]['file_path'];
       
        $update_icon = DB::table('tbl_bank_list')->where('BankID', $request->bank_id_logo)->update([ 'bank_icon' =>  $img_path ]);
       
        if($update_icon){
            return back()->with('success', 'Icon is Updated Succesfully!!!');
        }else{
            return back()->with('error', 'Failed... Icon is Not Updated !!');

        }
    }
}
