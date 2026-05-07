<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TotalDiscountController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
    }

    private function setCors()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }

    /**
     * Get Total Discount for Today (All Sources)
     */
    public function getTotalDiscountToday()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        $today = date('Y-m-d');

        // Test Discount
        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date', $today);
        $query = $this->db->get();
        $test_discount = $query->row()->total_discount ?? 0;

        // Emergency Discount
        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date', $today);
        $query = $this->db->get();
        $emergency_discount = $query->row()->total_discount ?? 0;

        // Physio Discount
        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date', $today);
        $query = $this->db->get();
        $physio_discount = $query->row()->total_discount ?? 0;

        // IPD Discount
        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date', $today);
        $query = $this->db->get();
        $ipd_discount = $query->row()->special_discount ?? 0;

        $total_discount = $test_discount + $emergency_discount + $physio_discount + $ipd_discount;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'total_discount_today' => $total_discount,
                'breakdown' => [
                    'test_discount' => $test_discount,
                    'emergency_discount' => $emergency_discount,
                    'physio_discount' => $physio_discount,
                    'ipd_discount' => $ipd_discount
                ]
            ]));
    }

    /**
     * Get Total Discount for This Week (All Sources)
     */
    public function getTotalDiscountThisWeek()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));

        // Test Discount
        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $test_discount = $query->row()->total_discount ?? 0;

        // Emergency Discount
        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $emergency_discount = $query->row()->total_discount ?? 0;

        // Physio Discount
        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $physio_discount = $query->row()->total_discount ?? 0;

        // IPD Discount
        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $ipd_discount = $query->row()->special_discount ?? 0;

        $total_discount = $test_discount + $emergency_discount + $physio_discount + $ipd_discount;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'total_discount_this_week' => $total_discount,
                'breakdown' => [
                    'test_discount' => $test_discount,
                    'emergency_discount' => $emergency_discount,
                    'physio_discount' => $physio_discount,
                    'ipd_discount' => $ipd_discount
                ]
            ]));
    }

    /**
     * Get Total Discount for This Month (All Sources)
     */
    public function getTotalDiscountThisMonth()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        // Test Discount
        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $test_discount = $query->row()->total_discount ?? 0;

        // Emergency Discount
        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $emergency_discount = $query->row()->total_discount ?? 0;

        // Physio Discount
        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $physio_discount = $query->row()->total_discount ?? 0;

        // IPD Discount
        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $ipd_discount = $query->row()->special_discount ?? 0;

        $total_discount = $test_discount + $emergency_discount + $physio_discount + $ipd_discount;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'total_discount_this_month' => $total_discount,
                'breakdown' => [
                    'test_discount' => $test_discount,
                    'emergency_discount' => $emergency_discount,
                    'physio_discount' => $physio_discount,
                    'ipd_discount' => $ipd_discount
                ]
            ]));
    }

    /**
     * Get Total Discount for This Year (All Sources)
     */
    public function getTotalDiscountThisYear()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $year = date('Y');
        $start_date = $year . '-01-01';
        $end_date = $year . '-12-31';

        // Test Discount
        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $test_discount = $query->row()->total_discount ?? 0;

        // Emergency Discount
        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $emergency_discount = $query->row()->total_discount ?? 0;

        // Physio Discount
        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $physio_discount = $query->row()->total_discount ?? 0;

        // IPD Discount
        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $ipd_discount = $query->row()->special_discount ?? 0;

        $total_discount = $test_discount + $emergency_discount + $physio_discount + $ipd_discount;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'total_discount_this_year' => $total_discount,
                'breakdown' => [
                    'test_discount' => $test_discount,
                    'emergency_discount' => $emergency_discount,
                    'physio_discount' => $physio_discount,
                    'ipd_discount' => $ipd_discount
                ]
            ]));
    }

    /**
     * Get All Time Total Discount (All Sources)
     */
    public function getTotalDiscountAllTime()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        // Test Discount
        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $test_discount = $query->row()->total_discount ?? 0;

        // Emergency Discount
        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $emergency_discount = $query->row()->total_discount ?? 0;

        // Physio Discount
        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $physio_discount = $query->row()->total_discount ?? 0;

        // IPD Discount
        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $ipd_discount = $query->row()->special_discount ?? 0;

        $total_discount = $test_discount + $emergency_discount + $physio_discount + $ipd_discount;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'total_discount_all_time' => $total_discount,
                'breakdown' => [
                    'test_discount' => $test_discount,
                    'emergency_discount' => $emergency_discount,
                    'physio_discount' => $physio_discount,
                    'ipd_discount' => $ipd_discount
                ]
            ]));
    }
}
