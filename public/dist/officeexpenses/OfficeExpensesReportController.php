<?php

namespace App\Http\Controllers\OfficeExpenses;
use App\Category;
use App\Role;
use App\ApiLogDetail;
use App\ApiSetting;

use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;
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


class OfficeExpensesReportController extends Controller
{
    public function index(Request $request){
            // print_r("sdcs");
           
           
    
            $user_dtls = Auth::user();
            $serviceType = 'OFFICE_EXPENSES';
            $loggedInRole = Auth::user()->roleId; 
            $filtersList = Config::get('constants.OFFICE_EXPENSES_ADMIN_FILTER');
            $reportListTH = Config::get('constants.OFFICE_EXPENSES_ADMIN_TD');
            
            $report = $this->filter_report($request, $loggedInRole);
            $all_expenses = $report;
            $report = $this->modifyReport($reportListTH, $report);
            

            $categories = Category::where('category_is_deleted', Config::get('constants.NOT-DELETED'))
                                ->get();
           
            return view('modules.office_expenses.office_expenses_report', compact('filtersList', 'categories', 'report', 'reportListTH', 'request', 'all_expenses'));
    }

    public function filter_report($request, $loggedInRole){


        $report = WalletTransactionDetail::leftJoin('tbl_category', 'tbl_wallet_trans_dtls.expense_category', '=', 'tbl_category.category_id')
                                                ->where('payment_type', 'OFFICE_EXPENSES')
                                                ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                                ->where('user_id', Config::get('constants.ADMIN'))
                                                ->orderBy('trans_date', 'DESC');
                                                
                                                // ->get();
        if($loggedInRole == Config::get('constants.ADMIN')){
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $report->whereDate('Trans_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $report->whereDate('Trans_date', $fromDate);
            }

            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $report->whereDate('Trans_date', '<=', $toDate);
            }

        }

        if (isset($request->filter_category_id) && $request->filter_category_id) {
            $report->where('expense_category', $request->filter_category_id);
        }

        $report = $report->get();
        return $report;
                                                
    }

     /**
     * Modify Recharge Reports List
     */
    public function modifyReport($tableHeads, $reportList)
    {
        $result = [];
        if ($reportList) {
            foreach ($reportList as $repInd => $report) {
                $keyList = [];
                $reportList[$repInd]['mobile'] = "";
                $reportList[$repInd]['response'] = "";
                foreach ($tableHeads as $headInd => $head) {
                    $keyList['id'] = $report['id'];

                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];

                       
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

    public function addOfficeExpense(Request $request){
       
            $user_id = Auth::user()->userId;
            $balance = $this->getBalanceByUser($user_id);
           
            $balance = (float)$balance - (float)$request->expense_amt;
            
            $insert_OfficeExpaense = WalletTransactionDetail::create([
                                    'order_id'=>'OFFICE',
                                    'user_id' => Config::get('constants.ADMIN'),
                                    'transaction_type'=> 'DEBIT',
                                    'trans_date'=>now(),
                                    // 'trans_date'=>$request->expense_date,
                                    'payment_type'=>'OFFICE_EXPENSES',
                                    'payment_mode'=>$request->expense_description,
                                    'expense_category'=>$request->expenses_type,
                                    'total_amount'=>$request->expense_amt,
                                    'balance'=>$balance
                                ]);
            if($insert_OfficeExpaense){
               
                $this->update_UserBalance( $user_id, $balance);

                return  back()->with("success", "Expense Added successfully!!"); 
    
            }else{
                
                return  back()->with("error", " Expense Not Added !!"); 
    
            }

            

    }

    public function getBalanceByUser($user_id){

        
        $getbalance = User::where('userId' , $user_id)->get();
        return $getbalance[0]['wallet_balance'];

    }

    public function update_UserBalance($user_id, $balance){
        $update_balance = User::where('userId', $user_id)
                                ->update([
                                    'wallet_balance' => $balance,
                                    'updatedDtm'=>now()
                                ]);
        return $update_balance;

    }

}
