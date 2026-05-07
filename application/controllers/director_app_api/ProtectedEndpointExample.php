<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Example Controller: Protected API Endpoint
 * 
 * This is an example showing how to protect API endpoints with token authentication
 * Copy this pattern to your other controllers that need authentication
 */
class ProtectedEndpointExample extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    /**
     * Example: Get director profile (protected endpoint)
     * 
     * Usage:
     * POST /director_app_api/ProtectedEndpointExample/get_profile
     * Header: Authorization: Bearer YOUR_TOKEN_HERE
     */
    public function get_profile()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }

        // --- Authenticate user ---
        $director = $this->authenticate();
        if (!$director) {
            return; // Error response already sent by authenticate()
        }

        // --- Director is authenticated, proceed with your logic ---
        // You can access: $director->id, $director->name, etc.

        $profile_data = [
            'id' => $director->id,
            'name' => $director->name ?? '',
            'category' => $director->category ?? '',
            'unique_id' => $director->unique_id ?? '',
            'mobile' => $director->sign_in_mobile_no,
            'amount_per_share' => $director->amount_per_share ?? '',
            'current_share_value' => $director->current_share_value ?? '',
            'date_of_join' => $director->date_of_join ?? '',
            'last_login' => $director->last_login ?? ''
        ];

        // --- Return success response ---
        http_response_code(200);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'message' => 'Profile retrieved successfully',
                'profile' => $profile_data,
                'http_code' => 200
            ]));
    }

    /**
     * Example: Update profile (protected endpoint)
     * 
     * Usage:
     * POST /director_app_api/ProtectedEndpointExample/update_profile
     * Header: Authorization: Bearer YOUR_TOKEN_HERE
     * Body: {"present_address": "New Address"}
     */
    public function update_profile()
    {
        // --- CORS headers ---
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit(0);
        }

        // --- Authenticate user ---
        $director = $this->authenticate();
        if (!$director) {
            return; // Error response already sent by authenticate()
        }

        // --- Get update data ---
        $present_address = $this->input->post('present_address');
        
        if (empty($present_address)) {
            http_response_code(400);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Present address is required',
                    'http_code' => 400
                ]));
        }

        // --- Update director profile ---
        $this->db->where('id', $director->id)
            ->update('director', [
                'present_address' => $present_address
            ]);

        if ($this->db->affected_rows() > 0) {
            http_response_code(200);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Profile updated successfully',
                    'http_code' => 200
                ]));
        } else {
            http_response_code(500);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update profile',
                    'http_code' => 500
                ]));
        }
    }

    /**
     * Authentication helper method
     * 
     * This method validates the authentication token and returns the director object
     * If authentication fails, it sends an error response and returns false
     * 
     * @return object|false Returns director object if authenticated, false otherwise
     */
    private function authenticate()
    {
        // Get token from Authorization header or POST data
        $token = $this->input->get_request_header('Authorization', TRUE);
        if (empty($token)) {
            $token = $this->input->post('token');
        } else {
            // Remove "Bearer " prefix if present
            $token = str_replace('Bearer ', '', $token);
        }

        // Check if token is provided
        if (empty($token)) {
            http_response_code(401);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Authentication token is required',
                    'http_code' => 401
                ]));
            return false;
        }

        // Validate token
        $director = $this->db
            ->where('auth_token', $token)
            ->get('director')
            ->row();

        // Check if token exists
        if (!$director) {
            http_response_code(401);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid authentication token',
                    'http_code' => 401
                ]));
            return false;
        }

        // Check if token has expired
        $current_time = date('Y-m-d H:i:s');
        if ($current_time > $director->token_expiry) {
            http_response_code(401);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Authentication token has expired. Please login again',
                    'http_code' => 401
                ]));
            return false;
        }

        // Token is valid, return director object
        return $director;
    }
}

