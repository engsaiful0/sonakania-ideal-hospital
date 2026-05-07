<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportCanteenController
 *
 * @author saiful
 */
class ReportCanteenController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
        $this->load->library('pagination');
    }

    public function ready_item_sell_report()
    {
        $page_data = array(
            'page_name' => 'canteen/report/ready_item_sell_report',
            'page_title' => 'Canteen Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function ready_item_sell_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('canteen/report/ready_item_sell_report_details', $data);
    }
    
    public function canteen_purchase_report()
    {
        $page_data = array(
            'page_name' => 'canteen/report/canteen_purchase_report',
            'page_title' => 'Canteen Purchase Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function canteen_purchase_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('canteen/report/canteen_purchase_report_details', $data);
    }
    public function goods_usage_report()
    {
        $page_data = array(
            'page_name' => 'canteen/report/goods_usage_report',
            'page_title' => 'Canteen Goods Usage Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function goods_usage_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('canteen/report/goods_usage_report_details', $data);
    }
    
    public function canteen_goods_stock_report()
    {
        $page_data = array(
            'page_name' => 'canteen/report/goods_stock_report',
            'page_title' => 'Canteen Goods Stock Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ready_item_stock_report()
    {
        $page_data = array(
            'page_name' => 'canteen/report/ready_item_stock_report',
            'page_title' => 'Canteen Ready Item Stock Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function ready_item_stock_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('canteen/report/ready_item_stock_report_details', $data);
    }
   
}
