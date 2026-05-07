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
class PurchaseGoodsModel extends CI_Model {
    public function get_purchase_goods($limit, $offset, $purchase_goods_invoice_no, $supplier_id, $from_date = '', $to_date = '') {
        // Initialize the query builder
        $this->db->from('purchase_goods');
        
        // Apply conditions based on parameters
        if ($supplier_id != '') {
            $this->db->where('supplier_id', $supplier_id);
        } 
    
        if ($purchase_goods_invoice_no != '') {
            $this->db->where('purchase_goods_invoice_no', $purchase_goods_invoice_no);
        } 
    
        if ($from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        }
    
        // Apply ordering and limits
        $this->db->order_by('purchase_goods_id', 'DESC');
        $query = $this->db->get('', $limit, $offset);
        
        // Return results
        return $query->result();
    }
    

    public function count_all_purchase_goods($purchase_goods_invoice_no, $supplier_id, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($purchase_goods_invoice_no != '') {
            $query = $this->db->where('purchase_goods_invoice_no', $purchase_goods_invoice_no)->count_all('purchase_goods');
        } else if ($supplier_id != '') {
            $query = $this->db->where('supplier_id', $supplier_id)->count_all('purchase_goods');
        } else if ($from_date != '' and $to_date != '') {
            $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->count_all('purchase_goods');
        } else if ($supplier_id == '' and $purchase_goods_invoice_no == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('purchase_goods');
        }
       
    }
    

}
