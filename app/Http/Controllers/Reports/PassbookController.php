<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\User;
use App\WalletTransactionDetail;
use Auth;
use Config;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class PassbookController extends Controller
{
    protected $page_name = "Passbook";
    protected $export_file_name = "";

    /**
     * Get all Passbook Reports
     */
    public function index(Request $request)
    {

        $loggedInRole = Auth::user()->roleId;

        $pageName = $this->page_name;

        $filtersList = $this->setFilterList($loggedInRole);

        $rechargeReportTH = $this->setTableHeader($loggedInRole);

        $rechargeReports = $this->filter($request);

        $rechargeReports = $rechargeReports->take(2500)->get();

        $rechargeReports = $this->modifyRechargeReport($rechargeReportTH, $rechargeReports);

        $rechargeReports = isset($rechargeReports) ? $rechargeReports : [];



        $this->export_file_name = 'passbook_' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($this->export_file_name, $rechargeReportTH, $rechargeReports);
            return $response;
        }

        return view('modules.reports.report', compact('pageName', 'filtersList', 'rechargeReportTH', 'rechargeReports', 'request'));
    }

    /**
     * Set Filter data here
     */
    public function setFilterList($loggedInRole)
    {
        $filterLists = [];
        $strAppend = "";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "PASSBOOK_ADMIN_FILTER";
        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "PASSBOOK_DIS_FILTER";
        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "PASSBOOK_RT_FILTER";
        }else if ($loggedInRole == Config::get('constants.MASTER_DISTRIBUTOR')) {

            $strAppend = "PASSBOOK_MD_FILTER";
        }

        if ($strAppend) {
            $tableConstFD = "constants." . $strAppend;
            $filterLists = Config::get($tableConstFD);
        }

        return $filterLists;
    }

    /**
     * Get Table Header
     */
    public function setTableHeader($loggedInRole)
    {
        $rechargeReportTH = [];
        $strAppend = "";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "PASSBOOK_ADMIN_TD";
        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "PASSBOOK_DIS_TD";
        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "PASSBOOK_RT_TD";
        }else if ($loggedInRole == Config::get('constants.MASTER_DISTRIBUTOR')) {

            $strAppend = "PASSBOOK_MD_TD";
        }

        if ($strAppend) {
            $tableConstHD = "constants." . $strAppend;
            $rechargeReportTH = Config::get($tableConstHD);
        }

        return $rechargeReportTH;
    }

    /**
     * Filter Transaction Reports Data
     */
    public function filter($request)
    {

        $tranDtls = WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))->orderBy('id', 'DESC')
            ->leftJoin('tbl_services_type', 'tbl_wallet_trans_dtls.service_id', '=', 'tbl_services_type.service_id');

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $tranDtls->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $tranDtls->whereDate('trans_date', '<=', $toDate);
        }

        if (!$request->has('from_date') && !isset($request->from_date) && !$request->has('to_date') && !isset($request->to_date) || $request->from_date == null  || $request->to_date == null) {

            $tranDtls->whereDate('trans_date', '=', Carbon::now()->format('Y-m-d'));
        }

        // if (Auth::id() != Config::get('constants.ADMIN')) {
        if ($tranDtls) {
            $tranDtls->where('user_id', Auth::user()->userId);
        }
        // }


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
                // $reportList[$repInd]['cr_dr'] = "";
                foreach ($tableHeads as $headInd => $head) {

                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];

                        if (($head['label'] == "payment_mode" && $report[$head['label']] == Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY'))) {
                            $label_val = "Money added via Payment Gateway";
                        } else if (($head['label'] == "payment_mode" && $report[$head['label']] == Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'))) {
                            $label_val = "Direct Transfer";
                        } elseif ($head['label'] == 'payment_type') {
                            if ((isset($report['service_name'])) &&  ($report['service_name'])) {
                                $label_val = $report['service_name'];
                            } else {
                                $label_val = $report['payment_type'];
                            }
                        }
                        // elseif ($head['label'] == 'debit_amount') {
                        //     if ($report['transaction_type'] == 'DEBIT') {
                        //         $label_val = $report['total_amount'];
                        //     }
                        // }
                        $keyList[$head['label']] = $label_val;
                    } elseif (($head['label'] == 'credit_amount')  && ($report['transaction_type'] == 'CREDIT')) {
                        $keyList[$head['label']] = $report['total_amount'];
                    } elseif (($head['label'] == 'debit_amount') && ($report['transaction_type'] == 'DEBIT')) {
                        $keyList[$head['label']] = $report['total_amount'];
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
     * Get Passbook Details
     */
    public function getPassbookDetails(Request $request)
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
            $tranDtls->where('trans_date', '<=', $toDate);
        }

        $tranDtls = $tranDtls->get();
        $statusMsg = "Success!!";
        if ($tranDtls) {
            return $this->sendSuccess($tranDtls, $statusMsg);
        } else {
            return $this->sendError($tranDtls, $statusMsg);
        }
    }

    /**
     * Set Filter data here
     */
    public function setMbrPbFilterList($loggedInRole)
    {
        $filterLists = [];
        $strAppend = "";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "MEMBER_PASSBOOK_ADMIN_FILTER";
        }

        if ($strAppend) {
            $tableConstFD = "constants." . $strAppend;
            $filterLists = Config::get($tableConstFD);
        }

        return $filterLists;
    }

    /**
     * Filter Transaction Reports Data
     */
    public function memberPassbookfilter($request)
    {

        $tranDtls = null;
        if (isset($request->username_mobile) && $request->username_mobile) {
            $tranDtls = WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))->orderBy('id', 'DESC');

            $userId = User::where('mobile', $request->username_mobile)
                ->orWhere('username', $request->username_mobile)->pluck('userId')->first();
            if ($userId) {
                $tranDtls = $tranDtls->where('user_id', $userId);
            }
        }
        if ($request->has('to_date') && $request->to_date != null) {
            $tranDtls = $tranDtls->whereDate('trans_date', '<=', $request->to_date);
        }
        if ($request->has('from_date') && $request->from_date != null) {
            $tranDtls = $tranDtls->whereDate('trans_date', '>=', $request->from_date);
        }



        return $tranDtls;
    }

    /**
     * Member Passbook View
     */
    public function memberPassbook(Request $request)
    {

        $loggedInRole = Auth::user()->roleId;

        $pageName = $this->page_name;

        $filtersList = $this->setMbrPbFilterList($loggedInRole);

        $rechargeReportTH = $this->setTableHeader($loggedInRole);

        $rechargeReports = $this->memberPassbookfilter($request);

        $rechargeReports = $rechargeReports ? $rechargeReports->get() : null;

        $rechargeReports = $this->modifyRechargeReport($rechargeReportTH, $rechargeReports);

        $rechargeReports = isset($rechargeReports) ? $rechargeReports : [];

        $this->export_file_name = 'passbook_' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($this->export_file_name, $rechargeReportTH, $rechargeReports);
            return $response;
        }
        return view('modules.reports.report', compact('pageName', 'filtersList', 'rechargeReportTH', 'rechargeReports', 'request'));
    }


    public function memberPassbookPaymama(Request $request)
    {


        $tranDtls = null;
        $errMessage = null;
        if (isset($request->username_mobile) && $request->username_mobile) {
            

            $userId = User::where('mobile', $request->username_mobile)
                ->orWhere('username', $request->username_mobile)->where('parent_user_id', Auth::user()->userId)->pluck('userId')->first();
            if ($userId !== null) {
                $tranDtls = WalletTransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'))->orderBy('id', 'DESC');
                $tranDtls = $tranDtls->where('user_id', $userId);
            }
            else{
                $errMessage = 'THIS USER IS NOT MAPPED UNDER YOU';
            }
        }
        if ($request->has('to_date') && $request->to_date != null) {
            $tranDtls = $tranDtls->whereDate('trans_date', '<=', $request->to_date);
        }
        if ($request->has('from_date') && $request->from_date != null) {
            $tranDtls = $tranDtls->whereDate('trans_date', '>=', $request->from_date);
        }

        
        if (isset($request->username_mobile) && $request->username_mobile) {
            if ($userId !== null) {
        $tranDtls= $tranDtls->get();
        }}
       
        

        return view('modules.reports.reports_pm', compact('tranDtls','errMessage'));
    }
}
