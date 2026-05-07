<?php

class GeneralStoreController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    public function general_store_dashboard()
    {
        $page_data = array(
            'page_name' => 'store/store_dashboard',
            'page_title' => 'Store Dashboard',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }

}
