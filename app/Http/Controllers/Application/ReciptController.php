<?php

namespace App\Http\Controllers\Application;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApplicationDetail;
use App\TransactionDetail;
use DB;
use Config;
use PDF;
use App\User;
class ReciptController extends Controller
{
    public function getRecipt(Request $request){
        // $result = [];
        $ttl_amt=0;
        $tranDtls_gid=[];
        // $appDetail = ApplicationDetail::select(['alias','value'])->get(); 
        $tranDtls = TransactionDetail:: leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                        // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                        ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                        ->where('tbl_transaction_dtls.user_id', $request->user_id)
                        ->where('tbl_transaction_dtls.order_id', $request->order_id)
                        // ->limit(1)
                        ->get();
        if (isset($tranDtls) && count($tranDtls) > 0) {
            
            
            if(!empty($tranDtls[0]['group_id']) && $tranDtls[0]['group_id']){

                $grp_id = $tranDtls[0]['group_id'];
                $tranDtls_gid = TransactionDetail:: leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                                                    // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                                                    ->where('tbl_transaction_dtls.user_id', $request->user_id)
                                                    ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                                    ->where('tbl_transaction_dtls.group_id', $grp_id)
                                                    ->get();
                foreach($tranDtls_gid as $tranDtls_gid_key => $tranDtls_gid_value){
                   $ttl_amt = (int)$ttl_amt + (int) $tranDtls_gid_value['total_amount'];
                }
            }else{
                $ttl_amt = (int)$tranDtls[0]['total_amount'];
            }
            
            $user = User::where("userId", "=", $request->user_id)->get() ;
            $surcharges =  $request->surcharge;
            $response = $this->exportPDF($tranDtls, $tranDtls_gid, $surcharges, $ttl_amt, $user);
            return $response;
            $response = base64_encode($response);
        
            $statusMsg = "Success!!";


            $result = [
                'pdf_base64' => $response,
            ];
            
            return $this->sendSuccess($result, $statusMsg);
            // if(count($tranDtls_gid) > 0){
            // return $this->sendSuccess($tranDtls_gid, $statusMsg);

            // }
            // return $this->sendSuccess($tranDtls, $statusMsg);
        } else {
            return $this->sendError("No records found!!");
        }

    }

   
    public function exportPDF($tranDtls, $tranDtls_gid, $surcharge, $ttl_amt, $user)
    {  
        $final_amt = (int)$surcharge +(int)$ttl_amt ;
        $user = $user[0];
        $tranDtls = $tranDtls[0];
        $fileName = $user['first_name'];
        // $fileName = 'recipt';
        $pdf = PDF::loadView('export.recipt', compact('tranDtls', 'tranDtls_gid', 'surcharge', 'final_amt', 'fileName', 'user', 'ttl_amt'));
        
        $pdf->setPaper('A4', 'portrait');
        
        // $response = $pdf->download('xyz.pdf');
        $response = $pdf->download($fileName.'.pdf');
       
        return $response;
    }

    public function getReciptBill(Request $request){
        $tranDtls=[];
        if($request->order_id){
            $tranDtls = TransactionDetail:: 
                                    // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                                    leftJoin('tbl_bbps_list', 'tbl_transaction_dtls.billerID', '=', 'tbl_bbps_list.billerId')
                                    ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                    ->where('tbl_transaction_dtls.user_id', $request->user_id)
                                    ->where('tbl_transaction_dtls.order_id', $request->order_id)
                                    ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                                    // ->limit(1)
                                    ->get();

            if(count($tranDtls)>0){

                $surcharges =  $request->surcharge;
                $ttl_amt = $tranDtls[0]['result']['basic_amount'];

                // print_r($ttl_amt);
                // print_r($tranDtls[0]);
                $response_msg =  json_decode($tranDtls[0]['response_msg'], true);
                
                $response = $this->exportPDF_Bill($tranDtls[0], $surcharges, $ttl_amt, $response_msg );
                $response = base64_encode($response);
                // print_r($response);
                $statusMsg = "Success!!"; 
                $result = [
                    'pdf_base64' => $response,
                ];
                return $this->sendSuccess($result, $statusMsg);
            }else{
                return $this->sendError("No records found!!");
            }
        }

    }

    public function exportPDF_Bill($tranDtls, $surcharges, $ttl_amt, $response_msg){
        $bill_response = json_decode($tranDtls['response_msg'], true);
        $grand_ttl = (float) $tranDtls['request_amount'] + (float) $surcharges;
        $fileName = $tranDtls['order_id'];

        

        $pdf = PDF::loadView('export.bill_recipt', compact('tranDtls', 'grand_ttl', 'surcharges','bill_response', 'response_msg', 'fileName'));
        $pdf->setPaper('A4', 'portrait');
        
        // $response = $pdf->download('xyz.pdf');
        $response = $pdf->download($fileName.'.pdf');
       
        return $response;
    }


}
