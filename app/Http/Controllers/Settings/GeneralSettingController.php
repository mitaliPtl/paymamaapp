<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Config;
use DB;
use App\File;
use App\PackageSetting;
use App\ApplicationDetail;
use Illuminate\Http\Request;
use App\ServicesType;
use Illuminate\Support\Facades\Validator;

class GeneralSettingController extends Controller
{
        public function index(){
            $general_data = DB::table('tbl_application_details')->get();

            $social_alias = ['facebook', 'instagram', 'twitter', 'youtube'];
            $verify_charges = ['Bank verification charges from API',
                                'SMARTPAY verification Charges',
                                'Smart pay verification charges from master table',
                                'Bank Verification from Master data',
                                'upichargeclient',
                                'upichargeapi',
                                'upichargemaster'
                            ];
            $pay_limit = ['TDS', 'Paytm Limit', 'upilimit', 'upimode'];
            //live  array company
            $company = ['support_helpline', 'sales_helpline', 'email', 'website', 'company_name', 'company_address'];
            //uat  array company
            // $company = ['c_helpline'];

            $other = ['app_version'];

            $social_data = DB::table('tbl_application_details')->whereIn('alias', $social_alias)->get();
            $social_data = (count($social_data)>0) ? $social_data : [];
            // $general_data = (count($general_data)>0) ? $general_data : [];
           
            $verifycharges_data = DB::table('tbl_application_details')->whereIn('alias', $verify_charges)->get();
            $verifycharges_data = (count($verifycharges_data)>0) ? $verifycharges_data : [];

            $paylimit_data = DB::table('tbl_application_details')->whereIn('alias', $pay_limit)->get();
            $paylimit_data = (count($paylimit_data)>0) ? $paylimit_data : [];

            $company_data = DB::table('tbl_application_details')->whereIn('alias', $company)->get();
            $company_data = (count($company_data)>0) ? $company_data : [];

            $other_data = DB::table('tbl_application_details')->whereIn('alias', $other)->get();
            $other_data = (count($other_data)>0) ? $other_data : [];

            $qrCodeFile = ApplicationDetail::where('alias', 'qr_code')->get()->first();
            $qrCodeFile = (isset($qrCodeFile)) ? $qrCodeFile : [];

            // print_r($qrCodeFile);
            // exit();

            return view('modules.settings.general_setting', compact('social_data', 'verifycharges_data', 'paylimit_data', 'company_data', 'other_data', 'qrCodeFile'));
        }

        public function updateSocialMedia(Request $request)
        {
                $update_facebook = DB::table('tbl_application_details')->where('alias', 'facebook')->update(['value' => $request->facebook, 'updated_at'=> now()]);
                $update_instagram = DB::table('tbl_application_details')->where('alias', 'instagram')->update(['value' => $request->instagram, 'updated_at'=> now()]);
                $update_twitter = DB::table('tbl_application_details')->where('alias', 'twitter')->update(['value' => $request->twitter, 'updated_at'=> now()]);
                $update_youtube = DB::table('tbl_application_details')->where('alias', 'youtube')->update(['value' => $request->youtube, 'updated_at'=> now()]);

            return back()->with('success', 'Social Media Updated Successfully!!');
        }

        public function updateVerifyCharges(Request $request){
            $request=$request->all();
            foreach ($request as $key => $value) {
                if ($key == '_token') {
                   continue;
                }
            //   print_r($value);
                $update_facebook = DB::table('tbl_application_details')->where('id', $key)->update(['value' => $value, 'updated_at'=> now()]);

            }
            return back()->with('success', 'Verification Charges Updated Successfully!!');
        }

        public function updatePayLimit(Request $request){
            $request=$request->all();
    
            foreach ($request as $key => $value) {
                if ($key == '_token') {
                   continue;
                }
            //   print_r($value);
                $update_paylimit = DB::table('tbl_application_details')->where('id', $key)->update(['value' => $value, 'updated_at'=> now()]);

            }
            $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER');
            $service_id = ServicesType::where(['is_deleted' => Config::get('constants.NOT-DELETED'), 'alias' => $serviceTypeAlias, 'activated_status' => 'YES'])->pluck('service_id')->first();
            $upimode = DB::table('tbl_application_details')->where('id', '26')->pluck('value')->first();
            $update = DB::table('tbl_operator_settings')->where('service_id', $service_id)->update(['default_api_id' => $upimode, 'updated_on'=> now()]);
            return back()->with('success', 'Payment Limits Updated Successfully!!');
        }

        public function updateCompany(Request $request){
            $request=$request->all();
            foreach ($request as $key => $value) {
                if ($key == '_token') {
                   continue;
                }
            //   print_r($value);
                $update_compny = DB::table('tbl_application_details')->where('id', $key)->update(['value' => $value, 'updated_at'=> now()]);

            }
            return back()->with('success', 'Company Details Updated Successfully!!');
        }

        public function updateOther(Request $request){
            $request=$request->all();
            foreach ($request as $key => $value) {
                if ($key == '_token') {
                   continue;
                }
                if($key == 'pg_mode') {
                    $pg_data = DB::table('tbl_payment_gateway_integation')->update(['is_active' => '0', 'updated_on'=> now()]);
                    $pg_data = DB::table('tbl_payment_gateway_integation')->where('payment_gateway_name', $value)->update(['is_active' => '1', 'updated_on'=> now()]);
                    continue;
                }
            //   print_r($value);
                $update_other_dtls = DB::table('tbl_application_details')->where('id', $key)->update(['value' => $value, 'updated_at'=> now()]);

            }
            return back()->with('success', 'Other Details Updated Successfully!!');
        }

   
   
    public function updateQRCode(Request $req){
        
        $update_arr = [];
        $get_qr = ApplicationDetail::where('alias', 'qr_code')->get()->first();
        
        $update_arr['name'] =  $req->qr_code_name;
        
       
        if ($req->file()) {

            $req->validate([
                'file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048',
            ]);
    
           
            $fileName = time() . '_' . $req->file->getClientOriginalName();
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
            
            $qr_name = time() . '_' . $req->file->getClientOriginalName();
            // $fileModel->file_path = '/storage/' . $filePath;
            $qr_path = '/storage/app/public/' . $filePath;
            $update_arr['value'] =  $qr_path;
            // $update_qr = ApplicationDetail::where('alies', 'qr_code')->update([ 'name'=> $req->qr_code_name]);
            // if ($response) {
            //     return response()->json($fileModel);
            // }

        }else {
            $update_arr['value'] =  $get_qr->value;
        }
        $update_qr = [];
        $update_arr['alias']= 'qr_code';
        if ($get_qr) {
            
            
            $update_arr['updated_at']= now();
            $update_qr = ApplicationDetail::where('alias', 'qr_code')->update($update_arr);
        }else {
            $update_qr = ApplicationDetail:: insert($update_arr);
        }

      
       
        
        if ($update_qr) {
            return back()->with('success', 'QR Code Updated');
        }else {
            return back()->with('error', 'QR Code Not Updated');
        }

    }
}