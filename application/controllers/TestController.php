<?php

use Laminas\Barcode\Barcode;

class TestController extends CI_Controller
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
    public function dashboard()
    {

        $page_data = array(
            'page_name' => 'test/dashboard',
            'page_title' => 'Test Dashboard',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function daily_test_sales_report()
    {
        $page_data = array(
            'page_name' => 'test/daily_test_sales_report',
            'page_title' => 'Daily Test Sales Report',
            'sidebar' => 'test/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function daily_test_sales_report_details_load($ids)
    {
        $ids = explode('_', $ids);
        $data['from_date'] = $ids[0];
        $data['to_date'] = $ids[1];
        $this->load->view('test/daily_test_sales_report_details_load', $data);
    }

    public function load_product_row()
    {
        $data['id'] = $_POST['id'];
        $this->load->view('test/load_product_row', $data);
    }
    public function TestEntryDetailsPrintWithId($patient_test_entry_id)
    {

        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $data['duplicate_or_main'] = "DUPLICATE";

        $this->load->view('test/print_test_entry_invoice_details', $data, true);
        $page_data = array(
            'page_name' => 'test/print_test_entry_invoice_details',
            'page_title' => 'Test Entry Invoie',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_test_entry_with_id($patient_test_entry_id)
    {

        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->session->set_userdata($data);
        $data['duplicate_or_main'] = "DUPLICATE";
        $this->load->view('test/print_test_entry', $data, true);
        $page_data = array(
            'page_name' => 'test/print_test_entry',
            'page_title' => 'Test Entry Invoie',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_test_entry()
    {
        $page_data = array(
            'page_name' => 'test/print_test_entry',
            'page_title' => 'Test Entry Invoie',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_test_entry_after_due_payment()
    {
        $page_data = array(
            'page_name' => 'test/print_test_entry_after_due_payment',
            'page_title' => 'Test Entry Invoie',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_test_entry($patient_test_entry_id)
    {
        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->load->view('test/edit_test_entry', $data, true);
        $page_data = array(
            'page_name' => 'test/edit_test_entry',
            'page_title' => 'Test Entry Invoie',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function return_test_entry($patient_test_entry_id)
    {
        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->load->view('test/return_test_entry', $data, true);
        $page_data = array(
            'page_name' => 'test/return_test_entry',
            'page_title' => 'Test Entry Return',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function price_load()
    {
        $test_id = $_POST['test_id'];
        $test = $this->db->where('test_id', $test_id)->get('test')->row();
        echo $test->price . '*' . $test->test_category_id;
    }

    public function deliver_report($patient_test_entry_id)
    {
        $update = array('is_delivered' => 'delivered');
        $this->db->where('patient_test_entry_id', $patient_test_entry_id)
            ->update('patient_test_entry', $update);
        $sdata['delivered'] = 'saved successully';
        $this->session->set_userdata($sdata);

        $patient_test_entry_id = $this->input->post('patient_test_entry_id');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');

        $config['base_url'] = site_url('TestController/view_test_entry');
        $config['total_rows'] = $this->db->count_all('patient_test_entry');
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->TestModel->patient_test_entry_details($config["per_page"], $data['page'], $patient_test_entry_id, $patient_name, $mobile);

        $data['pagination'] = $this->pagination->create_links();
        //    $data['model_id'] = $model_id;
        //  $data['product_category_id'] = $product_category_id;

        $this->load->view('test/test_report_delivery', $data, true);
        $page_data = array(
            'page_name' => 'test/test_report_delivery',
            'page_title' => 'Report Delivery',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_report_delivery()
    {
        $invoice_no = $this->input->post('invoice_no');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $config['base_url'] = base_url('test-report-delivery');
        $config['total_rows'] = $this->TestModel->count_all_report_delivery($invoice_no, $patient_name, $mobile, $from_date, $to_date);
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // get books list
        $data['detailsList'] = $this->TestModel->patient_test_entry_details($config["per_page"], $data['page'], $invoice_no, $patient_name, $mobile, $from_date, $to_date);
        $this->load->view('test/test_report_delivery', $data, true);
        $page_data = array(
            'page_name' => 'test/test_report_delivery',
            'page_title' => 'Report Delivery',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function test_due_collection()
    {
        $config = array();
        $invoice_no = $this->input->post('invoice_no');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');
        $from_date = '';
        $to_date = '';

        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/TestController/test_due_collection";
        $config['total_rows'] = $this->TestModel->count_all_dues($invoice_no, $patient_name, $mobile, $from_date, $to_date);
        $config["per_page"] = 20;
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

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Fetch data with limit and offset
        $data['detailsList'] = $this->TestModel->patient_test_entry_details($config['per_page'], $page, $invoice_no, $patient_name, $mobile, $from_date, $to_date);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'test/test_due_collection';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'test/test_sidebar';
        $this->load->view('content', $data);
    }
    public function test_due_collection1($offset = 0)
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

    public function add_test_entry()
    {
        $page_data = array(
            'page_name' => 'test/add_test_entry',
            'page_title' => 'Test Entry',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_due_payment_save()
    {
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

        $update = array(
            'paid' => $patient_test_entry->paid + $this->input->post('due'),
            'due' => '0',
            'paid_or_due_status' => 'paid'
        );
        $this->db->where('patient_test_entry_id', $patient_test_entry_id)
            ->update('patient_test_entry', $update);

        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->load->view('test/print_test_entry_invoice', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'test/print_test_entry_invoice',
            'page_title' => 'Print Investigation',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_test_entry_invoice()
    {
        $page_data = array(
            'page_name' => 'test/print_test_entry_invoice',
            'page_title' => 'Print Investigation',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function invoice_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('invoice_no', $parameter)
                ->from('patient_test_entry');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('patient_test_entry');
        }
        $sql = $this->db->get()->result();
        $data_invoice = array();
        foreach ($sql as $value) {
            array_push($data_invoice, $value->invoice_no);
        }
        echo json_encode($data_invoice);
    }
    public function patient_name_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('patient_name', $parameter)
                ->from('patient_test_entry');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('patient_test_entry');
        }
        $sql = $this->db->get()->result();
        $data_patient_name = array();
        foreach ($sql as $value) {
            array_push($data_patient_name, $value->patient_name);
        }
        echo json_encode($data_patient_name);
    }

    public function mobile_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('mobile', $parameter)
                ->from('patient_test_entry');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('patient_test_entry');
        }
        $sql = $this->db->get()->result();
        $data_mobile = array();
        foreach ($sql as $value) {
            array_push($data_mobile, $value->mobile);
        }
        echo json_encode($data_mobile);
    }

    function print_test_entry_invoice_with_id($patient_test_entry_id)
    {
        $sdata['print_patient_test_entry_id'] = $patient_test_entry_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'test/print_test_entry_invoice',
            'page_title' => 'Print Investigation',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function sms_send($data)
    {

        $sms_api = getSMSAPI();
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;

        if ($sms_api->is_test_entry_sms_send == 'yes') { // if is_test_entry_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['mobile_number'];
            $message = "Dear Patient, your test entry has been placed successfully. Patient Name: " . $data['patient_name'] . ", Gender: " . $data['gender'] . ', Age: ' . $data['age_year'] . ',' . $company_name;

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
                    'type' => 'Test Entry',
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
    public function add_test_entry_save()
    {
        if ($this->input->is_ajax_request()) {

            $invoice_no = $this->input->post('invoice_no');
            $data_invoice = array('invoice_no' => $invoice_no);

            // Ensure NULL for empty values
            function null_if_empty($value)
            {
                return !empty($value) ? $value : NULL;
            }
            $paid_or_due_status = '';
            if ($this->input->post('due') == 0) {
                $paid_or_due_status = 'paid';
            } else {
                $paid_or_due_status = 'due';
            }

            $data_test_entry = array(
                'patient_name' => null_if_empty($this->input->post('patient_name')),
                'username' => null_if_empty($this->input->post('mobile_number')), //For later signin
                'password' => generate_password(),
                'mobile_number' => null_if_empty($this->input->post('mobile_number')),
                'ipd_patient_id' => null_if_empty($this->input->post('ipd_patient_id')),
                'patient_unique_id' => null_if_empty($this->input->post('patient_unique_id')),
                'reference_director_id' => null_if_empty($this->input->post('reference_director_id')),
                'reference_doctor_id' => null_if_empty($this->input->post('reference_doctor_id')),
                'reference_media_id' => null_if_empty($this->input->post('reference_media_id')),
                'reference_employee_id' => null_if_empty($this->input->post('reference_employee_id')),
                'director_discount' => null_if_empty($this->input->post('director_discount')),
                'director_discount_percentage' => null_if_empty($this->input->post('director_discount_percentage')),
                'discount_reference_director_id' => null_if_empty($this->input->post('discount_reference_director_id')),
                'discount_reference_doctor_id' => null_if_empty($this->input->post('discount_reference_doctor_id')),
                'discount_reference_employee_id' => null_if_empty($this->input->post('discount_reference_employee_id')),
                'discount_reference_media_id' => null_if_empty($this->input->post('discount_reference_media_id')),
                'sub_total' => null_if_empty($this->input->post('sub_total')),
                'discount' => null_if_empty($this->input->post('discount')),
                'total_discount' => null_if_empty($this->input->post('total_discount')),
                'discount_reference' => null_if_empty($this->input->post('discount_reference')),
                'net_total' => null_if_empty($this->input->post('net_total')),
                'age_year' => null_if_empty($this->input->post('age_year')),
                'age_month' => null_if_empty($this->input->post('age_month')),
                'age_day' => null_if_empty($this->input->post('age_day')),
                'years_or_days' => null_if_empty($this->input->post('years_or_days')),
                'gender' => null_if_empty($this->input->post('gender')),
                'invoice_no' => null_if_empty($this->input->post('invoice_no')),
                'doctor_id' => null_if_empty($this->input->post('doctor_id')),
                'paid' => null_if_empty($this->input->post('paid')),
                'vat' => null_if_empty($this->input->post('vat')),
                'vat_in_percentage' => null_if_empty($this->input->post('vat_in_percentage')),
                'given' => null_if_empty($this->input->post('given')),
                'change' => null_if_empty($this->input->post('change')),
                'time' => null_if_empty($this->input->post('time')),
                'address' => null_if_empty($this->input->post('address')),
                'due' => null_if_empty($this->input->post('due')),
                'paid_or_due_status' => $paid_or_due_status,
                'user_id' => $this->session->userdata('user_id'),
                'date' => date('Y-m-d', strtotime(null_if_empty($this->input->post('date')))),
            );

            $this->db->insert('patient_test_entry', $data_test_entry);
            $patient_test_entry_id = $this->db->insert_id();

            $data_sells = array(
                'patient_test_entry_id' => $patient_test_entry_id,
                'invoice_no' => null_if_empty($this->input->post('invoice_no')),
                'payment_type' => 'from_direct_sales',
                'sub_total' => null_if_empty($this->input->post('sub_total')),
                'discount' => null_if_empty($this->input->post('discount')),
                'net_total' => null_if_empty($this->input->post('net_total')),
                'paid' => null_if_empty($this->input->post('paid')),
                'due' => null_if_empty($this->input->post('due')),
                'user_id' => $this->session->userdata('user_id'),
                'reference_director_id' => null_if_empty($this->input->post('reference_director_id')),
                'reference_doctor_id' => null_if_empty($this->input->post('reference_doctor_id')),
                'reference_media_id' => null_if_empty($this->input->post('reference_media_id')),
                'reference_employee_id' => null_if_empty($this->input->post('reference_employee_id')),
                'date' => date('Y-m-d', strtotime(null_if_empty($this->input->post('date')))),
            );
            $this->db->insert('test_collection', $data_sells);

            $test_id = $this->input->post('test_id');
            $test_category_id = $this->input->post('test_category_id');

            $quantity = $this->input->post('quantity');
            $unit_price = $this->input->post('unit_price');
            $total_price = $this->input->post('total_price');
            $delivery_date = $this->input->post('delivery_date');

            $test_array = array();
            $discount = null_if_empty($this->input->post('discount'));
            $net_total = null_if_empty($this->input->post('net_total'));

            $test_array = [];
            $total_price_sum = array_sum($total_price); // Sum of all item total prices

            for ($i = 0; $i < count($test_id); $i++) {
                $item_total = null_if_empty($total_price[$i]);

                // Proportionally distribute discount and paid amount
                $discount_each = ($total_price_sum > 0) ? round(($item_total / $total_price_sum) * $discount, 2) : 0;
                $paid_each = ($total_price_sum > 0) ? round(($item_total / $total_price_sum) * $net_total, 2) : 0;

                $test_array[] = array(
                    'patient_test_entry_id' => $patient_test_entry_id,
                    'test_id' => null_if_empty($test_id[$i]),
                    'test_category_id' => null_if_empty($test_category_id[$i]),
                    'delivery_date' => date('Y-m-d', strtotime(null_if_empty($delivery_date[$i]))),
                    'quantity' => null_if_empty($quantity[$i]),
                    'discount_each' => $discount_each,
                    'paid_each' => $paid_each,
                    'unit_price' => null_if_empty($unit_price[$i]),
                    'total_price' => $item_total,
                    'user_id' => $this->session->userdata('user_id'),
                    'reference_director_id' => null_if_empty($this->input->post('reference_director_id')),
                    'reference_doctor_id' => null_if_empty($this->input->post('reference_doctor_id')),
                    'reference_media_id' => null_if_empty($this->input->post('reference_media_id')),
                    'reference_employee_id' => null_if_empty($this->input->post('reference_employee_id')),
                    'date' => date('Y-m-d', strtotime(null_if_empty($this->input->post('date')))),
                );
            }

            $save_result = $this->db->insert_batch('patient_test_entry_details', $test_array);

            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data_test_entry);
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'invoice_no' => null_if_empty($this->input->post('invoice_no')),
                'test_invoice_serial' => null_if_empty($this->input->post('test_invoice_serial')),
            );
            $this->db->insert('test_invoice', $invoice_data);

            $sdata['patient_test_entry_id'] = $patient_test_entry_id;
            $sdata['success'] = 'saved successfully';
            $this->session->set_userdata($sdata);
            $response = array('success' => true, 'message' => 'Data saved successfully.');

            echo json_encode($response);
        } else {
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function return_test_entry_save()
    {
        if ($this->input->is_ajax_request()) {
            $patient_test_entry_id = $this->input->post('patient_test_entry_id');

            // Get previous data for maintaining user_id consistency
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

            // Update main test entry
            $this->db->where('patient_test_entry_id', $patient_test_entry_id)->update('patient_test_entry', $data);


            // Insert new test details
            $total_return = $this->input->post('total_return');
            $patient_test_entry_details_id = $this->input->post('patient_test_entry_details_id');
            $return_date = date('Y-m-d', strtotime($this->input->post('return_date')));
            $return_user_id = $this->session->userdata('user_id');

            for ($i = 0; $i < count($total_return); $i++) {
                $return_value = $total_return[$i];

                // Only update if value is not empty and not 0
                if (!empty($return_value) && $return_value != 0) {
                    $data = array(
                        'total_return'   => $return_value,
                        'return_user_id' => $return_user_id,
                        'return_date'    => $return_date,
                    );

                    $this->db->where('patient_test_entry_details_id', $patient_test_entry_details_id[$i]);
                    $this->db->update('patient_test_entry_details', $data);
                }
            }


            // Set session data
            $sdata['patient_test_entry_id'] = $patient_test_entry_id;
            $sdata['success'] = 'saved successfully';
            $this->session->set_userdata($sdata);
            // Return JSON response
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            echo json_encode($response);
        } else {
            // If not an AJAX request, return error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function edit_test_entry_save()
    {
        if ($this->input->is_ajax_request()) {
            $patient_test_entry_id = $this->input->post('patient_test_entry_id');

            // Get previous data for maintaining user_id consistency
            $patient_test_previous_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();

            // Ensure NULL for empty values
            function null_if_empty($value)
            {
                return !empty($value) ? $value : NULL;
            }

            $paid_or_due_status = '';
            if ($this->input->post('due') == 0) {
                $paid_or_due_status = 'paid';
            } else {
                $paid_or_due_status = 'due';
            }

            $data_sells = array(
                'patient_name' => null_if_empty($this->input->post('patient_name')),
                'mobile_number' => null_if_empty($this->input->post('mobile_number')),
                'ipd_patient_id' => null_if_empty($this->input->post('ipd_patient_id')),
                'patient_unique_id' => null_if_empty($this->input->post('patient_unique_id')),
                'reference_director_id' => null_if_empty($this->input->post('reference_director_id')),
                'reference_doctor_id' => null_if_empty($this->input->post('reference_doctor_id')),
                'reference_media_id' => null_if_empty($this->input->post('reference_media_id')),
                'reference_employee_id' => null_if_empty($this->input->post('reference_employee_id')),
                'director_discount' => null_if_empty($this->input->post('director_discount')),
                'director_discount_percentage' => null_if_empty($this->input->post('director_discount_percentage')),
                
                'discount_reference_director_id' => null_if_empty($this->input->post('discount_reference_director_id')),
                'discount_reference_doctor_id' => null_if_empty($this->input->post('discount_reference_doctor_id')),
                'discount_reference_employee_id' => null_if_empty($this->input->post('discount_reference_employee_id')),
                'discount_reference_media_id' => null_if_empty($this->input->post('discount_reference_media_id')),

                'sub_total' => null_if_empty($this->input->post('sub_total')),
                'discount' => null_if_empty($this->input->post('discount')),
                'total_discount' => null_if_empty($this->input->post('total_discount')),
                'discount_reference' => null_if_empty($this->input->post('discount_reference')),
                'net_total' => null_if_empty($this->input->post('net_total')),
                'age_year' => null_if_empty($this->input->post('age_year')),
                'age_month' => null_if_empty($this->input->post('age_month')),
                'age_day' => null_if_empty($this->input->post('age_day')),
                'years_or_days' => null_if_empty($this->input->post('years_or_days')),
                'invoice_no' => null_if_empty($this->input->post('invoice_no')),
                'doctor_id' => null_if_empty($this->input->post('doctor_id')),
                'paid' => null_if_empty($this->input->post('paid')),
                'vat' => null_if_empty($this->input->post('vat')),
                'vat_in_percentage' => null_if_empty($this->input->post('vat_in_percentage')),
                'given' => null_if_empty($this->input->post('given')),
                'change' => null_if_empty($this->input->post('change')),
                'due' => null_if_empty($this->input->post('due')),
                'paid_or_due_status' => $paid_or_due_status,
                'time' => null_if_empty($this->input->post('time')),
                'address' => null_if_empty($this->input->post('address')),
                'date' => date('Y-m-d', strtotime(null_if_empty($this->input->post('date')))),
            );

            // Update main test entry
            $this->db->where('patient_test_entry_id', $patient_test_entry_id)->update('patient_test_entry', $data_sells);

            // Delete previous test items before inserting new ones
            $this->db->where('patient_test_entry_id', $patient_test_entry_id)->delete('patient_test_entry_details');

            // Update test collection table
            $data_sells = array(
                'invoice_no' => null_if_empty($this->input->post('invoice_no')),
                'payment_type' => 'from_direct_sales',
                'sub_total' => null_if_empty($this->input->post('sub_total')),
                'discount' => null_if_empty($this->input->post('discount')),
                'net_total' => null_if_empty($this->input->post('net_total')),
                'paid' => null_if_empty($this->input->post('paid')),
                'due' => null_if_empty($this->input->post('due')),
                'reference_director_id' => null_if_empty($this->input->post('reference_director_id')),
                'reference_doctor_id' => null_if_empty($this->input->post('reference_doctor_id')),
                'reference_media_id' => null_if_empty($this->input->post('reference_media_id')),
                'reference_employee_id' => null_if_empty($this->input->post('reference_employee_id')),
                'date' => date('Y-m-d', strtotime(null_if_empty($this->input->post('date')))),
            );
            $this->db->where('patient_test_entry_id', $patient_test_entry_id)->update('test_collection', $data_sells);

            // Insert new test details
            $test_id = $this->input->post('test_id');
            $test_category_id = $this->input->post('test_category_id');
            $quantity = $this->input->post('quantity');
            $unit_price = $this->input->post('unit_price');
            $total_price = $this->input->post('total_price');
            $delivery_date = $this->input->post('delivery_date');

            $test_array = array();
            $discount = null_if_empty($this->input->post('discount'));
            $net_total = null_if_empty($this->input->post('net_total'));

            $test_array = [];
            $total_price_sum = array_sum($total_price); // Sum of all item total prices

            for ($i = 0; $i < count($test_id); $i++) {
                $item_total = null_if_empty($total_price[$i]);

                // Proportionally distribute discount and paid amount
                $discount_each = ($total_price_sum > 0) ? round(($item_total / $total_price_sum) * $discount, 2) : 0;
                $paid_each = ($total_price_sum > 0) ? round(($item_total / $total_price_sum) * $net_total, 2) : 0;


                $test_array[] = array(
                    'patient_test_entry_id' => $patient_test_entry_id,
                    'test_id' => null_if_empty($test_id[$i]),
                    'test_category_id' => null_if_empty($test_category_id[$i]),
                    'quantity' => null_if_empty($quantity[$i]),
                    'discount_each' => $discount_each,
                    'paid_each' => $paid_each,
                    'unit_price' => null_if_empty($unit_price[$i]),
                    'total_price' => null_if_empty($total_price[$i]),
                    'delivery_date' => date('Y-m-d', strtotime(null_if_empty($delivery_date[$i]))),
                    'user_id' => $patient_test_previous_entry->user_id,
                    'reference_director_id' => null_if_empty($this->input->post('reference_director_id')),
                    'reference_doctor_id' => null_if_empty($this->input->post('reference_doctor_id')),
                    'reference_media_id' => null_if_empty($this->input->post('reference_media_id')),
                    'reference_employee_id' => null_if_empty($this->input->post('reference_employee_id')),
                    'date' => date('Y-m-d', strtotime(null_if_empty($this->input->post('date')))),
                );
            }

            $this->db->insert_batch('patient_test_entry_details', $test_array);

            // Set session data
            $sdata['patient_test_entry_id'] = $patient_test_entry_id;
            $sdata['success'] = 'saved successfully';
            $this->session->set_userdata($sdata);

            // Return JSON response
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            echo json_encode($response);
        } else {
            // If not an AJAX request, return error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function delete_test_entry_ajax()
    {
        $patient_test_entry_id = $this->input->post('patient_test_entry_id');
        if ($this->db->where('patient_test_entry_id', $patient_test_entry_id)->delete('patient_test_entry')) {
            $this->db->where('patient_test_entry_id', $patient_test_entry_id)->delete('test_collection');
            $this->db->where('patient_test_entry_id', $patient_test_entry_id)->delete('patient_test_entry_details');

            $response = array('status' => 'success', 'message' => 'Test Entry deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    public function view_test_entry()
    {
        $config = array();
        $invoice_no = $this->input->post('invoice_no') ?: '';
        $from_date = '';
        $to_date = '';
        $patient_name = $this->input->post('patient_name');
        $status = $this->input->post('status');
        $mobile = $this->input->post('mobile');
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/TestController/view_test_entry";
        $config['total_rows'] = $this->TestModel->count_all_test($invoice_no, $patient_name, $mobile, $from_date, $to_date, $status);
        $config["per_page"] = 100;
        $config["uri_segment"] = 3;
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
        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Fetch data with limit and offset
        $data['detailsList'] = $this->TestModel->patient_test_entry_details($config['per_page'], $page, $invoice_no, $patient_name, $mobile, $from_date, $to_date, $status);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'test/view_test_entry';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'test/test_sidebar';
        $this->load->view('content', $data);
    }


    public function sold_test_report()
    {
        $page_data = array(
            'page_name' => 'test/sold_test_report',
            'page_title' => 'Sold Test Report',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function sold_test_report_details_load($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['test_id'] = $data[2];
        $this->load->view('test/sold_test_report_details_load', $data);
    }
}
