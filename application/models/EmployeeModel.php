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
class EmployeeModel extends CI_Model
{
    public function get_employee($limit, $offset, $employee_unique_id, $department_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($department_id != '' and $employee_unique_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->where('department_id', $department_id)
                ->get('employee', $limit, $offset);
        } else if ($employee_unique_id != '' and $department_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->where('employee_unique_id', $employee_unique_id)
                ->get('employee', $limit, $offset);
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('date_of_join>=', date('Y-m-d', strtotime($from_date)))
                ->where('date_of_join<=', date('Y-m-d', strtotime($to_date)))
                ->get('employee', $limit, $offset);
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date == '' and $to_date != '') {
            $query = $this->db
                ->where('date_of_join<=', date('Y-m-d', strtotime($to_date)))
                ->get('employee', $limit, $offset);
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date != '' and $to_date == '') {
            $query = $this->db
                ->where('date_of_join>=', date('Y-m-d', strtotime($from_date)))
                ->get('employee', $limit, $offset);
        } else if ($employee_unique_id != '' and $department_id == '' and $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('employee_unique_id', $employee_unique_id)
                ->where('date_of_join>=', date('Y-m-d', strtotime($from_date)))
                ->where('date_of_join<=', date('Y-m-d', strtotime($to_date)))
                ->get('employee', $limit, $offset);
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->get('employee', $limit, $offset);
        }
        return $query->result();
    }

    public function count_all_employees($employee_unique_id, $department_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($department_id != '' and $employee_unique_id == '' and $from_date == '' and $to_date == '') {
            return $this->db->where('department_id', $department_id)
                ->count_all('employee');
        } else if ($employee_unique_id != '' and $department_id == '' and $from_date == '' and $to_date == '') {
            return $this->db->where('employee_unique_id', $employee_unique_id)
                ->count_all('employee');
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date != '' and $to_date != '') {
            return $this->db
                ->where('date_of_join>=', date('Y-m-d', strtotime($from_date)))
                ->where('date_of_join<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('employee');
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date != '' and $to_date == '') {
            return $this->db
                ->where('date_of_join>=', date('Y-m-d', strtotime($from_date)))
                ->count_all('employee');
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date == '' and $to_date != '') {
            return $this->db
                ->where('date_of_join<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('employee');
        } else if ($employee_unique_id != '' and $department_id == '' and $from_date != '' and $to_date != '') {
            return $this->db
                ->where('employee_unique_id', $employee_unique_id)
                ->where('date_of_join>=', date('Y-m-d', strtotime($from_date)))
                ->where('date_of_join<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('employee');
        } else if ($employee_unique_id == '' and $department_id == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('employee');
        }
    }
}
