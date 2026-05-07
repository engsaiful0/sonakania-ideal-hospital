<?php
class TestResultModel extends CI_Model
{
    public function count_all_test_configuration($test_group_id = '', $test_id = '')
    {
        if ($test_group_id != '' && $test_id != '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id);
        } else if ($test_group_id == '' && $test_id != '') {
            $this->db->where('test_id', $test_id);
        } else if ($test_group_id != ''  && $test_id == '') {
            $this->db->where('test_group_id', $test_group_id);
        }
        return $this->db->count_all_results('test_configuration');
    }
    public function get_test_configuration_details($limit, $offset, $test_group_id = '', $test_id = '')
    {
        if ($test_group_id != '' && $test_id != '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id);
        } else if ($test_group_id == '' && $test_id != '') {
            $this->db->where('test_id', $test_id);
        } else if ($test_group_id != ''  && $test_id == '') {
            $this->db->where('test_group_id', $test_group_id);
        }
        $query = $this->db->order_by('test_configuration_id', 'DESC')->get('test_configuration', $limit, $offset);
        return $query->result();
    }
    public function get_test_result_details($limit, $offset, $test_group_id = '', $test_id = '', $patient_test_entry_id = '', $invoice_no='')
    {
        if ($test_group_id != '' && $test_id != '' && $patient_test_entry_id != '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id)
                ->where('patient_test_entry_id', $patient_test_entry_id);
        } else if ($test_group_id != '' && $test_id != '' && $patient_test_entry_id == '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id);
        } else if ($test_group_id != ''  && $test_id == '' && $patient_test_entry_id != '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('patient_test_entry_id', $patient_test_entry_id);
        } else if ($test_group_id != ''  && $test_id == '' && $patient_test_entry_id == '') {
            $this->db->where('test_group_id', $test_group_id);
        } else if ($test_group_id == ''  && $test_id == '' && $patient_test_entry_id == '' && $invoice_no != '') {
            $this->db->where('invoice_no', $invoice_no);
        } else if ($test_group_id == ''  && $test_id != '' && $patient_test_entry_id == '') {
            $this->db->where('test_id', $test_id);
        } else if ($test_group_id == ''  && $test_id == '' && $patient_test_entry_id != '') {
            $this->db->where('patient_test_entry_id', $patient_test_entry_id);
        }

        $query = $this->db->order_by('test_result_id', 'DESC')->get('test_result', $limit, $offset);
        return $query->result();
    }

    public function count_all_test_result($test_group_id = '', $test_id = '', $patient_test_entry_id = '', $invoice_no='')
    {
        if ($test_group_id != '' && $test_id != '' && $patient_test_entry_id != '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id)
                ->where('patient_test_entry_id', $patient_test_entry_id);
        } else if ($test_group_id != '' && $test_id != '' && $patient_test_entry_id == '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id);
        } else if ($test_group_id != ''  && $test_id == '' && $patient_test_entry_id != '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('patient_test_entry_id', $patient_test_entry_id);
        } else if ($test_group_id == ''  && $test_id == '' && $patient_test_entry_id == '' && $invoice_no != '') {
            $this->db->where('invoice_no', $invoice_no);
        } else if ($test_group_id != ''  && $test_id == '' && $patient_test_entry_id == '') {
            $this->db->where('test_group_id', $test_group_id);
        } else if ($test_group_id == ''  && $test_id != '' && $patient_test_entry_id == '') {
            $this->db->where('test_id', $test_id);
        } else if ($test_group_id == ''  && $test_id == '' && $patient_test_entry_id != '') {
            $this->db->where('patient_test_entry_id', $patient_test_entry_id);
        }

        return $this->db->count_all_results('test_result');
    }
}
