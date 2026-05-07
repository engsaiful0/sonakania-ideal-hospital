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
class PhygiotherapyModel extends CI_Model
{
    public function get_phygiotherapy($limit, $offset, $name = '', $phone = '', $phygiotherapy_invoice_no = '', $gender = '', $reference_doctor_id = '', $reference_media_id = '', $from_date = '', $to_date = '', $status = '')
    {
        $this->db->from('phygiotherapy');

        // Apply filters dynamically
        if (!empty($name)) {
            $this->db->where('name', $name);
        }

        if (!empty($phone)) {
            $this->db->where('phone', $phone);
        }

        if (!empty($phygiotherapy_invoice_no)) {
            $this->db->where('phygiotherapy_invoice_no', $phygiotherapy_invoice_no);
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
        } elseif (!empty($from_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } elseif (!empty($to_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        if (!empty($status)) {
            $this->db->where('status', $status); // assuming there is a `status` field
        }

        $this->db->order_by('phygiotherapy_id', 'DESC');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result();
    }


    public function count_all_phygiotherapy($name = '', $phone = '', $phygiotherapy_invoice_no = '', $gender = '', $reference_doctor_id = '', $reference_media_id = '', $from_date = '', $to_date = '', $status = '')
    {
        $this->db->from('phygiotherapy');

        // Apply filters dynamically
        if (!empty($name)) {
            $this->db->where('name', $name);
        }

        if (!empty($phone)) {
            $this->db->where('phone', $phone);
        }

        if (!empty($phygiotherapy_invoice_no)) {
            $this->db->where('phygiotherapy_invoice_no', $phygiotherapy_invoice_no);
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
        } elseif (!empty($from_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } elseif (!empty($to_date)) {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        if (!empty($status)) {
            $this->db->where('status', $status); // optional if status field exists
        }

        return $this->db->count_all_results();
    }
}
