<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DorectorLoginController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
    }

    public function send_otp()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0); // Preflight request
        }

        // --- Get mobile from POST ---
        $mobile = $this->input->post('mobile');
        if (empty($mobile)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Mobile number is required']));
        }

        // --- Check if mobile exists in director table ---
        $director = $this->db->where('sign_in_mobile_no', $mobile)->get('director')->row();

        if (!$director) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Mobile number not found']));
        }

        // --- Generate OTP ---
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes')); // valid for 5 minutes

        // --- Update director table with OTP and expiry ---
        $this->db->where('sign_in_mobile_no', $mobile)
            ->update('director', [
                'otp' => $otp,
                'otp_validity' => $expiry
            ]);

        if ($this->db->affected_rows() > 0) {
            // --- Send OTP via SMS (implement your own function) ---
            $this->sms_send(['mobile_number' => $mobile, 'otp' => $otp]);

            // --- Return success ---
            $summary = [
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'mobile' => $mobile,
                'otp' => $otp,        // ⚠️ Remove in production
                'otp_validity' => $expiry
            ];
        } else {
            $summary = [
                'status' => 'error',
                'message' => 'Failed to update OTP. Please try again.'
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($summary));
    }
    public function verify_otp()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0); // Preflight request
        }

        // --- Get data from POST ---
        $mobile = $this->input->post('mobile');
        $otp    = $this->input->post('otp');

        // --- Basic validation ---
        if (empty($mobile) || empty($otp)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Mobile number and OTP are required.'
                ]));
        }

        // --- Find director record ---
        $director = $this->db->where('sign_in_mobile_no', $mobile)->get('director')->row();

        if (!$director) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Mobile number not found.'
                ]));
        }

        // --- Validate OTP ---
        if ($director->otp != $otp) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid OTP.'
                ]));
        }

        // --- Check OTP validity ---
        $current_time = date('Y-m-d H:i:s');
        if ($current_time > $director->otp_validity) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'OTP has expired. Please request a new one.'
                ]));
        }

        // --- OTP is valid, clear it (optional but recommended) ---
        $this->db->where('sign_in_mobile_no', $mobile)
            ->update('director', ['otp' => null, 'otp_validity' => null]);

        // --- Prepare director info (exclude sensitive fields) ---
        $director_info = [
            'id' => $director->id,
            'name' => $director->name ?? '',
            'category' => $director->category ?? '',
            'unique_id' => $director->unique_id ?? '',
            'gender' => $director->gender,
            'nid_number' => $director->nid_number,
            'present_address' => $director->present_address,
            'permanent_address' => $director->permanent_address,
            'father_name' => $director->father_name,
            'mother_name' => $director->mother_name,
            'mobile' => $director->sign_in_mobile_no,
            'amount_per_share' => $director->amount_per_share ?? '',
            'current_share_value' => $director->current_share_value ?? '',
            'date_of_join' => $director->date_of_join ?? ''
        ];

        // --- Return success response ---
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'OTP verified successfully.',
                'director' => $director_info
            ]));
    }


    function sms_send($data)
    {

        $sms_api = getSMSAPI();
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;

        if ($sms_api->is_test_entry_sms_send == 'yes') { // if is_test_entry_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['mobile_number'];
            $message = "Dear Director, your sign in OTP is: " . $data['otp'] . ". Please use this OTP to complete your sign in." . $company_name;

            $data = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];

            $send_sms = array(
                'mobile_number' => $number,
                'message' => $message,
                'type' => 'Sign in OTP',
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
