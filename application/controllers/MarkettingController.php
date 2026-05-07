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
class MarkettingController extends CI_Controller
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
    function sms_send($data)
    {
        $doctor = getDoctorById($data['doctor_id']);
        $sms_api = getSMSAPI();
        // echo '<pre>';
        // print_r($doctor->doctor_name );
        // print_r($sms_api->api_key );
        // print_r($data['opd_patient_name']);

        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = $sms_api->api_key;
        $senderid = $sms_api->senderid;
        // $number = "88016xxxxxxxx,88019xxxxxxxx";
        $number = "88" . $data['mobile_number'];
        $message = "Dear patient, you have booked the following successfully: Patient Name: " . $data['opd_patient_name'] . "Serial: " . $data['serial_numaber'] . 'Visiting Date and Time: ' . $data['visiting_date'] . $data['visiting_time'] . 'Doctor Name: ' . $doctor->doctor_name . 'Mirzakhil General Hospital Ltd';

        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message
        ];

        $send_sms = array(
            'mobile_number' => $number,
            'message' => $message,
            'type' => 'OPD Patient',
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->insert('send_sms', $send_sms);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public function send_patient_sms_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $patient_type = $this->input->post('patient_type');
            if ($patient_type == 'opd') {
            } else if ($patient_type == 'ipd') {
            } else if ($patient_type == 'all') {
            }



            $this->db->insert('ot_services', $data);
            $ot_service_id = $this->db->insert_id();

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OT Service added and OT Service Id is=' . $ot_service_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ipd_service_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    
    public function send_employee_sms_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $employee_id = $this->input->post('employee_id');
            if ($employee_id == 'all') {
                $getAllDirectors = getAllDirectors();
            } else {
            }



            $this->db->insert('ot_services', $data);
            $ot_service_id = $this->db->insert_id();

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OT Service added and OT Service Id is=' . $ot_service_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ipd_service_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function send_driector_sms_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $director_id = $this->input->post('director_id');
            if ($director_id == 'all') {
                $getAllDirectors = getAllDirectors();
            } else {
            }



            $this->db->insert('ot_services', $data);
            $ot_service_id = $this->db->insert_id();

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OT Service added and OT Service Id is=' . $ot_service_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ipd_service_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
}
