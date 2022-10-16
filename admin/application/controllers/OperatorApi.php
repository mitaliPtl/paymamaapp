<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class OperatorApi extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('operatorApi_model');  
        $this->load->model('loginApi_model');             
    }

    /**
     * This function used as a login api
     */
    public function index() {        
        $data =  json_decode(file_get_contents('php://input'), true);
        $operatorInfo = array();
        $result = array('operatorInfo' => $operatorInfo);
        if (!empty($data['token']) && !empty($data['user_id']) && !empty($data['role_id'])) {
            //authenticate user with their details
            if(!$this->loginApi_model->authenticateUser($data['user_id'],$data['role_id'],$data['token'])){
                $response = array(
                    'status' => "false",
                    'msg' => "Authetication failure with invalid token",
                    'result' => $result 
                );
                echo json_encode($response);
                exit;
            }
            //if validation and authetication successfully done 

            $operatorInfo = $this->operatorApi_model->getOperators();

            if ($operatorInfo) {
                $result = array(
                    'operatorInfo' => $operatorInfo,
                );  
                $response = array(
                    'status' => "true",
                    'msg' => "success",
                    'result' => $result
                );              
            } else {
                $response = array(
                    'status' => "false",
                    'msg' => "No operators available",
                    'result' => $result 
                );
            }
        }else{//if proper input not getting from the application           
            $response = array(
                'status' => "false",
                'msg' => "Authetication Failure",
                'result' => $result 
            );
        }
        echo json_encode($response);
        exit;
    }

    public function authenticateUser($data){
        //autheticate user by token details begin
        if (!empty($data['token']) && !empty($data['user_id']) && !empty($data['role_id'])) {
            $user_id= $data['user_id'];
            $role_id= $data['role_id'];

            //authenticate user with their details
            if(!$this->loginApi_model->authenticateUser($data['user_id'],$data['role_id'],$data['token'])){
                $response = array(
                    'status' => "false",
                    'msg' => "Authetication failure with invalid token",
                    'result' => null
                );
                echo json_encode($response);
                exit;
            }
        }else{//if proper input not getting from the application           
        $response = array(
            'status' => "false",
            'msg' => "Authetication Failure",
            'result' => null
        );
        echo json_encode($response);
        exit;
        }
        //autheticate user by token details end
    }
}

?>