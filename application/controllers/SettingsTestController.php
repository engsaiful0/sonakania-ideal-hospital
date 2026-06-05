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
        $crud->columns('test_group_code', 'test_group_name','machine_name');
        $crud->fields('test_group_code', 'test_group_name','machine_name');
        $crud->callback_add_field('test_group_code', function () {
            $uniqu_id = $this->db->select('*')->get('test_group');
            $test_group_code = 'TG' . str_pad($uniqu_id->num_rows() + 1, 2, '0', STR_PAD_LEFT);


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
        $crud->required_fields('test_name','setting_type', 'price','test_category','test_group_id');
        $crud->set_relation('test_group_id', 'test_group', '{test_group_code}-{test_group_name}');
        $crud->set_relation('test_sub_group_id', 'test_sub_group', 'sub_group_name');
        $crud->set_relation('test_group_id', 'test_group', 'test_group_name');
        $crud->set_relation('test_category_id', 'test_categories', 'test_category_name');
        $crud->columns('test_name','setting_type','test_category_id', 'price', 'doctor_commission', 'urgent_fee', 'test_by_gender', 'sequence','test_group_id');
        $crud->fields('test_name','setting_type','test_category_id', 'price', 'doctor_commission', 'urgent_fee', 'test_by_gender', 'sequence','test_group_id');
        $crud->field_type('test_by_gender', 'dropdown', array('Male' => 'Male', 'Female' => 'Female', 'Both' => 'Both'));
        $crud->field_type('setting_type', 'dropdown', array('Normal' => 'Normal', 'Unique' => 'Unique'));
        $crud->display_as('test_name', 'Test Name');
        $crud->display_as('test_group_id', 'Test Group Name');
        $crud->display_as('test_category_id', 'Category Name');



        $crud->display_as('test_by_gender', 'Test By Gender');
        $crud->display_as('visiting_doctor_commisin', 'Visiting Doctor Commision');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function panel()
    {
        $page_data = array(
            'page_name' => 'settings_test/panel',
            'page_title' => 'Panel',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function section()
    {
        $page_data = array(
            'page_name' => 'settings_test/section',
            'page_title' => 'Section',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function parameter()
    {
        $page_data = array(
            'page_name' => 'settings_test/parameter',
            'page_title' => 'Parameter',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function report_result()
    {
        $page_data = array(
            'page_name' => 'settings_test/report_result',
            'page_title' => 'Report Result',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function panel_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('test_panels');
        $crud->set_subject('Test Panel');
        $user_type = $this->session->userdata('user_type');
        //        if ($user_type != 'admin'):
        //            $crud->unset_add();
        //            $crud->unset_edit();
        //            $crud->unset_delete();
        //        endif;
        $crud->required_fields('panel_name','panel_name_bangla','test_group_id');
        $crud->set_relation('test_group_id', 'test_group', 'test_group_name');
        $crud->columns('panel_name', 'panel_name_bangla','description','test_group_id');
        $crud->fields('panel_name', 'panel_name_bangla','description','test_group_id');
        $crud->display_as('panel_name', 'Panel Name');
        $crud->display_as('panel_name_bangla', 'Panel Name Bangla');
        $crud->display_as('description', 'Description');
        $crud->display_as('test_group_id', 'Test Group Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function section_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('test_sections');
        $crud->set_subject('Test Section');
        $user_type = $this->session->userdata('user_type');
        //        if ($user_type != 'admin'):
        //            $crud->unset_add();
        //            $crud->unset_edit();
        //            $crud->unset_delete();
        //        endif;
        $crud->required_fields('section_name','section_name_bangla','heading','description');
        $crud->set_relation('panel_id', 'test_panels', 'panel_name');
        $crud->columns('panel_id', 'section_name', 'section_name_bangla','heading','description');
        $crud->fields('panel_id', 'section_name', 'section_name_bangla','heading','description');
        $crud->display_as('panel_id', 'Panel Name');
        $crud->display_as('section_name', 'Section Name');
        $crud->display_as('section_name_bangla', 'Section Name Bangla');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function parameter_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('test_parameters');
        $crud->order_by('id', 'ASC');
        $crud->set_subject('Test Parameter');
        $user_type = $this->session->userdata('user_type');
        //        if ($user_type != 'admin'):
        //            $crud->unset_add();
        //            $crud->unset_edit();
        //            $crud->unset_delete();
        //        endif;
        $crud->required_fields('section_id','parameter_name',);
        $crud->set_relation('section_id', 'test_sections', 'section_name');
        $crud->columns('section_id', 'parameter_name', 'unit','default_value', 'input_type', 'normal_range', 'serial');
        $crud->fields('section_id', 'parameter_name', 'unit', 'default_value', 'input_type', 'normal_range', 'serial');
        $crud->display_as('section_id', 'Section Name');
        $crud->display_as('parameter_name', 'Parameter Name');
        $crud->display_as('unit', 'Unit');
        $crud->display_as('input_type', 'Input Type');
        $crud->display_as('min_value', 'Min Value');
        $crud->display_as('max_value', 'Max Value');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function report_result_iframe()
    {
        $page_data = array(
            'page_name' => 'settings_test/report_result_iframe',
            'page_title' => 'Report Result',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
