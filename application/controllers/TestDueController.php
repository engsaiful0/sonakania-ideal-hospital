<?php

class TestDueController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    
    public function test_due_collection($offset = 0)
    {
        $invoice_no = $this->input->post('invoice_no');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $total_rows = 0;

        $config['base_url'] = base_url('test-due-collection');
        $config['total_rows'] =  $this->TestModel->count_all_dues($invoice_no, $patient_name, $mobile, $from_date, $to_date);
        $config['per_page'] = "50";
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->TestModel->patient_test_entry_details($config["per_page"], $offset, $invoice_no, $patient_name, $mobile, $from_date, $to_date);


        //    $data['model_id'] = $model_id;
        //  $data['product_category_id'] = $product_category_id;

        $this->load->view('test/test_due_collection', $data, true);
        $page_data = array(
            'page_name' => 'test/test_due_collection',
            'page_title' => 'Due Collection',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_due_payment_save()
    {
        if ($this->input->is_ajax_request()) {
            $patient_test_entry_id = $this->input->post('patient_test_entry_id');
            $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
            $data_sells = array(
                'patient_test_entry_id' => $patient_test_entry_id,
                'invoice_no' => $patient_test_entry->invoice_no,
                'payment_type' => 'from_due_collection',
                'paid' => $patient_test_entry->due,
                'due' => '0',
                'user_id' => $this->session->userdata('user_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->insert('test_collection', $data_sells);

            // Update the patient_test_entry table to mark the test as paid
            // and set the due amount to 0
            $update = array(
                'paid_or_due_status' => "paid",
                'due' => '0',
                'due_paid_amount' => $patient_test_entry->due,
                'due_payment_user_id' => $this->session->userdata('user_id'),
                'due_payment_date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->where("patient_test_entry_id",$patient_test_entry_id)->update('patient_test_entry', $update);


            $sdata['patient_test_entry_id'] = $patient_test_entry_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Send SMS notification
            $this->sms_send($patient_test_entry);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            
            echo json_encode($response);
        }
    }

    function sms_send($patient_test_entry)
    {
        $sms_api = getSMSAPI();
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;

        if ($sms_api->is_test_due_sms_send == 'yes') { // if is_test_due_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $patient_test_entry->mobile_number;
            $message = "Dear Patient, your test due payment has been received successfully. Patient Name: " . $patient_test_entry->patient_name . ", Amount Paid: " . $patient_test_entry->due . ' BDT, ' . $company_name;

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
                    'type' => 'Test Due Payment',
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
