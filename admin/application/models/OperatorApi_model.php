<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class OperatorApi_model extends CI_Model
{
    
    //fetch all the operators
    function getOperators()
    {
        $this->db->select('id,service_type,operator_code,operator_name');
        $this->db->from('tbl_operators');
        $query = $this->db->get();
        return $query->result();
    }

    function getServiceDetailsByOperatorID($operator_ID)
    {
        $this->db->select('operator_code,service_id,offers_121_op_code,operator_name');
        $this->db->from('tbl_operator_settings');
        $this->db->where('operator_id',$operator_ID);
        $query = $this->db->get();
        if($query)
        	return $query->result();
        else 
        	return array();
    }

    function getOperatorDetailsByID($serviceID,$apiID,$operatorID)
    {
        $this->db->select('operator_code');
        $this->db->from('tbl_api_operator_dtls');
        $this->db->where('service_id',$serviceID);
        $this->db->where('api_id',$apiID);
        $this->db->where('operator_id',$operatorID);
        $query = $this->db->get();
        if($query)
            return $query->result();
        else 
            return array();
    }

}

  