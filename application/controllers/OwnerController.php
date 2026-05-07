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
class OwnerController extends CI_Controller {

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
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_owner() {
        $page_data = array(
            'page_name' => 'owner/add_owner',
            'page_title' => 'Add Owner',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_owner_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('owner');
        $crud->set_subject('Owner');
        $crud->required_fields('owner_name');

        $crud->fields('owner_name');
        $crud->columns('owner_name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_owner_payment() {
        $page_data = array(
            'page_name' => 'owner/add_owner_payment',
            'page_title' => 'Add Owner',
            'sidebar' => 'expense/expense_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_owner_payment_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('owner_payment');
        $crud->set_subject('owner_payment');
        $crud->required_fields('owner_name');

        $crud->fields('owner_id', 'bank_or_cash', 'bank_id', 'amount', 'date');
        $crud->columns('owner_id', 'bank_or_cash', 'bank_id', 'amount', 'date');
        $crud->set_relation('owner_id', 'owner', 'owner_name');
        $crud->field_type('bank_or_cash', 'dropdown',array('Case'=>'Cash','Bank'=>'Bank'));
        $crud->set_relation('bank_id', 'bank', 'bank_name');
        $crud->display_as('owner_id','Owner Name');
        $crud->display_as('bank_id','Bank Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

}
