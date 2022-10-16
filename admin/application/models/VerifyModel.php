<?php 

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class VerifyModel extends CI_Model
{
    
    public function create_va($name,$phone,$email,$account_no,$ifsc)
    {
        
        /*
        $this->load->model('verify_model');
        $account_number=$query->row()->account_number;
        $ifsc = $query->row()->ifsc_code;
        $createVa = $this->verify_model->create_va($fname,$mobile,$email,$account_number,$ifsc);
        if($createVa) {
            
            $this->load->model('login_model');
            $qr_id = $this->login_model->generateQr($store_name,$createVa['upi_id']) ?? "";
            $vdata = array(
                'va_id' => $createVa['va_id'],
                'va_account_number' => $createVa['account_no'],
                'va_ifsc_code' => $createVa['ifsc'],
                'va_upi_id' => $createVa['upi_id'],
                'qr_id' => $qr_id,
            );
            $updateVa = $this->verify_model->update_va($username,$vdata);
        }
        */
        require APPPATH . '/libraries/Cashfree.php';
        $clientId = "CF181206E0FI1B5H8LCI2QI";
        $clientSecret = "5e9641382c064b79e93b8c82a7d518131aa5cd8f";
        $stage = "PROD"; //TEST/PROD
        $authParams["clientId"] = $clientId;
        $authParams["clientSecret"] = $clientSecret;
        $authParams["stage"] = $stage;
        try {
          $autoCollect = new CfAutoCollect($authParams);
        } catch (Exception $e) {
          return false;
        }
        
        if($autoCollect) {
            $vid = rand(0000,99999999);
            $acc['vAccountId'] = $vid;
            $vpa['virtualVpaId'] = $phone;
            $account['name'] = $name;
            $account['email'] = $email;
            $account['phone'] = $phone;
            $account['remitterAccount'] = $account_no;
            $account['remitterIfsc'] = $ifsc;
            $resp_acc = $autoCollect->createVirtualAccount(array_merge($acc,$account));
            $resp_vpa = $autoCollect->createVirtualAccount(array_merge($vpa,$account));
            if($resp_acc['status'] == 'SUCCESS' && $resp_acc['subCode'] == 200 && $resp_vpa['status'] == 'SUCCESS' && $resp_vpa['subCode'] == 200) {
                $resp['account_no'] = $resp_acc['data']['accountNumber'];
                $resp['ifsc_code'] = $resp_acc['data']['ifsc'];
                $resp['va_id'] = $vid;
                $resp['upi_id'] = $resp_vpa['data']['vpa'];
                return $resp;
            }
        }
          
        return false;
    }
    
    public function update_va($username,$data) {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('username', $username);
        $query = $this->db->get();
        $token = $query->row();
        if(!empty($token)){
            $this->db->where('username', $username);
            $this->db->update('tbl_users', $data);
            return TRUE;
        } else{
            return FALSE;
        }
        
    }

   public function login($user_id,$password)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('mobile_no', $user_id)
        ->or_where('email',$user_id);

        $this->db->where('password', $password);
        $q = $this->db->get();
        $data = $q->result();
        return $data;
    }

   

    public function addMobile($alternate_mob_no,$email,$address,$mobile_name, $mobile_number,$parent_user_id,$parent_role_id,$role_id)
    {
       $post_data = array(
                'telecome_name' => $mobile_name,
                'alternate_mob_no'=>$alternate_mob_no,
                'email'=>$email,
                'address'=>$address,
                'mobile_number'=>$mobile_number,
                'roleID'=>$role_id,
                'parent_user_id'=>$parent_user_id,
                'parent_role_id'=>$parent_role_id,
                'step'=>1

                
            );
           // return $post_data;
          

            $this->db->insert('tbl_verification', $post_data);
             return $this->db->insert_id();
    }

     public function addPanData($pan_no,$pan_name,$id)
    {
       $post_data = array(
                'pan_name' => $pan_name,
                'pan_number'=>$pan_no   ,
                'step'=>2

                
            );
            //return $post_data;
                    $this->db->where('id', $id);

          

            $this->db->update('tbl_verification', $post_data);
            return $this->db->affected_rows();
    }


    public function phone_check($data)
    {
        $this->db->select('mobile');
        $this->db->from('tbl_users'); 
        $this->db->where('mobile',$data); 
        $query = $this->db->get();
        return $query->result();
    }
     public function phone_checks($data)
    {
        $this->db->select('mobile');
        $this->db->from('tbl_verification'); 
        $this->db->where('mobile_number',$data); 
        $query = $this->db->get();
        return $query->result();
    }



    public function addAadharData($aadhar_no,$aadhar_name,$id,$aadhar_address)
    {
       $post_data = array(
                'aadhar_number' => $aadhar_no,
                'aadhar_name'=>$aadhar_name,
                'aadhar_address'=>$aadhar_address,
                'step'=>3

                
            );
            //return $post_data;
          
             $this->db->where('id', $id);

            $this->db->update('tbl_verification', $post_data);
            return $this->db->affected_rows();
    }

     public function addBankInfo($name,$account_number,$ifsc,$id)
    {
       $post_data = array(
                'bank_account_name' => $name,
                'ifsc_code' => $ifsc,
                'account_number'=>$account_number,
                'step'=>4

                
            );
            //return $post_data;
          
             $this->db->where('id', $id);

            $this->db->update('tbl_verification', $post_data);
            return $this->db->affected_rows();
    }

 public function getAllStpes($id)
    {
        $this->db->select('step','id');
        $this->db->from('tbl_verification');
     $this->db->where('id', $id);


        $q = $this->db->get();
        $data = $q->result();
        return $data;
    }


/**
 * @DevelopedBy Swapna
 * @Date 27-09-2021
 * 
 */
 public function getBankVerificationToken($id)
    {
        $this->db->select('api_token');
        $this->db->from('tbl_api_settings');
        $this->db->where('api_id', $id);


        $q = $this->db->get();
        $data = $q->result();
        return $data;
    }



    
        


}
 ?>