<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{

    public function login_with_qr_code()
    {
        $username = $this->input->get('username');
        $password = $this->input->get('password');

        // Validate the username and password
        if ($this->AuthModel->validate_test_report_user($username, $password)) {
            // Set session data or other login processes
            $user_data = $this->AuthModel->get_test_report_user_data($username);
            $this->session->set_userdata($user_data);

            // Redirect to user's panel
            redirect(base_url('customer-panel'));
        } else {
            // Invalid login
            echo "Invalid login details.";
        }
    }

    function FunctionLogout()
    {
        $this->session->sess_destroy();
        // Redirect to the external site after logout
        header("Location: https://mghdc.com");
        exit();
    }
}
