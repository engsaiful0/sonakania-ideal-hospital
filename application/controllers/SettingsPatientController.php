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
class SettingsPatientController extends CI_Controller
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
    public function reference_media()
    {
        $page_data = array(
            'page_name' => 'settings_patient/reference_media',
            'page_title' => 'Add Reference Media',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_reference_media_frame()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('reference_media');
        $crud->set_subject('Reference Media');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('reference_media_name', 'unique_id', 'mobile_number', 'address','commissioin_rate');
        $crud->columns('reference_media_name', 'unique_id', 'mobile_number', 'address','commissioin_rate');
        $crud->fields('reference_media_name', 'unique_id', 'mobile_number', 'address','commissioin_rate');
        $crud->callback_add_field('unique_id', function () {
            $uniqu_id = $this->db->select('*')->get('reference_media');
            $uniqu_id = 'RM' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);

            return '<input type="text" maxlength="50" value="' . $uniqu_id . '" name="unique_id"  readonly>';
        });
        $crud->display_as('unique_id', 'Unique ID');
        $crud->display_as('mobile_number', 'Mobile Number');
        $crud->display_as('reference_media_name', 'Reference Media Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function cabin()
    {
        $page_data = array(
            'page_name' => 'settings_patient/cabin',
            'page_title' => 'Add Cabin',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_cabin_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('cabin');
        $crud->set_subject('Cabin');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('cabin_category_id', 'cabin_number', 'cabin_rent','status');
        $crud->set_relation('cabin_category_id', 'cabin_category', 'cabin_category_name');
        $crud->columns('cabin_category_id', 'cabin_number', 'cabin_rent','status');
        $crud->fields('cabin_category_id', 'cabin_number', 'cabin_rent','status');
        $crud->field_type('status', 'dropdown', array('Available' => 'Available', 'Not Available' => 'Not Available'));
        $crud->display_as('cabin_category_id', 'Cabin Category Name');
        $crud->display_as('cabin_number', 'Cabin Number');
        $crud->display_as('cabin_rent', 'Cabin Rent');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }


    public function discharge_reason()
    {
        $page_data = array(
            'page_name' => 'settings_patient/discharge_reason',
            'page_title' => 'Add Discharge Reason',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_discharge_reason_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('discharge_reason');
        $crud->set_subject('Discharge Reason');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('name');
        $crud->columns('name');
        $crud->fields('name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function ward()
    {
        $page_data = array(
            'page_name' => 'settings_patient/ward',
            'page_title' => 'Add Word',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_ward_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('ward');
        $crud->set_subject('Ward');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('number');

        $crud->columns('name', 'number');
        $crud->fields('name', 'number');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function add_referred_by()
    {
        $page_data = array(
            'page_name' => 'patient/add_referred_by',
            'page_title' => 'Add Referred By',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_referred_by_iframe()
    {
        $crud = new Grocery_crud();
        $crud->set_table('referred_by');
        $crud->set_subject('Referred By');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('referred_by_name');
        $crud->columns('referred_by_name');
        $crud->fields('referred_by_name');
        $crud->display_as('referred_by_name', 'Referred By');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function cabin_category()
    {
        $page_data = array(
            'page_name' => 'settings_patient/cabin_category',
            'page_title' => 'Add Cabin Category',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_cabin_category_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('cabin_category');
        $crud->set_subject('Cabin Category');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('cabin_category_name');
        $crud->columns('cabin_category_name');
        $crud->fields('cabin_category_name');
        $crud->display_as('cabin_category_name', 'Cabin Category Number');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function bed()
    {
        $page_data = array(
            'page_name' => 'settings_patient/add_general_bed',
            'page_title' => 'Add Bed',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_general_bed_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('bed');
        $crud->set_subject('Bed');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'Admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->required_fields('ward_id', 'bed_number', 'bed_rent', 'gender','status');
        $crud->columns( 'ward_id', 'bed_number', 'bed_rent', 'gender',  'status');
        $crud->fields('ward_id', 'bed_number', 'bed_rent', 'gender', 'status');
        $crud->display_as('bed_number', 'Bed Number');
        $crud->display_as('bed_rent', 'Per Day Rent');
        $crud->display_as('ward_id', 'Word');
        $crud->set_relation('ward_id', 'word', 'name');
        $crud->field_type('gender', 'dropdown', array('Male' => 'Male', 'Female' => 'Female', 'Both' => 'Both'));
        $crud->field_type('status', 'dropdown', array('Available' => 'Available', 'Not Available' => 'Not Available'));
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

}
