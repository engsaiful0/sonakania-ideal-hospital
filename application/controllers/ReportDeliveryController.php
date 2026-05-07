<?php

use Laminas\Barcode\Barcode;

class ReportDeliveryController extends CI_Controller
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
    public function test_report_delivery()
    {
        $config = array();
         $invoice_no = $this->input->post('invoice_no');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');
        $from_date='';
        $to_date='';
       
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/ReportDeliveryController/test_report_delivery";
         $config['total_rows'] = $this->TestModel->count_all_report_delivery($invoice_no, $patient_name, $mobile, $from_date, $to_date);
        $config["per_page"] = 20;
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
        $data['detailsList'] = $this->TestModel->patient_test_entry_details($config['per_page'], $page, $invoice_no, $patient_name, $mobile, $from_date, $to_date);
        // Create the pagination links
         $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'report_delivery/test_report_delivery';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'test/test_sidebar';
        $this->load->view('content', $data);
    }
    public function test_report_delivery1()
    {
        //  error_reporting(0);
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
        $this->load->view('report_delivery/test_report_delivery', $data, true);
        $page_data = array(
            'page_name' => 'report_delivery/test_report_delivery',
            'page_title' => 'Report Delivery',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function deliver_report_entry_ajax()
    {
        $patient_test_entry_id = $this->input->post('patient_test_entry_id');
        $update = array('is_delivered' => 'delivered');
        $status = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
            ->update('patient_test_entry', $update);
        if ($status) {
            $response = array('status' => 'success', 'message' => 'Report delivered successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
