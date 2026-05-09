<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fixed_asset_category_model extends CI_Model
{
    protected $table = 'fa_asset_category';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_active()
    {
        return $this->db->where('is_active', 1)
            ->order_by('name', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function get_all_for_list()
    {
        return $this->db->order_by('name', 'ASC')->get($this->table)->result();
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

    public function update_row($id, $data)
    {
        $this->db->where('id', (int) $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete_row($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    public function name_exists($name, $exclude_id = null)
    {
        $this->db->where('name', $name);
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    public function count_assets($category_id)
    {
        return (int) $this->db->where('category_id', (int) $category_id)->count_all_results('fa_asset');
    }
}
