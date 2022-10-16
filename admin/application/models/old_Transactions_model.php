<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Transactions_model extends CI_Model
{
    
    //add new transaction
    function addNewTransaction($transInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_transaction_dtls', $transInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function getAllTransactions($serviceID,$user_id,$reportType,$id='')
    {
    	if($serviceID == "1"){
            $serviceID  = array('1','2','3');
            if($reportType == "transactions"){
        		$this->db->select('TransTbl.id,TransTbl.transaction_id,TransTbl.trans_date,
        			TransTbl.transaction_status,TransTbl.order_status,TransTbl.order_id,
        			Operators.operator_name, Operators.service_type,
        			TransTbl.mobileno,TransTbl.total_amount
        			,TransTbl.charge_amount,TransTbl.basic_amount,TransTbl.debit_amount,TransTbl.credit_amount,TransTbl.balance,file.file_path as operator_icon');
    	        $this->db->from('tbl_transaction_dtls as TransTbl');
    	        $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id');/*for operator logo*/
    	        $this->db->where_in('TransTbl.service_id', $serviceID);
    	        $this->db->where('TransTbl.user_id', $user_id);
                if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
    	        $this->db->where('TransTbl.id_deleted', 0);
    	        $this->db->order_by("TransTbl.id", "desc");
            }else if($reportType == "commission"){
                $this->db->select('userId');
                $this->db->from('tbl_users');
                $this->db->where('parent_user_id', $user_id);
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);             
                $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,TransTbl.trans_date,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,TransTbl.order_id,TransTbl.total_amount,TransTbl.transaction_type,file.file_path as operator_icon,TransTbl.payment_mode as description');
                $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id');/*for operator logo*/
                $this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                $this->db->where_in('TransTbl.service_id', $serviceID);
                $this->db->where_in('TransTbl.user_id', $usersArray);
                if($id!=''){
                $this->db->where('txnTable.id', $id);
                }
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->where('TransTbl.payment_type', 'COMMISSION');
                $this->db->order_by("TransTbl.id", "desc");
            }else if($reportType == "passbook"){
                $this->db->select('userId');
                $this->db->from('tbl_users');
                $this->db->where('parent_user_id', $user_id);
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                $this->db->select('concat(users.first_name," ",users.last_name) as name,TransTbl.payment_mode as description,TransTbl.trans_date,TransTbl.total_amount,TransTbl.transaction_type,TransTbl.order_id,TransTbl.balance,Operators.operator_name,file.file_path as operator_icon');
                $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id');/*for operator logo*/
                $this->db->where_in('TransTbl.service_id', $serviceID);
                $this->db->where_in('TransTbl.user_id', $usersArray);

                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
            }
    	}else if($serviceID == "5"){
            $this->db->select('TransTbl.id,TransTbl.order_id as order_no,TransTbl.trans_date as trans_date,TransTbl.imps_name as name,TransTbl.mobileno as sender_no, TransTbl.transaction_type as mode,TransTbl.total_amount as amount,TransTbl.charge_amount as charge_amount,Services.service_name,TransTbl.bank_transaction_id,TransTbl.order_status,TransTbl.remarks,TransTbl.CCFcharges,TransTbl.Cashback,TransTbl.TDSamount,TransTbl.PayableCharge,TransTbl.FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id');
            //get accountno and ifsc
            $this->db->where('TransTbl.service_id', $serviceID);
            $this->db->where('TransTbl.user_id', $user_id);
            if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.id", "desc");
        }else{
           $this->db->select('TransTbl.*, Services.service_name,Benificiary.bank_account_number,Benificiary.ifsc');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id');
            //get accountno and ifsc
            $this->db->where('TransTbl.service_id', $serviceID);
            $this->db->where('TransTbl.user_id', $user_id);
            if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.id", "desc"); 
        }
        $query = $this->db->get();        
        $transactions = $query->result();      
        if(!empty($transactions)){
            return $transactions;
        } else {
            return array();
        }
    }
    function getAllTransactions_withoutserviceeid($user_id,$reportType)
    {          if($reportType == "passbook"){ 
               $this->db->select('userId');
                $this->db->from('tbl_users');
                $this->db->where('parent_user_id',$user_id);
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                $this->db->select('concat(users.first_name," ",users.last_name) as name,TransTbl.payment_mode as description,TransTbl.trans_date,TransTbl.total_amount,TransTbl.transaction_type,TransTbl.order_id,TransTbl.balance,Operators.operator_name,file.file_path as operator_icon');
                $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id');/*for operator logo*/
                $this->db->where_in('TransTbl.user_id', $usersArray);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
            }

        $query = $this->db->get();        
        $transactions = $query->result();      
        if(!empty($transactions)){
            return $transactions;
        } else {
            return array();
        }

    }
    //add new wallet transaction
    function addNewWalletTransaction($wallet_trans_info)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_wallet_trans_dtls', $wallet_trans_info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function getLastTxnID()
    {
        $this->db->select('order_id');
        $this->db->from('tbl_transaction_dtls as TransTbl');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();        
        $transactions = $query->result();      
        if(!empty($transactions)){
            return $transactions;
        } else {
            return array();
        }
    }

    function array_default_key($array) {
        $arrayTemp = array();
        $i = 0;
        foreach ($array as $key1 => $value) {
            foreach ($value as $key => $val) {
                $arrayTemp[$i] = $val;
                $i++;
            }
        }
        return $arrayTemp;
    }
    public function getmoneyreport($transid){
      $this->db->select('TransTbl.order_id as order_no,TransTbl.trans_date as trans_date,TransTbl.imps_name as name,TransTbl.mobileno as sender_no, TransTbl.transaction_type as mode,TransTbl.total_amount as amount,TransTbl.charge_amount as charge_amount,Services.service_name,TransTbl.bank_transaction_id,TransTbl.order_status,TransTbl.remarks,TransTbl.CCFcharges,TransTbl.Cashback,TransTbl.TDSamount,TransTbl.PayableCharge,TransTbl.FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
            //get accountno and ifsc
            
            $this->db->where('TransTbl.id', $transid);
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.id", "desc");
            $query = $this->db->get();        
        $transactions = $query->row();      
        if(!empty($transactions)){
            return $transactions;
        } else {
            return array();
        }

    }
    public function getapptab($id){
        $sql=$this->db->query("SELECT value FROM `tbl_application_details` where id=".$id."");
        return $sql->row();
    }
     public function getmoney_domultiple_report($order_id){
      $this->db->select('TransTbl.order_id as order_no,TransTbl.trans_date as trans_date,TransTbl.imps_name as name,TransTbl.mobileno as sender_no, TransTbl.transaction_type as mode,sum(TransTbl.total_amount) as amount,sum(TransTbl.charge_amount) as charge_amount,Services.service_name,
        TransTbl.bank_transaction_id,TransTbl.order_status,TransTbl.remarks,sum(TransTbl.CCFcharges) as CCFcharges,sum(TransTbl.Cashback) as Cashback,sum(TransTbl.TDSamount) as TDSamount,sum(TransTbl.PayableCharge) as PayableCharge,sum(TransTbl.FinalAmount) as FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
            //get accountno and ifsc
            
            $this->db->where('TransTbl.order_id',$order_id);
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.id", "desc");
            $query = $this->db->get();        
        $transactions = $query->row();      
        if(!empty($transactions)){
            return $transactions;
        } else {
            return array();
        }

    }
     public function getmoney_domultiple_group_report($group_id){
      $this->db->select('TransTbl.order_id as order_no,TransTbl.group_id,TransTbl.trans_date as trans_date,TransTbl.imps_name as name,TransTbl.mobileno as sender_no, TransTbl.transaction_type as mode,sum(TransTbl.total_amount) as amount,sum(TransTbl.charge_amount) as charge_amount,Services.service_name,
        TransTbl.bank_transaction_id,TransTbl.order_status,TransTbl.remarks,sum(TransTbl.CCFcharges) as CCFcharges,sum(TransTbl.Cashback) as Cashback,sum(TransTbl.TDSamount) as TDSamount,sum(TransTbl.PayableCharge) as PayableCharge,sum(TransTbl.FinalAmount) as FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
            //get accountno and ifsc
            
            $this->db->where('TransTbl.group_id',$group_id);
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.id", "desc");
            $query = $this->db->get();        
        $transactions = $query->result();      
        if(!empty($transactions)){
            return $transactions;
        } else {
            return array();
        }

    }
    public function check_order_id($orderid){
     $this->db->select('TransTbl.order_id');
     $this->db->from('tbl_transaction_dtls as TransTbl');
     $this->db->join('tbl_apilog_dts as api','api.order_id = TransTbl.order_id');
     $this->db->join('tbl_wallet_trans_dtls as wallet','wallet.order_id = TransTbl.order_id');
     $this->db->where('TransTbl.order_id',$orderid);
     $this->db->or_where('api.order_id',$orderid);
     $this->db->or_where('wallet.order_id',$orderid);
     $query = $this->db->get();
     return $order = $query->row();
    }
}

  