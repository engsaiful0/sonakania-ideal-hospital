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
class IssueModel extends CI_Model
{
    public function get_issue($limit, $offset, $issue_no, $employee_id, $from_date = '', $to_date = '')
    {

        $query = '';
        if ($employee_id != '') {
            $query = $this->db->where('employee_id', $employee_id)->get('issue', $limit, $offset);
        } else if ($issue_no != '') {
            $query = $this->db->where('issue_no', $issue_no)->get('issue', $limit, $offset);
        } else if ($from_date != '' and $to_date != '') {
            $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->get('issue', $limit, $offset);
        } else if ($employee_id == '' and $issue_no == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->get('issue', $limit, $offset);
        }
        return $query->result();
    }

    public function count_all_issue($issue_no, $employee_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($issue_no != '') {
            $query = $this->db->where('issue_no', $issue_no)->count_all('issue');
        } else if ($employee_id != '') {
            $query = $this->db->where('employee_id', $employee_id)->count_all('issue');
        } else if ($from_date != '' and $to_date != '') {
            $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->count_all('issue');
        } else if ($employee_id == '' and $issue_no == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->count_all('issue');
        }
        return $query;
    }
}
