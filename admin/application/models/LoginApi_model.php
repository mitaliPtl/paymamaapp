<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class LoginApi_model extends CI_Model
{

    //get user authentication on username and password
    function getAuthUser($username, $password)
    {
        $this->db->select('BaseTbl.*, Roles.role,dist.*,state.*');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->join('tbl_district_mst as dist','dist.city_id = BaseTbl.district_id','right');
        $this->db->join('tbl_state_mst as state','state.state_id = BaseTbl.state_id','right');
        $where = "BaseTbl.username='".$username."' OR BaseTbl.mobile='".$username."' OR BaseTbl.email='".$username."' OR BaseTbl.username='".$username."'";
        //$this->db->where('BaseTbl.username', $username);
        $this->db->where($where);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        $user = $query->row();        
        
        if(!empty($user)){
            if(verifyHashedPassword($password, $user->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    function getAuthUser1($username, $password)
    {
        $this->db->select('BaseTbl.*,Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        // $this->db->join('tbl_district_mst as dist','dist.city_id = BaseTbl.district_id','right');
        // $this->db->join('tbl_state_mst as state','state.state_id = BaseTbl.state_id','right');
        $where = "BaseTbl.username='".$username."' OR BaseTbl.mobile='".$username."' OR BaseTbl.email='".$username."' OR BaseTbl.username='".$username."'";
        //$this->db->where('BaseTbl.username', $username);
        $this->db->where($where);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        $user = $query->row();        
        
        if(!empty($user)){
            if(verifyHashedPassword($password, $user->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    // set user api key after successfully login
    function setAuthUserToken($userInfo,$userId,$roleId)
    {
        $this->db->select('id');
        $this->db->from('tbl_users_login_session_dtl');
        $this->db->where('user_id', $userId);
        $this->db->where('role_id', $roleId);
        $query = $this->db->get();
        $token = $query->row();
        if(!empty($token)){
            $this->db->where('user_id', $userId);
            $this->db->where('role_id', $roleId);
            $this->db->update('tbl_users_login_session_dtl', $userInfo);
            return TRUE;
        }else{
            $this->db->insert('tbl_users_login_session_dtl', $userInfo);
            return TRUE;
        }
    }
    
    function checkKyc($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_kyc_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $token = $query->row();
        if(!empty($token) && $token->status == "APPROVED"){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function checkEKyc($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_ekyc');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $token = $query->row();
        if(!empty($token)){
            return true;
        } else {
            $data = array(
                'user_id' => $userId,
                'aadhaar_kyc' => '0',
                'zip_file' => '',
                'share_code' => '',
                'mobile' => '',
                'aadhaar_no' => '',
                'aadhaar_name' => '',
                'aadhaar_address' => '',
                'aadhaar_image' => '',
                'pan_kyc' => '0',
                'pan_no' => '',
                'pan_name' => '',
                'pan_file' => '',
                'bank_kyc' => '0',
                'acc_no' => '',
                'acc_name' => '',
                'ifsc_code' => '',
                'bank_name' => '',
                'branch_name' => '',
                'selfie_kyc' => '0',
                'selfie_image' => '',
                'success_score' => '',
                'business_kyc' => '0',
                'business_name' => '',
                'business_address' => '',
                'pincode' => '',
                'state' => '',
                'city' => '',
                'category' => '',
                'front_image' => '',
                'inside_image' => '',
                'latitude' => '',
                'longitude' => '',
                'blat' => '',
                'blong' => '',
                'complete_kyc' => '0'
            );
            $this->db->insert('tbl_ekyc',$data);
            return true;
        }
    }
    
    function UpdateIp($userId,$roleId) {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('userId', $userId);
        $this->db->where('roleId', $roleId);
        $query = $this->db->get();
        $token = $query->row();
        if(!empty($token)){
            $this->db->where('userId', $userId);
            $this->db->where('roleId', $roleId);
            $this->db->update('tbl_users', array('last_login_ip'=>$this->getRealIpAddr()));
            return TRUE;
        }else{
            return FALSE;
        }
        
    }

    function authenticateUser($userId,$roleId,$apiKey)
    {
        $this->db->select('apiKey');
        $this->db->from('tbl_users_login_session_dtl');
        $this->db->where('user_id', $userId);
        $this->db->where('role_id', $roleId);
        $query = $this->db->get();
        $data = $query->row();
        if(!empty($data->apiKey) && $data->apiKey == $apiKey){
            return true;
        }else{
            return false;
        }
    }
    
    function generateQr($store_name,$upi_id) {
        $data = 'name='.$store_name.'&vpa='.$upi_id.'&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_smartpay&'; //customtemplate id given by apiclub
        $response = $this->apiclub_api('generate_qr',$data);
        
        $resp = json_decode($response,true);
        if(isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {
            $qr_id = $resp['response']['qr_id'];
            return $qr_id;
        }
        return false;
    }
    
    function check_proxy() {
        $data = 'ip_address='.$this->getRealIpAddr();
        $response = $this->apiclub_api('check_proxy',$data);
        $resp = json_decode($response,true);
        // write_file('admin/ashish.txt', json_encode($resp));
        if(isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {
            $vpn =  ($resp['response']['ip_block'] == true) ? true : false;
            $ccode = $resp['response']['country_code'];
            if(!$vpn && $ccode == "IN") {
                return true;
            }
        } else {
            return true;
        }
        return false;
    }
    
    function check_country($code = 'IN') {
        $data = 'ip_address='.$this->getRealIpAddr();
        $response = $this->apiclub_api('ip_track',$data);
        $resp = json_decode($response,true);
        if(isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {
            $ccode = $resp['response']['country_code'];
            if($ccode == $code) {
                return true;
            }
        }
        return false;
    }
    
    function apiclub_api($action,$data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.apiclub.in/api/v1/'.$action,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Referer: paymamaapp.in',
            'API-KEY: 35aea0ed0c44d3b5906e4da7c914b8d6',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     	} else {
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        // return $ip;
        $ips = explode(',', $ip);
        return $ips[0];
    }
    
    function cashfree_va($action,$method,$data,$bearer="") {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://cac-api.cashfree.com/cac/v1/".$action,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array(
            'X-Client-Id: CF144639FC69AK4UATAYUIY',
            'X-Client-Secret: e82d6d3bffdb66f26db8e648a5b118e2e4fa38b4',
            'Authorization: Bearer '.$bearer
          ),
        ));
        $response = curl_exec($curl);
        $body = json_decode($response,true);
        curl_close($curl);
        if($body['subCode'] == '200' && $body['status'] == "SUCCESS") {
            return $body;
        }
        return false;
    }
}