<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Director App API - OTP & Token-Based Authentication Controller
 * 
 * This controller handles OTP-based authentication and token management for Director App API
 * 
 * Available Endpoints:
 * 
 * 1. verify_sign_in_mobile_no() - Check if mobile number exists (optional step)
 * 2. send_otp()                 - Send OTP to registered mobile number
 * 3. verify_otp()               - Verify OTP and generate authentication token
 * 4. verify_token()             - Check if token is still valid
 * 5. logout()                   - Invalidate authentication token
 * 6. validate_token($token)     - Helper method for other controllers
 * 
 * Authentication Flow:
 * 1. User enters mobile number
 * 2. System sends OTP via SMS (valid for 5 minutes)
 * 3. User verifies OTP
 * 4. System returns authentication token (valid for 30 days)
 * 5. Client uses token for all subsequent API calls
 * 
 * Security Features:
 * - Mobile number validation (11 digits)
 * - OTP expiry (5 minutes)
 * - Token expiry (30 days)
 * - SMS API validation (response code 202)
 * - Proper HTTP status codes
 * - Secure random token generation (64 characters)
 * 
 * Database Requirements:
 * - Run director_auth_token_migration.sql to add required columns
 * - auth_token, token_expiry, last_login columns in director table
 * 
 * Documentation:
 * - See DIRECTOR_APP_API_AUTH_README.md for detailed API documentation
 * - See ProtectedEndpointExample.php for usage example
 * 
 * @author BGH ERP Development Team
 * @version 1.0
 * @date 2025-10-17
 */
