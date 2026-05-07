<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseGoodsController
 *
 * @author Lenovo
 */

use Laminas\Barcode\Barcode;

class PurchaseGoodsController extends CI_Controller
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

    private $defaults = array();
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

    public function item_price_load()
    {
        $item_id = $this->input->post('itemId'); // Use CI's input class for security
        $item = $this->db->select('unit_price')
            ->from('item')
            ->where('item_id', $item_id)
            ->get()
            ->row();

        if ($item) {
            echo $item->unit_price;
        } else {
            echo 0; // or handle error appropriately
        }
    }

    public function purchase_goods_invoice_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('purchase_goods_invoice_no', $parameter)
                ->from('purchase_goods');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('purchase_goods');
        }
        $sql = $this->db->get()->result();
        $data_invoice = array();
        foreach ($sql as $value) {
            array_push($data_invoice, $value->purchase_goods_invoice_no);
        }
        echo json_encode($data_invoice);
    }

    function add_purchase_goods()
    {

        $page_data = array(
            'page_name' => 'purchase_goods/add_purchase_goods',
            'page_title' => 'Purchase Goods',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function more_goods_purchase_row()
    {
        $next_id = $_POST['next_id'];
        $data = array(
            'next_id' => $next_id,
        );
        $this->load->view('purchase_goods/more_goods_purchase_row', $data);
    }
    public function view_purchase_goods()
    {

        $purchase_goods_invoice_no = $this->input->post('purchase_goods_invoice_no');
        $supplier_id = $this->input->post('supplier_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/PurchaseGoodsController/view_purchase_goods";
        $config['total_rows'] = $this->PurchaseGoodsModel->count_all_purchase_goods($purchase_goods_invoice_no, $supplier_id, $from_date, $to_date);
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

        $data['purchase_goods_data'] = $this->PurchaseGoodsModel->get_purchase_goods($this->per_page, $page, $purchase_goods_invoice_no, $supplier_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('purchase_goods/view_purchase_goods', $data, true);
        $page_data = array(
            'page_name' => 'purchase_goods/view_purchase_goods',
            'page_title' => 'View Purchase Goods',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function edit_purchase_goods($purchase_goods_id)
    {
        $data['purchase_goods_id'] = $purchase_goods_id;
        $this->load->view('purchase_goods/edit_purchase_goods', $data, true);
        $page_data = array(
            'page_name' => 'purchase_goods/edit_purchase_goods',
            'page_title' => 'Edit Purchase Goods',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_add_goods_purchase()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'purchase_goods_invoice_no' => $this->input->post('purchase_goods_invoice_no'),
                'supplier_id' => $this->input->post('supplier_id'),
                'purpose' => $this->input->post('purpose'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'total_discount' => $this->input->post('total_discount'),
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

            $purchase_goods_save = $this->db->insert('purchase_goods', $data);

            $purchase_goods_id = $this->db->insert_id();

            $item_id = $this->input->post('item_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');

            $purchase_goods_details = array();
            for ($loop = 0; $loop < count($item_id); $loop++) {
                $purchase_goods_details[] = array(
                    'item_id ' => $item_id[$loop],
                    'price ' => $price[$loop],
                    'purchase_goods_id ' => $purchase_goods_id,
                    'purchase_goods_invoice_no' => $this->input->post('purchase_goods_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'purchase_goods_invoice_no' => $this->input->post('purchase_goods_invoice_no'),
                'purchase_goods_invoice_serial' => $this->input->post('purchase_goods_invoice_serial'),

            );
            $emergency = $this->db->insert('purchase_goods_invoice', $invoice_data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Purchase Goods added and Purchase Goods Id is=' . $purchase_goods_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);

            $this->db->insert_batch('purchase_goods_details', $purchase_goods_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_purchase_goods_id'] = $purchase_goods_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    function goods_stock_report()
    {
        $page_data = array(
            'page_name' => 'purchase_goods/report/goods_stock_report',
            'page_title' => 'Print Purchase Goods Report',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_purchase_goods_with_id($purchase_goods_id)
    {
        $sdata['print_purchase_goods_id'] = $purchase_goods_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'purchase_goods/print_purchase_goods',
            'page_title' => 'Print Purchase Goods',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_purchase_goods()
    {

        $page_data = array(
            'page_name' => 'purchase_goods/print_purchase_goods',
            'page_title' => 'Print Purchase Goods',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function update_purchase_goods_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $purchase_goods_id = $this->input->post('purchase_goods_id');
            $purchase_goods_previous_data = $this->db->where('purchase_goods_id', $purchase_goods_id)->get('purchase_goods')->row();
            $data = array(
                'purchase_goods_invoice_no' => $this->input->post('purchase_goods_invoice_no'),
                'supplier_id' => $this->input->post('supplier_id'),
                'purpose' => $this->input->post('purpose'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'total_discount' => $this->input->post('total_discount'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'payment_type' => $this->input->post('payment_type'),
                'bank_id' => $this->input->post('bank_id'),
                'check_number' => $this->input->post('check_number'),
                'bank_details' => $this->input->post('bank_details'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
            );
            $issue = $this->db->where('purchase_goods_id', $purchase_goods_id)->update('purchase_goods', $data);

            $issue_id = $this->input->post('issue_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');
            $this->db->where('issue_id', $issue_id)->delete('purchase_goods_details');

            $item_id = $this->input->post('item_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');
            $this->db->where('purchase_goods_id', $purchase_goods_id)->delete('purchase_goods_details');
            $purchase_goods_details = array();
            for ($loop = 0; $loop < count($item_id); $loop++) {
                $purchase_goods_details[] = array(
                    'item_id ' => $item_id[$loop],
                    'price ' => $price[$loop],
                    'purchase_goods_invoice_no' => $this->input->post('purchase_goods_invoice_no'),
                    'purchase_goods_id ' => $purchase_goods_id,
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $purchase_goods_previous_data->user_id,
                );
            }
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Purchase Goods Updated and Purchase Goods Id is=' . $purchase_goods_id
            );
            $issue = $this->db->insert('activity_log', $activity_data);
            $this->db->insert_batch('purchase_goods_details', $purchase_goods_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['purchase_goods_id'] = $purchase_goods_id;
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function purchase_goods_delete_ajax()
    {
        $purchase_goods_id = $this->input->post('purchase_goods_id');
        if ($this->db->where('purchase_goods_id', $purchase_goods_id)->delete('purchase_goods')) {
            $this->db->where('purchase_goods_id', $purchase_goods_id)->delete('purchase_goods_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
