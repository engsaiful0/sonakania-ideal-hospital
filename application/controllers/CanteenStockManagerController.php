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

class CanteenStockManagerController extends CI_Controller
{
    private $per_page;
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

    public function unit_load()
    {
        // Get the canteen_raw_goods_id from the POST request
        $canteen_raw_goods_id = $this->input->post('canteen_raw_goods_id');

        // Load the model (you should have a model to interact with the database)
        $this->load->model('CanteenGoodsModel');

        // Get the unit_id from the canteen_raw_goods table
        $goods = $this->CanteenGoodsModel->get_goods_by_id($canteen_raw_goods_id);

        if (!empty($goods)) {
            // Now get the corresponding unit_name from the units table using the unit_id
            $unit = $this->CanteenGoodsModel->get_unit_by_id($goods->unit_id);

            if (!empty($unit)) {
                // Return unit_id and unit_name as JSON
                $response = [
                    'unit_id' => $unit->unit_id,
                    'name' => $unit->name
                ];
            } else {
                $response = ['error' => 'Unit not found'];
            }
        } else {
            $response = ['error' => 'Goods not found'];
        }

        // Send the response as JSON
        echo json_encode($response);
    }


    public function ready_item_stock_list()
    {
    
        $page_data = array(
            'page_name' => 'canteen_stock_manager/ready_item_stock_list',
            'page_title' => 'Add Purchase',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function goods_stock_list()
    {
    
        $page_data = array(
            'page_name' => 'canteen_stock_manager/goods_stock_list',
            'page_title' => 'Add Purchase',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    
    public function more_purchase_canteen_goods_row()
    {
        $next_id = $_POST['next_id'];
        $data = array(
            'next_id' => $next_id,
        );
        $this->load->view('canteen_purchase/more_purchase_canteen_goods_row', $data);
    }
    public function view_canteen_purchase_goods()
    {

        $purchase_canteen_goods_invoice_no = $this->input->post('purchase_canteen_goods_invoice_no');
        $canteen_goods_supplier_id = $this->input->post('canteen_goods_supplier_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/PurchaseGoodsController/view_purchase_goods";
        $config['total_rows'] = $this->CanteenPurchaseModel->count_all_canteen_purchase_goods($purchase_canteen_goods_invoice_no, $canteen_goods_supplier_id, $from_date, $to_date);
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

        $data['purchase_goods_data'] = $this->CanteenPurchaseModel->get_canteen_purchase_goods($this->per_page, $page, $purchase_canteen_goods_invoice_no, $canteen_goods_supplier_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('canteen_purchase/view_canteen_purchase_goods', $data, true);
        $page_data = array(
            'page_name' => 'canteen_purchase/view_canteen_purchase_goods',
            'page_title' => 'View Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function edit_canteen_purchase_goods($canteen_purchase_good_id)
    {
        $data['canteen_purchase_good_id'] = $canteen_purchase_good_id;
        $this->load->view('canteen_purchase/edit_canteen_purchase_goods', $data, true);
        $page_data = array(
            'page_name' => 'canteen_purchase/edit_canteen_purchase_goods',
            'page_title' => 'Edit Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_canteen_goods_purchase()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'purchase_canteen_goods_invoice_no' => $this->input->post('purchase_canteen_goods_invoice_no'),
                'canteen_goods_supplier_id' => $this->input->post('canteen_goods_supplier_id'),
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

            $canteen_purchase_goods_save = $this->db->insert('canteen_purchase_goods', $data);

            $canteen_purchase_good_id = $this->db->insert_id();

            $canteen_raw_goods_id = $this->input->post('canteen_raw_goods_id');
            $unit_id = $this->input->post('unit_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');

            $purchase_goods_details = array();
            for ($loop = 0; $loop < count($canteen_raw_goods_id); $loop++) {
                $purchase_goods_details[] = array(
                    'canteen_raw_goods_id ' => $canteen_raw_goods_id[$loop],
                    'unit_id ' => $unit_id[$loop],
                    'price ' => $price[$loop],
                    'canteen_purchase_good_id ' => $canteen_purchase_good_id,
                    'purchase_canteen_goods_invoice_no' => $this->input->post('purchase_canteen_goods_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'purchase_canteen_goods_invoice_no' => $this->input->post('purchase_canteen_goods_invoice_no'),
                'purchase_canteen_goods_invoice_serial' => $this->input->post('purchase_canteen_goods_invoice_serial'),

            );
            $this->db->insert('purchase_canteen_goods_invoice', $invoice_data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Purchase Goods added and Canteen Purchase Goods Id is=' . $canteen_purchase_good_id
            );
            $this->db->insert('activity_log', $activity_data);

            $this->db->insert_batch('canteen_purchase_goods_details', $purchase_goods_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_purchase_good_id'] = $canteen_purchase_good_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
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
    public function cantenn_purchase_goods_delete_ajax()
    {
        $canteen_purchase_good_id = $this->input->post('canteen_purchase_good_id');
        if ($this->db->where('canteen_purchase_good_id', $canteen_purchase_good_id)->delete('canteen_purchase_goods')) {
            $this->db->where('canteen_purchase_good_id', $canteen_purchase_good_id)->delete('canteen_purchase_goods_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    public function update_purchase_goods_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $canteen_purchase_good_id = $this->input->post('canteen_purchase_good_id');
            $data = array(
                'purchase_canteen_goods_invoice_no' => $this->input->post('purchase_canteen_goods_invoice_no'),
                'canteen_goods_supplier_id' => $this->input->post('canteen_goods_supplier_id'),
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
            $this->db->where('canteen_purchase_good_id', $canteen_purchase_good_id)->update('canteen_purchase_goods', $data);


            $canteen_raw_goods_id = $this->input->post('canteen_raw_goods_id');
            $unit_id = $this->input->post('unit_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');
            $this->db->where('canteen_purchase_good_id', $canteen_purchase_good_id)->delete('canteen_purchase_goods_details');

            $purchase_goods_details = array();
            for ($loop = 0; $loop < count($canteen_raw_goods_id); $loop++) {
                $purchase_goods_details[] = array(
                    'canteen_raw_goods_id ' => $canteen_raw_goods_id[$loop],
                    'unit_id ' => $unit_id[$loop],
                    'price ' => $price[$loop],
                    'canteen_purchase_good_id ' => $canteen_purchase_good_id,
                    'purchase_canteen_goods_invoice_no' => $this->input->post('purchase_canteen_goods_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Purchase Goods Id is Updated and Canteen Purchase Goods Id is Id is=' . $canteen_purchase_good_id
            );
            $this->db->insert('activity_log', $activity_data);
            $this->db->insert_batch('canteen_purchase_goods_details', $purchase_goods_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_purchase_good_id'] = $canteen_purchase_good_id;
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    function print_canteen_purchase_goods_with_id($canteen_purchase_good_id)
    {
        $sdata['print_canteen_purchase_good_id'] = $canteen_purchase_good_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'canteen_purchase/print_canteen_purchase_goods',
            'page_title' => 'Print Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_canteen_purchase_goods()
    {
        $page_data = array(
            'page_name' => 'canteen_purchase/print_canteen_purchase_goods',
            'page_title' => 'Print Canteen Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function purchase_canteen_goods_invoice_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('purchase_canteen_goods_invoice_no_load', $parameter)
                ->from('canteen_purchase_goods');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('canteen_purchase_goods');
        }
        $sql = $this->db->get()->result();
        $data_invoice = array();
        foreach ($sql as $value) {
            array_push($data_invoice, $value->purchase_canteen_goods_invoice_no_load);
        }
        echo json_encode($data_invoice);
    }
    
}
