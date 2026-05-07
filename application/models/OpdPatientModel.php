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
class OpdPatientModel extends CI_Model
{

    function count_all_opd_patients($opd_patient_name = '', $mobile_number = '', $opd_patient_unique_id = '', $gender = '', $reference_media_id = '', $doctor_id = '', $from_date = '', $to_date = '', $status = '')
    {
        $this->db->from('opd_patient');

        // Apply filters dynamically
        if (!empty($opd_patient_name)) {
            $this->db->where('opd_patient_name', $opd_patient_name);
        }
        if (!empty($mobile_number)) {
            $this->db->where('mobile_number', $mobile_number);
        }
        if (!empty($opd_patient_unique_id)) {
            $this->db->where('opd_patient_unique_id', $opd_patient_unique_id);
        }
        if (!empty($gender)) {
            $this->db->where('gender', $gender);
        }
        if (!empty($reference_media_id)) {
            $this->db->where('reference_media_id', $reference_media_id);
        }
        if (!empty($doctor_id)) {
            $this->db->where('doctor_id', $doctor_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('entry_date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('entry_date <=', date('Y-m-d', strtotime($to_date)));
        } elseif (!empty($from_date)) {
            $this->db->where('entry_date', date('Y-m-d', strtotime($from_date)));
        } elseif (!empty($to_date)) {
            $this->db->where('entry_date', date('Y-m-d', strtotime($to_date)));
        }
        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        return $this->db->count_all_results();
    }

    function opd_patient_details($limit, $offset, $opd_patient_name = '', $mobile_number = '', $opd_patient_unique_id = '', $gender = '', $reference_media_id = '', $doctor_id = '', $from_date = '', $to_date = '', $status = '')
    {
        $this->db->from('opd_patient')->order_by('opd_patient_id', "DESC");

        // Apply filters dynamically
        if (!empty($opd_patient_name)) {
            $this->db->where('opd_patient_name', $opd_patient_name);
        }
        if (!empty($mobile_number)) {
            $this->db->where('mobile_number', $mobile_number);
        }
        if (!empty($opd_patient_unique_id)) {
            $this->db->where('opd_patient_unique_id', $opd_patient_unique_id);
        }
        if (!empty($gender)) {
            $this->db->where('gender', $gender);
        }
        if (!empty($reference_media_id)) {
            $this->db->where('reference_media_id', $reference_media_id);
        }
        if (!empty($doctor_id)) {
            $this->db->where('doctor_id', $doctor_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('entry_date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('entry_date <=', date('Y-m-d', strtotime($to_date)));
        } elseif (!empty($from_date)) {
            $this->db->where('entry_date', date('Y-m-d', strtotime($from_date)));
        } elseif (!empty($to_date)) {
            $this->db->where('entry_date', date('Y-m-d', strtotime($to_date)));
        }
        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        // Apply limit and offset for pagination
        $query = $this->db->limit($limit, $offset)->get()->result();

        return $query;
    }
}
