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
class SettingsPrescriptionController extends CI_Controller
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
    public function add_diagnosis_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('diagnosis');
        $crud->set_subject('Diagnosis');
        
        $crud->required_fields('diagnosis_name');
        $crud->columns('diagnosis_name');
        $crud->fields('diagnosis_name');
        $crud->display_as('diagnosis_name', 'Diagnosis Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function diagnosis() {
        $page_data = array(
            'page_name' => 'prescription_settings/add_diagnosis',
            'page_title' => 'Add Diagnosis',
             'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    

    public function advice() {
        $page_data = array(
            'page_name' => 'prescription_settings/add_advice',
            'page_title' => 'Add Prescription',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_advice_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('advice');
        $crud->set_subject('Advice');
      
        $crud->required_fields('advice_name');
        $crud->columns('advice_name');
        $crud->fields('advice_name');
        $crud->display_as('advice_name', 'Advice Name');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function medicin_times() {
        $page_data = array(
            'page_name' => 'prescription_settings/add_medicin_times',
            'page_title' => 'Add Medicin Times',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_medicin_times_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('medicin_times');
        $crud->set_subject('Medicin Times');
        
        $crud->required_fields('medicin_times_name');

        $crud->columns('medicin_times_name');
        $crud->fields('medicin_times_name');

        $crud->display_as('medicin_times_name', 'Medicin Times Name');

        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

}
