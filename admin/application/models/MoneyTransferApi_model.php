<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class MoneyTransferApi_model extends CI_Model
{
    //add new beneficiary/recipient
    function addNewRecepient($recipientInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_dmt_benificiary_dtls', $recipientInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    

    function check_sender_mobile($mobile){
        $this->db->select('*');
        $this->db->from('tbl_sender_dts');
        $this->db->where('sender_mobile_number',$mobile);
        $query = $this->db->get();        
        $sender = $query->row();      
        if(!empty($sender)){
            return $sender;
        } else {
            return array();
        }
    }
    function getUser_code($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
    function get_singlebyid($table,$field,$value)
    {
        $this->db->select('*');
        $this->db->from($table);
       
        $this->db->where($field,$value);
        $query = $this->db->get();
        
        return $query->row();
    }
    function get_sender_details($mobile){
        $this->db->select('*');
        $this->db->from('tbl_sender_dts');
        $this->db->where('sender_mobile_number',$mobile);
        $this->db->where('api_name','paytm');
        $query = $this->db->get();
        
        return $query->row();

    }
    function getdata_where($table,$where, $orderby=''){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        if ($orderby!='') {
           
            $this->db->order_by("recipient_id", $orderby);
        }
        $query = $this->db->get();
        return $query->result();
    }
function get_bank_list()
    {
    	$this->db->select('*');
        $this->db->from('tbl_bank_list');
        $this->db->order_by("BANK_NAME", "asc");
        $query = $this->db->get();        
        $bank = $query->result_array();      
        if(!empty($bank)){
            return $bank;
        } else {
            return array();
        }
    }
  
    function get_receipt_list($mobile,$id='', $order=''){
        $this->db->select('t1.*');
        $this->db->select('t2.BANK_NAME as bank_name,t2.IMPS_Status as IMPS_mode,t2.NEFT_Status as NEFT_mode');
        $this->db->from('tbl_dmt_benificiary_dtls as t1');
        $this->db->join('tbl_bank_list as t2','t1.bank_code = trim(t2.ShortCode)','left');
        if($id==''){
        
        $this->db->where('t1.api_name','paytm');
         }
        $this->db->where('t1.sender_mobile_number',$mobile);
        $this->db->where('t1.is_deleted',0);
        if($id!=''){
            $this->db->where('t1.recipient_id',$id);
        }

        if ($order!='') {
           
            $this->db->order_by("t1.recipient_id", $order);
        }
        $query = $this->db->get();
        
        return $user = $query->result();

    }
    function checkduplicate_transaction($transdate,$where){
        $this->db->select('*');
        $this->db->from('tbl_transaction_dtls');
        $this->db->like('trans_date',$transdate);

        // $this->db->->where('trans_date', 'like', $transdate.'%')
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function checkduplicate_transaction_new($transdate,$where,$min){
         $cDate=date('Y-m-d H:i:s');
         
      
         $newtimestamp = strtotime($cDate.' - '.$min.' minute');
         $cDate1= date('Y-m-d H:i:s', $newtimestamp);


       
        //print_r($newtimestamp) ;

        //    $sql="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' And service_id='".$where['service_id']."' AND operator_id='".$where['operator_id']."' AND recipient_id='".$where['recipient_id']."' AND request_amount='".$where['request_amount']."'"; 
        $sql="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' AND request_amount='".$where['request_amount']."'"; 
        if (isset($where['operator_id']) && $where['operator_id']) {
           $sql = $sql."AND operator_id='".$where['operator_id']."'";
        }
        if (isset($where['service_id']) && $where['service_id']) {
            $sql = $sql."AND service_id='".$where['service_id']."'";
         }
         if (isset($where['recipient_id']) && $where['recipient_id']) {
            $sql = $sql."AND recipient_id='".$where['recipient_id']."'";
         }

         // print_r($sql);


         // $sql="SELECT * from tbl_transaction_dtls WHERE  DATE(trans_date) >= DATE_SUB(DATE(NOW()), INTERVAL 5 MINUTE) AND DATE(trans_date) < DATE(NOW()) And service_id='".$where['service_id']."' AND operator_id='".$where['operator_id']."' AND recipient_id='".$where['recipient_id']." ' AND request_amount='".$where['request_amount']."'"; 
         $query = $this->db->query($sql);
         return $query->result();        

      }


   function get_senderdts_byaccno($accno){
      $this->db->select('t2.sender_mobile_number,t2.available_limit_crazy,t2.used_limit_crazy,t2.available_limit,t2.used_limit,t2.Upi_available_limit,t2.Upi_used_limit,CONCAT(t2.first_name, '.', t2.last_name) as sender_name', FALSE);
        $this->db->from('tbl_dmt_benificiary_dtls as t1');
        $this->db->join('tbl_sender_dts as t2','t1.sender_mobile_number = t2.sender_mobile_number');
        $this->db->where('t1.bank_account_number',$accno);
        $this->db->where('t2.api_name', 'paytm');
        $this->db->where('t1.is_deleted', '0');
        $query = $this->db->get();
        
        return $user = $query->result();

   }
   public function get_table_alllike($table,$column,$keyword){
         
        $this->db->select("*");
        $this->db->from($table); 
        $this->db->like($column, $keyword);
       return $this->db->get()->result();
    }
}

  