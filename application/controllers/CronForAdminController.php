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
class CronForAdminController extends CI_Controller
{

    public function sendDailySummary()
    {

        $date = date('Y-m-d');
        //$date = date('2025-05-12');
        $this->db->select_sum('payable');
        $this->db->from('opd_patient');
        $this->db->where('entry_date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_opd_payable_today = $query->row()->payable ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid_amount');
        $this->db->from('ipd_patient');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('emergency');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_emergency_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('phygiotherapy');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_phygiotherapy_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('total_amount');
        $this->db->from('debit_voucher');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_debit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

        $this->db->select_sum('total_amount');
        $this->db->from('credit_voucher');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_credit_voucher_today = $query->row()->total_amount ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('ot_services');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_ot_service_paid_today = $query->row()->paid ?? 0; // Return 0 if no income found


        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_direct_sales');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_test_entry_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('test_collection');
        $this->db->where('payment_type', 'from_due_collection');
        $this->db->where('date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_test_due_collection_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('discharge');
        $this->db->where('discharge_date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_discharge_value_today = $query->row()->paid ?? 0; // Return 0 if no income found

        $this->db->select_sum('paid');
        $this->db->from('medicine_sales');
        $this->db->where('bill_date', $date); // Filter by today's date
        $query = $this->db->get();
        $total_medicine_sells_today = $query->row()->paid ?? 0; // Return 0 if no income found


        $grand_total_income = $total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today + $total_credit_voucher_today;
        $grand_total_expense = $total_debit_voucher_today;
        $grand_total_balance = ($total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today + $total_credit_voucher_today) - ($total_debit_voucher_today);
        //  $data=array(
        //     'grand_total_income'=>$grand_total_income,
        //     'grand_total_expense'=>$grand_total_expense,
        //     'grand_total_balance'=>$grand_total_balance,
        //     'grand_total_income'=>$grand_total_income,
        //     'grand_total_expense'=>$grand_total_expense,
        //     'grand_total_balance'=>$grand_total_balance,
        // );
       
        $this->sms_send($grand_total_income, $grand_total_expense, $grand_total_balance); //To send 
    }
    function sms_send($grand_total_income, $grand_total_expense, $grand_total_balance)
    {
        // Fetch company information
        $company = $this->db->where('company_id', '1')->get('company')->row();
        if (!$company) {
            return false; // Early return if company not found
        }

        $company_name = $company->company_name;
        $sms_api = getSMSAPI();

        if ($sms_api->is_summary_send_to_admin === 'yes') {
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;

            // Compose valid phone numbers
            $numbers = [];
            if (!empty($sms_api->admin_phone1)) $numbers[] = "88" . $sms_api->admin_phone1;
            if (!empty($sms_api->admin_phone2)) $numbers[] = "88" . $sms_api->admin_phone2;
            if (!empty($sms_api->admin_phone3)) $numbers[] = "88" . $sms_api->admin_phone3;

            $number = implode(',', $numbers);

            // Create the SMS message
            $message = "Dear Admin,\n\nToday's Summary:\nTotal Income: {$grand_total_income}\nTotal Expense: {$grand_total_expense}\nTotal Balance: {$grand_total_balance}\nDate: " . date('d-m-Y') . "\n\n{$company_name}";

            // Prepare POST data for API
            $postData = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];

            // Save the SMS record
            $send_sms = [
                'mobile_number' => $number,
                'message' => $message,
                'type' => 'Regular Summary to Admin',
                'date' => date('Y-m-d'),
                'user_id' => $this->session->userdata('user_id'),
            ];
            $this->db->insert('send_sms', $send_sms);

            // Send SMS via cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        return false;
    }
}
