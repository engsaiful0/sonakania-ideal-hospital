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
class SettingsPharmachyController extends CI_Controller
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
    public function shelf()
    {
        $page_data = array(
            'page_name' => 'settings_pharmachy/shelf',
            'page_title' => 'Test Group',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function shelf_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('shelfs');
        $crud->set_subject('Shelf Number');
        $user_type = $this->session->userdata('user_type');
        $crud->required_fields('shelf_number');
        $crud->columns('shelf_number');
        $crud->fields('shelf_number');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function medicine_type()
    {
    
        $page_data = array(
            'page_name' => 'settings_pharmachy/medicine_type',
            'page_title' => 'Medicine Type',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_type_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('drug_type');
        $crud->set_subject('Medicine Type');
        $crud->required_fields('type_name');
        $crud->columns('type_name', 'short_name');
        $crud->fields('type_name', 'short_name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function manufacturer()
    {
        $page_data = array(
            'page_name' => 'settings_pharmachy/manufacturer',
            'page_title' => 'Manufacturer',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function manufacturer_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('manufacturer');
        $crud->set_subject('Manufacturer');
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
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
        $crud->required_fields('test_group_id', 'test_name', 'price', 'test_by_gender');
        $crud->set_relation('test_group_id', 'test_group', '{test_group_code}-{test_group_name}');
        $crud->set_relation('test_sub_group_id', 'test_sub_group', 'sub_group_name');
        $crud->columns('test_group_id', 'test_code', 'test_name', 'price', 'urgent_fee', 'test_by_gender', 'visiting_doctor_commisin', 'sequence');
        $crud->fields('test_group_id', 'test_code', 'test_name', 'price', 'urgent_fee', 'test_by_gender', 'visiting_doctor_commisin', 'sequence');
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
}
