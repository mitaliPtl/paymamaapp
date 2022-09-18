<?php

namespace App\Http\Controllers\Reports;

use App\ApiLogDetail;
use App\ApiSetting;
use App\City;
use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;
use App\SmsTemplate;
use App\State;
use App\StoreCategory;
use App\TransactionDetail;
use App\User;
use App\WalletTransactionDetail;
use Auth;
use Config;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\Template;

class TransactionReportsController extends Controller
{
    protected $page_name = "";
    protected $service_type = "";
    protected $export_file_name = "";

    /**
     * Get all Recharge Reports
     */
    public function index(Request $request)
    {
  
        $rechargeReports_forinvoice=[];
        $user_dtls = Auth::user();
      
        $loggedInRole = Auth::user()->roleId;
       
        $serviceType = $request->input('service_type');
        
        $this->setPageName($serviceType);
        $pageName = $this->page_name;
        
        $filtersList = $this->setFilterList($loggedInRole, $serviceType);
       
        $rechargeReportTH = $this->setTableHeader($loggedInRole, $serviceType);
        
       
        $rechargeReports = $this->filter($request);
        
        
    //return $rechargeReports->get();
        
       
        if ( ($serviceType == 'MONEY_TRANSFER') || ($serviceType == 'UPI_TRANSFER')  || ($serviceType == 'AEPS')  || ($serviceType == 'AADHAR_PAY') || ($serviceType == 'Mini Statement') || ($serviceType == 'BALANCE_INQUIRY')) {
            // $rechargeReports = $rechargeReports->where('tbl_transaction_dtls.transaction_type', '!=', 'BANK_VERIFICATION');
            $rechargeReports = $rechargeReports->whereNotIn('tbl_transaction_dtls.transaction_type', ['BANK_VERIFICATION', 'UPI_VERIFICATION']);
        }
        
        $operators = OperatorSetting::where('tbl_operator_settings.is_deleted', Config::get('constants.NOT-DELETED'))->where('tbl_operator_settings.activated_status', Config::get('constants.ACTIVE'));
       
        if (isset($request->service_id) && $request->service_id) {
            $operators = $operators->where('tbl_operator_settings.service_id', $request->service_id);
        }
         
        if ($request->has('service_type')) {
            
            if ($serviceType == Config::get('constants.RECHARGE')['NAME']) {
                $type1 = Config::get('constants.RECHARGE')['VALUE']['MOBILE'];
                $type2 = Config::get('constants.RECHARGE')['VALUE']['DTH'];

                $rechargeReports = $rechargeReports
                    ->leftJoin('tbl_services_type', 'tbl_transaction_dtls.service_id', '=', 'tbl_services_type.service_id')
                    ->whereIn('tbl_services_type.alias', [
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                    ])
                    ->where('tbl_transaction_dtls.id_deleted', Config::get('constants.NOT-DELETED'))
                    // ->leftJoin('tbl_complaints', 'tbl_transaction_dtls.order_id', '=', 'tbl_complaints.order_id')

                    ->get();
                    
                $operators = $operators
                    ->leftJoin('tbl_services_type', 'tbl_operator_settings.service_id', '=', 'tbl_services_type.service_id')
                    ->whereIn('tbl_services_type.alias', [
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                    ])->get();
                   
            } else {
                
                
                $consType = 'constants.SERVICE_TYPE_ALIAS.'.$serviceType;
               
                // $rechargeReports = $rechargeReports->where('service_id', Config::get($consType))->where('id_deleted', Config::get('constants.NOT-DELETED'))->get();
               $rechargeReports = $rechargeReports
                    ->leftJoin('tbl_services_type', 'tbl_transaction_dtls.service_id', '=', 'tbl_services_type.service_id');
                   
                    if($serviceType != 'AEPS' && $serviceType != 'AADHAR_PAY' &&  $serviceType != 'Mini_Statement' &&  $serviceType != 'BALANCE_INQUIRY') {
                    $rechargeReports = $rechargeReports->leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id');
                    }
                    else
                    {
                        
                    }
                    $rechargeReports = $rechargeReports->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                    //->leftJoin('tbl_bbps_list', 'tbl_transaction_dtls.billerID', '=', 'tbl_bbps_list.billerId')

                     //->leftJoin('tbl_complaints', 'tbl_transaction_dtls.order_id', '=', 'tbl_complaints.order_id')
                     //->leftJoin('tbl_template', 'tbl_complaints.template_id', '=', 'tbl_template.template_id')
                    ->where('tbl_services_type.alias', Config::get($consType))
                    ->where('tbl_transaction_dtls.id_deleted', Config::get('constants.NOT-DELETED'))->get();
                
                $rechargeReports_forinvoice = $rechargeReports;

                $operators = $operators
                    ->leftJoin('tbl_services_type', 'tbl_operator_settings.service_id', '=', 'tbl_services_type.service_id')
                    ->where('tbl_services_type.alias', Config::get($consType))->get();
                    
            }
        } else {
            
            $rechargeReports->get();
        }
      
        $rechargeReports = $this->modifyRechargeReport($rechargeReportTH, $rechargeReports);
        
       
        $rechargeReports = isset($rechargeReports) ? $rechargeReports : [];

        $this->export_file_name = $this->service_type . '_transaction_report_' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($this->export_file_name, $rechargeReportTH, $rechargeReports);
            return $response;
        }
        
        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        
        $templates = $this->getTemplateByService($serviceType);        
      
