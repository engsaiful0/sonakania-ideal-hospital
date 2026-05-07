<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportHRMController
 *
 * @author saiful
 */
class ReportHRMController extends CI_Controller
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

    public function hrm_director_report()
    {
        $page_data = array(
            'page_name' => 'hrm/report/hrm_director_report',
            'page_title' => 'HRM Director List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function hrm_employee_report()
    {
        $page_data = array(
            'page_name' => 'hrm/report/hrm_employee_report',
            'page_title' => 'HRM Employee List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function hrm_doctor_list_report()
    {
        $page_data = array(
            'page_name' => 'hrm/report/hrm_doctor_list_report',
            'page_title' => 'HRM Doctor List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function hrm_increment_report()
    {
        $page_data = array(
            'page_name' => 'hrm/report/hrm_increment_report',
            'page_title' => 'HRM Increment List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function hrm_increment_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('hrm/report/hrm_increment_report_details', $data);
    }
    
    public function hrm_attendance_report()
    {
        $page_data = array(
            'page_name' => 'hrm/report/hrm_attendance_report',
            'page_title' => 'Canteen Purchase Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function hrm_attendance_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('hrm/report/hrm_attendance_report_details', $data);
    }
    
   
}
