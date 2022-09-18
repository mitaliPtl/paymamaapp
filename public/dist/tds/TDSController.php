<?php

namespace App\Http\Controllers\TDS;
use Auth;
use Config;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Http\Controllers\Controller;
use App\Role;
use App\TDS;

class TDSController extends Controller{

    public function index(Request $request){
        // print_r($request->all());
        $users = User::select('userId', 'roleId', 'store_name', 'username')
                        ->where('roleId', Config::get('constants.DISTRIBUTOR'))
                        ->orWhere('roleId', Config::get('constants.RETAILER'))
                        ->get();
        // print_r($users);

        return view('modules.tds.tds', compact('users'));
    } 

    public function uploadTDS(Request $request){
        // print_r($request->all());

        $uploaded_tds = TDS::create([
                            'user_id'=> $request->user_id,
                            "role_id" =>$request->role_id,
                            "tds_period"=>$request->tds_period,
                            "file_id"=>$request->uploaded_file_id,
                            "created_on"=>now(), 

                        ]);
        if($uploaded_tds){
            return  back()->with("success", "TDS Uploaded successfully!!"); 
        }else{
            return  back()->with("error", " TDS Not Uploaded !!"); 
        }
    }

    public  function viewTDSById($id = null, Request $request){
        // print_r($id);
        $tds_user=[];
        $tds_user = TDS::leftJoin('tbl_files', 'tbl_tds.file_id', '=', 'tbl_files.id')
                        ->where('user_id', $id)->get();
        
        // print_r($tds_user);

        return view('modules.tds.viewTDSByUserId', compact('tds_user'));
        
    }

    public function getUserTDSAPI(Request $request){

        if(isset($request->user_id) && $request->user_id){
            $tds_user = TDS::leftJoin('tbl_files', 'tbl_tds.file_id', '=', 'tbl_files.id')
                            ->where('user_id', $request->user_id)->get();

            if($tds_user){
                $statusMsg = "Success !!";
                $success = [];
                
                foreach($tds_user as $tds_user_key => $tds_user_value){
                    
                  
                    $success[$tds_user_key]['file_path'] = Config::get('constants.WEBSITE_BASE_URL'). $tds_user_value->file_path;
                    $success[$tds_user_key]['tds_period'] = $tds_user_value->tds_period;
                    $success[$tds_user_key]['created_on'] = $tds_user_value->created_on;
                    // $success['tds_period'] = $tds_user_value->tds_user;
                    // $success['file_path'] = $tds_user_value->file_path;
                    // $success['created_on'] = $tds_user_value->created_on;
                }
               
                return $this->sendSuccess($success, $statusMsg);
            }else{
                return $this->sendError('TDS Not Found !!');
            } 

        }else{
            return $this->sendError('User Required Not Found !!');
        }
        
    }

}