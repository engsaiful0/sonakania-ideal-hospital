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

use Laminas\Barcode\Barcode;

class DischargeController extends CI_Controller
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
        $this->load->library('pagination');
        $this->load->library('zend');
    }

    public function undischarged_patient_unique_id_load_for_edit()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('*') // Specify the columns to select, or '*' for all columns
                ->from('ipd_patient') // Define the table
                ->like('patient_unique_id', $parameter) // Add the LIKE condition
                ->where('status', 'Discharged'); // Add the WHERE condition
        } else {
            // Query without condition
            $this->db->select('*') // Specify columns to select, or use '*' for all columns
                ->from('ipd_patient') // Define the table
                ->where('status', 'Discharged'); // Add the condition
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        echo json_encode($data_patient);
    }
    public function undischarged_patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('*') // Specify the columns to select, or '*' for all columns
                ->from('ipd_patient') // Define the table
                ->like('patient_unique_id', $parameter) // Add the LIKE condition
                ->where('status !=', 'Discharged'); // Add the WHERE condition
        } else {
            // Query without condition
            $this->db->select('*') // Specify columns to select, or use '*' for all columns
                ->from('ipd_patient') // Define the table
                ->where('status !=', 'Discharged'); // Add the condition
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        echo json_encode($data_patient);
    }
    public function discharged_patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('*') // Specify the columns to select, or '*' for all columns
                ->from('ipd_patient') // Define the table
                ->like('patient_unique_id', $parameter) // Add the LIKE condition
                ->where('status', 'Discharged'); // Add the WHERE condition
        } else {
            // Query without condition
            $this->db->select('*') // Specify columns to select, or use '*' for all columns
                ->from('ipd_patient') // Define the table
                ->where('status !=', 'Discharged'); // Add the condition
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        echo json_encode($data_patient);
    }
    public function set_barcode($code)
    {
        // Configure barcode options
        $barcodeOptions = ['text' => $code];
        $rendererOptions = [];
        // Generate the barcode
        $barcode = Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions);
        // Send the image to the browser
        $imageResource = $barcode->draw();
        header('Content-Type: image/png');
        imagepng($imageResource);
        imagedestroy($imageResource);
    }
    public function view_discharge()
    {
        $discharge_bill_id = $this->input->post('discharge_bill_id') ?? '';
        $patient_unique_id = $this->input->post('patient_unique_id') ?? '';
        $discharge_reason_id = $this->input->post('discharge_reason_id') ?? '';
        $discharge_date = $this->input->post('discharge_date') ?? '';
        $admission_date = $this->input->post('admission_date') ?? '';

        if ($this->input->post('admission_date') != '') {
            $admission_date = date('Y-m-d', strtotime($this->input->post('admission_date')));
        }
        if ($this->input->post('discharge_date') != '') {
            $discharge_date = date('Y-m-d', strtotime($this->input->post('discharge_date')));
        }
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Pagination configuration
        $config['base_url'] = base_url() . "index.php/DischargeController/view_discharge";
        // var_dump($discharge_bill_id, $patient_unique_id, $discharge_reason_id, $discharge_date, $admission_date);
        $config['total_rows'] = $this->DischargeModel->count_all_discharged_patients($discharge_bill_id, $patient_unique_id, $discharge_reason_id, $discharge_date, $admission_date);
        $config['per_page'] = 100;
        $config["uri_segment"] = 3;
        $choice = $config['total_rows'] / $config['per_page'];

        $config['num_links'] = 2; // Number of page links to display on either side of the current page

        // Integrate bootstrap pagination
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i> Previous';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['first_link'] = 'First'; // Optional: Add a "First" link
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['last_link'] = 'Last'; // Optional: Add a "Last" link

        $this->pagination->initialize($config);

        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // get books list
        $data['detailsList'] = $this->DischargeModel->discharged_patient_details($this->per_page, $page, $discharge_bill_id, $patient_unique_id, $discharge_reason_id, $discharge_date, $admission_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('discharge/view_discharge', $data, true);

        $page_data = array(
            'page_name' => 'discharge/view_discharge',
            'page_title' => 'Discharge',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function add_discharge()
    {
        $page_data = array(
            'page_name' => 'discharge/add_discharge',
            'page_title' => 'Add Discharge',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function full_pay_bill($discharge_id)
    {
        $data['discharge_id'] = $discharge_id;
        $this->load->view('discharge/full_pay_bill', $data, TRUE);
        $page_data = array(
            'page_name' => 'discharge/full_pay_bill',
            'page_title' => 'IPD Full Pay',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function save_discharge_bill_data()
    {
        $discharge_id = $this->input->post('discharge_id');
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['ipd_patient_id'] = $this->input->post('ipd_patient_id');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['discharge_bill_id'] = $this->input->post('discharge_bill_id');
            $data['discharge_reason_id'] = $this->input->post('discharge_reason_id');
            $data['admission_time'] = $this->input->post('admission_time');
            $data['admission_date'] = date('Y-m-d', strtotime($this->input->post('admission_date')));
            $data['discharge_date'] = date('Y-m-d', strtotime($this->input->post('discharge_date')));
            $data['discharge_time'] = $this->input->post('discharge_time_hour') . ':' . $this->input->post('discharge_time_minute') . ':' . $this->input->post('discharge_time_second') . " " . $this->input->post('discharge_time_meridian');

            $data['discharge_time_hour'] = $this->input->post('discharge_time_hour');
            $data['discharge_time_minute'] = $this->input->post('discharge_time_minute');
            $data['discharge_time_second'] = $this->input->post('discharge_time_second');
            $data['discharge_time_meridian'] = $this->input->post('discharge_time_meridian');
            $data['total_duration_day'] = $this->input->post('total_duration_day');

            $data['total_duration_hours'] = $this->input->post('total_duration_hours');
            $data['discount_reference'] = $this->input->post('discount_reference');

            $data['discount_reference_director_id'] = $this->input->post('discount_reference_director_id');
            $data['discount_reference_doctor_id'] = $this->input->post('discount_reference_doctor_id');
            $data['discount_reference_employee_id'] = $this->input->post('discount_reference_employee_id');
            $data['discount_reference_media_id'] = $this->input->post('discount_reference_media_id');


            $data['cabin_or_bed_bill'] = $this->input->post('cabin_or_bed_bill');

            $data['pharmachy_bill'] = $this->input->post('pharmachy_bill');
            $data['test_bill'] = $this->input->post('test_bill');
            $data['ot_service_bill'] = $this->input->post('ot_service_bill');
            $data['phygiotherapy_bill'] = $this->input->post('phygiotherapy_bill');

            $data['emergency_bill'] = $this->input->post('emergency_bill');
            $data['admission_and_other_bill'] = $this->input->post('admission_and_other_bill');
            $data['extra_hours_bill'] = $this->input->post('extra_hours_bill');
            $data['total_bill'] = $this->input->post('total_bill');
            $data['payable'] = $this->input->post('payable');
            $data['previous_paid'] = $this->input->post('previous_paid');
            $data['director_discount'] = $this->input->post('director_discount');
            $data['net_payable'] = $this->input->post('net_payable');
            $data['reference_director_id'] = $this->input->post('reference_director_id');
            $data['special_discount'] = $this->input->post('special_discount');
            $data['paid'] = $this->input->post('paid');
            $data['due'] = $this->input->post('due');
            $data['payment_method_id'] = $this->input->post('payment_method_id');
            $data['bank_account_id'] = $this->input->post('bank_account_id');
            $data['mobile_bank_id'] = $this->input->post('mobile_bank_id');
            $data['check_details'] = $this->input->post('check_details');
            $data['remarks'] = $this->input->post('remarks');
            $data['user_id'] = $this->session->userdata('user_id'); //bill collector
            $this->db->where('discharge_id', $discharge_id)->update('discharge', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD patient discharge updated and IPD discharge Id is=' . $discharge_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_discharge_id'] = $discharge_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }


    public function edit_discharge($discharge_id)
    {
        // die;
        $data['discharge_id'] = $discharge_id;
        $this->load->view('discharge/edit_discharge', $data, TRUE);
        $page_data = array(
            'page_name' => 'discharge/edit_discharge',
            'page_title' => 'Edit Discharge Bill',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function update_discharge_data()
    {
        $discharge_id = $this->input->post('discharge_id');
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $ipd_patient = $this->db->where('ipd_patient_id', $this->input->post('ipd_patient_id'))->get('ipd_patient')->row();
            $patient_update = array('status' => 'Discharged');
            $this->db->where('ipd_patient_id', $ipd_patient->ipd_patient_id)
                ->update('ipd_patient', $patient_update);

            //Make Bed or Cabin Available
            makeBedAvailable($ipd_patient->bed_id);
            makeCabinAvailable($ipd_patient->cabin_id);

            $data = array();
            $data['ipd_patient_id'] = $this->input->post('ipd_patient_id');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['discharge_bill_id'] = $this->input->post('discharge_bill_id');

            $data['discharge_reason_id'] = $this->input->post('discharge_reason_id');
            $data['admission_time'] = $this->input->post('admission_time');
            $data['admission_date'] = date('Y-m-d', strtotime($this->input->post('admission_date')));
            $data['discharge_date'] = date('Y-m-d', strtotime($this->input->post('discharge_date')));
            $data['discharge_time'] = $this->input->post('discharge_time_hour') . ':' . $this->input->post('discharge_time_minute') . ':' . $this->input->post('discharge_time_second') . " " . $this->input->post('discharge_time_meridian');

            $data['discharge_time_hour'] = $this->input->post('discharge_time_hour');
            $data['discharge_time_minute'] = $this->input->post('discharge_time_minute');
            $data['discharge_time_second'] = $this->input->post('discharge_time_second');
            $data['discharge_time_meridian'] = $this->input->post('discharge_time_meridian');

            $data['total_duration_day'] = $this->input->post('total_duration_day');
            $data['cabin_or_bed_bill'] = $this->input->post('cabin_or_bed_bill');
            $data['total_duration_hours'] = $this->input->post('total_duration_hours');
            $data['discount_reference'] = $this->input->post('discount_reference');

            $data['discount_reference_director_id'] = $this->input->post('discount_reference_director_id');
            $data['discount_reference_doctor_id'] = $this->input->post('discount_reference_doctor_id');
            $data['discount_reference_employee_id'] = $this->input->post('discount_reference_employee_id');
            $data['discount_reference_media_id'] = $this->input->post('discount_reference_media_id');

            $data['pharmachy_bill'] = $this->input->post('pharmachy_bill');
            $data['test_bill'] = $this->input->post('test_bill');
            $data['ot_service_bill'] = $this->input->post('ot_service_bill');
            $data['phygiotherapy_bill'] = $this->input->post('phygiotherapy_bill');

            $data['emergency_bill'] = $this->input->post('emergency_bill');
            $data['admission_and_other_bill'] = $this->input->post('admission_and_other_bill');
            $data['consultant_fee'] = $this->input->post('consultant_fee');
            $data['assistatnt_fee'] = $this->input->post('assistatnt_fee');
            $data['service_charge'] = $this->input->post('service_charge');
            $data['admission_reg_fee'] = $this->input->post('admission_reg_fee');
            $data['bed_or_cabin_charge'] = $this->input->post('bed_or_cabin_charge');

            $data['extra_hours_bill'] = $this->input->post('extra_hours_bill');
            $data['total_bill'] = $this->input->post('total_bill');
            $data['payable'] = $this->input->post('payable');
            $data['previous_paid'] = $this->input->post('previous_paid');
            $data['director_discount'] = $this->input->post('director_discount');
            $data['net_payable'] = $this->input->post('net_payable');
            $data['reference_director_id'] = $this->input->post('reference_director_id');
            $data['special_discount'] = $this->input->post('special_discount');


            $data['paid'] = $this->input->post('paid');
            $data['due'] = $this->input->post('due');
            $data['payment_method_id'] = $this->input->post('payment_method_id');
            $data['bank_account_id'] = $this->input->post('bank_account_id');
            $data['mobile_bank_id'] = $this->input->post('mobile_bank_id');
            $data['check_details'] = $this->input->post('check_details');
            $data['remarks'] = $this->input->post('remarks');
            $this->db->where('discharge_id', $discharge_id)->update('discharge', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD patient discharge updated and IPD discharge Id is=' . $discharge_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_discharge_id'] = $discharge_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function sms_send($ipd_patient)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $sms_api = getSMSAPI();
        // echo '<pre>';
        // print_r($doctor->doctor_name );
        // print_r($sms_api->api_key );
        // print_r($data['opd_patient_name']);

        if ($sms_api->is_discharge_sms_send == 'yes') { // if is_phygiotherapy_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $ipd_patient->mobile_number;
            $message = "Dear Patient, you have discharged successfully. Patient Name: " . $ipd_patient->patient_name . ", Gender: " . $ipd_patient->gender . ', Age: ' . $ipd_patient->age . $company_name;
            $data = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];

            // Send SMS first
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            // Parse the response to check if SMS was sent successfully
            $response_code = null;
            if (!empty($response)) {
                // Decode JSON response if it's JSON format
                $response_data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($response_data['response_code'])) {
                    $response_code = $response_data['response_code'];
                } elseif (is_numeric($response)) {
                    // If response is just a number (response code)
                    $response_code = (int)$response;
                }
            }

            // Only save to database if SMS was sent successfully (code 202)
            if ($response_code == 202) {
                $send_sms = array(
                    'mobile_number' => $number,
                    'message' => $message,
                    'type' => 'Discharge',
                    'date' => date('Y-m-d'), // Today date
                    'user_id' => $this->session->userdata('user_id'),
                    'response_code' => $response_code,
                    'status' => 'Sent'
                );
                $this->db->insert('send_sms', $send_sms);
            }

            return $response;
        }
    }
    public function save_discharge_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['discharge_bill_id'] = $this->input->post('discharge_bill_id');
            $data['id_serial'] = $this->input->post('id_serial');
            $this->db->insert('discharge_bill_id_table', $data);
            //Update Status
            $ipd_patient = $this->db->where('ipd_patient_id', $this->input->post('ipd_patient_id'))->get('ipd_patient')->row();
            $patient_update = array('status' => 'Discharged');

            $this->db->where('ipd_patient_id', $ipd_patient->ipd_patient_id)
                ->update('ipd_patient', $patient_update);

            //Make Bed or Cabin Available
            makeBedAvailable($ipd_patient->bed_id);
            makeCabinAvailable($ipd_patient->cabin_id);


            $data = array();
            $data['ipd_patient_id'] = $this->input->post('ipd_patient_id');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['discharge_bill_id'] = $this->input->post('discharge_bill_id');

            $data['discharge_reason_id'] = $this->input->post('discharge_reason_id');
            $data['admission_time'] = $this->input->post('admission_time');
            $data['admission_date'] = date('Y-m-d', strtotime($this->input->post('admission_date')));
            $data['discharge_date'] = date('Y-m-d', strtotime($this->input->post('discharge_date')));
            $data['discharge_time'] = $this->input->post('discharge_time_hour') . ':' . $this->input->post('discharge_time_minute') . ':' . $this->input->post('discharge_time_second') . " " . $this->input->post('discharge_time_meridian');

            $data['discharge_time_hour'] = $this->input->post('discharge_time_hour');
            $data['discharge_time_minute'] = $this->input->post('discharge_time_minute');
            $data['discharge_time_second'] = $this->input->post('discharge_time_second');
            $data['discharge_time_meridian'] = $this->input->post('discharge_time_meridian');

            $data['total_duration_day'] = $this->input->post('total_duration_day');
            $data['cabin_or_bed_bill'] = $this->input->post('cabin_or_bed_bill');
            $data['total_duration_hours'] = $this->input->post('total_duration_hours');
            $data['pharmachy_bill'] = $this->input->post('pharmachy_bill');
            $data['test_bill'] = $this->input->post('test_bill');
            $data['ot_service_bill'] = $this->input->post('ot_service_bill');
            $data['phygiotherapy_bill'] = $this->input->post('phygiotherapy_bill');

            $data['emergency_bill'] = $this->input->post('emergency_bill');
            $data['admission_and_other_bill'] = $this->input->post('admission_and_other_bill');
            $data['consultant_fee'] = $this->input->post('consultant_fee');
            $data['assistatnt_fee'] = $this->input->post('assistatnt_fee');
            $data['service_charge'] = $this->input->post('service_charge');
            $data['admission_reg_fee'] = $this->input->post('admission_reg_fee');
            $data['bed_or_cabin_charge'] = $this->input->post('bed_or_cabin_charge');

            $data['extra_hours_bill'] = $this->input->post('extra_hours_bill');
            $data['total_bill'] = $this->input->post('total_bill');
            $data['payable'] = $this->input->post('payable');
            $data['previous_paid'] = $this->input->post('previous_paid');
            $data['director_discount'] = $this->input->post('director_discount');
            $data['net_payable'] = $this->input->post('net_payable');
            $data['reference_director_id'] = $this->input->post('reference_director_id');
            $data['special_discount'] = $this->input->post('special_discount');

            $data['discount_reference_director_id'] = $this->input->post('discount_reference_director_id');
            $data['discount_reference_doctor_id'] = $this->input->post('discount_reference_doctor_id');
            $data['discount_reference_employee_id'] = $this->input->post('discount_reference_employee_id');
            $data['discount_reference_media_id'] = $this->input->post('discount_reference_media_id');


            $data['paid'] = $this->input->post('paid');
            $data['due'] = $this->input->post('due');
            $data['payment_method_id'] = $this->input->post('payment_method_id');
            $data['bank_account_id'] = $this->input->post('bank_account_id');
            $data['mobile_bank_id'] = $this->input->post('mobile_bank_id');
            $data['check_details'] = $this->input->post('check_details');
            $data['remarks'] = $this->input->post('remarks');
            $data['bill_maker_id'] = $this->session->userdata('user_id');
            $data['user_id'] = $this->session->userdata('user_id'); //bill collector
            $save_result = $this->db->insert('discharge', $data);
            $discharge_id = $this->db->insert_id();
            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($ipd_patient); //To send sms
            }


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD patient Discharge data added and IPD Discharge Id is=' . $discharge_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_discharge_id'] = $discharge_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function print_discharge_bill()
    {

        $page_data = array(
            'page_name' => 'discharge/print_discharge_bill',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_discharge_bill_again($discharge_id)
    {
        // die;
        $data['discharge_id'] = $discharge_id;
        $sdata['print_discharge_id'] = $discharge_id;
        $this->session->set_userdata($sdata);
        $this->load->view('discharge/print_discharge_bill', $data, TRUE);
        $page_data = array(
            'page_name' => 'discharge/print_discharge_bill',
            'page_title' => 'Print Discharge Bill',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function patient_discharge_data_load_by_unique_id()
    {
        $patient_unique_id = $_POST['patient_unique_id'];
        $discharge_date = date('Y-m-d', strtotime($_POST['discharge_date']));
        $discharge_time = $_POST['discharge_time'];

        $patient = $this->db->where('patient_unique_id', $patient_unique_id)->get('ipd_patient')->row();

        $doctor = $this->db->where('doctor_id', $patient->reference_doctor_id)->get('doctor')->row();
        $reference_media = $this->db->where('reference_media_id', $patient->reference_media_id)->get('reference_media')->row();
        $reference_director = $this->db->where('director_id', $patient->reference_director_id)->get('director')->row();

        $ward = $this->db->where('ward_id', $patient->ward_id)->get('ward')->row();
        $bed = $this->db->where('bed_id', $patient->bed_id)->get('bed')->row();
        $cabin = $this->db->where('cabin_id', $patient->cabin_id)->get('cabin')->row();
        $ipd_patient_id = $patient->ipd_patient_id;
        // Combine date and time into a single string
        $admissionDateTimeStr = $patient->date . ' ' . $patient->admission_time;
        $bed_or_cabin_charge = '';
        $rent = 0;
        if ($cabin != '') {
            $rent = $cabin->cabin_rent;
            $bed_or_cabin_charge = 'Cabin Charge';
        } else if ($bed != '') {
            $rent = $bed->bed_rent;
            $bed_or_cabin_charge = 'Bed Charge';
        }
        $company = $this->db->where('company_id', '1')->get('company')->row();

        $tz = new DateTimeZone('Asia/Dhaka');
        $admissionDateTimeStr = trim($patient->date . ' ' . $patient->admission_time);
        //print_r($admissionDateTimeStr);
        $admissionDateTime = DateTime::createFromFormat('Y-m-d h:i:s A', $admissionDateTimeStr, $tz);
        // die($admissionDateTime);

        // if (!$admissionDateTime) {
        //     echo "Invalid datetime string: " . htmlspecialchars($admissionDateTimeStr) . "<br>";
        //     print_r(DateTime::getLastErrors());
        //     exit;
        // }
        $dischargeDateTimeStr = trim($discharge_date . ' ' . $discharge_time);
        $dischargeDateTime = DateTime::createFromFormat('Y-m-d h:i:s A', $dischargeDateTimeStr, $tz);
        //$dischargeDateTime = new DateTime('now', $tz);
        $interval = $admissionDateTime->diff($dischargeDateTime);


        $duration = '';
        if ($interval->y) $duration .= $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ', ';
        if ($interval->m) $duration .= $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ', ';
        if ($interval->d) $duration .= $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ', ';
        if ($interval->h) $duration .= $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ', ';
        if ($interval->i) $duration .= $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ', ';
        if ($interval->s) $duration .= $interval->s . ' second' . ($interval->s > 1 ? 's' : '') . ', ';

        $duration = rtrim($duration, ', ');

        // Time calculations
        $total_hours = ($interval->days * 24) + $interval->h;
        $remaining_hours = $total_hours % 24;
        $total_days = floor($total_hours / 24);


        // Calculate bill
        $total_bill_from_cabin = 0;
        $consultant_fee = 0;
        $assistant_fee = 0;
        $service_charge = 0;
        $extra_hours_bill = 0;
        $admission_reg_fee = $company->admission_reg_fee;

        // Handle full days
        if ($total_days > 0) {
            $total_bill_from_cabin += $total_days * $rent;
            $consultant_fee += $total_days * $company->consultant_fee;
            $assistant_fee += $total_days * $company->assistatnt_fee;

            // Handle remaining hours after full days
            if ($remaining_hours > 6) {
                // Treat as another full day
                $total_bill_from_cabin += $rent;
                $consultant_fee += $company->consultant_fee;
                $assistant_fee += $company->assistatnt_fee;
            } elseif ($remaining_hours <= 6) {
                // Optional flat extra charge (e.g., less than 6 hours)
                $extra_hours_bill = 500;
            }
        }

        // Handle 0 duration (i.e., less than 1 full day)
        if ($total_days == 0 && $remaining_hours >= 0) {
            $total_bill_from_cabin = $rent;
            $consultant_fee = $company->consultant_fee;
            $assistant_fee = $company->assistatnt_fee;
        }




        //Due calculation in from test
        $test_net_total = $this->db
            ->select_sum('net_total', 'net_total')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('patient_test_entry')->result();
        $test_net_total = $test_net_total[0]->net_total;
        $test_paid = $this->db
            ->select_sum('paid', 'paid')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('patient_test_entry')->result();
        $test_paid = $test_paid[0]->paid;
        $total_due_in_test = $test_net_total - $test_paid;

        //Due calculation in from pharmachy
        $medicine_net_total = $this->db
            ->select_sum('nettotal', 'nettotal')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('medicine_sales')->result();
        $medicine_net_total = $medicine_net_total[0]->nettotal;
        $medicine_paid = $this->db
            ->select_sum('paid', 'paid')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('medicine_sales')->result();
        $medicine_paid = $medicine_paid[0]->paid;
        $total_due_in_medicine = $medicine_net_total - $medicine_paid;

        //Due calculation from phygiotherapy
        $phygiotherapy_net_total = $this->db
            ->select_sum('nettotal', 'nettotal')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('phygiotherapy')->result();
        $phygiotherapy_net_total = $phygiotherapy_net_total[0]->nettotal;
        $phygiotherapy_paid = $this->db
            ->select_sum('paid', 'paid')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('phygiotherapy')->result();
        $phygiotherapy_paid = $phygiotherapy_paid[0]->paid;
        $total_due_in_phygiotherapy = $phygiotherapy_net_total - $phygiotherapy_paid;


        //OT service due
        $ot_service_totals = $this->db
            ->select('SUM(net_price) as total_net_price, SUM(paid) as total_paid')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('ot_services')
            ->row();
        $total_net_price = $ot_service_totals->total_net_price ?? 0;
        $total_paid = $ot_service_totals->total_paid ?? 0;
        $total_ot_service_due = $total_net_price - $total_paid;


        //Due calculation from IPD Services
        $ipd_service_net_total = $this->db
            ->select_sum('amount', 'amount')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('ipd_service_details')->result();
        $ipd_service_net_total = $ipd_service_net_total[0]->amount;
        //die( $ipd_service_net_total);



        //Due calculation from Emergency
        $ipd_emergency_net_total = $this->db
            ->select_sum('nettotal', 'nettotal')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('emergency')->result();
        $ipd_emergency_net_total = $ipd_emergency_net_total[0]->nettotal;
        $ipd_emergency_paid = $this->db
            ->select_sum('paid', 'paid')
            ->where('ipd_patient_id', $patient->ipd_patient_id)
            ->get('emergency')->result();
        $ipd_emergency_paid = $ipd_emergency_paid[0]->paid;
        $total_due_in_emergency = $ipd_emergency_net_total - $ipd_emergency_paid;

        $total_bill = (float)$extra_hours_bill + (float)$admission_reg_fee + (float)$consultant_fee + (float)$assistant_fee + (float)$total_ot_service_due + (float)$total_bill_from_cabin + (float)$total_due_in_test + (float)$total_due_in_medicine + (float)$total_due_in_phygiotherapy + (float)$ipd_service_net_total + (float)$total_due_in_emergency;
        $service_charge = (float)$total_bill * (float)$company->service_charge;

        $director_discount = 0;
        if ($reference_director != '') {
            $director_discount = ((float)$total_bill / 100) * (float)$reference_director->ipd_discount;
        }
        $payable = (float)$service_charge + (float)$total_bill - (float)$director_discount - (float)$patient->paid_amount;

        echo $total_days . '*' . $remaining_hours . '*' . $total_bill . '*' . $total_bill_from_cabin . '*' . $total_due_in_test . '*' . $total_due_in_medicine . '*' . $total_due_in_phygiotherapy . '*' . $ipd_service_net_total . '*' . $total_due_in_emergency . '*' . $patient->patient_name . '*' . $patient->mobile_number . '*' . $patient->age . '*' . $director_discount . '*' . $payable . '*' . date('d-m-Y', strtotime($patient->date)) . '*' . $patient->admission_time . '*' . $patient->ipd_patient_id . '*' . $patient->reference_director_id . '*' . $patient->paid_amount . '*' . $patient->patient_unique_id . '*' . $total_ot_service_due . '*' . $service_charge . '*' . $consultant_fee . '*' . $assistant_fee . '*' . $admission_reg_fee . '*' . $patient->age_year . '*' . $patient->age_year . '*' . $patient->age_year . '*' . $bed_or_cabin_charge . '*' . $extra_hours_bill;
    }
    public function delete_ipd_discharge_ajax()
    {
        $discharge_id = $this->input->post('discharge_id');
        $discharge = $this->db->where('discharge_id', $discharge_id)->get('discharge')->row();
        //back ipd patient status
        $ipd_patient_update = array(
            'status' => 'Admitted'
        );
        $ipd_patient_id = $discharge->ipd_patient_id;
        $this->db->where('ipd_patient_id', $ipd_patient_id)->update('ipd_patient', $ipd_patient_update);

        $ipd_patient = $this->db->where('ipd_patient_id', $ipd_patient_id)->get('ipd_patient')->row();
        if ($ipd_patient->cabin_id != '') {
            $booked = array(
                'status' => 'Not Available',
                'ipd_patient_id' => $ipd_patient_id,
            );
            $this->db->where('cabin_id', $ipd_patient->cabin_id)->update('cabin', $booked);
        }

        if ($ipd_patient->bed_id != '') {
            $booked = array(
                'status' => 'Not Available',
                'ipd_patient_id' => $ipd_patient_id,
            );
            $this->db->where('bed_id', $ipd_patient->bed_id)->update('bed', $booked);
        }

        if ($this->db->where('discharge_id', $discharge_id)->delete('discharge')) {
            $response = array('status' => 'success', 'message' => 'Discharge deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }
}
