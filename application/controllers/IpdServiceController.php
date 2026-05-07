<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IpdServiceController
 *
 * @author Lenovo
 */
class IpdServiceController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
        $this->load->library('pagination');
    }
    public function patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('patient_unique_id', $parameter)
                ->from('patient');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('patient');
        }
        $sql = $this->db->get()->result();
        $data_patient_unique = array();
        foreach ($sql as $value) {
            array_push($data_patient_unique, $value->patient_unique_id);
        }
        echo json_encode($data_patient_unique);
    }
    private $defaults = array();

    public function ipd_service_price_load()
    {
        $ipd_service_item_id = $_POST['ipd_service_item_id'];
        $ipd_service = $this->db->where('ipd_service_item_id', $ipd_service_item_id)->get('ipd_service_item')->row();
        echo $ipd_service->price;
    }
    public function ipd_service_price_with_category_load()
    {
        error_reporting(0);
        $ipd_service_item_id = $_POST['ipd_service_item_id'];
        $ipd_service = $this->db->where('ipd_service_item_id', $ipd_service_item_id)->get('ipd_service_item')->row();
        echo $ipd_service->price . '*' . $ipd_service->test_category_id;
    }
    public function add_more_ipd_service_row()
    {
        $data['next_id'] = $_POST['next_id'];
        $this->load->view('ipd_service/more_ipd_service_row', $data);
    }

    public function view_ipd_service()
    {


        $patient_unique_id = $this->input->post('patient_unique_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $ipd_patient_id = '';
        if ($this->input->post('patient_unique_id') != '') {
            $patient = $this->db->where('patient_unique_id', $patient_unique_id)->get('ipd_patient')->row();
            $ipd_patient_id = $patient->ipd_patient_id;
        }
        // die($patient_id);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] =  base_url() . "index.php/IpdServiceController/view_ipd_service";
        $config['total_rows'] = $this->IpdServiceModel->count_all_ipd_service($ipd_patient_id, $from_date, $to_date);
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

        // Integrate bootstrap pagination
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);

        $data['ipd_service_data'] = $this->IpdServiceModel->get_ipd_service($this->per_page, $page, $ipd_patient_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('ipd_service/view_ipd_service', $data, true);
        $page_data = array(
            'page_name' => 'ipd_service/view_ipd_service',
            'page_title' => 'View IPD Service',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_ipd_service()
    {
        $page_data = array(
            'page_name' => 'ipd_service/add_ipd_service',
            'page_title' => 'Add Ipd Service',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_ipd_service($ipd_service_id)
    {
        $data['ipd_service_id'] = $ipd_service_id;
        $this->load->view('ipd_service/edit_ipd_service', $data, true);
        $page_data = array(
            'page_name' => 'ipd_service/edit_ipd_service',
            'page_title' => 'Edit Ipd Srvice',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_ipd_service_again($ipd_service_id)
    {
        $data['ipd_service_id'] = $ipd_service_id;
        $this->load->view('ipd_service/print_ipd_service', $data, true);
        $page_data = array(
            'page_name' => 'ipd_service/print_ipd_service',
            'page_title' => 'Edit Ipd Srvice',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_ipd_service()
    {
        $page_data = array(
            'page_name' => 'ipd_service/print_ipd_service',
            'page_title' => 'Edit Ipd Srvice',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ipd_service_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'reference_doctor_id' => $this->input->post('reference_doctor_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'net_total' => $this->input->post('net_total'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $this->db->insert('ipd_service', $data);
            $ipd_service_id = $this->db->insert_id();

            $ipd_service_item_id = $this->input->post('ipd_service_item_id');
            $price = $this->input->post('price');
            $test_category_id = $this->input->post('test_category_id');
            $amount = $this->input->post('amount');
            $quantity = $this->input->post('quantity');

            $issue_details = array();
            for ($loop = 0; $loop < count($ipd_service_item_id); $loop++) {
                $issue_details[] = array(
                    'ipd_service_item_id ' => $ipd_service_item_id[$loop],
                    'ipd_patient_id ' => $this->input->post('ipd_patient_id'),
                    'ipd_service_id ' => $ipd_service_id,
                    'price' => $price[$loop],
                    'test_category_id' => $test_category_id[$loop],
                    'quantity' => $quantity[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
                );
            }


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD Service added and IPD Service Id is=' . $ipd_service_id
            );
            $this->db->insert('activity_log', $activity_data);


            $this->db->insert_batch('ipd_service_details', $issue_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_ipd_service_id'] = $ipd_service_id;
            $sdata['ipd_service_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }



    public function ipd_service_update()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $ipd_service_previous = $this->db->where('ipd_service_id', $this->input->post('ipd_service_id'))->get('ipd_service')->row();
            // Get form data
            $ipd_service_id = $this->input->post('ipd_service_id');
            $data = array(
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'reference_doctor_id' => $this->input->post('reference_doctor_id'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'net_total' => $this->input->post('net_total'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->where('ipd_service_id', $ipd_service_id)->update('ipd_service', $data);
            $ipd_service_item_id = $this->input->post('ipd_service_item_id');
            $price = $this->input->post('price');
            $test_category_id = $this->input->post('test_category_id');
            $amount = $this->input->post('amount');
            $quantity = $this->input->post('quantity');
            $this->db->where('ipd_service_id', $ipd_service_id)->delete('ipd_service_details');
            $issue_details = array();
            for ($loop = 0; $loop < count($ipd_service_item_id); $loop++) {
                $issue_details[] = array(
                    'ipd_service_item_id ' => $ipd_service_item_id[$loop],
                    'ipd_service_id ' => $ipd_service_id,
                    'ipd_patient_id ' => $this->input->post('ipd_patient_id'),
                    'test_category_id' => $test_category_id[$loop],
                    'price' => $price[$loop],
                    'quantity' => $quantity[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $ipd_service_previous->user_id,
                );
            }
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD Service update and IPD Service Id is=' . $ipd_service_id
            );
            $this->db->insert('activity_log', $activity_data);


            $this->db->insert_batch('ipd_service_details', $issue_details);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['ipd_service_update'] = 'saved successully';
            $sdata['print_ipd_service_id'] = $ipd_service_id;
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function get_ipd_service_details()
    {
        $id = $this->input->post('id');
        $data = $this->db->where('ipd_service_id', $id)->get('ipd_service')->row();
        $this->load->view('ipd_service/ipd_service_details', ['data' => $data]);
    }

    function delete_this_ipd_service($ipd_service_id)
    {

        $this->db->where('ipd_service_id', $ipd_service_id)->delete('ipd_service');
        $this->db->where('ipd_service_id', $ipd_service_id)->delete('ipd_service_details');
        $sdata['ipd_service_deleted'] = 'Data has been deleted successfully.';
        $this->session->set_userdata($sdata);
        redirect(base_url('view-ipd-service'));
    }
}
