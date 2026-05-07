<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportOPDController
 *
 * @author saiful
 */
class ReportOPDController extends CI_Controller
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

    
    public function opd_patient_report()
    {
        $page_data = array(
            'page_name' => 'opd_patient/report/opd_patient_report',
            'page_title' => 'OPD Patient Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_patient_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['doctor_id'] = $ids_array[2];
        
        $this->load->view('opd_patient/report/opd_patient_report_details', $data);
    }
    public function ipd_service_report()
    {
        $page_data = array(
            'page_name' => 'ipd_patient/report/ipd_service_report',
            'page_title' => 'IPD Patient Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function ipd_service_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('ipd_patient/report/ipd_service_report_details', $data);
    }
    
    
}
