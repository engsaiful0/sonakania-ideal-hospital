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
class AccounceController extends CI_Controller
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
        $page_data = array(
            'page_name' => 'accounce/accounce_dashboard',
            'page_title' => 'Accounce Dashbaord',
            'sidebar' => 'accounce/accounce_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_employee_salary()
    {
        $page_data = array(
            'page_name' => 'employee/add_employee_salary',
            'page_title' => 'Add Emplyee Salary',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_employee_salary_iframe()
    {
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

    public function add_employee_payroll()
    {
        $page_data = array(
            'page_name' => 'employee/add_employee_payroll',
            'page_title' => 'Add Emplyee Payroll',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_employee_payroll_iframe()
    {
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

    public function add_employee()
    {
        $page_data = array(
            'page_name' => 'employee/add_employee',
            'page_title' => 'Add Emplyee',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function employee_salary_report()
    {
        $page_data = array(
            'page_name' => 'employee/employee_salary_report',
            'page_title' => 'Emplyee Salary Report',
            'sidebar' => 'employee/employee_sidebar'
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
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_employee_iframe()
    {
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
