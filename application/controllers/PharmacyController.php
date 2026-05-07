<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BankController
 *
 * @author saiful
 */
class PharmacyController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
              redirect('LoginController');
        }
        $this->load->library('pagination');
    }

    public function index() {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function pharmacy() {
        // die;
        $page_data = array(
            'page_name' => 'pharmacy/pharmacy',
            'page_title' => 'Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function daily_medicin_sell_report() {
       
        $page_data = array(
            'page_name' => 'pharmacy/daily_medicin_sell_report',
            'page_title' => 'Daily Medicin Sell Report',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function daily_medicin_sell_report_details_load($ids) {
        $ids = explode('_', $ids);
        $data['from_date'] = $ids[0];
        $data['to_date'] = $ids[1];
        $this->load->view('pharmacy/daily_medicin_sell_report_details_load', $data);
    }

    public function drug_type() {
        $page_data = array(
            'page_name' => 'pharmacy/add_drug_type',
            'page_title' => 'Supplier',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function drug_type_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('drug_type');
      
        $crud->set_subject('Medicin Type');
        $crud->required_fields('type_name', 'short_name');
        $crud->columns('type_name', 'short_name');
        $crud->fields('type_name', 'short_name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function supplier() {
        $page_data = array(
            'page_name' => 'pharmacy/add_supplier',
            'page_title' => 'Supplier',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function supplier_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('supplier');
        
        $crud->set_subject('Medicin Supplier');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function medicin_list() {
        $page_data = array(
            'page_name' => 'pharmacy/medicin_list',
            'page_title' => 'Medicin List',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicin_list_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('drug');
       
        $crud->set_subject('Medicin List');
        $crud->required_fields('drug_name', 'type', 'manufacturer', 'stock', 'mrp');
        $crud->set_relation('manufacturer', 'manufacturer', 'name');
        $crud->set_relation('type', 'drug_type', 'type_name');
        $crud->columns('drug_name', 'type', 'manufacturer', 'stock', 'mrp');
        $crud->fields('drug_name', 'type', 'manufacturer', 'stock', 'mrp');

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function doctor_reference_payment_report() {
        $page_data = array(
            'page_name' => 'doctor/doctor_reference_payment_report',
            'page_title' => 'Doctor Reference Payment Report',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_payment_edit($doctor_commission_payment_id) {
        $data['doctor_commission_payment_id'] = $doctor_commission_payment_id;
        $this->load->view('doctor/doctor_payment_edit', $data);
    }

    public function doctors_payment() {
        $page_data = array(
            'page_name' => 'doctor/doctors_payment',
            'page_title' => 'Add Doctor Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_payment_delete($doctor_commission_payment_id) {
        $data = array('is_deleted' => '1');
        $this->db->where('doctor_commission_payment_id', $doctor_commission_payment_id)->update('doctor_commission_payment', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);

        $doctor_id = '';
        $config['base_url'] = site_url('DoctorController/view_doctors_payment');
        $config['total_rows'] = $this->db->count_all('doctor_commission_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->doctors_payment_details($config["per_page"], $data['page'], $doctor_id);

        $data['pagination'] = $this->pagination->create_links();



        $this->load->view('doctor/view_doctors_payment', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctors_payment',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_doctors_payment() {
        $doctor_id = $this->input->post('doctor_id');
        $config['base_url'] = site_url('DoctorController/view_doctors_payment');
        $config['total_rows'] = $this->db->count_all('doctor_commission_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->doctors_payment_details($config["per_page"], $data['page'], $doctor_id);

        $data['pagination'] = $this->pagination->create_links();



        $this->load->view('doctor/view_doctors_payment', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctors_payment',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function due_commission_history_load() {
        error_reporting(0);
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db->where('doctor_id', $doctor_id)
                ->get('doctor')
                ->row();
        $commission_rate = explode('%', $doctor->commission);
        $commission_rate = $commission_rate[0];
        $doctor_commission_payment = $this->db->select_sum('paid_amount', 'paid_amount')
                        ->where('doctor_id', $doctor_id)
                        ->where('is_deleted', '0')
                        ->get('doctor_commission_payment')->result();
        $total_commission_paid = $doctor_commission_payment[0]->paid_amount;

        $patient_test_entry = $this->db
                        ->where('is_deleted', '0')
                        ->where('doctor_id', $doctor_id)
                        ->get('patient_test_entry')->result();
        $grand_commission = 0;
        foreach ($patient_test_entry as $value) {
            $grand_commission += $value->net_total * ($commission_rate / 100);
        }

        echo $grand_commission - $total_commission_paid;
    }

    public function edit_doctor_payment_save() {
        $doctor_commission_payment_id = $this->input->post('doctor_commission_payment_id');
        $data = array();
        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['cash_or_bank'] = $this->input->post('cash_or_bank');
        $data['bank_id'] = $this->input->post('bank_id');
        $data['total_due_commission'] = $this->input->post('total_due_commission');
        $data['current_due_commission'] = $this->input->post('current_due_commission');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->where('doctor_commission_payment_id', $doctor_commission_payment_id)->update('doctor_commission_payment', $data);

        $sdata['update'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $doctor_id = '';
        $config['base_url'] = site_url('DoctorController/view_doctors_payment');
        $config['total_rows'] = $this->db->count_all('doctor_commission_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->doctors_payment_details($config["per_page"], $data['page'], $doctor_id);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('doctor/view_doctors_payment', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctors_payment',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_doctor_payment_save() {
        $doctor_id = $this->input->post('doctor_id');
        $data = array();
        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['cash_or_bank'] = $this->input->post('cash_or_bank');
        $data['bank_id'] = $this->input->post('bank_id');
        $data['total_due_commission'] = $this->input->post('total_due_commission');
        $data['current_due_commission'] = $this->input->post('current_due_commission');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->insert('doctor_commission_payment', $data);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'doctor/doctors_payment',
            'page_title' => 'Add Due Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_doctor_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('doctor');
        $crud->set_subject('Doctor');
       
        $crud->required_fields('name');
        $crud->columns('name', 'unique_id', 'address', 'commission', 'description', 'picture');
        $crud->fields('name', 'unique_id', 'address', 'commission', 'description', 'picture');

        $crud->callback_add_field('unique_id', function () {
            $uniqu_id = $this->db->select('*')->get('doctor_unique_id_table');
            $unique_id = 'EM' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
            $id = array(
                'unique_id' => $unique_id
            );
            $this->db->insert('doctor_unique_id_table', $id);
            return '<input type="text" maxlength="50" value="' . $unique_id . '" name="unique_id"  readonly>';
        });

        $crud->set_field_upload('picture', 'assets/doctor');

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function bank_deposit_report() {
        $page_data = array(
            'page_name' => 'bank/bank_deposit_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'bank/bank_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_test_reference_report() {

        $page_data = array(
            'page_name' => 'report/doctor_test_reference_report',
            'page_title' => 'Doctors Test Reference Report ',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_test_referece_report_load($ids) {
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['doctor_id'] = $ids_array[2];
        $this->load->view('report/doctor_test_referece_report_load_details', $data);
    }

    public function bank_deposit_report_details($ids) {
        $data = explode("_", $ids);
        $data['bank_id'] = $data[2];
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $this->load->view('bank/bank_deposit_report_details', $data);
    }

    public function bank_withdraw_report() {
        $page_data = array(
            'page_name' => 'bank/bank_withdraw_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'bank/bank_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_withdraw_report_details($ids) {
        $data = array();
        $data = explode("_", $ids);
        $data['bank_id'] = $data[2];
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $this->load->view('bank/bank_withdraw_report_details', $data);
    }

}
