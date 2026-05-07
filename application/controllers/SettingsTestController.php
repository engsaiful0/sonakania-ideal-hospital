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
class SettingsTestController extends CI_Controller
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
    public function add_test_group()
    {
        $page_data = array(
            'page_name' => 'settings_test/add_test_group',
            'page_title' => 'Test Group',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_test_group_iframe()
    {
        error_reporting(0);
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
    
    public function add_test_category()
    {
        $page_data = array(
            'page_name' => 'settings_test/add_test_category',
            'page_title' => 'Test Category',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function add_test_category_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('test_categories');
        $crud->set_subject('Test Category');
        $user_type = $this->session->userdata('user_type');
        //        if ($user_type != 'admin'):
        //            $crud->unset_add();
        //            $crud->unset_edit();
        //            $crud->unset_delete();
        //        endif;
        $crud->required_fields('test_category_name','test_category_name_bangla', 'room','room_bangla','commission_rate','commission_type');
        
        $crud->columns('test_category_name', 'test_category_name_bangla', 'room','room_bangla','commission_rate','commission_type','less_than_or_equal_condition','greater_than_or_equal_condition');
        $crud->fields('test_category_name', 'test_category_name_bangla', 'room','room_bangla','commission_rate','commission_type','less_than_or_equal_condition','greater_than_or_equal_condition');
        $crud->display_as('sub_group_name', 'Test Sub Group Code');
        $crud->display_as('test_group_id', 'Test Group Code');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function add_test_subgroup()
    {
        $page_data = array(
            'page_name' => 'settings_test/add_test_sub_group',
            'page_title' => 'Test Sub Group Name',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_test_subgroup_iframe()
    {
        error_reporting(0);
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

    public function test_name()
    {
        $page_data = array(
            'page_name' => 'settings_test/add_test',
            'page_title' => 'Test Name',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function add_test_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('test');
        $crud->set_subject('Test Name');
        $user_type = $this->session->userdata('user_type');
        //        if ($user_type != 'admin'):
        //            $crud->unset_add();
        //            $crud->unset_edit();
        //            $crud->unset_delete();
        //        endif;
        $crud->required_fields('test_name', 'price','test_category');
        $crud->set_relation('test_group_id', 'test_group', '{test_group_code}-{test_group_name}');
        $crud->set_relation('test_sub_group_id', 'test_sub_group', 'sub_group_name');
        $crud->set_relation('test_category_id', 'test_categories', 'test_category_name');
        $crud->columns('test_name','test_category_id', 'price', 'doctor_commission', 'urgent_fee', 'test_by_gender', 'sequence');
        $crud->fields('test_name','test_category_id', 'price', 'doctor_commission', 'urgent_fee', 'test_by_gender', 'sequence');
        $crud->field_type('test_by_gender', 'dropdown', array('Male' => 'Male', 'Female' => 'Female', 'Both' => 'Both'));

        $crud->display_as('test_name', 'Test Name');
        $crud->display_as('test_category_id', 'Category Name');



        $crud->display_as('test_by_gender', 'Test By Gender');
        $crud->display_as('visiting_doctor_commisin', 'Visiting Doctor Commision');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

}
