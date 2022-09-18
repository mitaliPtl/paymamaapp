<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;
use App\UserLoginSessionDetail;
use Config;
use DB;
use Auth;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class OperatorController extends Controller
{

    /**
     * Get all Operators
     */
    public function index()
    {
        $operators = OperatorSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('updated_on', 'DESC')->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $bill_payment = ServicesType::select('service_id')  
                                        ->where('is_deleted', Config::get('constants.NOT-DELETED'))
                                        ->where('activated_status', Config::get('constants.ACTIVE'))
                                        ->where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))
                                        ->get();

        
        return view('modules.operator.operator', compact('operators', 'servicesTypes', 'bill_payment'));
    }

    /**
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator_name' => 'required|string|max:255',
            'service_id' => 'required|string|max:255',
            'operator_code' => 'string|max:255',
            'operator_code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('operator_id')) {

            $resultById = OperatorSetting::find((int) $request->get('operator_id'));

            $resultById->operator_name = $request->get('operator_name');
            $resultById->operator_code = $request->get('operator_code');
            $resultById->service_id = $request->get('service_id');
            $resultById->helpline_no = $request->get('helpline_no');
            $resultById->color_code = $request->get('color_code');
            $response = $resultById->save();

            $action_message = "Operator Updated";
        } else {
            $response = OperatorSetting::create([
                'operator_name' => $request->get('operator_name'),
                'operator_code' => $request->get('operator_code'),
                'service_id' => $request->get('service_id'),
                'color_code' => $request->get('color_code'),
                'helpline_no' => $request->get('helpline_no'),
                'default_api_id' => '',
            ]);
            $action_message = "Operator Added";
        }

        if ($response) {
            return redirect('/operator')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $operator = OperatorSetting::where('operator_id', $request->get('operator_id'))->first();
        return $operator;
    }

    /**
     * Check whether operator code already exist or not
     */
    public function checkOperatorCodeExists(Request $request)
    {
        $operator_code = OperatorSetting::where('operator_code', $request->operator_code)->get();
        if (count($operator_code) > 0) {
            return response()->json(0);
        }
        return response()->json("true");
    }

    /**
     * Change Operator active status
     */
    public function changeActiveStatus(Request $request)
    {
        $id = $request->get('id');
        $activeStatus = $request->get('status');

        $setStatus = Config::get('constants.IN-ACTIVE');
        if ($activeStatus == 'true') {
            $setStatus = Config::get('constants.ACTIVE');
        }

        $result = false;
        if ($id) {
            $resultById = OperatorSetting::find((int) $id);
            $resultById->activated_status = $setStatus;
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change Operator delete status
     */
    public function changeDeleteStatus(Request $request)
    {
        $id = $request->get('id');

        $result = false;
        if ($id) {
            $resultById = OperatorSetting::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Operator HelpLine view
     */
    public function operatorHelpLine_old(Request $request)
    {
        $operators = OperatorSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('updated_on', 'DESC');
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        if (isset($request->service_id) && $request->service_id) {
            $operators = $operators->where('service_id', $request->service_id);
        }

        $operators = $operators->get();
        return view('modules.operator.operator_helpline', compact('operators', 'servicesTypes', 'request'));
    }

    public function  operatorHelpLine(Request $request){
        $servicesTypes = ServicesType::where('tbl_services_type.is_deleted', Config::get('constants.NOT-DELETED'))
                            ->where('tbl_services_type.activated_status', Config::get('constants.ACTIVE'))
                            ->leftJoin('tbl_operator_settings', 'tbl_services_type.service_id', '=', 'tbl_operator_settings.service_id')
                            ->where('tbl_operator_settings.is_deleted', Config::get('constants.NOT-DELETED'))
                            ->whereIn('tbl_services_type.alias', [ Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'), Config::get('constants.SERVICE_TYPE_ALIAS.DTH') ])
                            ->get();
        
        return view('modules.operator.operator_helpline', compact( 'servicesTypes', 'request'));
    }

    /**
     * Get all operator helpline Api
     */
    public function getOperatorHelpline()
    {
        $operators = OperatorSetting::select(['operator_id', 'operator_name', 'helpline_no'])->get();
        $statusMsg = "Success!!";
        if ($operators) {
            return $this->sendSuccess($operators, $statusMsg);
        } else {
            return $this->sendError($operators, $statusMsg);
        }
    }

    public function bbpsManagement(Request $request){
        
        $bbps_list = DB::table('tbl_bbps_list')->select('id','billerId', 'billerIcon', 'billerName', 'billerCategory', 'billercustomizeInputParams', 'billercustomize');
        // if (isset($request->filter_operator_name) && $request->filter_operator_name) {
            $bbps_list = $bbps_list->where('billerCategory', $request->filter_operator_name);
        // }
        $bbps_list= $bbps_list->get();
        $bbps_list= json_decode($bbps_list, true);
        // print_r($request->filter_operator_name);
        // print_r($bbps_list);
        // exit();
        $operators = DB::table('tbl_bbps_list')->select('billerCategory')->distinct()->get();
        $filtersList = Config::get('constants.BBPS_FILTER');

        // $operators = OperatorSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('updated_on', 'DESC')->get();
        return view('modules.operator.bbps_list', compact('bbps_list', 'filtersList', 'operators', 'request'));
    }

    public function uploadBillerImage(Request $request){
        print_r($request->all());

        
        $select_icon = File::where('id', $request->uploaded_file_id)->get();
        $img_path = Config::get('constants.WEBSITE_BASE_URL').$select_icon[0]['file_path'];
       
        $update_icon = DB::table('tbl_bbps_list')->where('id', $request->biller_id)->update([ 'billerIcon' =>  $img_path ]);

        if($update_icon){
            return back()->with('success', 'Icon is Updated Succesfully!!!');
        }else{
            return back()->with('error', 'Failed... Icon is Not Updated !!');

        }

    }

    public function addBbpsBiller(Request $request){
        // print_r($request->all());
        $request->newbiller_id = str_replace(' ', '', $request->newbiller_id);

        $newbiller_id_arr = explode(',', $request->newbiller_id);
        $biller_json='';
        $comma = '';
        foreach($newbiller_id_arr as $key => $value){
            $biller_json = $biller_json. $comma.'{
                "billerID": "'.$value.'"
                            }';
            $comma = ', ';

        }

        // print_r(json_decode("[".$biller_json."]", true));
        
        
        $token_id = UserLoginSessionDetail::where('user_id', Auth::user()->userId)->get();
        $user_token  = $token_id[0]['apiKey'];
        $requestBody = array(
            "token" => $user_token,
            "user_id" =>  Auth::user()->userId,
            "role_id" =>  Auth::user()->roleId,
            "biller" => json_decode("[".$biller_json."]", true)
        );
        $api_url = "https://paymamaapp.in/admin/index.php/RechargeApi/saveMultipleBillerDetails";
        $response = Http::post($api_url, $requestBody);
        $response = isset($response) && $response ? $response->json() : [];

        // print_r($requestBody);
        // print_r($response);
        // exit();
        if($response['status'] == 'false')
        {
            return back()->with('error', $response['msg']);
        }else if($response['status'] == 'true'){

            return back()->with('success', $response['msg']);
        }
        

    }

    public function updateCustomParam(Request $request){
        print_r($request->all());
        $cust_param = "";
        $cust_status = "No";
        if($request->input_params && isset($request->input_params)){
            $cust_param = $request->input_params;
        }

        if($request->custom_param_status && isset($request->custom_param_status)){
            $cust_status = $request->custom_param_status;
        }

        $update_cust_param = DB::table('tbl_bbps_list')->where('id', $request->input_params_id)
                                                        ->update([ 'billercustomizeInputParams' =>  $cust_param,
                                                                    'billercustomize' => $cust_status ]);
        if($update_cust_param){
            return back()->with('success', 'Biller Parameter is updated Successfullly..!!!');
        }else{
            return back()->with('error','Failed!!! Biller not Updated');
        }


    }
}
