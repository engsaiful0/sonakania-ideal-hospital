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
                ->order_by('serial', 'ASC')
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
        $id = (int) $id;
        $this->db->select('lr.*, pp.panel_name,pp.test_group_id, pp.description', false)
            ->from($this->table_reports . ' lr')
            ->join('test_panels pp', 'pp.id = lr.panel_id', 'left')
            ->where('lr.id', $id);
        $row = $this->db->get()->row();
        if (!$row) {
            return null;
        }

        // Resolve names explicitly (avoids SELECT alias issues with lr.* / drivers).
        $row->panel_test_group_name = '';
        $row->report_test_group_name = '';
        $tg_from_report = null;

        if ($this->db->field_exists('test_group_id', $this->table_reports)
            && isset($row->test_group_id) && (int) $row->test_group_id > 0) {
            $this->db->reset_query();
            $tg_from_report = $this->db->where('test_group_id', (int) $row->test_group_id)->get('test_group')->row();
            if ($tg_from_report && isset($tg_from_report->test_group_name)) {
                $row->report_test_group_name = trim((string) $tg_from_report->test_group_name);
            }
        }

        $panel_id = isset($row->panel_id) ? (int) $row->panel_id : 0;
        if ($panel_id > 0 && $this->db->field_exists('test_group_id', 'test_panels')) {
            $this->db->reset_query();
            $pp = $this->db->select('test_group_id')->where('id', $panel_id)->get('test_panels')->row();
            if ($pp && isset($pp->test_group_id) && (int) $pp->test_group_id > 0) {
                $this->db->reset_query();
                $tg_panel = $this->db->where('test_group_id', (int) $pp->test_group_id)->get('test_group')->row();
                if ($tg_panel && isset($tg_panel->test_group_name)) {
                    $row->panel_test_group_name = trim((string) $tg_panel->test_group_name);
                }
            }
        }

        // Panel row has no test_group_id: still show group saved on this lab report (add-panel form).
        if ($row->panel_test_group_name === '' && $tg_from_report && isset($tg_from_report->test_group_name)) {
            $row->panel_test_group_name = trim((string) $tg_from_report->test_group_name);
        }

        return $row;
    }

    /**
     * Flat list of result rows with section metadata (ordered).
     * Also returns min_value / max_value (always) and normal_range (if the column exists).
     */
    public function get_report_results($report_id)
    {
        $select = 'lrr.*, tp.parameter_name, tp.unit, tp.input_type, tp.min_value, tp.max_value,'
            . ' ts.id AS section_id, ts.section_name';
        if ($this->db->field_exists('normal_range', 'test_parameters')) {
            $select .= ', tp.normal_range';
        }
        // test_parameters.serial defines display order. Always alias it so it
        // cannot collide with any column from lrr.*, and sort numerically so
        // "10" comes after "9" if the column is stored as text.
        $select .= ', tp.serial AS parameter_serial';

        $this->db->select($select)
    ->from('lab_report_results lrr')
    ->join('test_parameters tp', 'tp.id = lrr.parameter_id')
    ->join('test_sections ts', 'ts.id = tp.section_id')
    ->where('lrr.report_id', (int) $report_id)
    ->order_by('ts.id', 'ASC')
    ->order_by('CAST(tp.serial AS UNSIGNED)', '', false)
    ->order_by('tp.id', 'ASC');

return $this->db->get()->result();
    }

    /**
     * Best-effort "Normal range" string for a result row:
     *  - Prefer literal `normal_range` text on test_parameters when present.
     *  - Else compose from min_value/max_value (with unit appended when available).
     *  - Returns '—' when no info is available.
     */
    public function format_normal_range($row)
    {
        if (!is_object($row)) {
            return '—';
        }
        if (isset($row->normal_range) && trim((string) $row->normal_range) !== '') {
            // The normal_range column may have been entered through a rich-text
            // editor and arrive wrapped in tags like <p>…</p> (or contain &nbsp;,
            // <br>, etc.). Strip all HTML and collapse stray whitespace so the
            // printed cell shows clean text only.
            $raw   = (string) $row->normal_range;
            $clean = strip_tags($raw);
            $clean = html_entity_decode($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $clean = preg_replace('/\s+/u', ' ', $clean);
            $clean = trim($clean);
            if ($clean !== '') {
                return $clean;
            }
        }
        $unit = isset($row->unit) ? trim((string) $row->unit) : '';
        $hmin = isset($row->min_value) && $row->min_value !== null && $row->min_value !== '';
        $hmax = isset($row->max_value) && $row->max_value !== null && $row->max_value !== '';
        $fmt = function ($v) {
            $s = rtrim(rtrim((string) $v, '0'), '.');
            return $s === '' ? '0' : $s;
        };
        if ($hmin && $hmax) {
            return $fmt($row->min_value) . ' – ' . $fmt($row->max_value) . ($unit !== '' ? ' ' . $unit : '');
        }
        if ($hmin) {
            return '≥ ' . $fmt($row->min_value) . ($unit !== '' ? ' ' . $unit : '');
        }
        if ($hmax) {
            return '≤ ' . $fmt($row->max_value) . ($unit !== '' ? ' ' . $unit : '');
        }
        return '—';
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
        $blocks = array_values($blocks);

        foreach ($blocks as &$block) {
            usort($block['rows'], function ($a, $b) {
                $sa = isset($a->parameter_serial) ? (int) $a->parameter_serial : 0;
                $sb = isset($b->parameter_serial) ? (int) $b->parameter_serial : 0;
                if ($sa !== $sb) {
                    return $sa - $sb;
                }
                $pa = isset($a->parameter_id) ? (int) $a->parameter_id : 0;
                $pb = isset($b->parameter_id) ? (int) $b->parameter_id : 0;
                return $pa - $pb;
            });
        }
        unset($block);

        return $blocks;
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
        $this->db->select('lr.id, lr.patient_name, lr.age_year, lr.age_month, lr.age_day, lr.sex, lr.patient_id, lr.report_date, pp.panel_name,
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

        return $this->db->limit($length, $start)->get()->result();
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
