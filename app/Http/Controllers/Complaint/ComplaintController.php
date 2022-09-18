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

class ComplaintController extends Controller
{
    protected $page_name = "";
    protected $service_type = "";
    protected $export_file_name = "";
    public function index(Request $request)
    {   
        $pageName='';
        $user_dtls = Auth::user();

        $serviceType = $request->input('service_type');

        $this->setPageName($serviceType);
        $pageName = $this->page_name;
        $loggedInRole = Auth::user()->roleId;
        
        $filtersList = $this->setFilterList($loggedInRole, $serviceType);
        $complaintListTH = $this->setTableHeader($loggedInRole, $serviceType);
        $complaintList = $this->filter($request, $loggedInRole);

       
        $operators = OperatorSetting::where('tbl_operator_settings.is_deleted', Config::get('constants.NOT-DELETED'))
                    ->where('tbl_operator_settings.activated_status', Config::get('constants.ACTIVE'));
                   
        
        if (isset($request->service_id) && $request->service_id) {
            $operators = $operators->where('tbl_operator_settings.service_id', $request->service_id);
        }
       
        $operators =$operators->get();
        $complaintList = $complaintList->get();
        $complaintList = $this->modifyComplaintReport($complaintListTH, $complaintList);
        
                
        $complaintList = isset($complaintList) ? $complaintList : [];
       
        // $complaints = Complaint::
        //             leftJoin('tbl_transaction_dtls', 'tbl_complaints.order_id', '=', 'tbl_transaction_dtls.order_id')
        //             ->get();
        $this->export_file_name = $this->service_type . '_complaint_list_' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($this->export_file_name, $complaintListTH, $complaintList);
            return $response;
        }
        
        // return view('modules.complaint.complaint', compact('complaints'));
        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
      
