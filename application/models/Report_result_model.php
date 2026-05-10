<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_result_model extends CI_Model
{
    private $table = 'lab_report_results';

    public function get_by_panel($report_id)
    {
        $this->db->select('lrr.*, tp.parameter_name')
                 ->from($this->table . ' lrr')
                 ->join('test_parameters tp', 'tp.id = lrr.parameter_id')
                 ->where('lrr.report_id', $report_id);

        return $this->db->get()->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}