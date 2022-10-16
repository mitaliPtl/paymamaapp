<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\PackageCommissionDetail;
use App\PackageSetting;
use App\Role;
use App\ServicesType;
use App\User;
use App\File;
use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageCommissionDetailController extends Controller
{
    public function index(Request $request)
    {
     
        $packageCommDetails = PackageCommissionDetail::leftJoin('tbl_operator_settings', 'tbl_pkg_commission_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
            ->where('tbl_pkg_commission_dtls.service_id', $request->service_id)
            ->where('tbl_pkg_commission_dtls.pkg_id', $request->pkg_id);
            
        if (isset($request->operator_id) && $request->operator_id) {
            $packageCommDetails->where('tbl_operator_settings.operator_id', $request->operator_id);
        }

        $packageCommDetails = $packageCommDetails->get();
        
        $packageSettings = PackageSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        
        $operators = OperatorSetting::where('service_id', $request->service_id)->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        //print_r($operators);
        return view('modules.settings.pack_comm_dtls', compact('operators', 'servicesTypes', 'packageSettings', 'packageCommDetails', 'request'));
    }

    /**
     * Store package commission details here
     */
    public function storeOpdetails(Request $request)
    {
        $admin_commission = $request->get('admin_commission');
        $api_commission = $request->get('api_commission');
        $md_commission = $request->get('md_commission');
        $distributor_commission = $request->get('distributor_commission');
        $retailer_commission = $request->get('retailer_commission');
        $commission_type = $request->get('commission_type');

        $service_id = $request->get('service_id');
        $pkg_id = $request->get('pkg_id');
        $operator_id = $request->get('operator_id');

        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'pkg_id' => 'required|integer',
            

            // 'ccf_commission' => 'required|integer',
            'api_charge_commission' => 'required',
            'admin_commission' => 'required',
            'api_commission' => 'required',
            'md_commission' => 'required',
            'distributor_commission' => 'required',
            'retailer_commission' => 'required',

        ]);

        if ($validator->fails()) {
           
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('pkg_commission_id')) {
           /* if($request->get('service_id') == 10 or $request->get('service_id') == 9)
                {
                    $operator_id= 45;
                }
                else{
                $operator_id = $request->get('operator_id');
                }*/
            $resultById = PackageCommissionDetail::find((int) $request->get('pkg_commission_id'));

            $resultById->api_charge_commission = $request->get('api_charge_commission');
            $resultById->ccf_commission = $request->get('ccf_commission');

            $resultById->admin_commission = $request->get('admin_commission');
            $resultById->api_commission = $request->get('api_commission');
            $resultById->md_commission = $request->get('md_commission');
            $resultById->distributor_commission = $request->get('distributor_commission');
            $resultById->retailer_commission = $request->get('retailer_commission');

            $resultById->commission_type = $request->get('commission_type');

            // Range
            $resultById->from_range = $request->get('from_range');
            $resultById->to_range = $request->get('to_range');

            // Commission type
            $resultById->ccf_commission_type = $request->get('ccf_commission_type');
            $resultById->api_charge_commission_type = $request->get('api_charge_commission_type');
            $resultById->admin_commission_type = $request->get('admin_commission_type');
            $resultById->md_commission_type = $request->get('md_commission_type');
            $resultById->distributor_commission_type = $request->get('distributor_commission_type');
            $resultById->retailer_commission_type = $request->get('retailer_commission_type');

            $resultById->service_id = $request->get('service_id');
            $resultById->pkg_id = $request->get('pkg_id');
            $resultById->operator_id = $request->get('operator_id');
           
            $response = $resultById->save();

            $action_message = "Package Commission Details Updated";
        } else {
            /*if($request->get('service_id') == 10 or $request->get('service_id') == 9)
                {
                    $operator_id= 45;
                }
                else{
                $operator_id = $request->get('operator_id');
                }*/
            $response = PackageCommissionDetail::create([
                'api_charge_commission' => $request->get('api_charge_commission'),

                'ccf_commission' => $request->get('ccf_commission'),
                'admin_commission' => $request->get('admin_commission'),
                'api_commission' => $request->get('api_commission'),
                'md_commission' => $request->get('md_commission'),
                'distributor_commission' => $request->get('distributor_commission'),
                'retailer_commission' => $request->get('retailer_commission'),

                'commission_type' => $request->get('commission_type'),

                // Range
                'from_range' => $request->get('from_range'),
                'to_range' => $request->get('to_range'),

                // Commission type
                'ccf_commission_type' => $request->get('ccf_commission_type'),
                'api_charge_commission_type' => $request->get('api_charge_commission_type'),
                'admin_commission_type' => $request->get('admin_commission_type'),
                'md_commission_type' => $request->get('md_commission_type'),
                'distributor_commission_type' => $request->get('distributor_commission_type'),
                'retailer_commission_type' => $request->get('retailer_commission_type'),

                'service_id' => $request->get('service_id'),
                'pkg_id' => $request->get('pkg_id'),
                'operator_id'=>$request->get('operator_id'),
                
            ]);
            $action_message = "Package Commission Details Saved";
        }

        if ($response) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * My Commission view for Retailer and Distributor
     */
    public function myCommission(Request $request)
    {
        $currentPkgId = Auth::user()['package_id'];
        $operators = OperatorSetting::where('service_id', $request->service_id)->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        if (!isset($request->pkg_id)) {
            $request['pkg_id'] = $currentPkgId;
        }

        $packageCommDetails = PackageCommissionDetail:: where('service_id', $request->service_id)
                                                        ->where('pkg_id', $request->pkg_id);
        $service=[];
        if (isset($request->service_id) && $request->service_id) {
            $service = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))
                            ->where('activated_status', Config::get('constants.ACTIVE'))
                            ->where('service_id', $request->service_id)
                            ->get()->first();
            if ($service->alias ==  Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER')) {
                $packageCommDetails->where('operator_id', $operators[0]->operator_id);
            }
        }
        
        // print_r( $request->service_id);
        // exit;
        
        
      
        if (isset($request->operator_id) && $request->operator_id) {
            $packageCommDetails->where('operator_id', $request->operator_id);
        }

        $packageCommDetails = $packageCommDetails->get();
        // print_r($packageCommDetails);
        // print_r($request->operator_id);
        // exit;
        $packageSettings = PackageSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
       
        return view('modules.settings.my_commission', compact('currentPkgId', 'operators', 'servicesTypes', 'packageSettings', 'packageCommDetails', 'request'));
    }

    /**
     * Get Package Commission Details
     */
    public function getPackageCommDtls(Request $request)
    {
        $packageCommDetails = PackageCommissionDetail::where('is_deleted', Config::get('constants.NOT-DELETED'));

        if (isset($request->service_id) && $request->service_id) {
            $packageCommDetails->where('service_id', $request->service_id);
        }
        if (isset($request->pkg_id) && $request->pkg_id) {
            $packageCommDetails->where('pkg_id', $request->pkg_id);
        }
        if (isset($request->operator_id) && $request->operator_id) {
            $packageCommDetails->where('operator_id', $request->operator_id);
        }

        $packageCommDetails = $packageCommDetails->get();
        $packageCommDetails->makeHidden([
            'api_charge_commission',
            'api_charge_commission_type',
            'admin_commission_type',
            'admin_commission',
            'api_commission',
            'md_commission_type',
            'md_commission',
        ]);

        if (isset($request->role_id) && $request->role_id) {
            $roleAlias = Role::getAliasFromId($request->role_id);
            if ($roleAlias == Config::get('constants.ROLE_ALIAS.RETAILER')) {
                $packageCommDetails->makeHidden(['distributor_commission', 'distributor_commission_type']);
            } else if ($roleAlias == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                $packageCommDetails->makeHidden(['retailer_commission', 'retailer_commission_type']);
            }
        }

        if (count($packageCommDetails) > 0) {
            $statusMsg = "Success!!";
            return $this->sendSuccess($packageCommDetails, $statusMsg);
        } else {
            return $this->sendError('No records found!!', $packageCommDetails);
        }
    }

    /**
     * My Commission API
     */
    public function myCommissionAPI_old(Request $request)
    {
        $packageCommDetails = PackageCommissionDetail::where('is_deleted', Config::get('constants.NOT-DELETED'));

        if (isset($request->pkg_id) && $request->pkg_id) {
            $packageCommDetails->where('pkg_id', $request->pkg_id);
        } else {
            $userPkgId = User::where('userId', $request->user_id)->pluck('package_id')->first();
            $packageCommDetails->where('pkg_id', $userPkgId);
        }

        $packageCommDetails = $packageCommDetails->get();

        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $roleAlias = Role::getAliasFromId($request->role_id);

        $result = [];

        if (!empty($servicesTypes) && !empty($packageCommDetails)) {

            foreach ($servicesTypes as $sInd => $sType) {
                $key = [];

                foreach ($packageCommDetails as $pCmInd => $pkCmDtl) {
                    if ($sType->service_id == $pkCmDtl->service_id) {
                        $key['service_name'] = $sType->service_name;
                        $key['commission_dtl'] = $this->getOperatorCommission($sType->service_id, $sType->alias, $packageCommDetails, $roleAlias);
                    }
                }

                if (!empty($key)) {
                    array_push($result, $key);
                }
            }

        }

        if (!empty($result)) {
            $statusMsg = "Success!!";
            return $this->sendSuccess($result, $statusMsg);
        } else {
            return $this->sendError('No records found!!');
        }

    }

    public function myCommissionAPI(Request $request)
    {
        $userPkgId = User::where('userId', $request->user_id)->pluck('package_id')->first();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $roleAlias = Role::getAliasFromId($request->role_id);

        $result = [];

        if ( count($servicesTypes)>0) {

            foreach ($servicesTypes as $sInd => $sType) {
               
                $result[$sInd]['service_name'] = $sType->service_name;
                $packageCommDetails = PackageCommissionDetail::where('tbl_pkg_commission_dtls.is_deleted', Config::get('constants.NOT-DELETED'))
                                        ->leftJoin('tbl_operator_settings', 'tbl_pkg_commission_dtls.operator_id', '=', 'tbl_operator_settings.operator_id')
                                        // ->leftJoin('tbl_operator_settings', 'tbl_user_services.service_id', '=', 'tbl_operator_settings.service_id')
                                        ->where('tbl_pkg_commission_dtls.service_id', $sType->service_id)
                                        ->where('tbl_pkg_commission_dtls.pkg_id', $userPkgId)->get();
                // $result[$sInd]['commission_dtl'] =(count($packageCommDetails)>0) ? $packageCommDetails : [];
                $serviceAlias = $sType->alias;
               
                if (count($packageCommDetails)>0) {
                   foreach ($packageCommDetails as $key_pk => $pkCmDtl) {
                        $result[$sInd]['commission_dtl'][$key_pk]['operator_logo_file'] = $pkCmDtl->operator_logo_file_id ? File::where('id', $pkCmDtl->operator_logo_file_id)->pluck('file_path')->first() : null;
                        $result[$sInd]['commission_dtl'][$key_pk]['operator_name'] = $pkCmDtl->operator_name;
                        $result[$sInd]['commission_dtl'][$key_pk]['helpline_no']  = $pkCmDtl->helpline_no;

                        $commission = '';
                        $commissionType = '';
    
                        if ($roleAlias == Config::get('constants.ROLE_ALIAS.RETAILER')) {
                            $commission = $pkCmDtl->retailer_commission;
                            $commissionType = $pkCmDtl->commission_type;
    
                            if ($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS')) {
                                $commissionType = $pkCmDtl->retailer_commission_type;
    
                                $result[$sInd]['commission_dtl'][$key_pk]['from_range'] = $pkCmDtl->from_range;
                                $result[$sInd]['commission_dtl'][$key_pk]['to_range'] = $pkCmDtl->to_range;
                            }
    
                        } else if ($roleAlias == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                            $commission = $pkCmDtl->distributor_commission;
                            $commissionType = $pkCmDtl->commission_type;
    
                            if ($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS')) {
                                $commissionType = $pkCmDtl->distributor_commission_type;
    
                                $result[$sInd]['commission_dtl'][$key_pk]['from_range'] = $pkCmDtl->from_range;
                                $result[$sInd]['commission_dtl'][$key_pk]['to_range'] = $pkCmDtl->to_range;
                            }
                        }
    
                        if ($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') && $roleAlias == Config::get('constants.ROLE_ALIAS.RETAILER')) {
                            $result[$sInd]['commission_dtl'][$key_pk]['charge'] = $commission ? abs($commission) : 0 ;
                            $result[$sInd]['commission_dtl'][$key_pk]['charge_type'] = $commissionType;
                        } else {
                            $result[$sInd]['commission_dtl'][$key_pk]['commission'] = $commission;
                            $result[$sInd]['commission_dtl'][$key_pk]['commission_type'] = $commissionType;
                        }


                    }
                }

    
            }

        }

        if (!empty($result)) {
            $statusMsg = "Success!!";
            return $this->sendSuccess($result, $statusMsg);
        } else {
            return $this->sendError('No records found!!');
        }

    }

    /**
     * Prepare Operator Commision List for a given service type
     */
    public function getOperatorCommission($serviceId, $serviceAlias, $pkCmDtls, $roleAlias)
    {
        $result = [];

        if (!empty($pkCmDtls)) {

            foreach ($pkCmDtls as $pCmInd => $pkCmDtl) {
                $key = [];
                if ($serviceId == $pkCmDtl->service_id) {
                    $opLogoId = OperatorSetting::where('operator_id', $pkCmDtl->operator_id)->pluck('operator_logo_file_id')->first();
                    $key['operator_logo_file'] = $opLogoId ? File::where('id',$opLogoId)->pluck('file_path')->first() : null;
                    $key['operator_name'] = OperatorSetting::where('operator_id', $pkCmDtl->operator_id)->pluck('operator_name')->first();
                    $key['helpline_no'] = OperatorSetting::where('operator_id', $pkCmDtl->operator_id)->pluck('helpline_no')->first();

                    $commission = '';
                    $commissionType = '';

                    if ($roleAlias == Config::get('constants.ROLE_ALIAS.RETAILER')) {
                        $commission = $pkCmDtl->retailer_commission;
                        $commissionType = $pkCmDtl->commission_type;

                        if ($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS')) {
                            $commissionType = $pkCmDtl->retailer_commission_type;

                            $key['from_range'] = $pkCmDtl->from_range;
                            $key['to_range'] = $pkCmDtl->to_range;
                        }

                    } else if ($roleAlias == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                        $commission = $pkCmDtl->distributor_commission;
                        $commissionType = $pkCmDtl->commission_type;

                        if ($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS')) {
                            $commissionType = $pkCmDtl->distributor_commission_type;

                            $key['from_range'] = $pkCmDtl->from_range;
                            $key['to_range'] = $pkCmDtl->to_range;
                        }
                    }

                    if ($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') && $roleAlias == Config::get('constants.ROLE_ALIAS.RETAILER')) {
                        $key['charge'] = $commission ? abs($commission) : 0 ;
                        $key['charge_type'] = $commissionType;
                    } else {
                        $key['commission'] = $commission;
                        $key['commission_type'] = $commissionType;
                    }

                }

                if (!empty($key)) {
                    array_push($result, $key);
                }
            }
        }

        return $result;
    }

}
