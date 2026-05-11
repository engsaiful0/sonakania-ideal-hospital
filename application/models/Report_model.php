<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Lab panels (test_panels / test_sections / test_parameters) + lab_reports + lab_report_results
 */
class Report_model extends CI_Model
{
    private $table_reports = 'lab_reports';

    public function insert_panel($data)
    {
        $this->db->insert('test_panels', $data);
        return $this->db->insert_id();
    }

    public function insert_section($data)
    {
        $this->db->insert('test_sections', $data);
        return $this->db->insert_id();
    }

    public function insert_parameter($data)
    {
        $this->db->insert('test_parameters', $data);
        return $this->db->insert_id();
    }

    public function get_panel($id)
    {
        return $this->db->get_where('test_panels', array('id' => (int) $id))->row();
    }

    public function get_all_panels()
    {
        return $this->db->order_by('panel_name', 'ASC')->get('test_panels')->result();
    }

    /**
     * Sections for a panel, each with ->parameters (array of rows from test_parameters).
     */
    public function get_sections_with_parameters($panel_id)
    {
        $panel_id = (int) $panel_id;
        $sections = $this->db->where('panel_id', $panel_id)
            ->order_by('id', 'ASC')
            ->get('test_sections')
            ->result();

        foreach ($sections as $s) {
            $s->parameters = $this->db->where('section_id', (int) $s->id)
                ->order_by('id', 'ASC')
                ->get('test_parameters')
                ->result();
        }

        return $sections;
    }

    public function get_parameter($id)
    {
        return $this->db->get_where('test_parameters', array('id' => (int) $id))->row();
    }

    public function insert_report($data)
    {
        $this->db->insert($this->table_reports, $data);
        return $this->db->insert_id();
    }

    public function insert_result($data)
    {
        return $this->db->insert('lab_report_results', $data);
    }

    public function get_report($id)
    {
        return $this->db->get_where($this->table_reports, array('id' => (int) $id))->row();
    }

    public function get_report_with_panel($id)
    {
        $this->db->select('lr.*, pp.panel_name')
            ->from($this->table_reports . ' lr')
            ->join('test_panels pp', 'pp.id = lr.panel_id', 'left')
            ->where('lr.id', (int) $id);
        return $this->db->get()->row();
    }

    /**
     * Flat list of result rows with section metadata (ordered).
     */
    public function get_report_results($report_id)
    {
        $this->db->select('lrr.*, tp.parameter_name, tp.unit, tp.input_type, ts.id AS section_id, ts.section_name')
            ->from('lab_report_results lrr')
            ->join('test_parameters tp', 'tp.id = lrr.parameter_id')
            ->join('test_sections ts', 'ts.id = tp.section_id')
            ->where('lrr.report_id', (int) $report_id)
            ->order_by('ts.id', 'ASC')
            ->order_by('tp.id', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Grouped by section for display: [ ['section_name' => ..., 'rows' => [...] ], ... ]
     */
    public function get_report_results_grouped_by_section($report_id)
    {
        $rows = $this->get_report_results($report_id);
        $blocks = array();
        foreach ($rows as $r) {
            $sid = (int) $r->section_id;
            if (!isset($blocks[$sid])) {
                $blocks[$sid] = array(
                    'section_name' => $r->section_name,
                    'rows' => array(),
                );
            }
            $blocks[$sid]['rows'][] = $r;
        }
        return array_values($blocks);
    }

    public function get_by_panel($panel_id)
    {
        return $this->db->get_where($this->table_reports, array('panel_id' => (int) $panel_id))->result();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table_reports, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int) $id)->delete($this->table_reports);
    }

    public function insert($data)
    {
        $this->db->insert($this->table_reports, $data);
        return $this->db->insert_id();
    }

    public function get_full_report($id)
    {
        $report = $this->get_report($id);
        if (!$report) {
            return null;
        }
        $this->db->select('lrr.*, tp.parameter_name, tp.unit')
            ->from('lab_report_results lrr')
            ->join('test_parameters tp', 'tp.id = lrr.parameter_id')
            ->where('lrr.report_id', (int) $id);
        $report->results = $this->db->get()->result();
        return $report;
    }

    /**
     * Applies the listing filters (patient text, panel id, date range) to the active query.
     */
    protected function _apply_panel_report_filters($filters)
    {
        $patient = isset($filters['patient']) ? trim((string) $filters['patient']) : '';
        $panel_id = isset($filters['panel_id']) ? (int) $filters['panel_id'] : 0;
        $date_from = isset($filters['date_from']) ? trim((string) $filters['date_from']) : '';
        $date_to = isset($filters['date_to']) ? trim((string) $filters['date_to']) : '';

        if ($patient !== '') {
            $this->db->group_start()
                ->like('lr.patient_name', $patient)
                ->or_like('lr.patient_id', $patient)
                ->group_end();
        }
        if ($panel_id > 0) {
            $this->db->where('lr.panel_id', $panel_id);
        }
        if ($date_from !== '') {
            $ts = strtotime($date_from);
            if ($ts !== false) {
                $this->db->where('lr.report_date >=', date('Y-m-d', $ts));
            }
        }
        if ($date_to !== '') {
            $ts = strtotime($date_to);
            if ($ts !== false) {
                $this->db->where('lr.report_date <=', date('Y-m-d', $ts));
            }
        }
    }

    public function count_panel_reports($filters = array())
    {
        $this->db->reset_query();
        $this->db->from($this->table_reports . ' lr');
        $this->_apply_panel_report_filters($filters);
        return (int) $this->db->count_all_results();
    }

    public function count_panel_reports_total()
    {
        $this->db->reset_query();
        return (int) $this->db->count_all($this->table_reports);
    }

    /**
     * Filtered + paginated rows for the listing.
     * $order_col is the field name, $order_dir is 'asc' or 'desc'.
     */
    public function panel_reports_filtered($filters, $start, $length, $order_col = 'lr.id', $order_dir = 'desc')
    {
        $this->db->reset_query();
        $this->db->select('lr.id, lr.patient_name, lr.age, lr.sex, lr.patient_id, lr.report_date, pp.panel_name,
            (SELECT COUNT(*) FROM lab_report_results lrr WHERE lrr.report_id = lr.id) AS result_count')
            ->from($this->table_reports . ' lr')
            ->join('test_panels pp', 'pp.id = lr.panel_id', 'left');

        $this->_apply_panel_report_filters($filters);

        $allowed_order = array('lr.id', 'lr.report_date', 'lr.patient_name', 'pp.panel_name');
        if (!in_array($order_col, $allowed_order, true)) {
            $order_col = 'lr.id';
        }
        $order_dir = strtolower((string) $order_dir) === 'asc' ? 'asc' : 'desc';

        $this->db->order_by($order_col, $order_dir);
        $start = max(0, (int) $start);
        $length = max(1, (int) $length);

        return $this->db->get(null, $length, $start)->result();
    }

    public function delete_report_with_results($id)
    {
        $id = (int) $id;
        if ($id < 1) {
            return false;
        }
        $this->db->where('report_id', $id)->delete('lab_report_results');
        return (bool) $this->db->where('id', $id)->delete($this->table_reports);
    }
}
