<?php
class TestResultModel extends CI_Model
{
    /**
     * Whether a list/entry row should use the panel-test (lab_reports) flow.
     *
     * @param object|null $row
     * @return bool
     */
    public function entry_is_panel_test($row)
    {
        if (!$row) {
            return false;
        }
        if (isset($row->setting_type) && strcasecmp(trim((string) $row->setting_type), 'Unique') === 0) {
            return true;
        }

        return isset($row->resolved_panel_id) && (int) $row->resolved_panel_id > 0;
    }

    /**
     * Unit from test_configuration for result entry display.
     *
     * @return string
     */
    private function add_result_entry_unit_select()
    {
        return ",
            (
                SELECT tc.unit
                FROM test_configuration tc
                WHERE tc.test_id = pted.test_id
                    AND IFNULL(tc.is_deleted, 0) = 0
                ORDER BY tc.test_configuration_id DESC
                LIMIT 1
            ) AS unit_name";
    }

    /**
     * Subquery: latest active saved result for this invoice line.
     *
     * @return string
     */
    private function add_result_entry_existing_test_result_subquery()
    {
        return ",
            (
                SELECT MAX(tr.test_result_id)
                FROM test_result tr
                INNER JOIN test_result_details trd ON trd.test_result_id = tr.test_result_id
                WHERE tr.patient_test_entry_id = pte.patient_test_entry_id
                    AND trd.test_id = pted.test_id
                    AND IFNULL(trd.is_deleted, 0) = 0
                    AND TRIM(IFNULL(trd.test_configuration_value, '')) != ''
            ) AS existing_test_result_id";
    }

    /**
     * Extra SELECT fragments for add-test-result list/detail queries.
     *
     * @return string
     */
    private function add_result_entry_select_extras()
    {
        return ",
            t.setting_type,
            (
                SELECT tp.id
                FROM test_panels tp
                WHERE tp.panel_name = t.test_name
                ORDER BY tp.id ASC
                LIMIT 1
            ) AS resolved_panel_id,
            (
                SELECT MAX(lr.id)
                FROM lab_reports lr
                INNER JOIN test_panels tp2 ON tp2.id = lr.panel_id
                WHERE lr.patient_id = pte.invoice_no
                    AND tp2.panel_name = t.test_name
            ) AS existing_lab_report_id";
    }

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
            t.test_group_id,
            tg.test_group_name,
            d.doctor_name AS referring_doctor_name,
            d.degree AS referring_doctor_degree,
            " . $this->add_result_entry_existing_test_result_subquery() . $this->add_result_entry_unit_select() . $this->add_result_entry_select_extras() . "
        ", false);
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('patient_test_entry pte', 'pte.patient_test_entry_id = pted.patient_test_entry_id', 'inner');
        $this->db->join('test t', 't.test_id = pted.test_id', 'inner');
        $this->db->join('test_group tg', 'tg.test_group_id = t.test_group_id', 'left');
        $this->db->join('doctor d', 'd.doctor_id = pte.reference_doctor_id', 'left');
        $this->apply_add_result_filters($invoice_id, $mobile_number, $test_name);
        $this->db->order_by('pted.patient_test_entry_details_id', 'DESC');

