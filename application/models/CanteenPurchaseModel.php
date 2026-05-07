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
class CanteenPurchaseModel extends CI_Model
{
    public function get_canteen_purchase_goods($limit, $offset, $purchase_canteen_goods_invoice_no, $canteen_goods_supplier_id, $from_date = '', $to_date = '')
    {
        $this->db->from('canteen_purchase_goods');

        // Apply conditions based on parameters
        if ($canteen_goods_supplier_id != '') {
            $this->db->where('canteen_goods_supplier_id', $canteen_goods_supplier_id);
        }

        if ($purchase_canteen_goods_invoice_no != '') {
            $this->db->where('purchase_canteen_goods_invoice_no', $purchase_canteen_goods_invoice_no);
        }

        if ($from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } else if ($from_date != '' && $to_date == '') {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } else if ($from_date == '' && $to_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }

        // Apply ordering and limits
        $this->db->order_by('canteen_purchase_good_id', 'DESC');
        $query = $this->db->get('', $limit, $offset);

        // Return results
        return $query->result();
    }

    public function count_all_canteen_purchase_goods($purchase_canteen_goods_invoice_no, $canteen_goods_supplier_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($purchase_canteen_goods_invoice_no != '') {
            return $this->db->where('purchase_canteen_goods_invoice_no', $purchase_canteen_goods_invoice_no)->count_all('canteen_purchase_goods');
        } else if ($canteen_goods_supplier_id != '') {
            return $this->db->where('canteen_goods_supplier_id', $canteen_goods_supplier_id)->count_all('canteen_purchase_goods');
        } else if ($from_date == '' and $to_date != '') {
            return $this->db->where('date', date('Y-m-d', strtotime($to_date)))->count_all('purchase_goods');
        } else if ($from_date != '' and $to_date == '') {
            return $this->db->where('date', date('Y-m-d', strtotime($from_date)))->count_all('purchase_goods');
        } else if ($from_date != '' and $to_date != '') {
            return $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->count_all('purchase_goods');
        } else if ($canteen_goods_supplier_id == '' and $purchase_canteen_goods_invoice_no == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('canteen_purchase_goods');
        }
    }
}
