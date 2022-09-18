<?php

namespace App\Http\Controllers\Complaint;
use App\Complaint;
use App\ApiLogDetail;
use App\ApiSetting;
use App\City;
use App\Template;
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

class APIComplaintController extends Controller
{
    public function getAllTemplate(Request $request){

        $templates = Template::where('tbl_template.isDeleted', Config::get('constants.NOT-DELETED'));
        if(isset($request->service_id) && $request->service_id){
            $templates = $templates ->where('tbl_template.service_id', $request->service_id);
        }
        $templates= $templates->get();
        
        if (isset($templates) && count($templates) > 0) {
            $statusMsg = "Success!!";

            return $this->sendSuccess($templates, $statusMsg);
        }else{
            return $this->sendError("No records found!!");
        }

        
    }

    public function createComplaint(Request $request){
        
        $check_Complaint =  $this->isComplaintPresent($request->order_id);
        if(count($check_Complaint)>0){
            return $this->sendError("Sorry!! Already Done Complaint");
        }
        

        $templt = $this->getTemplate($request->template_id);
        $cmp_id= $this->createComplaintID();
        $tranDtls = TransactionDetail::where('order_id', $request->order_id)
                                        ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                        ->get();
        $tranDtls = $tranDtls[0];
        
        $templt = $templt[0];   
        // $recipient_id =1;
        // if(!empty($tranDtls['recipient_id'])){
        // $recipient_id = $tranDtls['recipient_id'];
        // }       

            $inserted_complt = Complaint::create([
                                                'complaint_id' => $cmp_id,
                                                'order_id'=>$tranDtls['order_id'],
                                                'user_id'=>$tranDtls['user_id'],
                                                'role_id'=>$request->role_id,
                                                // 'recipient_id'=>$recipient_id,
                                                'transaction_id' =>$tranDtls['transaction_id'],
                                                'template_id'=>$request->template_id,
                                                // 'complaint_message'=> 'BY Admin',
                                                'comp_default_time'=>$templt['timing'],
                                                'complaint_date'=>now(),
                                                'admin_reply'=>'',
                                                // 'admin_reply_date'=>now(),
                                                'complaint_status'=> 'PENDING'
                                                // 'updated_on'=> now()
                                            ]);
            if($inserted_complt){
                $statusMsg = "Success!!";

                return $this->sendSuccess($inserted_complt, $statusMsg);
            }else{
                return $this->sendError("Sorry!!");
            }
        
    }
    public function createComplaintMsg(Request $request){
      
        $cmp_id= $this->createComplaintID();
        $inserted_complt = Complaint::create([
                                                    'complaint_id' => $cmp_id,
                                                    'order_id'=>'',
                                                    'user_id'=>$request->user_id,
                                                    'role_id'=>$request->role_id,
                                                    // 'recipient_id'=>$recipient_id,
                                                    'transaction_id' =>'',
                                                    // 'template_id'=>'',
                                                    'complaint_message'=> $request->message,
                                                    'comp_default_time'=> '',
                                                    'complaint_date'=>now(),
                                                    'admin_reply'=>'',
                                                    // 'admin_reply_date'=>now(),
                                                    'complaint_status'=> 'PENDING'
                                                    // 'updated_on'=> now()
                                            ]);
            if($inserted_complt){
                $statusMsg = "Success!!";

                return $this->sendSuccess($inserted_complt, $statusMsg);
            }else{
                return $this->sendError("Sorry!!");
            }
        
    }

    public function isComplaintPresent($order_id){
        
        $check_complt =Complaint::where('order_id', $order_id)->get();
        return $check_complt;
    }

    public function createComplaintID(){
        $max_id = Complaint::max('id');
        $max_id = 1+(int)$max_id;
        $newID = "CM".$max_id;
        return $newID;
    }
    public function getTemplate($temp_id){
        $templt = Template::where('template_id', $temp_id)->get();
        return $templt;                    
    }


