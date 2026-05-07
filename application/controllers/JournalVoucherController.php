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

class JournalVoucherController extends CI_Controller
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
    public function description_load()
    {
        $parameter = $this->input->post('parameter');
        $data_descriptions = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $descriptions = $this->db->select("description AS description")
                ->like('description', $parameter)
                ->get('journal_vouchers')
                ->result();

            // Extract discount_reference
            foreach ($descriptions as $value) {
                $data_descriptions[] = $value->description;
            }
        }

        echo json_encode($data_descriptions);
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
    public function journal_voucher_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('journal_voucher_no', $parameter)
                ->from('journal_vouchers');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('journal_vouchers');
        }
        $sql = $this->db->get()->result();
        $data_journal_voucher = array();
        foreach ($sql as $value) {
            array_push($data_journal_voucher, $value->journal_voucher_no);
        }
        echo json_encode($data_journal_voucher);
    }
    private $defaults = array();

    public function index() {}
    public function view_journal_voucher()
    {

        $journal_voucher_no = $this->input->post('journal_voucher_no');
        $debit_account_id = $this->input->post('debit_account_id');
        $credit_account_id = $this->input->post('credit_account_id');

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] =  base_url() . "index.php/journalVoucherController/view_journal_voucher";
        $config['total_rows'] = $this->JournalVoucherModel->count_all_journal_vouchers($journal_voucher_no, $debit_account_id, $credit_account_id, $from_date, $to_date);
        $config['per_page'] = 30;
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

        $data['journal_voucher_data'] = $this->JournalVoucherModel->get_journal_vouchers($this->per_page, $page, $journal_voucher_no, $debit_account_id, $credit_account_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('journal_voucher/view_journal_voucher', $data, true);
        $page_data = array(
            'page_name' => 'journal_voucher/view_journal_voucher',
            'page_title' => 'View Issue',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_journal_voucher()
    {

        $page_data = array(
            'page_name' => 'journal_voucher/add_journal_voucher',
            'page_title' => 'Add journal Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_journal_voucher($journal_voucher_id)
    {
        $data['journal_voucher_id'] = $journal_voucher_id;
        $this->load->view('journal_voucher/edit_journal_voucher', $data, true);
        $page_data = array(
            'page_name' => 'journal_voucher/edit_journal_voucher',
            'page_title' => 'Edit journal Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_journal_voucher_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'debit_account_id' => $this->input->post('debit_account_id'),
                'credit_account_id' => $this->input->post('credit_account_id'),
                'description' => $this->input->post('description'),
                'journal_voucher_no' => $this->input->post('journal_voucher_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_amount' => $this->input->post('total_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $journal_voucher = $this->db->insert('journal_vouchers', $data);

            $journal_voucher_id = $this->db->insert_id();



            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'journal_voucher_no' => $this->input->post('journal_voucher_no'),
                'journal_voucher_invoice_serial' => $this->input->post('journal_voucher_invoice_serial'),
            );
            $emergency = $this->db->insert('journal_voucher_invoices', $invoice_data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'journal Voucher added and journal Voucher Id is=' . $journal_voucher_id
            );
            $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_journal_voucher_id'] = $journal_voucher_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function print_journal_voucher()
    {
        $page_data = array(
            'page_name' => 'journal_voucher/print_journal_voucher',
            'page_title' => 'Print journal Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_journal_voucher_with_id($print_journal_voucher_id)
    {
        $sdata['print_journal_voucher_id'] = $print_journal_voucher_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'journal_voucher/print_journal_voucher',
            'page_title' => 'Print journal Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function save_journal_voucher_edit_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'debit_account_id' => $this->input->post('debit_account_id'),
                'credit_account_id' => $this->input->post('credit_account_id'),
                'description' => $this->input->post('description'),
                'journal_voucher_no' => $this->input->post('journal_voucher_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_amount' => $this->input->post('total_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $journal_voucher_id = $this->input->post('journal_voucher_id');
            $journal_voucher = $this->db->where('journal_voucher_id', $journal_voucher_id)->update('journal_vouchers', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'journal Voucher Edited and journal Voucher Id is=' . $journal_voucher_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_journal_voucher_id'] = $journal_voucher_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }


    public function journal_vocher_delete_ajax()
    {
        $journal_voucher_id = $this->input->post('journal_voucher_id');
        if ($this->db->where('journal_voucher_id', $journal_voucher_id)->delete('journal_vouchers')) {
            $response = array('status' => 'success', 'message' => 'Debit voucher deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }
}
