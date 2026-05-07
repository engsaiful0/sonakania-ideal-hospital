<?php
class ExpiredMedicineModel extends CI_Model
{
    public function get_expired_medicine_details($limit, $offset, $expired_medicine_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($expired_medicine_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db
                ->where('expired_medicine_invoice_no', $expired_medicine_invoice_no)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($expired_medicine_invoice_no != ''  && $from_date != '' && $to_date == '') {
            $this->db
                ->where('expired_medicine_invoice_no', $expired_medicine_invoice_no)
                ->where('date', $from_date);
        } else if ($expired_medicine_invoice_no != '' && $from_date == '' && $to_date != '') {
            $this->db->where('expired_medicine_invoice_no', $expired_medicine_invoice_no)
                ->where('date', $to_date);
        } else if ($expired_medicine_invoice_no != ''  && $from_date == '' && $to_date == '') {
            $this->db->where('expired_medicine_invoice_no', $expired_medicine_invoice_no);
        } else if ($expired_medicine_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($expired_medicine_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($expired_medicine_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }

        $query = $this->db->order_by('expired_medicine_id', 'DESC')->get('expired_medicines', $limit, $offset);
        return $query->result();
    }

    public function count_all_expired_medicine($expired_medicine_invoice_no = '', $from_date = '', $to_date = '')
    {
        if ($expired_medicine_invoice_no != '' && $from_date != '' && $to_date != '') {
            $this->db->where('expired_medicine_invoice_no', $expired_medicine_invoice_no)
                ->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($expired_medicine_invoice_no != '' && $from_date != '' && $to_date == '') {
            $this->db->where('expired_medicine_invoice_no', $expired_medicine_invoice_no)
                ->where('date', $from_date);
        } else if ($expired_medicine_invoice_no != ''  && $from_date == '' && $to_date != '') {
            $this->db->where('expired_medicine_invoice_no', $expired_medicine_invoice_no)
                ->where('date', $to_date);
        } else if ($expired_medicine_invoice_no != ''  && $from_date == '' && $to_date == '') {
            $this->db->where('expired_medicine_invoice_no', $expired_medicine_invoice_no);
        } else if ($expired_medicine_invoice_no == '' && $from_date != '' && $to_date != '') {
            $this->db->where('date >=', $from_date)
                ->where('date <=', $to_date);
        } else if ($expired_medicine_invoice_no == '' && $from_date != '' && $to_date == '') {
            $this->db->where('date', $from_date);
        } else if ($expired_medicine_invoice_no == '' && $from_date == '' && $to_date != '') {
            $this->db->where('date', $to_date);
        }
        return $this->db->count_all_results('expired_medicines');
    }
}
