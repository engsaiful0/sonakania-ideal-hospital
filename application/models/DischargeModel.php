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
class DischargeModel extends CI_Model
{
    function count_all_discharged_patients($discharge_bill_id = '', $patient_unique_id = '', $discharge_reason_id = '', $discharge_date = '', $admission_date = '')
    {
        
        if (!empty($discharge_date)) {
            $discharge_date = date('Y-m-d', strtotime($discharge_date));
        }

        if (!empty($admission_date)) {
            $admission_date = date('Y-m-d', strtotime($admission_date));
        }

        $this->db->from('discharge');

        if ($discharge_bill_id !== '') {
            $this->db->where('discharge_bill_id', $discharge_bill_id);
        }

        if ($patient_unique_id !== '') {
            $this->db->where('patient_unique_id', $patient_unique_id);
        }

        if ($discharge_reason_id !== '') {
            $this->db->where('discharge_reason_id', $discharge_reason_id);
        }

        if ($discharge_date !== '') {
            $this->db->where('discharge_date', $discharge_date);
        }

        if ($admission_date !== '') {
            $this->db->where('admission_date', $admission_date);
        }

        return $this->db->count_all_results();
    }

    function discharged_patient_details($limit, $offset, $discharge_bill_id = '', $patient_unique_id = '', $discharge_reason_id = '', $discharge_date = '', $admission_date = '')
    {
        if (!empty($discharge_date)) {
            $discharge_date = date('Y-m-d', strtotime($discharge_date));
        }

        if (!empty($admission_date)) {
            $admission_date = date('Y-m-d', strtotime($admission_date));
        }

        $this->db->from('discharge');

        if (!empty($discharge_bill_id)) {
            $this->db->where('discharge_bill_id', $discharge_bill_id);
        }

        if (!empty($patient_unique_id)) {
            $this->db->where('patient_unique_id', $patient_unique_id);
        }

        if (!empty($discharge_reason_id)) {
            $this->db->where('discharge_reason_id', $discharge_reason_id);
        }

        if (!empty($discharge_date)) {
            $this->db->where('discharge_date', $discharge_date);
        }

        if (!empty($admission_date)) {
            $this->db->where('admission_date', $admission_date);
        }

        $this->db->order_by('discharge_id', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }
}
