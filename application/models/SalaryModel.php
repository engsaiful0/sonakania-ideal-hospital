<?php
class SalaryModel extends CI_Model
{
    public function get_salary_details($limit, $offset, $month_id = '', $year_id = '')
    {
        if (!empty($month_id)) {
            $this->db->where('month_id', $month_id);
        }

        if (!empty($year_id)) {
            $this->db->where('year_id', $year_id);
        }

        $this->db->order_by('salary_id', 'DESC');

        // Defensive coding: ensure $limit and $offset are valid integers
        $limit = (int) $limit;
        $offset = (int) $offset;

        $query = $this->db->get('salaries', $limit, $offset);
        return $query->result();
    }


    public function count_all_salary($month_id = '', $year_id = '')
    {
        if (!empty($month_id)) {
            $this->db->where('month_id', $month_id);
        }

        if (!empty($year_id)) {
            $this->db->where('year_id', $year_id);
        }

        return $this->db->count_all_results('salaries');
    }
}
