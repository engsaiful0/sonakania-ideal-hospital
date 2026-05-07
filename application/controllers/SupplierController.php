<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SupplierController
 *
 * @author Lenovo
 */
class SupplierController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            // redirect('LoginController');
        }
    }

    public function index() {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function add_test_group() {
        $page_data = array(
            'page_name' => 'test/add_test_group',
            'page_title' => 'Test Group',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_test_group_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('test_group');
        $crud->set_subject('Test Group Name');
        $user_type = $this->session->userdata('user_type');
//        if ($user_type != 'admin'):
//            $crud->unset_add();
//            $crud->unset_edit();
//            $crud->unset_delete();
//        endif;
        $crud->required_fields('test_group_code', 'test_group_name');
        $crud->columns('test_group_code', 'test_group_name');
        $crud->fields('test_group_code', 'test_group_name');
        $crud->callback_add_field('test_group_code', function () {
            $uniqu_id = $this->db->select('*')->get('test_group');
            $test_group_code = 'TG' . str_pad($uniqu_id->num_rows() + 1, 3, '0', STR_PAD_LEFT);


            return '<input type="text" maxlength="50" value="' . $test_group_code . '" name="test_group_code"  readonly>';
        });
        $crud->display_as('test_group_code', 'Test Group Code');
        $crud->display_as('test_group_name', 'Test Group Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_test_subgroup() {
        $page_data = array(
            'page_name' => 'test/add_test_sub_group',
            'page_title' => 'Test Sub Group Name',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_test_subgroup_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('test_sub_group');
        $crud->set_subject('Test Sub Group Name');
        $user_type = $this->session->userdata('user_type');
//        if ($user_type != 'admin'):
//            $crud->unset_add();
//            $crud->unset_edit();
//            $crud->unset_delete();
//        endif;
        $crud->required_fields('test_group_id', 'sub_group_name');
        $crud->set_relation('test_group_id', 'test_group', 'test_group_name');
        $crud->columns('test_group_id', 'sub_group_name');
        $crud->fields('test_group_id', 'sub_group_name');
        $crud->display_as('sub_group_name', 'Test Sub Group Code');
        $crud->display_as('test_group_id', 'Test Group Code');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_test() {
        $page_data = array(
            'page_name' => 'test/add_test',
            'page_title' => 'Test Name',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function daily_test_sales_report() {
        $page_data = array(
            'page_name' => 'test/daily_test_sales_report',
            'page_title' => 'Daily Test Sales Report',
            'sidebar' => 'test/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function daily_test_sales_report_details_load($ids) {
        $ids = explode('_', $ids);
        $data['from_date'] = $ids[0];
        $data['to_date'] = $ids[1];
        $this->load->view('test/daily_test_sales_report_details_load', $data);
    }

    public function load_product_row() {
        $data['id'] = $_POST['id'];
        $this->load->view('test/load_product_row', $data);
    }

    public function TestEntryDetailsPrint($patient_test_entry_id) {

        $data['patient_test_entry_id'] = $patient_test_entry_id;

        $this->load->view('test/print_test_entry_invoice', $data, true);
        $page_data = array(
            'page_name' => 'test/print_test_entry_invoice',
            'page_title' => 'Test Entry Invoie',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_test_entry($patient_test_entry_id) {
        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->load->view('test/edit_test_entry', $data);
    }

    public function price_load() {
        $test_id = $_POST['test_id'];
        $test = $this->db->where('test_id', $test_id)->get('test')->row();
        echo $test->price;
    }

    public function add_test_iframe() {
        $crud = new Grocery_crud();
        $crud->set_table('test');
        $crud->set_subject('Test Name');
        $user_type = $this->session->userdata('user_type');
//        if ($user_type != 'admin'):
//            $crud->unset_add();
//            $crud->unset_edit();
//            $crud->unset_delete();
//        endif;
        $crud->required_fields('test_group_id', 'test_name', 'price', 'test_by_gender');
        $crud->set_relation('test_group_id', 'test_group', '{test_group_code}-{test_group_name}');
        $crud->set_relation('test_sub_group_id', 'test_sub_group', 'sub_group_name');
        $crud->columns('test_group_id', 'test_sub_group_id', 'test_code', 'test_name', 'price', 'urgent_fee', 'test_by_gender', 'visiting_doctor_commisin');
        $crud->fields('test_group_id', 'test_sub_group_id', 'test_code', 'test_name', 'price', 'urgent_fee', 'test_by_gender', 'visiting_doctor_commisin');
        $crud->field_type('test_by_gender', 'dropdown', array('Male' => 'Male', 'Female' => 'Female', 'Both' => 'Both'));
        $crud->display_as('test_name', 'Test Name');
        $crud->display_as('test_code', 'Test Code');
        $crud->display_as('test_sub_group_id', 'Test Sub Group Name');
        $crud->display_as('test_group_id', 'Test Group Name');
        $crud->display_as('test_name', 'Test Name');


        $crud->display_as('test_by_gender', 'Test By Gender');
        $crud->display_as('visiting_doctor_commisin', 'Visiting Doctor Commision');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function deliver_report($patient_test_entry_id) {
        $update = array('is_delivered' => 'delivered');
        $this->db->where('patient_test_entry_id', $patient_test_entry_id)
                ->update('patient_test_entry', $update);
        $sdata['delivered'] = 'saved successully';
        $this->session->set_userdata($sdata);

        $patient_test_entry_id = $this->input->post('patient_test_entry_id');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');

        $config['base_url'] = site_url('TestController/view_test_entry');
        $config['total_rows'] = $this->db->count_all('patient_test_entry');
        $config['per_page'] = "70";
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
        $data['detailsList'] = $this->ProductModel->patient_test_entry_details($config["per_page"], $data['page'], $patient_test_entry_id, $patient_name, $mobile);

        $data['pagination'] = $this->pagination->create_links();
        //    $data['model_id'] = $model_id;
        //  $data['product_category_id'] = $product_category_id;

        $this->load->view('test/test_report_delivery', $data, true);
        $page_data = array(
            'page_name' => 'test/test_report_delivery',
            'page_title' => 'Report Delivery',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_report_delivery() {
        $patient_test_entry_id = $this->input->post('patient_test_entry_id');
        $patient_name = $this->input->post('patient_name');
        $mobile = $this->input->post('mobile');

        $config['base_url'] = site_url('TestController/view_test_entry');
        $config['total_rows'] = $this->db->count_all('patient_test_entry');
        $config['per_page'] = "70";
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
        $data['detailsList'] = $this->ProductModel->patient_test_entry_details($config["per_page"], $data['page'], $patient_test_entry_id, $patient_name, $mobile);

        $data['pagination'] = $this->pagination->create_links();
        //    $data['model_id'] = $model_id;
        //  $data['product_category_id'] = $product_category_id;

        $this->load->view('test/test_report_delivery', $data, true);
        $page_data = array(
            'page_name' => 'test/test_report_delivery',
            'page_title' => 'Report Delivery',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    

    

    public function test_due_payment_save() {
        $patient_test_entry_id = $this->input->post('patient_test_entry_id');
        $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
        $data_sells = array(
            'patient_test_entry_id' => $patient_test_entry_id,
            'invoice_no' => $patient_test_entry->invoice_no,
            'payment_type' => 'from_due_collection',
            'paid' => $patient_test_entry->due,
            'due' => '0',
            'user_id' => $this->session->userdata('user_id'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );
        $this->db->insert('test_collection', $data_sells);

        $update = array(
            'paid' => $patient_test_entry->paid + $this->input->post('due'),
            'due' => '0'
        );
        $this->db->where('patient_test_entry_id', $patient_test_entry_id)
                ->update('patient_test_entry', $update);

        $data['patient_test_entry_id'] = $patient_test_entry_id;
        $this->load->view('test/print_test_entry_invoice', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'test/print_test_entry_invoice',
            'page_title' => 'Print Test Entry',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    

    

   

    

    public function sold_test_report() {
        $page_data = array(
            'page_name' => 'test/sold_test_report',
            'page_title' => 'Sold Test Report',
            'sidebar' => 'test/test_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function sold_test_report_details_load($ids) {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['test_id'] = $data[2];
        $this->load->view('test/sold_test_report_details_load', $data);
    }

}
