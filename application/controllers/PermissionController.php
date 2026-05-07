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
class PermissionController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('PermissionModel');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    
    function add_permission()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'permission/add_permission',
            'page_title' => 'Permission',
              'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function get_permissions() {
        $user_id = $this->input->post('user_id');
        $permissions = $this->PermissionModel->get_permissions($user_id);

        if ($permissions) {
            echo json_encode(['status' => 'success', 'permissions' => $permissions]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No permission is set']);
        }
    }
    public function save_permissions() {
        $user_id = $this->input->post('user_id');
        $permissions = $this->input->post('permissions');
        
        $response = [];
        
        if ($this->PermissionModel->save_user_permissions($user_id, $permissions)) {
            $response['status'] = 'success';
            $response['message'] = 'Permissions saved successfully.';
            $sdata['permission_saved'] = 'Data has been deleted successfully.';
            $this->session->set_userdata($sdata);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to save permissions.';
        }

        echo json_encode($response);
    }
    
}
