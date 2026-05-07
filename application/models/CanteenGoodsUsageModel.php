<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaleModel
 *
 * @author sohag
 */
class CanteenGoodsUsageModel extends CI_Model
{
    public function get_canteen_goods_usage($limit, $offset, $canteen_goods_usage_invoice_no, $from_date = '', $to_date = '')
    {
        $this->db->from('canteen_goods_usage');
        if ($canteen_goods_usage_invoice_no != '') {
            $this->db->where('canteen_goods_usage_invoice_no', $canteen_goods_usage_invoice_no);
        }

        if ($from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } else if ($from_date == '' && $to_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        } else if ($from_date != '' && $to_date == '') {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        }

        // Apply ordering and limits
        $this->db->order_by('canteen_goods_usage_id', 'DESC');
        $query = $this->db->get('', $limit, $offset);

        // Return results
        return $query->result();
    }

    public function count_all_canteen_goods_usage($canteen_goods_usage_invoice_no, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($canteen_goods_usage_invoice_no != '') {
            $query = $this->db->where('canteen_goods_usage_invoice_no', $canteen_goods_usage_invoice_no)->count_all('canteen_ready_item_sell');
        } else if ($from_date != '' and $to_date != '') {
            $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->count_all('canteen_ready_item_sell');
        } else if ($from_date == '' and $to_date != '') {
            $query = $this->db->where('date', date('Y-m-d', strtotime($to_date)))->count_all('canteen_ready_item_sell');
        } else if ($from_date != '' and $to_date == '') {
            $query = $this->db->where('date', date('Y-m-d', strtotime($from_date)))->count_all('canteen_ready_item_sell');
        } else if ($canteen_goods_usage_invoice_no == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('canteen_goods_usage');
        }
    }
}
