<?php
class MedicineSaleModel extends CI_Model
{
    public function get_medicine_sells_details($limit, $offset, $medicine_sale_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($medicine_sale_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no)
                ->where('bill_date >=', $from_date)
                ->where('bill_date <=', $to_date);
        } else if ($medicine_sale_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no)
                ->where('bill_date', $from_date);
        } else if ($medicine_sale_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no)
                ->where('bill_date', $to_date);
        } else if ($medicine_sale_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no);
        } else if ($medicine_sale_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('bill_date >=', $from_date)
                ->where('bill_date <=', $to_date);
        } else if ($medicine_sale_invoice_no == '' && $from_date != '' && $to_date == '') {
           //die($from_date);
            $this->db->where('bill_date', $from_date);
        } else if ($medicine_sale_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('bill_date', $to_date);
        }

        $query = $this->db->order_by('medicine_sale_id', 'DESC')->get('medicine_sales', $limit, $offset);
        return $query->result();
    }

    public function count_all_medicine_sales($medicine_sale_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($medicine_sale_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no)
                ->where('bill_date >=', $from_date)
                ->where('bill_date <=', $to_date);
        } else if ($medicine_sale_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no)
                ->where('bill_date', $from_date);
        } else if ($medicine_sale_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no)
                ->where('bill_date', $to_date);
        } else if ($medicine_sale_invoice_no != '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_sale_invoice_no', $medicine_sale_invoice_no);
        } else if ($medicine_sale_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('bill_date >=', $from_date)
                ->where('bill_date <=', $to_date);
        } else if ($medicine_sale_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('bill_date', $to_date);
        } else if ($medicine_sale_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('bill_date >=', $from_date)
                ->where('bill_date', $from_date);
        }

        return $this->db->count_all_results('medicine_sales');
    }
}
