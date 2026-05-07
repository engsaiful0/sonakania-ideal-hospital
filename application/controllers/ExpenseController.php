<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExpenseController
 *
 * @author Lenovo
 */
class ExpenseController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            // redirect('LoginController');
        }
    }

    public function index() {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_income_head() {
        $page_data = array(
            'page_name' => 'expense/add_income_head',
            'page_title' => 'Add Income Head',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_income_head_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('income_head');
        $crud->set_subject('Income Head');

        $crud->required_fields('income_head_name', 'short_name');
        $crud->fields('income_head_name', 'short_name');
        $crud->columns('income_head_name', 'short_name');

        $crud->display_as('income_head_name', 'Income Head Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_expense_all() {
        $page_data = array(
            'page_name' => 'expense/add_expense_all',
            'page_title' => 'Add Expense All',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function headwise_expense_report() {
        $page_data = array(
            'page_name' => 'expense/headwise_expense_report',
            'page_title' => 'Head Wise Expense Report ',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function headwise_expense_report_details() {
        $data = array();

        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $this->load->view('expense/headwise_expense_report_details', $data);
    }

    public function income_report() {
        $page_data = array(
            'page_name' => 'expense/income_report',
            'page_title' => 'Income Report ',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function income_details_load($ids) {
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['income_head_id'] = $data[2];
        $this->load->view('expense/income_report_details', $data);
    }

    public function expense_report() {
        $page_data = array(
            'page_name' => 'expense/expense_report',
            'page_title' => 'Expense Report ',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function expense_report_details() {
        $data = array();
        $data['expense_head_id'] = $_POST['expense_head_id'];
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $this->load->view('expense/expense_report_details', $data);
    }

    public function add_income() {
        $page_data = array(
            'page_name' => 'expense/add_income',
            'page_title' => 'Add Income',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_expense_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('expense');
        $crud->set_subject('Expense');

        $crud->required_fields('amount', 'expense_head_id', 'date');
        $crud->set_relation('expense_head_id', 'expense_head', 'expense_head_name');
        $crud->fields('voucher_no', 'expense_head_id', 'amount', 'date');
        $crud->columns('voucher_no', 'expense_head_id', 'amount', 'date');

        $crud->display_as('expense_head_id', 'Expense Head Name');

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_income_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('income');
        $crud->set_subject('Income');

        $crud->required_fields('amount', 'income_head_id', 'date');
        $crud->set_relation('income_head_id', 'income_head', 'income_head_name');
        $crud->fields('voucher_no', 'income_head_id', 'amount', 'date');
        $crud->columns('voucher_no', 'income_head_id', 'amount', 'date');

        $crud->display_as('income_head_id', 'Income Head Name');

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_expense() {
        $page_data = array(
            'page_name' => 'expense/add_expense',
            'page_title' => 'Add Expense',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_expense_head() {
        $page_data = array(
            'page_name' => 'expense/add_expense_head',
            'page_title' => 'Add Expense Head',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_expense_head_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('expense_head');
        $crud->set_subject('Expense Head');

        $crud->required_fields('expense_head_name', 'short_name');
        $crud->fields('expense_head_name', 'short_name');
        $crud->columns('expense_head_name', 'short_name');

        $crud->display_as('expense_head_name', 'Expense Head Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function expense_all_save() {
        $date = $this->input->post('date');
        $expense_head_id = $this->input->post('expense_head_id');
        $amount = $this->input->post('amount');
        $voucher_no = $this->input->post('voucher_no');
        for ($i = 0; $i < count($amount); $i++) {
            if ($amount[$i] == '') {
                continue;
            } else {
                $data = array(
                    'amount' => $amount[$i],
                    'voucher_no' => $voucher_no[$i],
                    'expense_head_id' => $expense_head_id[$i],
                    'date' => date('Y-m-d', strtotime($date))
                );
                $this->db->insert('expense', $data);
            }
        }
        $sdata['success'] = 'payment successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'expense/add_expense_all',
            'page_title' => 'Add Expense All',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

}
