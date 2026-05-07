<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CanteenGoodsModel extends CI_Model
{
    // Method to get unit_id from canteen_raw_goods based on canteen_goods_id
    public function get_goods_by_id($canteen_raw_goods_id)
    {
        $this->db->select('unit_id,price');
        $this->db->from('canteen_raw_goods');
        $this->db->where('canteen_raw_goods_id', $canteen_raw_goods_id);
        $query = $this->db->get();

        return $query->row();  // Return a single row
    }
    public function get_canteen_ready_item_by_id($canteen_ready_item_id)
    {
        $this->db->select('unit_id,price');
        $this->db->from('canteen_ready_items');
        $this->db->where('canteen_ready_item_id', $canteen_ready_item_id);
        $query = $this->db->get();

        return $query->row();  // Return a single row
    }
    
    // Method to get unit_name from the units table based on unit_id
    public function get_unit_by_id($unit_id)
    {
        $this->db->select('unit_id, name');
        $this->db->from('units');
        $this->db->where('unit_id', $unit_id);
        $query = $this->db->get();

        return $query->row();  // Return a single row
    }
}

