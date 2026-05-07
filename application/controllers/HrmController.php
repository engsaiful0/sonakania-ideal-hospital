<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeController
 *
 * @author Lenovo
 */
class HrmController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
             redirect('LoginController');
        }
    }

    function index() {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'hrm/hrm_dashbaord',
            'page_title' => 'Purchase',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_employee_salary() {
        $page_data = array(
            'page_name' => 'employee/add_employee_salary',
            'page_title' => 'Add Emplyee Salary',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_employee_salary_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('employee_salary');
        $crud->set_subject('Employee Salary');
     
        $crud->required_fields('employee_id', 'month', 'session', 'amount', 'date');
        $crud->set_relation('employee_id', 'employee', 'employee_name');
        $crud->columns('employee_id', 'month', 'session', 'amount', 'date', 'purpose');
        $crud->fields('employee_id', 'month', 'session', 'amount', 'date', 'purpose');
        $crud->display_as('employee_id', 'Employee Name');
        $crud->display_as('fathers_name', "Father's Name");
        $crud->field_type('month', 'dropdown', array('January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April', 'May' => 'May', 'Jun' => 'Jun', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December'));
        $crud->field_type('session', 'dropdown', array('2019' => '2019', '2020' => '2020'));
        $crud->display_as('date', 'Payment Date');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function share_holder() {
        $page_data = array(
            'page_name' => 'hrm/share_holder',
            'page_title' => 'Share Holder',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function share_holder_iframe() {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('share_holder');
        $crud->set_subject('Share Holder');
     
        // Required fields
        $crud->required_fields('name','category','nid','sign_in_mobile', 'unique_id', 'father_name', 'address', 'mobile','share_price','number_of_share','test_discount','op_discount','ipd_discount','physiography_discount','emergency_discount');  
        
        // Field types and validations
        $crud->field_type('category', 'dropdown', array('Founder' => 'Founder', 'Management' => 'Management', 'General' => 'General'));
        
        // Display columns
        $crud->columns('name','category','nid','sign_in_mobile', 'unique_id', 'father_name', 'address', 'mobile', 'email','share_price','number_of_share','test_discount','op_discount','ipd_discount','physiography_discount','emergency_discount','picture');
        
        // Form fields
        $crud->fields('name', 'category', 'nid', 'sign_in_mobile', 'unique_id', 'father_name', 'address', 'mobile', 'email','share_price','number_of_share','test_discount','op_discount','ipd_discount','physiography_discount','emergency_discount','picture');
        
        // Field display labels
        $crud->display_as('name', 'Share Holder Name');
        $crud->display_as('category', 'Share Holder Category');
        $crud->display_as('nid', 'National ID');
        $crud->display_as('sign_in_mobile', 'Sign-in Mobile');
        $crud->display_as('unique_id', 'Unique ID');
        $crud->display_as('father_name', "Father's Name");
        $crud->display_as('address', 'Address');
        $crud->display_as('mobile', 'Mobile Number');
        $crud->display_as('email', 'Email Address');
        $crud->display_as('share_price', 'Share Price (৳)');
        $crud->display_as('number_of_share', 'Number of Shares');
        $crud->display_as('test_discount', 'Test Discount (%)');
        $crud->display_as('op_discount', 'OPD Discount (%)');
        $crud->display_as('ipd_discount', 'IPD Discount (%)');
        $crud->display_as('physiography_discount', 'Physiography Discount (%)');
        $crud->display_as('emergency_discount', 'Emergency Discount (%)');
        $crud->display_as('picture', 'Profile Picture');
        
        // File upload configuration
        $crud->set_field_upload('picture', 'assets/employee');
        
        // Field type configurations for better UX
        $crud->field_type('address', 'text');
        $crud->field_type('email', 'email');
        $crud->field_type('share_price', 'decimal');
        $crud->field_type('number_of_share', 'integer');
        $crud->field_type('test_discount', 'decimal');
        $crud->field_type('op_discount', 'decimal');
        $crud->field_type('ipd_discount', 'decimal');
        $crud->field_type('physiography_discount', 'decimal');
        $crud->field_type('emergency_discount', 'decimal');
        
        // Add callback to generate unique_id before insert
        $crud->callback_before_insert(array($this, 'generate_share_holder_unique_id'));
        
        // Add callback to generate unique_id before update if empty
        $crud->callback_before_update(array($this, 'generate_share_holder_unique_id'));
        
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    
    /**
     * Generate unique_id for share_holder before insert
     * This function is called as a callback before inserting new share_holder records
     */
    public function generate_share_holder_unique_id($post_array) {
        // Generate unique_id if not provided or empty
        if (empty($post_array['unique_id'])) {
            $post_array['unique_id'] = $this->get_next_share_holder_unique_id();
        }
        return $post_array;
    }
    
    /**
     * Get the next unique_id for share_holder
     * Uses the helper function for consistency
     */
    public function get_next_share_holder_unique_id() {
        return generate_share_holder_unique_id();
    }
    
    /**
     * API endpoint to get next unique_id for share_holder
     * Can be called via AJAX to get unique_id before form submission
     */
    public function get_share_holder_unique_id() {
        if ($this->input->is_ajax_request()) {
            $unique_id = $this->get_next_share_holder_unique_id();
            echo json_encode(array(
                'success' => true,
                'unique_id' => $unique_id
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Invalid request'
            ));
        }
    }
    
    /**
     * Manual function to update unique_id for existing share_holders
     * This can be used to add unique_id to existing records that don't have one
     */
    public function update_share_holder_unique_ids() {
        $updated_count = update_share_holder_unique_ids();
        echo "Updated unique_id for {$updated_count} share holders.";
    }
    
    /**
     * Modern ShareHolder list view with Ajax
     */
    public function view_share_holder() {
        $page_data = array(
            'page_name' => 'hrm/view_share_holder',
            'page_title' => 'View Share Holders',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Modern ShareHolder add form with Ajax
     */
    public function add_share_holder() {
        $page_data = array(
            'page_name' => 'hrm/add_share_holder',
            'page_title' => 'Add Share Holder',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Ajax endpoint to get all share holders
     */
    public function get_share_holders() {
        if ($this->input->is_ajax_request()) {
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));
            
            $this->db->select('*');
            $this->db->from('share_holder');
            
            // Search functionality
            $search = $this->input->get("search");
            if (!empty($search['value'])) {
                $this->db->group_start();
                $this->db->like('name', $search['value']);
                $this->db->or_like('unique_id', $search['value']);
                $this->db->or_like('mobile', $search['value']);
                $this->db->or_like('email', $search['value']);
                $this->db->or_like('category', $search['value']);
                $this->db->group_end();
            }
            
            $total_records = $this->db->count_all_results('', false);
            
            // Ordering
            $order = $this->input->get("order");
            if (!empty($order)) {
                $columns = array('id', 'name', 'unique_id', 'category', 'mobile', 'email', 'share_price', 'number_of_share');
                $column_index = $order[0]['column'];
                $column_name = $columns[$column_index];
                $column_dir = $order[0]['dir'];
                $this->db->order_by($column_name, $column_dir);
            } else {
                $this->db->order_by('id', 'DESC');
            }
            
            $this->db->limit($length, $start);
            $share_holders = $this->db->get()->result();
            
            $data = array();
            foreach ($share_holders as $row) {
                $actions = '<div class="btn-group" role="group">';
                $actions .= '<button type="button" class="btn btn-sm btn-primary" onclick="viewShareHolder(' . $row->id . ')" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button type="button" class="btn btn-sm btn-warning" onclick="editShareHolder(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteShareHolder(' . $row->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                
                $data[] = array(
                    $row->id,
                    $row->name,
                    $row->unique_id,
                    $row->category,
                    $row->mobile,
                    $row->email,
                    '৳' . number_format($row->share_price, 2),
                    number_format($row->number_of_share),
                    $actions
                );
            }
            
            $response = array(
                "draw" => $draw,
                "recordsTotal" => $this->db->count_all('share_holder'),
                "recordsFiltered" => $total_records,
                "data" => $data
            );
            
            echo json_encode($response);
        } else {
            show_404();
        }
    }

    /**
     * Ajax endpoint to save share holder
     */
    public function save_share_holder() {
        if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
            $this->load->library('form_validation');
            
            // Set validation rules
            $this->form_validation->set_rules('name', 'Share Holder Name', 'required|trim');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('nid', 'National ID', 'required|trim');
            $this->form_validation->set_rules('sign_in_mobile', 'Sign-in Mobile', 'required|trim');
            $this->form_validation->set_rules('father_name', 'Father Name', 'required|trim');
            $this->form_validation->set_rules('address', 'Address', 'required|trim');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            $this->form_validation->set_rules('share_price', 'Share Price', 'required|numeric');
            $this->form_validation->set_rules('number_of_share', 'Number of Shares', 'required|integer');
            $this->form_validation->set_rules('test_discount', 'Test Discount', 'numeric');
            $this->form_validation->set_rules('op_discount', 'OPD Discount', 'numeric');
            $this->form_validation->set_rules('ipd_discount', 'IPD Discount', 'numeric');
            $this->form_validation->set_rules('physiography_discount', 'Physiography Discount', 'numeric');
            $this->form_validation->set_rules('emergency_discount', 'Emergency Discount', 'numeric');
            
            if ($this->form_validation->run() == FALSE) {
                $response = array(
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => validation_errors()
                );
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'category' => $this->input->post('category'),
                    'nid' => $this->input->post('nid'),
                    'sign_in_mobile' => $this->input->post('sign_in_mobile'),
                    'unique_id' => generate_share_holder_unique_id(),
                    'father_name' => $this->input->post('father_name'),
                    'address' => $this->input->post('address'),
                    'mobile' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'share_price' => $this->input->post('share_price'),
                    'number_of_share' => $this->input->post('number_of_share'),
                    'test_discount' => $this->input->post('test_discount') ?: 0,
                    'op_discount' => $this->input->post('op_discount') ?: 0,
                    'ipd_discount' => $this->input->post('ipd_discount') ?: 0,
                    'physiography_discount' => $this->input->post('physiography_discount') ?: 0,
                    'emergency_discount' => $this->input->post('emergency_discount') ?: 0
                );
                
                $id = $this->input->post('id');
                if ($id) {
                    // Update existing record
                    $this->db->where('id', $id);
                    $result = $this->db->update('share_holder', $data);
                    $message = 'Share holder updated successfully';
                } else {
                    // Insert new record
                    $result = $this->db->insert('share_holder', $data);
                    $message = 'Share holder added successfully';
                }
                
                if ($result) {
                    $response = array(
                        'success' => true,
                        'message' => $message
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Database error occurred'
                    );
                }
            }
            
            echo json_encode($response);
        } else {
            show_404();
        }
    }

    /**
     * Ajax endpoint to get single share holder data
     */
    public function get_share_holder($id) {
        if ($this->input->is_ajax_request()) {
            $share_holder = $this->db->get_where('share_holder', array('id' => $id))->row();
            
            if ($share_holder) {
                echo json_encode(array(
                    'success' => true,
                    'data' => $share_holder
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Share holder not found'
                ));
            }
        } else {
            show_404();
        }
    }

    /**
     * Ajax endpoint to delete share holder
     */
    public function delete_share_holder($id) {
        if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
            $result = $this->db->delete('share_holder', array('id' => $id));
            
            if ($result) {
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Share holder deleted successfully'
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Failed to delete share holder'
                ));
            }
        } else {
            show_404();
        }
    }

    /**
     * Test page for share_holder unique_id functionality
     */
    public function test_share_holder_unique_id() {
        $page_data = array(
            'page_name' => 'hrm/test_share_holder_unique_id',
            'page_title' => 'Test Share Holder Unique ID',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    
    public function add_employee_payroll() {
        $page_data = array(
            'page_name' => 'employee/add_employee_payroll',
            'page_title' => 'Add Emplyee Payroll',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_employee_payroll_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('employee_payroll');
        $crud->set_subject('Employee Payroll');
 
        $crud->required_fields('employee_id', 'basic');
        $crud->set_relation('employee_id', 'employee', 'employee_name');

        $crud->columns('employee_id', 'basic', 'house_rent', 'medical_allowance', 'tifin', 'transport', 'mobile', 'total');
        $crud->fields('employee_id', 'basic', 'house_rent', 'medical_allowance', 'tifin', 'transport', 'mobile', 'total');
        $crud->display_as('employee_id', 'Employee');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_employee() {
        $page_data = array(
            'page_name' => 'employee/add_employee',
            'page_title' => 'Add Emplyee',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function employee_salary_report() {
        $page_data = array(
            'page_name' => 'employee/employee_salary_report',
            'page_title' => 'Emplyee Salary Report',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function salary_report_details_load() {
        $data = array();
        $data['employee_id'] = $_POST['employee_id'];
        $data['month'] = $_POST['month'];
        $data['session'] = $_POST['session'];
        //  echo '<pre>';
        //  print_r($data);
        // die;
        $this->load->view('employee/salary_report_details_load', $data);
    }

    public function all_employee_salary_report_details() {
        $data = array();

        $data['month'] = $_POST['month'];
        $data['session'] = $_POST['session'];      
        $this->load->view('employee/all_employee_salary_report_details', $data);
    }

    public function all_employee_salary_report() {
        $page_data = array(
            'page_name' => 'employee/all_employee_salary_report',
            'page_title' => 'All Emplyee Salary Report',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_employee_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('employee');
        $crud->set_subject('Employee');
 
        $crud->required_fields('employee_name', 'address', 'mobile', 'joining_date', 'monthly_salary');
        $crud->set_field_upload('picture', 'assets/employee');
        $crud->callback_add_field('employee_unique_id', function () {
            $uniqu_id = $this->db->select('*')->get('employee_uniqueid_table');
            $employee_unique_id = 'EM' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
            $id = array(
                'unique_id' => $employee_unique_id
            );
            $this->db->insert('employee_uniqueid_table', $id);
            return '<input type="text" maxlength="50" value="' . $employee_unique_id . '" name="employee_unique_id"  readonly>';
        });
        $crud->callback_edit_field('employee_unique_id', function () {
            $uniqu_id = $this->db->select('*')->get('employee_uniqueid_table');
            $employee_unique_id = 'EM' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
            $id = array(
                'unique_id' => $employee_unique_id
            );
            $this->db->insert('employee_uniqueid_table', $id);
            return '<input type="text" maxlength="50" value="' . $employee_unique_id . '" name="employee_unique_id"  readonly>';
        });
        $crud->columns('employee_name', 'employee_unique_id', 'fathers_name', 'address', 'nid', 'mobile', 'picture', 'joining_date', 'monthly_salary');
        $crud->fields('employee_name', 'employee_unique_id', 'fathers_name', 'address', 'nid', 'mobile', 'picture', 'joining_date', 'monthly_salary');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

}
