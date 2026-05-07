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
class TestModel extends CI_Model
{
    public function get_emergency($limit, $offset)
    {
        $query = $this->db->get('emergency', $limit, $offset);
        return $query->result();
    }
    public function count_all_report_delivery($invoice_no = '', $mobile = '', $patient_name = '', $from_date = '', $to_date = '')
    {
        $total_rows = 0;
        if ($invoice_no != '' && $mobile == '' && $patient_name == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('invoice_no', $invoice_no)
                ->count_all('patient_test_entry');
        } else if ($patient_name != '' && $mobile == '' && $invoice_no == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('patient_name', $patient_name)
                ->count_all('patient_test_entry');
        } else if ($mobile != '' && $patient_name == '' && $invoice_no == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('mobile', $mobile)
                ->count_all('patient_test_entry');
        } else  if ($from_date != ''  && $to_date && $patient_name == '' && $mobile == '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $total_rows = $this->db
                ->where('date', $from_date)
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name == '' && $mobile != '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('mobile', $mobile)
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name != '' && $mobile == '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('patient_name', $patient_name)
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name == '' && $mobile != '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('mobile', $mobile)
                ->count_all('patient_test_entry');
        } else if ($from_date == '' && $to_date == '' && $mobile == '' && $invoice_no == '' && $patient_name == '') {
            $total_rows = $this->db
                ->count_all('patient_test_entry');
        }
        return $total_rows;
    }
    public function count_all_test($invoice_no = '', $patient_name = '', $mobile = '', $from_date = '', $to_date = '', $status = '')
    {
        if (!empty($invoice_no)) {
            $this->db->where('invoice_no', $invoice_no);
        }

        if (!empty($patient_name)) {
            $this->db->where('patient_name', $patient_name);
        }

        if (!empty($mobile)) {
            $this->db->where('mobile', $mobile);
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('date >=', $from_date);
            $this->db->where('date <=', $to_date);
        } elseif (!empty($from_date)) {
            $this->db->where('date', $from_date);
        } elseif (!empty($to_date)) {
            $this->db->where('date', $to_date);
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        return $this->db->count_all_results('patient_test_entry');
    }

    public function count_all_test1($invoice_no = '', $patient_name = '', $mobile = '', $from_date = '', $to_date = '')
    {
        $total_rows = 0;

        if ($invoice_no != '' && $mobile == '' && $patient_name == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('invoice_no', $invoice_no)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($patient_name != '' && $mobile == '' && $invoice_no == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('patient_name', $patient_name)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($mobile != '' && $patient_name == '' && $invoice_no == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('mobile', $mobile)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else  if ($from_date != ''  && $to_date && $patient_name == '' && $mobile == '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $total_rows = $this->db
                ->where('date', $from_date)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name == '' && $mobile != '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('mobile', $mobile)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name != '' && $mobile == '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('patient_name', $patient_name)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name == '' && $mobile != '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('mobile', $mobile)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date == '' && $to_date == '' && $mobile == '' && $invoice_no == '' && $patient_name == '') {
            $total_rows = $this->db
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        }
        return $total_rows;
    }
    public function count_all_dues($invoice_no = '', $patient_name = '', $mobile = '', $from_date = '', $to_date = '')
    {
        $total_rows = 0;

        if ($invoice_no != '' && $mobile == '' && $patient_name == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('invoice_no', $invoice_no)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($patient_name != '' && $mobile == '' && $invoice_no == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('patient_name', $patient_name)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($mobile != '' && $patient_name == '' && $invoice_no == '' && $from_date == '' && $to_date == '') {
            $total_rows = $this->db
                ->where('mobile', $mobile)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else  if ($from_date != ''  && $to_date && $patient_name == '' && $mobile == '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $total_rows = $this->db
                ->where('date', $from_date)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name == '' && $mobile != '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('mobile', $mobile)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name != '' && $mobile == '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('patient_name', $patient_name)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date != '' && $to_date != '' && $patient_name == '' && $mobile != '' && $invoice_no == '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $total_rows = $this->db
                ->where('date>=', $from_date)
                ->where('date<=', $to_date)
                ->where('mobile', $mobile)
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        } else if ($from_date == '' && $to_date == '' && $mobile == '' && $invoice_no == '' && $patient_name == '') {
            $total_rows = $this->db
                ->where('is_deleted', '0')
                ->count_all('patient_test_entry');
        }
        return $total_rows;
    }

    function opd_patient_details($limit, $start = '', $doctor_id = '', $gender = '', $department_id = '', $from_date = '', $to_date = '')
    {

        if ($department_id != '' && $doctor_id != '' && $gender != '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id' AND gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id == '' && $gender != '' && $from_date != '' && $to_date != '') {
            $sql = "select  * from opd_patient where department_id='$department_id' AND gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender == '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id'  AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender != '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id' AND gender='$gender'  and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id'  and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id == '' && $gender == '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id != '' && $gender = '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where doctor_id='$doctor_id' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender != '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where  gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender != '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id' AND gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id == '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where department_id='$department_id'  and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id != '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where  doctor_id='$doctor_id' and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender != '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where  gender='$gender' and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender == '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        }

        $query = $this->db->query($sql);
        //var_dump($query->result());
        return $query->result();
    }

    function prescription_details($limit, $start = '', $patient_name = '', $gender = '', $date = '', $invoice_no = '')
    {
        if ($invoice_no != '') {

            $sql = "select  * from prescription where invoice_no='$invoice_no' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name != '') {

            $sql = "select  * from prescription where patient_name='$patient_name' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($gender != '') {
            //  var_dump($gender);
            $sql = "select  * from prescription where gender='$gender' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($date != '') {
            //var_dump($date);
            $sql = "select  * from prescription where date='$date' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name == '' && $gender == '' && $date == '' && $invoice_no == '') {
            $sql = "select  * from prescription where is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        //var_dump($query->result());
        return $query->result();
    }

    function patient_details($limit, $start = '', $patient_name = '', $mobile_number = '', $patient_unique_id = '', $gender = '', $reference_media_id = '', $general_bed_id = '', $cabin_id = '', $date = '', $doctor_id = '')
    {
        $sql = '';
        if ($patient_name != '') {
            $sql = "select  * from patient where patient_name='$patient_name' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($mobile_number != '') {
            $sql = "select  * from patient where mobile_number='$mobile_number' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_unique_id != '') {
            $sql = "select  * from patient where patient_unique_id='$patient_unique_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($gender != '') {
            $sql = "select  * from patient where gender='$gender' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($reference_media_id != '') {
            $sql = "select  * from patient where reference_media_id='$reference_media_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($general_bed_id != '') {
            $sql = "select  * from patient where general_bed_id='$general_bed_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($cabin_id != '') {
            $sql = "select  * from patient where cabin_id='$cabin_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($date != '') {
            $sql = "select  * from patient where date='$date' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($doctor_id != '') {
            $sql = "select  * from patient where doctor_id='$doctor_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name == '' && $mobile_number == '' && $patient_unique_id == '' && $gender == '' && $reference_media_id == '' && $general_bed_id == '' && $cabin_id == '' && $date == '' && $doctor_id == '') {
            $sql = "select  * from patient where is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        //var_dump($sql);
        //  }
        $query = $this->db->query($sql);
        // var_dump($query->result());
        return $query->result();
    }
    public function patient_test_entry_details($limit, $offset, $invoice_no = '', $patient_name = '', $mobile = '', $from_date = '', $to_date = '', $status = '')
    {
        if (!empty($invoice_no)) {
            $this->db->where('invoice_no', $invoice_no);
        }

        if (!empty($patient_name)) {
            $this->db->where('patient_name', $patient_name);
        }

        if (!empty($mobile)) {
            $this->db->where('mobile', $mobile);
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('date >=', $from_date);
            $this->db->where('date <=', $to_date);
        } elseif (!empty($from_date)) {
            $this->db->where('date', $from_date);
        } elseif (!empty($to_date)) {
            $this->db->where('date', $to_date);
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        $query = $this->db->order_by('patient_test_entry_id', 'DESC')
            ->get('patient_test_entry', $limit, $offset);

        return $query->result();
    }


    function get_test_result_details($limit, $start, $test_group_id, $test_id, $patient_test_entry_id)
    {

        if ($test_group_id != '' && $test_id == '' && $patient_test_entry_id != '') {
            $sql = "select  * from test_result where test_group_id='$test_group_id' AND test_id='$test_id' AND  and is_deleted='0'  order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id != '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where test_id='$test_id' AND test_group_id='$test_group_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id == '' && $patient_test_entry_id != '') {
            $sql = "select  * from test_result where patient_test_entry_id='$patient_test_entry_id' AND test_group_id='$test_group_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id == '' && $patient_test_entry_id != '') {
            $sql = "select  * from test_result where patient_test_entry_id='$patient_test_entry_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id == '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where test_group_id='$test_group_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id != '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where test_id='$test_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id == '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        // var_dump($query->result());
        return $query->result();
    }

    function doctors_payment_details($limit, $start, $doctor_id = "")
    {

        if ($doctor_id != '') {
            $sql = "select  * from doctor_commission_payment where doctor_id='$doctor_id' and is_deleted='0' order by doctor_commission_payment_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from doctor_commission_payment where is_deleted='0' order by doctor_commission_payment_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        // var_dump($query->result());
        return $query->result();
    }

    function doctors_details($limit, $start, $doctor_id = "")
    {
        if ($doctor_id != '') {
            $sql = "select  * from doctor where doctor_id='$doctor_id' and is_deleted='0' order by doctor_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from doctor where is_deleted='0' order by doctor_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_test_configuration_details($limit, $start, $test_group_id = "", $test_id = "")
    {
        if ($test_group_id != '' && $test_id != '') {
            $sql = "select  * from test_configuration where test_group_id='$test_group_id' AND test_id='$test_id'  and is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id == '') {
            $sql = "select  * from test_configuration where test_group_id='$test_group_id'  and is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id != '') {
            $sql = "select  * from test_configuration where test_id='$test_id'  and is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id == '') {
            $sql = "select  * from test_configuration where is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);

        return $query->result();
    }
}
