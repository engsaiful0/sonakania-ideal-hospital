<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportTestResultController
 *
 * @author saiful
 */
class ReportTestResultController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
        $this->load->library('pagination');
    }

    public function test_result_report()
    {
        $page_data = array(
            'page_name' => 'test_result/report/test_result_report',
            'page_title' => 'Test Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_result_report_details_load($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('test_result/report/test_result_report_details_load', $data);
    }

}
