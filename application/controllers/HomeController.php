<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HomeController
 *
 * @author Lenovo
 */
class HomeController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('Grocery_crud');
    }

    public function index() {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function home() {
        $this->load->view('home');
    }

    public function company_profile() {
        $page_data = array(
            'page_name' => 'home/company_profile',
            'page_title' => 'Company Profile',
            'sidebar' => 'home/home_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function company_profile_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('company');
        $crud->set_subject('Company');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin'):
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('company_name', 'address', 'vat');
        $crud->columns('company_name', 'address', 'logo', 'web', 'email', 'mobile', 'vat');
        $crud->fields('company_name', 'address', 'logo', 'web', 'email', 'mobile', 'vat');
        $crud->set_field_upload('logo', 'assets/images');
        $crud->unset_add();
        $crud->unset_delete();
        $crud->display_as('vat', 'Vat(%)');
        $output = $crud->render();

        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function special_income() {
        $page_data = array(
            'page_name' => 'home/special_income',
            'page_title' => 'Special Income',
            'sidebar' => 'home/home_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function special_income_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('special_income');
        $crud->set_subject('Special Income');

        $crud->required_fields('income_type', 'amount', 'date');
        $crud->set_relation('bank_id', 'bank', '{bank_name}-{account_number}');
        $crud->columns('income_type', 'bank_id', 'description', 'amount', 'date');
        $crud->fields('income_type', 'bank_id', 'description', 'amount', 'date');
        $crud->field_type('income_type', 'dropdown', array('cash' => 'Cash', 'bank' => 'Bank'));
        $crud->display_as('bank_id', 'Bank Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function user() {
        $page_data = array(
            'page_name' => 'home/user',
            'page_title' => 'Add User',
            'sidebar' => 'common/left_menu'
        );
        $this->load->view('content', $page_data);
    }

    public function user_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('user');
        $crud->set_subject('User');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin'):
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('type', 'user_name', 'password');
        $crud->columns('type', 'user_name', 'password');
        $crud->fields('type', 'user_name', 'password');
        $crud->field_type('type', 'dropdown', array('admin' => 'Admin', 'lab_user' => 'Lab User', 'cash_user' => 'Receiption User', 'pharmacy_user' => 'Pharmacy User'));
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function bank_withdraw() {
        $page_data = array(
            'page_name' => 'home/bank_withdraw',
            'page_title' => 'Bank Withdraw',
            'sidebar' => 'home/home_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_withdraw_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('bank_withdraw');
        $crud->set_subject('Bank Withdraw');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin'):
            $crud->unset_edit();
            $crud->unset_delete();
        endif;

        $crud->required_fields('bank_id', 'amount', 'date');
        $crud->set_relation('bank_id', 'bank', '{bank_name}-{account_number}');
        $crud->columns('bank_id', 'amount', 'date', 'purpose');
        $crud->fields('bank_id', 'amount', 'date', 'purpose');

        //  $crud->callback_after_insert(array($this, 'withdraw_balance_add_after_insert'));
        // $crud->callback_before_update(array($this, 'withdraw_balance_update_before_update'));
        // $crud->callback_before_delete(array($this, 'withdraw_balance_update_before_delete'));

        $crud->display_as('bank_id', 'Bank Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function withdraw_balance_add_after_insert($post_array, $primary_key) {
        $bank_id = $post_array['bank_id'];
        $current_balance = $post_array['amount'];
        $bank = $this->db->where('bank_id', $bank_id)->get('bank')->row();
        $next_balacne['balance'] = $bank->balance - $current_balance;
        $this->db->where('bank_id', $bank_id)->update('bank', $next_balacne);
        return TRUE;
    }

    public function withdraw_balance_update_before_update($post_array, $primary_key) {
        $bank_previous = $this->db->where('bank_deposit_id', $primary_key)->get('bank_deposit')->row();
        //  echo '<pre>';
        // print_r($post_array);
        //  die;
        $previous_amount = $bank_previous->amount;
        $bank_id = $post_array['bank_id'];
        $current_balance = $post_array['amount'];
        $bank = $this->db->where('bank_id', $bank_id)->get('bank')->row();
        $next_balacne['balance'] = $bank->balance + $previous_amount - $current_balance;
        $this->db->where('bank_id', $bank_id)->update('bank', $next_balacne);
        return TRUE;
    }

    public function withdraw_balance_update_before_delete($primary_key) {
        $bank_previous = $this->db->where('bank_deposit_id', $primary_key)->get('bank_deposit')->row();
        $previous_amount = $bank_previous->amount;
        $bank_id = $bank_previous->bank_id;
        $bank = $this->db->where('bank_id', $bank_id)->get('bank')->row();
        $next_balacne['balance'] = $bank->balance + $previous_amount;
        $this->db->where('bank_id', $bank_id)->update('bank', $next_balacne);
        return TRUE;
    }

    public function bank_deposit() {
        $page_data = array(
            'page_name' => 'home/bank_deposit',
            'page_title' => 'Bank Deposit',
            'sidebar' => 'home/home_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_deposit_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('bank_deposit');
        $crud->set_subject('Bank Deposit');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin'):
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('bank_id', 'amount', 'date');
        $crud->set_relation('bank_id', 'bank', '{bank_name}-{account_number}');
        $crud->columns('bank_id', 'amount', 'date', 'purpose');
        $crud->fields('bank_id', 'amount', 'date', 'purpose');

        // $crud->callback_after_insert(array($this, 'balance_add_after_insert'));
        //$crud->callback_before_update(array($this, 'balance_update_before_update'));
        // $crud->callback_before_delete(array($this, 'balance_update_after_delete'));

        $crud->display_as('bank_id', 'Bank Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function balance_add_after_insert($post_array, $primary_key) {
        $bank_id = $post_array['bank_id'];
        $current_balance = $post_array['amount'];
        $bank = $this->db->where('bank_id', $bank_id)->get('bank')->row();
        $next_balacne['balance'] = $bank->balance + $current_balance;
        $this->db->where('bank_id', $bank_id)->update('bank', $next_balacne);
        return TRUE;
    }

    public function balance_update_after_delete($primary_key) {
        $bank_previous = $this->db->where('bank_deposit_id', $primary_key)->get('bank_deposit')->row();
        $previous_amount = $bank_previous->amount;
        $bank_id = $bank_previous->bank_id;
        $bank = $this->db->where('bank_id', $bank_id)->get('bank')->row();
        $next_balacne['balance'] = $bank->balance - $previous_amount;
        $this->db->where('bank_id', $bank_id)->update('bank', $next_balacne);
        return TRUE;
    }

    public function balance_update_before_update($post_array, $primary_key) {
        $bank_previous = $this->db->where('bank_deposit_id', $primary_key)->get('bank_deposit')->row();
        //  echo '<pre>';
        // print_r($post_array);
        //  die;
        $previous_amount = $bank_previous->amount;
        $bank_id = $post_array['bank_id'];
        $current_balance = $post_array['amount'];
        $bank = $this->db->where('bank_id', $bank_id)->get('bank')->row();
        $next_balacne['balance'] = $bank->balance - $previous_amount + $current_balance;
        $this->db->where('bank_id', $bank_id)->update('bank', $next_balacne);
        return TRUE;
    }

    public function add_bank() {
        $page_data = array(
            'page_name' => 'home/add_bank',
            'page_title' => 'Add Bank',
            'sidebar' => 'home/home_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_bank_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('bank');
        $crud->set_subject('Bank');

        $crud->required_fields('bank_name');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin'):
            $crud->unset_edit();
            $crud->unset_delete();
        endif;

        $crud->columns('bank_name', 'account_number', 'account_name', 'details', 'balance');
        $crud->fields('bank_name', 'account_number', 'account_name', 'details', 'balance');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function backup() {
        // Load the database utility class
        $this->load->dbutil();
    
        // Set preferences for backup
        $prefs = array(
            'format'      => 'txt',             // 'gzip', 'zip', 'txt'
            'filename'    => 'hospital.sql',    // Used only with 'zip'
        );
    
        // Create backup
        $backup = $this->dbutil->backup($prefs);
    
        // Load the file helper and write the file to server (optional)
        $this->load->helper('file');
        write_file('downloads/hospital.sql', $backup);
    
        // Load the download helper and send the file to the browser
        $this->load->helper('download');
        force_download(date('d-m-Y-h-i-s') . '_hospital.sql', $backup);
    }
    

}
