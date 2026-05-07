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
class IncrementController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }

    function index()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'increment/add',
            'page_title' => 'Increment',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function increment()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'increment/add',
            'page_title' => 'Increment',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_increment()
    {
        $employee_id = $this->input->post('employee_id');
        $from_date = '';
        $to_date = '';
        $config = array();
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/IncrementController/view_increment";
        $config['total_rows'] = $this->IncrementModel->count_all_increments($employee_id, $from_date, $to_date);
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $config['num_links'] = 2; // Number of page links to display on either side of the current page

        // Integrate bootstrap pagination
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i> Previous';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['first_link'] = 'First'; // Optional: Add a "First" link
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['last_link'] = 'Last'; // Optional: Add a "Last" link

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Fetch data with limit and offset
        $data['increment_data'] = $this->IncrementModel->get_increments($this->per_page, $page, $employee_id, $from_date, $to_date);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('increment/view', $data, true);
        // Load view
        $page_data = array(
            'page_name' => 'increment/view',
            'page_title' => 'Increment View',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function view_increment1($offset = 0)
    {
        $employee_id = $this->input->post('employee_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $config['base_url'] = base_url('view-increment');
        $config['total_rows'] = $this->IncrementModel->count_all_increments($employee_id, $from_date, $to_date);
        $config['per_page'] = 30;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['increment_data'] = $this->IncrementModel->get_increments($config['per_page'], $offset, $employee_id, $from_date, $to_date);

        $this->load->view('increment/view', $data, true);
        $page_data = array(
            'page_name' => 'increment/view',
            'page_title' => 'Increment View',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_increment()
    {
        $page_data = array(
            'page_name' => 'increment/add',
            'page_title' => 'Add Increment',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_increment($increment_id)
    {
        $data['increment_id'] = $increment_id;
        $this->load->view('increment/edit', $data, true);
        $page_data = array(
            'page_name' => 'increment/edit',
            'page_title' => 'Edit Incerment',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_increment_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'amount' => $this->input->post('amount'),
                'remark' => $this->input->post('remark'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'user_id' => $this->session->userdata('user_id'),
            );
            $increment = $this->db->insert('increment', $data);
            $increment_id = $this->db->insert_id();
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Increment added and Increment Id is=' . $increment_id
            );
            $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function update_increment_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'amount' => $this->input->post('amount'),
                'remark' => $this->input->post('remark'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $increment_id = $this->input->post('increment_id');
            $this->db->where('increment_id', $increment_id)->update('increment', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Increment Edited and Increment Id is=' . $increment_id
            );
            $this->db->insert('activity_log', $activity_data);

            $sdata['increment_updated'] = 'Data has been updated successfully.';
            $this->session->set_userdata($sdata);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    function delete_this_increment($increment_id)
    {
        $this->db->where('increment_id', $increment_id)->delete('increment');
        $sdata['increment_deleted'] = 'Data has been deleted successfully.';
        $this->session->set_userdata($sdata);
        redirect(base_url('view-increment'));
    }
}
