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
use DB;
use Config;
use Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DMTMarginController extends Controller
{
    public function index(Request $request)
    {
       
        // $packageCommDetails = PackageCommissionDetail::
        //     where('service_id', $request->service_id)
        //     ->where('pkg_id', $request->pkg_id);

        // if (isset($request->operator_id) && $request->operator_id) {
        //     $packageCommDetails->where('operator_id', $request->operator_id);
        // }

        // $packageCommDetails = $packageCommDetails->get();
        $where = [];
        $where_in = [];
        if (isset($request->pkg_id) && $request->pkg_id ) {
            $where['package_id'] = $request->pkg_id;
            $where_in =[Config::get('constants.DISTRIBUTOR'), Config::get('constants.RETAILER')] ;
        }

        if (isset($request->service_id) && $request->service_id ) {
            $where['service_id'] = $request->service_id;
        }

        if (isset($request->operator_id) && $request->operator_id ) {
            $where['operator_id'] = $request->operator_id;
        }

        $dtmargin = DB::table('tbl_dmt_margin')->where($where)->whereIn('role_id', $where_in)->orderBy('role_id', 'DESC')->get();
        $dtmargin =   (count($dtmargin)>0)? $dtmargin :[];
    
        $packageSettings = PackageSetting::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $servicesTypes = ServicesType::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $operators = OperatorSetting::where('service_id', $request->service_id)->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        return view('modules.settings.dmt_margin', compact('operators', 'servicesTypes', 'packageSettings', 'request', 'dtmargin'));
    }

    public function updateMargin(Request $request){
        
        $where = [];
        if (isset($request->margin_pkg_id) && $request->margin_pkg_id ) {
            $where['package_id'] = $request->margin_pkg_id;
        }

        if (isset($request->margin_service_id) && $request->margin_service_id ) {
            $where['service_id'] = $request->margin_service_id;
        }

        if (isset($request->margin_op_id) && $request->margin_op_id ) {
            $where['operator_id'] = $request->margin_op_id;
        }

        $dtmargin = DB::table('tbl_dmt_margin')->where($where)->get()->first();
       
        if($dtmargin){

           
            $update_dtmargin_r = DB::table('tbl_dmt_margin')->where('id', $request->user_4)
                                    ->update(['margin' => json_encode($request->r_margin),
                                            'updated_on' => now() ]);
            $update_dtmargin_d = DB::table('tbl_dmt_margin')->where('id', $request->user_2)
                                    ->update(['margin' => $request->d_margin,
                                            'updated_on' => now() ]);
            if ($update_dtmargin_r && $update_dtmargin_d) {
                return redirect('dmt_margin')->with('success', 'DMT margin Updated Successfully ');
                }else {
                    return redirect('dmt_margin')->with('success', 'DMT margin Failled  ');
                }

        }else {
            
            $where['role_id'] = Config::get('constants.RETAILER');
            $where['margin'] = json_encode($request->r_margin);
            $where['created_on'] = now();
            $dtmargin_insert_r = DB::table('tbl_dmt_margin')->insert($where);

            $where['role_id'] = Config::get('constants.DISTRIBUTOR');
            $where['margin'] = $request->d_margin;
            $where['created_on'] = now();
            $dtmargin_insert_d = DB::table('tbl_dmt_margin')->insert($where);

            if ($dtmargin_insert_r && $dtmargin_insert_r) {
               return redirect('dmt_margin')->with('success', 'DMT margin Added Successfully ');
            }else {
                return redirect('dmt_margin')->with('error', 'DMT margin Not Added ');
            }

        }

    }

    public function getDMTMarginAPI(Request $request){
        // print_r($request->all());

        $user_package = User::select('package_id')->where('userId', $request->user_id)->get()->first();
        // print_r($user_package->package_id);

        $dtmargin = DB::table('tbl_dmt_margin')
                        ->where('role_id', $request->role_id)
                        ->where('package_id', $user_package->package_id)->get();
        // print_r($dtmargin);

        if(count($dtmargin)>0){

            foreach ($dtmargin as $key => $value) {
                $dtmargin[$key]->operator_id = OperatorSetting::getOperatorNameById($value->operator_id);
                $dtmargin[$key]->margin = json_decode($value->margin, true);
            }
            $statusMsg = "Success!!";

            return $this->sendSuccess($dtmargin, $statusMsg);
        }else{
            return $this->sendError("No records found!!");
        }

    }
}