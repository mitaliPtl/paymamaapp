<?php

namespace App\Http\Controllers\TDS;
use Auth;
use Config;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Http\Controllers\Controller;
use App\Role;
use App\TDS;
use App\WalletTransactionDetail;

class TDSController extends Controller{

    public function index(Request $request){
        // print_r($request->all());
        $users = User::select('userId', 'roleId', 'store_name', 'username')
                        ->where('roleId', Config::get('constants.DISTRIBUTOR'))
                        ->orWhere('roleId', Config::get('constants.RETAILER'))
                        ->get();
        // print_r($users);

        return view('modules.tds.tds', compact('users'));
    } 

    public function uploadTDS(Request $request){
        // print_r($request->all());

        $uploaded_tds = TDS::create([
                            'user_id'=> $request->user_id,
                            "role_id" =>$request->role_id,
                            "tds_period"=>$request->tds_period,
                            "file_id"=>$request->uploaded_file_id,
                            "created_on"=>now(), 

                        ]);
        if($uploaded_tds){
            return  back()->with("success", "TDS Uploaded successfully!!"); 
        }else{
            return  back()->with("error", " TDS Not Uploaded !!"); 
        }
    }

    public  function viewTDSById($id = null, Request $request){
        // print_r($id);
        $tds_user=[];
        $tds_user = TDS::leftJoin('tbl_files', 'tbl_tds.file_id', '=', 'tbl_files.id')
                        ->where('user_id', $id)->get();
        
        // print_r($tds_user);

        return view('modules.tds.viewTDSByUserId', compact('tds_user'));
        
    }

    public function getUserTDSAPI(Request $request){

        if(isset($request->user_id) && $request->user_id){
            $tds_user = TDS::leftJoin('tbl_files', 'tbl_tds.file_id', '=', 'tbl_files.id')
                            ->where('user_id', $request->user_id)->get();

            if($tds_user){
                $statusMsg = "Success !!";
                $success = [];
                
                foreach($tds_user as $tds_user_key => $tds_user_value){
                    
                  
                    $success[$tds_user_key]['file_path'] = Config::get('constants.WEBSITE_BASE_URL'). $tds_user_value->file_path;
                    $success[$tds_user_key]['tds_period'] = $tds_user_value->tds_period;
                    $success[$tds_user_key]['created_on'] = $tds_user_value->created_on;
                    // $success['tds_period'] = $tds_user_value->tds_user;
                    // $success['file_path'] = $tds_user_value->file_path;
                    // $success['created_on'] = $tds_user_value->created_on;
                }
               
                return $this->sendSuccess($success, $statusMsg);
            }else{
                return $this->sendError('TDS Not Found !!');
            } 

        }else{
            return $this->sendError('User Required Not Found !!');
        }
        
    }

    public function tdsReport(Request $request){
        // print_r( date('m-01-Y'));

        $tds_report = $this->tdsfilter($request);
        // print_r($tds_report);

        $tds_report = $this->modifyTDS($tds_report, $request);

        $filtersList = Config::get('constants.TDS_REPORT_FILTER');
        $reportTH = Config::get('constants.TDS_REPORT');

        // print_r($tds_filter);
        $total_tds = 0.000;
        $total_tds = $this->totalTDS($tds_report);
        return view('modules.tds.tds_report', compact('tds_report', 'filtersList', 'reportTH', 'request', 'total_tds'));
        
    }

    public function tdsfilter($request){
        // $report = TDS::leftJoin('tbl_files', 'tbl_tds.file_id', '=','tbl_files.id')
        //                 ->leftJoin('tbl_users', 'tbl_tds.user_id', '=','tbl_users.userId');

        // $report = User::leftJoin('tbl_wallet_trans_dtls', 'tbl_users.userId', '=','tbl_wallet_trans_dtls.user_id');
        $report = User::where('activated_status', Config::get('constants.ACTIVE'))->orderBy('userId', 'asc');
                        
        

        // if ($request->has('from_date') && isset($request->from_date)) {
        //     $fromDate = $request->get('from_date');
        //     $report->whereDate('tbl_wallet_trans_dtls.trans_date', '>=', $fromDate);
        // }else {

        //     $fromDate = now();
        //     $fromDate = date('m-01-Y');
        //     $report->whereDate('tbl_wallet_trans_dtls.trans_date','>=', $fromDate);
        // }
        // if ($request->has('to_date') && isset($request->to_date)) {
        //     $toDate = $request->get('to_date');
        //     $report->whereDate('tbl_wallet_trans_dtls.trans_date', '<=', $toDate);
        // }
           $report = $report->distinct()->get();

           return $report;
    }

