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
class ReportDashboardModel extends CI_Model
{
    public function getPharmacyTodaySell()
    {
        $this->db->select_sum('nettotal');
        $this->db->from('medicine_sales');
        $this->db->where('DATE(date)', date('Y-m-d')); // Filter by today's date
        $query = $this->db->get();
        return $query->row()->nettotal ?? 0; // Return 0 if no income found
    }
}
