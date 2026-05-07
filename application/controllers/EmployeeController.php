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
class EmployeeController extends CI_Controller
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

    public function index()
    {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function designation_load()
    {
        $men_power_category_id = $_POST['men_power_category_id'];
        $designation = $this->db->where('men_power_category_id', $men_power_category_id)
            ->order_by('designation_name')->get('designation')->result();
        echo '<option selected disabled value="">Select Designation</option>';
        foreach ($designation as $designation_value) {
?>
            <option value="<?php echo $designation_value->designation_id ?>"><?php echo $designation_value->designation_name ?></option>
<?php
        }
    }
    public function employee_unique_id_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('employee_unique_id', $parameter)
                ->from('employee');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('employee');
        }
        $sql = $this->db->get()->result();
        $data_employee = array();
        foreach ($sql as $value) {
            array_push($data_employee, $value->employee_unique_id);
        }
        echo json_encode($data_employee);
    }

    public function view_employee()
    {


        $employee_unique_id = $this->input->post('employee_unique_id');
        $department_id = $this->input->post('department_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $config['base_url'] = base_url() . "index.php/EmployeeController/view_employee";
        $config['total_rows'] = $this->EmployeeModel->count_all_employees($employee_unique_id, $department_id, $from_date, $to_date);
        $config['per_page'] = 100;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
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


        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);

        $data['employee_data'] = $this->EmployeeModel->get_employee($config['per_page'], $page, $employee_unique_id, $department_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('employee/view_employee', $data, true);
        $page_data = array(
            'page_name' => 'employee/view_employee',
            'page_title' => 'View Employee',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function delete_this_employee($employee_id)
    {
        $this->db->where('employee_id', $employee_id)->delete('employee');
        $sdata['employee_deleted'] = 'Data has been deleted successfully.';
        $this->session->set_userdata($sdata);
        redirect(base_url('view-employee'));
    }

    public function update_employee_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $config['upload_path'] = 'assets/employee/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $picture = '';
            $this->upload->do_upload('picture');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            if ($sdata['file_name'] == '') {
                $picture =  $this->input->post('previous_picture');
            } else {
                $picture = $sdata['file_name'];
            }



            $config['upload_path'] = 'assets/employee/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $nid = '';
            $this->upload->do_upload('nid');
            $sdata = $this->upload->data();
            if ($sdata['file_name'] == '') {
                $nid =  $this->input->post('previous_picture');
            } else {
                $nid = $sdata['file_name'];
            }


            $employee_id = $this->input->post('employee_id');
            // Get form data
            $data = array(
                'employee_name' => $this->input->post('employee_name'),
                'employee_unique_id' => $this->input->post('employee_unique_id'),
                'father_name' => $this->input->post('father_name'),
                'mother_name' => $this->input->post('mother_name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'nid_number' => $this->input->post('nid_number'),
                'present_address' => $this->input->post('present_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'nationality_id' => $this->input->post('nationality_id'),
                'religion_id' => $this->input->post('religion_id'),
                'date_of_birth' => date('Y-m-d', strtotime($this->input->post('date_of_birth'))),
                'date_of_join' => !empty($this->input->post('date_of_join')) ? date('Y-m-d', strtotime($this->input->post('date_of_join'))) : null,
                'age' => $this->input->post('age'),
                'picture' => $picture,
                'marital_status_id' => $this->input->post('marital_status_id'),
                'nid' => $nid,
                'department_id' => $this->input->post('department_id'),
                'designation_id' => $this->input->post('designation_id'),
                'men_power_category_id' => $this->input->post('men_power_category_id'),
                'is_disable' => $this->input->post('is_disable'),
                'disabilites_description' => $this->input->post('disabilites_description'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'branch_name' => $this->input->post('branch_name'),
                'account_name' => $this->input->post('account_name'),
                'account_number' => $this->input->post('account_number'),

                'basic_salary' => $this->input->post('basic_salary'),

                'tin' => $this->input->post('tin'),
                'transport_allowance' => $this->input->post('transport_allowance'),
                'house_rent' => $this->input->post('house_rent'),
                'medical_allowance' => $this->input->post('medical_allowance'),

                'communication_allowance' => $this->input->post('communication_allowance'),
                'gross_salary' => $this->input->post('gross_salary'),
                'contact_person' => $this->input->post('contact_person'),
                'relation_id' => $this->input->post('relation_id'),
                'contact_no' => $this->input->post('contact_no'),
                'contact_email' => $this->input->post('contact_email'),
                'status' => $this->input->post('status'),

            );
            $this->db->where('employee_id', $employee_id)->update('employee', $data);
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Employee Updated and Employee Id is=' . $employee_id
            );
            $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['employee_updated'] = 'Data has been updated successfully.';
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function print_employee($director_id)
    {
        $data['director_id'] = $director_id;
        $this->load->view('director/print_director', $data, true);
        $page_data = array(
            'page_name' => 'director/print_director',
            'page_title' => 'Print Director',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function add_employee()
    {
        $page_data = array(
            'page_name' => 'employee/add_employee',
            'page_title' => 'Add Emplyee',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_employee($employee_id)
    {
        $data['employee_id'] = $employee_id;
        $this->load->view('employee/edit_employee', $data, true);
        $page_data = array(
            'page_name' => 'employee/edit_employee',
            'page_title' => 'Edit Employee',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function save_employee_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $serial_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'employee_unique_id' => $this->input->post('employee_unique_id'),
                'employee_unique_id_serial' => $this->input->post('employee_unique_id_serial'),
            );
            $this->db->insert('employee_uniqueid_table', $serial_data);

            $config['upload_path'] = 'assets/employee/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $picture = '';
            $this->upload->do_upload('picture');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            $picture = $sdata['file_name'];


            $config['upload_path'] = 'assets/employee/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $nid = '';
            $this->upload->do_upload('nid');
            $sdata = $this->upload->data();
            $nid = $sdata['file_name'];


            // Get form data
            $data = array(
                'employee_name' => $this->input->post('employee_name'),
                'employee_unique_id' => $this->input->post('employee_unique_id'),
                'father_name' => $this->input->post('father_name'),
                'mother_name' => $this->input->post('mother_name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'nid_number' => $this->input->post('nid_number'),
                'present_address' => $this->input->post('present_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'nationality_id' => $this->input->post('nationality_id'),
                'religion_id' => $this->input->post('religion_id'),
                'date_of_birth' => date('Y-m-d', strtotime($this->input->post('date_of_birth'))),
                'date_of_join' => !empty($this->input->post('date_of_join')) ? date('Y-m-d', strtotime($this->input->post('date_of_join'))) : null,
                'age' => $this->input->post('age'),
                'picture' => $picture,
                'marital_status_id' => $this->input->post('marital_status_id'),
                'nid' => $nid,
                'department_id' => $this->input->post('department_id'),
                'men_power_category_id' => $this->input->post('men_power_category_id'),
                'designation_id' => $this->input->post('designation_id'),
                'is_disable' => $this->input->post('is_disable'),
                'disabilites_description' => $this->input->post('disabilites_description'),
                'bank_name_id' => $this->input->post('bank_name_id'),
                'branch_name' => $this->input->post('branch_name'),
                'account_name' => $this->input->post('account_name'),
                'account_number' => $this->input->post('account_number'),

                'basic_salary' => $this->input->post('basic_salary'),

                'tin' => $this->input->post('tin'),
                'transport_allowance' => $this->input->post('transport_allowance'),
                'house_rent' => $this->input->post('house_rent'),
                'medical_allowance' => $this->input->post('medical_allowance'),

                'communication_allowance' => $this->input->post('communication_allowance'),
                'gross_salary' => $this->input->post('gross_salary'),
                'contact_person' => $this->input->post('contact_person'),
                'relation_id' => $this->input->post('relation_id'),
                'contact_no' => $this->input->post('contact_no'),
                'contact_email' => $this->input->post('contact_email'),
                'user_id' => $this->session->userdata('user_id'),
                'status' => $this->input->post('status'),
            );
            $this->db->insert('employee', $data);
            $employee_id = $this->db->insert_id();
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Employee Info added and Employee Id is=' . $employee_id
            );
            $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data saved successfully.');


            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function employee_salary_report()
    {
        $page_data = array(
            'page_name' => 'employee/employee_salary_report',
            'page_title' => 'Emplyee Salary Report',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function salary_report_details_load()
    {
        $data = array();
        $data['employee_id'] = $_POST['employee_id'];
        $data['month'] = $_POST['month'];
        $data['session'] = $_POST['session'];
        //  echo '<pre>';
        //  print_r($data);
        // die;
        $this->load->view('employee/salary_report_details_load', $data);
    }

    public function all_employee_salary_report_details()
    {
        $data = array();

        $data['month'] = $_POST['month'];
        $data['session'] = $_POST['session'];
        $this->load->view('employee/all_employee_salary_report_details', $data);
    }

    public function all_employee_salary_report()
    {
        $page_data = array(
            'page_name' => 'employee/all_employee_salary_report',
            'page_title' => 'All Emplyee Salary Report',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
