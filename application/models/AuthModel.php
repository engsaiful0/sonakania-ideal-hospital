<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model {

    public function validate_test_report_user($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', $password); // Assuming password is stored as an MD5 hash
        $query = $this->db->get('patient_test_entry');

        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_test_report_user_data($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('patient_test_entry');

        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
}
