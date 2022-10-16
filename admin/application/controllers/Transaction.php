<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Transaction extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaction_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CrazyPay Admin : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function rechargeReport()
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
            
            $count = $this->transaction_model->transactionListingCount($searchText);

            $returns = $this->paginationCompress ( "rechargeReport/", $count, 10 );
            
            $data['transactionRecords'] = $this->transaction_model->transactionListing($searchText, $returns["page"], $returns["segment"]);
            
//            print_r($data);
//            exit;
            
            $this->global['pageTitle'] = 'CrazyPay : User Listing';
            
            $this->loadViews("transactions/recharge_report", $this->global, $data, NULL);
        }
    }

 
}

?>