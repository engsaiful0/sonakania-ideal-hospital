<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class ExpiredMedicineController extends CI_Controller
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
    public function expired_medicine_invoice_no_load_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('expired_medicine_invoice_no', $parameter)
                ->from('expired_medicines');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('expired_medicines');
        }
        $sql = $this->db->get()->result();
        $expired_medicines_array = array();
        foreach ($sql as $value) {
            array_push($expired_medicines_array, $value->expired_medicine_invoice_no);
        }
        echo json_encode($expired_medicines_array);
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
    function add_expired_medicine()
    {
        $page_data = array(
            'page_name' => 'pharmacy/expired_medicine/add',
            'page_title' => 'Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_expired_medicine()
    {
        $expired_medicine_invoice_no = $this->input->post('expired_medicine_invoice_no') ?: '';
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
        $config['base_url'] = base_url() . "/index.php/ExpiredMedicineController/view_expired_medicine";
        $config["total_rows"] = $this->ExpiredMedicineModel->count_all_expired_medicine($expired_medicine_invoice_no, $from_date, $to_date);
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
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

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Fetch data with limit and offset
        $data['detailsList'] = $this->ExpiredMedicineModel->get_expired_medicine_details($this->per_page, $page, $expired_medicine_invoice_no, $from_date, $to_date);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'pharmacy/expired_medicine/view_expired_medicine';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }

    function expired_medicine_edit($expired_medicine_id)
    {
        $data['expired_medicine_id'] = $expired_medicine_id;
        $this->load->view('pharmacy/expired_medicine/expired_medicine_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/expired_medicine/expired_medicine_edit',
            'page_title' => 'Medicine Purchase Edit',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_expired_medicine()
    {
        $page_data = array(
            'page_name' => 'pharmacy/expired_medicine/print_expired_medicine',
            'page_title' => 'Print Expired Medicine',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_expired_medicine_again($expired_medicine_id)
    {
        //die($expired_medicine_id);
        $data['expired_medicine_id'] = $expired_medicine_id;
        // echo '<pre>';
        // print_r($data);
        // die;
        $this->load->view('pharmacy/expired_medicine/print_expired_medicine', $data, TRUE);
        $page_data = array(
            'page_name' => 'pharmacy/expired_medicine/print_expired_medicine',
            'page_title' => 'Add Doctor',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function addMoreRow()
    {
        $next_id = $_POST['next_id'];
?>
        <tr>


            <td>
                <input type="hidden" id="drug_id_value<?php echo $next_id ?>" readonly name="drug_id_value[]" class="form-control" ">
                <input type=" text" placeholder="Enter Medicine Name" id="drug_id<?php echo $next_id ?>" sequence=<?php echo $next_id ?> name="drug_id[]" class="form-control" ">
                                                
            </td>
            <td>
                <input type=" text" readonly id="purchase_rate<?php echo $next_id ?>" name="purchase_rate[]" class="form-control" sequence=<?php echo $next_id ?>>
            </td>
            <td>
                <input readonly type="text" id="mrp_rate<?php echo $next_id ?>" name="mrp_rate[]"
                    class="form-control" sequence=<?php echo $next_id ?>>
            </td>
            <td>
                <input type="text" value="" id="quantity<?php echo $next_id ?>" name="quantity[]" class="form-control"
                    sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)" required=""
                    onkeyup="getamount(this, event)">
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

    function expired_medicine_edit_save()
    {
        if ($this->input->is_ajax_request()) {
            $expired_medicine_id = $this->input->post('expired_medicine_id');
            $expired_medicines_previous_data = $this->db->where('expired_medicine_id', $expired_medicine_id)->get('expired_medicines')->row();
            $expired_medicine_details = array();
            $expired_medicine = array(
                'expired_medicine_invoice_no' => $this->input->post('expired_medicine_invoice_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
            );
            $this->db->where('expired_medicine_id', $expired_medicine_id)->update('expired_medicines', $expired_medicine);
            //Delete the old data of details
            $this->db->where('expired_medicine_id', $expired_medicine_id)->delete('expired_medicine_details');

            
            $drug_id = $this->input->post('drug_id_value');
            $purchase_rate = $this->input->post('purchase_rate');
            $mrp_rate = $this->input->post('mrp_rate');
            $quantity = $this->input->post('quantity');
            $amount = $this->input->post('amount');

            for ($loop = 0; $loop < count($drug_id); $loop++) {
                $expired_medicine_details[] = array(
                    'expired_medicine_id' => $expired_medicine_id,
                    'expired_medicine_invoice_no' => $this->input->post('expired_medicine_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'purchase_rate' => $purchase_rate[$loop],
                    'mrp_rate' => $mrp_rate[$loop],
                    'quantity' => $quantity[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $expired_medicines_previous_data->user_id,
                );
            }
            $this->db->insert_batch('expired_medicine_details', $expired_medicine_details);

            $sdata['print_expired_medicine_id'] = $expired_medicine_id;
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
    function expired_medicine_save()
    {
        if ($this->input->is_ajax_request()) {
            $expired_medicine_details = array();
            $data = array();
            $data['expired_medicine_invoice_no'] = $this->input->post('expired_medicine_invoice_no');
            $data['id_serial'] = $this->input->post('id_serial');
            $data['user_id'] = $this->input->post('user_id');
            $this->db->insert('expired_medicine_invoices', $data);

            $expired_medicine = array(
                'expired_medicine_invoice_no' => $this->input->post('expired_medicine_invoice_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'remarks' => $this->input->post('remarks'),
                'total' => $this->input->post('total'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('expired_medicines', $expired_medicine);
            if ($insert)
                $expired_medicine_id  = $this->db->insert_id();

            $drug_id = $this->input->post('drug_id_value');
            $purchase_rate = $this->input->post('purchase_rate');
            $mrp_rate = $this->input->post('mrp_rate');
            $quantity = $this->input->post('quantity');
            $amount = $this->input->post('amount');

            for ($loop = 0; $loop < count($drug_id); $loop++) {
                $expired_medicine_details[] = array(
                    'expired_medicine_id' => $expired_medicine_id,
                    'expired_medicine_invoice_no' => $this->input->post('expired_medicine_invoice_no'),
                    'drug_id' => $drug_id[$loop],
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'purchase_rate' => $purchase_rate[$loop],
                    'mrp_rate' => $mrp_rate[$loop],
                    'quantity' => $quantity[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            $this->db->insert_batch('expired_medicine_details', $expired_medicine_details);

            $sdata['print_expired_medicine_id'] = $expired_medicine_id;
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




    public function medicine_purchase_delete_ajax()
    {
        $expired_medicine_id = $this->input->post('expired_medicine_id');
        if ($this->db->where('expired_medicine_id', $expired_medicine_id)->delete('expired_medicines')) {

            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
