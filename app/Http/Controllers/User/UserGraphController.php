<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Config;
use DB;
use App\Role;
use App\TransactionDetail;
use App\ApiSetting;
use App\User;
use Illuminate\Support\Str;
use App\UserLoginSessionDetail;
use App\WalletTransactionDetail;
use App\BalanceRequest;
use App\KycDetail;
use App\ServicesType;
use App\OperatorSetting;
use App\Complaint;
use App\TransRevBal;
use App\File;

class UserGraphController extends Controller
{
  

    public function getUserGraphAPI(Request $request){
        $report=[];
        if($request->role_id== Config::get('constants.DISTRIBUTOR')){
            $report = $this->distributorGraph($request, $request->user_id, $request->role_id);
        }elseif($request->role_id== Config::get('constants.RETAILER')){
            $report = $this->retailerGraph($request, $request->user_id, $request->role_id);
            // print_r($report);
        }elseif($request->role_id== Config::get('constants.FOS')){
            $report = $this->fosGraph($request, $request->user_id, $request->role_id);
            // print_r($report);
        }
        


        if($report){
            
            $statusMsg = "Success!!";
            return $this->sendSuccess($report, $statusMsg);
        }else{
            return $this->sendError("Sorry!! Not Found");
        }
        
    }

    public function retailerGraph($request, $user_id, $role_id){
        $allWalletRecords =  $this->filter($user_id, $role_id, $request); 
        
        $filtersList = Config::get('constants.GRAPH_FILTER');
        $allWalletRecords = $this->modifyPiachart($allWalletRecords, $request, $user_id, $role_id); 
        
         //mobile pripaid
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))->get()->first();
        // $service = $getservice['service_id'];
        $allWalletRecords['recharge'] = $this->getRechargeAmt_new($user_id, $getservice['service_id'], $request); 

        //bill and post paid
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))->get()->first();
        // $service = $getservice['service_id'];

        $getpostpaid = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'))->get()->first();
        // $postpaid = $getpostpaid['service_id'];
        $allWalletRecords['bill_payment'] = $this->getRechargeAmt_new($user_id, $getservice['service_id'],$request, $getpostpaid['service_id']);

        //DTH Recharge
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))->get()->first();
        $allWalletRecords['dth'] = $this->getRechargeAmt_new($user_id, $getservice['service_id'],$request);

        $allWalletRecords['money_transfer'] = $this->moneyTransfer($user_id, $request); 

        $allWalletRecords['upi_transfer'] = $this->upi_transfer($user_id, $request); 

        $allWalletRecords['aeps'] = $this->getAEPS($user_id, $request); 

       
        // print_r($allWalletRecords);  
        return $allWalletRecords;
    }

    public function upi_transfer($user_id, $request){
        $upi_transfer=[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        
        foreach ($operator_types as $key => $value) {
            $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])->where('user_id', $user_id)
                                            // ->whereIn('order_status', 'SUCCESS')
                                            ->whereIn('order_status', ['SUCCESS', 'PENDING'])
                                            ->where('operator_id', $value['operator_id'])
                                            ->where('transaction_type', '!=','UPI_VERIFICATION');
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            }
             else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date', '>=',$fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();
            $upi_transfer[$key] = $trans_dtls;
        }
        
        $upi_transfer_amt = 0.00;
        

        $upi_transfer_report = [];
        foreach ($operator_types as $key => $value) {
            $upi_sum = 0.00;

            foreach ($upi_transfer[$key] as $upi_key => $upi_value) {
               
                if ( ($value['operator_id'] == $upi_value['operator_id'])) {

                  
                        $upi_sum=(float) $upi_sum + (float) $upi_value['total_amount'];
                    

                    
                }
            }

            $upi_transfer_report[$key]['opertor_name'] = $value['operator_name'];
            $upi_transfer_report[$key]['amount'] = (int) $upi_sum;

        }
        return $upi_transfer_report;

    }


    public function getAEPS($user_id, $request){
        $trans_dtls =[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.AEPS'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get()->first();

        $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])->where('user_id', $user_id)
                                            ->where('operator_id', $operator_types['operator_id']);
                                            // ->where('payment_type', 'SERVICE');
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date', $fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();

            // return $trans_dtls;
            $amt_sum = 0.00;
            foreach ($trans_dtls as $key => $value) {
              $amt_sum = (float)$amt_sum + (float) $value;
            }
            return (int)$amt_sum;
    }

    public function moneyTransfer_old($user_id, $request){
        $money_trans=[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        
        foreach ($operator_types as $key => $value) {
            $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])
                                            ->where('user_id', $user_id)
                                            ->where('operator_id', $value['operator_id']);
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date', '>=',$fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();
            $money_trans[$key] = $trans_dtls;
        }
        
        $money_report = [];
        foreach ($operator_types as $key => $value) {
            $moeny_sum = 0.00;

            foreach ($money_trans[$key] as $m_key => $m_value) {
               
                if ( ($value['operator_id'] == $m_value['operator_id'])) {
                    $moeny_sum=(float) $moeny_sum + (float) $m_value['total_amount'];
                }
            }

            $money_report[$key]['opertor_name'] = $value['operator_name'];
            $money_report[$key]['amount'] = (int) $moeny_sum;

        }
        return $money_report;

    }

    public function moneyTransfer($user_id, $request){
        $money_trans=[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();

        $op_array = [];
        foreach ($operator_types as $key => $value) {
            $op_array[$key] = $value['operator_id'];
        }


       
            $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])
                                            ->where('user_id', $user_id)
                                            ->where('order_status', 'SUCCESS')
                                            ->where('transaction_type', '!=', 'BANK_VERIFICATION')
                                            ->whereIn('operator_id', $op_array);
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date', '>=',$fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();
            // $money_trans[$] = $trans_dtls;
        
        
        $money_report = [];
        foreach ($operator_types as $key => $value) {
            $moeny_sum = 0.00;

            foreach ($trans_dtls as $m_key => $m_value) {
               
                if ( ($value['operator_id'] == $m_value['operator_id'])) {
                    $moeny_sum=(float) $moeny_sum + (float) $m_value['total_amount'];
                }
            }

            $money_report[$key]['opertor_name'] = $value['operator_name'];
            $money_report[$key]['amount'] = (int) $moeny_sum;

        }
        return $money_report;

    }


   

    public function filter($user_id, $role_id, $request)
    {
        $report = WalletTransactionDetail::leftJoin('tbl_operator_settings', 'tbl_wallet_trans_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
                                           ->where('user_id', $user_id);
                                           
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $report->whereDate('trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $report->whereDate('trans_date', '<=', $toDate);
        }
        $report = $report->get();
        return $report;
    }

    public function modifyPiachart($allWalletRecords, $request, $user_id, $role_id){

        $modified_Records = [];
        $totalAmtUsed = 0.00;
        $totalAmtAdded = 0.00;
        
        if (count($allWalletRecords)>0) {
           foreach ($allWalletRecords as $key => $value) {
              if($value['transaction_type'] == 'DEBIT'){
                  
                $totalAmtUsed = (float) $totalAmtUsed + (float) $value['total_amount'];
              }

              if ( ($value['transaction_type'] == 'CREDIT') && ($value['payment_type'] == 'LOAD_WALLET') ) {
                $totalAmtAdded = (float) $totalAmtAdded + (float) $value['total_amount'];  
              }

              
           }
        }
        //Mobile Prepaid
        // $service = '1';
        // $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))->get()->first();
        // $service = $getservice['service_id'];
        // $recharge_amt = $this->getRechargeAmt($user_id, $service,$request);
        // $recharge_comm = $this->getRechargeComm($user_id, $service,$request);
        //DTH Recharge
        // $recharge_dth = $this->getRechargeAmt(Auth::user()->userId, 3, $request);
        // $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))->get()->first();
        // $service = $getservice['service_id'];
        // $recharge_dth = $this->getRechargeDTH($user_id, $request, $service);
        // $recharge_dth_comm = $this->getRechargeComm(Auth::user()->userId, 3, $request);
        // $recharge_dth_comm = $this->getRechargeDTHComm($user_id, $request, $service);
        //BIll  ANd Post paid
        // $service = '4';
        // $postpaid = '2';
        // $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))->get()->first();
        // $service = $getservice['service_id'];

        // $getpostpaid = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'))->get()->first();
        // $postpaid = $getpostpaid['service_id'];

        // $bill_payment = $this->getRechargeAmt($user_id, $service,$request, $postpaid);
        // $bill_payment_comm = $this->getRechargeComm($user_id, $service,$request, $postpaid);
      
        $modified_Records = [
                                "totalAmtUsed" =>(int)$totalAmtUsed,
                                "totalAmtAdded" => (int)$totalAmtAdded,
                                // "recharge_amount" => $recharge_amt,    
                                // "recharge_commission" => $recharge_comm,    
                                // "recharge_dth" => $recharge_dth,    
                                // "recharge_dth_comm" => $recharge_dth_comm,  
                                // "bill_payment" => $bill_payment,    
                                // "bill_payment_comm" => $bill_payment_comm    
                            ];
        return $modified_Records;

    }

    
    public function ActiveInActiveUsers($user_id, $role_id, $request){

        $modified_Records=[];
        // $user =  User::where('parent_user_id', $user_id)->get();
        // print_r(count($user));
        $all_users = User::select('userId')
                            ->where('parent_user_id', $user_id)
                            ->where('activated_status', Config::get('constants.ACTIVE'))
                            ->where('roleId', $role_id)
                            ->where('isDeleted', '0')
                            ->where('isSpam', '0')
                            ->get();

        $NewDate=Date('y-m-d', strtotime('-10 days'));

        $total_users = count($all_users);

        $actived_users = [];
        foreach ($all_users as $key => $value) {
            $user_trans = $this->getUsersStatus($value['userId'], $NewDate);
            if($user_trans){
               $actived_users[$key] = $user_trans;
            }
          
        }
        $active_count = count($actived_users);
        $inactive_count = (int) $total_users - (int) $active_count;

        $modified_Records['active'] = $active_count;
        $modified_Records['inactive'] = $inactive_count;
        // $modified_Records['total'] = $total_users;
         

        // print_r($actived_users);
        return $modified_Records;
    }

    public function getUsersStatus($userId, $NewDate){
        
        $user_last_trans = WalletTransactionDetail::select('user_id', 'trans_date')
                                            ->where('user_id', $userId)
                                            ->orderBy('trans_date', 'DESC')
                                            ->whereDate('trans_date', '>=', $NewDate)
                                            ->first();
       return $user_last_trans;
    }

    public function getRechargeAmt($user_id, $service ,$request, $postpaid = null){
   
        $result = WalletTransactionDetail::select(DB::raw('sum(tbl_wallet_trans_dtls.total_amount) as total_amount'), 'tbl_operator_settings.operator_name')
                                        ->leftJoin('tbl_operator_settings', 'tbl_wallet_trans_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
                                        ->where('tbl_wallet_trans_dtls.user_id', $user_id)
                                        ->where('tbl_wallet_trans_dtls.payment_type', 'SERVICE')
                                        ->where('tbl_wallet_trans_dtls.service_id', $service);
                                        // 
        if($postpaid){
            $result= $result->orWhere('tbl_wallet_trans_dtls.service_id', $postpaid);
        }
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }                           
       
        $result = $result->groupBy('tbl_wallet_trans_dtls.operator_id', 'tbl_operator_settings.operator_name')
                        ->get();
        $rechargeAmt = [];
       
        $recharge_Record =[];
        foreach ($result as $key => $value) {
            // $rechargeAmt[$value['operator_name']] = $value['total_amount'];
            // $recharge_Record =
        //   array_push( $rechargeAmt, [ $value['operator_name'], $value['total_amount'] ]);
            // $value['operator_name'] = $value['operator_name'] . " :- ₹".$value['total_amount'];
            // $recharge_Record[$key] = [ $value['operator_name'], $value['total_amount'] ];
            $row['opertor_name'] = $value['operator_name'];
            $row['amount'] = $value['total_amount'];

            // $recharge_Record[$value['operator_name']] =   $value['total_amount'] ;
            $recharge_Record[$key] =   $row ;

        }
       
        return $recharge_Record;
    }



    public function getRechargeComm($user_id, $service, $request, $postpaid = null){
   
        $result = WalletTransactionDetail::select(DB::raw('sum(tbl_wallet_trans_dtls.total_amount) as total_amount'), 'tbl_operator_settings.operator_name')
                                        ->leftJoin('tbl_operator_settings', 'tbl_wallet_trans_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
                                        ->where('tbl_wallet_trans_dtls.user_id', $user_id)
                                        ->where('tbl_wallet_trans_dtls.transaction_type', 'CREDIT')
                                        ->where('tbl_wallet_trans_dtls.payment_type', 'COMMISSION')
                                        // ->where('tbl_wallet_trans_dtls.transaction_status', 'SUCCESS')
                                        ->where('tbl_wallet_trans_dtls.service_id', $service);
        if($postpaid){
            $result= $result->orWhere('tbl_wallet_trans_dtls.service_id', $postpaid);
        }
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }                           
       
        $result = $result->groupBy('tbl_wallet_trans_dtls.operator_id', 'tbl_operator_settings.operator_name')
                        ->get();
        $rechargeAmt = [];
       
        $recharge_Record =[];
        foreach ($result as $key => $value) {
            // $rechargeAmt[$value['operator_name']] = $value['total_amount'];
            // $recharge_Record =
        //   array_push( $rechargeAmt, [ $value['operator_name'], $value['total_amount'] ]);
            // $value['operator_name'] = $value['operator_name'] . " :- ₹".$value['total_amount'];

            $row['opertor_name'] = $value['operator_name'];
            $row['amount'] = $value['total_amount'];
            $recharge_Record[$key] = $row;
            // $recharge_Record[$value['operator_name']] =  $value['total_amount'] ;
        }
       
        return $recharge_Record;
    }

    public function getRechargeDTH($user_id, $request, $service){
        
        $result = WalletTransactionDetail::select(DB::raw('sum(tbl_wallet_trans_dtls.total_amount) as total_amount'), 'tbl_operator_settings.operator_name')
                                        ->leftJoin('tbl_operator_settings', 'tbl_wallet_trans_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
                                        ->where('tbl_wallet_trans_dtls.user_id', $user_id)
                                        ->where('tbl_wallet_trans_dtls.transaction_type', 'DEBIT')
                                        // ->where('tbl_wallet_trans_dtls.payment_type', 'COMMISSION')
                                        // ->where('tbl_wallet_trans_dtls.transaction_status', 'SUCCESS')
                                        ->where('tbl_wallet_trans_dtls.service_id', $service);
                                        // ->orWhere('tbl_wallet_trans_dtls.service_id', Config::get('constants.BILL_PAYMENTS'));
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }                           
       
        $result = $result->groupBy('tbl_wallet_trans_dtls.operator_id', 'tbl_operator_settings.operator_name')
                        ->get();
        $rechargeAmt = [];
       
        $recharge_Record =[];
        foreach ($result as $key => $value) {
            // $rechargeAmt[$value['operator_name']] = $value['total_amount'];
            // $recharge_Record =
        //   array_push( $rechargeAmt, [ $value['operator_name'], $value['total_amount'] ]);
        // $value['operator_name'] = $value['operator_name'] . " :- ₹".$value['total_amount'];

            $row['opertor_name'] = $value['operator_name'];
            $row['amount'] = $value['total_amount'];
            $recharge_Record[$key] = $row;
            // $recharge_Record[$value['operator_name']] = $value['total_amount'];
        }
       
        return $recharge_Record;
    }
    public function getRechargeDTHComm($user_id, $request, $service){
   
        $result = WalletTransactionDetail::select(DB::raw('sum(tbl_wallet_trans_dtls.total_amount) as total_amount'), 'tbl_operator_settings.operator_name')
                                        ->leftJoin('tbl_operator_settings', 'tbl_wallet_trans_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
                                        ->where('tbl_wallet_trans_dtls.user_id', $user_id)
                                        ->where('tbl_wallet_trans_dtls.transaction_type', 'CREDIT')
                                        ->where('tbl_wallet_trans_dtls.payment_type', 'COMMISSION')
                                        // ->where('tbl_wallet_trans_dtls.transaction_status', 'SUCCESS')
                                        ->where('tbl_wallet_trans_dtls.service_id', $service);
                                        // ->orWhere('tbl_wallet_trans_dtls.service_id', '4');
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $result->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }                           
       
        $result = $result->groupBy('tbl_wallet_trans_dtls.operator_id', 'tbl_operator_settings.operator_name')
                        ->get();
        $rechargeAmt = [];
       
        $recharge_Record =[];
        foreach ($result as $key => $value) {
            // $rechargeAmt[$value['operator_name']] = $value['total_amount'];
            // $recharge_Record =
        //   array_push( $rechargeAmt, [ $value['operator_name'], $value['total_amount'] ]);
            // $value['operator_name'] = $value['operator_name'] . " :- ₹".$value['total_amount'];

            $row['opertor_name'] = $value['operator_name'];
            $row['amount'] = $value['total_amount'];
            $recharge_Record[$key] = $row;

            // $recharge_Record[$value['operator_name'] ] =  $value['total_amount'];
        }
       
        return $recharge_Record;
    }

    public function getRechargeAmt_new($user_id, $service_id, $request, $postpaid=null){
        // $service = Config::get('constaants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID');
        // $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))->get()->first();
        // $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        $operator_types = OperatorSetting::where('service_id', $service_id)->get();

        $trans_dtls = TransactionDetail::where('service_id',  $service_id)
                                        ->where('user_id', $user_id)
                                        ->where('order_status', 'SUCCESS');
                                        // ->get();  
        // if($postpaid){
        //     $trans_dtls->orWhere('tbl_transaction_dtls.service_id', $postpaid);
        // } 
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $trans_dtls =$trans_dtls->whereDate('trans_date', '>=', $fromDate);
        } 
        else {
            $fromDate = now();
            $trans_dtls =$trans_dtls->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $trans_dtls = $trans_dtls->whereDate('trans_date', '<=', $toDate);
        }     

        $trans_dtls =$trans_dtls->get();
        $recharge = [];
        if(count($trans_dtls)>0){
            foreach ($operator_types as $op_key => $op_value) {
                $ttl_amt=00.00;
                $ttl_comm=00.00;
                foreach ($trans_dtls as $key => $value) {
               
                    if ($op_value['operator_id'] == $value['operator_id']) {
                        $ttl_amt = (float) $ttl_amt + (float) $value['total_amount'];

                        $wallt_comm = WalletTransactionDetail::where('order_id', $value['order_id'])
                                                    ->where('user_id', $user_id)
                                                    ->where('operator_id', $op_value['operator_id'])
                                                    ->where('service_id', $op_value['service_id'])
                                                    ->where('payment_type', 'COMMISSION')->get()->first();
                        $ttl_comm = (float) $ttl_comm + (float) $wallt_comm['total_amount'];
                    }
                }


                $recharge[$op_key]['opertor_name'] = $op_value['operator_name'];
                $recharge[$op_key]['amount'] = (int)$ttl_amt;
                $recharge[$op_key]['commission'] = (int)$ttl_comm;   
                $recharge[$op_key]['color_code'] = ($op_value['color_code']) ? $op_value['color_code'] : '' ;
                
                if ($op_value['operator_logo_file_id']) {
                    $op_icon  = File::select('file_path')->where('id', $op_value['operator_logo_file_id'])->get()->first();
                    $recharge[$op_key]['icon'] = $op_icon['file_path'] ; 
                }else{
                    $recharge[$op_key]['icon'] = '' ; 
                }   

            }
        }else {
            foreach ($operator_types as $op_key => $op_value) {
                $recharge[$op_key]['opertor_name'] = $op_value['operator_name'];
                $recharge[$op_key]['amount'] = 0;
            }
        }

        return $recharge;
    }

    public function distributorGraph($request, $user_id, $role_id){
        $graph_report = [];
        $graph_report['retailer'] =  $this->ActiveInActiveUsers($user_id, Config::get('constants.RETAILER'), $request);
        $graph_report['fos'] =  $this->ActiveInActiveUsers($user_id, Config::get('constants.FOS'), $request);

        $graph_report['balance']['load'] =  $this->userBalanceLoad($user_id, $request, 'CREDIT', 'DEBIT');
        $graph_report['balance']['transfer'] =  $this->userBalanceLoad($user_id, $request, 'DEBIT', 'CREDIT');
        
        $graph_report['credit_cash']['credit'] =  $this->userCreditTransfer($user_id, $request);
        $graph_report['credit_cash']['cash'] =  $this->userCashTransfer($user_id, $request);
        
        //REtailer Under Distributor
        $all_retailers = User::select('userId')->where('parent_user_id', $user_id)
                                ->where('activated_status', Config::get('constants.ACTIVE'))
                                ->where('roleId', Config::get('constants.RETAILER'))->get();
                                // print_r(count($all_retailers));
        $retailers_id = array();
        foreach ($all_retailers as $key => $value) {
            array_push($retailers_id,$value['userId']);
        }
        // print_r($retailers_id);
        //mobile pripaid
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))->get()->first();
        // $service = $getservice['service_id'];
        $graph_report['recharge'] = $this->getRechargeAmtDist($retailers_id, $getservice['service_id'], $request); 

        //bill and post paid
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))->get()->first();
        // $service = $getservice['service_id'];

        $getpostpaid = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'))->get()->first();
        // $postpaid = $getpostpaid['service_id'];
        $graph_report['bill_payment'] = $this->getRechargeAmtDist($all_retailers, $getservice['service_id'],$request, $getpostpaid['service_id']);

        //DTH Recharge
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))->get()->first();
        $graph_report['dth'] = $this->getRechargeAmtDist($all_retailers, $getservice['service_id'],$request);

        $graph_report['money_transfer'] = $this->moneyTransferDis($all_retailers, $request);

        $graph_report['upi_transfer'] = $this->upiTransferDis($all_retailers, $request); 
        return $graph_report;
    }

    public function getRechargeAmtDist($user_id, $service_id, $request, $postpaid=null){
        // $service = Config::get('constaants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID');
        // $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))->get()->first();
        // $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        $operator_types = OperatorSetting::where('service_id', $service_id)->get();
        
        $trans_dtls = TransactionDetail::where('service_id',  $service_id);
        // if($request->role_id== Config::get('constants.DISTRIBUTOR')){
        //     $trans_dtls = $trans_dtls->where('user_id', $request->user_id);
        // }else {
        //     $trans_dtls =  $trans_dtls->whereIn('user_id', $user_id);
        // }
        $trans_dtls = $trans_dtls->where('order_status', 'SUCCESS')
                                        ->whereIn('user_id', $user_id);
                                        
                                        // ->get();  
                
        // if($postpaid){
        //     $trans_dtls->orWhere('tbl_transaction_dtls.service_id', $postpaid);
        // } 
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $trans_dtls->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $trans_dtls->whereDate('trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $trans_dtls->whereDate('trans_date', '<=', $toDate);
        }     

        $trans_dtls =$trans_dtls->get();
        // print_r(json_encode($trans_dtls));
    
        $recharge = [];
        if(count($trans_dtls)>0){
            foreach ($operator_types as $op_key => $op_value) {
                $ttl_amt=00.00;
                $ttl_comm=00.00;
                foreach ($trans_dtls as $key => $value) {
               
                    if ($op_value['operator_id'] == $value['operator_id']) {
                        $ttl_amt = (float) $ttl_amt + (float) $value['total_amount'];

                        $wallt_comm = WalletTransactionDetail::where('order_id', $value['order_id']);

                        if($request->role_id== Config::get('constants.DISTRIBUTOR')){
                            $wallt_comm = $wallt_comm->where('user_id', $request->user_id);
                        }else {
                            $wallt_comm =  $wallt_comm->whereIn('user_id', $user_id);
                        }
                                                    // ->whereIn('user_id', $user_id)
                            $wallt_comm =  $wallt_comm->where('operator_id', $op_value['operator_id'])
                                                    ->where('service_id', $op_value['service_id'])
                                                    ->where('payment_type', 'COMMISSION')->get()->first();
                        $ttl_comm = (float) $ttl_comm + (float) $wallt_comm['total_amount'];
                    }
                }
                $recharge[$op_key]['opertor_name'] = $op_value['operator_name'];
                $recharge[$op_key]['amount'] = (int)$ttl_amt;
                $recharge[$op_key]['commission'] = (int)$ttl_comm; 
                $recharge[$op_key]['color_code'] = ($op_value['color_code']) ? $op_value['color_code'] : '' ;
                
                if ($op_value['operator_logo_file_id']) {
                    $op_icon  = File::select('file_path')->where('id', $op_value['operator_logo_file_id'])->get()->first();
                    $recharge[$op_key]['icon'] = $op_icon['file_path'] ; 
                }else{
                    $recharge[$op_key]['icon'] = '' ; 
                }                                           
            }
        }else {
            foreach ($operator_types as $op_key => $op_value) {
                $recharge[$op_key]['opertor_name'] = $op_value['operator_name'];
                $recharge[$op_key]['amount'] = 0;
            }
        }

        return $recharge;
    }

    public function userBalanceLoad($user_id, $request, $transaction_type, $transaction_type_revert){

        $balance_load = WalletTransactionDetail::where('user_id', $user_id)->where('transaction_status','SUCCESS')
                                                ->where('transaction_type', $transaction_type)
                                                ->where('payment_type', Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'))
                                                ->whereIn('payment_mode', array(Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'), Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY')));
                                                
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balance_load->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $balance_load->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balance_load->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }
        
        $balance_load = $balance_load->pluck('total_amount')->sum();

        $balance_revert = WalletTransactionDetail::where('user_id', $user_id)->where('transaction_status','SUCCESS')
                                                ->where('transaction_type', $transaction_type_revert)
                                                ->where('payment_type', Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'))
                                                ->where('payment_mode', 'REVERT');
                                                
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balance_revert->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $balance_revert->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balance_revert->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }

        $balance_revert = $balance_revert->pluck('total_amount')->sum();
        // print_r( $balance_load);
        // print_r("===");
        // print_r($balance_revert);


        $total_load = (float) $balance_load - (float) $balance_revert;

        return (int)$total_load;

                                                
    }

    public function userBalanceTransfer($user_id, $request){
        $balance_transfer = WalletTransactionDetail::where('user_id', $user_id)->where('transaction_status','SUCCESS')
                                                ->where('transaction_type', 'DEBIT')
                                                ->where('payment_type', Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'))
                                                ->whereIn('payment_mode', array(Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'), Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY')));
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balance_transfer->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $balance_transfer->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balance_transfer->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }   

        $balance_transfer = $balance_transfer->pluck('total_amount')->sum();

        $balance_revert = WalletTransactionDetail::where('user_id', $user_id)->where('transaction_status','SUCCESS')
                                                ->where('transaction_type', 'CREDIT')
                                                ->where('payment_type', Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'))
                                                ->where('payment_mode', 'REVERT');
                                                
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balance_revert->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $balance_revert->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balance_revert->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }

        $balance_revert = $balance_revert->pluck('total_amount')->sum();
        $total_load = (float) $balance_transfer - (float) $balance_revert;

        return (int)$total_trnsfer;
    }

    public function userCreditTransfer($user_id, $request){

        $total_credit = TransRevBal::where('payment_type', 'CREDIT')->where('transfered_by', $user_id)
                                        ->where('transfer_type', 'CREDIT');
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $total_credit->whereDate('tbl_transfer_revert_balances.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $total_credit->whereDate('tbl_transfer_revert_balances.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $total_credit->whereDate('tbl_transfer_revert_balances.trans_date', '<=', $toDate);
        }
        
        $total_credit = $total_credit->pluck('amount')->sum();

        $credit_revert = TransRevBal::where('payment_type', 'CREDIT')->where('transfered_by', $user_id)
                                        ->where('transfer_type', 'DEBIT');
        if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $credit_revert->whereDate('tbl_transfer_revert_balances.trans_date', '>=', $fromDate);
        } else {
                $fromDate = now();
                $credit_revert->whereDate('tbl_transfer_revert_balances.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $credit_revert->whereDate('tbl_transfer_revert_balances.trans_date', '<=', $toDate);
        }

        $credit_revert = $credit_revert->pluck('amount')->sum();

        $total_load = (float) $total_credit - (float) $credit_revert;

        return (int) $total_load;
    }

    public function userCashTransfer($user_id, $request){

        $total_cash = TransRevBal::where('payment_type', 'CASH')->where('transfered_by', $user_id)
                                        ->where('transfer_type', 'CREDIT');
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $total_cash->whereDate('tbl_transfer_revert_balances.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $total_cash->whereDate('tbl_transfer_revert_balances.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $total_cash->whereDate('tbl_transfer_revert_balances.trans_date', '<=', $toDate);
        }
        
        $total_cash = $total_cash->pluck('amount')->sum();

        $cash_revert = TransRevBal::where('payment_type', 'CASH')->where('transfered_by', $user_id)
                                        ->where('transfer_type', 'DEBIT');
        if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $cash_revert->whereDate('tbl_transfer_revert_balances.trans_date', '>=', $fromDate);
        } else {
                $fromDate = now();
                $cash_revert->whereDate('tbl_transfer_revert_balances.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $cash_revert->whereDate('tbl_transfer_revert_balances.trans_date', '<=', $toDate);
        }

        $cash_revert = $cash_revert->pluck('amount')->sum();

        $total_load = (float) $total_cash - (float) $cash_revert;

        return (int) $total_load;
    }

    public function moneyTransferDis_old($user_id, $request){
        $money_trans=[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        


        foreach ($operator_types as $key => $value) {
            $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])->whereIn('user_id', $user_id)
                                            ->where('operator_id', $value['operator_id']);


            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date', $fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();
            $money_trans[$key] = $trans_dtls;
        }
        
        $crezy_money = 0.00;
        $smart_money = 0.00;

        $money_report = [];
        foreach ($operator_types as $key => $value) {
            $moeny_sum = 0.00;

            foreach ($money_trans[0] as $m_key => $m_value) {
               
                if ( ($value['operator_id'] == $m_value['operator_id'])) {
                    $moeny_sum=(float) $moeny_sum + (float) $m_value['total_amount'];
                }
            }

            $money_report[$key]['opertor_name'] = $value['operator_name'];
            $money_report[$key]['amount'] = (int)$moeny_sum;

        }
        return $money_report;

    }

    public function moneyTransferDis($user_id, $request){
        $money_trans=[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        
        $op_array = [];
        foreach ($operator_types as $key => $value) {
            $op_array[$key] = $value['operator_id'];
        }

      
            $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])->whereIn('user_id', $user_id)
                                                ->where('order_status', 'SUCCESS')
                                                ->where('transaction_type', '!=', 'BANK_VERIFICATION')
                                            ->whereIn('operator_id',  $op_array);


            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date', $fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();
            // $money_trans[$key] = $trans_dtls;
        
        
        $crezy_money = 0.00;
        $smart_money = 0.00;

        $money_report = [];
        foreach ($operator_types as $key => $value) {
            $moeny_sum = 0.00;

            foreach ($trans_dtls as $m_key => $m_value) {
               
                if ( ($value['operator_id'] == $m_value['operator_id'])) {
                    $moeny_sum=(float) $moeny_sum + (float) $m_value['total_amount'];
                }
            }

            $money_report[$key]['opertor_name'] = $value['operator_name'];
            $money_report[$key]['amount'] = (int)$moeny_sum;

        }
        return $money_report;

    }
    public function upiTransferDis($user_id, $request){
        $upi_transfer=[];
        $service_type_id = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))->get()->first();
        $operator_types = OperatorSetting::where('service_id', $service_type_id['service_id'])->get();
        
        foreach ($operator_types as $key => $value) {
            $trans_dtls = TransactionDetail::where('service_id', $service_type_id['service_id'])->whereIn('user_id', $user_id)
                                            ->whereIn('order_status', ['SUCCESS', 'PENDING'])
                                            ->where('operator_id', $value['operator_id'])
                                            ->where('transaction_type', '!=','UPI_VERIFICATION');
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $trans_dtls->whereDate('trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $trans_dtls->whereDate('trans_date','>=', $fromDate);
            }
    
            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $trans_dtls->whereDate('trans_date', '<=', $toDate);
            }

            $trans_dtls = $trans_dtls->get();
            $upi_transfer[$key] = $trans_dtls;
        }
        
       

        $upi_report = [];
        foreach ($operator_types as $key => $value) {
            $upi_sum = 0.00;

            foreach ($upi_transfer[0] as $m_key => $m_value) {
               
                if ( ($value['operator_id'] == $m_value['operator_id'])) {
                     $upi_sum=(float) $upi_sum + (float) $m_value['total_amount']; 
                    }
                }
            $upi_report[$key]['opertor_name'] = $value['operator_name'];
            $upi_report[$key]['amount'] = (int)$upi_sum;

        }
        return $upi_report;

    }

    public function fosGraph($request, $user_id, $role_id){
        $graph_report = [];
        $graph_report['retailer'] =  $this->ActiveInActiveUsers($user_id, Config::get('constants.RETAILER'), $request);
        // $graph_report['fos'] =  $this->ActiveInActiveUsers($user_id, Config::get('constants.FOS'), $request);

        $graph_report['balance']['load'] =  $this->userBalanceLoad($user_id, $request, 'CREDIT', 'DEBIT');
        $graph_report['balance']['transfer'] =  $this->userBalanceLoad($user_id, $request, 'DEBIT', 'CREDIT');
        
        $graph_report['credit_cash']['credit'] =  $this->userCreditTransfer($user_id, $request);
        $graph_report['credit_cash']['cash'] =  $this->userCashTransfer($user_id, $request);
        
        //REtailer Under Distributor
        $all_retailers = User::select('userId')->where('fos_id', $user_id)
                                ->where('activated_status', Config::get('constants.ACTIVE'))
                                ->where('roleId', Config::get('constants.RETAILER'))->get();
                                // print_r(count($all_retailers));
        $retailers_id = array();
        foreach ($all_retailers as $key => $value) {
            array_push($retailers_id,$value['userId']);
        }
        // print_r($retailers_id);
        //mobile pripaid
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))->get()->first();
        // $service = $getservice['service_id'];
        $graph_report['recharge'] = $this->getRechargeAmtDist($retailers_id, $getservice['service_id'], $request); 

        //bill and post paid
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))->get()->first();
        // $service = $getservice['service_id'];

        $getpostpaid = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'))->get()->first();
        // $postpaid = $getpostpaid['service_id'];
        $graph_report['bill_payment'] = $this->getRechargeAmtDist($all_retailers, $getservice['service_id'],$request, $getpostpaid['service_id']);

        //DTH Recharge
        $getservice = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))->get()->first();
        $graph_report['dth'] = $this->getRechargeAmtDist($all_retailers, $getservice['service_id'],$request);

        $graph_report['money_transfer'] = $this->moneyTransferDis($all_retailers, $request);

        $graph_report['upi_transfer'] = $this->upiTransferDis($all_retailers, $request); 
        return $graph_report;
    }



}