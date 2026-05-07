<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TestDiscountController extends CI_Controller
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
     * Daily Discount
     */
    public function getDailyTestDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        $today = date('Y-m-d');

        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date', $today);
        $query = $this->db->get();
        $daily_test_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['daily_test_discount' => $daily_test_discount]));
    }

    /**
     * Weekly Discount
     */
    public function getWeeklyTestDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));

        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $weekly_test_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['weekly_test_discount' => $weekly_test_discount]));
    }

    /**
     * Monthly Discount
     */
    public function getMonthlyTestDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $monthly_test_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['monthly_test_discount' => $monthly_test_discount]));
    }

    /**
     * Six-Monthly Discount
     */
    public function getSixMonthlyTestDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('-6 months'));
        $end_date = date('Y-m-d');

        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $sixmonthly_test_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['sixmonthly_test_discount' => $sixmonthly_test_discount]));
    }

    /**
     * Yearly Discount
     */
    public function getYearlyTestDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $year = date('Y');
        $start_date = $year . '-01-01';
        $end_date = $year . '-12-31';

        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $yearly_test_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['yearly_test_discount' => $yearly_test_discount]));
    }

    /**
     * Total Discount (All Time)
     */
    public function getTotalTestDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select_sum('total_discount');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $total_test_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['total_test_discount' => $total_test_discount]));
    }
    public function getTestDiscountTopTenEntries()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        $this->db->select('*');
        $this->db->from('patient_test_entry');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->order_by('patient_test_entry_id', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        $test_discount_top_ten_entries = $query->result();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['test_discount_top_ten_entries' => $test_discount_top_ten_entries]));
    }
}
