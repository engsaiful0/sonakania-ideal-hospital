<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeController
 *
 * @author Lenovo
 */
class ReportDashboardController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    public function getPharmacyTodaySell()
    {
        $this->load->database();
        $todayTotalSell = $this->ReportDashboardModel->getPharmacyTodaySell();
        echo json_encode(['todayTotalSell' => $todayTotalSell]);
    }
}