    public function getComplaint_old(Request $request){

        $cmplt = Complaint::
                            leftJoin('tbl_transaction_dtls', 'tbl_complaints.order_id', '=', 'tbl_transaction_dtls.order_id')
                            // ->leftJoin('tbl_dmt_benificiary_dtls', 'tbl_complaints.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                            ->leftJoin('tbl_template', 'tbl_complaints.template_id', '=', 'tbl_template.template_id')
                            // ->leftJoin('tbl_operators', 'tbl_transaction_dtls.operator_id', '=', 'tbl_operators.id')
                            ->leftJoin('tbl_users', 'tbl_complaints.user_id', '=', 'tbl_users.userId')
                            ->leftJoin('tbl_roles', 'tbl_complaints.role_id', '=', 'tbl_roles.roleId')
                            ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                            // ->where('tbl_complaints.user_id', $request->user_id)
                            // ->where('tbl_complaints.role_id', $request->role_id)
                            
                            ->orderBy('complaint_date', 'DESC');
                            // ->get();
        $serviceType='COMPLAINT';
        $loggedInRole = $request->role_id;
       
            
       

        if ($request->role_id != Config::get('constants.ADMIN')) {
            if ($request->user_id) {
                $childResponse = User::where('parent_user_id',  $request->user_id)->pluck('userId');
                if (count($childResponse) > 0) {
                    $childResponse = $childResponse->toArray();
                    array_push($childResponse, $request->user_id);
                    $cmplt->whereIn('tbl_transaction_dtls.user_id', $childResponse);
                } else {
                    $cmplt->where('tbl_transaction_dtls.user_id', $request->user_id);
                }
            }
            
        } 
        else {
            if (isset($request->username_mobile) && $request->username_mobile) {
                $userId = User::where('mobile', $request->username_mobile)
                    ->orWhere('username', $request->username_mobile)->pluck('userId')->first();
                if ($userId) {
                    $cmplt = $cmplt->where('tbl_transaction_dtls.user_id', $userId);
                }
            }
        }
        $cmplt = $cmplt->get();
        if (isset($cmplt) && count($cmplt) > 0) {

            // $serviceType='COMPLAINT';
            // $loggedInRole = $request->role_id;
            $complaintListTH = $this->setTableHeader($loggedInRole, $serviceType);
            $cmplt = $this->modifyComplaintReport($complaintListTH, $cmplt);
            $statusMsg = "Success!!";

            return $this->sendSuccess($cmplt, $statusMsg);
        }else{
            return $this->sendError("No records found!!");
        }

    }

