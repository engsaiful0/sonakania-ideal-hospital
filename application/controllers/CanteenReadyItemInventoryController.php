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

class CanteenReadyItemInventoryController extends CI_Controller
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


    public function add_inventory()
    {
        $page_data = array(
            'page_name' => 'canteen_inventory/add_inventory',
            'page_title' => 'Add Sell',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_canteen_ready_item_inventory()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'canteen_ready_item_inventory_invoice_no' => $this->input->post('canteen_ready_item_inventory_invoice_no'),
                'purpose' => $this->input->post('purpose'),
                'grand_total_amount' => $this->input->post('grand_total_amount'),
                'grand_total_quantity' => $this->input->post('grand_total_quantity'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'grand_total_amount' => $this->input->post('grand_total_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $canteen_ready_item_inventory = $this->db->insert('canteen_ready_item_inventory', $data);
            $canteen_ready_item_inventory_id  = $this->db->insert_id();

            $canteen_ready_item_id = $this->input->post('canteen_ready_item_id');
            $quantity = $this->input->post('quantity');
            $unit_id = $this->input->post('unit_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');

            $canteen_ready_item_sell_details = array();
            for ($loop = 0; $loop < count($canteen_ready_item_id); $loop++) {
                if ($quantity[$loop] == ''||$quantity[$loop] == 0) {
                    continue;
                }
                $canteen_ready_item_sell_details[] = array(
                    'canteen_ready_item_id ' => $canteen_ready_item_id[$loop],
                    'unit_id ' => $unit_id[$loop],
                    'price ' => $price[$loop],
                    'canteen_ready_item_inventory_id ' => $canteen_ready_item_inventory_id,
                    'canteen_ready_item_inventory_invoice_no' => $this->input->post('canteen_ready_item_inventory_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
                $price_update=array(
                    'price'=>$price[$loop]
                );
                $this->db->where('canteen_ready_item_id',$canteen_ready_item_id[$loop])->update('canteen_ready_items',$price_update);
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'canteen_ready_item_inventory_invoice_no' => $this->input->post('canteen_ready_item_inventory_invoice_no'),
                'canteen_ready_item_inventory_invoice_serial' => $this->input->post('canteen_ready_item_inventory_invoice_serial'),
            );
            $this->db->insert('canteen_ready_item_inventory_invoice', $invoice_data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Ready Item Inventory added and Canteen Ready Item Inventory Id is=' . $canteen_ready_item_inventory_id
            );
            $this->db->insert('activity_log', $activity_data);

            $this->db->insert_batch('canteen_ready_item_inventory_details', $canteen_ready_item_sell_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_ready_item_inventory_id'] = $canteen_ready_item_inventory_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function cantenn_ready_item_inventory_delete_ajax()
    {
        $canteen_ready_item_inventory_id = $this->input->post('canteen_ready_item_inventory_id');
        if ($this->db->where('canteen_ready_item_inventory_id', $canteen_ready_item_inventory_id)->delete('canteen_ready_item_inventory')) {
            $this->db->where('canteen_ready_item_inventory_id', $canteen_ready_item_inventory_id)->delete('canteen_ready_item_inventory_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    public function update_canteen_ready_item_inventory()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $canteen_ready_item_inventory_id = $this->input->post('canteen_ready_item_inventory_id');
            $canteen_ready_item_inventory = $this->db->where('canteen_ready_item_inventory_id', $canteen_ready_item_inventory_id)->get('canteen_ready_item_inventory')->row();
            $data = array(
                'canteen_ready_item_inventory_invoice_no' => $this->input->post('canteen_ready_item_inventory_invoice_no'),
                'purpose' => $this->input->post('purpose'),
                'grand_total_amount' => $this->input->post('grand_total_amount'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'grand_total_quantity' => $this->input->post('grand_total_quantity'),
            );
            $this->db->where('canteen_ready_item_inventory_id', $canteen_ready_item_inventory_id)->update('canteen_ready_item_inventory', $data);


            $canteen_ready_item_id = $this->input->post('canteen_ready_item_id');
            $quantity = $this->input->post('quantity');
            $unit_id = $this->input->post('unit_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $total_amount = $this->input->post('total_amount');

            $this->db->where('canteen_ready_item_inventory_id', $canteen_ready_item_inventory_id)->delete('canteen_ready_item_inventory_details');

            for ($loop = 0; $loop < count($canteen_ready_item_id); $loop++) {
                if ($quantity[$loop] == ''||$quantity[$loop] == 0) {
                    continue;
                }
                $canteen_ready_item_sell_details[] = array(
                    'canteen_ready_item_id ' => $canteen_ready_item_id[$loop],
                    'unit_id ' => $unit_id[$loop],
                    'price ' => $price[$loop],
                    'canteen_ready_item_inventory_id ' => $canteen_ready_item_inventory_id,
                    'canteen_ready_item_inventory_invoice_no' => $this->input->post('canteen_ready_item_inventory_invoice_no'),
                    'quantity' => $quantity[$loop],
                    'total_amount' => $total_amount[$loop],
                    'user_id' => $canteen_ready_item_inventory->user_id,
                );
                $price_update=array(
                    'price'=>$price[$loop]
                );
                $this->db->where('canteen_ready_item_id',$canteen_ready_item_id[$loop])->update('canteen_ready_items',$price_update);
            }
            $this->db->insert_batch('canteen_ready_item_inventory_details', $canteen_ready_item_sell_details);
            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'canteen_ready_item_inventory_invoice_no' => $this->input->post('canteen_ready_item_inventory_invoice_no'),
                'canteen_ready_item_inventory_invoice_serial' => $this->input->post('canteen_ready_item_inventory_invoice_serial'),
            );
            $this->db->insert('canteen_ready_item_inventory_invoice', $invoice_data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Canteen Ready Item Inventory updated and Canteen Ready Item Inventory Id is=' . $canteen_ready_item_inventory_id
            );
            $this->db->insert('activity_log', $activity_data);

          
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_canteen_ready_item_inventory_id'] = $canteen_ready_item_inventory_id;
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function print_canteen_ready_item_inventory()
    {
        $page_data = array(
            'page_name' => 'canteen_inventory/print_canteen_ready_item_inventory',
            'page_title' => 'Print Inventory',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    function print_canteen_ready_item_inventory_with_id($canteen_ready_item_inventory_id)
    {
        $sdata['print_canteen_ready_item_inventory_id'] = $canteen_ready_item_inventory_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'canteen_inventory/print_canteen_ready_item_inventory',
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
    public function canteen_ready_item_inventory_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('canteen_ready_item_inventory_invoice_no', $parameter)
                ->from('canteen_ready_item_inventory');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('canteen_ready_item_inventory');
        }
        $sql = $this->db->get()->result();
        $ready_item_inventory = array();
        foreach ($sql as $value) {
            array_push($ready_item_inventory, $value->canteen_ready_item_inventory_invoice_no);
        }
        if (count($ready_item_inventory) == 0) {
            $ready_item_inventory = array('No invoice ID found!');
        }
        echo json_encode($ready_item_inventory);
    }
    function edit_canteen_ready_item_inventory($canteen_ready_item_inventory_id)
    {
        $data['canteen_ready_item_inventory_id'] = $canteen_ready_item_inventory_id;
        $this->load->view('canteen_inventory/edit_canteen_ready_item_inventory', $data, true);
        $page_data = array(
            'page_name' => 'canteen_inventory/edit_canteen_ready_item_inventory',
            'page_title' => 'Edit Purchase Goods',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function view_inventory()
    {
        $canteen_ready_item_sell_invoice_no = $this->input->post('canteen_ready_item_sell_invoice_no');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/CanteenReadyItemInventoryController/view_inventory";
        $config['total_rows'] = $this->CanteenReadyItemInventoryModel->count_all_canteen_ready_item_inventory($canteen_ready_item_sell_invoice_no, $from_date, $to_date);
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

        $data['canteen_ready_item_inventory_data'] = $this->CanteenReadyItemInventoryModel->get_canteen_ready_item_inventory($this->per_page, $page, $canteen_ready_item_sell_invoice_no, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('canteen_inventory/view_inventory', $data, true);
        $page_data = array(
            'page_name' => 'canteen_inventory/view_inventory',
            'page_title' => 'View Canteen Ready Item Sell',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
