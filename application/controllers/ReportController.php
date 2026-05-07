<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportController
 *
 * @author saiful
 */
class ReportController extends CI_Controller
{

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
    public function load_user_based_sell_report_for_user()
    {
        // Retrieve the user_id from POST request
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $user_id = $this->session->userdata('user_id');


        // Prepare data for the view - Convert dates from d-m-Y to Y-m-d format
        $data['to_date'] = !empty($to_date) ? date('Y-m-d', strtotime($to_date)) : '';
        $data['from_date'] = !empty($from_date) ? date('Y-m-d', strtotime($from_date)) : '';
        $data['user_id'] = $user_id;
        // Capture the HTML output of the view
        $html = $this->load->view('patient/report/load_user_based_sell_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }
    
    public function load_all_user_based_sell_report_for_admin()
    {
        // Retrieve the user_id from POST request
        $user_id = $this->input->post('user_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        // Prepare data for the view - Convert dates from d-m-Y to Y-m-d format
        $data['to_date'] = !empty($to_date) ? date('Y-m-d', strtotime($to_date)) : '';
        $data['from_date'] = !empty($from_date) ? date('Y-m-d', strtotime($from_date)) : '';
        $data['user_id'] = $user_id;
        // Capture the HTML output of the view
        $html = $this->load->view('patient/report/load_all_user_based_sell_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }
    public function load_user_based_sell_report_for_admin()
    {
        // Retrieve the user_id from POST request
        $user_id = $this->input->post('user_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        // Prepare data for the view - Convert dates from d-m-Y to Y-m-d format
        $data['to_date'] = !empty($to_date) ? date('Y-m-d', strtotime($to_date)) : '';
        $data['from_date'] = !empty($from_date) ? date('Y-m-d', strtotime($from_date)) : '';
        $data['user_id'] = $user_id;
        // Capture the HTML output of the view
        $html = $this->load->view('patient/report/load_user_based_sell_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }
    public function load_date_wise_summary_report()
    {
        // Retrieve the user_id from POST request
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        // Prepare data for the view
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        // Capture the HTML output of the view
        $html = $this->load->view('report/load_date_wise_summary_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }

    public function my_reception_sell_report()
    {

        $page_data = array(
            'page_name' => 'patient/report/my_reception_sell_report',
            'page_title' => 'IPD Patient Report ',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function reports_dashboard()
    {
        $page_data = array(
            'page_name' => 'report/reports_dashboard',
            'page_title' => 'Report Dashboard ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function bank_deposit_report()
    {
        $page_data = array(
            'page_name' => 'bank/bank_deposit_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_deposit_report_details()
    {
        $data = array();
        $data['bank_id'] = $_POST['bank_id'];
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $this->load->view('bank/bank_deposit_report_details', $data);
    }

    public function today_report()
    {

        $page_data = array(
            'page_name' => 'report/today_report',
            'page_title' => 'Today Details Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_test_reference_report()
    {

        $page_data = array(
            'page_name' => 'report/doctor_test_reference_report',
            'page_title' => 'Doctors Test Reference Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_test_referece_report_load($ids)
    {
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['doctor_id'] = $ids_array[2];
        $this->load->view('report/doctor_test_referece_report_load_details', $data);
    }

    public function purchase_report()
    {

        $page_data = array(
            'page_name' => 'report/purchase_report',
            'page_title' => 'Purchase Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function stock_balance_report()
    {
        $this->load->view('report/stock_balance_reprot');
    }

    public function purchase_report_details_load()
    {
        $data = array();
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $this->load->view('report/purchase_report_details_load', $data);
    }

    public function dealer_report_amount_paid_due()
    {

        $page_data = array(
            'page_name' => 'report/dealer_report_amount_paid_due',
            'page_title' => 'Today Details Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function today_report_details_load($ids)
    {
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('report/today_report_details_load', $data);
    }

    public function bank_withdraw_report()
    {
        $page_data = array(
            'page_name' => 'bank/bank_withdraw_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_withdraw_report_details()
    {
        $data = array();
        $data['bank_id'] = $_POST['bank_id'];
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $this->load->view('bank/bank_withdraw_report_details', $data);
    }

    public function bank_balance_report()
    {
        $page_data = array(
            'page_name' => 'bank/bank_balance_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_balance_report_details()
    {
        $data = array();
        //        $data['bank_id'] = $_POST['bank_id'];
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $this->load->view('bank/bank_balance_report_details', $data);
    }

    //put your code here
    public function income_report()
    {
        $page_data = array(
            'page_name' => 'report/income_report',
            'page_title' => 'Income Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function headwise_expense_report()
    {
        $page_data = array(
            'page_name' => 'expense/headwise_expense_report',
            'page_title' => 'Head Wise Expense Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function all_employee_salary_report()
    {
        $page_data = array(
            'page_name' => 'employee/all_employee_salary_report',
            'page_title' => 'All Employee Salary Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function expense_details_load($ids)
    {
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['expense_head_id'] = $ids_array[2];
        $this->load->view('expense/expense_report_details', $data);
    }

    public function income_report_details_load($ids)
    {
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];

        $this->load->view('report/income_report_details_load', $data);
    }

    public function profit_report()
    {
        $page_data = array(
            'page_name' => 'report/profit_report',
            'page_title' => 'Profit Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function sold_test_report()
    {
        $page_data = array(
            'page_name' => 'report/sold_test_report',
            'page_title' => 'Sold Test Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function sold_test_report_details_load($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['test_id'] = $data[2];
        $this->load->view('report/sold_test_report_details_load', $data);
    }

    public function balance_report()
    {
        $page_data = array(
            'page_name' => 'report/balance_report',
            'page_title' => 'Banalace Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function balance_report_details_load($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];

        $this->load->view('report/balance_report_details_load', $data);
    }

    public function profit_report_details_load()
    {
        $data = array();
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];

        $this->load->view('report/profit_report_details_load', $data);
    }

  

    public function expense_report()
    {
        $page_data = array(
            'page_name' => 'expense/expense_report',
            'page_title' => 'Expense Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function supplier_report()
    {
        $page_data = array(
            'page_name' => 'supplier/supplier_report',
            'page_title' => 'Supplier Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function employee_salary_report()
    {
        $page_data = array(
            'page_name' => 'employee/employee_salary_report',
            'page_title' => 'Emplyee Salary Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function dealer_report()
    {
        $page_data = array(
            'page_name' => 'dealer/dealer_report',
            'page_title' => 'Dealer Report',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