    public function modifyComplaintReport($tableHeads, $reportList)
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
                            $label_val = $this->getApiSettingNameById($report[$head['label']]);
                        }

                        if ($head['label'] == "response") {
                            $label_val = $this->getResponseByOrdId($report['order_id']);
                        }

                        if ($head['label'] == "service_id") {
                            // $label_val = $this->getResponseByOrdId($report['order_id']);
                            $label_val = isset(ServicesType::where('service_id', $report[$head['label']])->pluck('service_name')[0]) ? ServicesType::where('service_id', $report[$head['label']])->pluck('service_name')[0] : '';
                        }

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

    
    public function getComplaint(Request $request){

        $cmplt = Complaint::leftJoin('tbl_users', 'tbl_complaints.user_id', '=', 'tbl_users.userId')
                            ->leftJoin('tbl_template', 'tbl_complaints.template_id', '=', 'tbl_template.template_id')
                            ->orderBy('complaint_date', 'DESC');
        
        if ($request->role_id != Config::get('constants.ADMIN')) { 
                $cmplt->where('tbl_complaints.user_id', $request->user_id);
        }
        /*
        //Retailer compalints
        if ($request->role_id != Config::get('constants.ADMIN')) {
            $childResponse = User::where('parent_user_id',  $request->user_id)->pluck('userId');
            
            if (count($childResponse) > 0) {
                // array_push($childResponse, $request->user_id);
                $childResponse[count($childResponse)] = $request->user_id;
                $cmplt->whereIn('tbl_complaints.user_id', $childResponse);
            } else {
                $cmplt->where('tbl_complaints.user_id', $request->user_id);
            }
        }*/

        $cmplt = $cmplt->get();

        $serviceType='COMPLAINT';
        $loggedInRole = $request->role_id;
       
        if (isset($cmplt) && count($cmplt) > 0) {

            // $serviceType='COMPLAINT';
            // $loggedInRole = $request->role_id;
            // $complaintListTH = $this->setTableHeader($loggedInRole, $serviceType);
            // $cmplt = $this->modifyComplaintReport($complaintListTH, $cmplt);
            $cmplt = $this->modifyComplaintReport_new( $cmplt);
            $statusMsg = "Success!!";

            return $this->sendSuccess($cmplt, $statusMsg);
        }else{
            return $this->sendError("No records found!!");
        }
       
        
    }

    public function modifyComplaintReport_new($records){
        $result = [];
        if (count($records)>0) {
            foreach ($records as $key => $value) {
                // leftJoin('tbl_transaction_dtls', 'tbl_complaints.order_id', '=', 'tbl_transaction_dtls.order_id')
                // // ->leftJoin('tbl_dmt_benificiary_dtls', 'tbl_complaints.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                // ->leftJoin('tbl_template', 'tbl_complaints.template_id', '=', 'tbl_template.template_id')
                // // ->leftJoin('tbl_operators', 'tbl_transaction_dtls.operator_id', '=', 'tbl_operators.id')
                // ->leftJoin('tbl_users', 'tbl_complaints.user_id', '=', 'tbl_users.userId')
                // ->leftJoin('tbl_roles', 'tbl_complaints.role_id', '=', 'tbl_roles.roleId')
                // ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                // // ->where('tbl_complaints.user_id', $request->user_id)
                // // ->where('tbl_complaints.role_id', $request->role_id)
                
                // ->orderBy('complaint_date', 'DESC');
                $trnsDtls = TransactionDetail::where('order_id', $value['order_id'])->get()->first();
               
                $result[$key]['id'] = $value['id'];
                $result[$key]['complaint_id'] = $value['complaint_id'];
                $result[$key]['store_name'] = $value['store_name'];
                $result[$key]['role_id'] = $value->role_id;
                $result[$key]['mobile'] = $this->getUserMobileById($value['user_id']);
                $result[$key]['order_id'] = $value['order_id'];
                if ($trnsDtls) {
                    $result[$key]['api_id'] =  $this->getApiSettingNameById($trnsDtls->api_id);
                    $result[$key]['transaction_msg'] = $trnsDtls->transaction_msg;
                    $result[$key]['transaction_id'] = $trnsDtls->transaction_id;
                    $result[$key]['trans_date'] = $trnsDtls->trans_date;
                    $result[$key]['mobileno'] = $trnsDtls->mobileno;
                    $result[$key]['total_amount'] = $trnsDtls->total_amount;
                    $result[$key]['order_status'] = $trnsDtls->order_status;
                }else {
                    $result[$key]['api_id'] = '';
                    $result[$key]['transaction_msg'] = '';
                    $result[$key]['transaction_id'] = '';
                    $result[$key]['trans_date'] = '';
                    $result[$key]['mobileno'] = '';
                    $result[$key]['total_amount'] = '';
                    $result[$key]['order_status'] = '';

                }
               
                // $result[$key]['template'] = $value['template'];
                $result[$key]['template'] = (isset($value['template'])) ? Template::getTemplateById($value['template']) : '';
                $result[$key]['complaint_message'] = $value['complaint_message'];
                $result[$key]['admin_reply'] = $value['admin_reply'];
                $result[$key]['admin_reply_date'] = (isset($value['admin_reply_date'])) ? $value['admin_reply_date'] : '';
                $result[$key]['comp_default_time'] = $value['comp_default_time'];
                $result[$key]['complaint_status'] = $value['complaint_status'];
                $result[$key]['complaint_date'] = $value['complaint_date'];
                
               
            }
        }

        return $result;
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


}