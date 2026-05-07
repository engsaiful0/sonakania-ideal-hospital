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
class EmergencyModel extends CI_Model
{
    public function get_emergency($limit, $offset, $phone = '', $name = '', $emergency_invoice_no = '', $gender = '', $reference_doctor_id = '', $reference_media_id = '', $from_date = '', $to_date = '', $status = '')
    {
        $this->db->from('emergency');

        // Apply filters only if they are provided
        if (!empty($name)) {
            $this->db->where('name', $name);
        }

        if (!empty($phone)) {
            $this->db->where('phone', $phone);
        }

        if (!empty($emergency_invoice_no)) {
            $this->db->where('emergency_invoice_no', $emergency_invoice_no);
        }

        if (!empty($gender)) {
            $this->db->where('gender', $gender);
        }

        if (!empty($reference_doctor_id)) {
            $this->db->where('reference_doctor_id', $reference_doctor_id);
        }

        if (!empty($reference_media_id)) {
            $this->db->where('reference_media_id', $reference_media_id);
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } else if (!empty($from_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } else if (!empty($to_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('emergency_id', 'DESC');
        $query = $this->db->get('', $limit, $offset);

        return $query->result();
    }


    public function count_all_emergency($phone = '', $name = '', $emergency_invoice_no = '', $gender = '', $reference_doctor_id = '', $reference_media_id = '', $from_date = '', $to_date = '', $status = '')
    {
        // Start the query
        $this->db->from('emergency');

        // Apply filters if provided
        if (!empty($name)) {
            $this->db->where('name', $name);
        }

        if (!empty($phone)) {
            $this->db->where('phone', $phone);
        }

        if (!empty($emergency_invoice_no)) {
            $this->db->where('emergency_invoice_no', $emergency_invoice_no);
        }

        if (!empty($gender)) {
            $this->db->where('gender', $gender);
        }

        if (!empty($reference_doctor_id)) {
            $this->db->where('reference_doctor_id', $reference_doctor_id);
        }

        if (!empty($reference_media_id)) {
            $this->db->where('reference_media_id', $reference_media_id);
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } else if (!empty($from_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } else if (!empty($to_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        return $this->db->count_all_results();
    }
}
