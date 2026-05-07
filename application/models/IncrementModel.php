<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaleModel
 *
 * @author sohag
 */
class IncrementModel extends CI_Model
{
    public function get_increments($limit, $offset, $employee_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($employee_id != '' and $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('employee_id', $employee_id)
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->get('increment', $limit, $offset);
        } else if ($employee_id != '' and $from_date == '' and $to_date == '') {
            $query = $this->db
                ->where('employee_id', $employee_id)
                ->get('increment', $limit, $offset);
        } else if ($employee_id != '' and $from_date != '' and $to_date == '') {
            $query = $this->db->where('employee_id', $employee_id)
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->get('increment', $limit, $offset);
        } else if ($employee_id != '' and $from_date == '' and $to_date != '') {
            $query = $this->db->where('employee_id', $employee_id)
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->get('increment', $limit, $offset);
        } else if ($employee_id == '' and $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->get('increment', $limit, $offset);
        } else if ($employee_id == '' and $from_date == '' and $to_date != '') {
            $query = $this->db
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->get('increment', $limit, $offset);
        } else if ($employee_id == '' and $from_date != '' and $to_date == '') {
            $query = $this->db
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->get('increment', $limit, $offset);
        } else if ($employee_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->get('increment', $limit, $offset);
        }
        return $query->result();
    }

    public function count_all_increments($employee_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($employee_id != '' and $from_date != '' and $to_date != '') {
            $query = $this->db->where('employee_id', $employee_id)
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('increment');
        } else if ($employee_id != '' and $from_date == '' and $to_date == '') {
            $query = $this->db->where('employee_id', $employee_id)
                ->count_all('increment');
        } else if ($employee_id != '' and $from_date != '' and $to_date == '') {
            $query = $this->db->where('employee_id', $employee_id)
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->count_all('increment');
        } else if ($employee_id != '' and $from_date == '' and $to_date != '') {
            $query = $this->db->where('employee_id', $employee_id)
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->count_all('increment');
        } else if ($employee_id == '' and $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('increment');
        } else if ($employee_id == '' and $from_date == '' and $to_date != '') {
            $query = $this->db
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->count_all('increment');
        } else if ($employee_id == '' and $from_date != '' and $to_date == '') {
            $query = $this->db
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->count_all('increment');
        } else if ($employee_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->count_all('increment');
        }
        return $query;
    }
}
