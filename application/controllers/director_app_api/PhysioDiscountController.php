<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PhysioDiscountController extends CI_Controller
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
     * Daily Physio Discount
     */
    public function getDailyPhysioDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        $today = date('Y-m-d');

        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date', $today);
        $query = $this->db->get();
        $daily_physio_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['daily_physio_discount' => $daily_physio_discount]));
    }

    /**
     * Weekly Physio Discount
     */
    public function getWeeklyPhysioDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));

        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $weekly_physio_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['weekly_physio_discount' => $weekly_physio_discount]));
    }

    /**
     * Monthly Physio Discount
     */
    public function getMonthlyPhysioDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $monthly_physio_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['monthly_physio_discount' => $monthly_physio_discount]));
    }

    /**
     * Six-Monthly Physio Discount
     */
    public function getSixMonthlyPhysioDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('-6 months'));
        $end_date = date('Y-m-d');

        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $sixmonthly_physio_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['sixmonthly_physio_discount' => $sixmonthly_physio_discount]));
    }

    /**
     * Yearly Physio Discount
     */
    public function getYearlyPhysioDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $year = date('Y');
        $start_date = $year . '-01-01';
        $end_date = $year . '-12-31';

        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get();
        $yearly_physio_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['yearly_physio_discount' => $yearly_physio_discount]));
    }

    /**
     * Total Physio Discount (All Time)
     */
    public function getTotalPhysioDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select_sum('total_discount');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $total_physio_discount = $query->row()->total_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['total_physio_discount' => $total_physio_discount]));
    }

    /**
     * Top Ten Physio Discount Entries
     */
    public function getPhysioDiscountTopTenEntries()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select('*');
        $this->db->from('phygiotherapy');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->order_by('phygiotherapy_id', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        $physio_discount_top_ten_entries = $query->result();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['physio_discount_top_ten_entries' => $physio_discount_top_ten_entries]));
    }
}