    public function modifyTDS($report, $request){
        $tsd_report = [];
        foreach ($report as $key => $value) {
        //   print_r($value['userId']);
            if($value['roleId']  != Config::get('constants.ADMIN')){
                $tsd_report[$key]['tds_id'] = $value['tds_id'];
                $tsd_report[$key]['user_id'] = $value['userId'];
                $tsd_report[$key]['role_id'] = $value['roleId'];
                $tsd_report[$key]['username'] = $value['username'];
                $tsd_report[$key]['store_name'] = $value['store_name'];
                $tsd_report[$key]['name'] = $value['first_name'] ." ".$value['last_name'];
                $tsd_report[$key]['pan_no'] = $value['pan_no'];
                $tsd_report[$key]['createdDtm'] = $value['createdDtm'];
                $tsd_report[$key]['tds_amount'] = $this->getTotalTDSByUserId($request, $value['userId']);
            }
          
         
        }

        return $tsd_report;

    }

    public function getTotalTDSByUserId($request, $user_id){
        $total_tds_records = WalletTransactionDetail::where('user_id', $user_id);

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $total_tds_records->whereDate('trans_date', '>=', $fromDate);
        }else {

            // $fromDate = now();
            $fromDate = date('m-01-Y');
            $total_tds_records->whereDate('trans_date','>=', $fromDate);
           

        }


        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $total_tds_records->whereDate('trans_date', '<=', $toDate);
        }

        $total_tds_records = $total_tds_records->get();
        $total_tds = 0.0000;
        if(count($total_tds_records)>0){
            foreach ($total_tds_records as $key => $value) {
               $total_tds = (float) $total_tds + (float) $value['TDSamount'];
            }
        }
        
        return $total_tds;
    }   

    public function tdsHistoryByDate(Request $request){
        //         print_r($request->all());
        //    exit();
        
        $tds_report = WalletTransactionDetail::where('user_id', $request->user_id_form);

        if ($request->has('from_date_form') && isset($request->from_date_form)) {

            $fromDate = $request->get('from_date_form');
            $tds_report->whereDate('trans_date', '>=', $fromDate);

        }elseif($request->has('from_date') && isset($request->from_date)) {
            
            $fromDate = date($request->get('from_date'));
            $tds_report->whereDate('trans_date', '>=', $fromDate);

        }else{
            
            $fromDate = date('Y-m-01');
            $tds_report->whereDate('trans_date','>=', $fromDate);
        }



        if ($request->has('to_date_form') && isset($request->to_date_form)) {

            $toDate = $request->get('to_date_form');
            $tds_report->whereDate('trans_date', '<=', $toDate);

        }elseif($request->has('to_date') && isset($request->to_date)){

            $toDate = $request->get('to_date');
            $tds_report->whereDate('trans_date', '<=', $toDate);

        }

        $tds_report = $tds_report->get();
   
        $tds_report = $this->modifyTDSByDate($tds_report);

        $filtersList = Config::get('constants.TDS_REPORT_FILTER');

        $reportTH = Config::get('constants.TDS_HISTORY');

        // print_r($tds_filter);
        $user_id_form = $request->user_id_form;

        $total_tds = $this->tsdAmount($tds_report);
        $total_amt = $this->totalAmount($tds_report);
        $total_cashback = $this->totalCashback($tds_report);

        return view('modules.tds.tds_report', compact('tds_report', 'filtersList', 'reportTH', 'request', 'user_id_form', 'total_tds', 'total_amt', 'total_cashback'));

    }

    public function modifyTDSByDate($tds_report){
        $report=[];
        
        if(count($tds_report)> 0){
            foreach ($tds_report as $key => $value) {
                if($value['TDSamount']){
                    $report[$key]['trans_date'] =$value['trans_date'];
                    $report[$key]['order_id'] =$value['order_id'];
                    $report[$key]['total_amount'] =$value['total_amount'];
                    $report[$key]['Cashback'] =$value['Cashback'];
                    $report[$key]['TDSamount'] =$value['TDSamount'];
                }
                
            }
        }

        return  $report;
        
    }

    public function totalTDS($records){
        $total = 0.000;

        foreach ($records as $key => $value) {
           if($value['tds_amount']){
               $total = (float) $total + (float) $value['tds_amount'];
           }

          
        }
        return $total;
    }

    public function totalAmount($records){
        $total = 0.000;

        foreach ($records as $key => $value) {
           if($value['total_amount']){
               $total = (float) $total + (float) $value['total_amount'];
           }
        }
        return $total;
    }

    public function totalCashback($records){
        $total = 0.000;

        foreach ($records as $key => $value) {
           if($value['Cashback']){
               $total = (float) $total + (float) $value['Cashback'];
           }
        }
        return $total;
    }

    public function tsdAmount($records){
        $total = 0.000;

        foreach ($records as $key => $value) {

            if($value['TDSamount']){
                $total = (float) $total + (float) $value['TDSamount'];
            }
        }
        return $total;
    }
}