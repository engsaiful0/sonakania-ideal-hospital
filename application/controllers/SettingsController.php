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
class SettingsController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }

    function index()
    {
        $page_data = array(
            'page_name' => 'settings/settings_dashbaord',
            'page_title' => 'Purchase',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_report_footer()
    {
        $page_data = array(
            'page_name' => 'settings/add_report_footer',
            'page_title' => 'Add Report Footer',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function add_report_footer_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('report_footer');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin'):
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->set_subject('Refort Footer');
        $crud->columns('report_footer_1', 'report_footer_2', 'report_footer_3');
        $crud->fields('report_footer_1', 'report_footer_2', 'report_footer_3');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function company_profile()
    {
        $page_data = array(
            'page_name' => 'settings/company_profile',
            'page_title' => 'Company Profile',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function company_profile_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('company');
        $crud->set_subject('Company');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin'):
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('company_name', 'address', 'vat');
        $crud->columns('company_name', 'address', 'logo', 'web', 'email', 'mobile', 'vat', 'opd_discount_days', 'consultant_fee', 'service_charge', 'assistatnt_fee', 'admission_reg_fee', 'report_instruction');
        $crud->fields('company_name', 'address', 'logo', 'web', 'email', 'mobile', 'vat', 'opd_discount_days', 'consultant_fee', 'service_charge', 'assistatnt_fee', 'admission_reg_fee', 'report_instruction');
        $crud->set_field_upload('logo', 'assets/images');
        $crud->unset_add();
        $crud->unset_delete();
        $crud->display_as('vat', 'Vat(%)');
        $output = $crud->render();

        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function department()
    {
        $page_data = array(
            'page_name' => 'settings/department',
            'page_title' => 'Add Department',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function user_role()
    {
        $page_data = array(
            'page_name' => 'settings/user_role/add',
            'page_title' => 'Add User Role',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_department_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('department');
        $crud->set_subject('Department');
        $crud->required_fields('department_name', 'type');
        $crud->columns('department_name', 'department_short_name', 'type');
        $crud->fields('department_name', 'department_short_name', 'type');
        $crud->field_type('type', 'dropdown', array('Clinical' => 'Clinical', 'Non-Clinical' => 'Non-Clinical'));
        $crud->display_as('department_name', 'Department Name');
        $crud->display_as('department_short_name', 'Department Short Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function module()
    {
        $page_data = array(
            'page_name' => 'settings/module',
            'page_title' => 'Module',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function module_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('module');
        $crud->set_subject('Module');
        $crud->required_fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function sub_module()
    {
        $page_data = array(
            'page_name' => 'settings/sub_module',
            'page_title' => 'Module',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function sub_module_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('sub_module');
        $crud->set_subject('Sub Module');
        $crud->columns('module_id', 'name');
        $crud->fields('module_id', 'name');
        $crud->set_relation('module_id', 'module', 'name');
        $crud->display_as('module_id', 'Module');
        $crud->required_fields('name', 'module_id');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function sms_api()
    {
        $page_data = array(
            'page_name' => 'settings/sms_api',
            'page_title' => 'Module',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function sms_api_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('sms_api');

        $crud->unset_add();
        // $crud->unset_edit();
        $crud->unset_delete();

        $crud->set_subject('SMS API');
        $crud->columns('url', 'api_key', 'senderid',  'is_opd_sms_send', 'is_ipd_sms_send', 'is_emergency_sms_send', 'is_phygiotherapy_sms_send', 'is_discharge_sms_send', 'is_test_entry_sms_send', 'is_test_result_ready_notification_sms_send', 'is_ot_service_sms_send', 'is_summary_send_to_admin', 'admin_phone1', 'admin_phone2', 'admin_phone3', 'is_test_due_sms_send', 'is_emergency_due_sms_send', 'is_phygiotherapy_due_sms_send');
        $crud->fields('url', 'api_key', 'senderid',  'is_opd_sms_send', 'is_ipd_sms_send', 'is_emergency_sms_send', 'is_phygiotherapy_sms_send', 'is_discharge_sms_send', 'is_test_entry_sms_send', 'is_test_result_ready_notification_sms_send', 'is_ot_service_sms_send', 'is_summary_send_to_admin', 'admin_phone1', 'admin_phone2', 'admin_phone3', 'is_test_due_sms_send', 'is_emergency_due_sms_send', 'is_phygiotherapy_due_sms_send');
        $crud->required_fields('url', 'api_key', 'senderid', 'is_opd_sms_send', 'is_ipd_sms_send', 'is_emergency_sms_send', 'is_phygiotherapy_sms_send', 'is_discharge_sms_send', 'is_test_entry_sms_send', 'is_test_result_ready_notification_sms_send', 'is_ot_service_sms_send', 'is_summary_send_to_admin');
        $crud->field_type('is_opd_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_ipd_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_emergency_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_phygiotherapy_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_discharge_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_test_entry_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_test_result_ready_notification_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_ot_service_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_summary_send_to_admin', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_test_due_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_emergency_due_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('is_phygiotherapy_due_sms_send', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function sms_template()
    {
        $page_data = array(
            'page_name' => 'settings/sms_template',
            'page_title' => 'Module',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function sms_template_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('sms_template');
        $crud->set_subject('SMS Template');
        $crud->columns('sms_body', 'type');
        $crud->fields('sms_body', 'type');
        $crud->field_type('type', 'dropdown', array('doctor_serial' => 'Doctor Serial', 'test_report_done' => 'Test Report Done', 'about_employee' => 'About Employee', 'about_doctor' => 'About Doctor', 'about_patient' => 'About Patient', 'all' => 'All'));
        $crud->required_fields('sms_body', 'type');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function designation()
    {
        $page_data = array(
            'page_name' => 'settings/designation',
            'page_title' => 'Debit Account',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function designation_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('designation');
        $crud->set_subject('Designation');
        $crud->set_relation('men_power_category_id', 'men_power_categories', 'name');
        $crud->required_fields('designation_name', 'men_power_category_id');
        $crud->columns('designation_name', 'men_power_category_id');
        $crud->fields('designation_name', 'men_power_category_id');
        $crud->display_as('men_power_category_id', 'Category');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function supplier()
    {
        $page_data = array(
            'page_name' => 'settings/supplier',
            'page_title' => 'Debit Account',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function supplier_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('supplier');
        $crud->set_subject('Supplier');
        $crud->field_type('type', 'dropdown', array('medicine_supplier' => 'Medicine Supplier', 'canteen_raw_goods_supplier' => 'Canten Raw Goods Supplier', 'management_goods_supplier' => 'Management Goods Supplier'));
        $crud->required_fields('name', 'type');


        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function monthly_bill()
    {
        $page_data = array(
            'page_name' => 'settings/monthly_bill',
            'page_title' => 'Monthly Bill',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function monthly_bill_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('software_bills');
        $crud->set_subject('Software Bill');
        $crud->field_type('month', 'dropdown', array(
            'January' => 'January',
            'February' => 'February',
            'March' => 'March',
            'April' => 'April',
            'May' => 'May',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
            'September' => 'September',
            'October' => 'October',
            'November' => 'November',
            'December' => 'December'
        ));

        $crud->field_type('year', 'dropdown', array('2025' => '2025', '2026' => '2026', '2027' => '2027', '2028' => '2028', '2029' => '2029', '2030' => '2030'));
        $crud->field_type('status', 'dropdown', array('due' => 'Due', 'paid' => 'Paid'));
        $crud->required_fields('name', 'type');


        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }


    public function expertise()
    {
        $page_data = array(
            'page_name' => 'settings/expertise',
            'page_title' => 'Add Doctor Expertise',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_expertise_frame()
    {
        $crud = new Grocery_crud();

        $crud->set_table('doctorspecilization');
        $crud->set_subject('Doctor Specialization');
        $crud->required_fields('specilization');
        $crud->columns('specilization');
        $crud->fields('specilization');
        $crud->display_as('specilization', 'Doctor Specialization');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function blood_group_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('blood_group');
        $crud->set_subject('Blood Group');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function blood_group()
    {
        $page_data = array(
            'page_name' => 'settings/blood_group',
            'page_title' => 'Blood Group',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function nationality_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('nationality');
        $crud->set_subject('Nationality');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function nationality()
    {
        $page_data = array(
            'page_name' => 'settings/nationality',
            'page_title' => 'Nationality',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function profession_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('profession');
        $crud->set_subject('Profession');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function profession()
    {
        $page_data = array(
            'page_name' => 'settings/profession',
            'page_title' => 'Profession',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function religion_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('religion');
        $crud->set_subject('Religion');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function religion()
    {
        $page_data = array(
            'page_name' => 'settings/religion',
            'page_title' => 'Religion',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function relation_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('relation');
        $crud->set_subject('Relation');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function relation()
    {
        $page_data = array(
            'page_name' => 'settings/relation',
            'page_title' => 'Relation',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function bank_name_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('bank_name');
        $crud->set_subject('Bank Name');
        $crud->required_fields('name', 'status');
        $crud->columns('name', 'status');
        $crud->fields('name', 'status');
        $crud->field_type('status', 'dropdown', array('active' => 'Active', 'inactive' => 'Inactive'));
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function bank_name()
    {
        $page_data = array(
            'page_name' => 'settings/bank_name',
            'page_title' => 'Bank Name',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function mobile_banking_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('mobile_banks');
        $crud->set_subject('Mobile Banks');
        $crud->required_fields('account_name', 'account_number', 'type');
        $crud->columns('account_name', 'account_number', 'type');
        $crud->fields('account_name', 'account_number', 'type');
        $crud->field_type('type', 'dropdown', array('Personal' => 'Personal', 'Marchent' => 'Marchent'));
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function mobile_banking()
    {
        $page_data = array(
            'page_name' => 'settings/mobile_banking',
            'page_title' => 'Mobile Banking',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_account_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('bank_accounts');
        $crud->set_subject('Bank Account');
        $crud->required_fields('bank_name_id', 'account_number', 'account_name');
        $crud->set_relation('bank_name_id', 'bank_name', 'name');
        $crud->columns('bank_name_id', 'account_number', 'account_name', 'opening_balance', 'details');
        $crud->display_as('bank_name_id', 'Bank Name');
        $crud->fields('bank_name_id', 'account_number', 'account_name', 'opening_balance', 'details');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function bank_account()
    {
        $page_data = array(
            'page_name' => 'settings/bank_account',
            'page_title' => 'Bank Account',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function marital_status_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('marital_status');
        $crud->set_subject('Marital Status');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function marital_status()
    {
        $page_data = array(
            'page_name' => 'settings/marital_status',
            'page_title' => 'Marital Status',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
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

    public function add_employee()
    {
        $page_data = array(
            'page_name' => 'employee/add_employee',
            'page_title' => 'Add Emplyee',
            'sidebar' => 'employee/employee_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function referred_by()
    {
        $page_data = array(
            'page_name' => 'settings/referred_by',
            'page_title' => 'Add Reffered By',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function referred_by_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('referred_by');
        $crud->set_subject('Referred By');
        $crud->required_fields('referred_by_name');
        $crud->columns('referred_by_name', 'mobile', 'email', 'address');
        $crud->fields('referred_by_name', 'mobile', 'email', 'address');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
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


    public function add_employee_payroll()
    {
        $page_data = array(
            'page_name' => 'employee/add_employee_payroll',
            'page_title' => 'Add Emplyee Payroll',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function user_type()
    {
        $page_data = array(
            'page_name' => 'settings/user_type',
            'page_title' => 'User Type',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function user_type_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('user_type');
        $crud->set_subject('User Type');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }


    public function user()
    {
        $page_data = array(
            'page_name' => 'settings/user',
            'page_title' => 'User',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function user_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('user');
        $crud->set_subject('User');
        $crud->required_fields('user_type_id', 'name', 'user_name', 'password', 'is_active');
        $crud->set_field_upload('picture', 'assets');
        $crud->set_relation('user_type_id', 'user_type', 'name');
        $crud->display_as('user_type_id', 'User Type');
        $crud->field_type('is_active', 'dropdown', array('active' => 'Active', 'in_active' => 'In Active'));
        $crud->columns('user_type_id', 'name', 'user_name', 'password', 'email', 'mobile', 'is_active', 'picture');
        $crud->fields('user_type_id', 'name', 'user_name', 'password', 'email', 'mobile', 'is_active', 'picture');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function credit_account()
    {
        $page_data = array(
            'page_name' => 'settings/credit_account',
            'page_title' => 'Credit Account',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function credit_account_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('credit_account');
        $crud->set_subject('Credit Account');
        $crud->required_fields('account_name', 'account_number');
        $crud->columns('account_name', 'account_number');
        $crud->fields('account_name', 'account_number');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function debit_account()
    {
        $page_data = array(
            'page_name' => 'settings/debit_account',
            'page_title' => 'Debit Account',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function debit_account_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('debit_account');
        $crud->set_subject('Debit Account');
        $crud->required_fields('account_name', 'account_number', 'legend','daily_limit','monthly_limit','yearly_limit');
        $crud->columns('account_name', 'account_number', 'legend','daily_limit','monthly_limit','yearly_limit');
        $crud->fields('account_name', 'account_number', 'legend','daily_limit','monthly_limit','yearly_limit');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function item()
    {
        $page_data = array(
            'page_name' => 'settings/item',
            'page_title' => 'Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function item_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('item');
        $crud->set_subject('Item');
        $crud->required_fields('item_name');
        $crud->columns('item_name', 'unit_price', 'opening_stock');
        $crud->fields('item_name', 'unit_price', 'opening_stock');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function emergency_service_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('emergency_service');
        $crud->set_subject('Emergency Service');
        $crud->required_fields('name', 'price', 'commissionable', 'commissioin_rate', 'commission_type');
        $crud->required_fields('name', 'price', 'commissionable', 'commissioin_rate', 'commission_type');
        $crud->field_type('commissionable', 'dropdown', array('yes' => 'Yes', 'no' => 'No'));
        $crud->field_type('commission_type', 'dropdown', array('fixed' => 'Fixed', 'percentage' => 'Percentage'));
        $crud->columns('name', 'price', 'commissionable', 'commissioin_rate');
        $crud->fields('name', 'price', 'commissionable', 'commissioin_rate');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function emergency_service()
    {

        $page_data = array(
            'page_name' => 'settings/emergency_service',
            'page_title' => 'Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ipd_service_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('ipd_service_item');
        $crud->set_subject('IPD Service');
        $crud->required_fields('name', 'price', 'service_type');
        $crud->columns('name', 'test_category_id', 'price', 'service_type');
        $crud->set_relation('test_category_id', 'test_categories', 'test_category_name');
        $crud->field_type('service_type', 'dropdown', array('Required' => 'Required', 'Optional' => 'Optional'));
        $crud->fields('name', 'test_category_id', 'price', 'service_type');
        $crud->display_as('service_type', 'Service Type');
        $crud->display_as('test_category_id', 'Catagory Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function payment_method()
    {
        $page_data = array(
            'page_name' => 'settings/payment_method',
            'page_title' => 'Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function payment_method_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('payment_methods');
        $crud->set_subject('Payment Method');
        $crud->required_fields('name');
        $crud->columns('name', 'remarks');
        $crud->fields('name', 'remarks');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function surgery()
    {
        $page_data = array(
            'page_name' => 'settings/surgery',
            'page_title' => 'Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function surgery_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('surgeries');
        $crud->set_subject('Surgery');
        $crud->required_fields('name', 'price');
        $crud->columns('name', 'price');
        $crud->fields('name', 'price');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function men_power_category()
    {
        $page_data = array(
            'page_name' => 'settings/men_power_category',
            'page_title' => 'Men Power Category',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function men_power_category_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('men_power_categories');
        $crud->set_subject('Men Power Category');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function ipd_service()
    {

        $page_data = array(
            'page_name' => 'settings/ipd_service',
            'page_title' => 'IPD Service',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function phygiotherapy_service_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('phygiotherapy_service');
        $crud->set_subject('Phygiotherapy Service');
        $crud->required_fields('name', 'price');
        $crud->columns('name', 'price');
        $crud->fields('name', 'price');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function phygiotherapy_service()
    {

        $page_data = array(
            'page_name' => 'settings/phygiotherapy_service',
            'page_title' => 'Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function phygiotherapy_service_iframe1()
    {
        $crud = new Grocery_crud();
        $crud->set_table('phygiotherapy_service');
        $crud->set_subject('Phygiotherapy Service');

        $crud->required_fields('name', 'price');


        $crud->columns('name', 'price', 'description');
        $crud->fields('name', 'price', 'description');

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function phygiotherapy_service1()
    {
        $page_data = array(
            'page_name' => 'settings/phygiotherapy_service',
            'page_title' => 'Phygiotherapy Service',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
