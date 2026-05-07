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
class AttendanceModel extends CI_Model
{
    public function get_attendane($limit, $offset, $employee_id, $from_date = '', $to_date = '')
    {

        $query = '';
        if ($employee_id != '' && $from_date == '' && $to_date == '') {
            $query = $this->db->where('employee_id', $employee_id)->order_by('attendance_id', 'DESC')->get('attendance', $limit, $offset);
        } else if ($employee_id != '' && $from_date != '' && $to_date == '') {
            $query = $this->db
                ->where('employee_id', $employee_id)
                ->where('date', $from_date)->order_by('attendance_id', 'DESC')
                ->get('attendance', $limit, $offset);
        } else if ($employee_id == '' && $from_date != '' && $to_date == '') {
          
            $query = $this->db
                ->where('date', $from_date)->order_by('attendance_id', 'DESC')
                ->get('attendance', $limit, $offset);
        } else if ($employee_id == '' && $from_date == '' && $to_date != '') {
            $query = $this->db
                ->where('date', $to_date)->order_by('attendance_id', 'DESC')
                ->get('attendance', $limit, $offset);
        } else if ($employee_id == '' && $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))->order_by('attendance_id', 'DESC')
                ->get('attendance', $limit, $offset);
        } else if ($employee_id != '' && $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('employee_id', $employee_id)
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))->order_by('attendance_id', 'DESC')
                ->get('attendance', $limit, $offset);
        } else if ($employee_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->order_by('attendance_id', 'DESC')->get('attendance', $limit, $offset);
        }
        return $query->result();
    }

    public function count_all_attendance($employee_id, $from_date = '', $to_date = '')
    {
        $total_rows = 0;

        if ($employee_id != ''  && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('employee_id', $employee_id)
                ->count_all('attendance');
        } else if ($employee_id == '' && $from_date != '' && $to_date == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $total_rows = $this->db
                ->where('date', $from_date)
                ->count_all('attendance');
        } else if ($employee_id == '' && $from_date == '' && $to_date != '') {
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date', $to_date)
                ->count_all('attendance');
        } else if ($employee_id != '' && $from_date != '' && $to_date == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $total_rows = $this->db
                ->where('employee_id', $employee_id)
                ->where('date', $from_date)
                ->count_all('attendance');
        } else if ($from_date != '' && $to_date != '' && $employee_id == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->count_all('attendance');
        } else if ($from_date != '' && $to_date != '' && $employee_id != '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('employee_id', $employee_id)
                ->count_all('attendance');
        } else if ($from_date == '' && $to_date == '' && $employee_id == '') {
            $total_rows = $this->db
                ->count_all('attendance');
        }
        return $total_rows;
    }
}
