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

class CreditVoucherController extends CI_Controller
{
    private $per_page;
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
    public function received_from_load()
    {
        $parameter = $this->input->post('parameter');
        $data_received_from = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $received_froms = $this->db->select("received_from AS received_from")
                ->like('received_from', $parameter)
                ->get('credit_voucher')
                ->result();

            // Extract discount_reference
            foreach ($received_froms as $value) {
                $data_received_from[] = $value->received_from;
            }
        }

        echo json_encode($data_received_from);
    }
    public function purpose_load()
    {
        $parameter = $this->input->post('parameter');
        $data_purpose = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $purposes = $this->db->select("purpose AS purpose")
                ->like('purpose', $parameter)
                ->get('credit_voucher')
                ->result();
            // Extract discount_reference
            foreach ($purposes as $value) {
                $data_purpose[] = $value->purpose;
            }
        }

        echo json_encode($data_purpose);
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
    public function credit_voucher_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('credit_voucher_no', $parameter)
                ->from('credit_voucher');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('credit_voucher');
        }
        $sql = $this->db->get()->result();
        $data_credit_voucher = array();
        foreach ($sql as $value) {
            array_push($data_credit_voucher, $value->credit_voucher_no);
        }
        echo json_encode($data_credit_voucher);
    }
    private $defaults = array();

    public function index() {}
    public function view_credit_voucher()
    {
        $credit_voucher_no = $this->input->post('credit_voucher_no');
        $credit_account_id = $this->input->post('credit_account_id');
        $type = $this->input->post('type');
        $bank_id = $this->input->post('bank_id');
        $check_number = $this->input->post('check_number');

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] =  base_url() . "index.php/CreditVoucherController/view_credit_voucher";
        $config['total_rows'] = $this->CreditVoucherModel->count_all_credit_vouchers($credit_voucher_no, $credit_account_id, $type, $bank_id, $check_number, $from_date, $to_date);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;

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

        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Get medicine sales list

        $data['credit_voucher_data'] = $this->CreditVoucherModel->get_credit_vouchers($this->per_page, $page, $credit_voucher_no, $credit_account_id, $type, $bank_id, $check_number, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('credit_voucher/view_credit_voucher', $data, true);
        $page_data = array(
            'page_name' => 'credit_voucher/view_credit_voucher',
            'page_title' => 'View Issue',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_credit_voucher()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'credit_voucher/add_credit_voucher',
            'page_title' => 'Add credit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_credit_voucher($credit_voucher_id)
    {
        $data['credit_voucher_id'] = $credit_voucher_id;
        $this->load->view('credit_voucher/edit_credit_voucher', $data, true);
        $page_data = array(
            'page_name' => 'credit_voucher/edit_credit_voucher',
            'page_title' => 'Edit credit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_credit_voucher_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'credit_account_id' => $this->input->post('credit_account_id'),
                'director_id' => $this->input->post('director_id'),
                'type' => $this->input->post('type'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'bank_account_id' => $this->input->post('bank_account_id'),
                'check_number' => $this->input->post('check_number'),
                'bank_details' => $this->input->post('bank_details'),
                'purpose' => $this->input->post('purpose'),
                'received_from' => $this->input->post('received_from'),

                'credit_voucher_no' => $this->input->post('credit_voucher_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_amount' => $this->input->post('total_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $credit_voucher = $this->db->insert('credit_voucher', $data);

            $credit_voucher_id = $this->db->insert_id();



            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'credit_voucher_no' => $this->input->post('credit_voucher_no'),
                'credit_voucher_invoice_serial' => $this->input->post('credit_voucher_invoice_serial'),
            );
            $emergency = $this->db->insert('credit_voucher_invoice', $invoice_data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'credit Voucher added and credit Voucher Id is=' . $credit_voucher_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_credit_voucher_id'] = $credit_voucher_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function print_credit_voucher()
    {
        $page_data = array(
            'page_name' => 'credit_voucher/print_credit_voucher',
            'page_title' => 'Print credit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_credit_voucher_with_id($print_credit_voucher_id)
    {
        $sdata['print_credit_voucher_id'] = $print_credit_voucher_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'credit_voucher/print_credit_voucher',
            'page_title' => 'Print credit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function save_credit_voucher_edit_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'credit_account_id' => $this->input->post('credit_account_id'),
                'director_id' => $this->input->post('director_id'),
                'type' => $this->input->post('type'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'bank_account_id' => $this->input->post('bank_account_id'),
                'check_number' => $this->input->post('check_number'),
                'purpose' => $this->input->post('purpose'),
                'bank_details' => $this->input->post('bank_details'),
                'credit_voucher_no' => $this->input->post('credit_voucher_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_amount' => $this->input->post('total_amount'),
            );
            $credit_voucher_id = $this->input->post('credit_voucher_id');
            $credit_voucher = $this->db->where('credit_voucher_id', $credit_voucher_id)->update('credit_voucher', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Credit Voucher Edited and Credit Voucher Id is=' . $credit_voucher_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_credit_voucher_id'] = $credit_voucher_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }


    public function credit_vocher_delete_ajax()
    {
        $credit_voucher_id = $this->input->post('credit_voucher_id');
        if ($this->db->where('credit_voucher_id', $credit_voucher_id)->delete('credit_voucher')) {
            $response = array('status' => 'success', 'message' => 'Debit voucher deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }
}
