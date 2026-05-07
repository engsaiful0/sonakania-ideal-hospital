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
class DoctorSerialModel extends CI_Model
{

    function count_all_doctor_serials($patient_name = '', $mobile_number = '', $doctor_serial_unique_id = '', $gender = '', $reference_media_id = '', $doctor_id = '', $from_date = '', $to_date = '')
    {
        $this->db->from('doctor_serial');

        // Apply filters dynamically
        if (!empty($patient_name)) {
            $this->db->where('patient_name', $patient_name);
        }
        if (!empty($mobile_number)) {
            $this->db->where('mobile_number', $mobile_number);
        }
        if (!empty($doctor_serial_unique_id)) {
            $this->db->where('doctor_serial_unique_id', $doctor_serial_unique_id);
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
       

        return $this->db->count_all_results();
    }

    function doctor_serial_details($limit, $offset, $patient_name = '', $mobile_number = '', $doctor_serial_unique_id = '', $gender = '', $reference_media_id = '', $doctor_id = '', $from_date = '', $to_date = '')
    {
        $this->db->from('doctor_serial')->order_by('doctor_serial_id', "DESC");

        // Apply filters dynamically
        if (!empty($patient_name)) {
            $this->db->where('patient_name', $patient_name);
        }
        if (!empty($mobile_number)) {
            $this->db->where('mobile_number', $mobile_number);
        }
        if (!empty($doctor_serial_unique_id)) {
            $this->db->where('doctor_serial_unique_id', $doctor_serial_unique_id);
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
        

        // Apply limit and offset for pagination
        $query = $this->db->limit($limit, $offset)->get()->result();

        return $query;
    }

    function get_serials_by_ids($ids)
    {
        if (empty($ids) || !is_array($ids)) {
            return [];
        }

        $this->db->from('doctor_serial');
        $this->db->where_in('doctor_serial_id', $ids);
        $this->db->order_by('doctor_serial_id', 'ASC');
        
        return $this->db->get()->result();
    }
}