        return $this->db->get('', (int) $limit, (int) $offset)->result();
    }

    /**
     * Resolve patient_test_entry_details_id for a saved single-test result (add-test-result flow).
     *
     * @param int $test_result_id
     * @return object|null
     */
    /**
     * Find an existing test_result for invoice entry + test (ignores soft-deleted details).
     *
     * @param int $patient_test_entry_id
     * @param int $test_id
     * @return int
     */
    public function find_existing_test_result_id($patient_test_entry_id, $test_id)
    {
        $patient_test_entry_id = (int) $patient_test_entry_id;
        $test_id = (int) $test_id;
        if ($patient_test_entry_id < 1 || $test_id < 1) {
            return 0;
        }

        $row = $this->db->select('MAX(tr.test_result_id) AS test_result_id', false)
            ->from('test_result tr')
            ->join('test_result_details trd', 'trd.test_result_id = tr.test_result_id', 'inner')
            ->where('tr.patient_test_entry_id', $patient_test_entry_id)
            ->where('trd.test_id', $test_id)
            ->get()
            ->row();

        return $row && !empty($row->test_result_id) ? (int) $row->test_result_id : 0;
    }

    public function get_entry_detail_id_for_test_result($test_result_id)
    {
        $test_result_id = (int) $test_result_id;
        if ($test_result_id < 1) {
            return null;
        }

        $this->db->select('pted.patient_test_entry_details_id', false);
        $this->db->from('test_result tr');
        $this->db->join('test_result_details trd', 'trd.test_result_id = tr.test_result_id', 'inner');
        $this->db->join(
            'patient_test_entry_details pted',
            'pted.patient_test_entry_id = tr.patient_test_entry_id AND pted.test_id = trd.test_id',
            'inner'
        );
        $this->db->where('tr.test_result_id', $test_result_id);
        $this->db->where('IFNULL(trd.is_deleted, 0) =', 0, false);
        $this->db->order_by('trd.test_result_details_id', 'DESC');
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    /**
     * Load multiple patient_test_entry_details rows by id (same query shape as list).
     *
     * @param array $detail_ids
     * @return array
     */
    public function get_add_test_result_entries_by_ids(array $detail_ids)
    {
        $detail_ids = array_values(array_unique(array_filter(array_map('intval', $detail_ids))));
        if (empty($detail_ids)) {
            return array();
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
            tg.test_group_name,
            d.doctor_name AS referring_doctor_name,
            d.degree AS referring_doctor_degree,
            " . $this->add_result_entry_existing_test_result_subquery() . $this->add_result_entry_unit_select() . $this->add_result_entry_select_extras() . "
        ", false);
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('patient_test_entry pte', 'pte.patient_test_entry_id = pted.patient_test_entry_id', 'inner');
        $this->db->join('test t', 't.test_id = pted.test_id', 'inner');
        $this->db->join('test_group tg', 'tg.test_group_id = t.test_group_id', 'left');
        $this->db->join('doctor d', 'd.doctor_id = pte.reference_doctor_id', 'left');
        $this->db->where_in('pted.patient_test_entry_details_id', $detail_ids);
        $this->db->order_by('t.test_name', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Ensure selected rows share one invoice and one test group (non-panel).
     *
     * @param array $detail_ids
     * @return array{ok:bool,message:string,entries:array}
     */
    public function validate_group_result_selection(array $detail_ids)
    {
        $entries = $this->get_add_test_result_entries_by_ids($detail_ids);
        if (count($entries) !== count(array_unique(array_filter(array_map('intval', $detail_ids))))) {
            return array('ok' => false, 'message' => 'One or more selected tests were not found.', 'entries' => array());
        }
        if (empty($entries)) {
            return array('ok' => false, 'message' => 'Please select at least one test.', 'entries' => array());
        }

        $invoice_no = null;
        $test_group_id = null;
        foreach ($entries as $entry) {
            if ($this->entry_is_panel_test($entry)) {
                return array(
                    'ok' => false,
                    'message' => 'Panel tests cannot be combined. Select parameter tests from the same test group only.',
                    'entries' => array(),
                );
            }
            $inv = trim((string) $entry->invoice_no);
            $gid = (int) $entry->test_group_id;
            if ($invoice_no === null) {
                $invoice_no = $inv;
                $test_group_id = $gid;
            } elseif ($invoice_no !== $inv || $test_group_id !== $gid) {
                return array(
                    'ok' => false,
                    'message' => 'Selected tests must belong to the same invoice and the same test group.',
                    'entries' => array(),
                );
            }
        }

        return array('ok' => true, 'message' => '', 'entries' => $entries);
    }

    /**
     * Existing test_result header for invoice + test group (combined group report).
     *
     * @param int $patient_test_entry_id
     * @param int $test_group_id
     * @return int
     */
    public function find_existing_test_result_for_group($patient_test_entry_id, $test_group_id)
    {
        $patient_test_entry_id = (int) $patient_test_entry_id;
        $test_group_id = (int) $test_group_id;
        if ($patient_test_entry_id < 1 || $test_group_id < 1) {
            return 0;
        }

        $row = $this->db->select('MAX(tr.test_result_id) AS test_result_id', false)
            ->from('test_result tr')
            ->where('tr.patient_test_entry_id', $patient_test_entry_id)
            ->where('tr.test_group_id', $test_group_id)
            ->get()
            ->row();

        return ($row && !empty($row->test_result_id)) ? (int) $row->test_result_id : 0;
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
            " . $this->add_result_entry_existing_test_result_subquery() . $this->add_result_entry_unit_select() . $this->add_result_entry_select_extras() . "
        ", false);
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('patient_test_entry pte', 'pte.patient_test_entry_id = pted.patient_test_entry_id', 'inner');
        $this->db->join('test t', 't.test_id = pted.test_id', 'inner');
        $this->db->join('test_group tg', 'tg.test_group_id = t.test_group_id', 'left');
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

    /**
     * Ensure a test_result header exists for panel/unique test description saves.
     *
     * @return int test_result_id
     */
    public function ensure_panel_test_result_header($patient_test_entry_id, $test_group_id, $invoice_no, $test_result_id = 0, $test_result_no = '')
    {
        $patient_test_entry_id = (int) $patient_test_entry_id;
        $test_group_id = (int) $test_group_id;
        $test_result_id = (int) $test_result_id;

        if ($test_result_id > 0) {
            $existing = $this->db->where('test_result_id', $test_result_id)->get('test_result')->row();
            if ($existing && (int) $existing->patient_test_entry_id === $patient_test_entry_id) {
                return $test_result_id;
            }
        }

        if ($patient_test_entry_id > 0 && $test_group_id > 0) {
            $found = $this->find_existing_test_result_for_group($patient_test_entry_id, $test_group_id);
            if ($found > 0) {
                return $found;
            }
        }

        if ($patient_test_entry_id < 1) {
            return 0;
        }

        if ($test_result_no === '') {
            $serial = $this->db->select('*')->get('test_result');
            $test_result_no = 'TR' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
        }

        $header = array(
            'patient_test_entry_id' => $patient_test_entry_id,
            'test_group_id' => $test_group_id > 0 ? $test_group_id : null,
            'invoice_no' => (string) $invoice_no,
            'manual_or_dynamic_report' => 'Dynamic',
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'user_id' => $this->session->userdata('user_id'),
            'manual_report' => '',
            'test_result_no' => $test_result_no,
        );
        if (!$this->db->insert('test_result', $header)) {
            return 0;
        }

        return (int) $this->db->insert_id();
    }

    /**
     * Table name for saved panel/unique test descriptions.
     *
     * @return string
     */
    private function test_result_descriptions_table()
    {
        return 'test_result_descriptions';
    }

    /**
     * Next primary key (table has no AUTO_INCREMENT).
     *
     * @return int
     */
    private function next_test_result_description_id()
    {
        $row = $this->db->select_max('test_result_description_id', 'max_id')
            ->get($this->test_result_descriptions_table())
            ->row();

        return ($row && !empty($row->max_id)) ? ((int) $row->max_id + 1) : 1;
    }

    /**
     * Saved section descriptions in panel section order (one row per section).
     *
     * @return array<int,string>
     */
    public function get_test_result_description_list($test_result_id, $test_id = 0)
    {
        $list = array();
        $test_result_id = (int) $test_result_id;
        if ($test_result_id < 1 || !$this->db->table_exists($this->test_result_descriptions_table())) {
            return $list;
        }

        $this->db->where('test_result_id', $test_result_id);
        if ((int) $test_id > 0) {
            $this->db->where('test_id', (int) $test_id);
        }
        $this->db->order_by('test_result_description_id', 'ASC');
        foreach ($this->db->get($this->test_result_descriptions_table())->result() as $row) {
            $list[] = isset($row->result_description) ? (string) $row->result_description : '';
        }

        return $list;
    }

    /**
     * Save section descriptions to test_result_descriptions
     * (test_result_id, test_id, result_description, user_id).
     *
     * @param int   $test_result_id
     * @param int   $test_id
     * @param array $section_descriptions [section_id => html/text]
     * @return bool
     */
    public function save_test_result_descriptions($test_result_id, $test_id, $section_descriptions)
    {
        $test_result_id = (int) $test_result_id;
        $test_id = (int) $test_id;
        if ($test_result_id < 1 || $test_id < 1 || !$this->db->table_exists($this->test_result_descriptions_table())) {
            return false;
        }
        if (!is_array($section_descriptions)) {
            $section_descriptions = array();
        }

        $this->db->where('test_result_id', $test_result_id)
            ->where('test_id', $test_id)
            ->delete($this->test_result_descriptions_table());

        $user_id = $this->session->userdata('user_id');
        $next_id = $this->next_test_result_description_id();

        foreach ($section_descriptions as $description) {
            $description = (string) $description;
            $this->db->insert($this->test_result_descriptions_table(), array(
                'test_result_description_id' => $next_id,
                'test_result_id' => $test_result_id,
                'test_id' => $test_id,
                'result_description' => $description,
                'user_id' => $user_id ? (int) $user_id : null,
            ));
            $next_id++;
        }

        return true;
    }

    /**
     * Remove panel-test auxiliary rows (descriptions + empty test_result header).
     *
     * @param int $patient_test_entry_id
     * @param int $test_id
     * @return void
     */
    public function delete_panel_test_related_records($patient_test_entry_id, $test_id)
    {
        $patient_test_entry_id = (int) $patient_test_entry_id;
        $test_id = (int) $test_id;
        if ($patient_test_entry_id < 1 || $test_id < 1) {
            return;
        }

        $test_result_ids = array();
        if ($this->db->table_exists('test_result_descriptions')) {
            $this->db->select('DISTINCT test_result_id', false)
                ->from('test_result_descriptions')
                ->where('test_id', $test_id);
            $desc_rows = $this->db->get()->result();
            foreach ($desc_rows as $dr) {
                if (!empty($dr->test_result_id)) {
                    $test_result_ids[] = (int) $dr->test_result_id;
                }
            }
            $this->db->where('test_id', $test_id)->delete('test_result_descriptions');
        }

        $header = $this->db->select('test_result_id')
            ->from('test_result')
            ->where('patient_test_entry_id', $patient_test_entry_id)
            ->order_by('test_result_id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
        if ($header && !empty($header->test_result_id)) {
            $test_result_ids[] = (int) $header->test_result_id;
        }

        $test_result_ids = array_values(array_unique(array_filter($test_result_ids)));
        foreach ($test_result_ids as $test_result_id) {
            $remaining = (int) $this->db->where('test_result_id', $test_result_id)
                ->where('IFNULL(is_deleted, 0) =', 0, false)
                ->count_all_results('test_result_details');
            if ($remaining === 0) {
                $this->db->where('test_result_id', $test_result_id)->delete('test_result');
                $this->db->where('test_result_id', $test_result_id)->delete('test_result_details');
            }
        }
    }
}
