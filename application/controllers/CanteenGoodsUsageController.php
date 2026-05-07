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

class CanteenGoodsUsageController extends CI_Controller
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
    }
    public function stock_check()
    {


        // Get the 'id' from the POST request
        $id = $this->input->post('id');
        $quantity_or_weight = $this->input->post('quantity_or_weight');

        $this->db->select_sum('quantity');
        $this->db->where('canteen_raw_goods_id', $id);
        $query = $this->db->get('canteen_purchase_goods_details'); // replace 'your_table_name' with the actual table name
        $result = $query->row();
        $total_purchase_quantity = $result->quantity;

        $this->db->select_sum('quantity_or_weight');
        $this->db->where('canteen_raw_goods_id', $id);
        $query = $this->db->get('canteen_goods_usage_details'); // replace 'your_table_name' with the actual table name
        $result = $query->row();
        $total_quantity_or_weight = $result->quantity_or_weight;

        $total_stock = $total_purchase_quantity - $total_quantity_or_weight;
        if ($total_stock >= $quantity_or_weight) {
            // Send response back to AJAX
            echo json_encode(["status" => "success", "id" => $id]); // Example response
        } else {
            echo json_encode(["status" => "false", "id" => $id]); // Example response
        }
    }

    public function add_canteen_goods_usage()
    {
        $page_data = array(
            'page_name' => 'canteen_goods_usage/add_canteen_goods_usage',
            'page_title' => 'Add Canteen Goods Usage',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_canteen_goods_usage()
    {
        $canteen_goods_usage_invoice_no = $this->input->post('canteen_goods_usage_invoice_no');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/CanteenGoodsUsageController/view_canteen_goods_usage";
        $config['total_rows'] = $this->CanteenGoodsUsageModel->count_all_canteen_goods_usage($canteen_goods_usage_invoice_no, $from_date, $to_date);
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

        $data['canteen_goods_usage_data'] = $this->CanteenGoodsUsageModel->get_canteen_goods_usage($this->per_page, $page, $canteen_goods_usage_invoice_no, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('canteen_goods_usage/view_canteen_goods_usage', $data, true);
        $page_data = array(
            'page_name' => 'canteen_goods_usage/view_canteen_goods_usage',
            'page_title' => 'View Canteen Goods Usage',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function update_canteen_goods_usage()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $canteen_goods_usage_id = $this->input->post('canteen_goods_usage_id');
            $canteen_goods_usage= $this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->get('canteen_goods_usage')->row();
            // Get form data
            $data = array(
                'canteen_goods_usage_invoice_no' => $this->input->post('canteen_goods_usage_invoice_no'),
                'purpose' => $this->input->post('purpose'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->update('canteen_goods_usage', $data);


            $canteen_raw_goods_id = $this->input->post('canteen_raw_goods_id');
            $quantity_or_weight = $this->input->post('quantity_or_weight');
            $this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->delete('canteen_goods_usage_details');

            $canteen_goods_usage_details = array();
            for ($loop = 0; $loop < count($quantity_or_weight); $loop++) {
                if ($quantity_or_weight[$loop] == '' || $quantity_or_weight[$loop] == 0) {
                    continue;
                }
                $canteen_goods_usage_details[] = array(
                    'canteen_raw_goods_id ' => $canteen_raw_goods_id[$loop],
                    'canteen_goods_usage_id ' => $canteen_goods_usage_id,
                    'canteen_goods_usage_invoice_no ' =>  $this->input->post('canteen_goods_usage_invoice_no'),
                    'quantity_or_weight ' => $quantity_or_weight[$loop],
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'user_id' => $canteen_goods_usage->user_id,
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'canteen_goods_usage_invoice_no' => $this->input->post('canteen_goods_usage_invoice_no'),
                'canteen_goods_usage_invoice_serial' => $this->input->post('canteen_goods_usage_invoice_serial'),

            );
            $this->db->insert('canteen_goods_usage_invoice', $invoice_data);
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Goods Usage updated and Canteen Goods Usage Id is=' . $canteen_goods_usage_id
            );
            $this->db->insert('activity_log', $activity_data);

            $this->db->insert_batch('canteen_goods_usage_details', $canteen_goods_usage_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_goods_usage_id'] = $canteen_goods_usage_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function save_canteen_goods_usage()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'canteen_goods_usage_invoice_no' => $this->input->post('canteen_goods_usage_invoice_no'),
                'purpose' => $this->input->post('purpose'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'user_id' => $this->session->userdata('user_id'),
            );
            $canteen_goods_usage_save = $this->db->insert('canteen_goods_usage', $data);
            $canteen_goods_usage_id  = $this->db->insert_id();

            $canteen_raw_goods_id = $this->input->post('canteen_raw_goods_id');
            $quantity_or_weight = $this->input->post('quantity_or_weight');

            $canteen_goods_usage_details = array();
            for ($loop = 0; $loop < count($quantity_or_weight); $loop++) {
                if ($quantity_or_weight[$loop] == '' || $quantity_or_weight[$loop] == 0) {
                    continue;
                }
                $canteen_goods_usage_details[] = array(
                    'canteen_raw_goods_id ' => $canteen_raw_goods_id[$loop],
                    'canteen_goods_usage_id ' => $canteen_goods_usage_id,
                    'canteen_goods_usage_invoice_no ' =>  $this->input->post('canteen_goods_usage_invoice_no'),
                    'quantity_or_weight ' => $quantity_or_weight[$loop],
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'user_id' => $this->session->userdata('user_id'),
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'canteen_goods_usage_invoice_no' => $this->input->post('canteen_goods_usage_invoice_no'),
                'canteen_goods_usage_invoice_serial' => $this->input->post('canteen_goods_usage_invoice_serial'),

            );
            $this->db->insert('canteen_goods_usage_invoice', $invoice_data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Goods Usage added and Canteen Goods Usage Id is=' . $canteen_goods_usage_id
            );
            $this->db->insert('activity_log', $activity_data);

            $this->db->insert_batch('canteen_goods_usage_details', $canteen_goods_usage_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_goods_usage_id'] = $canteen_goods_usage_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function print_canteen_goods_usage()
    {
        $page_data = array(
            'page_name' => 'canteen_goods_usage/print_canteen_goods_usage',
            'page_title' => 'Print Inventory',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    function print_canteen_goods_usage_with_id($canteen_goods_usage_id)
    {
        $sdata['print_canteen_goods_usage_id'] = $canteen_goods_usage_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'canteen_goods_usage/print_canteen_goods_usage',
            'page_title' => 'Print Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
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
    public function canteen_goods_usage_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('canteen_goods_usage_invoice_no', $parameter)
                ->from('canteen_goods_usage');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('canteen_goods_usage');
        }
        $sql = $this->db->get()->result();
        $canteen_goods_usage_invoice = array();
        foreach ($sql as $value) {
            array_push($canteen_goods_usage_invoice, $value->canteen_goods_usage_invoice_no);
        }
        if (count($canteen_goods_usage_invoice) == 0) {
            $ready_item_inventory = array('No invoice ID found!');
        }
        echo json_encode($canteen_goods_usage_invoice);
    }
    function edit_canteen_goods_usage($canteen_goods_usage_id)
    {
        $data['canteen_goods_usage_id'] = $canteen_goods_usage_id;
        $this->load->view('canteen_goods_usage/edit_canteen_goods_usage', $data, true);
        $page_data = array(
            'page_name' => 'canteen_goods_usage/edit_canteen_goods_usage',
            'page_title' => 'Edit Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function canteen_goods_usage_delete_ajax()
    {
        $canteen_goods_usage_id = $this->input->post('canteen_goods_usage_id');
        if ($this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->delete('canteen_goods_usage')) {
            $this->db->where('canteen_goods_usage_id', $canteen_goods_usage_id)->delete('canteen_goods_usage_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
