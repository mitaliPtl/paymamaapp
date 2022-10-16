<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class VerifyController extends CI_Controller
{
    
  public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('url');
        $this->load->library('session');
        $this->db = $this->load->database('default', true);
        $this->load->model('VerifyModel');
        $this->load->helper('url', 'form'); 
        $this->load->library('form_validation');
    }

  
  public function index()
    {
       
       echo json_encode(array('success' => true));   
        
    }

  public function addMobile(){
        $data = json_decode(file_get_contents('php://input'), true);
        $success = false;
        $message = 'Data Fetch Error';
        $mobile_name = $data['mobile_name'];
        $mobile_number = $data['mobile_no'];
        $parent_user_id = $data['parent_user_id'];
        if($parent_user_id == '')
        {
          $parent_user_id = 4; 
        }
        else
        {
          $parent_user_id = $data['parent_user_id'];  
        }
        $parent_role_id = $data['parent_role_id'];
        $role_id = $data['role_id'];
        $alternate_mob_no=$data['alternate_mob_no'];
        $email=$data['email'];
        $address=$data['address'];
        
        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->addMobile($alternate_mob_no,$email,$address,$mobile_name, $mobile_number,$parent_user_id,$parent_role_id,$role_id)) {
            $success = true;
            $message = 'Mobile Number Added';

        } else {
            $success = false;
            $message = 'Mobile Number Already exists!';
        }
       
        echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));

  }


  public function addPanNumber(){
$data = json_decode(file_get_contents('php://input'), true);
       $success = false;
        $message = 'Data Fetch Error';
        $pan_no =$data['pan_no'];
        $pan_name =$data['pan_name'];
        $id =$data['id'];

        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->addPanData($pan_no, $pan_name,$id)) {
            $success = true;
            $message = 'Pan Number Added';

        } else {
            $success = false;
            $message = 'Error while adding data!';
        }
       
        echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));

  }


  public function phoneCheck(){
    $data = json_decode(file_get_contents('php://input'), true);
       $success = false;
        $message = 'Data Fetch Error';
        $mobile = $data['mobile'];
       
        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->phone_check($mobile)) {
            $success = true;
            $message = 'Mobile Number exists';

        } else {
             $this->db->select('*');
            $this->db->from('tbl_verification'); 
            $this->db->where('mobile_number',$mobile); 
            $query = $this->db->get();
            $num = $query->num_rows();
       
            if ($num > 0) 
            {
            $success = true;
            $message = 'Mobile Number Exists in Verification';
            } 
            else {
               $success = false;
            $message = 'Mobile Number Not Exist'; 
            
            }
           
        
        } 
        echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));
