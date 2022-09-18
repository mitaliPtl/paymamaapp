<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Config;
use App\ApiAmountDetail;
use App\ApiSetting;
use App\OperatorSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiAmountDetailController extends Controller
{
    /**
     * Get all API Amount details 
     */
    public function index()
    {
        $apiAmountDetails = ApiAmountDetail::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $operators = OperatorSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        return view('modules.settings.api_amount_details', compact('apiAmountDetails','operators','apiSettings'));
    }

    /**
     *Store API amount details
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'api_id' => 'integer',
            'operator_id' => 'integer',
            'amount' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('id')) {

            $resultById = ApiAmountDetail::find((int) $request->get('id'));

            $resultById->api_id = $request->get('api_id');
            $resultById->operator_id = $request->get('operator_id');
            $resultById->amount = $request->get('amount');
            // $resultById->add_date = $request->get('add_date');

            $response = $resultById->save();

            $action_message = "API Amount Details Updated";
        } else {
            $response = ApiAmountDetail::create([
                'api_id' => $request->get('api_id'),
                'operator_id' => $request->get('operator_id'),
                'amount' => $request->get('amount'),
                'add_date' => date("Y-m-d"),
            ]);
            $action_message = "API Amount Details Added";
        }

        if ($response) {
            return redirect('/api_amount_details')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = ApiAmountDetail::where('id', $request->get('id'))->first();
        return $resultById;
    }

    /**
     * Change Api Amount Details active status
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
            $resultById = ApiAmountDetail::find((int) $id);
            $resultById->activated_status = $setStatus;
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change API Amount Details delete status
     */
    public function changeDeleteStatus(Request $request)
    {
        $id = $request->get('id');

        $result = false;
        if ($id) {
            $resultById = ApiAmountDetail::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }
}
