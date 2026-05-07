<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MedicineSaleReturnWithoutInvoiceController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');

        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('LoginController');
        }
    }
    public function return_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('return_invoice_no', $parameter)
                ->from('medicine_sale_returns_without_invoice');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('medicine_sale_returns_without_invoice');
        }
        $sql = $this->db->get()->result();
        $data_medicine_sale_return = array();
        foreach ($sql as $value) {
            array_push($data_medicine_sale_return, $value->return_invoice_no);
        }
        echo json_encode($data_medicine_sale_return);
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
    public function patient_data_load_by_name_unique_id()
    {
        $search_parameter = $this->input->post('search_parameter');

        // Search in ipd_patient table by unique ID or name
        $this->db->group_start();
        $this->db->where('patient_unique_id', $search_parameter);
        $this->db->or_where('patient_name', $search_parameter);
        $this->db->group_end();
        $ipd_patient = $this->db->get('ipd_patient')->row();

        // Search in opd_patient table by unique ID or name
        $this->db->group_start();
        $this->db->where('opd_patient_unique_id', $search_parameter);
        $this->db->or_where('opd_patient_name', $search_parameter);
        $this->db->group_end();
        $opd_patient = $this->db->get('opd_patient')->row();

        // Return ipd patient data if found
        if (!empty($ipd_patient)) {
            echo 'ipd_patient*'
                . $ipd_patient->ipd_patient_id . '*'
                . $ipd_patient->patient_name . '*'
                . $ipd_patient->mobile_number . '*'
                . $ipd_patient->age_year . '*'
                . $ipd_patient->age_month . '*'
                . $ipd_patient->age_day . '*'
                . $ipd_patient->address;
            return;
        }

        // Return opd patient data if found
        if (!empty($opd_patient)) {
            echo 'opd_patient*'
                . $opd_patient->opd_patient_id . '*'
                . $opd_patient->opd_patient_name . '*'
                . $opd_patient->mobile_number . '*'
                . $opd_patient->age_year . '*'
                . $opd_patient->age_month . '*'
                . $opd_patient->age_day . '*'
                . $opd_patient->address;
            return;
        }

        // If no patient found
        echo 'No patient found';
    }

    
    // Create new medicine sale return
    public function create()
    {
        $data = array(
            'page_name' => 'pharmacy/medicine_sale_return/create',
            'page_title' => 'Add Medicine Sale Return',
            'sidebar' => 'pharmacy/pharmacy_sidebar',
        );

        $this->load->view('content', $data);
    }

    // Store new medicine sale return
   
    public function addMoreSale()
    {
        $next_id = $_POST['next_id'];
        $this->load->view('pharmacy/medicine_sale_return/add_more_medicine_row', array('next_id' => $next_id));
    }
    function medicine_sale_return_save()
    {
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['return_invoice_no'] = $this->input->post('return_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_sale_return_invoices_without_invoice', $data);

            $sales = array(
                'name' => $this->input->post('name'),
                'age_year' => $this->input->post('age_year'),
                'age_month' => $this->input->post('age_month'),
                'age_day' => $this->input->post('age_day'),
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'opd_patient_id' => $this->input->post('opd_patient_id '),
                'address' => $this->input->post('address'),
                'remarks' => $this->input->post('remarks'),
                
                'mobile_number' => $this->input->post('mobile_number'),
                'return_invoice_no' => $this->input->post('return_invoice_no'),
                'return_date' => date('Y-m-d', strtotime($this->input->post('return_date'))),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'total_discount' => $this->input->post('total_discount'),
                'return_reference' => $this->input->post('return_reference'),
                'nettotal' => $this->input->post('nettotal'),
                'return_amount' => $this->input->post('return_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('medicine_sale_returns_without_invoice', $sales);
            if ($insert)
                $medicine_sale_return_id_without_invoice  = $this->db->insert_id();

            $sales_return_details = array();
            $drug_id = $this->input->post('drug_id_value');
            $sales_rate = $this->input->post('sales_rate');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $pur_rate = $this->input->post('pur_rate');
            $amount = $this->input->post('amount');
            for ($loop = 0; $loop < count($drug_id); $loop++) {
                if ($drug_id[$loop] == '') {
                    continue; // Skip if drug_id is empty
                }
                $sales_return_details[] = array(
                    'medicine_sale_return_id_without_invoice' => $medicine_sale_return_id_without_invoice,
                    'return_invoice_no' => $this->input->post('return_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'sales_rate' => isset($sales_rate[$loop]) ? $sales_rate[$loop] : 0,
                    'quantity' => isset($quantity[$loop]) ? $quantity[$loop] : 0,
                    'discounteach' => isset($discounteach[$loop]) ? $discounteach[$loop] : 0,
                    'pur_rate' => isset($pur_rate[$loop]) ? $pur_rate[$loop] : 0,
                    'amount' => isset($amount[$loop]) ? $amount[$loop] : 0,
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            if ($insert) {
                $insert = $this->db->insert_batch('medicine_sale_return_details_without_invocie', $sales_return_details);
            }
            $sdata['print_medicine_sale_return_id_without_invoice'] = $medicine_sale_return_id_without_invoice;
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    
    public function update($medicine_sale_return_id_without_invoice)
    {
        if ($this->input->is_ajax_request()) {
            // Update the main return record
            $sales = array(
                'name' => $this->input->post('name'),
                'age_year' => $this->input->post('age_year'),
                'age_month' => $this->input->post('age_month'),
                'age_day' => $this->input->post('age_day'),
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'opd_patient_id' => $this->input->post('opd_patient_id'),
                'address' => $this->input->post('address'),
                'remarks' => $this->input->post('remarks'),
                'mobile_number' => $this->input->post('mobile_number'),
                'return_invoice_no' => $this->input->post('return_invoice_no'),
                'return_date' => date('Y-m-d', strtotime($this->input->post('return_date'))),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'total_discount' => $this->input->post('total_discount'),
                'return_reference' => $this->input->post('return_reference'),
                'nettotal' => $this->input->post('nettotal'),
                'return_amount' => $this->input->post('return_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $this->db->where('medicine_sale_return_id_without_invoice', $medicine_sale_return_id_without_invoice)
                ->update('medicine_sale_returns_without_invoice', $sales);

            // Delete the old details
            $this->db->where('medicine_sale_return_id_without_invoice', $medicine_sale_return_id_without_invoice)
                ->delete('medicine_sale_return_details_without_invocie');

            // Insert new details
            $sales_return_details = array();
            $drug_id = $this->input->post('drug_id_value');
            $sales_rate = $this->input->post('sales_rate');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $pur_rate = $this->input->post('pur_rate');
            $amount = $this->input->post('amount');
            
            for ($loop = 0; $loop < count($drug_id); $loop++) {
                if ($drug_id[$loop] == '') {
                    continue; // Skip if drug_id is empty
                }
                $sales_return_details[] = array(
                    'medicine_sale_return_id_without_invoice' => $medicine_sale_return_id_without_invoice,
                    'return_invoice_no' => $this->input->post('return_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'sales_rate' => isset($sales_rate[$loop]) ? $sales_rate[$loop] : 0,
                    'quantity' => isset($quantity[$loop]) ? $quantity[$loop] : 0,
                    'discounteach' => isset($discounteach[$loop]) ? $discounteach[$loop] : 0,
                    'pur_rate' => isset($pur_rate[$loop]) ? $pur_rate[$loop] : 0,
                    'amount' => isset($amount[$loop]) ? $amount[$loop] : 0,
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            
            if (!empty($sales_return_details)) {
                $this->db->insert_batch('medicine_sale_return_details_without_invocie', $sales_return_details);
            }
            
            $response = array('success' => true, 'message' => 'Data updated successfully.');
            echo json_encode($response);
        } else {
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    
    public function edit($medicine_sale_return_id_without_invoice)
    {
        // Get the medicine sale return data
        $medicine_sale_return = $this->db->where('medicine_sale_return_id_without_invoice', $medicine_sale_return_id_without_invoice)
            ->get('medicine_sale_returns_without_invoice')
            ->row();
        
        if (!$medicine_sale_return) {
            redirect('MedicineSaleReturnWithoutInvoiceController/view_medicine_sale_return');
            return;
        }
        
        // Get the medicine sale return details
        $medicine_sale_return_details = $this->db->where('medicine_sale_return_id_without_invoice', $medicine_sale_return_id_without_invoice)
            ->get('medicine_sale_return_details_without_invocie')
            ->result();
        
        $data = array(
            'medicine_sale_return' => $medicine_sale_return,
            'medicine_sale_return_details' => $medicine_sale_return_details,
            'medicine_sale_return_id_without_invoice' => $medicine_sale_return_id_without_invoice,
            'page_name' => 'pharmacy/medicine_sale_return/edit',
            'page_title' => 'Edit Medicine Sale Return',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        
        $this->load->view('content', $data);
    }
    
    public function medicin_sale_return_edit($sales_id)
    {
        $data['sales_id'] = $sales_id;
        $this->load->view('pharmacy/medicine_sale_return/edit', $data);
    }

    public function print_medicine_sale_return_without_invoice()
    {
        // Get the medicine sale return ID from session
        $medicine_sale_return_id_without_invoice = $this->session->userdata('print_medicine_sale_return_id_without_invoice');
        
        if (!$medicine_sale_return_id_without_invoice) {
            // If no session data, redirect to view page
            redirect('MedicineSaleReturnWithoutInvoiceController/view_medicine_sale_return');
            return;
        }
        
        $data['medicine_sale_return_id_without_invoice'] = $medicine_sale_return_id_without_invoice;
        
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale_return/print_medicine_sale_return_without_invoice',
            'page_title' => 'Print Medicine Sale Return',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        
        // Merge data with page_data
        $page_data = array_merge($page_data, $data);
        
        $this->load->view('content', $page_data);
    }

    public function print_medicine_sale_return_without_invoice_again($medicine_sale_return_id_without_invoice)
    {
        $data['medicine_sale_return_id_without_invoice'] = $medicine_sale_return_id_without_invoice;
        $sdata['print_medicine_sale_return_id_without_invoice'] = $medicine_sale_return_id_without_invoice;
        $this->session->set_userdata($sdata);

        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale_return/print_medicine_sale_return_without_invoice',
            'page_title' => 'Print Medicine Sale Return',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        
        // Merge data with page_data
        $page_data = array_merge($page_data, $data);
        
        $this->load->view('content', $page_data);
    }

    public function view_medicine_sale_return()
    {
        $return_invoice_no = $this->input->post('return_invoice_no') ?: '';
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
        $config['base_url'] = base_url() . "index.php/MedicineSaleReturnWithoutInvoiceController/view_medicine_sale_return";
        $config['total_rows'] = $this->MedicineSaleReturnWithoutInvoiceModel->count_all_medicine_sale_return($return_invoice_no, $from_date, $to_date);
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
        $data['detailsList'] = $this->MedicineSaleReturnWithoutInvoiceModel->get_medicine_sale_return_details($this->per_page, $page, $return_invoice_no, $from_date, $to_date);

        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $data['page_name'] = 'pharmacy/medicine_sale_return/view_medicine_sale_return';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }

    public function medicine_sale_return_delete_ajax()
    {
        $medicine_sale_return_id_without_invoice = $this->input->post('medicine_sale_return_id_without_invoice');
        if ($this->db->where('medicine_sale_return_id_without_invoice', $medicine_sale_return_id_without_invoice)->delete('medicine_sale_returns_without_invoice')) {
            $this->db->where('medicine_sale_return_id_without_invoice', $medicine_sale_return_id_without_invoice)->delete('medicine_sale_return_details_without_invocie');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    
    
}
