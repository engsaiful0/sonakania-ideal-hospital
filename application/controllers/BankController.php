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
class BankController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
               redirect('LoginController');
        }
    }

    public function index() {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function bank_deposit_report() {
        $page_data = array(
            'page_name' => 'bank/bank_deposit_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'bank/bank_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_deposit_report_details($ids) {
        $data = explode("_", $ids);
        $data['bank_id'] = $data[2];
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $this->load->view('bank/bank_deposit_report_details', $data);
    }

    public function bank_withdraw_report() {
        $page_data = array(
            'page_name' => 'bank/bank_withdraw_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'bank/bank_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_withdraw_report_details($ids) {
        $data = array();
        $data = explode("_", $ids);
        $data['bank_id'] = $data[2];
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $this->load->view('bank/bank_withdraw_report_details', $data);
    }

}
