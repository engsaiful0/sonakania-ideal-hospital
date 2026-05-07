<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class MedicineSaleReturnController extends CI_Controller
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
    public function medicine_sale_return_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('medicine_sale_return_invoice_no', $parameter)
                ->from('medicine_sale_return');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('medicine_sale_return');
        }
        $sql = $this->db->get()->result();
        $data_medicine_sale_return = array();
        foreach ($sql as $value) {
            array_push($data_medicine_sale_return, $value->medicine_sale_return_invoice_no);
        }
        echo json_encode($data_medicine_sale_return);
    }
    public function print_medicine_sale_return()
    {
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/print_medicine_sale_return',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function print_medicine_sale_return_again($medicine_sale_return_id)
    {
        //die($medicine_sale_return_id);
        $data['medicine_sale_return_id'] = $medicine_sale_return_id;
        $this->load->view('pharmacy/medicine_sale/print_medicine_sale_return', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/print_medicine_sale_return',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_sale_return($medicine_sale_id)
    {
        $data['medicine_sale_id'] = $medicine_sale_id;
        $this->load->view('pharmacy/medicine_sale/medicine_sale_return', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/medicine_sale_return',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function medicine_sale_return_edit($medicine_sale_return_id)
    {
        $data['medicine_sale_return_id'] = $medicine_sale_return_id;
        $this->load->view('pharmacy/medicine_sale/medicine_sale_return_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/medicine_sale_return_edit',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_medicine_sale_return()
    {
        $medicine_sale_return_invoice_no = $this->input->post('medicine_sale_return_invoice_no') ?: '';
        $from_date = '';
        $to_date = '';

        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Pagination configuration
        $config['base_url'] = base_url() . "index.php/MedicineSaleReturnController/view_medicine_sale_return";
        $config['total_rows'] = $this->MedicineSaleReturnModel->count_all_medicine_sale_return($medicine_sale_return_invoice_no, $from_date, $to_date);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = 3;

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

        $this->pagination->initialize($config);

        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Get medicine sales list
        $data['detailsList'] = $this->MedicineSaleReturnModel->get_medicine_sell_return_details($this->per_page, $page, $medicine_sale_return_invoice_no, $from_date, $to_date);

        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $data['page_name'] = 'pharmacy/medicine_sale/view_medicine_sale_return';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
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
    function medicine_sale_return_save()
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['medicine_sale_return_invoice_no'] = $this->input->post('medicine_sale_return_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_sale_return_invoices', $data);

            $sales = array(
                'medicine_sale_id' => $this->input->post('medicine_sale_id'),
                'remarks' => $this->input->post('remarks'),
                'medicine_sale_return_invoice_no' => $this->input->post('medicine_sale_return_invoice_no'),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('medicine_sale_return', $sales);
            if ($insert)
                $medicine_sale_return_id  = $this->db->insert_id();

            $purchase_details = array();
            $drug_id = $this->input->post('drug_id');
            $drug_type_id = $this->input->post('drug_type_id');
            $sales_rate = $this->input->post('sales_rate');
            $sale_quantity = $this->input->post('sale_quantity');
            $return_quantity = $this->input->post('return_quantity');
            $discounteach = $this->input->post('discounteach');

            $amount = $this->input->post('amount');
            for ($loop = 0; $loop < count($drug_id); $loop++) {
                $sales_details[] = array(
                    'medicine_sale_id ' => $this->input->post('medicine_sale_id'),
                    'medicine_sale_return_id ' => $medicine_sale_return_id,
                    'medicine_sale_return_invoice_no ' => $this->input->post('medicine_sale_return_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'drug_type_id' => $drug_type_id[$loop],
                    'sales_rate' => $sales_rate[$loop],
                    'sale_quantity' => $sale_quantity[$loop],
                    'return_quantity' => $return_quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop]
                );
            }
            if ($insert) {
                $insert = $this->db->insert_batch('medicine_sale_return_details', $sales_details);
            }
            $sdata['print_medicine_sale_return_id'] = $medicine_sale_return_id;
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
    function medicine_sale_return_update_save()
    {
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['medicine_sale_return_invoice_no'] = $this->input->post('medicine_sale_return_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_sale_return_invoices', $data);
            $medicine_sale_return_id = $this->input->post('medicine_sale_return_id');
            $medicine_sale_return_previous_data = $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->get('medicine_sale_return')->row();

            $sales_return = array(
                'medicine_sale_id' => $this->input->post('medicine_sale_id'),
                'remarks' => $this->input->post('remarks'),
                'medicine_sale_return_invoice_no' => $this->input->post('medicine_sale_return_invoice_no'),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->update('medicine_sale_return', $sales_return);

            //Delete the old data of details
            $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->delete('medicine_sale_return_details');

            $purchase_details = array();
            $drug_id = $this->input->post('drug_id');
            $drug_type_id = $this->input->post('drug_type_id');
            $sales_rate = $this->input->post('sales_rate');
            $sale_quantity = $this->input->post('sale_quantity');
            $return_quantity = $this->input->post('return_quantity');
            $discounteach = $this->input->post('discounteach');

            $amount = $this->input->post('amount');
            for ($loop = 0; $loop < count($drug_id); $loop++) {
                $sales_details[] = array(
                    'medicine_sale_id ' => $this->input->post('medicine_sale_id'),
                    'medicine_sale_return_id ' => $medicine_sale_return_id,
                    'medicine_sale_return_invoice_no ' => $this->input->post('medicine_sale_return_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'drug_type_id' => $drug_type_id[$loop],
                    'sales_rate' => $sales_rate[$loop],
                    'sale_quantity' => $sale_quantity[$loop],
                    'return_quantity' => $return_quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $medicine_sale_return_previous_data->user_id,
                );
            }

            $this->db->insert_batch('medicine_sale_return_details', $sales_details);
            $sdata['print_medicine_sale_return_id'] = $medicine_sale_return_id;
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


    public function medicine_sale_return_delete_ajax()
    {
        $medicine_sale_return_id = $this->input->post('medicine_sale_return_id');
        if ($this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->delete('medicine_sale_return')) {
            $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->delete('medicine_sale_return_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
