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
class IpdServiceModel extends CI_Model
{
    public function get_ipd_service($limit, $offset, $ipd_patient_id = '', $from_date = '', $to_date = '')
    {

        $query = '';
        if ($ipd_patient_id != '' && $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('ipd_patient_id', $ipd_patient_id)
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id != '' && $from_date != '' and $to_date == '') {
            $query = $this->db
                ->where('ipd_patient_id', $ipd_patient_id)
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id != '' && $from_date == '' and $to_date != '') {
            $query = $this->db->where('ipd_patient_id', $ipd_patient_id)
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id != '' && $from_date == '' and $to_date == '') {
            $query = $this->db->where('ipd_patient_id', $ipd_patient_id)->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id == '' && $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id == '' && $from_date != '' and $to_date == '') {
            $query = $this->db
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id == '' && $from_date == '' and $to_date != '') {
            $query = $this->db
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->get('ipd_service', $limit, $offset);
        } else if ($ipd_patient_id == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->order_by('ipd_service_id','DESC')->get('ipd_service', $limit, $offset);
        }
        return $query->result();
    }

    public function count_all_ipd_service($ipd_patient_id, $from_date = '', $to_date = '')
    {
        $query = 0;
        if ($ipd_patient_id != '' && $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('ipd_patient_id', $ipd_patient_id)
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('ipd_service');
        } else if ($ipd_patient_id != '' && $from_date != '' and $to_date == '') {
            $query = $this->db
                ->where('ipd_patient_id', $ipd_patient_id)
                ->where('date', date('Y-m-d', strtotime($from_date)))
                ->count_all('ipd_service');
        } else if ($ipd_patient_id != '' && $from_date == '' and $to_date != '') {
            $query = $this->db
                ->where('ipd_patient_id', $ipd_patient_id)
                ->where('date', date('Y-m-d', strtotime($to_date)))
                ->count_all('ipd_service');
        } else if ($ipd_patient_id != '' && $from_date == '' and $to_date == '') {
            $query = $this->db->where('ipd_patient_id', $ipd_patient_id)->count_all('ipd_service');
        } else if ($ipd_patient_id == '' && $from_date != '' and $to_date != '') {
            $query = $this->db
                ->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('ipd_service');
        } else if ($ipd_patient_id == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('ipd_service');
        }
    }
}
