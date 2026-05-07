<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmergencyDiscountController extends CI_Controller
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
     * Daily Emergency Discount
     */
    public function getDailyEmergencyDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        $today = date('Y-m-d');

        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date', $today);
        $query = $this->db->get();
        $daily_emergency_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['daily_emergency_discount' => $daily_emergency_discount]));
    }

    /**
     * Weekly Emergency Discount
     */
    public function getWeeklyEmergencyDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));

        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $weekly_emergency_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['weekly_emergency_discount' => $weekly_emergency_discount]));
    }

    /**
     * Monthly Emergency Discount
     */
    public function getMonthlyEmergencyDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $monthly_emergency_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['monthly_emergency_discount' => $monthly_emergency_discount]));
    }

    /**
     * Six-Monthly Emergency Discount
     */
    public function getSixMonthlyEmergencyDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('-6 months'));
        $end_date = date('Y-m-d');

        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $sixmonthly_emergency_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['sixmonthly_emergency_discount' => $sixmonthly_emergency_discount]));
    }

    /**
     * Yearly Emergency Discount
     */
    public function getYearlyEmergencyDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $year = date('Y');
        $start_date = $year . '-01-01';
        $end_date = $year . '-12-31';

        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $yearly_emergency_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['yearly_emergency_discount' => $yearly_emergency_discount]));
    }

    /**
     * Total Emergency Discount (All Time)
     */
    public function getTotalEmergencyDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select_sum('total_discount');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $total_emergency_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['total_emergency_discount' => $total_emergency_discount]));
    }

    /**
     * Top Ten Emergency Discount Entries
     */
    public function getEmergencyDiscountTopTenEntries()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select('*');
        $this->db->from('emergency');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->order_by('emergency_id', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        $emergency_discount_top_ten_entries = $query->result();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['emergency_discount_top_ten_entries' => $emergency_discount_top_ten_entries]));
    }
}