//echo json_encode(array('success' => $success, 'message' => $message));
  }



     



   public function addAadharNumber(){
    $data = json_decode(file_get_contents('php://input'), true);
       $success = false;
        $message = 'Data Fetch Error';
        $aadhar_name= $data['aadhar_name'];
        $aadhar_no = $data['aadhar_no'];
        $aadhar_address = $data['aadhar_address'];
        $id = $data['id'];

        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->addAadharData($aadhar_no, $aadhar_name,$id,$aadhar_address)) {
            $success = true;
            $message = 'Aadhar Details Added';

        } else {
            $success = false;
            $message = 'Error while adding data!';
        }
       
        echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));

  }



   public function addBankInfo(){

        $success = false;
        $message = 'Data Fetch Error';
        $name= $this->input->post('name');
        $ac_number = $this->input->post('ac_number');
        $id = $this->input->post('id');
        $ifsc = $this->input->post('ifsc_code');

        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->addBankInfo($name, $ac_number,$ifsc,$id)) {
            $success = true;
            $message = 'Bank Info Added';

        } else {
            $success = false;
            $message = 'Error while adding data!';
        }
       
        echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));

  }
  
   public function addBankInfoToDb($name,$ac_number,$ifsc,$id){

        $success = false;
        $message = 'Data Fetch Error';

        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->addBankInfo($name, $ac_number,$ifsc,$id)) {
            $success = true;
            $message = 'Bank Info Added';

        } else {
            $success = false;
            $message = 'Error while adding data!';
        }
       
        //echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));

  }
  
 
    
  public function bank_verification() {
      
        $ifsc= $this->input->post('ifsc');
        $number = $this->input->post('number');
        $server_id = $this->input->post('id');
        
        $id = 13;

        $this->load->model('VerifyModel');
        $userId = array('api_id' => 0);
        if ($userId = $this->VerifyModel->getBankVerificationToken($id)) {
            
            $accesstoken = $userId[0]->api_token;
           
            
            $this->verifyBankAccount($accesstoken, $ifsc, $number,$server_id);
            
        } else {
            $success = false;
            $message = 'Data Not Availbale';
        }
       
       // echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));
  }
  
  function verifyBankAccount($accesstoken, $ifsc, $number,$id) {
 
    $URL = 'https://partners.hypto.in/api/verify/bank_account';
    $ref_number = uniqid();
    //$accesstoken="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I";
 
        $post_data = '{
           "ifsc": "' .$ifsc.'",
          "number": "' .$number.'",
          "reference_number": "' .$ref_number.'"
          }';
          
    $crl = curl_init();
 
    $header = array();
    $header[] = 'Content-type: application/json';
    $header[] = 'Authorization: ' . $accesstoken;
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
 
    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
 
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
 
    $rest = curl_exec($crl);
 
        
    if ($rest == false) {
        echo json_encode(array('success' => false, 'message' => 'Curl error: ' . curl_error($crl), 'params' => null));
    } else {
        $response_arr = json_decode($rest);
        $acc_holder_name = $response_arr->data->verify_account_holder;
        echo json_encode(array('success' => 'true', 'message' => $acc_holder_name));
    
        //print_r($id);
        
         $this->addBankInfoToDb($acc_holder_name,$number,$ifsc,$id);
    }
 
    curl_close($crl);
}

function verifyIfsc() {
$data = json_decode(file_get_contents('php://input'), true);
    $URL = 'https://payout-api.cashfree.com/payout/v1/authorize';
    
    $crl = curl_init();
 
    $header = array();
    $header[] = 'Content-type: application/json';
    $header[] = 'X-Client-Id: CF144639C5J9ITTDEC84LG6NOTP0';
    $header[] = 'X-Client-Secret: b8066bec7ae4872baef1ba1f0c1586bb479e49c1';
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
 
    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
 
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
 
    $rest = curl_exec($crl);
   
    if ($rest == false) {
         $re=json_encode(array('success' => false, 'message' => 'Curl error: ' . curl_error($crl), 'params' => null));
    } else {
       
        $response_arr = json_decode($rest);
        $token = $response_arr->data->token;
        
       //Verifying Token
                $URL = 'https://payout-api.cashfree.com/payout/v1/verifyToken';
                
                $crl = curl_init();
             
                $header = array();
                $header[] = 'Content-type: application/json';
                $header[] = 'Authorization: Bearer'.$token;
                curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
             
                curl_setopt($crl, CURLOPT_URL, $URL);
                curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
             
                curl_setopt($crl, CURLOPT_POST, true);
                curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
             
                $rest = curl_exec($crl);
             
                if ($rest == false) {
                     $re=json_encode(array('success' => false, 'message' => 'Curl error: ' . curl_error($crl), 'params' => null));
                } else {
                     $ifsc= $data['ifsc'];
                     $user_id= $data['user_id'];
                    //Verify IFSC
                                $URL = 'https://payout-api.cashfree.com/payout/v1/ifsc/'.$ifsc;
                
                                $crl = curl_init();
                             
                                $header = array();
                                $header[] = 'Content-type: application/json';
                                $header[] = 'Authorization: Bearer '.$token;
                                curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
                             
                                curl_setopt($crl, CURLOPT_URL, $URL);
                                curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
                    
                                curl_setopt($crl, CURLOPT_GET, true);
                                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                             
                                $rest = curl_exec($crl);
                               
                                if ($rest == false) {
                                     $re=json_encode(array('success' => false, 'message' => 'Curl error: ' . curl_error($crl), 'params' => null));
                                } else {
                                     
                                   $response_arr = json_decode($rest);
                                    $branch = $response_arr->data->branch;
                                    $bank_name = $response_arr->data->bank;
                                    
                                    
                                    //Storing Branch Name
                                         $post_data = array(
                                                            'branch_name' => $branch,
                                                            'bank_name' => $bank_name,
                                                            'ifsc_code' => $ifsc
                                                            );
                                        $this->db->where('id', $user_id);
                                        $this->db->update('tbl_verification', $post_data);
                                        $this->db->affected_rows();
                                        
                                        echo json_encode(array('success' => 'true', 'data'=>array('branch_name' => $branch, 'bankname' => $bank_name)));
                                    //Ending Branch Name
                                }
                               
                             
                                curl_close($crl);
                    
                    //Verify IFSC End
                }
               
             
                curl_close($crl);
       
       
       //End Verifying Token
       
       
       
        //print_r($id);
        
         //$this->addBankInfoToDb($acc_holder_name,$number,$id);
    }
   
 
    curl_close($crl);
}


