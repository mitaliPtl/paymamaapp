<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
// require APPPATH . '/libraries/MobileDetect.php';
class Transactions_model extends CI_Model
{
    
    //add new transaction
    function addNewTransaction($transInfo)
    {
        $source = 'WEB';
        if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {
            $source = 'APP';
        }
        $iparr = array('ip_address' => $this->getRealIpAddr(),'source' => $source);
        $transInfo = array_merge($transInfo,$iparr);
        $this->db->trans_start();
        $this->db->insert('tbl_transaction_dtls', $transInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     	} else {
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
        // $ips = explode(',', $ip);
        // return $ips[0];
    }

    function getAllTransactions($serviceID,$user_id,$reportType,$id='',$from='',$to='',$operator_id='',$order_status='',$mobileno='',$role_id='',$limit='',$start='')
    {
        if($serviceID == "1"){
            $serviceID  = array('1','2','3');
             if($reportType == "commission"){
              $this->db->select('userId');
                $this->db->from('tbl_users');
                 //$this->db->where('roleId',$roleId);
                if ($role_id == '3') {
                    $this->db->where('fos_id', $user_id);

                }else {
                    $this->db->where('parent_user_id', $user_id);

                }
                $this->db->where('parent_user_id', $user_id);
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,txnTable.order_id,file.file_path as operator_icon,txnTable.operator_id as operator_id,txnTable.updated_on as trans_date,txnTable.user_id,users.store_name,txnTable.api_id,users.mobile as retailer_mobile');
                $this->db->from('tbl_transaction_dtls as txnTable');
                $this->db->join('tbl_operators as Operators','Operators.id = txnTable.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = txnTable.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                //$this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                $this->db->join('tbl_users as users','users.userId = txnTable.user_id');
                $this->db->where_in('txnTable.service_id',$serviceID);
                if($roleId!='1'){
                $this->db->where_in('txnTable.user_id', $usersArray);
                  }
                if($from!=''){
                    $this->db->where('date(txnTable.updated_on)>=', $from);      
                }else {
                    $this->db->where('date(txnTable.updated_on)>=', date('Y-m-d'));      
                    
                }
                if($to!=''){
                 $this->db->where('date(txnTable.updated_on) <=', $to);
                }
                if($operator_id!=''){
                 $this->db->where('txnTable.operator_id',$operator_id);
                }
                //$this->db->where('txnTable.id_deleted', 0);
                //$this->db->where('txnTable.payment_type', 'COMMISSION');
                $this->db->order_by("txnTable.updated_on", "desc");
                 if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }             
                // $this->db->select('userId');
                // $this->db->from('tbl_users');
                // $this->db->where('parent_user_id', $user_id);
                // $usersList = $this->db->get()->result(); 
                // $usersArray = $this->array_default_key($usersList);   
                // array_push($usersArray,$user_id);             
                // $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,TransTbl.trans_date,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,TransTbl.order_id,TransTbl.total_amount,TransTbl.transaction_type,file.file_path as operator_icon,TransTbl.payment_mode as description');
                // $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                // $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                // /*for operator logo*/
                // $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                // $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id');/*for operator logo*/
                // $this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                // $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                // $this->db->where_in('TransTbl.service_id', $serviceID);
                // $this->db->where_in('TransTbl.user_id', $usersArray);
                // if($id!=''){
                // $this->db->where('txnTable.id', $id);
                // }
                //  if($from!=''){
                //     $this->db->where('date(TransTbl.updated_on)>=', $from);
                      
                // }
                // if($to!=''){
                //  $this->db->where('date(TransTbl.updated_on) <=', $to);
                // }
                // if($operator_id!=''){
                //  $this->db->where('TransTbl.operator_id',$operator_id);
                // }
                // $this->db->where('TransTbl.id_deleted', 0);
                // $this->db->where('TransTbl.payment_type', 'COMMISSION');
                // $this->db->order_by("TransTbl.id", "desc");
                //  if ($limit!= '' && $start!= '') {
                //   $this->db->limit($limit,$start);
                //  }
            }else if($reportType == "passbook"){
                $this->db->select('userId');
                $this->db->from('tbl_users');
                if ($role_id == '3') {
                    $this->db->where('fos_id', $user_id);

                }else {
                    $this->db->where('parent_user_id', $user_id);

                }
                // $this->db->where('parent_user_id', $user_id);
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
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=', $from);      
                }else {
                    $this->db->where('date(TransTbl.updated_on)>=',  date('Y-m-d'));   
                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }
            }else{
                $this->db->select('TransTbl.id,TransTbl.transaction_id,TransTbl.trans_date,
                    TransTbl.transaction_status,TransTbl.order_status,TransTbl.order_id,
                    Operators.operator_name, Operators.service_type,
                    TransTbl.mobileno,TransTbl.total_amount
                    ,TransTbl.charge_amount,TransTbl.basic_amount,TransTbl.debit_amount,TransTbl.credit_amount,TransTbl.balance,file.file_path as operator_icon,users.store_name,users.mobile');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id');/*for operator logo*/
                 $this->db->join('tbl_users as users','TransTbl.user_id = users.userId','left');
                $this->db->where_in('TransTbl.service_id', $serviceID);
                
                if($operator_id!=''){
                 $this->db->where('TransTbl.operator_id',$operator_id);
                }
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=',$from);
                }else {
                    $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));
                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                if($order_status!=''){
                 $this->db->where('TransTbl.order_status',$order_status);
                }
                if($mobileno!=''){
                 $this->db->where('TransTbl.mobileno',$mobileno);
                }
                
                if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
                if (($role_id=="2")||($role_id=="3")){
                    $user_sql = 'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                    if ($role_id=="3") {
                        $user_sql = 'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                    }
                   $userlist=$this->db->query($user_sql)->result();
                  foreach($userlist as $up){
                   $childuser[]=$up->userId;
                   }
                   if(!empty($childuser)){
                    //$allchilduser=implode(',',$childuser);
                    $this->db->where_in('TransTbl.user_id',$childuser);
                   }
                }
                else{
                    $this->db->where('TransTbl.user_id', $user_id);
                }
               
                $this->db->where('TransTbl.id_deleted', 0);

                $this->db->order_by("TransTbl.id", "desc");
                if ($limit!= '' && $start!= '') {
                
                   
                  $this->db->limit($limit,$start);
                 
                 }
            }
        }
        else if($serviceID == "4"){
            //$serviceID  = array('4');
             if($reportType == "commission"){
                $this->db->select('userId');
                $this->db->from('tbl_users');
                 //$this->db->where('roleId',$roleId);
                if ($role_id == '3') {
                    $this->db->where('fos_id', $user_id);

                }else {
                    $this->db->where('parent_user_id', $user_id);

                }
                // $this->db->where('parent_user_id', $user_id);
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,txnTable.api_id ,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,txnTable.order_id,file.file_path as operator_icon,txnTable.operator_id as operator_id,txnTable.updated_on as trans_date,txnTable.user_id,users.store_name,users.mobile as retailer_mobile,txnTable.response_msg,bbps.billerName,bbps.billerIcon');
                $this->db->from('tbl_transaction_dtls as txnTable');
                $this->db->join('tbl_operators as Operators','Operators.id = txnTable.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = txnTable.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                //$this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                $this->db->join('tbl_users as users','users.userId = txnTable.user_id');
                $this->db->join('tbl_bbps_list as bbps','txnTable.billerID = bbps.billerId','left');
                $this->db->where_in('txnTable.service_id',$serviceID);
                if($roleId!='1'){
                $this->db->where_in('txnTable.user_id', $usersArray);
                  }
                  if($id!=''){
                $this->db->where('txnTable.id', $id);
                }
                if($from!=''){
                    $this->db->where('date(txnTable.updated_on)>=', $from);
                      
                }else {
                    $this->db->where('date(txnTable.updated_on)>=', date('Y-m-d'));    
                }
                if($to!=''){
                 $this->db->where('date(txnTable.updated_on) <=', $to);
                }
                if($operator_id!=''){
                 $this->db->where('txnTable.operator_id',$operator_id);
                }
                //$this->db->where('txnTable.id_deleted', 0);
                //$this->db->where('txnTable.payment_type', 'COMMISSION');
                $this->db->order_by("txnTable.updated_on", "desc");
                 if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }             
                // $this->db->select('userId');
                // $this->db->from('tbl_users');
                // $this->db->where('parent_user_id', $user_id);
                // $usersList = $this->db->get()->result(); 
                // $usersArray = $this->array_default_key($usersList);   
                // array_push($usersArray,$user_id);             
                // $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,TransTbl.trans_date,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,TransTbl.order_id,TransTbl.total_amount,TransTbl.transaction_type,file.file_path as operator_icon,TransTbl.payment_mode as description');
                // $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                // $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                // /*for operator logo*/
                // $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                // $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                // $this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                // $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                // $this->db->where('TransTbl.service_id', $serviceID);
                // $this->db->where_in('TransTbl.user_id', $usersArray);
                // if($id!=''){
                // $this->db->where('txnTable.id', $id);
                // }
                //  if($from!=''){
                //     $this->db->where('date(TransTbl.updated_on)>=', $from);
                      
                // }
                // if($to!=''){
                //  $this->db->where('date(TransTbl.updated_on) <=', $to);
                // }
                // if($operator_id!=''){
                //  $this->db->where('TransTbl.operator_id',$operator_id);
                // }
                // $this->db->where('TransTbl.id_deleted', 0);
                // $this->db->where('TransTbl.payment_type', 'COMMISSION');
                // $this->db->order_by("TransTbl.id", "desc");
                // if ($limit!= '' && $start!= '') {
                //   $this->db->limit($limit,$start);
                //  }
            }else if($reportType == "passbook"){
                $this->db->select('userId');
                $this->db->from('tbl_users');
                if ($role_id == '3') {
                    $this->db->where('fos_id', $user_id);

                }else {
                    $this->db->where('parent_user_id', $user_id);

                }
                // $this->db->where('parent_user_id', $user_id);
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                $this->db->select('concat(users.first_name," ",users.last_name) as name,TransTbl.payment_mode as description,TransTbl.trans_date,TransTbl.total_amount,TransTbl.transaction_type,TransTbl.order_id,TransTbl.balance,Operators.operator_name,file.file_path as operator_icon');
                $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=', $from);
                      
                }else {
                    $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      
    
                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                $this->db->where('TransTbl.service_id', $serviceID);
                $this->db->where_in('TransTbl.user_id', $usersArray);

                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }
            }else{
                $this->db->select('TransTbl.id,TransTbl.transaction_id,TransTbl.trans_date,
                    TransTbl.transaction_status,TransTbl.order_status,TransTbl.order_id,
                    Operators.operator_name, Operators.service_type,
                    TransTbl.mobileno,TransTbl.total_amount
                    ,TransTbl.charge_amount,TransTbl.basic_amount,TransTbl.debit_amount,TransTbl.credit_amount,TransTbl.balance,file.file_path as operator_icon,TransTbl.txnRespType,TransTbl.inputParams,TransTbl.CustConvFee,TransTbl.RespAmount,TransTbl.RespBillDate,TransTbl.RespBillNumber,TransTbl.RespBillPeriod,TransTbl.RespCustomerName,TransTbl.RespDueDate,users.store_name,users.mobile,TransTbl.response_msg,bbps.billerName,bbps.billerIcon');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                 $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                 /*for operator logo*/
                 $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                 $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');//for operator logo
                  $this->db->join('tbl_users as users','TransTbl.user_id = users.userId','left');
                  $this->db->join('tbl_bbps_list as bbps','TransTbl.billerID = bbps.billerId','left');
                 $this->db->where('TransTbl.service_id',$serviceID);
                 if(($role_id=="2")||($role_id=="3")){
                    $user_sql = 'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                    if ($role_id=="3") {
                        $user_sql = 'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                    }
                   $userlist=$this->db->query($user_sql)->result();
                  foreach($userlist as $up){
                   $childuser[]=$up->userId;
                   }
                   if(!empty($childuser)){
                    //$allchilduser=implode(',',$childuser);
                    $this->db->where_in('TransTbl.user_id',$childuser);
                   }
                }
                else{
                    $this->db->where('TransTbl.user_id', $user_id);
                }
                 //$this->db->where('TransTbl.user_id',$user_id);
                if($id!=''){
                $this->db->where('TransTbl.id',$id);
                }
                if($operator_id!=''){
                 $this->db->where('TransTbl.operator_id',$operator_id);
                }
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=',$from);
                }else {
                    $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));    
                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                if($order_status!=''){
                 $this->db->where('TransTbl.order_status',$order_status);
                }
                if($mobileno!=''){
                 $this->db->where('TransTbl.mobileno',$mobileno);
                }
                 $this->db->where('TransTbl.id_deleted', 0);
                 $this->db->order_by("TransTbl.updated_on", "desc");
                 if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }
            }
        }else if($serviceID == "5"){
            $this->db->select('TransTbl.id,
            TransTbl.order_id as order_no,
            TransTbl.trans_date as trans_date,
            TransTbl.imps_name as name,
            TransTbl.mobileno as sender_no,
            TransTbl.api_id,
            TransTbl.operator_id,
             TransTbl.transaction_type as mode,
             TransTbl.total_amount as amount,
             TransTbl.charge_amount as charge_amount,
             Services.service_name,
             TransTbl.bank_transaction_id,
             TransTbl.order_status,
             TransTbl.remarks,
             TransTbl.CCFcharges,
             TransTbl.Cashback,
             TransTbl.TDSamount,
             TransTbl.PayableCharge,
             TransTbl.FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc,users.store_name,users.mobile');
            //  $this->db->select('TransTbl.id,
            //  TransTbl.order_id as order_no,
            //  TransTbl.trans_date as trans_date,
            //  TransTbl.imps_name as name,
            //  TransTbl.mobileno as sender_no,
            //   TransTbl.transaction_type as mode,
            //   TransTbl.total_amount as amount,
            //   TransTbl.charge_amount as charge_amount,
            //     Services.service_name,
            //   TransTbl.bank_transaction_id,
            //   TransTbl.order_status,
            //   TransTbl.remarks,
            //   TransTbl.CCFcharges,
            //   TransTbl.Cashback,
            //   TransTbl.TDSamount,
            //   TransTbl.PayableCharge,
            //   TransTbl.FinalAmount,users.store_name,users.mobile');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id');
            $this->db->join('tbl_users as users','TransTbl.user_id = users.userId','left');
            //get accountno and ifsc
            $this->db->where('TransTbl.service_id', $serviceID);
            if(($role_id=="2")||($role_id=="3")){
                $user_sql = 'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                if ($role_id=="3") {
                    $user_sql = 'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                }
               $userlist=$this->db->query($user_sql)->result();
                  foreach($userlist as $up){
                   $childuser[]=$up->userId;
                   }
                   if(!empty($childuser)){
                    //$allchilduser=implode(',',$childuser);
                    $this->db->where_in('TransTbl.user_id',$childuser);
                   }
                }
                else{
                    $this->db->where('TransTbl.user_id', $user_id);
                }
            //$this->db->where('TransTbl.user_id', $user_id);
            if($operator_id!=''){
                 $this->db->where('TransTbl.operator_id',$operator_id);
                }
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=',$from);      
                }
                // else {
                //     $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      

                // }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                if($order_status!=''){
                 $this->db->where('TransTbl.order_status',$order_status);
                }
                if($mobileno!=''){
                 $this->db->where('TransTbl.mobileno',$mobileno);
                }
            if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.updated_on", "desc");
            if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }
        }else if( $serviceID == "7"){
            // $this->db->select('TransTbl.id,
            // TransTbl.order_id as order_no,
            // TransTbl.trans_date as trans_date,
            // TransTbl.imps_name as name,
            // TransTbl.mobileno as sender_no,
            //  TransTbl.transaction_type as mode,
            //  TransTbl.total_amount as amount,
            //  TransTbl.charge_amount as charge_amount,
            //  Services.service_name,
            //  TransTbl.bank_transaction_id,
            //  TransTbl.order_status,
            //  TransTbl.remarks,
            //  TransTbl.CCFcharges,
            //  TransTbl.Cashback,
            //  TransTbl.TDSamount,
            //  TransTbl.PayableCharge,
            //  TransTbl.FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc,users.store_name,users.mobile');
             $this->db->select('TransTbl.id,
             TransTbl.order_id as order_no,
             TransTbl.trans_date as trans_date,
             TransTbl.imps_name as name,
             TransTbl.mobileno as sender_no,
             TransTbl.api_id ,
              TransTbl.transaction_type as mode,
              TransTbl.total_amount as amount,
              TransTbl.charge_amount as charge_amount,
              TransTbl.operator_id,
                Services.service_name,
              TransTbl.bank_transaction_id,
              TransTbl.order_status,
              TransTbl.remarks,
              TransTbl.CCFcharges,
              TransTbl.Cashback,
              TransTbl.TDSamount,
              TransTbl.PayableCharge,
              TransTbl.FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc,users.store_name,users.mobile');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id');
            $this->db->join('tbl_users as users','TransTbl.user_id = users.userId','left');
            //get accountno and ifsc
            $this->db->where('TransTbl.service_id', $serviceID);
            if(($role_id=="2")||($role_id=="3")){
                $user_sql = 'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                if ($role_id=="3") {
                    $user_sql = 'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                }
               $userlist=$this->db->query($user_sql)->result();
                  foreach($userlist as $up){
                   $childuser[]=$up->userId;
                   }
                   if(!empty($childuser)){
                    //$allchilduser=implode(',',$childuser);
                    $this->db->where_in('TransTbl.user_id',$childuser);
                   }
                }
                else{
                    $this->db->where('TransTbl.user_id', $user_id);
                }
            //$this->db->where('TransTbl.user_id', $user_id);
            if($operator_id!=''){
                 $this->db->where('TransTbl.operator_id',$operator_id);
                }
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=',$from);      
                }
                else {
                    // $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      
                    $this->db->where('date(TransTbl.updated_on)=', date('Y-m-d'));      

                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                if($order_status!=''){
                 $this->db->where('TransTbl.order_status',$order_status);
                }
                if($mobileno!=''){
                 $this->db->where('TransTbl.mobileno',$mobileno);
                }
            if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.updated_on", "desc");
            if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }
        }
        else if( $serviceID == "6" or  $serviceID == 6){
           
           
             $this->db->select('TransTbl.id,
             TransTbl.order_id as trans_date,
             TransTbl.order_id as order_id,
             TransTbl.transaction_id as transaction_id,
             TransTbl.client_reference_id as client_reference_id,
             TransTbl.rrnno,
              TransTbl.mobileno as mobileno,
              TransTbl.aadharnumber as aadharnumber,
              TransTbl.aeps_bank_id as aeps_bank_id,
              TransTbl.total_amount,
              
              TransTbl.retailer_commision,
              TransTbl.order_status,
              TransTbl.distributor_commision,,
              TransTbl.response_msg');
        
           
            $this->db->from('tbl_transaction_dtls as TransTbl');
            
            $this->db->order_by("id", "desc");
          
        }
          else if( $serviceID == 9){
           
             $this->db->select('TransTbl.id,
             TransTbl.trans_date as trans_date,
             TransTbl.order_id as order_id,
             TransTbl.transaction_id as transaction_id,
             TransTbl.client_reference_id as client_reference_id,
             TransTbl.rrnno,
              TransTbl.mobileno as mobileno,
              TransTbl.aadharnumber as aadharnumber,
              TransTbl.aeps_bank_id as aeps_bank_id,
              TransTbl.aeps_balance as aeps_balance,
              TransTbl.order_status as order_status,
              TransTbl.total_amount,
              TransTbl.retailer_commision,
               TransTbl.distributor_commision,
              TransTbl.response_msg');
          
           
            
            $this->db->from('tbl_transaction_dtls as TransTbl');
             if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=', $from);      
                }else {
                    $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      
                    
                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
            $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
            $st="TransTbl.service_id=9";
            $this->db->order_by("id", "desc");
            $this->db->where($st);
          
        }else if($serviceID == "100"){
             $this->db->select('*');
             $this->db->from('tbl_payment_gateway_report as TransTbl');
             if(($role_id=="2")||($role_id=="3")){
                 $user_sql = "";
                 if ($role_id=="3"){
                     $user_sql =  'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                 } else {
                     $user_sql =  'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                 }
                 $userlist=$this->db->query($user_sql)->result();
                 foreach($userlist as $up){
                     $childuser[] = $up->userId;
                 }
                 if(!empty($childuser)){
                     $this->db->where_in('TransTbl.user_id',$childuser);
                 }
             } else {
                 $this->db->where('TransTbl.user_id', $user_id);
             }
             
            //  $sql = 'SELECT * from tbl_virtual_trans_dtls WHERE user_id ='.$user_id.'';
            //  $txns = $this->db->query($sql)->result();
            //  return $txns;
             
             if($from!=''){
                 $this->db->where('date(TransTbl.trans_date)>=',$from);      
             }
             else {
                 $this->db->where('date(TransTbl.trans_date)>=', date('Y-m-d'));      
             }
            if($to!=''){
                $this->db->where('date(TransTbl.trans_date) <=', $to);
            }
            //payment mode should be "CARD","UPI","NET BANKING","WALLET"
            if($order_status!=''){
                $this->db->where('TransTbl.payment_mode', strtoupper($order_status));
            }
            //payment status should be "SUCCESS","FAILED","PENDING","REFUND"
            if($operator_id!=''){
                $this->db->where('TransTbl.payment_status', $operator_id);
            }
            $this->db->order_by("TransTbl.trans_date", "desc");
            if ($limit!= '' && $start!= '') {
                $this->db->limit($limit,$start);
            }
            
            
            $query = $this->db->get();        
            $transactions = $query->result();      
            if(!empty($transactions)){
                return $transactions;
            } else {
                return array();
            }
            
        }else if($serviceID == "101") {
             $this->db->select('*');
             $this->db->from('tbl_virtual_trans_dtls as TransTbl');
             if(($role_id=="2")||($role_id=="3")){
                 $user_sql = "";
                 if ($role_id=="3"){
                     $user_sql =  'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                 } else {
                     $user_sql =  'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                 }
                 $userlist=$this->db->query($user_sql)->result();
                 foreach($userlist as $up){
                     $childuser[] = $up->userId;
                 }
                 if(!empty($childuser)){
                     $this->db->where_in('TransTbl.user_id',$childuser);
                 }
             } else {
                 $this->db->where('TransTbl.user_id', $user_id);
             }
             
            //  $sql = 'SELECT * from tbl_virtual_trans_dtls WHERE user_id ='.$user_id.'';
            //  $txns = $this->db->query($sql)->result();
            //  return $txns;
             
             if($from!=''){
                 $this->db->where('date(TransTbl.updated_on)>=',$from);      
             }
             else {
                 $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      
             }
            if($to!=''){
                $this->db->where('date(TransTbl.updated_on) <=', $to);
            }
            //payment type should be "UPI","BANK"
            $this->db->where('TransTbl.payment_type', "BANK");
            $this->db->order_by("TransTbl.updated_on", "desc");
            if ($limit!= '' && $start!= '') {
                $this->db->limit($limit,$start);
            }
            
            
            $query = $this->db->get();        
            $transactions = $query->result();      
            if(!empty($transactions)){
                return $transactions;
            } else {
                return array();
            }
        }else if($serviceID == "102") {
             $this->db->select('*');
             $this->db->from('tbl_virtual_trans_dtls as TransTbl');
             if(($role_id=="2")||($role_id=="3")){
                 $user_sql = "";
                 if ($role_id=="3"){
                     $user_sql =  'SELECT userId FROM `tbl_users` WHERE `fos_id` ='.$user_id.'';
                 } else {
                     $user_sql =  'SELECT userId FROM `tbl_users` WHERE `parent_user_id` ='.$user_id.'';
                 }
                 $userlist=$this->db->query($user_sql)->result();
                 foreach($userlist as $up){
                     $childuser[] = $up->userId;
                 }
                 if(!empty($childuser)){
                     $this->db->where_in('TransTbl.user_id',$childuser);
                 }
             } else {
                 $this->db->where('TransTbl.user_id', $user_id);
             }
             
            //  $sql = 'SELECT * from tbl_virtual_trans_dtls WHERE user_id ='.$user_id.'';
            //  $txns = $this->db->query($sql)->result();
            //  return $txns;
             
             if($from!=''){
                 $this->db->where('date(TransTbl.updated_on)>=',$from);      
             }
             else {
                 $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      
             }
            if($to!=''){
                $this->db->where('date(TransTbl.updated_on) <=', $to);
            }
            //payment type should be "UPI","BANK"
            if($order_status!=''){
                $this->db->where('TransTbl.payment_type', "UPI");
            }
            $this->db->order_by("TransTbl.updated_on", "desc");
            if ($limit!= '' && $start!= '') {
                $this->db->limit($limit,$start);
            }
            
            
            $query = $this->db->get();        
            $transactions = $query->result();      
            if(!empty($transactions)){
                return $transactions;
            } else {
                return array();
            }
        }else{
           $this->db->select('TransTbl.*, Services.service_name,Benificiary.bank_account_number,Benificiary.ifsc');
            $this->db->from('tbl_transaction_dtls as TransTbl');
            $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id');
            //get accountno and ifsc
            $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id');
            //get accountno and ifsc
            $this->db->where('TransTbl.service_id', $serviceID);
            $this->db->where('TransTbl.user_id', $user_id);
            if($from!=''){
                $this->db->where('date(TransTbl.updated_on)>=',$from);      
            }else {
                $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));      

            }
            if($id!=''){
                $this->db->where('TransTbl.id', $id);
                }
            $this->db->where('TransTbl.id_deleted', 0);
            $this->db->order_by("TransTbl.id", "desc"); 
            if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }
        }
        $query = $this->db->get();  
        // print_r($this->db->last_query());         
        $transactions = $query->result();      
        if(!empty($transactions)){
            
            return $transactions;
        } else {
            return array();
        }
    }
    function getAllTransactions_withoutserviceeid($user_id,$reportType,$from="",$to="",$operator_id='',$limit='',$start='',$roleId='')
    {   

            if($reportType == "passbook"){ 
                $this->db->select('userId');
                $this->db->from('tbl_users');
                $this->db->where('roleId',$roleId);
                if ($roleId == '3') {
                    $this->db->where('fos_id', $user_id);

                }else {
                    $this->db->where('parent_user_id', $user_id);

                }
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                // $this->db->select('concat(users.first_name," ",users.last_name) as name,users.parent_user_id as pid,users.roleId as role,TransTbl.payment_mode as description,TransTbl.trans_date,TransTbl.total_amount,TransTbl.transaction_type,TransTbl.order_id,TransTbl.balance,Operators.operator_name,file.file_path as operator_icon');
                // $this->db->select('concat(users.first_name," ",users.last_name) as name,users.parent_user_id as pid,users.roleId as role,TransTbl.payment_mode as description,TransTbl.trans_date,TransTbl.total_amount,TransTbl.transaction_type,TransTbl.order_id,TransTbl.balance,Operators.operator_name');
                $this->db->select('concat(users.first_name," ",users.last_name) as name,users.parent_user_id as pid,users.roleId as role,TransTbl.payment_mode as description,TransTbl.trans_date,TransTbl.total_amount,TransTbl.transaction_type,TransTbl.order_id,TransTbl.balance,TransTbl.operator_id');/*, file.file_path as operator_icon, Operators.operator_name*/
                $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                // $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id','right');
                /*for operator logo*/
                // $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id','right');
                // $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                if($from!=''){
                    $this->db->where('date(TransTbl.updated_on)>=', $from);
                      
                }else{
                    $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));
                }
                if($to!=''){
                 $this->db->where('date(TransTbl.updated_on) <=', $to);
                }
                $this->db->where_in('TransTbl.user_id', $usersArray);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                 if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }

            }
            else if($reportType == "commission"){
                $this->db->select('userId');
                $this->db->from('tbl_users');
                 //$this->db->where('roleId',$roleId);
                if ($role_id == '3') {
                    $this->db->where('fos_id', $user_id);

                }else {
                    $this->db->where('parent_user_id', $user_id);

                }
                $usersList = $this->db->get()->result(); 
                $usersArray = $this->array_default_key($usersList);   
                array_push($usersArray,$user_id);
                if ($roleId == 4) {
                    $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,TransTbl.trans_date,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,TransTbl.order_id,TransTbl.total_amount,TransTbl.transaction_type,file.file_path as operator_icon,TransTbl.payment_mode as description,TransTbl.operator_id as operator_id');
                    $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                    $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                    /*for operator logo*/
                    $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                    $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                    $this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                    $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                    //$this->db->where('TransTbl.service_id', $serviceID);
                    $this->db->where_in('TransTbl.user_id', $usersArray);
                    if($from!=''){
                        $this->db->where('date(TransTbl.updated_on)>=', $from);
                          
                    }else{
                        $this->db->where('date(TransTbl.updated_on)>=', date('Y-m-d'));
                    }
                    if($to!=''){
                     $this->db->where('date(TransTbl.updated_on) <=', $to);
                    }
                    if($operator_id!=''){
                     $this->db->where('TransTbl.operator_id',$operator_id);
                    }
                    $this->db->where('TransTbl.id_deleted', 0);
                    $this->db->where('TransTbl.payment_type', 'COMMISSION');
                    $this->db->order_by("TransTbl.id", "desc");
                     if ($limit!= '' && $start!= '') {
                      $this->db->limit($limit,$start);
                     }
                }else{
                $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,txnTable.order_id,file.file_path as operator_icon,txnTable.operator_id as operator_id,txnTable.updated_on as trans_date,txnTable.user_id,users.store_name,users.mobile as retailer_mobile');
                $this->db->from('tbl_transaction_dtls as txnTable');
                $this->db->join('tbl_operators as Operators','Operators.id = txnTable.operator_id');
                /*for operator logo*/
                $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = txnTable.operator_id');
                $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                //$this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                $this->db->join('tbl_users as users','users.userId = txnTable.user_id');
                //$this->db->where('TransTbl.service_id', $serviceID);
                if($roleId!='1'){
                $this->db->where_in('txnTable.user_id', $usersArray);
                  }
                if($from!=''){
                    $this->db->where('date(txnTable.updated_on)>=', $from);
                      
                }else{
                    $this->db->where('date(txnTable.updated_on)>=', date('Y-m-d'));
                }
                if($to!=''){
                 $this->db->where('date(txnTable.updated_on) <=', $to);
                }
                if($operator_id!=''){
                 $this->db->where('txnTable.operator_id',$operator_id);
                }
                //$this->db->where('txnTable.id_deleted', 0);
                //$this->db->where('txnTable.payment_type', 'COMMISSION');
                $this->db->order_by("txnTable.updated_on", "desc");
                 if ($limit!= '' && $start!= '') {
                  $this->db->limit($limit,$start);
                 }             
                // $this->db->select('concat(users.first_name," ",users.last_name) as name,Operators.operator_name,TransTbl.trans_date,txnTable.mobileno as mobileno,txnTable.total_amount as rechargeAmt,TransTbl.order_id,TransTbl.total_amount,TransTbl.transaction_type,file.file_path as operator_icon,TransTbl.payment_mode as description,TransTbl.operator_id as operator_id,TransTbl.user_id');
                // $this->db->from('tbl_wallet_trans_dtls as TransTbl');
                // $this->db->join('tbl_operators as Operators','Operators.id = TransTbl.operator_id');
                // /*for operator logo*/
                // $this->db->join('tbl_operator_settings as Ope_set','Ope_set.operator_id = TransTbl.operator_id');
                // $this->db->join('tbl_files as file','file.id = Ope_set.operator_logo_file_id','left');/*for operator logo*/
                // $this->db->join('tbl_transaction_dtls as txnTable','txnTable.order_id = TransTbl.order_id');
                // $this->db->join('tbl_users as users','users.userId = TransTbl.user_id');
                // //$this->db->where('TransTbl.service_id', $serviceID);
                // $this->db->where_in('TransTbl.user_id', $usersArray);
                // if($from!=''){
                //     $this->db->where('date(TransTbl.updated_on)>=', $from);
                      
                // }
                // if($to!=''){
                //  $this->db->where('date(TransTbl.updated_on) <=', $to);
                // }
                // if($operator_id!=''){
                //  $this->db->where('TransTbl.operator_id',$operator_id);
                // }
                // $this->db->where('TransTbl.id_deleted', 0);
                // $this->db->where('TransTbl.payment_type', 'COMMISSION');
                // $this->db->order_by("TransTbl.id", "desc");
                //  if ($limit!= '' && $start!= '') {
                //   $this->db->limit($limit,$start);
                //  }
                }
            }

        $query = $this->db->get();        
        $transactions = $query->result();      
        if(!empty($transactions)){
            if($reportType == "passbook"){
                foreach ($transactions as $key => $value) {
                  if ($transactions[$key]->operator_id) {
                       
                       $op_dtls = $this->db->select('Operators.operator_name as operator_name, file.file_path as operator_icon')
                                        ->from('tbl_operator_settings as op_settings')
                                        ->join('tbl_operators as Operators','Operators.id = op_settings.operator_id','right')
                                        ->join('tbl_files as file','file.id = op_settings.operator_logo_file_id','right')
                                        ->where('op_settings.operator_id', $transactions[$key]->operator_id)->get()->row();
                      
                        if ($op_dtls) {
                                $transactions[$key]->operator_name=$op_dtls->operator_name;
                                $transactions[$key]->operator_icon=$op_dtls->operator_icon;

                        }else {
                            $transactions[$key]->operator_name='';
                            $transactions[$key]->operator_icon='';
                        }
                        // print_r($op_dtls);
                        // print_r("==");

                    }else {
                        $transactions[$key]->operator_name='';
                        $transactions[$key]->operator_icon='';
                    }

                }
            }
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
      $this->db->select('TransTbl.order_id as order_no,TransTbl.trans_date as trans_date,TransTbl.imps_name as name,TransTbl.mobileno as sender_no, TransTbl.transaction_type as mode,TransTbl.total_amount as amount,TransTbl.charge_amount as charge_amount,Services.service_name,TransTbl.bank_transaction_id,TransTbl.order_status,TransTbl.remarks,TransTbl.CCFcharges,TransTbl.Cashback,TransTbl.TDSamount,TransTbl.PayableCharge,TransTbl.FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc,TransTbl.transaction_id');
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
        $transactions = $query->row();      
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
    public function getone($table,$field,$id){
        
        $sql=$this->db->query("SELECT * FROM ".$table." where ".$field."='".$id."'");
        return $sql->row();
    }
    public function getall($table,$field,$id){
        $sql=$this->db->query("SELECT * FROM ".$table." where ".$field."='".$id."'");
        return $sql->result();
    }
    public function update($table,$field,$id,$data){
        $this->db->where($field,$id);
       $this->db->update($table, $data);
    }
    public function commisionget_byorderid($orderid){
      $this->db->select('t1.order_id,t1.total_amount,t1.user_id');
      $this->db->select('t2.roleid');
      $this->db->from('tbl_wallet_trans_dtls as t1');
      $this->db->join('tbl_users as t2','t1.user_id=t2.userId');
      $this->db->like('t1.order_id',$orderid);
      $this->db->where('t1.payment_type','COMMISSION');
     
     $query = $this->db->get();
     return $order = $query->result();
    }
    public function getapp_byalis($alias){
      $this->db->select('t1.*');
      $this->db->from('tbl_application_details as t1');
      $this->db->like('t1.alias',$alias);
      $query = $this->db->get();
      return $order = $query->row();
    }
}

  