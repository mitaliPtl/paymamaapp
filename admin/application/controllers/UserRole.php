<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class UserRole extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('userRole_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Admin System : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function userRoleListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->userRole_model->userRoleListingCount($searchText);

            $returns = $this->paginationCompress ( "userRoleListing/", $count, 10 );
            
            $data['userRecords'] = $this->userRole_model->userRoleListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CrazyPay : User Role Listing';
            
            $this->loadViews("user_roles/userRole_list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewRole()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('userRole_model');
            $data['roles'] = $this->userRole_model->getUserRoles();
            
            $this->global['pageTitle'] = 'CrazyPay : Add New User Role';

            $this->loadViews("user_roles/addNewRole", $this->global, $data, NULL);
        }
    }

    function addNewUserRole()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('role','Role','trim|required|max_length[100]');

            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewRole();
            }
            else
            {
                $role = ucwords(strtolower($this->security->xss_clean($this->input->post('role'))));
        
                
                $roleInfo = array('role'=>$role);
                
                $this->load->model('userRole_model');
                $result = $this->userRole_model->addNewUserRole($roleInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Role created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role creation failed');
                }
                
                redirect('userRoleListing');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOldRole($roleId = NULL)
    {

        if($this->isAdmin() == TRUE || $roleId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($roleId == null)
            {
                redirect('userRoleListing');
            }
                    
            
            $data['roles'] = $this->userRole_model->getUserRole($roleId);
            
            $this->global['pageTitle'] = 'CrazyPay : Edit User Role';
            
            $this->loadViews("user_roles/editOldRole", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editUserRole()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $roleId = $this->input->post('roleId');
            
              $this->form_validation->set_rules('role','Role','trim|required|max_length[100]');
            

            if($this->form_validation->run() == FALSE)
            {
                $this->editOldRole($roleId);
            }
            else
            {
                $role = ucwords(strtolower($this->security->xss_clean($this->input->post('role'))));
                
                $userInfo = array();
                
                    $roleInfo = array('role'=>$role);
                
                $result = $this->userRole_model->editUserRole($roleInfo, $roleId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Role updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role updation failed');
                }
                
                redirect('userRoleListing');
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRole()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $roleId = $this->input->post('roleId');
            $roleInfo = array('is_deleted'=>1);
            
            $result = $this->userRole_model->deleteRole($roleId, $roleInfo);   
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

}

?>