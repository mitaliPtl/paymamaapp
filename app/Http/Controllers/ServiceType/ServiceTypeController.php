<?php

namespace App\Http\Controllers\ServiceType;

use App\Http\Controllers\Controller;
use App\ServicesType;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceTypeController extends Controller
{
    /**
     * Get all Services Types for service list
     */
    public function index(Request $request)
    {   
        $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS');
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        return view('modules.service_type.service_type', compact('servicesTypes','serviceTypeAlias'));
    }
    /**
     *
     */
    public function create()
    {
        return view('modules.service_type.create_service_type');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('service_id')) {

            $resultById = ServicesType::find((int) $request->get('service_id'));

            $resultById->service_id = $request->get('service_id');
            $resultById->service_name = $request->get('service_name');
            $resultById->alias = $request->get('alias');
            $resultById->service_dtls = $request->get('service_dtls');

            $response = $resultById->save();

            $action_message = "Service Type Updated";
        } else {
            $response = ServicesType::create([
                'service_name' => $request->get('service_name'),
                'alias' => $request->get('alias'),
                'service_dtls' => $request->get('service_dtls'),
            ]);
            $action_message = "Service Type Added";
        }

        if ($response) {
            return redirect('/service_type')->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = ServicesType::where('service_id', $request->get('service_id'))->first();
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
            $resultById = ServicesType::find((int) $id);
            $resultById->activated_status = $setStatus;
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change Service Type delete status
     */
    public function changeDeleteStatus(Request $request)
    {
        $id = $request->get('id');

        $result = false;
        if ($id) {
            $resultById = ServicesType::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    public function aepsDeviceDriver(){

        return view('modules.aeps.devicedriver');
    }
}
