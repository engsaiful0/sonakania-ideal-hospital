<?php
class MedicinePurchaseReturnModel extends CI_Model
{
    public function get_medicine_purchase_return_details($limit, $offset, $medicine_purchase_return_invoice_no = '', $supplier_id = '', $from_date = '', $to_date = '')
    {
        if ($medicine_purchase_return_invoice_no != '' && $supplier_id != '' && $from_date != '' && $to_date != '') {
            $this->db
                ->where('medicine_purchase_invoice_no', $medicine_purchase_return_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_return_invoice_no != '' && $supplier_id != '' && $from_date != '' && $to_date == '') {
            $this->db
                ->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $from_date);
        } else if ($medicine_purchase_return_invoice_no != '' && $supplier_id != '' && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $to_date);
        } else if ($medicine_purchase_return_invoice_no != '' && $supplier_id == '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no);
        } else if ($medicine_purchase_return_invoice_no == '' && $supplier_id != '' && $from_date == '' && $to_date == '') {
            $this->db->where('supplier_id', $supplier_id);
        } else if ($medicine_purchase_return_invoice_no == '' && $supplier_id == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_return_invoice_no == '' && $supplier_id == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($medicine_purchase_return_invoice_no == '' && $supplier_id == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }

        $query = $this->db->order_by('medicine_purchase_return_id', 'DESC')->get('medicine_purchase_return', $limit, $offset);
        return $query->result();
    }

    public function count_all_medicine_purchase_return($medicine_purchase_return_invoice_no = '', $supplier_id = '', $from_date = '', $to_date = '')
    {
        if ($medicine_purchase_return_invoice_no != ''  && $supplier_id != '' && $from_date != '' && $to_date != '') {
            $this->db->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_return_invoice_no != '' && $supplier_id != '' && $from_date != '' && $to_date == '') {
            $this->db->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $from_date);
        } else if ($medicine_purchase_return_invoice_no != ''  && $supplier_id != ''  && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $to_date);
        } else if ($medicine_purchase_return_invoice_no != '' && $supplier_id == '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_purchase_return_invoice_no', $medicine_purchase_return_invoice_no);
        } else if ($medicine_purchase_return_invoice_no == '' && $supplier_id != '' && $from_date == '' && $to_date == '') {
            $this->db->where('supplier_id', $supplier_id);
        } else if ($medicine_purchase_return_invoice_no == ''  && $supplier_id == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_return_invoice_no == ''  && $supplier_id == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($medicine_purchase_return_invoice_no == ''  && $supplier_id == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }
        return $this->db->count_all_results('medicine_purchase_return');
    }
}
