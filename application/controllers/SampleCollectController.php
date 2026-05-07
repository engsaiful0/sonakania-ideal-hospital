<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BankController
 *
 * @author saiful
 */
class SampleCollectController extends CI_Controller
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
        $this->load->database();
        $this->load->helper('url');


        $this->load->library('pagination');
    }


    public function sample_collect($patient_test_entry_id = null)
    {
        if ($patient_test_entry_id == null) {
            redirect('lab-dashboard');
        }
        $page_data = array(
            'page_name' => 'sample_collect/view_sample_collect',
            'page_title' => 'Sample Collection',
            'sidebar' => 'test/test_sidebar',
            'patient_test_entry_id' => $patient_test_entry_id
        );
        $this->load->view('content', $page_data);
    }
}
