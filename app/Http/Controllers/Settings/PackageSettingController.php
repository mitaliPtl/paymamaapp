<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Config;
use App\PackageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageSettingController extends Controller
{
    /**
     * Get all API settings
     */
    public function index()
    {
        $packageSettings = PackageSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        return view('modules.settings.package_setting', compact('packageSettings'));
    }

    /**
     *Store new package Setting
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string|max:255',
            'retailer_cost' => 'required|string|max:255',
            'distributor_cost' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('package_id')) {

            $resultById = PackageSetting::find((int) $request->get('package_id'));

            $resultById->package_name = $request->get('package_name');
            $resultById->package_descr = $request->get('package_descr');
            $resultById->retailer_cost = $request->get('retailer_cost');
            $resultById->distributor_cost = $request->get('distributor_cost');

            $response = $resultById->save();

            $action_message = "Package Setting Updated";
        } else {
            $response = PackageSetting::create([
                'package_name' => $request->get('package_name'),
                'package_descr' => $request->get('package_descr'),
                'retailer_cost' => $request->get('retailer_cost'),
                'distributor_cost' => $request->get('distributor_cost'),
            ]);
            $action_message = "Package Setting Added";
        }

        if ($response) {
            return redirect('/package_setting')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = PackageSetting::where('package_id', $request->get('package_id'))->first();
        return $resultById;
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
            $resultById = PackageSetting::find((int) $id);
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
            $resultById = PackageSetting::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }
}
