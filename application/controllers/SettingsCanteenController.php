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
class SettingsCanteenController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }

    function index()
    {
        $page_data = array(
            'page_name' => 'settings_canteen/settings_dashbaord',
            'page_title' => 'Purchase',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }
    public function raw_goods_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('canteen_raw_goods');
        $crud->set_subject('Raw Goods');
        $crud->set_relation('unit_id', 'units', 'name');
        $crud->required_fields('name');
        $crud->required_fields('unit_id');
        $crud->display_as('unit_id','Unit');
        $crud->columns('name','unit_id');
        $crud->fields('name','unit_id');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function raw_goods()
    {

        $page_data = array(
            'page_name' => 'settings_canteen/raw_goods',
            'page_title' => 'Raw Goods',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ready_item_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('canteen_ready_items');
        $crud->set_subject('Ready Item');
        $crud->set_relation('unit_id', 'units', 'name');
        $crud->required_fields('name','price','unit_id');
        $crud->display_as('unit_id','Unit');
        $crud->columns('name','unit_id','price');
        $crud->fields('name','unit_id','price');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function ready_item()
    {
        $page_data = array(
            'page_name' => 'settings_canteen/ready_item',
            'page_title' => 'Ready Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function raw_goods_supplier_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('canteen_goods_supplier');
        $crud->set_subject('Goods Supplier');
        $crud->required_fields('name');
        $crud->columns('name','address','mobile','opening_balance');
        $crud->fields('name','address','mobile','opening_balance');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function raw_goods_supplier()
    {
        $page_data = array(
            'page_name' => 'settings_canteen/raw_goods_supplier',
            'page_title' => 'Ready Item',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    
}
