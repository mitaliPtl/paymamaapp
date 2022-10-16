<?php 

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class VerifyModel extends CI_Model
{

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

   

    public function addMobile($mobile_name,$mobile_number)
    {
       $post_data = array(
                'telecome_name' => $mobile_name,
                'mobile_number'=>$mobile_number,
                'step'=>1

                
            );
            //return $post_data;
          

            $this->db->insert('tbl_verification', $post_data);
             return $this->db->insert_id();
    }

     public function addPanData($pan_no,$pan_name,$id)
    {
       $post_data = array(
                'pan_name' => $pan_name,
                'pan_number'=>$pan_number,
                'step'=>2

                
            );
            //return $post_data;
                    $this->db->where('id', $id);

          

            $this->db->update('tbl_verification', $post_data);
            return $this->db->affected_rows();
    }


    public function addAadharData($aadhar_no,$aadhar_name,$id)
    {
       $post_data = array(
                'aadhar_number' => $aadhar_no,
                'aadhar_name'=>$aadhar_name,
                'step'=>3

                
            );
            //return $post_data;
          
             $this->db->where('id', $id);

            $this->db->update('tbl_verification', $post_data);
            return $this->db->affected_rows();
    }

 public function getAllStpes($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_verification');
     $this->db->where('id', $id);


        $q = $this->db->get();
        $data = $q->result();
        return $data;
    }



    
        


}
 ?>