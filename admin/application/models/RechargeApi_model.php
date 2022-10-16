<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class RechargeApi_model extends CI_Model
{
    
    function send_telegram($userID,$tg_msg)
    {
        $this->db->select('telegram_no');
        $this->db->from('tbl_users');
        $this->db->where('userId', $userID);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        $user = $query->row();
        
        if(empty($user) && empty($user->telegram_no)){
            return array();
        }
        $tg_url = $path = "https://api.telegram.org/bot2059334712:AAGrn4QSKM3tD2rGsFKRjRtGUX5QZYE2Sf8/sendMessage";
        $data = array(
            "chat_id" => $user->telegram_no,
            "text" => $tg_msg,
            "parse_mode" => "HTML"
        );
        $curld = curl_init();
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curld, CURLOPT_URL, $tg_url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        return true;
    }
    
    function send_telegram_api($order_id)
    {
        $url = "https://paymamaapp.in/api/telegram_order_api";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = array(
            "order_id"=>$order_id,
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $resp = curl_exec($curl);
        curl_close($curl);
    }

    //get user authentication on username and password
    function getAuthUser($username, $password)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.password, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.username', $username);
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

    function getUserBalance($userID)
    {
        $this->db->select('wallet_balance,min_balance,package_id');
        $this->db->from('tbl_users');
        $this->db->where('userId', $userID);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        $user = $query->row();
        
        if(!empty($user)){
            return $user;
        } else {
            return array();
        }
    }

    function getCommissionDetails($packageID,$serviceID,$operatorID,$amount)
    {
        $this->db->select('*');
        $this->db->from('tbl_pkg_commission_dtls');
        $whr_array = array('service_id'=>$serviceID,'pkg_id'=>$packageID,'operator_id'=>$operatorID,'is_deleted'=>0);
        $this->db->where($whr_array);
        $query = $this->db->get();        
        $commission = $query->row();
        foreach($commission as $commsnKey => $commsnVal){
            if($commsnKey == "commission_type" && $commsnVal == "Range"){
                $this->db->select('*');
                $this->db->from('tbl_pkg_commission_dtls');
                $whr_array = array('service_id'=>$serviceID,'pkg_id'=>$packageID,'operator_id'=>$operatorID,'is_deleted'=>0,'from_range<='=> $amount,'to_range>='=> $amount);
                $this->db->where($whr_array);
                $query = $this->db->get();        
                $commission = $query->row();
                break;
            }
        }        
        if(!empty($commission)){
            return $commission;
        } else {
            return array();
        }
    }

    function updateUserBalance($userId,$wallet_balance)
    {
        $userInfo = array('wallet_balance' => $wallet_balance);
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        return true;
    }

    function getUserParentID($userID)
    {
        $query = $this->db->query("select userId,roleId,wallet_balance from tbl_users where isDeleted=0 and userId in (select parent_user_id from tbl_users where userId='".$userID."')");        
        $user = $query->row(); 
        if(!empty($user)){
            return $user;
        } else {
            return array();
        }
    }

    function getValidateMPIN($userID,$mpin)
    {
        $this->db->select('userId');
        $this->db->from('tbl_users');
        $this->db->where('mpin', $mpin);
        $this->db->where('userId', $userID);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        $user = $query->row();
        
        if(!empty($user)){
            return $user;
        } else {
            return array();
        }
    }

    function getActiveApiDetails($operatorID,$serviceID,$amount)
    {
        if($serviceID == 1 || $serviceID == 2){ //recharge and bill payment
            $this->db->select('*');
            $this->db->from('tbl_api_by_amount_dtls');
            $whr_array = array('operator_id'=>$operatorID,'amount'=>$amount,'is_deleted'=>0,'activated_status'=>'YES');
            $this->db->where($whr_array);
            $query = $this->db->get();        
            $apiDtl = $query->row();
            if(!empty($apiDtl)){
                $this->db->select('*');
                $this->db->from('tbl_api_settings');
                $whr_array = array('api_id'=>$apiDtl->api_id,'is_deleted'=>0,'activated_status'=>'YES');
                $this->db->where($whr_array);
                $query = $this->db->get();        
                $apiDtl = $query->row();
                if(!empty($apiDtl)){
                    return $apiDtl;
                }else {
                    return array();
                }
            } else {
                $this->db->select('*');
                $this->db->from('tbl_operator_settings');
                $whr_array = array('operator_id'=>$operatorID,'service_id'=>$serviceID,'is_deleted'=>0,'activated_status'=>'YES');
                $this->db->where($whr_array);
                $query = $this->db->get();        
                $apiDtl = $query->row();
                if(!empty($apiDtl)){
                    $this->db->select('*');
                    $this->db->from('tbl_api_settings');
                    $whr_array = array('api_id'=>$apiDtl->default_api_id,'is_deleted'=>0,'activated_status'=>'YES');
                    $this->db->where($whr_array);
                    $query = $this->db->get();        
                    $apiDtl = $query->row();
                    if(!empty($apiDtl)){
                        return $apiDtl;
                    }else {
                        return array();
                    }
                }
            }
        }else {
            $this->db->select('*');
            $this->db->from('tbl_operator_settings');
            $whr_array = array('operator_id'=>$operatorID,'service_id'=>$serviceID,'is_deleted'=>0,'activated_status'=>'YES');
            $this->db->where($whr_array);
            $query = $this->db->get();        
           $apiDtl = $query->row();
             if(!empty($apiDtl)){
                $this->db->select('*');
                $this->db->from('tbl_api_settings');
                $whr_array = array('api_id'=>$apiDtl->default_api_id,'is_deleted'=>0,'activated_status'=>'YES');
                //print_r($whr_array);
                $this->db->where($whr_array);
                $query = $this->db->get();        
                $apiDtl = $query->row();
                if(!empty($apiDtl)){
                    return $apiDtl;
                }else {
                    return array();
                }
            }
        }
    }
     function getActiveApiDetailsclone($apiis)
    {
      
             
                $this->db->select('*');
                $this->db->from('tbl_api_settings');
                $whr_array = array('api_id'=>$apiis,'is_deleted'=>0,'activated_status'=>'YES');
                //print_r($whr_array);
                $this->db->where($whr_array);
                $query = $this->db->get();        
                $apiDtl = $query->row();
                if(!empty($apiDtl)){
                    return $apiDtl;
                }else {
                    return array();
                }
            
        
    }
    public function getTDS(){
        $sql=$this->db->query("SELECT value FROM `tbl_application_details` where id='7'");
        return $sql->row();
    }
    public function getcommission($serviceID,$packageID,$operatorID,$amount){
        $sql=$this->db->query("SELECT * FROM `tbl_pkg_commission_dtls` where from_range<=".$amount." AND to_range>=".$amount." AND service_id=".$serviceID." and pkg_id=".$packageID." and operator_id=".$operatorID."");
         return $sql->row();
        // $this->db->select('*');
        //         $this->db->from('tbl_pkg_commission_dtls');
        //         $whr_array = array('service_id'=>$serviceID,'pkg_id'=>$packageID,'operator_id'=>$operatorID,'is_deleted'=>0,'from_range<='=> $amount,'to_range>='=> $amount);
        //         $this->db->where($whr_array);
        //         $query = $this->db->get();        
        //         $commission = $query->row();
        // if(!empty($commission)){
        //     return $commission;
        // } else {
        //     return array();
        // }
    }
    public function getOne($table,$id,$field){
        $sql=$this->db->query("SELECT * FROM `".$table."` where ".$field."='".$id."'");
        return $sql;
    }
    public function getAll($table){
        $sql=$this->db->query("SELECT * FROM `".$table."`");
        return $sql->result();
    }
    public function getbiller_bycategory($category){
        $this->db->select('billerId,billerIcon,billerName,billerCategory,billerCoverage,billerInputParams,billercustomizeInputParams,billercustomize,billerAmountOptions,billerPaymentModes');
        $this->db->from('tbl_bbps_list');
        
        $this->db->like('billerCategory',$category);
        $query = $this->db->get();        
         return $query->result();
    }
    public function getbillermaster_bycategory($category){
        
         $query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `blr_category_name` LIKE '".$category."'")->result_array();
         // $query=$this->db->query("SELECT * FROM `tbl_bbps_master` WHERE `blr_category_name` LIKE 'Education Fees' limit 0,2000")->result();
          
           //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>2094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
           //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>4094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
           //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>6094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
          //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>8094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
          //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>10094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
          //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>12094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
          //CHECK TOMMORROW FROM THIS ON WORDS
          //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>14094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
           //$query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>16094 and  blr_category_name like 'Education Fees' limit 0,2000")->result();
          // $query=$this->db->query("SELECT *  FROM `tbl_bbps_master` WHERE `id`>18094 and  blr_category_name like 'Education Fees' limit 0,2000")->result_array();
         
                
         return $query;
    }
    
}

  