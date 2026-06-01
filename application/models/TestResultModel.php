<?php
class TestResultModel extends CI_Model
{
    /**
     * Shared filter for test-entry list in Add Test Result.
     */
    private function apply_add_result_filters($invoice_id = '', $mobile_number = '', $test_name = '')
    {
        $invoice_id = trim((string) $invoice_id);
        $mobile_number = trim((string) $mobile_number);
        $test_name = trim((string) $test_name);

        if ($invoice_id !== '') {
            $this->db->like('pte.invoice_no', $invoice_id);
        }
        if ($mobile_number !== '') {
            $this->db->group_start()
                ->like('pte.mobile_number', $mobile_number)
                ->or_like('pte.mobile_number', $mobile_number)
                ->group_end();
        }
        if ($test_name !== '') {
            $this->db->like('t.test_name', $test_name);
        }
    }

    public function count_add_test_result_entries($invoice_id = '', $mobile_number = '', $test_name = '')
    {
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('patient_test_entry pte', 'pte.patient_test_entry_id = pted.patient_test_entry_id', 'inner');
        $this->db->join('test t', 't.test_id = pted.test_id', 'inner');
        $this->apply_add_result_filters($invoice_id, $mobile_number, $test_name);

        return (int) $this->db->count_all_results();
    }

    public function get_add_test_result_entries($limit, $offset, $invoice_id = '', $mobile_number = '', $test_name = '')
    {
        $this->db->select("
            pted.patient_test_entry_details_id,
            pted.patient_test_entry_id,
            pted.test_id,
            pte.invoice_no,
            pte.patient_name,
            COALESCE(NULLIF(pte.mobile_number, ''), pte.mobile_number) AS mobile_number,
            pte.date AS test_date,
            t.test_name,
            d.doctor_name AS referring_doctor_name,
            d.degree AS referring_doctor_degree,
            (
                SELECT MAX(tr.test_result_id)
                FROM test_result tr
                INNER JOIN test_result_details trd ON trd.test_result_id = tr.test_result_id
                WHERE tr.patient_test_entry_id = pte.patient_test_entry_id
                    AND trd.test_id = pted.test_id
                    AND IFNULL(trd.is_deleted, 0) = 0
            ) AS existing_test_result_id
        ", false);
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('patient_test_entry pte', 'pte.patient_test_entry_id = pted.patient_test_entry_id', 'inner');
        $this->db->join('test t', 't.test_id = pted.test_id', 'inner');
        $this->db->join('doctor d', 'd.doctor_id = pte.reference_doctor_id', 'left');
        $this->apply_add_result_filters($invoice_id, $mobile_number, $test_name);
        $this->db->order_by('pted.patient_test_entry_details_id', 'DESC');

        return $this->db->get('', (int) $limit, (int) $offset)->result();
    }

    public function get_add_test_result_entry($entry_detail_id)
    {
        $entry_detail_id = (int) $entry_detail_id;
        if ($entry_detail_id < 1) {
            return null;
        }

        $this->db->select("
            pted.patient_test_entry_details_id,
            pted.patient_test_entry_id,
            pted.test_id,
            pte.invoice_no,
            pte.patient_name,
            COALESCE(NULLIF(pte.mobile_number, ''), pte.mobile_number) AS mobile_number,
            pte.date AS test_date,
            pte.time AS test_time,
            t.test_name,
            t.test_group_id,
            d.doctor_name AS referring_doctor_name,
            d.degree AS referring_doctor_degree,
            (
                SELECT MAX(tr.test_result_id)
                FROM test_result tr
                INNER JOIN test_result_details trd ON trd.test_result_id = tr.test_result_id
                WHERE tr.patient_test_entry_id = pte.patient_test_entry_id
                    AND trd.test_id = pted.test_id
                    AND IFNULL(trd.is_deleted, 0) = 0
            ) AS existing_test_result_id
        ", false);
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('patient_test_entry pte', 'pte.patient_test_entry_id = pted.patient_test_entry_id', 'inner');
        $this->db->join('test t', 't.test_id = pted.test_id', 'inner');
        $this->db->join('doctor d', 'd.doctor_id = pte.reference_doctor_id', 'left');
        $this->db->where('pted.patient_test_entry_details_id', $entry_detail_id);

        return $this->db->get()->row();
    }

    public function count_all_test_configuration($test_group_id = '', $test_id = '')
    {
        $this->db->reset_query();
        $test_group_id = $test_group_id !== null && $test_group_id !== false ? (string) $test_group_id : '';
        $test_id = $test_id !== null && $test_id !== false ? (string) $test_id : '';
        $test_group_id = trim($test_group_id);
        $test_id = trim($test_id);
        if ($test_group_id !== '' && $test_id !== '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id);
        } elseif ($test_group_id === '' && $test_id !== '') {
            $this->db->where('test_id', $test_id);
        } elseif ($test_group_id !== '' && $test_id === '') {
            $this->db->where('test_group_id', $test_group_id);
        }
        return $this->db->count_all_results('test_configuration');
    }
    public function get_test_configuration_details($limit, $offset, $test_group_id = '', $test_id = '')
    {
        $this->db->reset_query();
        $test_group_id = $test_group_id !== null && $test_group_id !== false ? (string) $test_group_id : '';
        $test_id = $test_id !== null && $test_id !== false ? (string) $test_id : '';
        $test_group_id = trim($test_group_id);
        $test_id = trim($test_id);
        if ($test_group_id !== '' && $test_id !== '') {
            $this->db->where('test_group_id', $test_group_id)
                ->where('test_id', $test_id);
        } elseif ($test_group_id === '' && $test_id !== '') {
            $this->db->where('test_id', $test_id);
        } elseif ($test_group_id !== '' && $test_id === '') {
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
