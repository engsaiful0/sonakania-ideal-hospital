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

class IssueController extends CI_Controller
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
    public function issue_no_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('issue_no', $parameter)
                ->from('issue');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('issue');
        }
        $sql = $this->db->get()->result();
        $data_issue = array();
        foreach ($sql as $value) {
            array_push($data_issue, $value->issue_no);
        }
        echo json_encode($data_issue);
    }
    private $defaults = array();

    public function index() {}
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
    public function view_issue()
    {
        $issue_no = $this->input->post('issue_no');
        $employee_id = $this->input->post('employee_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/IssueController/view_issue";
        $config['total_rows'] = $this->IssueModel->count_all_issue($issue_no, $employee_id, $from_date, $to_date);
        $config['per_page'] = 20;
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

        $data['issue_data'] = $this->IssueModel->get_issue($this->per_page, $page, $issue_no, $employee_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('issue/view_issue', $data, true);
        $page_data = array(
            'page_name' => 'issue/view_issue',
            'page_title' => 'View Issue',
              'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_issue()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'issue/add_issue',
            'page_title' => 'Add Issue',
              'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_issue($issue_id)
    {
        $data['issue_id'] = $issue_id;
        $this->load->view('issue/edit_issue', $data, true);
        $page_data = array(
            'page_name' => 'issue/edit_issue',
            'page_title' => 'Edit Issue',
              'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_issue_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'issue_no' => $this->input->post('issue_no'),
                'employee_id' => $this->input->post('employee_id'),
                'purpose' => $this->input->post('purpose'),
                'department_id' => $this->input->post('department_id'),
                'issue_no' => $this->input->post('issue_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_quantity' => $this->input->post('total_quantity'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $issue = $this->db->insert('issue', $data);

            $issue_id = $this->db->insert_id();

            $item_id = $this->input->post('item_id');
            $issue_quantity = $this->input->post('issue_quantity');
            $issue_no = $this->input->post('issue_no');

            $issue_details = array();
            for ($loop = 0; $loop < count($item_id); $loop++) {
                $issue_details[] = array(
                    'issue_no ' => $issue_no,
                    'issue_id ' => $issue_id,
                    'item_id' => $item_id[$loop],
                    'department_id' => $this->input->post('department_id'),
                    'employee_id' => $this->input->post('employee_id'),
                    'purpose' => $this->input->post('purpose'),
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'issue_quantity' => $issue_quantity[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'issue_invoice_no' => $this->input->post('issue_no'),
                'issue_invoice_serial' => $this->input->post('issue_invoice_serial'),
            );
            $this->db->insert('issue_invoice', $invoice_data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Issue added and Issue Id is=' . $issue_id
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


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
    function print_issue()
    {
        $page_data = array(
            'page_name' => 'issue/print_issue',
            'page_title' => 'Print Issue',
            'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_issue_with_id($issue_id)
    {
        $sdata['print_issue_id'] = $issue_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'issue/print_issue',
            'page_title' => 'Print Issue',
              'sidebar' => 'store/store_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function load_product_row()
    {
        $data['next_id'] = $_POST['next_id'];
        $this->load->view('issue/load_product_row', $data);
    }
    public function invoice_load()
    {
        $manufacture_id = $_POST['manufacture_id'];
        $sql = $this->db->where('supplier', $manufacture_id)->get('purchase')->result();
?><option>Select Invoice</option>
        <?php
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->purchase_id ?>"><?php echo $value->mrr ?></option>
<?php
        }
    }

    public function available_quantity_load()
    {
        $item_id = $_POST['item_id'];
        if ($item_id != "null") {
            $sql_item = $this->db->where('item_id', $item_id)->get('item')->row();
            $query = $this->db->select_sum('quantity')
                ->where('item_id', $item_id)
                ->get('item_purchase_details');
            $result = $query->row();
            $sum_purchase_quantity = $result->quantity;

            $query = $this->db->select_sum('issue_quantity')
                ->where('item_id', $item_id)
                ->get('issue_details');
            $result = $query->row();
            $sum_issue_quantity = $result->issue_quantity;

            echo ($sql_item->opening_stock + $sum_purchase_quantity) - $sum_issue_quantity;
        } else {
            return;
        }
    }

    public function update_issue_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $issue_id = $this->input->post('issue_id');
            $issue_previous_data = $this->db->where('issue_id', $issue_id)->get('issue')->row();
            $data = array(
                'issue_no' => $this->input->post('issue_no'),
                'employee_id' => $this->input->post('employee_id'),
                'purpose' => $this->input->post('purpose'),
                'issue_no' => $this->input->post('issue_no'),
                'department_id' => $this->input->post('department_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'total_quantity' => $this->input->post('total_quantity'),
            );
            $issue = $this->db->where('issue_id', $issue_id)->update('issue', $data);



            $issue_id = $this->input->post('issue_id');

            $this->db->where('issue_id', $issue_id)->delete('issue_details');



            $item_id = $this->input->post('item_id');
            $issue_quantity = $this->input->post('issue_quantity');
            $issue_no = $this->input->post('issue_no');

            $issue_details = array();
            for ($loop = 0; $loop < count($item_id); $loop++) {
                $issue_details[] = array(
                    'issue_no ' => $issue_no,
                    'issue_id ' => $issue_id,
                    'employee_id' => $this->input->post('employee_id'),
                    'department_id' => $this->input->post('department_id'),
                    'purpose' => $this->input->post('purpose'),
                    'item_id' => $item_id[$loop],
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'issue_quantity' => $issue_quantity[$loop],
                    'user_id' => $issue_previous_data->user_id,
                );
            }
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


    public function issue_delete_ajax()
    {
        $issue_id = $this->input->post('issue_id');
        if ($this->db->where('issue_id', $issue_id)->delete('issue')) {
            $this->db->where('issue_id', $issue_id)->delete('issue_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
