<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class MedicinePurchaseReturnController extends CI_Controller
{
    private $per_page;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    public function medicine_purchase_return_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('medicine_purchase_return_invoice_no', $parameter)
                ->from('medicine_purchase_return');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('medicine_purchase_return');
        }
        $sql = $this->db->get()->result();
        $data_medicine_purchase_return = array();
        foreach ($sql as $value) {
            array_push($data_medicine_purchase_return, $value->medicine_purchase_return_invoice_no);
        }
        echo json_encode($data_medicine_purchase_return);
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


    function medicine_purchase_return($medicine_purchase_id)
    {
        $data['medicine_purchase_id'] = $medicine_purchase_id;
        $this->load->view('pharmacy/medicine_purchase/medicine_purchase_return', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/medicine_purchase_return',
            'page_title' => 'Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_medicine_purchase_return()
    {
        $medicine_purchase_return_invoice_no = $this->input->post('medicine_purchase_return_invoice_no') ?: '';
        $supplier_id = $this->input->post('supplier_id') ?: '';
        $from_date = '';
        $to_date = '';
        $config = array();
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/MedicinePurchaseReturnController/view_medicine_purchase_return";
        $config["total_rows"] = $this->MedicinePurchaseReturnModel->count_all_medicine_purchase_return($medicine_purchase_return_invoice_no, $supplier_id, $from_date, $to_date);
        $config["per_page"] = 100;
        $config["uri_segment"] = 3;
        $config['num_links'] = 3;
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
        $data['detailsList'] = $this->MedicinePurchaseReturnModel->get_medicine_purchase_return_details($this->per_page, $page, $medicine_purchase_return_invoice_no, $supplier_id, $from_date, $to_date);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'pharmacy/medicine_purchase/view_medicine_purchase_return';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }

    function medicine_purchase_return_edit($medicine_purchase_return_id)
    {
        $data['medicine_purchase_return_id'] = $medicine_purchase_return_id;
        $this->load->view('pharmacy/medicine_purchase/medicine_purchase_return_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/medicine_purchase_return_edit',
            'page_title' => 'Medicine Purchase Edit',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_medicine_purchase_return()
    {
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/print_medicine_purchase_return',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_medicine_purchase_return_again($medicine_purchase_return_id)
    {
        $data['medicine_purchase_return_id'] = $medicine_purchase_return_id;
        $this->load->view('pharmacy/medicine_purchase/print_medicine_purchase_return', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/print_medicine_purchase_return',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function medicine_purchase_return_save()
    {
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['medicine_purchase_return_invoice_no'] = $this->input->post('medicine_purchase_return_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_purchase_return_invoices', $data);

            $sales = array(
                'supplier_id' => $this->input->post('supplier_id'),
                'medicine_purchase_id' => $this->input->post('medicine_purchase_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'medicine_purchase_return_invoice_no' => $this->input->post('medicine_purchase_return_invoice_no'),
                'status' => $this->input->post('status'),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('medicine_purchase_return', $sales);
            if ($insert)
                $medicine_purchase_return_id  = $this->db->insert_id();
            $purchase_details = array();
            $drug_id = $this->input->post('drug_id');
            $purchase_rate = $this->input->post('purchase_rate');
            $purchase_quantity = $this->input->post('purchase_quantity');
            $return_quantity = $this->input->post('return_quantity');
            $amount = $this->input->post('amount');

            for ($loop = 0; $loop < count($drug_id); $loop++) {
                $purchase_details[] = array(
                    'medicine_purchase_return_id' => $medicine_purchase_return_id,
                    'medicine_purchase_id' => $this->input->post('medicine_purchase_id'),
                    'medicine_purchase_return_invoice_no' => $this->input->post('medicine_purchase_return_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'purchase_rate' => $purchase_rate[$loop],
                    'purchase_quantity' => $purchase_quantity[$loop],
                    'return_quantity' => $return_quantity[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            $this->db->insert_batch('medicine_purchase_return_details', $purchase_details);
            $sdata['print_medicine_purchase_return_id'] = $medicine_purchase_return_id;
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ipd_service_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function medicine_purchase_return_edit_save()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $medicine_purchase_return_id = $this->input->post('medicine_purchase_return_id');
            $medicine_purchase_return_previous_data=$this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)->get('medicine_purchase_return')->row();
            $sales = array(
                'supplier_id' => $this->input->post('supplier_id'),
                'medicine_purchase_id' => $this->input->post('medicine_purchase_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'medicine_purchase_return_invoice_no' => $this->input->post('medicine_purchase_return_invoice_no'),
                'status' => $this->input->post('status'),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
            );
            $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)->update('medicine_purchase_return', $sales);
            //Delete the old data of details
            $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)->delete('medicine_purchase_return_details');
            
            $purchase_details = array();
            $drug_id = $this->input->post('drug_id');
            $purchase_rate = $this->input->post('purchase_rate');
            $purchase_quantity = $this->input->post('purchase_quantity');
            $return_quantity = $this->input->post('return_quantity');
            $amount = $this->input->post('amount');

            for ($loop = 0; $loop < count($drug_id); $loop++) {
                $purchase_details[] = array(
                    'medicine_purchase_return_id' => $medicine_purchase_return_id,
                    'medicine_purchase_id' => $this->input->post('medicine_purchase_id'),
                    'medicine_purchase_return_invoice_no' => $this->input->post('medicine_purchase_return_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'purchase_rate' => $purchase_rate[$loop],
                    'purchase_quantity' => $purchase_quantity[$loop],
                    'return_quantity' => $return_quantity[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $medicine_purchase_return_previous_data->user_id,
                );
            }
            $this->db->insert_batch('medicine_purchase_return_details', $purchase_details);
            $sdata['print_medicine_purchase_return_id'] = $medicine_purchase_return_id;
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ipd_service_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    


    public function medicine_purchase_return_delete_ajax()
    {
        $medicine_purchase_return_id = $this->input->post('medicine_purchase_return_id');
        if ($this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)->delete('medicine_purchase_return')) {
            $this->db->where('medicine_purchase_return_id', $medicine_purchase_return_id)->delete('medicine_purchase_return_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
