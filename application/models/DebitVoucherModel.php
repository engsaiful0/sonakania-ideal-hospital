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
class DebitVoucherModel extends CI_Model
{
    public function get_debit_vouchers($limit, $offset, $debit_voucher_no, $debit_account_id, $type, $bank_id, $check_number, $from_date, $to_date)
    {
        $query = '';
        if ($debit_voucher_no != '') {
            $query = $this->db->where('debit_voucher_no', $debit_voucher_no)->order_by('debit_voucher_id','DESC')->get('debit_voucher', $limit, $offset);
        } else if ($debit_account_id != '') {
            $query = $this->db->where('debit_account_id', $debit_account_id)->order_by('debit_voucher_id','DESC')->get('debit_voucher', $limit, $offset);
        } else if ($type != '') {
            $query = $this->db->where('type', $type)->order_by('debit_voucher_id','DESC')->get('debit_voucher', $limit, $offset);
        } else if ($bank_id != '') {
            $query = $this->db->where('bank_id', $bank_id)->order_by('debit_voucher_id','DESC')->get('debit_voucher', $limit, $offset);
        } else if ($check_number != '') {
            $query = $this->db->where('check_number', $check_number)->order_by('debit_voucher_id','DESC')->get('debit_voucher', $limit, $offset);
        } else if ($from_date != '' and $to_date != '') {
            $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->order_by('debit_voucher_id','DESC')->get('issue', $limit, $offset);
        } else if ($debit_voucher_no == '' and $debit_account_id == '' and $type == '' and $bank_id == '' and $check_number == '' and $from_date == '' and $to_date == '') {
            $query = $this->db->order_by('debit_voucher_id','DESC')->get('debit_voucher', $limit, $offset);
        }
        return $query->result();
    }

    public function count_all_debit_vouchers($debit_voucher_no, $debit_account_id, $type, $bank_id, $check_number, $from_date, $to_date)
    {
        $query = '';
        if ($debit_voucher_no != '') {
            $query = $this->db->where('debit_voucher_no', $debit_voucher_no)->count_all('debit_voucher');
        } else if ($debit_account_id != '') {
            $query = $this->db->where('debit_account_id', $debit_account_id)->count_all('debit_voucher');
        } else if ($type != '') {
            $query = $this->db->where('type', $type)->count_all('debit_voucher');
        } else if ($bank_id != '') {
            $query = $this->db->where('bank_id', $bank_id)->count_all('debit_voucher');
        } else if ($check_number != '') {
            $query = $this->db->where('check_number', $check_number)->count_all('debit_voucher');
        } else if ($from_date != '' and $to_date != '') {
            $query = $this->db->where('date>=', date('Y-m-d', strtotime($from_date)))->where('date<=', date('Y-m-d', strtotime($to_date)))->count_all('issue');
        } else if ($debit_voucher_no == '' and $debit_account_id == '' and $type == '' and $bank_id == '' and $check_number == '' and $from_date == '' and $to_date == '') {
            return $this->db->count_all('debit_voucher');
        }
    }
}
