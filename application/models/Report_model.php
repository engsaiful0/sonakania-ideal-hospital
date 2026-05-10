<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model
{
    private $table = 'lab_reports';

    public function get_by_panel($panel_id)
    {
        return $this->db->get_where($this->table, ['panel_id' => $panel_id])->result();
    }

    public function get_report($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    /**
     * Get full report with results
     */
    public function get_full_report($id)
    {
        $report = $this->get_report($id);

        $this->db->select('lrr.*, tp.parameter_name, tp.unit')
                 ->from('lab_report_results lrr')
                 ->join('test_parameters tp', 'tp.id = lrr.parameter_id')
                 ->where('lrr.report_id', $id);

        $report->results = $this->db->get()->result();

        return $report;
    }
}