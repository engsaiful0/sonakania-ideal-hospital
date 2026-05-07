<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseController
 *
 * @author Lenovo
 */

use Laminas\Barcode\Barcode;

class DebitVoucherController extends CI_Controller
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
    public function paid_to_load()
    {
        $parameter = $this->input->post('parameter');
        $data_paid_to = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $paid_tos = $this->db->select("paid_to AS paid_to")
                ->like('paid_to', $parameter)
                ->get('debit_voucher')
                ->result();


            // Merge all results
            $all_results = array_merge($paid_tos);

            // Extract discount_reference
            foreach ($all_results as $value) {
                $data_paid_to[] = $value->paid_to;
            }
        }

        echo json_encode($data_paid_to);
    }
    public function purpose_load()
    {
        $parameter = $this->input->post('parameter');
        $data_purpose = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $purposes = $this->db->select("purpose AS purpose")
                ->like('purpose', $parameter)
                ->get('debit_voucher')
                ->result();


          

            // Extract discount_reference
            foreach ($purposes as $value) {
                $data_purpose[] = $value->purpose;
            }
        }

        echo json_encode($data_purpose);
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
    public function load_account_number()
    {
        $bank_name_id = $_POST['bank_name_id'];

        $bank_accounts = $this->db->where('bank_name_id', $bank_name_id)
            ->from('bank_accounts')->get()->result();
?>
        <option>Select Bank Account</option>
        <?php
        foreach ($bank_accounts as $value) {
        ?>
            <option value="<?php echo $value->bank_account_id ?>"><?php echo $value->account_name ?>&nbsp;<?php echo $value->account_number ?></option>
<?php
        }
    }
    private $defaults = array();
    public function debit_voucher_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('debit_voucher_no', $parameter)
                ->from('debit_voucher');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('debit_voucher');
        }
        $sql = $this->db->get()->result();
        $data_debit_voucher = array();
        foreach ($sql as $value) {
            array_push($data_debit_voucher, $value->debit_voucher_no);
        }
        echo json_encode($data_debit_voucher);
    }
    public function index() {}
    public function view_debit_voucher()
    {

        $debit_voucher_no = $this->input->post('debit_voucher_no');
        $debit_account_id = $this->input->post('debit_account_id');
        $type = $this->input->post('type');
        $bank_id = $this->input->post('bank_id');
        $check_number = $this->input->post('check_number');

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] =  base_url() . "index.php/DebitVoucherController/view_debit_voucher";
        $config['total_rows'] = $this->DebitVoucherModel->count_all_debit_vouchers($debit_voucher_no, $debit_account_id, $type, $bank_id, $check_number, $from_date, $to_date);
        $config['per_page'] = 100;
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

        $data['debit_voucher_data'] = $this->DebitVoucherModel->get_debit_vouchers($this->per_page, $page, $debit_voucher_no, $debit_account_id, $type, $bank_id, $check_number, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('debit_voucher/view_debit_voucher', $data, true);
        $page_data = array(
            'page_name' => 'debit_voucher/view_debit_voucher',
            'page_title' => 'View Issue',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_debit_voucher()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'debit_voucher/add_debit_voucher',
            'page_title' => 'Add Debit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_debit_voucher($debit_voucher_id)
    {
        $data['debit_voucher_id'] = $debit_voucher_id;
        $this->load->view('debit_voucher/edit_debit_voucher', $data, true);
        $page_data = array(
            'page_name' => 'debit_voucher/edit_debit_voucher',
            'page_title' => 'Edit Debit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_debit_voucher_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'debit_account_id' => $this->input->post('debit_account_id'),
                'director_id' => $this->input->post('director_id'),
                'employee_id' => $this->input->post('employee_id'),
                'month_id' => $this->input->post('month_id'),
                'year_id' => $this->input->post('year_id'),
                'doctor_id' => $this->input->post('doctor_id'),
                'paid_to' => $this->input->post('paid_to'),
                'supplier_id' => $this->input->post('supplier_id'),
                'type' => $this->input->post('type'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'bank_account_id' => $this->input->post('bank_account_id'),
                'check_number' => $this->input->post('check_number'),
                'bank_details' => $this->input->post('bank_details'),

                'purpose' => $this->input->post('purpose'),
                'debit_voucher_no' => $this->input->post('debit_voucher_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_amount' => $this->input->post('total_amount'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $debit_voucher = $this->db->insert('debit_voucher', $data);

            $debit_voucher_id = $this->db->insert_id();



            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'debit_voucher_no' => $this->input->post('debit_voucher_no'),
                'debit_voucher_invoice_serial' => $this->input->post('debit_voucher_invoice_serial'),
            );
            $emergency = $this->db->insert('debit_voucher_invoice', $invoice_data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Debit Voucher added and Debit Voucher Id is=' . $debit_voucher_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_debit_voucher_id'] = $debit_voucher_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function save_debit_voucher_edit_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'debit_account_id' => $this->input->post('debit_account_id'),
                'director_id' => $this->input->post('director_id'),
                'employee_id' => $this->input->post('employee_id'),
                'month_id' => $this->input->post('month_id'),
                'year_id' => $this->input->post('year_id'),
                'paid_to' => $this->input->post('paid_to'),
                'supplier_id' => $this->input->post('supplier_id'),
                'doctor_id' => $this->input->post('doctor_id'),
                'type' => $this->input->post('type'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'bank_account_id' => $this->input->post('bank_account_id'),
                'check_number' => $this->input->post('check_number'),
                'bank_details' => $this->input->post('bank_details'),
                'purpose' => $this->input->post('purpose'),
                'debit_voucher_no' => $this->input->post('debit_voucher_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_amount' => $this->input->post('total_amount'),
            );
            $debit_voucher_id = $this->input->post('debit_voucher_id');
            $debit_voucher = $this->db->where('debit_voucher_id', $debit_voucher_id)->update('debit_voucher', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Debit Voucher Edited and Debit Voucher Id is=' . $debit_voucher_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_debit_voucher_id'] = $debit_voucher_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    function print_debit_voucher()
    {
        $page_data = array(
            'page_name' => 'debit_voucher/print_debit_voucher',
            'page_title' => 'Print Debit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_debit_voucher_with_id($print_debit_voucher_id)
    {
        $sdata['print_debit_voucher_id'] = $print_debit_voucher_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'debit_voucher/print_debit_voucher',
            'page_title' => 'Print Debit Voucher',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function update_issue_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $issue_id = $this->input->post('issue_id');
            $data = array(
                'issue_no' => $this->input->post('issue_no'),
                'employee_id' => $this->input->post('employee_id'),
                'purpose' => $this->input->post('purpose'),
                'issue_no' => $this->input->post('issue_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_quantity' => $this->input->post('total_quantity'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $issue = $this->db->where('issue_id', $issue_id)->update('issue', $data);



            $issue_id = $this->input->post('issue_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');
            $this->db->where('issue_id', $issue_id)->delete('issue_details');



            $item_id = $this->input->post('item_id');
            $issue_quantity = $this->input->post('issue_quantity');
            $issue_no = $this->input->post('issue_no');


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Issue Updated and Issue Id is=' . $issue_id
            );
            $issue = $this->db->insert('activity_log', $activity_data);
            $this->db->insert_batch('issue_details', $issue_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_issue_id'] = $issue_id;
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function debit_voucher_delete_ajax()
    {
        $debit_voucher_id = $this->input->post('debit_voucher_id');
        if ($this->db->where('debit_voucher_id', $debit_voucher_id)->delete('debit_voucher')) {
            $response = array('status' => 'success', 'message' => 'Debit voucher deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }

    public function check_debit_limit()
    {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $debit_account_id = (int)$this->input->post('debit_account_id');
        $amount = (float)$this->input->post('amount');
        $exclude_id = (int)$this->input->post('exclude_debit_voucher_id'); // optional, to skip current row on edit
        $date_str = $this->input->post('date');
        $date = $date_str ? date('Y-m-d', strtotime($date_str)) : date('Y-m-d');

        if (!$debit_account_id || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            return;
        }

        $account = $this->db->where('debit_account_id', $debit_account_id)->get('debit_account')->row();
        if (!$account) {
            echo json_encode(['success' => false, 'message' => 'Account not found']);
            return;
        }

        $daily_limit = (float)($account->daily_limit ?? 0);
        $monthly_limit = (float)($account->monthly_limit ?? 0);
        $yearly_limit = (float)($account->yearly_limit ?? 0);

        // Compute period sums from existing vouchers
        $this->db->select_sum('total_amount')
            ->where('debit_account_id', $debit_account_id)
            ->where('date', $date);
        if ($exclude_id > 0) { $this->db->where('debit_voucher_id !=', $exclude_id); }
        $today_total = (float)$this->db->get('debit_voucher')->row()->total_amount;

        $month_start = date('Y-m-01', strtotime($date));
        $month_end = date('Y-m-t', strtotime($date));
        $this->db->select_sum('total_amount')
            ->where('debit_account_id', $debit_account_id)
            ->where('date >=', $month_start)
            ->where('date <=', $month_end);
        if ($exclude_id > 0) { $this->db->where('debit_voucher_id !=', $exclude_id); }
        $month_total = (float)$this->db->get('debit_voucher')->row()->total_amount;

        $year_start = date('Y-01-01', strtotime($date));
        $year_end = date('Y-12-31', strtotime($date));
        $this->db->select_sum('total_amount')
            ->where('debit_account_id', $debit_account_id)
            ->where('date >=', $year_start)
            ->where('date <=', $year_end);
        if ($exclude_id > 0) { $this->db->where('debit_voucher_id !=', $exclude_id); }
        $year_total = (float)$this->db->get('debit_voucher')->row()->total_amount;

        // Predict totals after including current amount
        $daily_after = $today_total + $amount;
        $monthly_after = $month_total + $amount;
        $yearly_after = $year_total + $amount;

        $daily_exceeded = ($daily_limit > 0) ? ($daily_after > $daily_limit) : false;
        $monthly_exceeded = ($monthly_limit > 0) ? ($monthly_after > $monthly_limit) : false;
        $yearly_exceeded = ($yearly_limit > 0) ? ($yearly_after > $yearly_limit) : false;

        echo json_encode([
            'success' => true,
            'daily' => [
                'limit' => $daily_limit,
                'current_total' => $today_total,
                'after' => $daily_after,
                'exceeded' => $daily_exceeded,
            ],
            'monthly' => [
                'limit' => $monthly_limit,
                'current_total' => $month_total,
                'after' => $monthly_after,
                'exceeded' => $monthly_exceeded,
            ],
            'yearly' => [
                'limit' => $yearly_limit,
                'current_total' => $year_total,
                'after' => $yearly_after,
                'exceeded' => $yearly_exceeded,
            ],
            'anyExceeded' => ($daily_exceeded || $monthly_exceeded || $yearly_exceeded)
        ]);
    }

}
