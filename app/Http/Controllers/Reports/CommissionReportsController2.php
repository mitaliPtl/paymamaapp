<?php

namespace App\Http\Controllers\Reports;

use App\ApiLogDetail;
use App\ApiSetting;
use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\Role;
use App\ServicesType;
use App\User;
use App\WalletTransactionDetail;
use Auth;
use Config;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;

class CommissionReportsController extends Controller
{
    protected $page_name = "";
    protected $service_type = "";
    protected $export_file_name = "";

    /**
     * Get all Recharge Reports
     */
    public function index(Request $request)
    {
        // print_r($request->all());
        $rechargeReports_forinvoice=[];
        $user_dtls = Auth::user();
        $loggedInRole = Auth::user()->roleId;

        $serviceType = $request->input('service_type');

        $this->setPageName($serviceType);
        $pageName = $this->page_name;

        $filtersList = $this->setFilterList($loggedInRole, $serviceType);

        $rechargeReportTH = $this->setTableHeader($loggedInRole, $serviceType);

        $rechargeReports = $this->filter($request);

        
        // $rechargeReports = $this->filterNew($request);
      
        $operators = OperatorSetting::where('tbl_operator_settings.is_deleted', Config::get('constants.NOT-DELETED'))->where('tbl_operator_settings.activated_status', Config::get('constants.ACTIVE'));

        if (isset($request->service_id) && $request->service_id) {
            $operators = $operators->where('tbl_operator_settings.service_id', $request->service_id);
        }
       
        if ($request->has('service_type')) {
            if ($serviceType == Config::get('constants.RECHARGE')['NAME']) {
                $type1 = Config::get('constants.RECHARGE')['VALUE']['MOBILE'];
                $type2 = Config::get('constants.RECHARGE')['VALUE']['DTH'];
                $rechargeReports = $rechargeReports
                    ->leftJoin('tbl_services_type', 'tbl_wallet_trans_dtls.service_id', '=', 'tbl_services_type.service_id')
                    ->whereIn('tbl_services_type.alias', [
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                    ])
                    ->where('tbl_wallet_trans_dtls.id_deleted', Config::get('constants.NOT-DELETED'))
                    ->get();

                $operators = $operators
                    ->leftJoin('tbl_services_type', 'tbl_operator_settings.service_id', '=', 'tbl_services_type.service_id')
                    ->whereIn('tbl_services_type.alias', [
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                    ])->get();

            } else {
                $consType = 'constants.SERVICE_TYPE_ALIAS.' . $serviceType;
                $rechargeReports = $rechargeReports
                    ->leftJoin('tbl_services_type', 'tbl_wallet_trans_dtls.service_id', '=', 'tbl_services_type.service_id')
                    ->where('tbl_services_type.alias', Config::get($consType))
                    ->where('tbl_wallet_trans_dtls.id_deleted', Config::get('constants.NOT-DELETED'))->get();
                $rechargeReports_forinvoice = $rechargeReports;
                $operators = $operators
                    ->leftJoin('tbl_services_type', 'tbl_operator_settings.service_id', '=', 'tbl_services_type.service_id')
                    ->where('tbl_services_type.alias', Config::get($consType))->get();
            }
        } else {
            $rechargeReports->get();
        }
        
        
        $rechargeReports = $this->checkRechargeReportCommission($rechargeReports);
        $totalCommission = $this->calcTotalCommission($rechargeReports);
       
        $rechargeReports = $this->modifyRechargeReport($rechargeReportTH, $rechargeReports);
        
        // print_r($rechargeReports);

        //    $rechargeReports = $this->addComissions($rechargeReports);
        //    $this->print_row($rechargeReports);
        //    exit();
        $rechargeReports = isset($rechargeReports) ? $rechargeReports : [];

        $this->export_file_name = $this->service_type . '_commission_report_' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($this->export_file_name, $rechargeReportTH, $rechargeReports);
            return $response;
        }

        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
       
