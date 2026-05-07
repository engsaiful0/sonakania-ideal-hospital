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
class CronController extends CI_Controller
{

    public function sendDailySummary()
    {

        $this->db->select_sum('nettotal');
        $this->db->from('medicine_sales');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_medicine_sells_today = $query->row()->nettotal ?? 0; // Return 0 if no income found

        $this->db->select_sum('payable');
        $this->db->from('opd_patient');
        $this->db->where('entry_date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_opd_payable_today = $query->row()->payable ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid_amount');
        $this->db->from('ipd_patient');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('emergency');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_emergency_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('phygiotherapy');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_phygiotherapy_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('total_amount');
        $this->db->from('debit_voucher');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_debit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

        $this->db->select_sum('total_amount');
        $this->db->from('credit_voucher');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_credit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('ot_services');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_ot_service_paid_today = $query->row()->paid ?? 0; // Return 0 if no income found


        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_direct_sales');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_test_entry_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_due_collection');
        $this->db->where('date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_test_due_collection_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('discharge');
        $this->db->where('discharge_date', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        $total_discharge_value_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $grand_total_income = $total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today + $total_credit_voucher_today;
        $grand_total_expense = $total_debit_voucher_today;
        $grand_total_balance = ($total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today + $total_credit_voucher_today) - ($total_debit_voucher_today);
        $this->sms_send($grand_total_income, $grand_total_expense, $grand_total_balance); //To send 
    }
    function sms_send($grand_total_income, $grand_total_expense, $grand_total_balance)
    {

        $sms_api = getSMSAPI();
        if ($sms_api->is_summary_send_to_admin == 'yes') { // if is_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $sms_api->admin_phone1 . ",88" . $sms_api->admin_phone2 . ",88" . $sms_api->admin_phone3;
            //$number = "88" . $sms_api->admin_phone1;
           // $number2 = "88" . $sms_api->admin_phone2;
           // $number2 = "88" . $sms_api->admin_phone3;
            $message = "Dear Admin, Today business summary is- Total Income: " . $grand_total_income . ", Total Expense: " . $grand_total_expense . ', Total Balance: '  . $grand_total_balance . ', Date: ' . date('d-m-Y') . ', Mirzakhil General Hospital and Diagnostic Center.';

            $data = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];
        

            $send_sms = array(
                'mobile_number' => $number,
                'message' => $message,
                'type' => 'Business Summary by Cron',
                'date' => date('Y-m-d'), // Today date
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->insert('send_sms', $send_sms);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
        }
    }
}
