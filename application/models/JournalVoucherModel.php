<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JournalVoucherModel
 *
 * @author sohag
 */
class JournalVoucherModel extends CI_Model
{
    public function get_journal_vouchers($limit, $offset, $journal_voucher_no = '', $debit_account_id = '', $credit_account_id = '', $from_date = '', $to_date = '', $journal_account_id = '', $type = '', $bank_id = '', $check_number = '')
    {
        $this->db->from('journal_vouchers');

        // Apply filters if values are provided
        if (!empty($journal_voucher_no)) {
            $this->db->where('journal_voucher_no', $journal_voucher_no);
        }

        if (!empty($journal_account_id)) {
            $this->db->where('journal_account_id', $journal_account_id);
        }


        if (!empty($debit_account_id)) {
            $this->db->where('debit_account_id', $debit_account_id);
        }

        if (!empty($credit_account_id)) {
            $this->db->where('credit_account_id', $credit_account_id);
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        }

        $this->db->order_by('journal_voucher_id', 'DESC');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result();
    }


    public function count_all_journal_vouchers($journal_voucher_no = '', $journal_account_id = '', $debit_account_id = '', $credit_account_id = '', $from_date = '', $to_date = '', $type = '', $bank_id = '', $check_number = '')
    {
        $this->db->from('journal_vouchers');

        // Apply filters
        if (!empty($journal_voucher_no)) {
            $this->db->where('journal_voucher_no', $journal_voucher_no);
        }

        if (!empty($journal_account_id)) {
            $this->db->where('journal_account_id', $journal_account_id);
        }

        if (!empty($debit_account_id)) {
            $this->db->where('debit_account_id', $debit_account_id);
        }

        if (!empty($credit_account_id)) {
            $this->db->where('credit_account_id', $credit_account_id);
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('date >=', date('Y-m-d', strtotime($from_date)));
            $this->db->where('date <=', date('Y-m-d', strtotime($to_date)));
        }

        return $this->db->count_all_results();
    }
}
