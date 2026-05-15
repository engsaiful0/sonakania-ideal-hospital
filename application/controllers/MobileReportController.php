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
class MobileReportController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('Grocery_crud');

        $this->load->library('pagination');
    }

    public function mobile_report()
    {
        // Date filter via POST (HTML5 type=date sends Y-m-d). Default: today when not posted or invalid.
        $today_ymd = date('Y-m-d');
        $normalize_ymd = function ($s) {
            $s = is_string($s) ? trim($s) : '';
            if ($s === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
                return false;
            }
            $ts = strtotime($s . ' 00:00:00');
            return $ts !== false ? date('Y-m-d', $ts) : false;
        };

        $from_raw = $this->input->post('from_date', true);
        $to_raw = $this->input->post('to_date', true);
        $date_from = $normalize_ymd($from_raw !== false && $from_raw !== null ? $from_raw : '');
        $date_to = $normalize_ymd($to_raw !== false && $to_raw !== null ? $to_raw : '');
        if ($date_from === false || $date_from === '') {
            $date_from = $today_ymd;
        }
        if ($date_to === false || $date_to === '') {
            $date_to = $today_ymd;
        }
        if (strtotime($date_from) > strtotime($date_to)) {
            $tmp = $date_from;
            $date_from = $date_to;
            $date_to = $tmp;
        }
        $display_from = date('d-m-Y', strtotime($date_from));
        $display_to = date('d-m-Y', strtotime($date_to));

        $this->db->select_sum('nettotal');
        $this->db->from('medicine_sales');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_medicine_sells_today = $query->row()->nettotal ?? 0;

        $this->db->select_sum('payable');
        $this->db->from('opd_patient');
        $this->db->where('entry_date >=', $date_from)->where('entry_date <=', $date_to);
        $query = $this->db->get();
        $total_opd_payable_today = $query->row()->payable ?? 0;

        $this->db->select_sum('paid_amount');
        $this->db->from('ipd_patient');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('emergency');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_emergency_today = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('phygiotherapy');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_phygiotherapy_today = $query->row()->paid ?? 0;

        $this->db->select_sum('total_amount');
        $this->db->from('debit_voucher');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_debit_voucher_today = $query->row()->total_amount ?? 0;

        $this->db->select_sum('total_amount');
        $this->db->from('credit_voucher');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_credit_voucher_today = $query->row()->total_amount ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('ot_services');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_ot_service_paid_today = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_direct_sales');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_test_entry_today = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_due_collection');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_test_due_collection_today = $query->row()->paid ?? 0;

        $this->db->select_sum('total_return');
        $this->db->from('patient_test_entry_details');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $total_test_return_today = $query->row()->total_return ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('discharge');
        $this->db->where('discharge_date >=', $date_from)->where('discharge_date <=', $date_to);
        $query = $this->db->get();
        $total_discharge_value_today = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('medicine_sales');
        $this->db->where('bill_date >=', $date_from)->where('bill_date <=', $date_to);
        $query = $this->db->get();
        $total_medicine_sells_today = $query->row()->paid ?? 0;

        $this->db->select_sum('due_payment');
        $this->db->from('medicine_sales');
        $this->db->where('due_payment_date >=', $date_from)->where('due_payment_date <=', $date_to);
        $query = $this->db->get();
        $total_medicine_sells_due_payment = $query->row()->due_payment ?? 0;

        $this->db->select_sum('returnable_amount');
        $this->db->from('emergency');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $emergency_return_amount = $query->row()->returnable_amount ?? 0;

        $this->db->select_sum('returnable_amount');
        $this->db->from('phygiotherapy');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $phygiotherapy_return_amount = $query->row()->returnable_amount ?? 0;

        $this->db->select_sum('returnable_amount');
        $this->db->from('opd_patient');
        $this->db->where('entry_date >=', $date_from)->where('entry_date <=', $date_to);
        $query = $this->db->get();
        $opd_returnable_amount = $query->row()->returnable_amount ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('medicine_sale_return');
        $this->db->where('date >=', $date_from)->where('date <=', $date_to);
        $query = $this->db->get();
        $medicine_sales_return_amount = $query->row()->paid ?? 0;

        $total_income =
            $total_ot_service_paid_today +
            $total_discharge_value_today +
            $total_test_entry_today +
            $total_test_due_collection_today +
            $total_opd_payable_today +
            $total_ipd_paid_amount_today +
            $total_emergency_today +
            $total_phygiotherapy_today +
            $total_medicine_sells_today +
            $total_medicine_sells_due_payment +
            $total_credit_voucher_today;

        $total_return =
            $medicine_sales_return_amount +
            $opd_returnable_amount +
            $total_test_return_today +
            $emergency_return_amount +
            $phygiotherapy_return_amount;

        $total_expense = $total_debit_voucher_today;
        $total_balance = $total_income - $total_return - $total_expense;

        $data['input_date_from'] = $date_from;
        $data['input_date_to'] = $date_to;
        $data['display_from'] = $display_from;
        $data['display_to'] = $display_to;
        $data['total_emergency_today'] = $total_emergency_today;
        $data['total_phygiotherapy_today'] = $total_phygiotherapy_today;
        $data['total_medicine_sells_today'] = $total_medicine_sells_today;
        $data['total_credit_voucher_today'] = $total_credit_voucher_today;
        $data['total_debit_voucher_today'] = $total_debit_voucher_today;
        $data['total_ot_service_paid_today'] = $total_ot_service_paid_today;
        $data['total_test_entry_today'] = $total_test_entry_today;
        $data['total_test_due_collection_today'] = $total_test_due_collection_today;
        $data['total_test_return_today'] = $total_test_return_today;
        $data['total_discharge_value_today'] = $total_discharge_value_today;
        $data['total_medicine_sells_due_payment'] = $total_medicine_sells_due_payment;
        $data['emergency_return_amount'] = $emergency_return_amount;
        $data['phygiotherapy_return_amount'] = $phygiotherapy_return_amount;
        $data['opd_returnable_amount'] = $opd_returnable_amount;
        $data['medicine_sales_return_amount'] = $medicine_sales_return_amount;
        $data['total_income'] = $total_income;
        $data['total_return'] = $total_return;
        $data['total_expense'] = $total_expense;
        $data['total_balance'] = $total_balance;
        $data['total_opd_payable_today'] = $total_opd_payable_today;
        $data['total_ipd_paid_amount_today'] = $total_ipd_paid_amount_today;
        $this->load->view('mobile/mobile_report', $data);
        
    }
}
