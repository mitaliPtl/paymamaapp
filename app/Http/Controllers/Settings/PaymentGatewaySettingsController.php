<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\PaymentGateWaySetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Role;

class PaymentGatewaySettingsController extends Controller
{
    /**
     * Get all API settings
     */
    public function index()
    {
        $payGateSettings = PaymentGateWaySetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        return view('modules.settings.pay_gate_setting', compact('payGateSettings'));
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
            $usernameValidation = 'required|string|max:255|unique:tbl_payment_gateway_settings';
        }
        $validator = Validator::make($request->all(), [
            'payment_gateway_name' => 'required|string|max:255',
            'username' => $usernameValidation,
            'working_key' => 'required|string|max:255',
            'charges' => 'required|string|max:255',
            'password' => $pwdValidation,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('id')) {

            $resultById = PaymentGateWaySetting::find((int) $request->get('id'));

            $resultById->payment_gateway_name = $request->get('payment_gateway_name');
            $resultById->working_key = $request->get('working_key');
            $resultById->charges = $request->get('charges');
            $resultById->username = $request->get('username');

            $response = $resultById->save();

            $action_message = "Payment Gateway Setting Updated";
        } else {
            $response = PaymentGateWaySetting::create([
                'payment_gateway_name' => $request->get('payment_gateway_name'),
                'working_key' => $request->get('working_key'),
                'username' => $request->get('username'),
                'charges' => $request->get('charges'),
                'password' => Hash::make($request->get('password')),
            ]);
            $action_message = "Payment Gateway Setting Added";
        }

        if ($response) {
            return redirect('/pay_gate_setting')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = PaymentGateWaySetting::where('id', $request->get('id'))->first();
        return $resultById;
    }

    /**
     * Check whether username already exist or not
     */
    public function checkUsernameExists(Request $request)
    {
        $username = PaymentGateWaySetting::where('username', $request->username)->get();
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
            $resultById = PaymentGateWaySetting::find((int) $request->get('ch_pwd_id'));

            $resultById->password = Hash::make($request->get('ch_pwd_password'));
            $response = $resultById->save();

        }

        if ($response) {
            return redirect('/pay_gate_setting')->with('success', 'Password Changed Successfully!');
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
            $resultById = PaymentGateWaySetting::find((int) $id);
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
            $resultById = PaymentGateWaySetting::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * 
     */
    public function pgMemberList()
    {
        $userList = User::with(['ekyc', 'parentuser'])->where('isDeleted', Config::get('constants.NOT-DELETED'))
            ->where('userId', '!=', '1')
            ->where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')))
            ->orderBy('createdDtm', 'DESC')
            ->orderBy('updatedDtm', 'DESC')
            ->get();
        return view('modules.settings.pg_member_list', compact('userList'));
    }
}
