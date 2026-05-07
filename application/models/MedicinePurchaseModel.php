<?php
class MedicinePurchaseModel extends CI_Model
{
    public function get_medicine_purchase_details($limit, $offset, $medicine_purchase_invoice_no = '', $supplier_id = '', $from_date = '', $to_date = '')
    {
        if ($medicine_purchase_invoice_no != '' && $supplier_id != '' && $from_date != '' && $to_date != '') {
            $this->db
                ->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_invoice_no != '' && $supplier_id != '' && $from_date != '' && $to_date == '') {
            $this->db
                ->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $from_date);
        } else if ($medicine_purchase_invoice_no != '' && $supplier_id != '' && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $to_date);
        } else if ($medicine_purchase_invoice_no != '' && $supplier_id == '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no);
        } else if ($medicine_purchase_invoice_no == '' && $supplier_id != '' && $from_date == '' && $to_date == '') {
            $this->db->where('supplier_id', $supplier_id);
        } else if ($medicine_purchase_invoice_no == '' && $supplier_id == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_invoice_no == '' && $supplier_id == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($medicine_purchase_invoice_no == '' && $supplier_id == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }

        $query = $this->db->order_by('medicine_purchase_id', 'DESC')->get('medicine_purchase', $limit, $offset);
        return $query->result();
    }

    public function count_all_medicine_purchase($medicine_purchase_invoice_no = '', $supplier_id = '', $from_date = '', $to_date = '')
    {
        if ($medicine_purchase_invoice_no != ''  && $supplier_id != '' && $from_date != '' && $to_date != '') {
            $this->db->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_invoice_no != '' && $supplier_id != '' && $from_date != '' && $to_date == '') {
            $this->db->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $from_date);
        } else if ($medicine_purchase_invoice_no != ''  && $supplier_id != ''  && $from_date == '' && $to_date != '') {
            $this->db->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no)
                ->where('supplier_id', $supplier_id)
                ->where('date', $to_date);
        } else if ($medicine_purchase_invoice_no != '' && $supplier_id == '' && $from_date == '' && $to_date == '') {
            $this->db->where('medicine_purchase_invoice_no', $medicine_purchase_invoice_no);
        } else if ($medicine_purchase_invoice_no == '' && $supplier_id != '' && $from_date == '' && $to_date == '') {
            $this->db->where('supplier_id', $supplier_id);
        } else if ($medicine_purchase_invoice_no == ''  && $supplier_id == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($medicine_purchase_invoice_no == ''  && $supplier_id == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($medicine_purchase_invoice_no == ''  && $supplier_id == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }

        return $this->db->count_all_results('medicine_purchase');
    }
}
