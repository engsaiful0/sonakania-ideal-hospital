<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CronForBillController
 *
 * @author Lenovo
 */
class CronForBillController extends CI_Controller
{
  public function update_status()
    {
        if ($this->input->is_ajax_request()) {
            $software_bill_id = $this->input->post('software_bill_id');
            $new_status = $this->input->post('new_status');
            $paid_amount = $this->input->post('paid_amount');
            $data_paid = array(
                'status' => $new_status,
                'paid_amount' => $paid_amount,
                'paid_date' => date('Y-m-d'),
            );
            $this->db->where('software_bill_id',$software_bill_id)->update('software_bills', $data_paid);

            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            
            echo json_encode($response);
        }
    }
    public function generate_monthly_bill()
    {

        $year = date('Y');
        $month = date('m');
        $month_name = '';
        if ($month == 1) {
            $month_name = 'January';
        } else if ($month == 2) {
            $month_name = 'February';
        } else if ($month == 3) {
            $month_name = 'March';
        } else if ($month == 4) {
            $month_name = 'April';
        } else if ($month == 5) {
            $month_name = 'May';
        } else if ($month == 6) {
            $month_name = 'Jun';
        } else if ($month == 7) {
            $month_name = 'July';
        } else if ($month == 8) {
            $month_name = 'August';
        } else if ($month == 9) {
            $month_name = 'September';
        } else if ($month == 10) {
            $month_name = 'October';
        } else if ($month == 11) {
            $month_name = 'November';
        } else if ($month == 12) {
            $month_name = 'December';
        }

        $total_sms = $this->BillModel->count_sms_by_month($year, $month);

        // Now store this in a billing table, log it, or use it as needed
        //echo "Total SMS for $year-$month: $total_sms";
        $company = $this->db->where('company_id', '1')->get('company')->row();
        $total_sms_bill = $total_sms * $company->sms_price;
        $monthly_software_charge = $company->monthly_software_charge;
        $total_monthly_bill_for_current_month = $total_sms_bill + $monthly_software_charge;
        $data = array(
            'month' => $month_name,
            'year' => $year,
            'regular_charge' => $company->monthly_software_charge,
            'total_sms' => $total_sms,
            'per_sms_price' => $company->sms_price,
            'total_sms_price' => $total_sms_bill,
            'total_bill' => $total_monthly_bill_for_current_month,
            'status' => 'Due',
        );
        $this->db->insert("software_bills", $data);
        $this->sms_send($data);
    }
    function sms_send($data)
    {
        $total_bill = $data['total_bill'];
        $month = $data['month'];
        $year = $data['year'];
        $total_sms = $data['total_sms'];
        $total_sms_price = $data['total_sms_price'];
        $regular_charge = $data['regular_charge'];

        $company = $this->db->where('company_id', '1')->get('company')->row();
        if (!$company) {
            return false; // Fail early if company not found
        }

        $company_name = $company->company_name;
        $sms_api = getSMSAPI();

        if ($sms_api->is_summary_send_to_admin === 'yes') {
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;

            // Compose admin phone numbers (skip if any are empty)
            $numbers = [];
            if (!empty($sms_api->admin_phone1)) $numbers[] = "88" . $sms_api->admin_phone1;
            if (!empty($sms_api->admin_phone2)) $numbers[] = "88" . $sms_api->admin_phone2;
            if (!empty($sms_api->admin_phone3)) $numbers[] = "88" . $sms_api->admin_phone3;

            $number = implode(',', $numbers);

            $message = "Dear Admin,\n";
            $message .= "The monthly software bill for {$month}-{$year} is: {$total_bill}.\n";
            $message .= "Bill Generated Date: " . date('d-m-Y') . "\n";
            $message .= "{$company_name}";


            $postData = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];

            // Save SMS to DB
            $send_sms = [
                'mobile_number' => $number,
                'message' => $message,
                'type' => 'Monthly Bill',
                'date' => date('Y-m-d'),
                'user_id' => $this->session->userdata('user_id'),
            ];
            $this->db->insert('send_sms', $send_sms);

            // Send via cURL
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
