<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class UserRole_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userRoleListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.roleId, BaseTbl.role, BaseTbl.last_modified_date');
        $this->db->from('tbl_roles as BaseTbl');
        if(!empty($searchText)) {
               $likeCriteria = "(BaseTbl.role LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.is_deleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userRoleListing($searchText = '', $page, $segment)
    {
          $this->db->select('BaseTbl.roleId, BaseTbl.role, BaseTbl.last_modified_date');
        $this->db->from('tbl_roles as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.role LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.is_deleted', 0);
        //$this->db->where('BaseTbl.roleId !=', 1);
        $this->db->order_by('BaseTbl.roleId', 'ASC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
        function getUserRole($roleId)
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId', $roleId);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }
  
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUserRole($roleInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_roles', $roleInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userId, first_name, last_name, username, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
		$this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUserRole($roleInfo, $roleId)
    {
        $this->db->where('roleId', $roleId);
        $this->db->update('tbl_roles', $roleInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRole($roleId, $roleInfo)
    {
        $this->db->where('roleId', $roleId);
        $this->db->update('tbl_roles', $roleInfo);
        
        return $this->db->affected_rows();
    }

}

  