        return view('modules.reports.report', compact('pageName', 'filtersList', 'rechargeReportTH', 'rechargeReports', 'apiSettings', 'servicesTypes', 'operators', 'request','totalCommission', 'rechargeReports_forinvoice', 'user_dtls'));
    
    }

    public function addComissions($rechargeReports){
        foreach ($rechargeReports as $key => $value) {
         
            $rechargeReports[$key]['retailer_commission']= $this->getComissionByOrdId($value['order_id'], "RETAILER");
            $rechargeReports[$key]['distributor_commission']=  $this->getComissionByOrdId($value['order_id'], "DISTRIBUTOR");
            $rechargeReports[$key]['admin_commission']=  $this->getComissionByOrdId($value['order_id'], "ADMIN");
 
          
        }

        // foreach ($rechargeReports as $key => $value) {
        //     // $value['retailer_commission'] = '1001';
        //     // print_r($value['retailer_commission']);
        //     $rechargeReports[$key]['retailer_commission'] = '1001';
          
        // }
        return $rechargeReports;
        
    }

    public function getComissionByOrdId($ord_id, $comm_role){
        $cmm_Amt = '';
        // $cmm_Amt = [];
       
        // if($comm_role == 'TOTAL'){
        //     $commssion =  WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))
        //                                             ->where('order_id', $ord_id)
        //                                             ->where('transaction_type', 'DEBIT')
        //                                             ->get();
                                                                 
        //         return  $commssion[0]['total_amount'] ;    
        // }
        $commssion =  WalletTransactionDetail::select('user_id','total_amount')->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                                ->where('order_id', $ord_id)
                                                ->where('payment_type', 'COMMISSION')
                                                ->where('transaction_type', 'CREDIT')
                                               ->get();
        foreach($commssion as $comm_key => $comm_value){
           
                
                $r_id  = $this->getUserRoleById($comm_value['user_id']);

                
                $comm_role = 'constants.'.$comm_role;

                if(Config::get($comm_role) == $r_id){
                    $cmm_Amt = $comm_value['total_amount'];
                } 

            
        }
       
        return $cmm_Amt;
    }

    public function print_row($rechargeReports){
        foreach ($rechargeReports as $key => $value) {
           print_r($value['retailer_commission']);
           print_r("<br><br><br>");
        }
    }
    /**
     * Calculate Total Commission
     */
    public function calcTotalCommission($reports){
        $response =[];

        if($reports && !empty($reports)){
            $totalAdCom =0;
            $totalDtCom =0;
            $totalRtCom =0;
            $totalAmt =0;
            foreach ($reports as $key => $report) {
                $totalAdCom += isset($report['admin_commission']) ? ((float) $report['admin_commission']) : 0;
                $totalDtCom += isset($report['distributor_commission']) ? ((float) $report['distributor_commission']) : 0;
                $totalRtCom += isset($report['retailer_commission']) ? ((float) $report['retailer_commission']) : 0;
                $totalAmt += isset($report['total_amount']) ? ((float) $report['total_amount']) : 0;
            }

            $response['total_ad_comm'] = round($totalAdCom,2);
            $response['total_dt_comm'] = round($totalDtCom,2);
            $response['total_rt_comm'] = round($totalRtCom,2);
            $response['total_amount'] = round($totalAmt,2);
        }

        return $response;
    }

    /**
     * Check Report and Distribute Commission to Users here
     */
    public function checkRechargeReportCommission($reports)
    {
        $result = [];
        $tempOrdId = null;
        if (count($reports) > 0) {
            foreach ($reports as $ist => $outReport) {
                $key = [];
                if ($tempOrdId != $outReport['order_id']) {
                    foreach ($reports as $isnd => $inReport) {
                        if ($outReport['order_id'] == $inReport['order_id'] && $inReport['payment_type'] == Config::get('constants.PAYMENT_TYPE.PAYMT_SERVICE')) {
                            $key = $outReport;
                            $tempOrdId = $outReport['order_id'];
                        }

                        if ($outReport['order_id'] == $inReport['order_id'] && $inReport['payment_type'] == Config::get('constants.PAYMENT_TYPE.PAYMT_COMMISSION')) {

                            $userRoleAlias = $this->checkUserRole($inReport['user_id']);
                            if ($userRoleAlias) {
                                if ($userRoleAlias == 'retailer') {
                                    $key['retailer_commission'] = $inReport['total_amount'];
                                }

                                if ($userRoleAlias == 'distributor') {
                                    $key['distributor_commission'] = $inReport['total_amount'];
                                }

                                if ($userRoleAlias == 'admin') {
                                    $key['admin_commission'] = $inReport['total_amount'];
                                }
                            }
                        }
                    }
                }
                if (isset($key) && $key) {
                    array_push($result, $key);
                }
            }
        }
        return $result;
    }

    /**
     * Check User's Role
     */
    public function checkUserRole($userId)
    {
        $result = "";

        $userRole = User::where('userId', $userId)->pluck('roleId');

        if (count($userRole) > 0) {
            $roleAlias = Role::where('roleId', $userRole[0])->pluck('alias');
            $result = count($roleAlias) > 0 ? $roleAlias[0] : '';
        }

        return $result;
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
        }
    }

    /**
     * Set Filter data here
     */
    public function setFilterList($loggedInRole, $serviceType = null)
    {
        $filterLists = [];
        $strAppend = "";
        $strAppend = "_COM_ADMIN_FILTER";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_COM_ADMIN_FILTER";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_COM_DIS_FILTER";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_COM_RT_FILTER";
        }

        if ($serviceType) {
            $tableConstFD = "constants." . $serviceType . $strAppend;
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
        $strAppend = "_COM_ADMIN_TD";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_COM_ADMIN_TD";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_COM_DIS_TD";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_COM_RT_TD";
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
        $tranDtls = WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))
                                            // ->where('transaction_type', 'DEBIT')
                                            // ->where('payment_type', 'SERVICE')
                                            ->orderBy('trans_date','DESC');

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

        if ($request->has('api_id') && isset($request->api_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.api_id', $request->api_id);
        }

        if ($request->has('service_id') && isset($request->service_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.service_id', $request->service_id);
        }

        if ($request->has('operator_id') && isset($request->operator_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.operator_id', $request->get('operator_id'));
        }

        if ($request->has('order_status') && isset($request->order_status)) {
            $tranDtls->where('tbl_wallet_trans_dtls.order_status', $request->get('order_status'));
        }

        // if (Auth::user()->userId != Config::get('constants.ADMIN')) {
        //     if ($tranDtls) {
        //         $tranDtls->where('tbl_wallet_trans_dtls.user_id', Auth::user()->userId);
        //     }
        // }

        if (Auth::id() != Config::get('constants.ADMIN')) {
            if (Auth::id()) {
                $childResponse = User::where('parent_user_id', Auth::id())->pluck('userId');
                if (count($childResponse) > 0) {
                    $childResponse = $childResponse->toArray();
                    array_push($childResponse, Auth::id());
                    $tranDtls->whereIn('tbl_wallet_trans_dtls.user_id', $childResponse);
                } else {
                    $tranDtls->where('tbl_wallet_trans_dtls.user_id', Auth::user()->userId);
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

                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];

                        if ($head['label'] == "user_id") {
                            $label_val = User::getStoreNameById($report[$head['label']]);
                        }

                        if ($head['label'] == "mobile") {
                            $label_val = $this->getUserMobileById($report['user_id']);
                            // $label_val = "10000000000000";
                        }
                        // if ($head['label'] == "retailer_commission") {
                        //     // $label_val = $this->getUserMobileById($report['user_id']);
                        //     $label_val = "10000000000000";
                        // }

                        if ($head['label'] == "operator_id") {
                            $label_val = $this->getOperatorNameById($report[$head['label']]);
                        }

                        if ($head['label'] == "api_id") {
                            $label_val = $this->getApiSettingNameById($report[$head['label']]);
                        }

                        if ($head['label'] == "response") {
                            $label_val = $this->getResponseByOrdId($report['order_id']);
                        }

                        // if($head['label'] == "total_amount"){
                            
                        //     $label_val =  $this->getComissionByOrdId($report['order_id'], 'TOTAL');
                           
                            
                        // }
                         
                        if($head['label'] == "retailer_commission"){
                            
                            // $label_val =  $this->getComissionByOrdId($report['order_id'], 'RETAILER');
                            $label_val =  "101";
                           
                        }

                        // if($head['label'] == "distributor_commission"){
                            
                        //     $label_val =  $this->getComissionByOrdId($report['order_id'], 'DISTRIBUTOR');
                            
                        // }

                        // if($head['label'] == "admin_commission"){
                            
                        //     $label_val =  $this->getComissionByOrdId($report['order_id'], 'ADMIN');
                            
                        // }
                        // if ($head['label'] == "trans_date") {
                        //     $label_val = substr($report['trans_date'], 0, 8);
                        // }

                        $keyList[$head['label']] = $label_val;
                    } else {
                        $keyList[$head['label']] = "";
                    }
                }
                array_push($result, $keyList);
                $keyList = [];

            }
        }
       
        return $result;
    }

    

    public function getUserRoleById($user_id){
            $userRole = User::where('userId', $user_id)->get();
            
            return $userRole[0]['roleId'];
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
     * Get Commission Details
     */
    public function getCommissionDetails(Request $request)
    {
        $tranDtls = WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))->orderBy('updated_on', 'DESC');

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $fromDate = strtotime($fromDate);
            $fromDate = date('d/m/Y', $fromDate);
            $tranDtls->where('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $toDate = strtotime($toDate);
            $toDate = date('d/m/Y', $toDate);
            $tranDtls->where('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }

        if ($request->has('api_id') && isset($request->api_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.api_id', $request->api_id);
        }

        if ($request->has('service_id') && isset($request->service_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.service_id', $request->service_id);
        }

        if ($request->has('operator_id') && isset($request->operator_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.operator_id', $request->get('operator_id'));
        }

        if ($request->has('order_status') && isset($request->order_status)) {
            $tranDtls->where('tbl_wallet_trans_dtls.order_status', $request->get('order_status'));
        }

        $tranDtls = $tranDtls->get();
        $statusMsg = "Success!!";
        if ($tranDtls) {
            return $this->sendSuccess($tranDtls, $statusMsg);
        } else {
            return $this->sendError($tranDtls, $statusMsg);
        }
    }




    public function getCommissionReport(Request $request)
    {
        $rechargeReports_forinvoice=[];
        $user_dtls = Auth::user();
        $loggedInRole = Auth::user()->roleId;

        $serviceType = $request->input('service_type');

        $this->setPageName($serviceType);
        $pageName = $this->page_name;

        $filtersList = $this->setFilterList($loggedInRole, $serviceType);

        $rechargeReportTH = $this->setTableHeader($loggedInRole, $serviceType);

        // $rechargeReports = $this->filter($request);

        
        $rechargeReports = $this->filterNew($request);
      
        $operators = OperatorSetting::where('tbl_operator_settings.is_deleted', Config::get('constants.NOT-DELETED'))->where('tbl_operator_settings.activated_status', Config::get('constants.ACTIVE'));

        if (isset($request->service_id) && $request->service_id) {
            $operators = $operators->where('tbl_operator_settings.service_id', $request->service_id);
        }
       
        if ($request->has('service_type')) {
            if ($serviceType == Config::get('constants.RECHARGE')['NAME']) {
                $type1 = Config::get('constants.RECHARGE')['VALUE']['MOBILE'];
                $type2 = Config::get('constants.RECHARGE')['VALUE']['DTH'];
                $rechargeReports = $rechargeReports
                    ->leftJoin('tbl_services_type', 'tbl_wallet_trans_dtls.service_id', '=', 'tbl_services_type.service_id')
                    ->whereIn('tbl_services_type.alias', [
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                    ])
                    ->where('tbl_wallet_trans_dtls.id_deleted', Config::get('constants.NOT-DELETED'))
                    ->get();

                $operators = $operators
                    ->leftJoin('tbl_services_type', 'tbl_operator_settings.service_id', '=', 'tbl_services_type.service_id')
                    ->whereIn('tbl_services_type.alias', [
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'),
                        Config::get('constants.SERVICE_TYPE_ALIAS.DTH'),
                    ])->get();

            } else {
                $consType = 'constants.SERVICE_TYPE_ALIAS.' . $serviceType;
                $rechargeReports = $rechargeReports
                    ->leftJoin('tbl_services_type', 'tbl_wallet_trans_dtls.service_id', '=', 'tbl_services_type.service_id')
                    ->where('tbl_services_type.alias', Config::get($consType))
                    ->where('tbl_wallet_trans_dtls.id_deleted', Config::get('constants.NOT-DELETED'))->get();
                $rechargeReports_forinvoice = $rechargeReports;
                $operators = $operators
                    ->leftJoin('tbl_services_type', 'tbl_operator_settings.service_id', '=', 'tbl_services_type.service_id')
                    ->where('tbl_services_type.alias', Config::get($consType))->get();
            }
        } else {
            $rechargeReports->get();
        }
        $rechargeReports = $this->modifyCommissionRecords($rechargeReports);
        // print_r($rechargeReports);
        // exit();
        // $rechargeReports = $this->checkRechargeReportCommission($rechargeReports);
        $totalCommission = $this->calcTotalCommission($rechargeReports);
       
        // $rechargeReports = $this->modifyRechargeReport($rechargeReportTH, $rechargeReports);
        // print_r($rechargeReports);

        //    $rechargeReports = $this->addComissions($rechargeReports);
        //    $this->print_row($rechargeReports);
        //    exit();
        $rechargeReports = isset($rechargeReports) ? $rechargeReports : [];

        $this->export_file_name = $this->service_type . '_commission_report_' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($this->export_file_name, $rechargeReportTH, $rechargeReports);
            return $response;
        }

        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
       
        return view('modules.reports.report', compact('pageName', 'filtersList', 'rechargeReportTH', 'rechargeReports', 'apiSettings', 'servicesTypes', 'operators', 'request','totalCommission', 'rechargeReports_forinvoice', 'user_dtls'));
    
    
    }

     /**
     * Filter Transaction Reports Data
     */
    public function filterNew($request)
    {
        $tranDtls = WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))
                                            ->leftJoin('tbl_users', 'tbl_wallet_trans_dtls.user_id', 'tbl_users.userId')
                                            ->where('transaction_type', 'DEBIT')
                                            ->where('payment_type', 'SERVICE')
                                            ->orderBy('trans_date','DESC');

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $tranDtls->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $tranDtls->whereDate('tbl_wallet_trans_dtls.trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $tranDtls->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        }

        if ($request->has('api_id') && isset($request->api_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.api_id', $request->api_id);
        }

        if ($request->has('service_id') && isset($request->service_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.service_id', $request->service_id);
        }

        if ($request->has('operator_id') && isset($request->operator_id)) {
            $tranDtls->where('tbl_wallet_trans_dtls.operator_id', $request->get('operator_id'));
        }

        if ($request->has('order_status') && isset($request->order_status)) {
            $tranDtls->where('tbl_wallet_trans_dtls.order_status', $request->get('order_status'));
        }

        // if (Auth::user()->userId != Config::get('constants.ADMIN')) {
        //     if ($tranDtls) {
        //         $tranDtls->where('tbl_wallet_trans_dtls.user_id', Auth::user()->userId);
        //     }
        // }

        // if (Auth::id() != Config::get('constants.ADMIN')) {
        //     if (Auth::id()) {
        //         $childResponse = User::where('parent_user_id', Auth::id())->pluck('userId');
        //         if (count($childResponse) > 0) {
        //             $childResponse = $childResponse->toArray();
        //             array_push($childResponse, Auth::id());
        //             $tranDtls->whereIn('tbl_wallet_trans_dtls.user_id', $childResponse);
        //         } else {
        //             $tranDtls->where('tbl_wallet_trans_dtls.user_id', Auth::user()->userId);
        //         }
        //     }
        // }

        return $tranDtls;
    }

    public function modifyCommissionRecords($records){
        $report=[];

        if(count($records)>0){
            foreach ($records as $key => $value) {
                // print_r($value);
               $report[$key]['trans_date'] =  $value['trans_date'];
               $report[$key]['user_id'] =  $value['store_name'];
               $report[$key]['order_id'] =  $value['order_id'];
               $report[$key]['api_id'] =  $this->getApiSettingNameById($value['api_id']);
               $report[$key]['operator_id'] =   $this->getOperatorNameById($value['operator_id']);
               $report[$key]['mobile'] = $this->getUserMobileById($value['user_id']);;
               $report[$key]['total_amount'] =  $value['total_amount'];
               $report[$key]['retailer_commission'] =  $value['total_amount'];
               $report[$key]['distributor_commission'] =  $value['total_amount'];
               $report[$key]['admin_commission'] =  $value['total_amount'];
            }
        }
        
        return $report;

    }

}
