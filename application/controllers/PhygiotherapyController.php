<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class PhygiotherapyController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('PhygiotherapyModel'); // Load your model
    }

    private $defaults = array();


    public function name_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('name', $parameter)
                ->from('phygiotherapy');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('phygiotherapy');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->name);
        }
        echo json_encode($data_patient);
    }
    public function phone_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('phone', $parameter)
                ->from('phygiotherapy');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('phygiotherapy');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->phone);
        }
        echo json_encode($data_patient);
    }
    public function phygiotherapy_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('phygiotherapy_invoice_no', $parameter)
                ->from('phygiotherapy');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('phygiotherapy');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->phygiotherapy_invoice_no);
        }
        echo json_encode($data_patient);
    }

    public function view_phygiotherapy()
    {
        $phygiotherapy_invoice_no = $this->input->post('phygiotherapy_invoice_no');
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $gender = $this->input->post('gender');
        $reference_doctor_id = $this->input->post('reference_doctor_id');
        $reference_media_id = $this->input->post('reference_media_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $status = $this->input->post('status');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Pagination configuration
        $config['base_url'] = base_url() . "index.php/PhygiotherapyController/view_phygiotherapy";

        $config['total_rows'] = $this->PhygiotherapyModel->count_all_phygiotherapy($name, $phone, $phygiotherapy_invoice_no, $gender, $reference_doctor_id, $reference_media_id, $from_date, $to_date, $status);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;

        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = 5;

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
        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Get medicine sales list
        $data['phygiotherapys_data'] = $this->PhygiotherapyModel->get_phygiotherapy($this->per_page, $page, $name, $phone, $phygiotherapy_invoice_no, $gender, $reference_doctor_id, $reference_media_id, $from_date, $to_date, $status);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('phygiotherapy/view_phygiotherapy', $data, true);
        $page_data = array(
            'page_name' => 'phygiotherapy/view_phygiotherapy',
            'page_title' => 'View phygiotherapy',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_phygiotherapy()
    {
        $page_data = array(
            'page_name' => 'phygiotherapy/add_phygiotherapy',
            'page_title' => 'Add phygiotherapy',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_phygiotherapy($phygiotherapy_id)
    {
        $data['phygiotherapy_id'] = $phygiotherapy_id;
        $this->load->view('phygiotherapy/edit_phygiotherapy', $data, true);
        $page_data = array(
            'page_name' => 'phygiotherapy/edit_phygiotherapy',
            'page_title' => 'Edit phygiotherapy',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function return_phygiotherapy($phygiotherapy_id)
    {
        $data['phygiotherapy_id'] = $phygiotherapy_id;
        $this->load->view('phygiotherapy/return_phygiotherapy', $data, true);
        $page_data = array(
            'page_name' => 'phygiotherapy/return_phygiotherapy',
            'page_title' => 'Return phygiotherapy',
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
    function print_phygiotherapy()
    {
        $page_data = array(
            'page_name' => 'phygiotherapy/print_phygiotherapy',
            'page_title' => 'Print phygiotherapy',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function phygiotherapy_service_price_load()
    {

        $phygiotherapy_service_id = $_POST['phygiotherapy_service_id'];
        if ($phygiotherapy_service_id != '') {
            $phygiotherapy_service = $this->db->where('phygiotherapy_service_id', $phygiotherapy_service_id)->get('phygiotherapy_service')->row();
            echo $phygiotherapy_service->price;
        }
    }

    public function delete_ipd_phygiotherapy_ajax()
    {
        $phygiotherapy_id = $this->input->post('phygiotherapy_id');
        if ($this->db->where('phygiotherapy_id', $phygiotherapy_id)->delete('phygiotherapy')) {
            $this->db->where('phygiotherapy_id', $phygiotherapy_id)->delete('phygiotherapy_details');
            $response = array('status' => 'success', 'message' => 'Phygiotherapy deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }

    function print_phygiotherapy_with_id($phygiotherapy_id)
    {
        $sdata['print_phygiotherapy_id'] = $phygiotherapy_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'phygiotherapy/print_phygiotherapy',
            'page_title' => 'Print Phygiotherapy',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function add_more_phygiotherapy_row()
    {
        $data['next_id'] = $_POST['next_id'];
        $this->load->view('phygiotherapy/more_phygiotherapy_row', $data);
    }
    public function phygiotherapy_return_data_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $phygiotherapy_id = $this->input->post('phygiotherapy_id');
            $this->db->where('phygiotherapy_id', $phygiotherapy_id)->get('phygiotherapy')->row();
            $data = array(
                'return_reason' => $this->input->post('return_reason'),
                'returnable_amount' => $this->input->post('returnable_amount'),
                'deduction' => $this->input->post('deduction'),
                'return_time' => $this->input->post('return_time'),
                'status' => "Returned",
                'return_user_id' => $this->session->userdata('user_id'),
                'return_date' => date('Y-m-d', strtotime($this->input->post('return_date'))),
                // Add more fields as needed
            );

            $this->db->where('phygiotherapy_id', $phygiotherapy_id)->update('phygiotherapy', $data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Physiotherapy return updated and physiotherapy Id is=' . $phygiotherapy_id,
            );
            $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data updated successfully.');
            $sdata['print_phygiotherapy_id'] = $phygiotherapy_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function update_phygiotherapy_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $paid_or_due_status='';
            if($this->input->post('due')==0)
            {
                $paid_or_due_status='paid';
            }else{
                $paid_or_due_status='due';
            }
            $phygiotherapy_id = $this->input->post('phygiotherapy_id');
            $phygiotherapy_previous_data = $this->db->where('phygiotherapy_id', $phygiotherapy_id)->get('phygiotherapy')->row();
            $data = array(
                'phygiotherapy_invoice_no' => $this->input->post('phygiotherapy_invoice_no'),
                'date' => $this->input->post('date'),
                'physiotherapy_time' => $this->input->post('physiotherapy_time'),
                'ipd_patient_id' => (!empty($ipd_patient_id)) ? $ipd_patient_id : null,
                'patient_unique_id' => $this->input->post('patient_unique_id'),
                'name' => $this->input->post('name'),
                'age_year' => $this->input->post('age_year') ?? null,
                'age_month' => $this->input->post('age_month') ?? null,
                'age_day' => $this->input->post('age_day') ?? null,
                'years_or_days' => $this->input->post('years_or_days'),
                'gender' => $this->input->post('gender'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'attendant' => $this->input->post('attendant'),
                'doctor_id' => (!empty($this->input->post('doctor_id'))) ? $this->input->post('doctor_id') : null,
                'employee_nurse_id' => (!empty($this->input->post('employee_nurse_id'))) ? $this->input->post('employee_nurse_id') : null,
                'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,

                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'director_discount' => $this->input->post('director_discount'),
                'director_discount_percentage' => $this->input->post('director_discount_percentage'),
                'total_discount' => $this->input->post('total_discount'),
                'discount_reference_director_id' => (!empty($this->input->post('discount_reference_director_id'))) ? $this->input->post('discount_reference_director_id') : null,
                'discount_reference_doctor_id' => (!empty($this->input->post('discount_reference_doctor_id'))) ? $this->input->post('discount_reference_doctor_id') : null,
                'discount_reference_employee_id' => (!empty($this->input->post('discount_reference_employee_id'))) ? $this->input->post('discount_reference_employee_id') : null,
                'discount_reference_media_id' => (!empty($this->input->post('discount_reference_media_id'))) ? $this->input->post('discount_reference_media_id') : null,
                
                'discount_reference' => $this->input->post('discount_reference'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $paid_or_due_status,
                'paid_or_due_status' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
                // Add more fields as needed
            );

            $phygiotherapy = $this->db->where('phygiotherapy_id', $phygiotherapy_id)->update('phygiotherapy', $data);

            $phygiotherapy_service_id = $this->input->post('phygiotherapy_service_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');
            $this->db->where('phygiotherapy_id', $phygiotherapy_id)->delete('phygiotherapy_details');

            $emerygency_details = array();
            for ($loop = 0; $loop < count($phygiotherapy_service_id); $loop++) {
                $emerygency_details[] = array(
                    'phygiotherapy_id ' => $phygiotherapy_id,
                    'ipd_patient_id ' => $this->input->post('ipd_patient_id'),
                    'patient_unique_id ' => $this->input->post('patient_unique_id'),
                    'phygiotherapy_service_id' => $phygiotherapy_service_id[$loop],
                    'price' => $price[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop],
                    'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                    'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                    'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                    'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                    'user_id' => $phygiotherapy_previous_data->user_id,
                );
            }


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'phygiotherapy updated and phygiotherapy Id is=' . $phygiotherapy_id,
            );
            $phygiotherapy = $this->db->insert('activity_log', $activity_data);


            $this->db->insert_batch('phygiotherapy_details', $emerygency_details);
            $response = array('success' => true, 'message' => 'Data updated successfully.');
            $sdata['print_phygiotherapy_id'] = $phygiotherapy_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function sms_send($data)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $sms_api = getSMSAPI();

        if ($sms_api->is_phygiotherapy_sms_send == 'yes') { // if is_phygiotherapy_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['phone'];

            $age_text = "";
            if (!empty($data['age_year'])) {
                $age_text .= $data['age_year'] . " " . ($data['age_year'] == 1 ? "Year" : "Years") . " ";
            }
            if (!empty($data['age_month'])) {
                $age_text .= $data['age_month'] . " " . ($data['age_month'] == 1 ? "Month" : "Months") . " ";
            }
            if (!empty($data['age_day'])) {
                $age_text .= $data['age_day'] . " " . ($data['age_day'] == 1 ? "Day" : "Days");
            }


            $message = "Dear Patient, you have booked for the phygiotherapy successfully. Patient Name: " . $data['name'] . ", Gender: " . $data['gender'] . ', Age: ' . trim($age_text) . ',' . $company_name;

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
                    'type' => 'Phygiotherapy',
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
    public function save_phygiotherapy_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $paid_or_due_status='';
            if($this->input->post('due')==0)
            {
                $paid_or_due_status='paid';
            }else{
                $paid_or_due_status='due';
            }
            $data = array(
                'phygiotherapy_invoice_no' => $this->input->post('phygiotherapy_invoice_no'),
                'date' => $this->input->post('date'),
                'physiotherapy_time' => $this->input->post('physiotherapy_time'),
                'ipd_patient_id' => (!empty($ipd_patient_id)) ? $ipd_patient_id : null,
                'patient_unique_id' => $this->input->post('patient_unique_id'),
                'name' => $this->input->post('name'),
                'age_year' => $this->input->post('age_year') ?? null,
                'age_month' => $this->input->post('age_month') ?? null,
                'age_day' => $this->input->post('age_day') ?? null,
                'years_or_days' => $this->input->post('years_or_days'),
                'gender' => $this->input->post('gender'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'attendant' => $this->input->post('attendant'),
                'doctor_id' => (!empty($this->input->post('doctor_id'))) ? $this->input->post('doctor_id') : null,
                'employee_nurse_id' => (!empty($this->input->post('employee_nurse_id'))) ? $this->input->post('employee_nurse_id') : null,
                'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'director_discount' => $this->input->post('director_discount'),
                'director_discount_percentage' => $this->input->post('director_discount_percentage'),
                'total_discount' => $this->input->post('total_discount'),
                'discount_reference' => $this->input->post('discount_reference'),
                'discount_reference_director_id' => (!empty($this->input->post('discount_reference_director_id'))) ? $this->input->post('discount_reference_director_id') : null,
                'discount_reference_doctor_id' => (!empty($this->input->post('discount_reference_doctor_id'))) ? $this->input->post('discount_reference_doctor_id') : null,
                'discount_reference_employee_id' => (!empty($this->input->post('discount_reference_employee_id'))) ? $this->input->post('discount_reference_employee_id') : null,
                'discount_reference_media_id' => (!empty($this->input->post('discount_reference_media_id'))) ? $this->input->post('discount_reference_media_id') : null,
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'paid_or_due_status' => $paid_or_due_status,
                'user_id' => $this->session->userdata('user_id'),
                // Add more fields as needed
            );


            $phygiotherapy = $this->db->insert('phygiotherapy', $data);

            $phygiotherapy_id = $this->db->insert_id();

            $phygiotherapy_service_id = $this->input->post('phygiotherapy_service_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');

            $emerygency_details = array();
            for ($loop = 0; $loop < count($phygiotherapy_service_id); $loop++) {
                $emerygency_details[] = array(
                    'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                    'patient_unique_id' => $this->input->post('patient_unique_id'),
                    'phygiotherapy_id' => $phygiotherapy_id,
                    'phygiotherapy_service_id' => $phygiotherapy_service_id[$loop],
                    'price' => $price[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop],
                    'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                    'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                    'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                    'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                    'user_id' => $this->session->userdata('user_id'),
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'phygiotherapy_invoice_no' => $this->input->post('phygiotherapy_invoice_no'),
                'phygiotherapy_invoice_serial' => $this->input->post('phygiotherapy_invoice_serial'),
            );
            $phygiotherapy = $this->db->insert('phygiotherapy_invoice', $invoice_data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Phygiotherapy added and phygiotherapy Id is=' . $phygiotherapy_id,
            );
            $phygiotherapy = $this->db->insert('activity_log', $activity_data);
            $save_result = $this->db->insert_batch('phygiotherapy_details', $emerygency_details);

            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data); //To send sms
            }

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_phygiotherapy_id'] = $phygiotherapy_id;
            $this->session->set_userdata($sdata);
            $sdata['phygiotherapy_inserted'] = 'Data has been inserted successfully.';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function physiotherapy_due_payment($phygiotherapy_id)
    {
        $data['phygiotherapy_id'] = $phygiotherapy_id;
        $this->load->view('phygiotherapy/physiotherapy_due_payment', $data, true);
        $page_data = array(
            'page_name' => 'phygiotherapy/physiotherapy_due_payment',
            'page_title' => 'Physiotherapy Due Payment',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_phygiotherapy_due_payment_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $phygiotherapy_id = $this->input->post('phygiotherapy_id');
            
            // Get phygiotherapy record before update for SMS
            $phygiotherapy = $this->db->where('phygiotherapy_id', $phygiotherapy_id)->get('phygiotherapy')->row();
            
            // Data for main emergency table
            $data = array(
                'due_payment' => $this->input->post('due_payment') ?? null,
                'due' => 0,
                'paid_or_due_status' => 'paid',
                'due_payment_date' => $this->input->post('due_payment_date') ?? null,
                'due_payment_time' => $this->input->post('due_payment_time') ?? null,
                'due_payment_user_id' =>$this->session->userdata('user_id'),
            );
            // Update main emergency record
            $this->db->where('phygiotherapy_id', $phygiotherapy_id)->update('phygiotherapy', $data);

            // Log activity
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Physiotherapy due payment updated and Physiotherapy Id is=' . $phygiotherapy_id,
            );
            $this->db->insert('activity_log', $activity_data);
            
            // Send SMS notification for due payment
            $this->sms_send_due_payment($phygiotherapy, $this->input->post('due_payment'));
            
            // Session for printing
            $sdata['print_phygiotherapy_id'] = $phygiotherapy_id;
            $this->session->set_userdata($sdata);
            // Return success JSON
            echo json_encode(array('success' => true, 'message' => 'Data updated successfully.'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Invalid request.'));
        }
    }
    
    function sms_send_due_payment($phygiotherapy, $due_payment_amount)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $sms_api = getSMSAPI();

        if ($sms_api->is_phygiotherapy_due_sms_send == 'yes') { // if is_phygiotherapy_due_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $phygiotherapy->phone;
            $message = "Dear Patient, your physiotherapy due payment has been received successfully. Patient Name: " . $phygiotherapy->name . ", Amount Paid: " . $due_payment_amount . " BDT, " . $company_name;

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
                    'type' => 'Phygiotherapy Due Payment',
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
}
