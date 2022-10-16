<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/MobileDetect.php';
require APPPATH . '/libraries/BaseController.php';

class LoginApi extends BaseController {
    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('loginApi_model');
    }

    /**
     * This function used as a login api
     */
    public function index() {
        $data =  json_decode(file_get_contents('php://input'), true);
     
        if (!empty($data['userName']) && !empty($data['passWord'])) {
            $username = $data['userName'];
            $password = $data['passWord'];
            $firebase_token = $data['firebase_token'];
            $userdata = $this->loginApi_model->getAuthUser1($username, $password);
            
            if ($userdata) {
                $userId = $userdata->userId;
                $roleId = $userdata->roleId;

                $date = date("Y-m-dh:i:s");

                $apiKey = md5($userId . $roleId . $date);

                $userInfo = array('user_id' => $userId, 'role_id' => $roleId, 'apiKey' => $apiKey, 'firebase_token' => $firebase_token);

                $result = $this->loginApi_model->setAuthUserToken($userInfo, $userId, $roleId);
                
                $check_kyc = $this->loginApi_model->checkKyc($userId);
                $check_ekyc = $this->loginApi_model->checkEKyc($userId);

                $result_response = array(
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'role_title' => $userdata->role,
                    'token' => $apiKey,
                    'first_name' => $userdata->first_name,
                    'last_name' => $userdata->last_name,
                    'mobile' => $userdata->mobile,
                    'email' => $userdata->email,
                    'username' => $userdata->username,
                    'storeName' => $userdata->store_name,
                    'profilePicId' => $userdata->profile_pic_id,
                    'walletBalance' => $userdata->wallet_balance,
                    'city_name'=>$userdata->city_name ?? '',
                    'city_id'  =>$userdata->city_id ?? '',
                    'city_code'=>$userdata->city_code ?? '',
                    'state_name'=>$userdata->state_name ?? '',
                    'state_id'=>$userdata->state_id ?? '',
                    'state_code'=>$userdata->state_code ?? '',
                    'va_upi_id'=>'',
                    'va_account_number'=>'',
                    'va_ifsc_code'=>'',
                    'bank_name'=>'IDFC FIRST BANK',
                    'qr_id'=>$userdata->qr_id,
                    'aeps_kyc'=>$userdata->aeps_kyc,
                    'zip_code'=>$userdata->zip_code,
                    'address'=>$userdata->address,
                );
                 $response = array(
                    'status' => "true",
                    'msg' => "User logged in successfully.",
                    'result' => $result_response
                );
                $check_proxy = $this->loginApi_model->check_proxy();
                if($check_proxy) {
                    $this->loginApi_model->UpdateIp($userId,$roleId);
                    $response = array(
                        'status' => "true",
                        'msg' => "User logged in successfully.",
                        'result' => $result_response
                    );
                } else {
                    $response = array(
                    'status' => "false",
                        'msg' => "Please disable your VPN to continue.",
                        'result' => null
                    );
                }
                
            } else {
                $response = array(
                    'status' => "false",
                    'msg' => "username or password not matched",
                    'result' => null
                );
            }
        }else{
            $response = array(
                'status' => "false",
                'msg' => "username or password invalid",
                'result' => null
            );
        }
        echo json_encode($response);
        exit;
    }
    
    public function test() {
        return $this->loginApi_model->getAuthUser('RT100171', '680924');
    }
}

?>