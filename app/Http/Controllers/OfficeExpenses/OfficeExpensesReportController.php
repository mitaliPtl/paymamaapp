<?php

namespace App\Http\Controllers\OfficeExpenses;
use App\Category;
use App\Role;
use App\ApiLogDetail;
use App\ApiSetting;
use App\ExpensesReport;
use App\BankAccount;

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
            
            // $report = $this->filter_report($request, $loggedInRole);
            // $all_expenses = $report;
            // $report = $this->modifyReport($reportListTH, $report);
            

            $categories = Category::where('category_is_deleted', Config::get('constants.NOT-DELETED'))->get();
            $bank_acc = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
            
            $expenses_report = $this->filterExpense($request, $loggedInRole);
            $expenses_report = $this->modifyExpense($expenses_report);
            
            $export_file_name = 'Expense_report_' . date('Y_m_d_H:i:s');
            if (isset($request->is_export) && $request->is_export == 1) {
                $response = $this->exportPDF($export_file_name, $reportListTH, $expenses_report);
                return $response;
            }

            $total = $this->calcTotal($expenses_report);
        //    print_r($total['total_balnc']);
        //    exit();
            // return view('modules.office_expenses.office_expenses_report', compact('filtersList', 'categories', 'report', 'reportListTH', 'request', 'all_expenses', 'bank_acc', 'expenses_report'));
            return view('modules.office_expenses.office_expenses_report', compact('filtersList', 'categories', 'reportListTH', 'request', 'bank_acc', 'expenses_report', 'total'));
    }

    public function calcTotal($report){
        $total_amt=0.000;
        $total_balnc=0.000;

        if(count($report)>0){
            foreach ($report as $key => $value) {
                if($value['amount']){
                    $total_amt = (float) $total_amt + (float) $value['amount'];
                }

                if($value['balance']){
                    $total_balnc = (float) $total_balnc + (float) $value['balance'];
                }
                
            }
        }
        $result['total_amt'] = $total_amt;
        $result['total_balnc'] = $total_balnc;
        return $result;
    }

    public function modifyExpense($expenses_report){
         
        $report=[];

        if (count($expenses_report)>0) {
            foreach ($expenses_report as $key => $value) {

                $report[$key]['date'] = $value['date'];
                $report[$key]['category_bank'] = $value['category_bank'];
                $report[$key]['description'] = $value['description'];
                $report[$key]['account_name'] = $value['account_name'];
                $report[$key]['cr_dr'] = $value['cr_dr'];
                $report[$key]['amount'] = $value['amount'];
                $report[$key]['balance'] = $value['balance'];

                
            }
        }

        return $report;
        
    }   

    public function filterExpense($request, $loggedInRole){
       
        
        $report = ExpensesReport::orderBy('date', 'DESC');

            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $report->whereDate('date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $report->whereDate('date', $fromDate);
            }

            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $report->whereDate('date', '<=', $toDate);
            }

            if ($request->has('filter_category_id') && isset($request->filter_category_id)) {
                $filter_category_id = $request->get('filter_category_id');
                $report->where('category_bank', '=', $filter_category_id);
            }
            if ($request->has('filter_bank_name') && isset($request->filter_bank_name)) {
                $filter_bank_name = $request->get('filter_bank_name');
                $report->where('account_name', '=', $filter_bank_name);
            }
        

        $report = $report->get();
        return $report;
                                                
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
        
        // print_r($request->all());
        // exit();
           
            $user_id = Auth::user()->userId;
           

            if($request->bank_acc == 'Wallet'){
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

                    $exp_cat = Category::where('category_id', $request->expenses_type)->get();

                    $expenses_report = ExpensesReport::create([
                                                        "user_id"=> $user_id,
                                                        "bank_id"=> $request->bank_acc,
                                                        "category_id" => $request->expenses_type,
                                                        "date" => now(),
                                                        "category_bank" => $exp_cat[0]['category'],
                                                        "account_name" => "Wallet",
                                                        "description"=> $request->expense_description,
                                                        "cr_dr"=> "DEBIT",
                                                        "amount" => $request->expense_amt,
                                                        "balance" => $balance
                                                    ]);
                }

            }else{

                
                $bnk_name = '';
                $bnk_id = '';
                $bnk_name = '';

                $bank_dtls = $this->getBankByID($request->bank_acc);
                if((int)$bank_dtls['balance'] < 0){
                    return  back()->with("error", " Bank Does Not Have Balance !!"); 
                }
                $blnce = (float) $bank_dtls['balance'] - (float) $request->expense_amt;

                //upadate balance
                $bnk_blnce = BankAccount::where('id', $request->bank_acc)->update([ "balance" =>$blnce ]);

                $exp_cat = Category::where('category_id', $request->expenses_type)->get();

                $expenses_report = ExpensesReport::create([
                                                    "user_id"=> $user_id,
                                                    "bank_id"=> $request->bank_acc,
                                                    "category_id" => $request->expenses_type,
                                                    "date" => now(),
                                                    "category_bank" => $exp_cat[0]['category'],
                                                    "account_name"=> $bank_dtls['bank_name'],
                                                    "description"=> $request->expense_description,
                                                    "cr_dr"=> "DEBIT",
                                                    "amount" => $request->expense_amt,
                                                    "balance" => $blnce
                                                ]);

            }
           
            if($expenses_report){
               
               
                // $this->insertExpensesReport($user_id, $request, $bank_dtls);
                return  back()->with("success", "Expense Added successfully!!"); 
    
            }else{
                
                return  back()->with("error", " Expense Not Added !!"); 
    
            }
    }

    public function insertExpensesReport($user_id, $request, $bank_dtls){

        $exp_cat = Category::where('category_id', $request->expenses_type)->get();

        $expenses_report = ExpensesReport::create([
                                            "user_id"=> $user_id,
                                            "bank_id"=> $request->bank_acc,
                                            "category_id" => $request->expenses_type,
                                            "date" => now(),
                                            "category_bank" => $exp_cat[0]['category'],
                                            "description"=> $request->expense_description,
                                            "cr_dr"=> "DEBIT",
                                            "amount" => $request->expense_amt,
                                            "balance" => $balReq->amount
                                        ]);
        return $expenses_report;
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

    public function getBankByID($id){

        $bankDtls = BankAccount:: where('id', $id)->get();

        return $bankDtls[0];
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
   

}
