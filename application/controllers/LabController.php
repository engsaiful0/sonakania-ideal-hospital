<?php

class LabController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    public function lab_dashboard()
    {
        $page_data = array(
            'page_name' => 'lab/lab_dashboard',
            'page_title' => 'Lab Dashboard',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function reload_dashboard()
    {
        // Query the database for the latest data
        $this->db->select("patient_test_entry.*, GROUP_CONCAT(test.test_name SEPARATOR ', ') AS test_names");
        $this->db->from("patient_test_entry");
        $this->db->join("patient_test_entry_details", "patient_test_entry_details.patient_test_entry_id = patient_test_entry.patient_test_entry_id");
        $this->db->join("test", "test.test_id = patient_test_entry_details.test_id");
        $this->db->where("patient_test_entry.is_sample_collected", "no");
        $this->db->group_by("patient_test_entry.patient_test_entry_id");

        $patient_test_entry = $this->db->get()->result_array();

        // Prepare the data for the AJAX response
        $html = $this->load->view('lab/lab_dashboard_partial', ['patient_test_entry' => $patient_test_entry], TRUE);

        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'html' => $html,
            'test_count' => count($patient_test_entry)
        ]);
        exit();
    }
}
