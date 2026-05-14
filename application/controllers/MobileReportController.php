<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportController
 *
 * @author saiful
 */
class MobileReportController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('Grocery_crud');
       
        $this->load->library('pagination');
    }
   
    public function mobile_report()
    {
        $page_data = array(
            'page_name' => 'mobile/mobile_report',
            'page_title' => 'Mobile Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    
}
