<?php

namespace App\Http\Controllers\Operator;

use App\ApiSetting;
use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperatorSettingsController extends Controller
{
    /**
     * Operator settings page view
     */
    public function index()
    {
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $operatorSettings = OperatorSetting::with(['servicesType'])->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        return view('modules.operator.operator_settings', compact('apiSettings', 'servicesTypes', 'operatorSettings'));
    }

    /**
     * store operator settings here
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator_id' => 'required',
            'default_api_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        $operatorList = $request->operator_id;

        foreach ($operatorList as $operator) {

            $operatorById = $this->getOperatorDataById($operator);
            $opSetExist = $this->checkOpSettingsExists($operator);
            if ($opSetExist) {
                $resultById = OperatorSetting::where('operator_id',$operator)->first();
                // return $resultById;
                $resultById->operator_id = $operator;
                $resultById->service_id = $operatorById->servicesType->service_id;
                $resultById->operator_name = $operatorById->operator_name;
                $resultById->operator_code = $operatorById->operator_code;
                $resultById->default_api_id = $request->default_api_id;

                $response = $resultById->save();

                $action_message = "Operator Settings Updated";
            } else {
                $response = OperatorSetting::create([
                    'operator_id' => $operator,
                    'service_id' => $operatorById->servicesType->service_id,
                    'operator_name' => $operatorById->operator_name,
                    'operator_code' => $operatorById->operator_code,
                    'default_api_id' => $request->default_api_id,
                    'activated_status' => Config::get('constants.ACTIVE'),
                ]);
            }
        }

        $action_message = "Operator Settings Added";

        if ($response) {
            return redirect('/operator_settings')->with('success', $action_message);
        }
    }

    /**
     * Get OperatorRecords by providing id
     */
    public function getOperatorDataById($operatorId)
    {
        $operators = null;
        if ($operatorId) {
            $operators = OperatorSetting::with(['servicesType'])->where('operator_id', $operatorId)->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->first();
        }
        return $operators;
    }

    /**
     * Check if Operator Settings already exist
     */
    public function checkOpSettingsExists($operatorId)
    {
        $result = false;
        if ($operatorId) {
            $operator = OperatorSetting::where('operator_id', $operatorId)->get();
            if (count($operator) > 0) {
                $result = true;
            }
        }
        return $result;
    }

}
