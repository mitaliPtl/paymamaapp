<?php

namespace App\Http\Controllers\Operator;

use App\ApiSetting;
use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\OperatorDetail;
use App\ServicesType;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperatorDetailsController extends Controller
{
    /**
     * Get all Operators details record
     */
    public function index(Request $request)
    {
        $operatorDetails = OperatorDetail::
            where('service_id', $request->service_id)
            ->where('api_id', $request->api_id);


        $operatorDetails = $operatorDetails->get();

        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $operators = OperatorSetting:: where('service_id', $request->service_id)->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        return view('modules.operator.operator_dtls', compact('operators', 'servicesTypes', 'apiSettings', 'operatorDetails', 'request'));
    }

    /**
     * Store Operation details here
     */
    public function storeOpdetails(Request $request)
    {
        $operator_code = $request->get('operator_code');
        $service_id = $request->get('service_id');
        $api_id = $request->get('api_id');
        $operator_id = $request->get('operator_id');

        $validator = Validator::make($request->all(), [
            'operator_code' => 'required|string|max:255',
            'service_id' => 'required|integer',
            'api_id' => 'required|integer',
            'operator_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('api_operator_id')) {

            $resultById = OperatorDetail::find((int) $request->get('api_operator_id'));

            $resultById->operator_code = $request->get('operator_code');
            $resultById->service_id = $request->get('service_id');
            $resultById->api_id = $request->get('api_id');
            $resultById->operator_id = $request->get('operator_id');
            $response = $resultById->save();

            $action_message = "Operator Details Updated";
        } else {
            $response = OperatorDetail::create([
                'operator_code' => $request->get('operator_code'),
                'operator_id' => $request->get('operator_id'),
                'service_id' => $request->get('service_id'),
                'api_id' => $request->get('api_id'),
                'operator_id' => $request->get('operator_id'),
            ]);
            $action_message = "Operator Details Saved";
        }

        if ($response) {
            return true;
        } else {
            return false;
        }
    }
}
