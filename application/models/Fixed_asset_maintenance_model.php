<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fixed_asset_maintenance_model extends CI_Model
{
    protected $table = 'fa_asset_maintenance';

    public function __construct()
    {
        parent::__construct();
    }

    public function list_by_asset($asset_id)
    {
        return $this->db->where('asset_id', (int) $asset_id)
            ->order_by('maintenance_date', 'DESC')
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row();
    }

    public function insert_row($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete_row($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        return $this->db->affected_rows();
    }
}