public function extract() {
    $rest = json_array();
    $rest =  $this->input->post('sucess');
    
    $response_arr = json_decode($rest);
    $acc_holder_name = $response_arr->data->verify_account_holder;
    print_r($rest);
}


  public function getAllStpes(){

         $success = false;
        $message = 'Data Fetch Error';
    
            $id = $this->input->post('id');

        $this->load->model('VerifyModel');
        $userId = array('id' => 0);
        if ($userId = $this->VerifyModel->getAllStpes($id)) {
            $success = true;
            $message = 'Data Fetch Successfully';
        } else {
            $success = false;
            $message = 'Data Not Availbale';
        }
       
        echo json_encode(array('success' => $success, 'message' => $message, 'params' => $userId));
    }
    
     public function check_verification()
    {
        if($this->input->post('auto_verified') == '0')
        {
            $user_id=$this->input->post('user_id');
            $this->db->select('*');
            $this->db->from('tbl_verification'); 
            $this->db->where('id',$user_id); 
            $this->db->where('success_score >= 70');
            $query = $this->db->get();
            $pan_name=$query->row()->pan_name;
            $pan_name=explode(' ',$pan_name);
            $bank_account_name=$query->row()->bank_account_name;
           
            $aadhar_name=$query->row()->aadhar_name;
            $aadhar_names=explode(' ',$aadhar_name);
            
            $pattern1 = "/".$pan_name[0]."/i";
            $pattern2 = "/".$pan_name[1]."/i";
            $pattern3="/".$aadhar_names[0]."/i";
            $pattern4="/".$aadhar_names[1]."/i";
            if(preg_match_all($pattern1, $bank_account_name, $matches) or preg_match_all($pattern2, $bank_account_name, $matches) or preg_match_all($pattern1, $aadhar_name, $matches)
             or preg_match_all($pattern2, $aadhar_name, $matches)  or preg_match_all($pattern3, $bank_account_name, $matches)   or preg_match_all($pattern4, $bank_account_name, $matches))
             {
                 
                //Taking Details from Verification Table and storing in user table
                $email=$query->row()->email;
                $password='123456';
                $type=$query->row()->type;
                $fname=$query->row()->telecome_name;
                $mobile=$query->row()->mobile_number;
                $address=$query->row()->business_address;
                $state_id=$query->row()->state_id;
                $district_id=$query->row()->district_id;
                $zip_code=$query->row()->zip_code;
                $roleId=$this->input->post('roleId');
                $store_name=$query->row()->business_name;
                $shop_lat=$query->row()->shop_lat;
                $shop_lan=$query->row()->shop_long;
                $pic_lat=$query->row()->pic_lat;
                $pic_lan=$query->row()->pic_lang;
                $store_category_id=$query->row()->business_category;
                if($roleId == 4)
                {
                    $parent_role_id=2;
                    $parent_user_id=4;
                }
                else if($roleId == 2)
                {
                    $parent_role_id=1;
                    $parent_user_id=1;
                }
                else
                {
                    $parent_role_id='';
                    $parent_user_id='';
                }
                
                
                $get=$this->db->select('*');
                $get=$this->db->from('tbl_users'); 
                $get=$this->db->where('roleId',$roleId);
                $get=$this->db->order_by("userId", "desc"); 
                $getuserid = $get->get();
                $getusername=$getuserid->row()->username;
                
               
               $username = substr($getusername, 5);
               $password=rand(000000,999999);
               $user=$username+1;
               if($roleId == 4)
               {
               $username='RT1000'.$user;
               }
               else
               {
               $username='DT1000'.$user;
               }
              print_r( $userInfo = array('store_category_id'=>$store_category_id,'shop_lat'=>$shop_lat,'shop_lan'=>$shop_lan,'pic_lat'=>$pic_lat,'pic_lan'=>$pic_lan,'store_name'=>$store_name,'username'=>$username,'email'=>$email, 'password'=>$password, 'roleId'=>$roleId, 'first_name'=> $fname, 'last_name'=> '',
                                    'mobile'=>$mobile, 'alternate_mob_no'=>'', 'zip_code'=>$zip_code, 'address'=>$address, 'parent_role_id'=>$parent_role_id, 'parent_user_id'=>$parent_user_id, 
                    'commission_id'=>'', 'package_id'=>1, 'state_id'=>$state_id, 'district_id'=>$district_id, 'createdBy'=>'0', 'createdDtm'=>date('Y-m-d H:i:s')));
                
               // $this->load->model('user_model');
                //$result = $this->user_model->addNewUser($userInfo);
                
                
                if($result > 0)
                {
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
                    echo "success";
                    $this->session->set_flashdata('success', 'New User created successfully');
                }
                else
                {
                    echo "failed";
                    $this->session->set_flashdata('error', 'User creation failed');
                }  
             }
             else
             {
                 echo "verification pending";
             }
        }
        else
        {
            
        }
   
    }
    
    public function business_verification()
    {
        $success=true;
        $failure=false;
        
    $business_name=$this->input->post('business_name');
    $business_address=$this->input->post('business_address');
    $business_category=$this->input->post('business_category');
    $email=$this->input->post('email');
    $shop_lat=$this->input->post('shop_lat');
    $shop_long=$this->input->post('shop_long');
    $state_id=$this->input->post('state_id');
    $district_id=$this->input->post('district_id');
    $zip_code=$this->input->post('zip_code');
    $shop_front_image=$_FILES['shop_front_image']['name'];
    $shop_inside_image=$_FILES['shop_inside_image']['name'];
    
    if (empty($shop_front_image))
    {
    $message = 'Shop Front Image Not Uploaded';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;
    }
    
    if (empty($shop_inside_image))
    {
    $message = 'Shop Inside Image Not Uploaded';
    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;
    }
    
    //Store Image in folder
        $shop_inside_image = time().$_FILES["shop_inside_image"]['name'];
         $config['file_name'] = $shop_inside_image;
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpeg|png|jpg';
        
       
    
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        
     //Upload shop_inside_image to Folder and Save in Database
        if (!$this->upload->do_upload('shop_inside_image')) {
         $error = array('error' => $this->upload->display_errors());
         $message = 'Shop Inside Image Not Uploaded in Folder';
        echo json_encode(array('success' => $failure, 'message' => $error));
        exit;

          //  $this->load->view('files/upload_form', $error);
        } else {
          $data = array('image_metadata' => $this->upload->data());

            //$this->load->view('files/upload_result', $data);
        }
      
      $shop_front_image = time().$_FILES["shop_front_image"]['name'];
      
      $config['file_name'] = $shop_front_image;
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpeg|png|jpg';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
      
         //Upload shop_front_image to Folder and Save in Database
        if (!$this->upload->do_upload('shop_front_image')) {
           $error = array('error' => $this->upload->display_errors());
           $message = 'Shop Front Image Not Uploaded in Folder';
        echo json_encode(array('success' => $failure, 'message' => $error));
        exit; 

          //  $this->load->view('files/upload_form', $error);
        } else {
          $data = array('image_metadata' => $this->upload->data());

            //$this->load->view('files/upload_result', $data);
        }
        
         $post_data = array(
                'name' => $shop_front_image,
                'file_path'=>'/assets/images/'.$shop_front_image
                );
                
        $idcardpath='../../assets/images/'.$id_card;
        $this->db->insert('tbl_files', $post_data);
        $shop_front_imageid=$this->db->insert_id();
        
        
        $post_datas = array(
                'name' => $shop_inside_image,
                'file_path'=>'/assets/images/'.$shop_inside_image
                );
                
        $idcardpath='../../assets/images/'.$id_card;
        $this->db->insert('tbl_files', $post_datas);
        $Shop_inside_imageid=$this->db->insert_id();
        
        
        //Upload Pan Card Ends
    
    //Update in tbl_verification
    
     $post_datas = array(
                'business_name' => $business_name,
                'business_address' => $business_address,
                'business_category' => $business_category,
                'email' => $email,
                'shop_lat' => $shop_lat,
                'shop_long' => $shop_long,
                'shop_front_image' => $shop_front_imageid,
                'shop_inside_image' =>$Shop_inside_imageid,
                'state_id' => $state_id,
                'district_id' => $district_id,
                'zip_code' => $zip_code
                );
               
                
    $user_id=$this->input->post('user_id');
    $this->db->where('id', $user_id);
    $this->db->update('tbl_verification', $post_datas);
   $this->db->affected_rows();
  // print_r($this->db->error());
  if($this->db->affected_rows() <= 0)
    {
        $message = 'File Not Saved in Database';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;
    }
    
    
    
    
    $message = 'Business Verification Successfull';
  
           // echo $message = 'Business Verification Successfull';

    echo json_encode(array('success' => true, 'message' => $message));
    
    }
      

    public function getfacematch()
    {
        
    
    
    $success = true;
    $failure=false;
  
    $user_id=$this->input->post('user_id');
    
    
    $selfie=$_FILES['selfie']['name'];
       
    $id_card=$_FILES['id_card']['name'];
    
   /* $post_data = array(
                'selfie_id' => $selfie_id,
                'pan_id'=>   $pan_id,
                'pic_lat'=>$this->input->post('pic_lat'),
                'pic_lang'=>$this->input->post('pic_lang')
            );
    $user_id=$this->input->post('user_id');
    $this->db->where('id', $user_id);
    $this->db->update('tbl_verification', $post_data);
    $this->db->affected_rows();
    
    exit;
    */
    
    
    if (empty($selfie))
    {
    $message = 'SELFIE Not Uploaded';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;
    }
    if (empty($id_card))
    {
    $message = 'PAN Card Not Uploaded';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;
    }
    
    $tmpselfiefile=$_FILES['selfie']['tmp_name'];
    $tmpid_cardfile=$_FILES['id_card']['tmp_name'];
    
    
    $filespath="assets/images/";
    $URL = 'https://kyc-api.aadhaarkyc.io/api/v1/face/face-match';
    $ref_number = uniqid();
  
    
    //Store Image in folder
    
        $new_name = time().$_FILES["selfie"]['name'];
        $config['file_name'] = $new_name;
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpeg|png|jpg';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
    
        //Upload Selfie to Folder and Save in Database
        
        if (!$this->upload->do_upload('selfie')) {
           $error = array('error' => $this->upload->display_errors());
            
        echo json_encode(array('success' => $failure, 'message' => $error));
        exit;
          //  $this->load->view('files/upload_form', $error);
        } else {
          $data = array('image_metadata' => $this->upload->data());
        
            //$this->load->view('files/upload_result', $data);
        }
        
        $post_data = array(
                'name' => $new_name,
                'file_path'=>'/assets/images/'.$new_name
                );
        $selfiepath='./assets/images/'.$new_name;
        $this->db->insert('tbl_files', $post_data);
        $selfie_id=$this->db->insert_id();
        //Upload Selfie Ends
        
        
        
        
         $new_names = time().$_FILES["id_card"]['name'];
         
        $config['file_name'] = $new_names;
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpeg|png|jpg';
       $this->load->library('upload', $config);
        $this->upload->initialize($config);
        //Upload Pan Card to Folder and Save in Database
        if (!$this->upload->do_upload('id_card')) {
           $error = array('error' => $this->upload->display_errors());
        echo json_encode(array('success' => $failure, 'message' => $error));
        exit;

          //  $this->load->view('files/upload_form', $error);
        } else {
          $data = array('image_metadata' => $this->upload->data());

            //$this->load->view('files/upload_result', $data);
        }
       
        $post_data = array(
                'name' => $new_names,
                'file_path'=>'/assets/images/'.$new_names
                );
                
        $idcardpath='./assets/images/'.$new_names;
        $this->db->insert('tbl_files', $post_data);
        $pan_id=$this->db->insert_id();
        //Upload Pan Card Ends
    
    //Update in tbl_verification
    
     $pic_lat=$this->input->post('pic_lat');
    $pic_lang=$this->input->post('pic_lang');
   /* if(empty($pic_lat))
    {
       $message = 'Pic Lat is Empty';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;  
    }
    if(empty($pic_lang))
    {
        $message = 'Pic Langitude is Empty';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit; 
    }*/
    
    $post_data = array(
                'selfie_id' => $selfie_id,
                'pan_id'=>   $pan_id,
                'pic_lat'=>$this->input->post('pic_lat'),
                'pic_lang'=>$this->input->post('pic_lang')
            );
    $user_id=$this->input->post('user_id');
    $this->db->where('id', $user_id);
    $this->db->update('tbl_verification', $post_data);
    $this->db->affected_rows();
    if($this->db->affected_rows() <= 0)
    {
        $message = 'File Not Saved in Database';

    echo json_encode(array('success' => $failure, 'message' => $message));
    exit;
    }
      $message = 'PAN IMAGE SUCCESSFULLY UPLOADED';

    echo json_encode(array('success' => $success, 'message' => $message));
exit;
    $URL = 'http://paymamaapp.in/api/facematch';
    $this->load->helper('url'); 
    redirect('http://paymamaapp.in/api/facematch/'.$user_id);
                            
    
    }
    public function getfacematchs()
    {
    $success = true;
    $message = 'Verification Successfull';
    
    

    //echo json_encode(array('success' => $success, 'message' => $message));
    //exit;
    $selfie=$_FILES['selfie']['name'];
    $id_card=$_FILES['id_card']['name'];
    
    $tmpselfiefile=$_FILES['selfie']['tmp_name'];
    $tmpid_cardfile=$_FILES['id_card']['tmp_name'];
    
    
    $filespath="assets/images/";
    $URL = 'https://kyc-api.aadhaarkyc.io/api/v1/face/face-match';
    $ref_number = uniqid();
  
    
    //Store Image in folder
        $config['upload_path'] = './assets/images';
        $config['allowed_types'] = 'gif|jpeg|png|jpg';
        
    
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        
        //Upload Selfie to Folder and Save in Database
        
        if (!$this->upload->do_upload('selfie')) {
           $error = array('error' => $this->upload->display_errors());
            

          //  $this->load->view('files/upload_form', $error);
        } else {
          $data = array('image_metadata' => $this->upload->data());

            //$this->load->view('files/upload_result', $data);
        }
        
        $post_data = array(
                'name' => $selfie,
                'file_path'=>'/assets/images/'.$selfie
                );
        $selfiepath='../../assets/images/'.$selfie;
        $this->db->insert('tbl_files', $post_data);
        $selfie_id=$this->db->insert_id();
        //Upload Selfie Ends
        
        //Upload Pan Card to Folder and Save in Database
        if (!$this->upload->do_upload('id_card')) {
           $error = array('error' => $this->upload->display_errors());
            

          //  $this->load->view('files/upload_form', $error);
        } else {
          $data = array('image_metadata' => $this->upload->data());

            //$this->load->view('files/upload_result', $data);
        }
        
        $post_data = array(
                'name' => $id_card,
                'file_path'=>'/assets/images/'.$id_card
                );
                
        $idcardpath='../../assets/images/'.$id_card;
        $this->db->insert('tbl_files', $post_data);
        $pan_id=$this->db->insert_id();
        //Upload Pan Card Ends
    
    //Update in tbl_verification
   
    $post_data = array(
                'selfie_id' => $selfie_id,
                'pan_id'=>   $pan_id,
                'pic_lat'=>$this->input->post('pic_lat'),
                'pic_lang'=>$this->input->post('pic_lang')
            );
    $user_id=$this->input->post('user_id');
    $this->db->where('id', $user_id);
    $this->db->update('tbl_verification', $post_data);
    $this->db->affected_rows();
    
    //Update in tbl_verification Ends
    $selfie_upload="@$file;type=image/jpeg";
    $accesstoken="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjM3NDU2NzAsIm5iZiI6MTYyMzc0NTY3MCwianRpIjoiNzIxMWU2MmMtZTIxMy00MDQ2LTgzZTUtZjI0MWFiNGI3MjczIiwiZXhwIjoxOTM5MTA1NjcwLCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.kT625qNXK9ie6jYCCwh8Ox2hmleuXn2WOOcaxxHYQQo";
        /* $post_data = "{
           'selfie':file_get_contents($selfie),
          
          'id_card':file_get_contents($id_card)
          }";
    print_r($post_data); */
   
    $crl = curl_init();
   $post_data="{
       'user_id':$user_id
   }";
   
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
    
    curl_setopt($crl, CURLOPT_URL, 'http://paymamaapp.in/api/facematch');
    //curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
 
    curl_setopt($crl, CURLOPT_GET, true);
    curl_setopt($crl, CURLOPT_GETFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
 
    $rest = curl_exec($crl);
    echo curl_error($crl);
     if ($rest == false) {
        echo json_encode(array('success' => false, 'message' => 'Curl error: ' . curl_error($crl), 'params' => null));
    } else {
        echo $response_arr = json_decode($rest);
        //$acc_holder_name = $response_arr->data->verify_account_holder;
        
        //print_r($id);
        
        // $this->addBankInfoToDb($acc_holder_name,$number,$id);
    }
 
   
    
    //$curl = curl_init();
/*$head = 'Authorization: ' . $accesstoken;
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://kyc-api.aadhaarkyc.io/api/v1/face/face-match",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => array('selfie'=> new CURLFILE('/pan1.jpeg'),'id_card'=> new CURLFILE('/pan1.jpeg')),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjM3NDU2NzAsIm5iZiI6MTYyMzc0NTY3MCwianRpIjoiNzIxMWU2MmMtZTIxMy00MDQ2LTgzZTUtZjI0MWFiNGI3MjczIiwiZXhwIjoxOTM5MTA1NjcwLCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.kT625qNXK9ie6jYCCwh8Ox2hmleuXn2WOOcaxxHYQQo"
  ),
  ));*/
$response = curl_exec($curl);
echo $response;
curl_close($curl);

 exit;
    if ($rest == false) {
        echo json_encode(array('success' => false, 'message' => 'Curl error: ' . curl_error($crl), 'params' => null));
    } else {
        echo $response_arr = json_decode($rest);
        //$acc_holder_name = $response_arr->data->verify_account_holder;
        
        //print_r($id);
        
        // $this->addBankInfoToDb($acc_holder_name,$number,$id);
    }
 
    curl_close($crl);
    }

   
}
 ?>