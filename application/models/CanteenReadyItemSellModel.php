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
class CanteenReadyItemSellModel extends CI_Model
{
    public function get_canteen_ready_item_sell($limit, $offset, $canteen_ready_item_sell_invoice_no, $from_date = '', $to_date = '')
    {
        $this->db->from('canteen_ready_item_sell');
        if ($canteen_ready_item_sell_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('canteen_ready_item_sell_invoice_no', $canteen_ready_item_sell_invoice_no);
        } else if ($canteen_ready_item_sell_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        } elseif ($canteen_ready_item_sell_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', date('Y-m-d', strtotime($from_date)));
        } else if ($canteen_ready_item_sell_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', date('Y-m-d', strtotime($to_date)));
        }else if ($canteen_ready_item_sell_invoice_no == '' && $from_date == '' && $to_date == '') {
            //$this->db->where('date', date('Y-m-d', strtotime($to_date)));
           // die('Here');
        }

        // Apply ordering and limits
        $this->db->order_by('canteen_ready_item_sell_id', 'DESC');
        $query = $this->db->get('', $limit, $offset);

        // Return results
        return $query->result();
    }

    public function count_all_canteen_ready_item_sell($canteen_ready_item_sell_invoice_no, $from_date = '', $to_date = '')
    {
        $query = '';
        if ($canteen_ready_item_sell_invoice_no != '' and $from_date == '' and $to_date == '') {
            return $this->db->where('canteen_ready_item_sell_invoice_no', $canteen_ready_item_sell_invoice_no)
                ->count_all('canteen_ready_item_sell');
        } else if ($canteen_ready_item_sell_invoice_no == '' and $from_date != '' and $to_date != '') {
            return $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))
                ->where('date<=', date('Y-m-d', strtotime($to_date)))
                ->count_all('canteen_ready_item_sell');
        } else if ($canteen_ready_item_sell_invoice_no == '' and $from_date != '' and $to_date == '') {
            return $this->db->where('date', date('Y-m-d', strtotime($from_date)))->count_all('canteen_ready_item_sell');
        } else if ($canteen_ready_item_sell_invoice_no == '' and $from_date != '' and $to_date == '') {
            return $this->db->where('date', date('Y-m-d', strtotime($from_date)))->count_all('canteen_ready_item_sell');
        } else if ($canteen_ready_item_sell_invoice_no == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('canteen_ready_item_sell');
        }
    }
}
