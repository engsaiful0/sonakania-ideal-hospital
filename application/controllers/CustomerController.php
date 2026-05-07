<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OwnerController
 *
 * @author Lenovo
 */
class CustomerController extends CI_Controller
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

    public function index()
    {
        $page_data = array(
            'page_name' => 'customer/my_report',
            'page_title' => 'My Report',
        );
        $this->load->view('customer/customer_home', $page_data);
    }
    public function view_report($patient_test_entry_id)
    {
        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->load->view('customer/view_report', $data, TRUE);
        $page_data = array(
            'page_name' => 'customer/view_report',
            'page_title' => 'View Report',
        );
        $this->load->view('customer/customer_home', $page_data);
    }
    public function my_report()
    {
        $this->load->view('customer/my_report');
    }
}
