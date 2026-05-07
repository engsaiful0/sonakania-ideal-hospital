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
class OTServiceModel extends CI_Model
{
    public function get_ot_service($limit, $offset, $ot_service_unique_id = '', $patient_unique_id = '', $from_date = '', $to_date = '', $surgery_id = '')
    {
        $this->db->from('ot_services');

        if ($patient_unique_id != '') {
            $this->db->where('patient_unique_id', $patient_unique_id);
        }

        if ($surgery_id != '') {
            $this->db->where('surgery_id', $surgery_id);
        }

        if ($from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } else if ($from_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } else if ($to_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        if ($ot_service_unique_id != '') {
            $this->db->where('ot_service_unique_id', $ot_service_unique_id);
        }

        $this->db->order_by('ot_service_id', 'DESC');
        $query = $this->db->get('', $limit, $offset);

        return $query->result();
    }


    public function count_all_ot_service($ot_service_unique_id = '', $patient_unique_id = '', $from_date = '', $to_date = '', $surgery_id = '')
    {
        if ($patient_unique_id != '') {
            $this->db->where('patient_unique_id', $patient_unique_id);
        }

        if ($surgery_id != '') {
            $this->db->where('surgery_id', $surgery_id);
        }

        if ($from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } else if ($from_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } else if ($to_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        if ($ot_service_unique_id != '') {
            $this->db->where('ot_service_unique_id', $ot_service_unique_id);
        }

        return $this->db->count_all_results('ot_services');
    }
}
