<?php 

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class VerifyModel extends CI_Model
{
     public function insertpan($id_card,$filespath)
    { 
        
        
        $post_data = array(
                'name' => $filesname,
                'file_path'=>$filespath

                
            );
            //return $post_data;
          

            $this->db->insert('tbl_files', $post_data);
             return $this->db->insert_id();
    }
     public function insertselfie($selfie,$filespath)
    { 
        
        
        $post_data = array(
                'name' => $filesname,
                'file_path'=>$filespath

                
            );
            //return $post_data;
          

            $this->db->insert('tbl_files', $post_data);
             return $this->db->insert_id();
    }
}
?>