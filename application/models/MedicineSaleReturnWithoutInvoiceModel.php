<?php
class MedicineSaleReturnWithoutInvoiceModel extends CI_Model
{
    public function get_medicine_sale_return_details($limit, $offset, $return_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($return_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('return_invoice_no', $return_invoice_no)
                ->where('return_date >=', $from_date)
                ->where('return_date <=', $to_date);
        } else if ($return_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('return_invoice_no', $return_invoice_no)
                ->where('return_date', $from_date);
        } else if ($return_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('return_invoice_no', $return_invoice_no)
                ->where('return_date', $to_date);
        } else if ($return_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('return_invoice_no', $return_invoice_no);
        } else if ($return_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('return_date >=', $from_date)
                ->where('return_date <=', $to_date);
        } else if ($return_invoice_no == '' && $from_date != '' && $to_date == '') {
           //die($from_date);
            $this->db->where('return_date', $from_date);
        } else if ($return_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('return_date', $to_date);
        }

        $query = $this->db->order_by('medicine_sale_return_id_without_invoice', 'DESC')->get('medicine_sale_returns_without_invoice', $limit, $offset);
        return $query->result();
    }

    public function count_all_medicine_sale_return($return_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($return_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('return_invoice_no', $return_invoice_no)
                ->where('return_date >=', $from_date)
                ->where('return_date <=', $to_date);
        } else if ($return_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('return_invoice_no', $return_invoice_no)
                ->where('return_date', $from_date);
        } else if ($return_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('return_invoice_no', $return_invoice_no)
                ->where('return_date', $to_date);
        } else if ($return_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('return_invoice_no', $return_invoice_no);
        } else if ($return_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('return_date >=', $from_date)
                ->where('return_date <=', $to_date);
        } else if ($return_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('return_date', $to_date);
        } else if ($return_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('return_date >=', $from_date)
                ->where('return_date', $from_date);
        }

        return $this->db->count_all_results('medicine_sale_returns_without_invoice');
    }
}
