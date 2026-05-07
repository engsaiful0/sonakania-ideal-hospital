<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class MedicineExpireController extends CI_Controller
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
    public function medicine_purchase_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('medicine_purchase_invoice_no', $parameter)
                ->from('medicine_purchase');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('medicine_sales');
        }
        $sql = $this->db->get()->result();
        $data_medicine_purchase = array();
        foreach ($sql as $value) {
            array_push($data_medicine_purchase, $value->medicine_purchase_invoice_no);
        }
        echo json_encode($data_medicine_purchase);
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
    public function addMorePurchase()
    {
        $next_id = $_POST['next_id'];
?>
        <tr>
            <td>
                <select name="drug_type_id[]" class="form-control" id="drug_type_id_<?php echo $next_id ?>" sequence=0
                    onchange="drug_name_load(this.id)" required="" style="width:180px;">
                    <option value="" selected="">Select Type</option>
                    <?php
                    $sql = $this->db->select('*')->order_by('type_name', 'ASC')->get('drug_type')->result();

                    foreach ($sql as $value) {
                    ?>
                        <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="drug_id[]" class="form-control" id="drug_id<?php echo $next_id ?>"
                    onchange="getdrugdetails(this, event)" required="" style="width:150px;" sequence=<?php echo $next_id; ?>>

                </select>
            </td>
            <td>
                <input type="text" id="mfg_datepicker<?php echo $next_id ?>" name="mfg_date[]"
                    class="form-control" sequence=<?php echo $next_id ?> required="" style="width:90px;">
            </td>
            <td>
                <input type="text" id="exp_date<?php echo $next_id ?>" name="exp_date[]"
                    class="form-control" sequence=<?php echo $next_id ?> required="" style="width:90px;">
            </td>
            <td>
                <input type="text" id="mrp_rate<?php echo $next_id ?>" name="mrp_rate[]"
                    class="form-control" sequence=<?php echo $next_id ?> required="" style="width:60px;">
            </td>
            <td>
                <input type="text" id="purchase_rate<?php echo $next_id ?>" name="purchase_rate[]"
                    class="form-control" sequence=<?php echo $next_id ?> required="" style="width:60px;" onkeyup="getamount(this, event)">
            </td>

            <td>
                <input type="text" value="" id="quantity<?php echo $next_id ?>" name="quantity[]" class="form-control"
                    sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)" required="" style="width:60px;"
                    onkeyup="getamount(this, event)">
            </td>
            <td>
                <input type="text" value="" id="discounteach<?php echo $next_id ?>" name="discounteach[]" style="width:60px;" class="form-control"
                    sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)">
            </td>
            <td>
                <input type="text" id="amount<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly=""
                    sequence=<?php echo $next_id ?>>
            </td>
            <td>
                <button class="btn btn-danger  btn-xs remove" type="button" sequence=<?php echo $next_id ?>
                    onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
            </td>
        </tr>
    <?php
    }

    function add_medicine_purchase()
    {
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/add_medicine_purchase',
            'page_title' => 'Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_medicine_purchase()
    {
        $medicine_purchase_invoice_no = $this->input->post('medicine_purchase_invoice_no') ?: '';
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
        $config['base_url'] = base_url() . "index.php/MedicinePurchaseController/view_medicine_purchase";
        $config["total_rows"] = $this->MedicinePurchaseModel->count_all_medicine_purchase($medicine_purchase_invoice_no, $supplier_id, $from_date, $to_date);
        $config["per_page"] = 100;
        $config["uri_segment"] = 3;
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
        $data['detailsList'] = $this->MedicinePurchaseModel->get_medicine_purchase_details($this->per_page, $page, $medicine_purchase_invoice_no, $supplier_id, $from_date, $to_date);

        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $data['page_name'] = 'pharmacy/medicine_purchase/view_medicine_purchase';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }

    function edit_medicine_purchase($medicine_purchase_id)
    {
        $data['medicine_purchase_id'] = $medicine_purchase_id;
        $this->load->view('pharmacy/medicine_purchase/edit_medicine_purchase', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/edit_medicine_purchase',
            'page_title' => 'Medicine Purchase Edit',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    
    public function print_medicine_purchase()
    {
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/print_medicine_purchase',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_medicine_purchase_again($medicine_purchase_id)
    {
        $data['medicine_purchase_id'] = $medicine_purchase_id;
        $this->load->view('pharmacy/medicine_purchase/print_medicine_purchase', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/medicine_purchase/print_medicine_purchase',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function medicine_purchase_save()
    {
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['medicine_purchase_invoice_no'] = $this->input->post('medicine_purchase_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_purchase_invoices', $data);

            $sales = array(
                'supplier_id' => $this->input->post('supplier_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'medicine_purchase_invoice_no' => $this->input->post('medicine_purchase_invoice_no'),
                'status' => $this->input->post('status'),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('medicine_purchase', $sales);
            if ($insert)
                $medicine_purchase_id  = $this->db->insert_id();

            $purchase_details = array();
            $drug_type_id = $this->input->post('drug_type_id');
            $drug_id = $this->input->post('drug_id');
            $mfg_date = $this->input->post('mfg_date');
            $exp_date = $this->input->post('exp_date');
            $purchase_rate = $this->input->post('purchase_rate');
            $mrp_rate = $this->input->post('mrp_rate');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');

            for ($loop = 0; $loop < count($drug_type_id); $loop++) {
                $purchase_details[] = array(
                    'medicine_purchase_id' => $medicine_purchase_id,
                    'medicine_purchase_invoice_no' => $this->input->post('medicine_purchase_invoice_no'),
                    'drug_type_id' => $drug_type_id[$loop],
                    'drug_id' => $drug_id[$loop],
                    'mfg_date' => date('Y-m-d', strtotime($mfg_date[$loop])),
                    'exp_date' => date('Y-m-d', strtotime($exp_date[$loop])),
                    'purchase_rate' => $purchase_rate[$loop],
                    'mrp_rate' => $mrp_rate[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            if ($insert) {
                $insert = $this->db->insert_batch('medicine_purchase_details', $purchase_details);
            }
            $sdata['print_medicine_purchase_id'] = $medicine_purchase_id;
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
    function medicine_purchase_edit_save()
    {
        if ($this->input->is_ajax_request()) {
            $medicine_purchase_id = $this->input->post('medicine_purchase_id');
            $data = array();
            $data['medicine_purchase_invoice_no'] = $this->input->post('medicine_purchase_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('medicine_purchase_invoices', $data);

            $purchase = array(
                'supplier_id' => $this->input->post('supplier_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'medicine_purchase_invoice_no' => $this->input->post('medicine_purchase_invoice_no'),
                'status' => $this->input->post('status'),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->where('medicine_purchase_id', $medicine_purchase_id)->update('medicine_purchase', $purchase);
            //Delete the old data of details
            $this->db->where('medicine_purchase_id', $medicine_purchase_id)->delete('medicine_purchase_details');


            $purchase_details = array();
            $drug_type_id = $this->input->post('drug_type_id');
            $drug_id = $this->input->post('drug_id');
            $mfg_date = $this->input->post('mfg_date');
            $exp_date = $this->input->post('exp_date');
            $purchase_rate = $this->input->post('purchase_rate');
            $mrp_rate = $this->input->post('mrp_rate');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');

            for ($loop = 0; $loop < count($drug_type_id); $loop++) {
                $purchase_details[] = array(
                    'medicine_purchase_id' => $medicine_purchase_id,
                    'medicine_purchase_invoice_no' => $this->input->post('medicine_purchase_invoice_no'),
                    'drug_type_id' => $drug_type_id[$loop],
                    'drug_id' => $drug_id[$loop],
                    'mfg_date' => date('Y-m-d', strtotime($mfg_date[$loop])),
                    'exp_date' => date('Y-m-d', strtotime($exp_date[$loop])),
                    'purchase_rate' => $purchase_rate[$loop],
                    'mrp_rate' => $mrp_rate[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            $this->db->insert_batch('medicine_purchase_details', $purchase_details);
            $sdata['print_medicine_purchase_id'] = $medicine_purchase_id;
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

   
    public function medicine_purchase_delete_ajax()
    {
        $medicine_purchase_id = $this->input->post('medicine_sale_id');
        if ($this->db->where('medicine_purchase_id', $medicine_purchase_id)->delete('medicine_purchase')) {
            $this->db->where('medicine_purchase_id', $medicine_purchase_id)->delete('medicine_purchase_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
