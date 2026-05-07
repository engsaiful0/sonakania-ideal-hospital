<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
    }

    public function getDailyIncomeSummary()
    {
          // CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // For preflight request
            exit(0);
        }
        
        $today = date('Y-m-d');

        $this->db->select_sum('nettotal');
        $this->db->from('medicine_sales');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $net_medicine_sales = $query->row()->nettotal ?? 0;

        $this->db->select_sum('payable');
        $this->db->from('opd_patient');
        $this->db->where('entry_date', $today);
        $query = $this->db->get();
        $opd_payable = $query->row()->payable ?? 0;

        $this->db->select_sum('paid_amount');
        $this->db->from('ipd_patient');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $ipd_paid = $query->row()->paid_amount ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('emergency');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $emergency_paid = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('phygiotherapy');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $physiotherapy_paid = $query->row()->paid ?? 0;

        $this->db->select_sum('total_amount');
        $this->db->from('debit_voucher');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $debit_voucher = $query->row()->total_amount ?? 0;

        $this->db->select_sum('total_amount');
        $this->db->from('credit_voucher');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $credit_voucher = $query->row()->total_amount ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('ot_services');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $ot_service_paid = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_direct_sales');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $test_direct = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_due_collection');
        $this->db->where('date', $today);
        $query = $this->db->get();
        $test_due = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('discharge');
        $this->db->where('discharge_date', $today);
        $query = $this->db->get();
        $discharge_paid = $query->row()->paid ?? 0;

        $this->db->select_sum('paid');
        $this->db->from('medicine_sales');
        $this->db->where('bill_date', $today);
        $query = $this->db->get();
        $medicine_paid = $query->row()->paid ?? 0;

        $total_income = $net_medicine_sales + $opd_payable + $ipd_paid + $emergency_paid + $physiotherapy_paid + $credit_voucher + $ot_service_paid + $test_direct + $test_due + $discharge_paid + $medicine_paid;
        $total_expense =$debit_voucher;
        $total_balance = $total_income - $total_expense;
            $summary = [
                'net_medicine_sales' => $net_medicine_sales,
                'opd_payable' => $opd_payable,
                'ipd_paid' => $ipd_paid,
                'emergency_paid' => $emergency_paid,
                'physiotherapy_paid' => $physiotherapy_paid,
                'debit_voucher' => $debit_voucher,
                'credit_voucher' => $credit_voucher,
                'ot_service_paid' => $ot_service_paid,
                'test_direct' => $test_direct,
                'test_due' => $test_due,
                'discharge_paid' => $discharge_paid,
                'medicine_paid' => $medicine_paid,
                'total_income' => $total_income,
                'total_expense' => $total_expense,
                'total_balance' => $total_balance
            ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($summary));
    }
}
