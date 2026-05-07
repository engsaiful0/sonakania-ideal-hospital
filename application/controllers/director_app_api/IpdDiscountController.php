<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IpdDiscountController extends CI_Controller
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
     * Daily IPD Discount
     */
    public function getDailyIpdDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        $today = date('Y-m-d');

        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date', $today);
        $query = $this->db->get();
        $daily_ipd_discount = $query->row()->special_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['daily_ipd_discount' => $daily_ipd_discount]));
    }

    /**
     * Weekly IPD Discount
     */
    public function getWeeklyIpdDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));

        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $weekly_ipd_discount = $query->row()->special_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['weekly_ipd_discount' => $weekly_ipd_discount]));
    }

    /**
     * Monthly IPD Discount
     */
    public function getMonthlyIpdDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $monthly_ipd_discount = $query->row()->special_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['monthly_ipd_discount' => $monthly_ipd_discount]));
    }

    /**
     * Six-Monthly IPD Discount
     */
    public function getSixMonthlyIpdDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $start_date = date('Y-m-d', strtotime('-6 months'));
        $end_date = date('Y-m-d');

        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $sixmonthly_ipd_discount = $query->row()->special_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['sixmonthly_ipd_discount' => $sixmonthly_ipd_discount]));
    }

    /**
     * Yearly IPD Discount
     */
    public function getYearlyIpdDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $year = date('Y');
        $start_date = $year . '-01-01';
        $end_date = $year . '-12-31';

        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $this->db->where('discharge_date >=', $start_date);
        $this->db->where('discharge_date <=', $end_date);
        $query = $this->db->get();
        $yearly_ipd_discount = $query->row()->special_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['yearly_ipd_discount' => $yearly_ipd_discount]));
    }

    /**
     * Total IPD Discount (All Time)
     */
    public function getTotalIpdDiscountSummary()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select_sum('special_discount');
        $this->db->from('discharge');
        $this->db->where('discount_reference_director_id', $director_id);
        $query = $this->db->get();
        $total_ipd_discount = $query->row()->special_discount ?? 0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['total_ipd_discount' => $total_ipd_discount]));
    }
    public function getIpdDiscountTopTenEntries()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');

        $this->db->select('discharge.*, ipd_patient.patient_name, ipd_patient.patient_unique_id');
        $this->db->from('discharge');
        $this->db->join('ipd_patient', 'ipd_patient.ipd_patient_id = discharge.ipd_patient_id', 'left');
        $this->db->where('discharge.discount_reference_director_id', $director_id);
        $this->db->order_by('discharge.discharge_id', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        $ipd_discount_top_ten_entries = $query->result();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['ipd_discount_top_ten_entries' => $ipd_discount_top_ten_entries]));
    }
}
