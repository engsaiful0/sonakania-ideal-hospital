<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseController
 *
 * @author Lenovo
 */

use Laminas\Barcode\Barcode;

class OTServiceController extends CI_Controller
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
    public function ot_service_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('ot_service_unique_id', $parameter)
                ->from('ot_services');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('ot_services');
        }
        $sql = $this->db->get()->result();
        $data_ot_services = array();
        foreach ($sql as $value) {
            array_push($data_ot_services, $value->ot_service_unique_id);
        }
        echo json_encode($data_ot_services);
    }
    public function surgery_price_load()
    {
        $surgery_id = $_POST['surgery_id'];
        $surgery = $this->db->where('surgery_id', $surgery_id)->get('surgeries')->row();
        echo $surgery->price;
    }
    public function add_more_doctor_row()
    {
        $data['next_id'] = $_POST['next_id'];
        $this->load->view('ot_service/more_doctor_row', $data);
    }
    public function add_more_nurse_row()
    {
        $data['next_id'] = $_POST['next_id'];
        $this->load->view('ot_service/more_nurse_row', $data);
    }

    public function get_ot_service_details()
    {
        $ot_service_id = $this->input->post('ot_service_id');
        $data = $this->db->where('ot_service_id', $ot_service_id)->get('ot_services')->row();
        $this->load->view('ot_service/ot_service_details', ['data' => $data]);
    }
    public function view_ot_service()
    {
        $patient_unique_id = $this->input->post('patient_unique_id');
        $ot_service_unique_id = $this->input->post('ot_service_unique_id');

        $surgery_id = $this->input->post('surgery_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        // die($patient_id);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Pagination configuration
        $config['base_url'] = base_url() . "index.php/OTServiceController/view_ot_service";
        $config['total_rows'] = $this->OTServiceModel->count_all_ot_service($ot_service_unique_id, $patient_unique_id, $from_date, $to_date, $surgery_id);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

        // Integrate bootstrap pagination
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Get medicine sales list
        $data['ipd_service_data'] = $this->OTServiceModel->get_ot_service($this->per_page, $page, $ot_service_unique_id, $patient_unique_id, $from_date, $to_date, $surgery_id);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('ot_service/view_ot_service', $data, true);
        $page_data = array(
            'page_name' => 'ot_service/view_ot_service',
            'page_title' => 'View IPD Service',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_ot_service()
    {
        $page_data = array(
            'page_name' => 'ot_service/add_ot_service',
            'page_title' => 'Add Ipd Service',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_ot_service()
    {
        $page_data = array(
            'page_name' => 'ot_service/print_ot_service',
            'page_title' => 'Print Ot Service',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_ot_service_by_id($ot_service_id)
    {
        $data['ot_service_id'] = $ot_service_id;
        $this->load->view('ot_service/print_ot_service', $data, TRUE);
        $page_data = array(
            'page_name' => 'ot_service/print_ot_service',
            'page_title' => 'Print Ot Service',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_ot_service($ot_service_id)
    {
        $data['ot_service_id'] = $ot_service_id;
        $this->load->view('ot_service/edit_ot_service', $data, true);
        $page_data = array(
            'page_name' => 'ot_service/edit_ot_service',
            'page_title' => 'Edit OT Srvice',
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
    function sms_send($data)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $surgery = getSugeryById($data['surgery_id']);

        $sms_api = getSMSAPI();

        if ($sms_api->is_ot_service_sms_send == 'yes') { // if is_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['mobile_number'];
            $message = "Dear patient, you'r OT has been done successfully. Patient Name: " . $data['patient_name'] . ", Surgery Name : " . $surgery->name . ', Date and Time: ' . $data['date'] . $data['time'] . ',' . $company_name;

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
                    'type' => 'OT Service',
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
    public function ot_service_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['ot_service_unique_id'] = $this->input->post('ot_service_unique_id');
            $data['id_serial'] = $this->input->post('id_serial');
            $this->db->insert('ot_service_unique_table', $data);

            $config['upload_path'] = 'assets/bond/';
            $config['allowed_types'] = '*';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $bond_paper = '';
            $this->upload->do_upload('bond_paper');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            $bond_paper = $sdata['file_name'];

            // Get form data
            $data = array(
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'patient_unique_id' => $this->input->post('patient_unique_id'),
                'ot_service_unique_id' => $this->input->post('ot_service_unique_id'),
                'patient_name' => $this->input->post('patient_name'),
                'age_year' => $this->input->post('age_year'),
                'age_month' => $this->input->post('age_month'),
                'age_day' => $this->input->post('age_day'),
                'years_or_days' => $this->input->post('years_or_days'),
                'mobile_number' => $this->input->post('mobile_number'),
                'address' => $this->input->post('address'),
                'surgery_id' => $this->input->post('surgery_id'),
                'price' => $this->input->post('price'),
                'price' => $this->input->post('price'),
                'discount' => $this->input->post('discount'),
                'net_price' => $this->input->post('net_price'),
                'total_discount' => $this->input->post('total_discount'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'discount_reference' => $this->input->post('discount_reference'),
                'surgon_doctor_id' => json_encode($this->input->post('surgon_doctor_id')),
                'employee_nurse_id' => json_encode($this->input->post('employee_nurse_id')),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'time' => $this->input->post('time'),
                'anestasia_doctor_id' => $this->input->post('anestasia_doctor_id'),
                'bond_paper' => $bond_paper,
                'reference_employee_id' => $this->input->post('reference_employee_id'),
                'reference_media_id' => $this->input->post('reference_media_id'),
                'reference_director_id' =>  $this->input->post('reference_director_id'),
                'reference_doctor_id' => $this->input->post('reference_doctor_id'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $save_result = $this->db->insert('ot_services', $data);
            $ot_service_id = $this->db->insert_id();
            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data); //To send sms
            }

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OT Service added and OT Service Id is=' . $ot_service_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ot_service_saved'] = 'saved successully';
            $sdata['print_ot_service_id'] = $ot_service_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function delete_ot_service_ajax()
    {
        $ot_service_id = $this->input->post('ot_service_id');
        if ($this->db->where('ot_service_id', $ot_service_id)->delete('ot_services')) {

            $response = array('status' => 'success', 'message' => 'OT Service deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }

    public function ot_service_update()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $ot_service_id = $this->input->post('ot_service_id');
            $config['upload_path'] = 'assets/bond/';
            $config['allowed_types'] = '*';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $bond_paper = '';
            $this->upload->do_upload('bond_paper');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            $bond_paper = $sdata['file_name'];
            if ($bond_paper == '') {
                $bond_paper = $this->input->post('old_bond_paper');
            }
            // Get form data
            $data = array(
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'patient_unique_id' => $this->input->post('patient_unique_id'),
                'ot_service_unique_id' => $this->input->post('ot_service_unique_id'),
                'patient_name' => $this->input->post('patient_name'),
                'age_year' => $this->input->post('age_year'),
                'age_month' => $this->input->post('age_month'),
                'age_day' => $this->input->post('age_day'),
                'years_or_days' => $this->input->post('years_or_days'),
                'mobile_number' => $this->input->post('mobile_number'),
                'address' => $this->input->post('address'),
                'surgery_id' => $this->input->post('surgery_id'),
                'price' => $this->input->post('price'),
                'price' => $this->input->post('price'),
                'discount' => $this->input->post('discount'),
                'net_price' => $this->input->post('net_price'),
                'total_discount' => $this->input->post('total_discount'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'discount_reference' => $this->input->post('discount_reference'),
                'surgon_doctor_id' => json_encode($this->input->post('surgon_doctor_id')),
                'employee_nurse_id' => json_encode($this->input->post('employee_nurse_id')),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'time' => $this->input->post('time'),
                'reference_employee_id' => $this->input->post('reference_employee_id'),
                'reference_media_id' => $this->input->post('reference_media_id'),
                'reference_director_id' =>  $this->input->post('reference_director_id'),
                'reference_doctor_id' => $this->input->post('reference_doctor_id'),
                'anestasia_doctor_id' => $this->input->post('anestasia_doctor_id'),
                'bond_paper' => $bond_paper,
            );
            $this->db->where('ot_service_id', $ot_service_id)->update('ot_services', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OT Service update and OT Service Id is=' . $ot_service_id
            );
            $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_ot_service_id'] = $ot_service_id;
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function ot_service_due_payment($ot_service_id)
    {
        $data['ot_service_id'] = $ot_service_id;
        $this->load->view('ot_service/ot_service_due_payment', $data, true);
        $page_data = array(
            'page_name' => 'ot_service/ot_service_due_payment',
            'page_title' => 'OT Service Due Payment',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_ot_service_due_payment_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $ot_service_id = $this->input->post('ot_service_id');
            // Data for main emergency table
            $data = array(
                'due_payment' => $this->input->post('due_payment') ?? null,
                'due' => $this->input->post('due') ?? null,
                'due_payment_date' => $this->input->post('date') ?? null,
                'due_payment_time' => $this->input->post('due_payment_time') ?? null,
                'due_payment_user_id' =>$this->session->userdata('user_id'),
            );
            // Update main emergency record
            $this->db->where('ot_service_id', $ot_service_id)->update('ot_services', $data);

            // Log activity
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'OT Service due payment updated and OT Service Id is=' . $ot_service_id,
            );
            $this->db->insert('activity_log', $activity_data);
            // Session for printing
            $sdata['print_ot_service_id'] = $ot_service_id;
            $this->session->set_userdata($sdata);
            // Return success JSON
            echo json_encode(array('success' => true, 'message' => 'Data updated successfully.'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Invalid request.'));
        }
    }
}
