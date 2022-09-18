<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\SmsGatewaySetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SmsGatewaySettingsController extends Controller
{
    /**
     * Get all API settings
     */
    public function index()
    {
        $smsGateSettings = SmsGatewaySetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        return view('modules.settings.sms_gate_setting', compact('smsGateSettings'));
    }

    /**
     *
     */
    public function store(Request $request)
    {
        if ($request->get('id')) {
            $pwdValidation = '';
            $usernameValidation = 'required|string|max:255';
        } else {
            $pwdValidation = 'required|string|min:6|confirmed';
            $usernameValidation = 'required|string|max:255|unique:tbl_sms_gateway_settings';
        }
        $validator = Validator::make($request->all(), [
            'api_name' => 'required|string|max:255',
            'username' => $usernameValidation,
            'api_url' => 'required|string|max:255',
            'password' => $pwdValidation,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('id')) {

            $resultById = SmsGatewaySetting::find((int) $request->get('id'));

            $resultById->api_name = $request->get('api_name');
            $resultById->api_url = $request->get('api_url');
            $resultById->username = $request->get('username');
            // $resultById->updated_on = now();
            $response = $resultById->save();

            $action_message = "SMS Gateway Setting Updated";
        } else {
            $response = SmsGatewaySetting::create([
                'api_name' => $request->get('api_name'),
                'api_url' => $request->get('api_url'),
                'username' => $request->get('username'),
                'password' => Hash::make($request->get('password')),
            ]);
            $action_message = "SMS Gateway Setting Added";
        }

        if ($response) {
            return redirect('/sms_gate_setting')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = SmsGatewaySetting::where('id', $request->get('id'))->first();
        return $resultById;
    }

    /**
     * Check whether username already exist or not
     */
    public function checkUsernameExists(Request $request)
    {
        $username = SmsGatewaySetting::where('username', $request->username)->get();
        if (count($username) > 0) {
            return response()->json(0);
        }
        return response()->json("true");
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ch_pwd_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $response = false;
        if ($request->get('ch_pwd_id')) {
            $resultById = SmsGatewaySetting::find((int) $request->get('ch_pwd_id'));

            $resultById->password = Hash::make($request->get('ch_pwd_password'));
            $response = $resultById->save();

        }

        if ($response) {
            return redirect('/sms_gate_setting')->with('success', 'Password Changed Successfully!');
        }
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
            $resultById = SmsGatewaySetting::find((int) $id);
            $resultById->activated_status = $setStatus;
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change API Setting delete status
     */
    public function changeDeleteStatus(Request $request)
    {
        $id = $request->get('id');

        $result = false;
        if ($id) {
            $resultById = SmsGatewaySetting::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }
}