      $total = $this->calcTotalCharges($rechargeReports);
    //   if ($serviceType == 'MONEY_TRANSFER') {
    //     $rechargeReports = $this->modifyRechargeReport_new($rechargeReports, $rechargeReports_forinvoice);
          
    //   }
        // print_r($total);
        // exit();
        
        return view('modules.reports.report', compact('pageName', 'filtersList', 'rechargeReportTH', 'rechargeReports', 'apiSettings', 'servicesTypes', 'operators', 'request', 'rechargeReports_forinvoice', 'user_dtls','templates', 'total'));
    }

     /**
     * Calculate Total Commission
     */
    public function calcTotalCharges($reports){
        $response =[];

        if($reports && !empty($reports)){
            $totalAmt =0;
            $totalTransCharge =0;
            $totalPayableCharge =0;
            $totalFinalAmount =0;
            $totalCCFcharges =0;
            $totalCashback =0;
            $totalTDSamount =0;
            $totalChargeAmount =0;
            // $totalRtCom =0;
            // $totalAmt =0;
            foreach ($reports as $key => $report) {
                if ( isset($report['order_status']) && ( ($report['order_status'] == 'SUCCESS') || ($report['order_status'] == 'PENDING') ) ){
                    # code...
                
                    $totalAmt += isset($report['total_amount']) ? ((float) $report['total_amount']) : 0;
                    $totalTransCharge += isset($report['charge_amount']) ? ((float) $report['charge_amount']) : 0;

                    $totalPayableCharge += isset($report['PayableCharge']) ? ((float) $report['PayableCharge']) : 0;
                    $totalFinalAmount += isset($report['FinalAmount']) ? ((float) $report['FinalAmount']) : 0;
                    $totalCCFcharges += isset($report['CCFcharges']) ? ((float) $report['CCFcharges']) : 0;
                    $totalCashback += isset($report['Cashback']) ? ((float) $report['Cashback']) : 0;
                    $totalTDSamount += isset($report['TDSamount']) ? ((float) $report['TDSamount']) : 0;
                    $totalChargeAmount += isset($report['charge_amount']) ? ((float) $report['charge_amount']) : 0;
                    // $totalRtCom += isset($report['retailer_commission']) ? ((float) $report['retailer_commission']) : 0;
                    // $totalAmt += isset($report['total_amount']) ? ((float) $report['total_amount']) : 0;
                }
            }

            
            $response['total_amt'] = round($totalAmt,2);
            $response['total_trans_charges'] = round($totalTransCharge,2);

            $response['PayableCharge'] = round($totalPayableCharge,2);
            $response['FinalAmount'] = round($totalFinalAmount,2);
            $response['CCFcharges'] = round($totalCCFcharges,2);
            $response['Cashback'] = round($totalCashback,2);
            $response['TDSamount'] = round($totalTDSamount,2);
            //bill payment
            $response['charge_amount'] = round($totalChargeAmount,2);
            
            // $response['total_rt_comm'] = round($totalRtCom,2);
            // $response['total_amount'] = round($totalAmt,2);
        }

        return $response;
    }

    /**
     * Set Page name according to Service Type
     */
    public function setPageName($serviceType = null)
    {
        if ($serviceType == Config::get('constants.RECHARGE')['NAME']) {
            $this->page_name = "Recharge";
            $this->service_type = "recharge";
        } else if ($serviceType == Config::get('constants.BILL_PAYMENTS_LABEL')) {
            $this->page_name = "Bill Payment";
            $this->service_type = "bill_payment";
        } else if ($serviceType == Config::get('constants.MONEY_TRANSFER_LABEL')) {
            $this->page_name = "Money Transfer";
            $this->service_type = "money_transfer";
        } else if ($serviceType == Config::get('constants.AEPS_LABEL')) {
            $this->page_name = "AEPS";
            $this->service_type = "aeps";
        
        } else if ($serviceType == Config::get('constants.UPI_TRANSFER_LABEL')) {
            $this->page_name = "UPI TRANSFER";
            $this->service_type = "upi";
        }
        else if ($serviceType == Config::get('constants.AADHAR_PAY_LABEL')) {
            $this->page_name = "AADHAR PAY";
            $this->service_type = "AADHAR_PAY";
        }
        else if ($serviceType == Config::get('constants.ICICI_CASH_DEPOSIT_LABEL')) {
            $this->page_name = "ICICI CASH DEPOSIT";
            $this->service_type = "ICICI_CASH_DEPOSIT";
        }
        else if ($serviceType == Config::get('constants.Mini_Statement_LABEL')) {
            $this->page_name = "MINI STATEMENT";
            $this->service_type = "Mini_Statement";
        }
         else if ($serviceType == Config::get('constants.BALANCE_INQUIRY_LABEL')) {
            $this->page_name = "BALANCE INUIRY";
            $this->service_type = "BALANCE_INQUIRY";
        }
    }

    /**
     * Set Filter data here
     */
    public function setFilterList($loggedInRole, $serviceType = null)
    {
        $filterLists = [];
        $strAppend = "";
        $strAppend = "_ADMIN_FILTER";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_ADMIN_FILTER";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_DIS_FILTER";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_RT_FILTER";
        }

        if ($serviceType) {
            $tableConstFD = "constants." . $serviceType . $strAppend;
            $filterLists = Config::get($tableConstFD);
        } else {
            $tableConstFD = "constants.ALL_SRVC_TYP" . $strAppend;
            $filterLists = Config::get($tableConstFD);
        }

        return $filterLists;
    }

    /**
     * Get Table Header
     */
    public function setTableHeader($loggedInRole, $serviceType = null)
    {
        $rechargeReportTH = [];
        $strAppend = "";
        $strAppend = "_ADMIN_TD";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_ADMIN_TD";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_DIS_TD";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_RT_TD";
        }

        if ($serviceType) {
            $tableConstHD = "constants." . $serviceType . $strAppend;
            $rechargeReportTH = Config::get($tableConstHD);
        }

        return $rechargeReportTH;
    }

    /**
     * Filter Transaction Reports Data
     */
    public function filter($request)
    {
        $tranDtls = TransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))
                    ->leftJoin('tbl_api_settings', 'tbl_transaction_dtls.api_id', '=', 'tbl_api_settings.api_id')
            ->orderBy('tbl_transaction_dtls.id','DESC');
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $tranDtls->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $tranDtls->whereDate('trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $tranDtls->whereDate('trans_date', '<=', $toDate);
        }

        if (isset($request->api_id) && $request->api_id) {
            $tranDtls->where('tbl_transaction_dtls.api_id', $request->api_id);
        }

        if (isset($request->service_id) && $request->service_id) {
            $tranDtls->where('tbl_transaction_dtls.service_id', $request->get('service_id'));
        }

        if (isset($request->operator_id) && $request->operator_id) {
            $tranDtls->where('tbl_transaction_dtls.operator_id', $request->get('operator_id'));
        }

        if (isset($request->order_status) && $request->order_status) {
            $tranDtls->where('tbl_transaction_dtls.order_status', $request->get('order_status'));
        }

        if (Auth::id() != Config::get('constants.ADMIN')) {
            if (Auth::id()) {
                $childResponse = User::where('parent_user_id', Auth::id())->pluck('userId');
                if (count($childResponse) > 0) {
                    $childResponse = $childResponse->toArray();
                    array_push($childResponse, Auth::id());
                    $tranDtls->whereIn('tbl_transaction_dtls.user_id', $childResponse);
                } else {
                    $tranDtls->where('tbl_transaction_dtls.user_id', Auth::user()->userId);
                }
            }
        } else {
            if (isset($request->username_mobile) && $request->username_mobile) {
                $userId = User::where('mobile', $request->username_mobile)
                    ->orWhere('username', $request->username_mobile)->pluck('userId')->first();
                if ($userId) {
                    $tranDtls = $tranDtls->where('tbl_transaction_dtls.user_id', $userId);
                }
            }
        }

        return $tranDtls;
    }

    /**
     * Modify Recharge Reports List
     */
    public function modifyRechargeReport($tableHeads, $reportList)
    {
        
        $result = [];
        if ($reportList) {
            foreach ($reportList as $repInd => $report) {
                $keyList = [];
                $reportList[$repInd]['mobile'] = "";
                $reportList[$repInd]['response'] = "";
                foreach ($tableHeads as $headInd => $head) {
                    $keyList['id'] = $report['id'];
                    
                    if ($report['bank_code']) {
                        $bnk_name = DB::table('tbl_bank_list')->where('ShortCode', $report['bank_code'])->get()->first();
                        $bnk_name = json_decode(json_encode( $bnk_name), true);
                       if(isset($bnk_name))
                       {
                        $keyList['ifsc_code'] = $bnk_name['ifsc_prefix'];
                       }
                       else
                       {
                           $keyList['ifsc_code']='';
                       }
                        // print_r( $bnk_name);
                        // $label_val =  $bnk_name['BANK_NAME'];
                    }
                    
                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];

                        if ($head['label'] == "user_id") {
                            // $label_val = $this->getUserNameById($report[$head['label']]);
                            $label_val = User::getStoreNameById($report[$head['label']]);
                        }
                       

                        if ($head['label'] == "mobile") {
                            $label_val = $this->getUserMobileById($report['user_id']);
                        }

                        if ($head['label'] == "operator_id") {
                            $label_val = $this->getOperatorNameById($report[$head['label']]);
                        }

                        if ($head['label'] == "api_id") {
                            if($report[$head['label']]==17 || $report[$head['label']]==18 || $report[$head['label']]==19 || $report[$head['label']]==20)
                            {
                                $label_val = "Fingpay-".$this->getApiSettingNameById($report[$head['label']]);
                            }
                            else
                            {
                                $label_val = $this->getApiSettingNameById($report[$head['label']]);
                            }
                            
                        }
                        if ($head['label'] == "retailer_id") {
                            $label_val = User::getUsernameById($report['user_id']);
                        }
                         if ($head['label'] == "user_mobile_no") {
                            // $label_val = $this->getUserNameById($report[$head['label']]);
                            $label_val = "nhsdbjchb";
                        }
                        if ($head['label'] == "response") {
                            $label_val = $this->getResponseByOrdId($report['order_id']);
                        }

                        if ($head['label'] == "service_id") {
                            // $label_val = $this->getResponseByOrdId($report['order_id']);
                            $label_val = isset(ServicesType::where('service_id', $report[$head['label']])->pluck('service_name')[0]) ? ServicesType::where('service_id', $report[$head['label']])->pluck('service_name')[0] : '';
                        }
                        if ($head['label'] == "bank_name") {
                            if ($report['bank_code']) {
                                $bnk_name = DB::table('tbl_bank_list')->where('ShortCode', $report['bank_code'])->get()->first();
                                $bnk_name = json_decode(json_encode( $bnk_name), true);
                                if(isset($bnk_name))
                                {
                                    $label_val =  $bnk_name['BANK_NAME'];
                                }
                                else
                                {
                                    $label_val =  "";
                                }
                                
                                // print_r( $bnk_name);
                                // $label_val =  $bnk_name['BANK_NAME'];
                            }
                            
                        }
                        if ($head['label'] == "ifsc_code") {
                            $label_val = "ifsc";
                        }
                        

                        // if ($head['label'] == "trans_date") {
                        //     $label_val = substr($report['trans_date'], 0, 8);
                        // }

                        $keyList[$head['label']] = $label_val;
                    }
                    elseif ($head['label'] == "retailer_id") {
                            $label_val = User::getUsernameById($report['user_id']);
                            $keyList[$head['label']]=$label_val;
                        }
                       elseif ($head['label'] == "api_id") {
                           $id=$report['api_id'];
                            $response = ApiSetting::getApiNameById($report[$head['label']]);
                           
                            //$label_val = $this->getApiSettingNameById($report[$head['label']]);
                            $keyList[$head['label']] = "";
                        }
                       elseif ($head['label'] == "user_mobile_no") {
                            $label_val = User::getMobilenoById($report['user_id']);
                           
                             $keyList[$head['label']] = $label_val;
                        }
                    
                    
                    elseif ($head['label'] == 'customer_name') {
                        $label_val = "";
                        if (isset($report['response_msg']) && $report['response_msg'] ) {
                           $resp_msg = json_decode( $report['response_msg'] ,true);
                           if (isset($resp_msg['RespCustomerName']) && $resp_msg['RespCustomerName'] ) {
                                $label_val = $resp_msg['RespCustomerName'];
                           }
                        }
                        $keyList[$head['label']] = $label_val;
                    }
                    else {
                        
                        $keyList[$head['label']] = "";
                    }
                }
                array_push($result, $keyList);
                $keyList = [];

            }
        }

        return $result;
    }

    /**
     * Get User name by providing ID
     */
    public function getUserNameById($id)
    {
        $result = "";
        if ($id && $id != "") {
            $user = User::find((int) $id);
            if ($user) {
                $result = $user->first_name;
            }
        }
        return $result;
    }

    /**
     * Get User mobile by providing ID
     */
    public function getUserMobileById($id)
    {
        $result = "";
        if ($id && $id != "") {
            $user = User::find((int) $id);
            if ($user) {
                $result = $user->mobile;
            }
        }
        return $result;
    }

    /**
     * Get Api Setting name by providing ID
     */
    public function getApiSettingNameById($id)
    {
        $result = "";
        if ($id && $id != "") {
            $response = ApiSetting::find((int) $id);
            if ($response) {
                $result = $response->api_name;
            }
        }
        return $result;
    }

    /**
     * Get Operator name by providing ID
     */
    public function getOperatorNameById($id)
    {
        $result = "";
        if ($id && $id != "") {
            $response = OperatorSetting::find((int) $id);
            if ($response) {
                $result = $response->operator_name;
            }
        }
        return $result;
    }

    /**
     * Get Response by providing Order Id
     */
    public function getResponseByOrdId($id)
    {
        $result = "";
        if ($id && $id != "") {
            $response = ApiLogDetail::where('order_id', $id)->first();
            if ($response) {
                // $result = $response->response; // ToDO Later
            }
        }
        return $result;
    }

    /**
     * Export PDF
     */
    public function exportPDF($fileName, $tableHead, $tableBody)
    {
        $pdf = PDF::loadView('export.pdf', compact('fileName', 'tableHead', 'tableBody'));
        $pdf->setPaper('A4', 'landscape');
        $response = $pdf->download($fileName . '.pdf');
        return $response;
    }

    /**
     * Change Transaction Status
     */
    public function changeTransactionStatus(Request $request)
    {

        $response = null;
        if (isset($request->transaction_id) && $request->transaction_id) {

            // $tranDtl = TransactionDetail::find((int) $request->transaction_id);
            $tranDtl = TransactionDetail::where('order_id', $request->transaction_id)->get()->first();
            if (!isset($request->transaction_status)) {
                return back()->with("error", "Please Select Transaction Status");
            }
            $tranDtl->transaction_status = $request->transaction_status;
            $tranDtl->order_status = $request->transaction_status;
            $response = $tranDtl->save();

            if ($response && isset($request->transaction_status)) {
                $userWalletResponse = WalletTransactionDetail::where('order_id', $tranDtl->order_id)->get();

                if (!empty($userWalletResponse)) {
                    foreach ($userWalletResponse as $key => $walletData) {
                        $walletResponse = $this->updateWalletTransactionNdRefund($request->transaction_status, $walletData);
                    }
                }

                if ($walletResponse) {
                    $response = $walletResponse;
                }

            }
        }

        if ($response) {
            return back()->with("success", "Status updated successfully!!");
        } else {
            return back()->with("error", "Failed to update status!!");
        }
    }
    
     public function changewebhookstatusfromcodeigniter($order_id)
    {
        
        
        $response = null;
      
           
                $userWalletResponse = WalletTransactionDetail::where('order_id', $order_id)->get();
               
                if (!empty($userWalletResponse)) {
                    foreach ($userWalletResponse as $key => $walletData) {
                        $walletResponse = $this->updateWalletTransactionNdRefund('FAILED', $walletData);
                    }
                }

                if ($walletResponse) {
                    $response = $walletResponse;
                }
           
             return $walletResponse;
        

        // if ($response) {
        //     return back()->with("success", "Status updated successfully!!");
        // } else {
        //     return back()->with("error", "Failed to update status!!");
        // }
    }

    /**
     * Update Wallet Transaction Entry and Refund the amount
     */
    public function updateWalletTransactionNdRefund($tranStatus, $walletData)
    {
        $response = "";

        if (isset($walletData)) {
            
            $walletData->transaction_status = $tranStatus;
            $walletChngStatusRes = $walletData->save();
            $response = $walletChngStatusRes;
        
            if ($tranStatus == "FAILED") {
               
                $transactionType = "";
                $paymentMode = "";
                $smsData = [];

                $transacUser = User::find((int) $walletData->user_id);
                $smsData['last_balance_amount'] = $transacUser->wallet_balance;
                
                if ($walletData->payment_type == "SERVICE") {
                   
                    $transactionType = "CREDIT";
                    $mobile_number = 
                    $tranDtl = TransactionDetail::where('order_id',$walletData->order_id)->get()->first();
                    $operators = OperatorSetting::where('operator_id', $tranDtl->operator_id)->get()->first();
                    $paymentMode = "REFUND FOR ".$operators->operator_name.", MOBILE NUMBER ".$tranDtl->mobileno.", AMOUNT ".$walletData->total_amount;
                    $smsData['template'] = "constants.SMS_TEMPLATE_ALIAS.BALANCE_ADDED.name";
                    $transacUser->wallet_balance = $transacUser->wallet_balance + $walletData->total_amount;

                } else if ($walletData->payment_type == "COMMISSION") {
                  
                    $transactionType = "DEBIT";
                    $paymentMode = "Commission Revert";
                    $smsData['template'] = "constants.SMS_TEMPLATE_ALIAS.BALANCE_DEDUCT.name";

                    $transacUser->wallet_balance = $transacUser->wallet_balance - $walletData->total_amount;
                }
                
               
                $transacUserResponse = $transacUser->save();

                $smsData['amount'] = $walletData->total_amount;
                $smsData['updated_balance_amount'] = $transacUser->wallet_balance;
                $smsData['mobile'] = $transacUser->mobile;
               
                if (isset($transacUserResponse) && $transacUserResponse) {
                    
                    $walletResponse = WalletTransactionDetail::create([
                        'service_id' => $walletData->service_id,
                        'order_id' => $walletData->order_id,
                        'user_id' => $walletData->user_id,
                        'operator_id' => $walletData->operator_id,
                        'api_id' => $walletData->api_id,
                        'transaction_status' => "SUCCESS",
                        'transaction_type' => $transactionType,
                        'transaction_id' => $walletData->transaction_id,
                        // 'bank_trans_id' => $tranDtl->bank_trans_id,
                        'trans_date' => now(),
                        'payment_type' => $walletData->payment_type,
                        'payment_mode' => $paymentMode,
                        'total_amount' => $walletData->total_amount,
                        'charge_amount' => $walletData->charge_amount,
                        'balance' => $transacUser->wallet_balance,
                        'TDSamount' => -(float)($walletData->TDSamount),
                    ]);
                   
                    if ($walletResponse) {
                        $this->sendSmswithTransactionInfo($smsData);
                        $response = $walletResponse;
                    }
                }

            }

        }
       
        return $response;

    }

    /**
     * Change Transaction Status Via API
     */
    public function changeTransactionStatusApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
        ]);
       
        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        if($request->transaction_status != "SUCCESS" && $request->transaction_status != "FAILED"){
            return $this->sendError("Invalid Transaction Status!!");
        }
       
        $response = null;
        if (isset($request->order_id) && $request->order_id) {
            
            $tranDtl = TransactionDetail::where('order_id',$request->order_id)->get()->first();
            
            if(!$tranDtl){
                return $this->sendError("Transaction Record with given Order Id not found!!");
            }
            
            $tranDtl->transaction_status = $request->transaction_status;
            $tranDtl->order_status = $request->transaction_status;
            $response = $tranDtl->save();
            
            if ($response && isset($request->transaction_status)) {
                $userWalletResponse = WalletTransactionDetail::where('order_id', $tranDtl->order_id)->get();
               
                $walletResponse=[];
                if (!empty($userWalletResponse)) {
                   
                    foreach ($userWalletResponse as $key => $walletData) {
                        
                       
                        $walletResponse = $this->updateWalletTransactionNdRefund($request->transaction_status, $walletData);
                    }
                    
                }
               

                if ($walletResponse) {
                    $response = $walletResponse;
                }

            }
        }

        if ($response) {
            $msg = "Success!!";
            return $this->sendSuccess("Status updated successfully!!",$msg);
        } else {
            return $this->sendError("Failed to update status!!");
        }
    }

    /**
     * Send Sms to User on Successfull Transaction
     */
    public function sendSmswithTransactionInfo($smsData)
    {
        $msg = "";
        $result = null;

        $SmsBalAddTemplate = SmsTemplate::where('alias', Config::get($smsData['template']))->first();
        if (isset($SmsBalAddTemplate)) {
            $msg = __($SmsBalAddTemplate->template, [
                "last_balance_amount" => $smsData['last_balance_amount'],
                "amount" => $smsData['amount'],
                "updated_balance_amount" => $smsData['updated_balance_amount'],
            ]);
        }

        if ($msg) {
            if ($smsData['mobile']) {
                $result = $this->sendSms($msg, $smsData['mobile']);
            }

        }

        return $result;
    }

    /**
     * Filter Transaction Reports Data
     */
    public function filterTransactions($request)
    {
        $tranDtls = DB::table('tbl_transaction_dtls');
        // $tranDtls = TransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'));

        $query = "CAST(total_amount AS DECIMAL(10,2)) DESC";
        $tranDtls = $tranDtls
            ->orderByRaw($query)
            ->orderBy('order_status');

        if (isset($request->from_date) && $request->from_date) {
            $fromDate = $request->get('from_date');
            $fromDate = strtotime($fromDate);
            $fromDate = date('d/m/Y', $fromDate);
            $tranDtls->where('trans_date', '>=', $fromDate);
        }

        if (isset($request->to_date) && $request->to_date) {
            $toDate = $request->get('to_date');
            $toDate = strtotime($toDate);
            $toDate = date('d/m/Y', $toDate);
            $tranDtls->where('trans_date', '<=', $toDate);
        }

        if (isset($request->api_id) && $request->api_id) {
            $tranDtls->where('api_id', $request->api_id);
        }

        if (isset($request->service_id) && $request->service_id) {
            $tranDtls->where('service_id', $request->get('service_id'));
        }

        if (isset($request->operator_id) && $request->operator_id) {
            $tranDtls->where('operator_id', $request->get('operator_id'));
        }

        if (isset($request->order_status) && $request->order_status) {
            $tranDtls->where('order_status', $request->get('order_status'));
        }

        $filteredUsers = [];
        $users = User::where('isDeleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'));
        if (isset($request->state_id) && $request->state_id) {
            $users = $users->where('state_id', $request->state_id);
        }

        if (isset($request->city_id) && $request->city_id) {
            $users = $users->where('district_id', $request->city_id);
        }

        if (isset($request->store_category_id) && $request->store_category_id) {
            $users = $users->where('store_category_id', $request->store_category_id);
        }

        if (isset($request->state_id) || isset($request->city_id) || isset($request->store_category_id)) {
            $users = $users->pluck('userId')->toArray();
            $tranDtls->whereIn('user_id', $users);
        }
        
        return $tranDtls;
    }

    /**
     * Transaction Details(success/failure) info
     */
    public function transactionDetails(Request $request)
    {
        $loggedInRole = Auth::user()->roleId;

        $filtersList = $this->setFilterList($loggedInRole);
        $transactions = $this->filterTransactions($request);
        // return ($transactions);

        $transactions = $transactions->get();

        $operators = OperatorSetting::where('is_deleted', Config::get('constants.NOT-DELETED'));
        $cities = City::all();

        if (isset($request->service_id) && $request->service_id) {
            $operators = $operators->where('service_id', $request->service_id);
        }
        if (isset($request->state_id) && $request->state_id) {
            $cities = City::where('state_id', $request->state_id)->get();
        }

        $operators = $operators->get();

        $states = State::all();
        $storeCategories = StoreCategory::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        return view('modules.reports.transaction_detail', compact('request', 'transactions', 'filtersList', 'servicesTypes', 'apiSettings', 'operators', 'states', 'cities', 'storeCategories'));
    }

    /**
     * Change Transaction Table "trans_date" Column to default date format
     */
    public function chnageTranTblDateformat2Default()
    {
        $allTransactions = TransactionDetail::select(['id', 'trans_date'])->get();
        $response = "";

        if (isset($allTransactions) && $allTransactions) {
            foreach ($allTransactions as $i => $transaction) {
                $transaction->trans_date = $this->change2DefaultDtFrmt($transaction->trans_date);
                $response = $transaction->save();
            }
        }

        if ($response) {
            return "Transaction Table 'trans_date' Date Format changed successfully!!";
        }else{
            return "Failed to Change Date Format!!";
        }

    }

    /**
     * Change Transaction Table "trans_date" Column to default date format
     */
    public function chnageWlltTranTblDateformat2Default()
    {
        $allTransactions = WalletTransactionDetail::select(['id', 'trans_date'])->get();
        $response = "";

        if (isset($allTransactions) && $allTransactions) {
            foreach ($allTransactions as $i => $transaction) {
                $transaction->trans_date = $this->change2DefaultDtFrmt($transaction->trans_date);
                $response = $transaction->save();
            }
        }

        if ($response) {
            return "Wallet Transaction Table 'trans_date' Date Format changed successfully!!";
        }else{
            return "Failed to Change Date Format!!";
        }

    }

    public function change2DefaultDtFrmt($date)
    {
        $response = "";
        if ($date) {
            $date = str_replace('/', '-', $date);
            $date = strtotime($date);
            $response = date('Y-m-d H:i:s', $date);
        }
        return $response;
    }

    public function get_Allbeneficiary_dtls($data)
    {
        $beneficiary_array= [];
        foreach ($data as $key => $value) {
            $beneficiary_array[$key] = json_decode(DB::table('tbl_dmt_benificiary_dtls')
            ->where('recipient_id', '=',  $value['recipient_id'])    
            ->get(), true);
        }

        return $beneficiary_array;
    }

    public function getTemplateByService($service_name){
        $templates = Template::where('tbl_template.isDeleted', Config::get('constants.NOT-DELETED'));
       

        if ($service_name == Config::get('constants.RECHARGE')['NAME']) {

           $templates =$templates ->leftJoin('tbl_services_type', 'tbl_template.service_id', '=', 'tbl_services_type.service_id')
                                    ->whereIn('tbl_services_type.alias', [
                                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                                    ]);

           
        } else {
            $consType = 'constants.SERVICE_TYPE_ALIAS.' . $service_name;
            $templates = $templates ->leftJoin('tbl_services_type', 'tbl_template.service_id', '=', 'tbl_services_type.service_id')
                                    ->where('tbl_services_type.alias', Config::get($consType));

            
        }
        
            $templates=$templates->get();
           
       
        return $templates;
         
    }

    public function modifyRechargeReport_new($report, $original){
        if (count($report) > 0) {
            foreach ($report as $key => $value) {
               
                // $trnsDtls = TransactionDetail::where('group_id', $original[$key]['group_id'])->get();
                if ($original[$key]['group_id']) {
                    $data = $this->getRecipientIdFromGroupId($original[$key]['group_id']);
                
                    if (empty($value['api_name'])) {
                            // $report[$key]['api_id'] = $original[$key]['api_id'];
                            $report[$key]['api_name'] = "apis";
                    }
                    if (empty($value['bank_name'])) {
                        // $report[$key]['bank_name'] = "bank_name";
                    }
                    if (empty($value['transaction_type'])) {
                        $report[$key]['transaction_type'] = $data['transaction_type'];
                    }
                    if (empty($value['bank_account_number'])) {
                            $report[$key]['bank_account_number'] = $original[$key]['group_id'];
                    }
                }

            }
        }
        return $report;
    }

    public function getRecipientIdFromGroupId($group_id){
        $trnsDtls = TransactionDetail::where('group_id', $group_id)->get();
        $data=[];
        $recipient_id = '';
        foreach ($trnsDtls as $key => $value) {
           if ($value->recipient_id) {
                $data['recipient_id'] = $value->recipient_id;
                $data['transaction_type'] = $value->transaction_type;
           }
        }
        return $data;
    }

    public function getIFSCCode()
    {
        
    }

}
