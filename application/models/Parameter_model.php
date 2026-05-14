<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Parameter_model extends CI_Model
{
    private $table = 'test_parameters';

    public function get_by_panel($panel_id)
    {
        $this->db->select('tp.*')
                 ->from($this->table . ' tp')
                 ->join('test_sections ts', 'ts.id = tp.section_id')
                 ->where('ts.panel_id', $panel_id);

        return $this->db->get()->result();
    }

    public function get_by_section($section_id)
    {
        return $this->db->get_where($this->table, ['section_id' => $section_id])->result();
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