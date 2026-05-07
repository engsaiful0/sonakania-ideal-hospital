<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medicine_sale_return_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Get all medicine sale returns with pagination and filters
    public function get_medicine_sale_returns($limit = 10, $offset = 0, $filters = array())
    {
        $this->db->select('msr.*, ms.name as customer_name, ms.medicine_sale_invoice_no, u.user_name');
        $this->db->from('medicine_sale_return msr');
        $this->db->join('medicine_sales ms', 'msr.medicine_sale_id = ms.medicine_sale_id', 'left');
        $this->db->join('user u', 'msr.user_id = u.user_id', 'left');

        // Apply filters
        if (!empty($filters['return_invoice_no'])) {
            $this->db->like('msr.medicine_sale_return_invoice_no', $filters['return_invoice_no']);
        }
        if (!empty($filters['sale_invoice_no'])) {
            $this->db->like('ms.medicine_sale_invoice_no', $filters['sale_invoice_no']);
        }
        if (!empty($filters['customer_name'])) {
            $this->db->like('ms.name', $filters['customer_name']);
        }
        if (!empty($filters['from_date'])) {
            $this->db->where('msr.return_date >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $this->db->where('msr.return_date <=', $filters['to_date']);
        }

        $this->db->order_by('msr.medicine_sale_return_id', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    // Count total records for pagination
    public function count_medicine_sale_returns($filters = array())
    {
        $this->db->from('medicine_sale_return msr');
        $this->db->join('medicine_sales ms', 'msr.medicine_sale_id = ms.medicine_sale_id', 'left');

        // Apply same filters as get_medicine_sale_returns
        if (!empty($filters['return_invoice_no'])) {
            $this->db->like('msr.medicine_sale_return_invoice_no', $filters['return_invoice_no']);
        }
        if (!empty($filters['sale_invoice_no'])) {
            $this->db->like('ms.medicine_sale_invoice_no', $filters['sale_invoice_no']);
        }
        if (!empty($filters['customer_name'])) {
            $this->db->like('ms.name', $filters['customer_name']);
        }
        if (!empty($filters['from_date'])) {
            $this->db->where('msr.return_date >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $this->db->where('msr.return_date <=', $filters['to_date']);
        }

        return $this->db->count_all_results();
    }

    // Get single medicine sale return by ID
    public function get_medicine_sale_return_by_id($return_id)
    {
        $this->db->select('msr.*, ms.name as customer_name, ms.medicine_sale_invoice_no, ms.bill_date, u.user_name');
        $this->db->from('medicine_sale_return msr');
        $this->db->join('medicine_sales ms', 'msr.medicine_sale_id = ms.medicine_sale_id', 'left');
        $this->db->join('user u', 'msr.user_id = u.user_id', 'left');
        $this->db->where('msr.medicine_sale_return_id', $return_id);
        
        return $this->db->get()->row();
    }

    // Get medicine sale return details (items)
    public function get_medicine_sale_return_details($return_id)
    {
        $this->db->select('msrd.*, d.drug_name, d.drug_type_id, dt.drug_type_name');
        $this->db->from('medicine_sale_return_details msrd');
        $this->db->join('drug d', 'msrd.drug_id = d.drug_id', 'left');
        $this->db->join('drug_type dt', 'd.drug_type_id = dt.drug_type_id', 'left');
        $this->db->where('msrd.medicine_sale_return_id', $return_id);
        
        return $this->db->get()->result();
    }

    // Get medicine sale by invoice number for return
    public function get_medicine_sale_by_invoice($invoice_no)
    {
        $this->db->select('ms.*, u.user_name');
        $this->db->from('medicine_sales ms');
        $this->db->join('user u', 'ms.user_id = u.user_id', 'left');
        $this->db->where('ms.medicine_sale_invoice_no', $invoice_no);
        
        return $this->db->get()->row();
    }

    // Get medicine sale details by sale ID
    public function get_medicine_sale_details($sale_id)
    {
        $this->db->select('msd.*, d.drug_name, d.drug_type_id, dt.drug_type_name');
        $this->db->from('medicine_sales_details msd');
        $this->db->join('drug d', 'msd.drug_id = d.drug_id', 'left');
        $this->db->join('drug_type dt', 'd.drug_type_id = dt.drug_type_id', 'left');
        $this->db->where('msd.medicine_sale_id', $sale_id);
        
        return $this->db->get()->result();
    }

    // Create new medicine sale return
    public function create_medicine_sale_return($data)
    {
        $this->db->trans_start();

        // Insert main return record
        $return_data = array(
            'medicine_sale_id' => $data['medicine_sale_id'],
            'medicine_sale_return_invoice_no' => $data['medicine_sale_return_invoice_no'],
            'return_date' => $data['return_date'],
            'total_amount' => $data['total_amount'],
            'discount_amount' => $data['discount_amount'],
            'net_amount' => $data['net_amount'],
            'remarks' => $data['remarks'],
            'user_id' => $data['user_id'],
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('medicine_sale_return', $return_data);
        $return_id = $this->db->insert_id();

        // Insert return details
        if (!empty($data['return_items'])) {
            $details_data = array();
            foreach ($data['return_items'] as $item) {
                $details_data[] = array(
                    'medicine_sale_return_id' => $return_id,
                    'medicine_sale_id' => $data['medicine_sale_id'],
                    'drug_id' => $item['drug_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'return_quantity' => $item['return_quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'],
                    'discount_amount' => $item['discount_amount'],
                    'total_amount' => $item['total_amount'],
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            $this->db->insert_batch('medicine_sale_return_details', $details_data);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $return_id : false;
    }

    // Update medicine sale return
    public function update_medicine_sale_return($return_id, $data)
    {
        $this->db->trans_start();

        // Update main return record
        $return_data = array(
            'medicine_sale_id' => $data['medicine_sale_id'],
            'medicine_sale_return_invoice_no' => $data['medicine_sale_return_invoice_no'],
            'return_date' => $data['return_date'],
            'total_amount' => $data['total_amount'],
            'discount_amount' => $data['discount_amount'],
            'net_amount' => $data['net_amount'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('medicine_sale_return_id', $return_id);
        $this->db->update('medicine_sale_return', $return_data);

        // Delete existing details
        $this->db->where('medicine_sale_return_id', $return_id);
        $this->db->delete('medicine_sale_return_details');

        // Insert updated return details
        if (!empty($data['return_items'])) {
            $details_data = array();
            foreach ($data['return_items'] as $item) {
                $details_data[] = array(
                    'medicine_sale_return_id' => $return_id,
                    'medicine_sale_id' => $data['medicine_sale_id'],
                    'drug_id' => $item['drug_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'return_quantity' => $item['return_quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'],
                    'discount_amount' => $item['discount_amount'],
                    'total_amount' => $item['total_amount'],
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            $this->db->insert_batch('medicine_sale_return_details', $details_data);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Delete medicine sale return
    public function delete_medicine_sale_return($return_id)
    {
        $this->db->trans_start();

        // Delete details first
        $this->db->where('medicine_sale_return_id', $return_id);
        $this->db->delete('medicine_sale_return_details');

        // Delete main record
        $this->db->where('medicine_sale_return_id', $return_id);
        $this->db->delete('medicine_sale_return');

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Get next invoice number
    public function get_next_invoice_number()
    {
        $this->db->select('medicine_sale_return_invoice_no');
        $this->db->from('medicine_sale_return');
        $this->db->order_by('medicine_sale_return_id', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_number = intval(substr($result->medicine_sale_return_invoice_no, 3));
            return 'MSR' . str_pad($last_number + 1, 6, '0', STR_PAD_LEFT);
        } else {
            return 'MSR000001';
        }
    }

    // Search medicine sales by invoice number
    public function search_medicine_sales($invoice_no)
    {
        $this->db->select('ms.*, u.user_name');
        $this->db->from('medicine_sales ms');
        $this->db->join('user u', 'ms.user_id = u.user_id', 'left');
        $this->db->like('ms.medicine_sale_invoice_no', $invoice_no);
        $this->db->order_by('ms.medicine_sale_id', 'DESC');
        $this->db->limit(10);
        
        return $this->db->get()->result();
    }

    // Get return summary for dashboard
    public function get_return_summary($from_date = null, $to_date = null)
    {
        $this->db->select('COUNT(*) as total_returns, SUM(net_amount) as total_amount');
        $this->db->from('medicine_sale_return');
        
        if ($from_date) {
            $this->db->where('return_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('return_date <=', $to_date);
        }
        
        return $this->db->get()->row();
    }
}
