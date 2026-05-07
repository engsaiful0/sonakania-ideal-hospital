<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class MedicineSaleController extends CI_Controller
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
    public function patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        $data_patient = array();
        $ipd_results = '';
        $opd_results = '';
        if (!empty($parameter)) {
            // Query with condition for ipd_patient
            $this->db->select('patient_unique_id')
                ->like('patient_unique_id', $parameter)
                ->from('ipd_patient');
            $ipd_results = $this->db->get()->result();

            // Query with condition for opd_patient
            $this->db->select('opd_patient_unique_id as patient_unique_id')
                ->like('opd_patient_unique_id', $parameter)
                ->from('opd_patient');
            $opd_results = $this->db->get()->result();
        } else {
            // Query without condition for ipd_patient
            $this->db->select('patient_unique_id')
                ->from('ipd_patient');
            $ipd_results = $this->db->get()->result();

            // Query without condition for opd_patient
            $this->db->select('opd_patient_unique_id as patient_unique_id')
                ->from('opd_patient');
            $opd_results = $this->db->get()->result();
        }

        // Merging the results from both tables
        $sql = array_merge($ipd_results, $opd_results);

        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        // Now $data_patient contains the merged patient_unique_id from both tables
        echo json_encode($data_patient);
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
    public function patient_data_load_by_unique_id()
    {
        $patient_unique_id = $this->input->post('patient_unique_id');

        // Search in ipd_patient table
        $ipd_patient = $this->db->where('patient_unique_id', $patient_unique_id)->get('ipd_patient')->row();

        $opd_patient = $this->db->where('opd_patient_unique_id', $patient_unique_id)->get('opd_patient')->row();

        // If no patient found in ipd_patient, search in opd_patient
        if (!empty($ipd_patient)) {
            echo 'ipd_patient*' . $ipd_patient->ipd_patient_id . '*' . $ipd_patient->patient_name . '*' . $ipd_patient->mobile_number . '*' . $ipd_patient->age_year . '*' . $ipd_patient->age_month . '*' . '*' . $ipd_patient->age_day . '*' . $ipd_patient->address;
        }

        if (!empty($opd_patient)) {
            echo 'opd_patient*' . $opd_patient->opd_patient_id . '*' . $opd_patient->opd_patient_name . '*' . $opd_patient->mobile_number . '*' . $opd_patient->age_year . '*' . $opd_patient->age_month . '*' . $opd_patient->age_day . '*' . $opd_patient->address;
        }
    }
   
    public function addMoreSale()
    {
        $next_id = $_POST['next_id'];
        $this->load->view('pharmacy/medicine_sale/add_more_sale', array('next_id' => $next_id));
    }

    public function medicine_sale_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('medicine_sale_invoice_no', $parameter)
                ->from('medicine_sales');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('medicine_sales');
        }
        $sql = $this->db->get()->result();
        $data_medicine_sale = array();
        foreach ($sql as $value) {
            array_push($data_medicine_sale, $value->medicine_sale_invoice_no);
        }
        echo json_encode($data_medicine_sale);
    }

    public function addMorePurchase()
    {
        $next_id = $_POST['next_id'];
        $supplier = $_POST['supplier'];
?>
        <tr>
            <td>
                <select name="type_name[]" class="form-control" id="type_name_<?php echo $next_id ?>" sequence=0
                    onchange="drug_name_load(this.id)" required="" style="width:110px;">
                    <option value="" selected="">Select Type</option>
                    <?php
                    $sql = $this->db->select('*')->get('drug_type')->result();

                    foreach ($sql as $value) {
                    ?>
                        <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td style="padding:5px;">

                <select name="drug[]" class="form-control" id="drug_add<?php echo $next_id ?>" sequence=<?php echo $next_id ?>
                    onchange="details(this, event);" required="" style="width:160px;">
                    <option value="" selected="">Select Drug</option>
                    <?php
                    $sql = $this->db->where('manufacturer', $supplier)->get('drug')->result();

                    foreach ($sql as $value) {
                    ?>
                        <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="boxqty<?php echo $next_id ?>" name="boxqty[]" class="form-control"
                    sequence=<?php echo $next_id ?> onkeyup="getamount(this, event), getqty(this, event)" required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="re_order_qty<?php echo $next_id ?>" name="re_order_qty[]" class="form-control"
                    sequence=<?php echo $next_id ?>>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="pbq<?php echo $next_id ?>" name="pbq[]" class="form-control"
                    sequence=<?php echo $next_id ?>
                    onkeyup="getamount(this, event), purchase_rate(this, event), getqty(this, event)" required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="boxrate<?php echo $next_id ?>" name="boxrate[]" class="form-control"
                    sequence=<?php echo $next_id ?> onkeyup="purchase_rate(this, event), getamount(this, event)" required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="invoice_date<?php echo $next_id ?>" style="width:100px;" name="expdate[]"
                    class="form-control date<?php echo $next_id ?>" sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="discount<?php echo $next_id ?>" style="width:40px;" name="discount[]"
                    class="form-control" sequence=<?php echo $next_id ?>
                    onkeyup="purchase_rate(this, event), getamount(this, event)">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="vat<?php echo $next_id ?>" style="width:40px;" name="vat[]" class="form-control"
                    sequence=<?php echo $next_id ?> onkeyup="purchase_rate(this, event), getamount(this, event)">
            </td>

            <td style="padding:5px;">
                <input type="text" value="" id="pur_rate<?php echo $next_id ?>" name="pur_rate[]" class="form-control"
                    sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="mrp<?php echo $next_id ?>" name="mrp[]" class="form-control"
                    sequence=<?php echo $next_id ?>>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="wsr<?php echo $next_id ?>" name="whole_sale_rate[]" class="form-control"
                    sequence=0 required="">
            </td>

            <td style="padding:5px;">
                <input type="text" value="" id="qty<?php echo $next_id ?>" name="qty[]" class="form-control" readonly=""
                    sequence=<?php echo $next_id ?>>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="stock<?php echo $next_id ?>" name="stock[]" class="form-control" readonly=""
                    sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="amount<?php echo $next_id ?>" name="amount[]" class="form-control amount"
                    readonly="" sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <button class="btn btn-danger  btn-xs remove" type="button" sequence=<?php echo $next_id ?>
                    onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
            </td>
        </tr>
<?php
    }


    function medicin_sell()
    {
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/medicine_sale_add',
            'page_title' => 'Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function medicine_sale_edit($medicine_sale_id)
    {
        $data['medicine_sale_id'] = $medicine_sale_id;
        $this->load->view('pharmacy/medicine_sale/medicine_sale_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/medicine_sale_edit',
            'page_title' => 'Medicine Sale Edit',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    

    function purchase_edit()
    {
        $purchase_id = $this->input->post('purchase_id');
        $data['purchase_id'] = $purchase_id;
        $this->load->view('purchase_edit', $data);
    }


    public function medicine_sale_edit_save()
    {
        if ($this->input->is_ajax_request()) {
            /* Start Pay Status Calculation */
            $medicine_sale_id = $this->input->post('medicine_sale_id');
            $medicine_sale_previous_data = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales')->row();
            $sales = array(
                'name' => $this->input->post('name'),
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'opd_patient_id' => $this->input->post('opd_patient_id '),
                'age_year' => $this->input->post('age_year'),
                'age_month' => $this->input->post('age_month'),
                'age_day' => $this->input->post('age_day'),
                'address' => $this->input->post('address'),
                'remarks' => $this->input->post('remarks'),
                'given_amount' => $this->input->post('given_amount'),
                'change_amount' => $this->input->post('change_amount'),
                'mobile_number' => $this->input->post('mobile_number'),
                'medicine_sale_invoice_no' => $this->input->post('medicine_sale_invoice_no'),
                'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'total_discount' => $this->input->post('total_discount'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'due_reference' => $this->input->post('due_reference'),
            );
            $this->db->where('medicine_sale_id', $medicine_sale_id)->update('medicine_sales', $sales);

            //Delete the old data of details
            $this->db->where('medicine_sale_id', $medicine_sale_id)->delete('medicine_sales_details');

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
                $sales_details[] = array(
                    'medicine_sale_id ' => $medicine_sale_id,
                    'medicine_sale_invoice_no ' => $this->input->post('medicine_sale_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'sales_rate' => $sales_rate[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'pur_rate' => $pur_rate[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $medicine_sale_previous_data->user_id,
                );
            }
            $this->db->insert_batch('medicine_sales_details', $sales_details);

            $sdata['print_medicine_sale_id'] = $medicine_sale_id;
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


    function medicine_sale_save()
    {
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['medicine_sale_invoice_no'] = $this->input->post('medicine_sale_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_sale_invoices', $data);

            $sales = array(
                'name' => $this->input->post('name'),
                'age_year' => $this->input->post('age_year'),
                'age_month' => $this->input->post('age_month'),
                'age_day' => $this->input->post('age_day'),
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'opd_patient_id' => $this->input->post('opd_patient_id '),
                'address' => $this->input->post('address'),
                'remarks' => $this->input->post('remarks'),
                'given_amount' => $this->input->post('given_amount'),
                'change_amount' => $this->input->post('change_amount'),
                'mobile_number' => $this->input->post('mobile_number'),
                'medicine_sale_invoice_no' => $this->input->post('medicine_sale_invoice_no'),
                'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'total_discount' => $this->input->post('total_discount'),
                'due_reference' => $this->input->post('due_reference'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('medicine_sales', $sales);
            if ($insert)
                $medicine_sale_id  = $this->db->insert_id();

            $purchase_details = array();
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
                $sales_details[] = array(
                    'medicine_sale_id ' => $medicine_sale_id,
                    'medicine_sale_invoice_no ' => $this->input->post('medicine_sale_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'sales_rate' => $sales_rate[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'pur_rate' => $pur_rate[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            if ($insert) {
                $insert = $this->db->insert_batch('medicine_sales_details', $sales_details);
            }
            $sdata['print_medicine_sale_id'] = $medicine_sale_id;
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
    function medicine_sale_due_payment($medicine_sale_id)
    {
        $data['medicine_sale_id'] = $medicine_sale_id;
        $this->load->view('pharmacy/medicine_sale/medicine_sale_due_payment', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/medicine_sale_due_payment',
            'page_title' => 'Medicine Sale Due Payment',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_medicine_sale_due_payment_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $medicine_sale_id = $this->input->post('medicine_sale_id');
            // Data for main medicine sales table
            $data = array(
                'due_payment' => $this->input->post('due_payment') ?? null,
                'due' =>null,
                'paid_or_due_status' => 'paid',
                'due_payment_date' => $this->input->post('due_payment_date') ?? null,
                'due_payment_time' => $this->input->post('due_payment_time') ?? null,
                'due_payment_user_id' => $this->session->userdata('user_id'),
            );
            // Update main medicine sale record
            $this->db->where('medicine_sale_id', $medicine_sale_id)->update('medicine_sales', $data);

            // Log activity
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Medicine sale due payment updated and Medicine Sale Id is=' . $medicine_sale_id,
            );
            $this->db->insert('activity_log', $activity_data);
            // Session for printing
            $sdata['print_medicine_sale_id'] = $medicine_sale_id;
            $this->session->set_userdata($sdata);
            // Return success JSON
            echo json_encode(array('success' => true, 'message' => 'Data updated successfully.'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Invalid request.'));
        }
    }

    public function medicin_sell_edit($sales_id)
    {
        $data['sales_id'] = $sales_id;
        $this->load->view('pharmacy/medicin_sell_edit', $data);
    }

    public function print_medicine_sale()
    {
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/print_medicine_sale',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_medicine_sale_again($medicine_sale_id)
    {
        $data['medicine_sale_id'] = $medicine_sale_id;
        $sdata['print_medicine_sale_id'] = $medicine_sale_id;
        $this->session->set_userdata($sdata);

        $this->load->view('pharmacy/medicine_sale/print_medicine_sale', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_sale/print_medicine_sale',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_medicine_sale()
    {
        $medicine_sale_invoice_no = $this->input->post('medicine_sale_invoice_no') ?: '';
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
        $config['base_url'] = base_url() . "index.php/MedicineSaleController/view_medicine_sale";
        $config['total_rows'] = $this->MedicineSaleModel->count_all_medicine_sales($medicine_sale_invoice_no, $from_date, $to_date);
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
        $data['detailsList'] = $this->MedicineSaleModel->get_medicine_sells_details($this->per_page, $page, $medicine_sale_invoice_no, $from_date, $to_date);

        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $data['page_name'] = 'pharmacy/medicine_sale/view_medicine_sell';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }

    public function medicine_sale_delete_ajax()
    {
        $medicine_sale_id = $this->input->post('medicine_sale_id');
        if ($this->db->where('medicine_sale_id', $medicine_sale_id)->delete('medicine_sales')) {
            $this->db->where('medicine_sale_id', $medicine_sale_id)->delete('medicine_sales_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
