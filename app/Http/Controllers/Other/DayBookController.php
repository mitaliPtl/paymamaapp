<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\TransactionDetail;
use App\WalletTransactionDetail;
use Auth;
use Config;
use Illuminate\Http\Request;

class DayBookController extends Controller
{
    /**
     * Day Book List
     */
    public function index(Request $request)
    {  
        $today = date('Y-m-d');
        $reports = $this->filter($request);
        $reports = $this->modifyReports($reports);

        $total = $this->calcTotalCharges($reports);
        // print_r($total);
        // exit();
        return view('modules.other.day_book', compact('request', 'reports', 'today', 'total'));
    }

    /**
     * Filter Transaction Reports Data
     */
    public function filter($request)
    {
        $tranDtls = TransactionDetail::where('id_deleted', Config::get('constants.NOT-DELETED'));

        if ($request->has('date') && isset($request->date)) {
            $date = $request->get('date');
            $tranDtls->whereDate('trans_date', $date);
        } else {
            $date = now();
            $tranDtls->whereDate('trans_date', $date);
        }

        return $tranDtls->get();
    }

    /**
     * Modify Reports for Daybook
     */
    public function modifyReports($reports)
    {
        $result = [];

        $operators = OperatorSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        if ($operators && $reports) {
            foreach ($operators as $opInd => $operator) {
                $key = [];
                $totalHits = 0;
                $totalAmt = 0;
                $successHits = 0;
                $successAmt = 0;
                $failureHits = 0;
                $failureAmt = 0;
                $commission = 0;
                foreach ($reports as $repInd => $report) {

                    if ($report->operator_id == $operator->operator_id) {

                        $operatorId = $operator->operator_id;
                        $operatorName = $operator->operator_name;

                        $commissionRes = WalletTransactionDetail::where('operator_id', $report->operator_id)
                            ->where('service_id',$report->service_id)
                            ->where('order_id',$report->order_id)
                            ->where('transaction_id',$report->transaction_id)
                            ->where('payment_type',Config::get('constants.PAYMENT_TYPE.PAYMT_COMMISSION'))
                            ->where('user_id',Auth::id())
                            ->pluck('total_amount')->first();

                        $totalHits = $totalHits + 1;
                        $totalAmt = $totalAmt + $report->total_amount;
                        $successHits = $successHits + ($report->order_status == "SUCCESS" ? 1 : 0);
                        $successAmt = $successAmt + ($report->order_status == "SUCCESS" ? $report->total_amount : 0);
                        $failureHits = $failureHits + ($report->order_status == "FAILED" ? 1 : 0);
                        $failureAmt = $failureAmt + ($report->order_status == "FAILED" ? $report->total_amount : 0);
                        $commission = $commission + $commissionRes;

                        $key["operator_id"] = $operatorId;
                        $key["operator_name"] = $operatorName;
                        $key["total_hits"] = $totalHits;
                        $key["total_amount"] = $totalAmt;
                        $key["success_hits"] = $successHits;
                        $key["success_amount"] = $successAmt;
                        $key["failure_hits"] = $failureHits;
                        $key["failure_amount"] = $failureAmt;
                        $key["commission"] = $commission;
                    }
                }
                if ($key) {
                    array_push($result, $key);
                    $key = [];
                }
            }
        }
        return $result;
    }

    public function calcTotalCharges($reports){
        $response =[];

        if($reports && !empty($reports)){
            $totalHits =0;
            $totalAmt =0;
            $totalSuccessHits =0;
            $totalSuccessAmt =0;
            $totalFailureHits =0;
            $totalFailureAmt =0;
            $totalCommission =0;
            
            // $totalRtCom =0;
            // $totalAmt =0;
            foreach ($reports as $key => $report) {
                $totalHits += isset($report['total_hits']) ? ((float) $report['total_hits']) : 0;
                $totalAmt += isset($report['total_amount']) ? ((float) $report['total_amount']) : 0;
                $totalSuccessHits += isset($report['success_hits']) ? ((float) $report['success_hits']) : 0;
                $totalSuccessAmt += isset($report['success_amount']) ? ((float) $report['success_amount']) : 0;
                $totalFailureHits += isset($report['failure_hits']) ? ((float) $report['failure_hits']) : 0;
                $totalFailureAmt += isset($report['failure_amount']) ? ((float) $report['failure_amount']) : 0;
                $totalCommission += isset($report['commission']) ? ((float) $report['commission']) : 0;
                // $totalRtCom += isset($report['retailer_commission']) ? ((float) $report['retailer_commission']) : 0;
                // $totalAmt += isset($report['total_amount']) ? ((float) $report['total_amount']) : 0;
            }

            $response['total_hits'] = round($totalHits,2);
            $response['total_amount'] = round($totalAmt,2);
            $response['success_hits'] = round($totalSuccessHits,2);
            $response['success_amount'] = round($totalSuccessAmt,2);
            $response['failure_hits'] = round($totalFailureHits,2);
            $response['failure_amount'] = round($totalFailureAmt,2);
            $response['commission'] = round($totalCommission,2);
           
            // $response['total_rt_comm'] = round($totalRtCom,2);
            // $response['total_amount'] = round($totalAmt,2);
        }

        return $response;
    }

}
