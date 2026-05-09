<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fixed_asset_model extends CI_Model
{
    protected $table = 'fa_asset';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Fa_depreciation');
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

    public function code_exists($code, $exclude_id = null)
    {
        $this->db->where('asset_code', $code);
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Apply straight-line figures to row fields before save/update.
     */
    public function apply_depreciation_fields(array &$row)
    {
        $p = $this->fa_depreciation->straight_line(
            $row['purchase_cost'],
            $row['salvage_value'],
            $row['useful_life_years'],
            $row['purchase_date'],
            date('Y-m-d')
        );
        $row['annual_depreciation'] = $p['annual_depreciation'];
        $row['accumulated_depreciation'] = $p['accumulated_depreciation'];
        $row['current_book_value'] = $p['current_book_value'];
    }

    public function dashboard_stats()
    {
        $total = (int) $this->db->count_all($this->table);
        $this->db->select_sum('current_book_value', 'sum_book');
        $sum_row = $this->db->get($this->table)->row();
        $total_value = $sum_row && $sum_row->sum_book !== null ? (float) $sum_row->sum_book : 0;

        $this->db->select_sum('annual_depreciation', 'sum_annual');
        $this->db->select_sum('accumulated_depreciation', 'sum_accum');
        $dep = $this->db->get($this->table)->row();

        return array(
            'asset_count' => $total,
            'total_book_value' => round($total_value, 2),
            'total_annual_depreciation' => $dep && $dep->sum_annual !== null ? round((float) $dep->sum_annual, 2) : 0,
            'total_accumulated_depreciation' => $dep && $dep->sum_accum !== null ? round((float) $dep->sum_accum, 2) : 0,
        );
    }

    public function depreciation_summary_by_category()
    {
        return $this->db->select('c.name AS category_name, COUNT(a.id) AS asset_count, SUM(a.current_book_value) AS total_book_value, SUM(a.annual_depreciation) AS total_annual, SUM(a.accumulated_depreciation) AS total_accum')
            ->from($this->table . ' a')
            ->join('fa_asset_category c', 'c.id = a.category_id', 'left')
            ->group_by('a.category_id, c.name')
            ->order_by('c.name', 'ASC')
            ->get()
            ->result();
    }

    public function category_wise_report()
    {
        return $this->db->select('c.id AS category_id, c.name AS category_name, COUNT(a.id) AS asset_count, SUM(a.purchase_cost) AS total_cost, SUM(a.current_book_value) AS total_book_value')
            ->from($this->table . ' a')
            ->join('fa_asset_category c', 'c.id = a.category_id', 'left')
            ->group_by('c.id, c.name')
            ->order_by('c.name', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Server-side DataTables source for assets list.
     *
     * @return array{0:object[],1:int}
     */
    public function datatable_filtered($search, $start, $length)
    {
        $this->_asset_list_base_query($search);
        $total_filtered = (int) $this->db->count_all_results();

        $this->_asset_list_base_query($search);
        $this->db->select('a.*, c.name AS category_name, d.department_name, e.employee_name');
        $this->db->order_by('a.id', 'DESC');
        $this->db->limit((int) $length, (int) $start);
        $rows = $this->db->get()->result();

        return array($rows, $total_filtered);
    }

    protected function _asset_list_base_query($search)
    {
        $this->db->from($this->table . ' a');
        $this->db->join('fa_asset_category c', 'c.id = a.category_id', 'left');
        $this->db->join('department d', 'd.department_id = a.department_id', 'left');
        $this->db->join('employee e', 'e.employee_id = a.employee_id', 'left');
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('a.asset_name', $search);
            $this->db->or_like('a.asset_code', $search);
            $this->db->or_like('c.name', $search);
            $this->db->or_like('d.department_name', $search);
            $this->db->or_like('e.employee_name', $search);
            $this->db->group_end();
        }
    }

    public function count_all_assets()
    {
        return (int) $this->db->count_all($this->table);
    }
}
