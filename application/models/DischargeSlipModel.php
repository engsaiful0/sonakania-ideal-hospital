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
class DischargeSlipModel extends CI_Model
{
    function count_all_discharged_slips($patient_unique_id = '', $discharge_slip_unique_id = '', $date = '')
    {
        $query = 0;
        if ($patient_unique_id != '' && $discharge_slip_unique_id == '' && $date == '') {
            $query = $this->db
                ->where('patient_unique_id', $patient_unique_id)
                ->count_all('discharge_slips');
        } else if ($patient_unique_id == '' && $discharge_slip_unique_id != '' && $date == '') {
            $query = $this->db
                ->where('discharge_slip_unique_id', $discharge_slip_unique_id)
                ->count_all('discharge_slips');
        } else if ($patient_unique_id == '' && $discharge_slip_unique_id != '' && $date != '') {
            $query = $this->db
                ->where('discharge_date', date('Y-m-d', strtotime($date)))
                ->count_all('discharge_slips');
        } else if ($patient_unique_id == '' && $discharge_slip_unique_id == '' && $date == '') {
            $query = $this->db->count_all('discharge_slips');
        }
        return $query;
    }
    function discharged_slip_details($limit, $offset, $patient_unique_id = '', $discharge_slip_unique_id = '', $date = '')
    {
        $query = '';
        if ($patient_unique_id != '' && $discharge_slip_unique_id == '' && $date == '') {
            $query = $this->db
                ->where('patient_unique_id', $patient_unique_id)
                ->order_by('discharge_slip_id', "DESC")
                ->get('discharge_slips', $limit, $offset)->result();
        } else if ($patient_unique_id == '' && $discharge_slip_unique_id != '' && $date == '') {
            $query = $this->db
                ->where('discharge_slip_unique_id', $discharge_slip_unique_id)
                ->order_by('discharge_slip_id', "DESC")
                ->get('discharge_slips', $limit, $offset)->result();
        } else if ($patient_unique_id == '' && $discharge_slip_unique_id != '' && $date != '') {
            $query = $this->db
                ->where('discharge_date', date('Y-m-d', strtotime($date)))
                ->order_by('discharge_slip_id', "DESC")
                ->get('discharge_slips', $limit, $offset)->result();
        } else if ($patient_unique_id == '' && $discharge_slip_unique_id == '' && $date == '') {
            $query = $this->db
                ->order_by('discharge_slip_id', "DESC")
                ->get('discharge_slips', $limit, $offset)->result();
        }
        return $query;
    }
}
