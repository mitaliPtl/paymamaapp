<?php

namespace App\Http\Controllers\User;

use App\City;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\KycDetail;
use App\PackageSetting;
use App\Role;
use App\SmsTemplate;
use App\State;
use App\StoreCategory;
use App\TransactionDetail;
use App\ApplicationDetail;
use App\User;
use App\ApiSetting;
use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class APIUserController extends Controller
{
    // public function index(Request $request){
    //     // print_r($request->all());
    //     $u_id = $request->user_id;
    //     $user_list = User::leftJoin('tbl_package_settings', 'tbl_users.package_id', '=', 'tbl_package_settings.package_id')
    //                         // ->where('parent_user_id', $request->user_id);
    //                         ->where(function($q) use ($u_id) {
    //                             $q->where('tbl_users.parent_user_id', $u_id)
    //                             ->orWhere('tbl_users.fos_id', $u_id);
    //                         })
    //                         ->where('tbl_users.isDeleted', '0')
    //                         ->where('tbl_users.isSpam', '0');
    //     // if(isset($request->user_type) && $request->user_type){
    //     //     $user_type = 'constants.'.$request->user_type;
    //     //     $user_type = Config::get($user_type);
    //     //     $user_list = $user_list->where('roleId', $user_type);
    //     // }
    //     if(isset($request->filter_value) && $request->filter_value){
    //         $filter_value = $request->filter_value;
    //         $user_list = $user_list->where(function($q) use ($filter_value) {
    //                                         $q->where('tbl_users.store_name','like', '%'. $filter_value.'%')
    //                                         ->orWhere('tbl_users.username','like', '%'. $filter_value.'%')
    //                                         ->orWhere('tbl_users.mobile','like', '%'.$filter_value.'%')
    //                                         ->orWhere('tbl_users.first_name','like', '%'.$filter_value.'%')
    //                                         ->orWhere('tbl_users.last_name','like', '%'.$filter_value.'%');
    //                                 });
    //     }

    //     $user_list = $user_list->get();         
    //     $user_list = $this->modifyUserList($user_list);
    //     if(count($user_list)>0){
            
    //         $statusMsg = "Success!!";
    //         return $this->sendSuccess($user_list, $statusMsg);
    //     }else{
    //         return $this->sendError("Sorry!! Not Found");
    //     }

    //     // return $userList[3]['kyc_dtls'];        
    // }
 public function index(Request $request){
        // print_r($request->all());
        $u_id = $request->user_id;
        $user_list = User::leftJoin('tbl_package_settings', 'tbl_users.package_id', '=', 'tbl_package_settings.package_id')
                            // ->where('parent_user_id', $request->user_id);
                            ->where(function($q) use ($u_id) {
                                $q->where('tbl_users.parent_user_id', $u_id)
                                ->orWhere('tbl_users.fos_id', $u_id);
                            })
                            ->where('tbl_users.isDeleted', '0')
                            ->where('tbl_users.isSpam', '0');
        if(isset($request->user_type) && $request->user_type){
            $user_type = 'constants.'.$request->user_type;
            $user_type = Config::get($user_type);
            $user_list = $user_list->where('roleId', $user_type);
        }
        if(isset($request->filter_value) && $request->filter_value){
            $filter_value = $request->filter_value;
            $user_list = $user_list->where(function($q) use ($filter_value) {
                                            $q->where('tbl_users.store_name','like', '%'. $filter_value.'%')
                                            ->orWhere('tbl_users.username','like', '%'. $filter_value.'%')
                                            ->orWhere('tbl_users.mobile','like', '%'.$filter_value.'%')
                                            ->orWhere('tbl_users.first_name','like', '%'.$filter_value.'%')
                                            ->orWhere('tbl_users.last_name','like', '%'.$filter_value.'%');
                                    });
        }

        $user_list = $user_list->get();         
        $user_list = $this->modifyUserList($user_list);
        if(count($user_list)>0){
            
            $statusMsg = "Success!!";
            return $this->sendSuccess($user_list, $statusMsg);
        }else{
            return $this->sendError("Sorry!! Not Found");
        }

        // return $userList[3]['kyc_dtls'];        
    }

    public function modifyUserList($userList)
    {
        $result = [];
        if ($userList) {
            $result_=[];

            foreach ($userList as $i => $user) {

                $result_[$i]['user_id'] = $user->userId;
                $result_[$i]['role_name'] = Role::where('roleId',$user->roleId)->pluck('role')->first();
                $result_[$i]['role_id'] = $user->roleId;
                $result_[$i]['first_name'] = $user->first_name;
                $result_[$i]['last_name'] = $user->last_name;
                $result_[$i]['username'] = $user->username;
                $result_[$i]['store_name'] = $user->store_name;
                $result_[$i]['parent_store_name'] = User::where('userId',$user->parent_user_id)->pluck('store_name')->first();
                $result_[$i]['mobile'] = $user->mobile;
                $result_[$i]['balance'] = (int) $user->wallet_balance;
                $result_[$i]['package_name'] = $user->package_name;
                $result_[$i]['reg_date'] = $user->createdDtm;
                $result_[$i]['min_balance'] = $user->min_balance;
                $result_[$i]['last_activity'] = $this->getUserLastActive($user->userId);
                $result_[$i]['activated_status'] = $user->activated_status;
                $result_[$i]['fos_id'] = "";
                $result_[$i]['fos_firstname'] = "";
                $result_[$i]['fos_lastname'] = "";
                $result_[$i]['fos_username'] = "";
                if ($user->fos_id) {
                    $fos_user = User::find((int)$user->fos_id );
                    if ($fos_user) {
                        $result_[$i]['fos_id'] =$fos_user->userId ;
                        $result_[$i]['fos_firstname'] = $fos_user->first_name ;
                        $result_[$i]['fos_lastname'] = $fos_user->last_name ;
                        $result_[$i]['fos_username'] = $fos_user->username ;
                    }
                }

                // $result_[$i]['parent_role_name'] = Role::where('roleId',$user->parent_role_id)->pluck('role')->first();
               
                // $result_[$i]['store_category_name'] = StoreCategory::where('id',$user->store_category_id)->pluck('store_category_name')->first();
                // $result_[$i]['parent_user_first_name'] = User::where('userId',$user->parent_user_id)->pluck('first_name')->first();
                // $result_[$i]['parent_user_last_name'] = User::where('userId',$user->parent_user_id)->pluck('last_name')->first();
                
                // $result_[$i]['state_name'] = state::where('state_id',$user->state_id)->pluck('state_name')->first();
                // $result_[$i]['city_name'] = City::where('city_id',$user->district_id)->pluck('city_name')->first();

                
                // $result_[$i]['kyc_dtls'] = $this->getUserKycStatus($user->userId);
            }
            $result = $result_;
        }
        return $result;
    }

        /**
     * Get User Last activity by providing user id
     */
    public function getUserLastActive($userId)
    {
        $lastActiveDt = "";

        $lastActiveDate = TransactionDetail::where('user_id', $userId)->orderBy('trans_date', 'DESC')->first();

        if ($lastActiveDate) {
            $lastActiveDt = $lastActiveDate->trans_date;
        }

        return $lastActiveDt;
    }


     /**
     * Get User Kyc status
     */
    public function getUserKycStatus($userId)
    {
        $kycDtls = "";

        $kycRes = KycDetail::where('user_id', $userId)
            ->with(['panFile', 'aadharFrontFile', 'aadharBackFile', 'photoFrontFile', 'photoInnerFile'])
            ->get();

        if (isset($kycRes) && count($kycRes) > 0) {
            $kycDtls = $kycRes[0];
        }

        return $kycDtls;
    }

    
}