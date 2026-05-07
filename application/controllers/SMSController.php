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
class SMSController extends CI_Controller
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
            'page_name' => 'marketting/marketting_dashboard',
            'page_title' => 'Marketting',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function director_sms()
    {
        $page_data = array(
            'page_name' => 'marketting/director_sms',
            'page_title' => 'Director SMS',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function employee_sms()
    {
        $page_data = array(
            'page_name' => 'marketting/employee_sms',
            'page_title' => 'Employee SMS',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function doctor_sms()
    {
        $page_data = array(
            'page_name' => 'marketting/doctor_sms',
            'page_title' => 'Doctor SMS',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function patient_sms()
    {
        $page_data = array(
            'page_name' => 'marketting/patient_sms',
            'page_title' => 'Patient SMS',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function send_driector_sms_save()
    {

    }
    public function send_doctor_sms_save()
    {

    }
    public function send_employee_sms_save()
    {

    }
    
}
