<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OwnerController
 *
 * @author Lenovo
 */
class CanteenInventoryController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
             redirect('LoginController');
        }
    }

    public function index() {
        $page_data = array(
            'page_name' => 'marketting/marketting_dashboard',
            'page_title' => 'Marketting',
            'sidebar' => 'marketting/marketting_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_inventory() {
        $page_data = array(
            'page_name' => 'canteen_inventory/add_inventory',
            'page_title' => 'Add Sell',
            'sidebar' => 'canteen/canteen_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function canteen_ready_item_inventory_form()
    {
        
    }

}
