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
class IpdPatientModel extends CI_Model
{
    public function get_emergency($limit, $offset)
    {
        $query = $this->db->get('emergency', $limit, $offset);
        return $query->result();
    }

    public function count_all_emergency()
    {
        return $this->db->count_all('emergency');
    }
    function count_all_ipd_patients(
        $patient_name = '',
        $mobile_number = '',
        $patient_unique_id = '',
        $gender = '',
        $reference_media_id = '',
        $ward_id = '',
        $bed_id = '',
        $cabin_id = '',
        $reference_doctor_id = '',
        $from_date = '',
        $to_date = '',
        $status = ''
    ) {
        $this->db->from('ipd_patient');

        if ($patient_name != '') {
            $this->db->where('patient_name', $patient_name);
        }
        if ($mobile_number != '') {
            $this->db->where('mobile_number', $mobile_number);
        }
        if ($patient_unique_id != '') {
            $this->db->where('patient_unique_id', $patient_unique_id);
        }
        if ($gender != '') {
            $this->db->where('gender', $gender);
        }
        if ($reference_media_id != '') {
            $this->db->where('reference_media_id', $reference_media_id);
        }
        if ($ward_id != '') {
            $this->db->where('ward_id', $ward_id);
        }
        if ($bed_id != '') {
            $this->db->where('bed_id', $bed_id);
        }
        if ($cabin_id != '') {
            $this->db->where('cabin_id', $cabin_id);
        }
        if ($reference_doctor_id != '') {
            $this->db->where('reference_doctor_id', $reference_doctor_id);
        }
        if ($from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } elseif ($from_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } elseif ($to_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        return $this->db->count_all_results();
    }

    function ipd_patient_details($limit, $offset, $patient_name = '', $mobile_number = '', $patient_unique_id = '', $gender = '', $reference_media_id = '', $ward_id = '', $bed_id = '', $cabin_id = '', $reference_doctor_id = '', $from_date = '', $to_date = '', $status = '')
    {
        $this->db->from('ipd_patient');
        // print_r("patient_name=".$patient_name);
        // print_r("mobile_number=".$mobile_number);
        // print_r("patient_unique_id=".$patient_unique_id);
        // print_r("gender=".$gender);
        // print_r("reference_media_id=".$reference_media_id);
        // print_r("ward_id=".$ward_id);
        // print_r("bed_id=".$bed_id);
        // print_r("cabin_id=".$cabin_id);
        // print_r("reference_doctor_id=".$reference_doctor_id);
        // print_r("from_date=".$from_date);
        // print_r("to_date=".$to_date);
        // print_r("status=".$status);


        // Add filters if values are provided
        if ($patient_name != '') {
              // die("patient_name");
            $this->db->where('patient_name', $patient_name);
        }
        if ($mobile_number != '') {
              // die("mobile_number");
            $this->db->where('mobile_number', $mobile_number);
        }
        if ($patient_unique_id != '') {
              // die("patient_unique_id");
            $this->db->where('patient_unique_id', $patient_unique_id);
        }
        if ($gender != '') {
              // die("gender");
            $this->db->where('gender', $gender);
        }
        if ($reference_media_id != '') {
            // die("reference_media_id");
            $this->db->where('reference_media_id', $reference_media_id);
        }
        if ($ward_id != '') {
            // die("ward_id");
            $this->db->where('ward_id', $ward_id);
        }
        if ($bed_id != '') {
          // die("bed_id");
            $this->db->where('bed_id', $bed_id);
        }
        if ($cabin_id != '') {
          // die("cabin_id");
            $this->db->where('cabin_id', $cabin_id);
        }
        if ($reference_doctor_id != '') {
            // die("reference_doctor_id");
            $this->db->where('reference_doctor_id', $reference_doctor_id);
        }
        if ($from_date != '') {
            // die("from_date");
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date != '') {
            // die("to_date");
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        }
        if ($status != '') {
          // die("status");
            $this->db->where('status', $status);
        }

        $query = $this->db->order_by('ipd_patient_id', 'DESC')->limit($limit, $offset)->get()->result();
        return $query;
    }
}
