<?php

namespace App\Http\Controllers\Settings;

use App\ApiSetting;
use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApisettingController extends Controller
{

    /**
     * Get all API settings
     */
    public function index()
    {
        $apiSettings = ApiSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        return view('modules.settings.api_setting', compact('apiSettings'));
    }

    /**
     *
     */
    public function create()
    {
        return view('modules.settings.create_api_setting');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        if ($request->get('api_id')) {
            $pwdValidation = '';
        } else {
            $pwdValidation = 'required|string|min:6|confirmed';
        }
        $validator = Validator::make($request->all(), [
            'api_name' => 'required|string|max:255',
            // 'username' => 'required|string|max:255|unique:tbl_api_settings',
            'username' => 'required|string|max:255',
            'password' => $pwdValidation,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('api_id')) {

            $resultById = Apisetting::find((int) $request->get('api_id'));

            $resultById->api_id = $request->get('api_id');
            $resultById->api_name = $request->get('api_name');
            $resultById->api_dtls = $request->get('api_dtls');
            $resultById->api_url = $request->get('api_url');
            $resultById->username = $request->get('username');
            $resultById->balance = $request->get('balance');

            $response = $resultById->save();

            $action_message = "API Setting Updated";
        } else {
            $response = Apisetting::create([
                'api_name' => $request->get('api_name'),
                'api_dtls' => $request->get('api_dtls'),
                'api_url' => $request->get('api_url'),
                'username' => $request->get('username'),
                'password' => base64_encode($request->get('password')),
                'balance' => $request->get('balance'),
            ]);
            $action_message = "API Setting Added";
        }

        if ($response) {
            return redirect('/api_setting')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = Apisetting::where('api_id', $request->get('api_id'))->first();
        return $resultById;
    }

    /**
     * Check whether username already exist or not
     */
    public function checkUsernameExists(Request $request)
    {
        $username = ApiSetting::where('username', $request->username)->get();
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
        if ($request->get('ch_pwd_api_id')) {
            $resultById = Apisetting::find((int) $request->get('ch_pwd_api_id'));

            $resultById->password = base64_encode($request->get('ch_pwd_password'));
            $response = $resultById->save();

        }

        if ($response) {
            return redirect('/api_setting')->with('success', 'Password Changed Successfully!');
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
            $resultById = ApiSetting::find((int) $id);
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
            $resultById = ApiSetting::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }
}
