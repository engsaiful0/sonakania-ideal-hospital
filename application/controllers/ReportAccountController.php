<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportAccountController
 *
 * @author saiful
 */
class ReportAccountController extends CI_Controller
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
    public function purpose_wise_account_credit_voucher_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/purpose_wise_account_credit_voucher_report',
            'page_title' => 'Credit Voucher Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function purpose_wise_account_credit_voucher_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('accounce/report/purpose_wise_account_credit_voucher_report_details', $data);
    }

    public function purpose_wise_account_debit_voucher_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/purpose_wise_account_debit_voucher_report',
            'page_title' => 'Credit Voucher Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function purpose_wise_account_debit_voucher_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('accounce/report/purpose_wise_account_debit_voucher_report_details', $data);
    }
    public function all_users_collection_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/all_users_collection_report',
            'page_title' => 'All Users Collection Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function due_report_details_load()
    {
        $data = array();
        $data['from_date'] = $this->input->post('from_date');
        $data['to_date'] = $this->input->post('to_date');
        $html = $this->load->view('accounce/report/due_report_details_load', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }

    public function due_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/due_report',
            'page_title' => 'Due Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function monthly_software_bill()
    {
        $page_data = array(
            'page_name' => 'accounce/report/monthly_software_bill',
            'page_title' => 'Software Bill ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function software_bill_print($software_bill_id)
    {
        $data['software_bill_id'] = $software_bill_id;
        $this->load->view('accounce/report/software_bill_print', $data, true);
        $page_data = array(
            'page_name' => 'accounce/report/software_bill_print',
            'page_title' => 'Software Bill ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function all_users_collection_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['user_id'] = $ids_array[2];
        $this->load->view('accounce/report/all_users_collection_report_details', $data);
    }

    public function account_credit_voucher_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/account_credit_voucher_report',
            'page_title' => 'Credit Voucher Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function account_credit_voucher_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['credit_account_id'] = $ids_array[2];
        $this->load->view('accounce/report/account_credit_voucher_report_details', $data);
    }
    public function account_journal_voucher_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/account_journal_voucher_report',
            'page_title' => 'Journal Voucher Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function account_journal_voucher_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['debit_account_id'] = $ids_array[2];
        $data['credit_account_id'] = $ids_array[3];

        $this->load->view('accounce/report/account_journal_voucher_report_details', $data);
    }

    public function account_debit_voucher_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/account_debit_voucher_report',
            'page_title' => 'HRM Employee List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function account_debit_voucher_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['debit_account_id'] = $ids_array[2];
        $this->load->view('accounce/report/account_debit_voucher_report_details', $data);
    }
    public function account_purchase_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/account_purchase_report',
            'page_title' => 'HRM Doctor List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function account_purchase_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('accounce/report/account_purchase_report_details', $data);
    }
    public function account_issue_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/account_issue_report',
            'page_title' => 'HRM Increment List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function account_issue_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('accounce/report/account_issue_report_details', $data);
    }
    public function daily_summary_report()
    {
        $page_data = array(
            'page_name' => 'accounce/report/daily_summary_report',
            'page_title' => 'HRM Increment List Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function daily_summary_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];


        $this->load->view('accounce/report/daily_summary_report_details', $data);
    }
}