class OtpController extends CI_Controller
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
            http_response_code(200);
            exit(0); // Preflight request
        }

        // --- Get mobile from POST ---
        $sign_in_mobile_no = $this->input->post('sign_in_mobile_no');
        if (empty($sign_in_mobile_no)) {
            http_response_code(400); // Bad Request
            $summary = [
                'status' => 'error',
                'message' => 'Sign in mobile number is required',
                'http_code' => 400
            ];
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($summary));
        }

        // --- Validate mobile number format (11 digits for Bangladesh) ---
        if (strlen($sign_in_mobile_no) != 11) {
            http_response_code(400); // Bad Request
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid mobile number format. Mobile number must be 11 digits',
                    'http_code' => 400
                ]));
        }

        // --- Check if mobile exists in director table ---
        $director = $this->db->where('sign_in_mobile_no', $sign_in_mobile_no)->get('director')->row();

        if (!$director) {
            http_response_code(404); // Not Found
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Mobile number not found. Please contact administrator',
                    'http_code' => 404
                ]));
        }

        // --- Generate OTP ---
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes')); // valid for 5 minutes

        // --- Update director table with OTP and expiry ---
        $this->db->where('sign_in_mobile_no', $sign_in_mobile_no)
            ->update('director', [
                'otp' => $otp,
                'otp_validity' => $expiry
            ]);

        if ($this->db->affected_rows() > 0) {
            // --- Send OTP via SMS ---
            $sms_result = $this->sms_send(['mobile_number' => $sign_in_mobile_no, 'otp' => $otp]);

            // --- Check if SMS was sent successfully (response code 202) ---
            if ($sms_result['response_code'] == 202) {
                // --- Save to send_sms table only if SMS sent successfully ---
                $send_sms_data = array(
                    'mobile_number' => "88" . $sign_in_mobile_no,
                    'message' => $sms_result['message'],
                    'type' => 'Sign in OTP',
                    'date' => date('Y-m-d'),
                    'user_id' => $this->session->userdata('user_id') ?? null,
                    'response_code' => $sms_result['response_code'],
                    'status' => 'Sent'
                );
                $this->db->insert('send_sms', $send_sms_data);

                // --- Return success ---
                http_response_code(200); // Success
                $summary = [
                    'status' => 'success',
                    'message' => 'OTP sent successfully to your mobile number',
                    'mobile' => $sign_in_mobile_no,
                    'otp' => $otp,        // ⚠️ Remove in production
                    'otp_validity' => $expiry,
                    'http_code' => 200
                ];
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($summary));
            } else {
                // --- SMS sending failed ---
                http_response_code(500); // Internal Server Error
                $summary = [
                    'status' => 'error',
                    'message' => 'Failed to send OTP SMS. Please try again',
                    'http_code' => 500
                ];
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($summary));
            }
        } else {
            http_response_code(500); // Internal Server Error
            $summary = [
                'status' => 'error',
                'message' => 'Failed to generate OTP. Please try again',
                'http_code' => 500
            ];
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($summary));
        }
    }
    public function verify_sign_in_mobile_no()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0); // Preflight request
        }

        $sign_in_mobile_no = $this->input->post('sign_in_mobile_no');
        if (empty($sign_in_mobile_no)) {
            http_response_code(400); // Bad Request
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Sign in mobile number is required',
                    'http_code' => 400
                ]));
        }

        // --- Validate mobile number format (11 digits) ---
        if (strlen($sign_in_mobile_no) != 11) {
            http_response_code(400); // Bad Request
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid mobile number format. Mobile number must be 11 digits',
                    'http_code' => 400
                ]));
        }

        $director = $this->db->where('sign_in_mobile_no', $sign_in_mobile_no)->get('director')->row();
        if (!$director) {
            http_response_code(404); // Not Found
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Mobile number not found',
                    'http_code' => 404
                ]));
        }

        http_response_code(200); // Success
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Mobile number found',
                'http_code' => 200
            ]));
    }

    public function verify_otp()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0); // Preflight request
        }

        // --- Get data from POST ---
        $sign_in_mobile_no = $this->input->post('sign_in_mobile_no');
        $otp = $this->input->post('otp');
        if (empty($sign_in_mobile_no) || empty($otp)) {
            http_response_code(400); // Bad Request
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Sign in mobile number and OTP are required',
                    'http_code' => 400
                ]));
        }
        // --- Basic validation ---
        // --- Find director record ---
        $director = $this->db->where('sign_in_mobile_no', $sign_in_mobile_no)->get('director')->row();

        if (!$director) {
            http_response_code(404); // Not Found
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Mobile number not found',
                    'http_code' => 404
                ]));
        }

        // --- Validate OTP ---
        if ($director->otp != $otp) {
            http_response_code(401); // Unauthorized
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
                    'http_code' => 401
                ]));
        }

        // --- Check OTP validity ---
        $current_time = date('Y-m-d H:i:s');
        if ($current_time > $director->otp_validity) {
            http_response_code(401); // Unauthorized
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'OTP has expired. Please request a new one',
                    'http_code' => 401
                ]));
        }

        // --- Generate authentication token ---
        $token = $this->generate_secure_token();
        $token_expiry = date('Y-m-d H:i:s', strtotime('+30 days')); // Token valid for 30 days

        // --- Update director with token and clear OTP ---
        $this->db->where('sign_in_mobile_no', $sign_in_mobile_no)
            ->update('director', [
                'otp' => null,
                'otp_validity' => null,
                'auth_token' => $token,
                'token_expiry' => $token_expiry,
                'last_login' => date('Y-m-d H:i:s')
            ]);

        // --- Prepare director info (exclude sensitive fields) ---
        $director_info = [
            'director_id' => $director->director_id,
            'name' => $director->name ?? '',
            'category' => $director->category ?? '',
            'unique_id' => $director->unique_id ?? '',
            'gender' => $director->gender,
            'nid_number' => $director->nid_number,
            'present_address' => $director->present_address,
            'permanent_address' => $director->permanent_address,
            'father_name' => $director->father_name,
            'mother_name' => $director->mother_name,
            'mobile' => $director->mobile,
            'sign_in_mobile_no' => $director->sign_in_mobile_no,
            'email' => $director->email,
            'amount_per_share' => $director->amount_per_share ?? '',
            'current_share_value' => $director->current_share_value ?? '',
            'date_of_join' => $director->date_of_join ?? '',
            'picture' => $director->picture ?? '',
        ];

        // --- Return success response with token ---
        http_response_code(200); // Success
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'OTP verified successfully',
                'token' => $token,
                'token_expiry' => $token_expiry,
                'director' => $director_info,
                'http_code' => 200
            ]));
    }

    /**
     * Generate a secure random token for authentication
     */
    private function generate_secure_token()
    {
        // Generate a secure random token (64 characters)
        return bin2hex(random_bytes(32));
    }

    /**
     * Verify authentication token for protected API endpoints
     */
    public function verify_token()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }

        // Get token from Authorization header or POST data
        $token = $this->input->get_request_header('Authorization', TRUE);
        if (empty($token)) {
            $token = $this->input->post('token');
        } else {
            // Remove "Bearer " prefix if present
            $token = str_replace('Bearer ', '', $token);
        }

        if (empty($token)) {
            http_response_code(401); // Unauthorized
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Authentication token is required',
                    'http_code' => 401
                ]));
        }

        // Find director with this token
        $director = $this->db->where('auth_token', $token)->get('director')->row();

        if (!$director) {
            http_response_code(401); // Unauthorized
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid authentication token',
                    'http_code' => 401
                ]));
        }

        // Check if token has expired
        $current_time = date('Y-m-d H:i:s');
        if ($current_time > $director->token_expiry) {
            http_response_code(401); // Unauthorized
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Authentication token has expired. Please login again',
                    'http_code' => 401
                ]));
        }

        // Token is valid
        http_response_code(200);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Token is valid',
                'director_id' => $director->id,
                'director_name' => $director->name ?? '',
                'http_code' => 200
            ]));
    }

    /**
     * Helper method to validate token and return director (for use in other controllers)
     * 
     * Usage example in other controllers:
     * 
     * $this->load->model('director_app_api/OtpController', 'otp_controller');
     * $director = $this->otp_controller->validate_token($token);
     * if (!$director) {
     *     // Return unauthorized error
     * }
     */
    public function validate_token($token)
    {
        if (empty($token)) {
            return false;
        }

        // Find director with this token
        $director = $this->db
            ->where('auth_token', $token)
            ->where('token_expiry >', date('Y-m-d H:i:s'))
            ->get('director')
            ->row();

        return $director ? $director : false;
    }

    /**
     * Logout - Invalidate the authentication token
     */
    public function logout()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }

        // Get token from Authorization header or POST data
        $token = $this->input->get_request_header('Authorization', TRUE);
        if (empty($token)) {
            $token = $this->input->post('token');
        } else {
            // Remove "Bearer " prefix if present
            $token = str_replace('Bearer ', '', $token);
        }

        if (empty($token)) {
            http_response_code(400); // Bad Request
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Authentication token is required',
                    'http_code' => 400
                ]));
        }

        // Invalidate the token
        $updated = $this->db
            ->where('auth_token', $token)
            ->update('director', [
                'auth_token' => null,
                'token_expiry' => null
            ]);

        if ($this->db->affected_rows() > 0) {
            http_response_code(200);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Logged out successfully',
                    'http_code' => 200
                ]));
        } else {
            http_response_code(404); // Not Found
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid token or already logged out',
                    'http_code' => 404
                ]));
        }
    }


    function sms_send($data)
    {
        $sms_api = getSMSAPI();
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;

        // Check if SMS sending is enabled
        if ($sms_api->is_test_entry_sms_send != 'yes') {
            return [
                'success' => false,
                'message' => 'SMS sending is disabled',
                'response' => null,
                'response_code' => null
            ];
        }

        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = $sms_api->api_key;
        $senderid = $sms_api->senderid;
        $number = "88" . $data['mobile_number'];
        // Follow SMS API recommendation: Your {Brand/Company Name} OTP is XXXX
        $message = "Your " . $company_name . " OTP is " . $data['otp'];

        $post_data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message
        ];

        // Send SMS via cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Check if cURL request was successful
        if ($curl_error) {
            return [
                'success' => false,
                'message' => $message,
                'response' => $curl_error,
                'response_code' => null
            ];
        }

        // Parse the response to check if SMS was sent successfully
        $response_code = null;
        if (!empty($response)) {
            // Decode JSON response if it's JSON format
            $response_data = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($response_data['response_code'])) {
                $response_code = $response_data['response_code'];
            } elseif (is_numeric($response)) {
                // If response is just a number (response code)
                $response_code = (int)$response;
            }
        }

        // Only consider successful if response code is 202
        if ($response_code == 202) {
            return [
                'success' => true,
                'message' => $message,
                'response' => $response,
                'response_code' => $response_code
            ];
        } else {
            return [
                'success' => false,
                'message' => $message,
                'response' => $response,
                'response_code' => $response_code
            ];
        }
    }
}
