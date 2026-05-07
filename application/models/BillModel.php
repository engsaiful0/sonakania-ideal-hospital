<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BillModel extends CI_Model
{

    public function count_sms_by_month($year = null, $month = null)
    {
        $this->db->select('COUNT(*) as total_sms');
        $this->db->from('send_sms');

        if ($year && $month) {
            // Use the first and last day of the month
            $start_date = date('Y-m-01', strtotime("$year-$month-01"));
            $end_date = date('Y-m-t', strtotime("$year-$month-01"));

            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
        }

        $query = $this->db->get();
        return $query->row()->total_sms;
    }
}
