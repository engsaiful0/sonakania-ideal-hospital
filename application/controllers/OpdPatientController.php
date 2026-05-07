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

class OpdPatientController extends CI_Controller
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

    public function index()
    {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }
    function sms_send($data)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $doctor = getDoctorById($data['doctor_id']);
        $sms_api = getSMSAPI();

        if ($sms_api->is_opd_sms_send == 'yes') { // if is_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['mobile_number'];
            $message = "Dear patient, you have booked the serial successfully. Patient Name: " . $data['opd_patient_name'] . ", Serial: " . $data['serial_numaber'] . ', Visiting Date and Time: ' . $data['visiting_date'] . $data['visiting_time'] . ', Doctor Name: ' . $doctor->doctor_name . ',' . $company_name;

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
                    'type' => 'OPD Patient',
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
    public function discount_reference_load()
    {
        $parameter = $this->input->post('parameter');
        $data_patient = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $doctors = $this->db->select("doctor_id, doctor_name")
                ->like('doctor_name', $parameter)
                ->get('doctor')
                ->result();

            // Search in director table
            $directors = $this->db->select("director_id, name")
                ->like('name', $parameter)
                ->get('director')
                ->result();

            // Search in employee table
            $employees = $this->db->select("employee_id, employee_name")
                ->like('employee_name', $parameter)
                ->get('employee')
                ->result();

            // Search in reference_media table
            $reference_media = $this->db->select("reference_media_id, reference_media_name")
                ->like('reference_media_name', $parameter)
                ->get('reference_media')
                ->result();

            // Process doctors
            foreach ($doctors as $doctor) {
                $data_patient[] = array(
                    'label' => $doctor->doctor_name,
                    'value' => $doctor->doctor_id,
                    'type' => 'doctor'
                );
            }

            // Process directors
            foreach ($directors as $director) {
                $data_patient[] = array(
                    'label' => $director->name,
                    'value' => $director->director_id,
                    'type' => 'director'
                );
            }

            // Process employees
            foreach ($employees as $employee) {
                $data_patient[] = array(
                    'label' => $employee->employee_name,
                    'value' => $employee->employee_id,
                    'type' => 'employee'
                );
            }

            // Process reference_media
            foreach ($reference_media as $media) {
                $data_patient[] = array(
                    'label' => $media->reference_media_name,
                    'value' => $media->reference_media_id,
                    'type' => 'reference_media'
                );
            }
        }

        echo json_encode($data_patient);
    }


    public function opd_patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('opd_patient_unique_id', $parameter)
                ->from('opd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('opd_patient');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->opd_patient_unique_id);
        }
        echo json_encode($data_patient);
    }
    public function patient_name_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('opd_patient_name', $parameter)
                ->from('opd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('opd_patient');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->opd_patient_name);
        }
        echo json_encode($data_patient);
    }
    public function mobile_number_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('mobile_number', $parameter)
                ->from('opd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('opd_patient');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->mobile_number);
        }
        echo json_encode($data_patient);
    }

    public function opd_patient_report()
    {
        $page_data = array(
            'page_name' => 'opd_patient/opd_patient_report',
            'page_title' => 'OPD Patient Report',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_patient_report_details($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['doctor_id'] = $data[2];
        $this->load->view('opd_patient/opd_patient_report_details', $data);
    }

    public function add_opd_patient()
    {
        $page_data = array(
            'page_name' => 'opd_patient/add_opd_patient',
            'page_title' => 'Add OPD Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
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
    public function delete_opd_patient_ajax()
    {
        $opd_patient_id = $this->input->post('opd_patient_id');

        if ($this->db->where('opd_patient_id', $opd_patient_id)->delete('opd_patient')) {
            $response = array('status' => 'success', 'message' => 'Patient deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }


    public function opd_patient_edit($opd_patient_id)
    {
        $data['opd_patient_id'] = $opd_patient_id;
        $this->load->view('opd_patient/opd_patient_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'opd_patient/opd_patient_edit',
            'page_title' => 'Opd Patient Edit',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function opd_patient_return($opd_patient_id)
    {
        $data['opd_patient_id'] = $opd_patient_id;
        $this->load->view('opd_patient/opd_patient_return', $data, TRUE);
        $page_data = array(
            'page_name' => 'opd_patient/opd_patient_return',
            'page_title' => 'Opd Patient Return',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function edit_opd_patient_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $opd_patient_id = $this->input->post('opd_patient_id');
            $data = array();
            $data['opd_patient_name'] = $this->input->post('opd_patient_name');
            $data['opd_patient_unique_id'] = $this->input->post('opd_patient_unique_id');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['address'] = $this->input->post('address');

            $data['gender'] = $this->input->post('gender');


            $data['entry_date'] = date('Y-m-d', strtotime($this->input->post('entry_date')));
            $data['serial_numaber'] = $this->input->post('serial_numaber');
            $data['visiting_date'] = date('Y-m-d', strtotime($this->input->post('visiting_date')));
            $data['visiting_fee'] = $this->input->post('visiting_fee');
            $data['visiting_interval'] = $this->input->post('visiting_interval');
            $data['visiting_time'] = $this->input->post('visiting_time');
            $data['discount'] = $this->input->post('discount');
            $data['discount_reference'] = $this->input->post('discount_reference');

            $data['age_year'] = $this->input->post('age_year');
            $data['age_month'] = $this->input->post('age_month');
            $data['age_day'] = $this->input->post('age_day');

            $data['years_or_days'] = $this->input->post('years_or_days');

            $data['payable'] = $this->input->post('payable');

            $data['department_id'] = $this->input->post('department_id') ?: null;
            $data['doctor_id'] = $this->input->post('doctor_id') ?: null;
            $data['reference_director_id'] = $this->input->post('reference_director_id') ?: null;
            $data['reference_doctor_id'] = $this->input->post('reference_doctor_id') ?: null;
            $data['reference_media_id'] = $this->input->post('reference_media_id') ?: null;
            $data['reference_employee_id'] = $this->input->post('reference_employee_id') ?: null;


            $this->db->where('opd_patient_id', $opd_patient_id)->update('opd_patient', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OPD patient updated and OPD Patient Id is=' . $opd_patient_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['opd_patient_id'] = $opd_patient_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function return_opd_patient_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $opd_patient_id = $this->input->post('opd_patient_id');
            $data = array();
            $data['return_reason'] = $this->input->post('return_reason');
            $data['returnable_amount'] = $this->input->post('returnable_amount');
            $data['return_user_id'] = $this->session->userdata('user_id');
            $data['deduction'] = $this->input->post('deduction');
            $data['return_time'] = $this->input->post('return_time');
            $data['status'] = "Returned";
            $data['return_date'] = date('Y-m-d', strtotime($this->input->post('return_date')));

            $this->db->where('opd_patient_id', $opd_patient_id)->update('opd_patient', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OPD patient returned and OPD Patient Id is=' . $opd_patient_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['opd_patient_id'] = $opd_patient_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function add_opd_patient_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['opd_patient_unique_id'] = $this->input->post('opd_patient_unique_id');
            $data['id_serial'] = $this->input->post('id_serial');
            $this->db->insert('opd_patient_unique_table', $data);

            $data = array();

            $data['opd_patient_name'] = $this->input->post('opd_patient_name');
            $data['opd_patient_unique_id'] = $this->input->post('opd_patient_unique_id');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['address'] = $this->input->post('address');
            $data['gender'] = $this->input->post('gender');
            $data['department_id'] = $this->input->post('department_id') ?: null;
            $data['doctor_id'] = $this->input->post('doctor_id') ?: null;

            $data['entry_date'] = date('Y-m-d', strtotime($this->input->post('entry_date')));
            $data['serial_numaber'] = $this->input->post('serial_numaber');
            $data['visiting_date'] = date('Y-m-d', strtotime($this->input->post('visiting_date')));
            $data['visiting_fee'] = $this->input->post('visiting_fee');
            $data['visiting_interval'] = $this->input->post('visiting_interval');
            $data['visiting_time'] = $this->input->post('visiting_time');
            $data['discount'] = $this->input->post('discount');
            $data['discount_reference'] = $this->input->post('discount_reference');
            $data['age_year'] = $this->input->post('age_year');
            $data['age_month'] = $this->input->post('age_month');
            $data['age_day'] = $this->input->post('age_day');
            $data['years_or_days'] = $this->input->post('years_or_days');
            $data['payable'] = $this->input->post('payable');

            $data['reference_director_id'] = $this->input->post('reference_director_id') ?: null;
            $data['reference_doctor_id'] = $this->input->post('reference_doctor_id') ?: null;
            $data['reference_media_id'] = $this->input->post('reference_media_id') ?: null;
            $data['reference_employee_id'] = $this->input->post('reference_employee_id') ?: null;
            $data['user_id'] =  $this->session->userdata('user_id') ?: null;

            $save_result = $this->db->insert('opd_patient', $data);

            $opd_patient_id = $this->db->insert_id();
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data); //To send sms
            }
            $sdata['opd_patient_id'] = $opd_patient_id;

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OPD patient added and OPD Patient Id is=' . $opd_patient_id
            );
            $this->db->insert('activity_log', $activity_data);

            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function opd_patient_print()
    {
        $page_data = array(
            'page_name' => 'opd_patient/print_opd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_patient_print_again($opd_patient_id)
    {
        $data['opd_patient_id'] = $opd_patient_id;
        $this->load->view('opd_patient/print_opd_patient_admission', $data, TRUE);
        $page_data = array(
            'page_name' => 'opd_patient/print_opd_patient_admission',
            'page_title' => 'Print Opd Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_payment()
    {
        $page_data = array(
            'page_name' => 'patient/opd_payment',
            'page_title' => 'OPD Payment',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function opd_payment_load()
    {
        $data = array();
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $data['doctor_id'] = $_POST['doctor_id'];
        $this->load->view('patient/opd_payment_load', $data);
    }

    public function patient_data_load()
    {
        $patient_id = $_POST['patient_id'];
        $patient = $this->db->where('patient_id', $patient_id)->get('patient')->row();
        echo $patient->patient_name . '-' . $patient->mobile_number . '-' . $patient->age;
    }

    public function view_opd_patient()
    {
        $doctor_id = $this->input->post('doctor_id');
        $department_id = $this->input->post('department_id');
        $gender = $this->input->post('gender');
        $patient_name = $this->input->post('patient_name');
        $mobile_number = $this->input->post('mobile_number');
        $opd_patient_unique_id = $this->input->post('opd_patient_unique_id');
        $reference_media_id = $this->input->post('reference_media_id');
        $status = $this->input->post('status');


        $to_date = '';
        $from_date = '';
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/OpdPatientController/view_opd_patient";
        $config['total_rows'] = $this->OpdPatientModel->count_all_opd_patients(
            $patient_name,
            $mobile_number,
            $opd_patient_unique_id,
            $gender,
            $reference_media_id,
            $doctor_id,
            $from_date,
            $to_date,
            $status
        );
        $config['per_page'] = 100;
        $config["uri_segment"] = 3;
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

        $data['page'] = $page;

        // Get OPD patient list
        $data['detailsList'] = $this->OpdPatientModel->opd_patient_details(
            $config["per_page"],
            $page,
            $patient_name,
            $mobile_number,
            $opd_patient_unique_id,
            $gender,
            $reference_media_id,
            $doctor_id,
            $from_date,
            $to_date,
            $status
        );
        $data['pagination'] = $this->pagination->create_links();

        // Load view with pagination
        $this->load->view('opd_patient/view_opd_patient', $data, true);
        $page_data = array(
            'page_name' => 'opd_patient/view_opd_patient',
            'page_title' => 'View OPD Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function visiting_fee_load()
    {
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db->where('doctor_id', $doctor_id)
            ->get('doctor')->row();
        echo $doctor->new_patient_fee;
    }

    public function serial_load()
    {
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db
            ->where('doctor_id', $doctor_id)
            ->where('entry_date', date('Y-m-d'))
            ->get('opd_patient');
        echo $doctor->num_rows() + 1;
    }

    public function opd_patient_payment_save()
    {
        $opd_patient_id = $this->input->post('opd_patient_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $doctor_id = $this->input->post('doctor_id');
        $doctor = $this->db->where('doctor_id', $doctor_id)->get('doctor')->row();
        $total_visitiong_fee = 0;
        $total_doctors_commission = 0;
        $total_patients = 0;
        for ($i = 0; $i < count($opd_patient_id); $i++) {
            $update = array('is_doctor_commision_paid' => 'yes');
            $this->db->where('opd_patient_id', $opd_patient_id[$i])->update('opd_patient', $update);
            $opd_patient = $this->db->where('opd_patient_id', $opd_patient_id[$i])->get('opd_patient')->row();

            $total_visitiong_fee += $opd_patient->payable;
            $total_doctors_commission += $opd_patient->payable * ($doctor->opd_patient_percentage / 100);
            $total_patients++;
        }
        $data = array(
            'doctor_id' => $doctor_id,
            'percentage_of_commission' => $doctor->opd_patient_percentage,
            'from_date' => date('Y-m-d', strtotime($from_date)),
            'to_date' => date('Y-m-d', strtotime($to_date)),
            'total_patients' => $total_patients,
            'total_visitiong_fee' => $total_visitiong_fee,
            'total_doctors_commission' => $total_doctors_commission,
            'paid_date' => date('Y-m-d')
        );
        $this->db->insert('opd_doctor_commision_payment', $data);
        $data['opd_doctor_commision_payment_id'] = $this->db->insert_id();
        $this->load->view('opd_patient/opd_patient_doctor_payment_voucer', $data, true);
        $page_data = array(
            'page_name' => 'opd_patient/opd_patient_doctor_payment_voucer',
            'page_title' => 'Payment Voucher',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
