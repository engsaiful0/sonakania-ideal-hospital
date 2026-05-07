<?php
class MedicineSaleReturnModel extends CI_Model
{
    public function get_medicine_sell_return_details($limit, $offset, $medicine_sale_return_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($medicine_sale_return_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_sale_return_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no)
                ->where('date', $from_date);
        } else if ($medicine_sale_return_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no)
                ->where('date', $to_date);
        } else if ($medicine_sale_return_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no);
        } else if ($medicine_sale_return_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_sale_return_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($medicine_sale_return_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }
        $query = $this->db->order_by('medicine_sale_return_id', 'DESC')->get('medicine_sale_return', $limit, $offset);
        return $query->result();
    }

    public function count_all_medicine_sale_return($medicine_sale_return_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($medicine_sale_return_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_sale_return_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no)
                ->where('date', $from_date);
        } else if ($medicine_sale_return_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no)
                ->where('date', $to_date);
        } else if ($medicine_sale_return_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_sale_return_invoice_no', $medicine_sale_return_invoice_no);
        } else if ($medicine_sale_return_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_sale_return_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($medicine_sale_return_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }

        return $this->db->count_all_results('medicine_sale_return');
    }
}
