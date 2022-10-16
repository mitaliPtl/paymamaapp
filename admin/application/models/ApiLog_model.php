<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class ApiLog_model extends CI_Model
{
    
    //add api log details
    function addApiLogDetails($apiInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_apilog_dts', $apiInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    function update_api_amount($data,$id)
    {
      $this->db->where('api_id',$id);
      $this->db->update('tbl_api_settings',$data);
    }

}

  