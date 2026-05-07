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
use Laminas\Barcode\Barcode;
class CanteenSellController extends CI_Controller {
    private $per_page;
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

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
    function edit_ready_item_sell($canteen_ready_item_sell_id)
    {
        $data['canteen_ready_item_sell_id'] = $canteen_ready_item_sell_id;
        $this->load->view('canteen_sell/edit_ready_item_sell', $data, true);
        $page_data = array(
            'page_name' => 'canteen_sell/edit_ready_item_sell',
            'page_title' => 'Edit Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function canteen_ready_item_sell_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('canteen_ready_item_sell_invoice_no', $parameter)
                ->from('canteen_ready_item_sell');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('canteen_ready_item_sell');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->canteen_ready_item_sell_invoice_no);
        }
        if (count($data_patient) == 0) {
            $data_patient = array('No invoice ID found!');
        }
        echo json_encode($data_patient);
    }
    public function unit_load()
    {
        // Get the canteen_goods_id from the POST request
        $canteen_ready_item_id = $this->input->post('canteen_ready_item_id');

        // Load the model (you should have a model to interact with the database)
        $this->load->model('CanteenGoodsModel');

        // Get the unit_id from the canteen_raw_goods table
        $ready_item = $this->CanteenGoodsModel->get_canteen_ready_item_by_id($canteen_ready_item_id);

        if (!empty($ready_item)) {
            // Now get the corresponding unit_name from the units table using the unit_id
            $unit = $this->CanteenGoodsModel->get_unit_by_id($ready_item->unit_id);

            if (!empty($unit)) {
                // Return unit_id and unit_name as JSON
                $response = [
                    'unit_id' => $unit->unit_id,
                    'name' => $unit->name,
                    'price' => $ready_item->price,
                ];
            } else {
                $response = ['error' => 'Unit not found'];
            }
        } else {
            $response = ['error' => 'Ready Item not found'];
        }

        // Send the response as JSON
        echo json_encode($response);
    }
    public function index() {
        $page_data = array(
            'page_name' => 'marketting/marketting_dashboard',
            'page_title' => 'Marketting',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }
    public function more_sell_ready_item_row()
    {
        $next_id = $_POST['next_id'];
        $data = array(
            'next_id' => $next_id,
        );
        $this->load->view('canteen_sell/more_sell_ready_item_row', $data);
    }
    public function add_ready_item_sell() {
        $page_data = array(
            'page_name' => 'canteen_sell/add_ready_item_sell',
            'page_title' => 'Add Sell',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_canteen_ready_item_sell() {
        $page_data = array(
            'page_name' => 'canteen_sell/print_canteen_ready_item_sell',
            'page_title' => 'Add Sell',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
   

    function print_canteen_ready_item_sell_with_id($canteen_ready_item_sell_id)
    {
        $sdata['print_canteen_ready_item_sell_id'] = $canteen_ready_item_sell_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'canteen_sell/print_canteen_ready_item_sell',
            'page_title' => 'Print Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
 
    public function view_ready_item_sell()
    {
        $canteen_ready_item_sell_invoice_no = $this->input->post('canteen_ready_item_sell_invoice_no');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/CanteenSellController/view_canteen_ready_item_sell";
        $config['total_rows'] = $this->CanteenReadyItemSellModel->count_all_canteen_ready_item_sell($canteen_ready_item_sell_invoice_no, $from_date, $to_date);
        $config['per_page'] = 30;
        $config['uri_segment'] = 3;

        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);
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

        $data['canteen_ready_item_sell_data'] = $this->CanteenReadyItemSellModel->get_canteen_ready_item_sell($this->per_page, $page, $canteen_ready_item_sell_invoice_no, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('canteen_sell/view_ready_item_sell', $data, true);
        $page_data = array(
            'page_name' => 'canteen_sell/view_ready_item_sell',
            'page_title' => 'View Canteen Ready Item Sell',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_canteen_sell_item()
    {
         // Check if it's an AJAX request
         if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'canteen_ready_item_sell_invoice_no' => $this->input->post('canteen_ready_item_sell_invoice_no'),
                'customer_name' => $this->input->post('customer_name'),
                'purpose' => $this->input->post('purpose'),
                'total' => $this->input->post('total'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'payment_type' => $this->input->post('payment_type'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'bank_account_id' => $this->input->post('bank_account_id'),
                'check_number' => $this->input->post('check_number'),
                'bank_details' => $this->input->post('bank_details'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $canteen_ready_item_sell_save = $this->db->insert('canteen_ready_item_sell', $data);
            $canteen_ready_item_sell_id  = $this->db->insert_id();

            $canteen_ready_item_id = $this->input->post('canteen_ready_item_id');
            $unit_id = $this->input->post('unit_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');

            $canteen_ready_item_sell_details = array();
            for ($loop = 0; $loop < count($canteen_ready_item_id); $loop++) {
                $canteen_ready_item_sell_details[] = array(
                    'canteen_ready_item_id ' => $canteen_ready_item_id[$loop],
                    'unit_id ' => $unit_id[$loop],
                    'price ' => $price[$loop],
                    'canteen_ready_item_sell_id ' => $canteen_ready_item_sell_id,
                    'canteen_ready_item_sell_invoice_no' => $this->input->post('canteen_ready_item_sell_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'canteen_ready_item_sell_invoice_no' => $this->input->post('canteen_ready_item_sell_invoice_no'),
                'canteen_ready_item_sell_invoice_serial' => $this->input->post('canteen_ready_item_sell_invoice_serial'),

            );
            $this->db->insert('canteen_ready_item_sell_invoice', $invoice_data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Ready Item added and Canteen Ready Item Sell Id is=' . $canteen_ready_item_sell_id
            );
            $this->db->insert('activity_log', $activity_data);

            $this->db->insert_batch('canteen_ready_item_sell_details', $canteen_ready_item_sell_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_ready_item_sell_id'] = $canteen_ready_item_sell_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function cantenn_ready_item_sell_delete_ajax()
    {
        $canteen_ready_item_sell_id = $this->input->post('canteen_ready_item_sell_id');
        if ($this->db->where('canteen_ready_item_sell_id', $canteen_ready_item_sell_id)->delete('canteen_ready_item_sell')) {
            $this->db->where('canteen_ready_item_sell_id', $canteen_ready_item_sell_id)->delete('canteen_ready_item_sell_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    public function update_canteen_sell_item_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $canteen_ready_item_sell_id = $this->input->post('canteen_ready_item_sell_id');
            $canteen_ready_item_sell_previous_data=$this->db->where('canteen_ready_item_sell_id', $canteen_ready_item_sell_id)->get('canteen_ready_item_sell')->row();
            $data = array(
                'canteen_ready_item_sell_invoice_no' => $this->input->post('canteen_ready_item_sell_invoice_no'),
                'customer_name' => $this->input->post('customer_name'),
                'purpose' => $this->input->post('purpose'),
                'total' => $this->input->post('total'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'payment_type' => $this->input->post('payment_type'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'bank_account_id' => $this->input->post('bank_account_id'),
                'check_number' => $this->input->post('check_number'),
                'bank_details' => $this->input->post('bank_details'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
            );
            $this->db->where('canteen_ready_item_sell_id', $canteen_ready_item_sell_id)->update('canteen_ready_item_sell', $data);


            $canteen_ready_item_id = $this->input->post('canteen_ready_item_id');
            $unit_id = $this->input->post('unit_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');
            $this->db->where('canteen_ready_item_sell_id', $canteen_ready_item_sell_id)->delete('canteen_ready_item_sell_details');

            $purchase_goods_details = array();
            for ($loop = 0; $loop < count($canteen_ready_item_id); $loop++) {
                $purchase_goods_details[] = array(
                    'canteen_ready_item_id ' => $canteen_ready_item_id[$loop],
                    'unit_id ' => $unit_id[$loop],
                    'price ' => $price[$loop],
                    'canteen_ready_item_sell_id ' => $canteen_ready_item_sell_id,
                    'canteen_ready_item_sell_invoice_no' => $this->input->post('canteen_ready_item_sell_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $canteen_ready_item_sell_previous_data->user_id,
                );
            }
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Ready Item Sell Id is Updated and Canteen Ready Item Sell Id is=' . $canteen_ready_item_sell_id
            );
            $this->db->insert('activity_log', $activity_data);
            $this->db->insert_batch('canteen_ready_item_sell_details', $purchase_goods_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_ready_item_sell_id'] = $canteen_ready_item_sell_id;
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
