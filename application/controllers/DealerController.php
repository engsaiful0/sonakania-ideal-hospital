<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DealerController
 *
 * @author Lenovo
 */
class DealerController extends CI_Controller {

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

    public function add_check() {
        $page_data = array(
            'page_name' => 'dealer/add_check',
            'page_title' => 'Add Check',
            'sidebar' => 'dealer/dealer_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function discount_load_of_this_dealer() {
        $dealer_id = $_POST['dealer_id'];
        $dealer = $this->db->where('dealer_id', $dealer_id)->get('dealer')->row();
        echo $dealer->discount_rate . '_' . $dealer->due_amount . '_' . $dealer->mobile . '_' . $dealer->address;
    }

    public function add_check_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('dealer_check');
        $crud->set_subject('Dealer Check');
        $crud->required_fields('dealer_id', 'bank_id', 'check_number', 'cash_or_account_payable', 'check_date', 'check_given_date', 'amount', 'check_picture');
        $crud->set_relation('bank_id', 'bank', 'bank_name');

        $crud->columns('dealer_id', 'bank_id', 'check_number', 'cash_or_account_payable', 'check_date', 'check_given_date', 'amount', 'check_picture');
        $crud->fields('dealer_id', 'bank_id', 'check_number', 'cash_or_account_payable', 'check_date', 'check_given_date', 'amount', 'check_picture');
        $crud->set_relation('dealer_id', 'dealer', 'dealer_name');
        $crud->display_as('dealer_id', 'Dealer Name');
        $crud->display_as('check_date', 'Expire Date');
        $crud->display_as('bank_id', 'Bank Name');
        $crud->display_as('cash_or_account_payable', 'Cash Or Account Payable');

        $crud->set_field_upload('check_picture', 'assets/dealer_check_picture');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_dealer() {
        $page_data = array(
            'page_name' => 'dealer/add_dealer',
            'page_title' => 'Add Dealer',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function total_amount_update($post_array, $primary_key) {
        $amount = array(
            'due_amount' => $post_array['opening_balance'],
            'total_amount' => $post_array['opening_balance']
        );
        $this->db->where('dealer_id', $primary_key)->update('dealer', $amount);

        $sell_payment = array(
            'current_due' => $post_array['opening_balance'],
            'total_due' => $post_array['opening_balance'],
            'dealer_id' => $primary_key,
            'date' => $post_array['date'],
        );
        $this->db->insert('whole_customer_payment', $sell_payment);
        return true;
    }

    public function add_dealer_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('dealer');
        $crud->set_subject('Dealer');
        $crud->required_fields('deler_name', 'address', 'mobile', 'discount_rate');
        $crud->set_field_upload('picture', 'assets/dealer');
        $crud->columns('dealer_name', 'mobile', 'discount_rate',  'trade_license', 'opening_balance', 'total_amount', 'paid_amount', 'due_amount','date');
        $crud->fields('dealer_name', 'address', 'mobile', 'picture', 'discount_rate', 'nid', 'trade_license', 'opening_balance', 'total_amount', 'paid_amount', 'due_amount','date');
        $crud->set_field_upload('picture', 'assets/dealer');
        $crud->set_field_upload('nid', 'assets/dealer_nid');
        $crud->callback_after_insert(array($this, 'total_amount_update'));
        $crud->callback_after_update(array($this, 'total_amount_update'));
        $crud->display_as('discount_rate', 'Discount Rate (%)');
        $crud->set_field_upload('trade_license', 'assets/dealer_trade_license');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function dealer_report() {
        $page_data = array(
            'page_name' => 'dealer/dealer_report',
            'page_title' => 'Dealer Report',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function dealer_report_details_load() {
        $data = array();

        $data['dealer_id'] = $_POST['dealer_id'];
        $data['from_date'] = date('Y-m-d', strtotime($_POST['from_date']));
        $data['to_date'] = date('Y-m-d', strtotime($_POST['to_date']));
        $this->load->view('dealer/dealer_report_details_load', $data);
    }

}
