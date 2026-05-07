<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
date_default_timezone_set('Asia/Dhaka');

class LoginController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
    }

    /*     * *default functin, redirects to login page if no admin logged in yet** */

    public function index()
    {
        $this->load->view('login');
    }

    /*     * *validate login*** */

    public function database_backup()
    {

        $this->load->dbutil();
        $prefs = array(
            'format' => 'zip',
            'filename' => 'my_db_backup.sql'
        );
        $backup = &$this->dbutil->backup($prefs);
        $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
        $save = '/upload/_tmp/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->load->helper('download');
        force_download($db_name, $backup);
    }
    public function FunctionLogin()
    {
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');
    
        $this->db->select('user.*, user_type.name as user_type_name');
        $this->db->from('user');
        $this->db->join('user_type', 'user.user_type_id = user_type.user_type_id');
        $this->db->where(array('user.user_name' => $user_name, 'user.password' => $password, 'user.is_active' => 'active'));
        $login_query = $this->db->get();
    
        if ($login_query->num_rows() > 0) {
            $row = $login_query->row();
    
            // Fetch permissions
            $permissions_query = $this->db->get_where('user_permissions', array('user_id' => $row->user_id));
            $permissions = array_column($permissions_query->result_array(), 'action');
    
            // Store user data, user type, and permissions in session
            $data = array(
                'user_name' => $row->user_name,
                'user_id' => $row->user_id,
                'user_type' => $row->user_type_name,
                'permissions' => $permissions
            );
            $this->session->set_userdata($data);
    
            // Log user session
            $ip = $_SERVER['REMOTE_ADDR'];
            $data_session = array(
                'user_id' => $row->user_id,
                'sign_in_time' => date('Y-m-d H:i:s'),
                'ip' => $ip
            );
            $this->db->insert('user_session', $data_session);
    
            // Send success response
            echo json_encode(array('success' => true, 'message' => 'Login successful.'));
        } else {
            // Send failure response
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password.'));
        }
    }
    
    function FunctionLogin1()
    {
        
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');
        $this->db->select('user.*, user_type.name as user_type_name');
        $this->db->from('user');
        $this->db->join('user_type', 'user.user_type_id = user_type.user_type_id');
        $this->db->where(array('user.user_name' => $user_name, 'user.password' => $password,'user.is_active'=>'active'));
        $login_query = $this->db->get();

        if ($login_query->num_rows() > 0) {
            $row = $login_query->row();

            // Fetch permissions
            $permissions_query = $this->db->get_where('user_permissions', array('user_id' => $row->user_id));
            $permissions = array_column($permissions_query->result_array(), 'action');

            // Store user data, user type, and permissions in session
            $data = array(
                'user_name' => $row->user_name,
                'user_id' => $row->user_id,
                'user_type' => $row->user_type_name, // Include user_type_name
                'permissions' => $permissions
            );
            $this->session->set_userdata($data);

            // Log user session
            $ip = $_SERVER['REMOTE_ADDR'];
            $data_session = array(
                'user_id' => $row->user_id,
                'sign_in_time' => date('Y-m-d H:i:s'),
                'ip' => $ip
            );
            $this->db->insert('user_session', $data_session);

            redirect('home');
        } else {
            redirect('LoginController');
        }
    }


    /*     * *****LOGOUT FUNCTION ****** */

    function FunctionLogout()
    {

        $ip = $_SERVER['REMOTE_ADDR'];
        $data_session = array(
            'user_id' => $this->session->userdata('user_id'),
            'sign_out_time' => date('Y-m-d H:i:s'),
            'ip' => $ip
        );
        $this->db->insert('user_session', $data_session);
        $this->session->sess_destroy();
        redirect('login');
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four()
    {
        $this->load->view('four_zero_four');
    }

    /*     * *RESET AND SEND PASSWORD TO REQUESTED EMAIL*** */

    function reset_password() {}

    /*     * *LOGIN AS ANOTHER USER LIKE DOCTOR,PATIENT,PHARMACIST,LABORATORIST ETC***** */

    function login_as($user_type = '', $user_id = '') {}
}