        return view('modules.complaint.complaint', compact('pageName', 'filtersList', 'complaintListTH', 'complaintList', 'apiSettings', 'servicesTypes', 'operators', 'request', 'user_dtls'));
    }

    /**
     * Set Page name according to Service Type
     */
    public function setPageName($serviceType = null)
    {
        if ($serviceType == Config::get('constants.COMPLAINT_LABEL')) {
            $this->page_name = "Complaint List";
            $this->service_type = "complaint";
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
    public function filter($request, $loggedInRole)
    {
       
        $cmplt = Complaint::
                            leftJoin('tbl_transaction_dtls', 'tbl_complaints.order_id', '=', 'tbl_transaction_dtls.order_id')
                            ->leftJoin('tbl_dmt_benificiary_dtls', 'tbl_complaints.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                            ->leftJoin('tbl_template', 'tbl_complaints.template_id', '=', 'tbl_template.template_id')
                            ->leftJoin('tbl_operators', 'tbl_transaction_dtls.operator_id', '=', 'tbl_operators.id')
                            ->leftJoin('tbl_users', 'tbl_complaints.user_id', '=', 'tbl_users.userId')
                            ->leftJoin('tbl_roles', 'tbl_complaints.role_id', '=', 'tbl_roles.roleId')
                            ->where('tbl_transaction_dtls.id_deleted', Config::get('constants.NOT-DELETED'))
                            ->orderBy('complaint_date', 'DESC');
        if($loggedInRole == Config::get('constants.ADMIN') || $loggedInRole == Config::get('constants.DISTRIBUTOR')){
            if ($request->has('from_date') && isset($request->from_date)) {
                $fromDate = $request->get('from_date');
                $cmplt->whereDate('complaint_date', '>=', $fromDate);
            } else {
                $fromDate = now();
                $cmplt->whereDate('complaint_date', $fromDate);
            }

            if ($request->has('to_date') && isset($request->to_date)) {
                $toDate = $request->get('to_date');
                $cmplt->whereDate('complaint_date', '<=', $toDate);
            }

        }
            
        if (isset($request->api_id) && $request->api_id) {
            $cmplt->where('tbl_transaction_dtls.api_id', $request->api_id);
        }

        if (isset($request->service_id) && $request->service_id) {
            $cmplt->where('tbl_transaction_dtls.service_id', $request->get('service_id'));
        }

        if (isset($request->operator_id) && $request->operator_id) {
            $cmplt->where('tbl_transaction_dtls.operator_id', $request->get('operator_id'));
        }

        if (isset($request->order_status) && $request->order_status) {
            $cmplt->where('tbl_transaction_dtls.order_status', $request->get('order_status'));
        }

        if (Auth::id() != Config::get('constants.ADMIN')) {
            if (Auth::id()) {
                $childResponse = User::where('parent_user_id', Auth::id())->pluck('userId');
                if (count($childResponse) > 0) {
                    $childResponse = $childResponse->toArray();
                    array_push($childResponse, Auth::id());
                    $cmplt->whereIn('tbl_transaction_dtls.user_id', $childResponse);
                } else {
                    $cmplt->where('tbl_transaction_dtls.user_id', Auth::user()->userId);
                }
            }
           
        } else {
            if (isset($request->username_mobile) && $request->username_mobile) {
                $userId = User::where('mobile', $request->username_mobile)
                    ->orWhere('username', $request->username_mobile)->pluck('userId')->first();
                if ($userId) {
                    $cmplt = $cmplt->where('tbl_transaction_dtls.user_id', $userId);
                }
            }
        }
        // $cmplt = $cmplt->get();
        // print_r($cmplt);
        // exit();
        
        return $cmplt;
    }

     /**
     * Modify Recharge Reports List
     */
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

    
 

    public function complaintReply(Request $request){
        // print_r("dd");
        // print_r($request->complaint_id);
        $update_complt = Complaint::
                        where('complaint_id', $request->change_complaint_id)
                        ->update(['complaint_message'=>  $request->admin_reply,
                        'admin_reply'=>  "by ADMIN",
                        'admin_reply_date'=>  now(),
                        'updated_on'=> now()]);    
        $this-> change_complaint_status();
                        
        return redirect('/complaints')->with('success', "Reply sent successfully!!");
        
    }
    
    public function change_complaint_status (Request $request){
        // public function change_complaint_status (Request $request){
      
        $update_complt =Complaint::
                        where('complaint_id', $request->change_complaint_id)
                        ->update(['admin_reply'=> $request->admin_reply ,
                        'admin_reply_date'=>  now(),
                        'complaint_status'=>$request->complt_status,
                        'updated_on'=> now()]);       
                               
        // return redirect('/complaints')->with('success', "Reply sent successfully!!");
        return  back()->with("success", "Status updated successfully!!");
    }

    //change Default Time

    public function changeDefaultTime(Request $request){
        // print_r($request->all());
        $update_complt =Complaint::
                        where('complaint_id', $request->change_time_complaint_id)
                        ->update(["comp_default_time"=> $request->change_time,
                        'admin_reply_date'=>  now(),
                        'updated_on'=> now()]); 
        return  back()->with("success", "Status updated successfully!!"); 
    }

    public function addComplaint(Request $request){
         
        $check_Complaint =  $this->isComplaintPresent($request->complaint_order_id);
        if(count($check_Complaint)>0){
            return  back()->with("error", " Already Done Complaint !!"); 
        }
        

        $templt = $this->getTemplate($request->selected_comp);
        
        $cmp_id= $this->createComplaintID();
        $tranDtls = TransactionDetail::where('order_id', $request->complaint_order_id)
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
                            'role_id'=>Auth::user()->roleId,
                            // 'recipient_id'=>$recipient_id,
                            'transaction_id' =>$tranDtls['transaction_id'],
                            'template_id'=>$request->selected_comp,
                            // 'complaint_message'=> 'BY Admin',
                            'comp_default_time'=>$templt['timing'],
                            'complaint_date'=>now(),
                            'admin_reply'=>'',
                            // 'admin_reply_date'=>now(),
                            'complaint_status'=> 'PENDING'
                            // 'updated_on'=> now()
                        ]);
        if($inserted_complt){

            return  back()->with("success", "Complaint Added successfully!!"); 

        }else{
            return  back()->with("error", " Complaint Not Added !!"); 

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
}
