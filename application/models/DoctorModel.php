<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DoctorModel
 *
 * @author sohag
 */
class DoctorModel extends CI_Model
{
    function doctors_details($limit, $start, $doctor_id = "")
    {
        if ($doctor_id != '') {
            $sql = "select  * from doctor where doctor_id='$doctor_id' order by doctor_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from doctor  order by doctor_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        // var_dump($query->result());
        return $query->result();
    }

    public function count_doctor($doctor_id = '')
    {
        $query = '';
        if ($doctor_id != '') {
            return $this->db->where('doctor_id', $doctor_id)
                ->count_all('doctor');
        } else if ($doctor_id == '') {
            return $this->db->count_all('doctor');
        }
    }
}
