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
class DirectorController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('custom');
        $this->load->model('DirectorModel');

        $this->load->library('Grocery_crud');
        $this->load->library('pagination');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }

    function index()
    {
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'hrm/hrm_dashbaord',
            'page_title' => 'Purchase',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    /**
     * Modern directorHolder list view with Ajax
     */


    /**
     * Modern directorHolder add form with Ajax
     */
    public function add_director()
    {
        $page_data = array(
            'page_name' => 'hrm/director/add_director',
            'page_title' => 'Add Director',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Modern directorHolder edit form with Ajax
     */
    public function edit_director($director_id = null)
    {
        if (!$director_id) {
            redirect('view-director');
        }

        $director = $this->db->get_where('director', array('director_id' => $director_id))->row();
        if (!$director) {
            redirect('view-director');
        }

        $page_data = array(
            'page_name' => 'hrm/director/edit_director',
            'page_title' => 'Edit Director',
            'sidebar' => 'hrm/hrm_sidebar',
            'director' => $director
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Ajax endpoint to get all director holders
     */
    public function get_directors()
    {
        if ($this->input->is_ajax_request()) {
            $draw = intval($this->input->get("draw"));
            $start = intval($this->input->get("start"));
            $length = intval($this->input->get("length"));

            $this->db->select('*');
            $this->db->from('director');

            // Search functionality
            $search = $this->input->get("search");
            if (!empty($search['value'])) {
                $this->db->group_start();
                $this->db->like('name', $search['value']);
                $this->db->or_like('unique_id', $search['value']);
                $this->db->or_like('mobile', $search['value']);
                $this->db->or_like('email', $search['value']);
                $this->db->or_like('father_name', $search['value']);
                $this->db->or_like('nid_number', $search['value']);
                $this->db->group_end();
            }

            $total_records = $this->db->count_all_results('', false);

            // Ordering
            $order = $this->input->get("order");
            if (!empty($order)) {
                $columns = array('id', 'name', 'unique_id', 'mobile', 'email', 'no_of_share', 'amount_per_share', 'current_share_value', 'total_amount');
                $column_index = $order[0]['column'];
                $column_name = $columns[$column_index];
                $column_dir = $order[0]['dir'];
                $this->db->order_by($column_name, $column_dir);
            } else {
                $this->db->order_by('id', 'DESC');
            }

            $this->db->limit($length, $start);
            $directors = $this->db->get()->result();

            $data = array();
            foreach ($directors as $row) {
                $actions = '<div class="btn-group" role="group">';
                $actions .= '<button type="button" class="btn btn-sm btn-primary" onclick="viewdirectorHolder(' . $row->id . ')" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button type="button" class="btn btn-sm btn-warning" onclick="editdirectorHolder(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deletedirectorHolder(' . $row->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';

                $data[] = array(
                    $row->id,
                    $row->name,
                    $row->director_unique_id,
                    $row->mobile,
                    $row->email ?: 'N/A',
                    number_format($row->no_of_director),
                    '৳' . number_format($row->amount_per_director, 2),
                    '৳' . number_format($row->current_director_value ?: $row->amount_per_director, 2),
                    '৳' . number_format($row->total_amount, 2),
                    $actions
                );
            }

            $response = array(
                "draw" => $draw,
                "recordsTotal" => $this->db->count_all('director'),
                "recordsFiltered" => $total_records,
                "data" => $data
            );

            echo json_encode($response);
        } else {
            show_404();
        }
    }
    public function director_unique_id_load()
    {
        $parameter = $_POST['parameter'];

        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('unique_id', $parameter)
                ->from('director');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('director');
        }
        $sql = $this->db->get()->result();
        $data_director = array();
        foreach ($sql as $value) {
            array_push($data_director, $value->unique_id);
        }
        echo json_encode($data_director);
    }
    /**
     * Ajax endpoint to save director holder
     */
    public function save_director()
    {
        if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
            $this->load->library('form_validation');

            // Set validation rules
            $this->form_validation->set_rules('name', 'Director Name', 'required|trim');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('nid_number', 'National ID', 'required|trim');
            $this->form_validation->set_rules('sign_in_mobile', 'Sign-in Mobile', 'required|trim');
            $this->form_validation->set_rules('father_name', 'Father Name', 'required|trim');
            $this->form_validation->set_rules('address', 'Address', 'required|trim');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            $this->form_validation->set_rules('director_price', 'director Price', 'required|numeric');
            $this->form_validation->set_rules('number_of_director', 'Number of directors', 'required|integer');
            $this->form_validation->set_rules('test_discount', 'Test Discount', 'numeric');
            $this->form_validation->set_rules('opd_discount', 'OPD Discount', 'numeric');
            $this->form_validation->set_rules('ipd_discount', 'IPD Discount', 'numeric');
            $this->form_validation->set_rules('physiography_discount', 'Physiography Discount', 'numeric');
            $this->form_validation->set_rules('emergency_discount', 'Emergency Discount', 'numeric');

            if ($this->form_validation->run() == FALSE) {
                $response = array(
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => validation_errors()
                );
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'unique_id' => $this->input->post('unique_id'),
                    'father_name' => $this->input->post('father_name'),
                    'mother_name' => $this->input->post('mother_name'),
                    'mobile' => $this->input->post('mobile'),
                    'email' => $this->input->post('email'),
                    'gender' => $this->input->post('gender'),
                    'nid_number' => $this->input->post('nid_number'),
                    'present_address' => $this->input->post('present_address'),
                    'permanent_address' => $this->input->post('permanent_address'),
                    'nationality_id' => $this->input->post('nationality_id'),
                    'religion_id' => $this->input->post('religion_id'),
                    'profession_id' => $this->input->post('profession_id'),
                    'category' => $this->input->post('category'),
                    'sign_in_mobile' => $this->input->post('sign_in_mobile'),
                    'number_of_share' => $this->input->post('number_of_share'),
                    'amount_per_share' => $this->input->post('amount_per_share'),
                    'yearly_share_value_increment_rate' => $this->input->post('yearly_share_value_increment_rate') ?: 0,
                    'current_share_value' => $this->input->post('current_share_value') ?: $this->input->post('amount_per_share'),
                    'total_amount' => $this->input->post('total_amount') ?: $this->input->post('amount_per_share') * $this->input->post('number_of_share'),
                    'name_of_nominee' => $this->input->post('name_of_nominee'),
                    'relation_id' => $this->input->post('relation_id'),
                    'nominee_mobile' => $this->input->post('nominee_mobile'),
                    'nominee_email' => $this->input->post('nominee_email'),
                    'nominee_present_address' => $this->input->post('nominee_present_address'),
                    'nominee_parmanent_address' => $this->input->post('nominee_parmanent_address'),
                    'bank_name_id' => $this->input->post('bank_name_id'),
                    'branch_name' => $this->input->post('branch_name'),
                    'account_name' => $this->input->post('account_name'),
                    'account_number' => $this->input->post('account_number'),
                    'date_of_join' => date('Y-m-d', strtotime($this->input->post('date_of_join'))),
                    'pharmachy_discount' => $this->input->post('pharmachy_discount') ?: 0,
                    'test_discount' => $this->input->post('test_discount') ?: 0,
                    'opd_discount' => $this->input->post('opd_discount') ?: 0,
                    'physiography_discount' => $this->input->post('physiography_discount') ?: 0,
                    'ipd_discount' => $this->input->post('ipd_discount') ?: 0,
                    'emergency_discount' => $this->input->post('emergency_discount') ?: 0,
                    'phygiotherapy_discount' => $this->input->post('phygiotherapy_discount') ?: 0,
                    'user_id' => $this->session->userdata('user_id'),
                );

                $id = $this->input->post('id');
                if ($id) {
                    // Update existing record
                    $this->db->where('director_id', $id);
                    $result = $this->db->update('director', $data);
                    $message = 'Director updated successfully';
                } else {
                    // Insert new record
                    $result = $this->db->insert('director', $data);
                    $message = 'Director added successfully';
                }

                if ($result) {
                    $response = array(
                        'success' => true,
                        'message' => $message
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Database error occurred'
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    /**
     * Ajax endpoint to get single director holder data
     */
    public function get_director($director_id)
    {
        if ($this->input->is_ajax_request()) {
            $director = $this->db->get_where('director', array('director_id' => $director_id))->row();

            if ($director) {
                echo json_encode(array(
                    'success' => true,
                    'data' => $director
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'director holder not found'
                ));
            }
        } else {
            show_404();
        }
    }

    /**
     * Ajax endpoint to delete director holder
     */
    public function delete_director($director_id)
    {
        if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
            $result = $this->db->delete('director', array('director_id' => $director_id));

            if ($result) {
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Director deleted successfully'
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Failed to delete director'
                ));
            }
        } else {
            show_404();
        }
    }

    /**
     * Ajax endpoint to save extended director holder data with file uploads
     */
    public function save_director_data()
    {
        if ($this->input->method() == 'post') {
            $this->load->library('form_validation');

            // Set validation rules for all fields
            $this->form_validation->set_rules('name', 'director Holder Name', 'required|trim');
            $this->form_validation->set_rules('father_name', 'Father Name', 'required|trim');
            $this->form_validation->set_rules('mother_name', 'Mother Name', 'required|trim');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('no_of_share', 'Number of share', 'required|integer');
            $this->form_validation->set_rules('amount_per_share', 'Amount Per share', 'required|numeric');
            $this->form_validation->set_rules('yearly_share_value_increment_rate', 'Yearly share Value Increment Rate', 'numeric');
            $this->form_validation->set_rules('current_share_value', 'Current share Value', 'numeric');
            $this->form_validation->set_rules('name_of_nominee', 'Nominee Name', 'required|trim');
            $this->form_validation->set_rules('sign_in_mobile_no', 'Sign In Mobile Number', 'required|trim');

            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Validation failed: ' . validation_errors()
                ));
                return;
            }

            // Handle file uploads
            $picture_filename = '';
            $nid_filename = '';

            if (!empty($_FILES['picture']['name'])) {
                $picture_filename = $this->upload_director_file('picture', 'assets/employee/');
                if (!$picture_filename) {
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Failed to upload picture file'
                    ));
                    return;
                }
            }

            if (!empty($_FILES['nid_file']['name'])) {
                $nid_filename = $this->upload_director_file('nid_file', 'assets/employee/');
                if (!$nid_filename) {
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Failed to upload NID file'
                    ));
                    return;
                }
            }

            // Prepare data for insertion/update
            $data = array(
                'name' => $this->input->post('name'),
                'unique_id' => $this->input->post('unique_id'),
                'father_name' => $this->input->post('father_name'),
                'mother_name' => $this->input->post('mother_name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email') ?: null,
                'gender' => $this->input->post('gender'),
                'nid_number' => $this->input->post('nid_number') ?: null,
                'present_address' => $this->input->post('present_address') ?: null,
                'permanent_address' => $this->input->post('permanent_address') ?: null,
                'nationality_id' => $this->input->post('nationality_id') ?: null,
                'religion_id' => $this->input->post('religion_id') ?: null,
                'profession_id' => $this->input->post('profession_id') ?: null,
                'date_of_join' => date('Y-m-d', strtotime($this->input->post('date_of_join'))),
                'no_of_share' => $this->input->post('no_of_share'),
                'amount_per_share' => $this->input->post('amount_per_share'),
                'total_amount' => $this->input->post('total_amount') ?: 0,
                
                'yearly_share_value_increment_rate' => $this->input->post('yearly_share_value_increment_rate') ?: 0,
                'current_share_value' => $this->input->post('current_share_value') ?: $this->input->post('amount_per_share'),
                'picture' => $picture_filename,
                'nid_file' => $nid_filename,
                'name_of_nominee' => $this->input->post('name_of_nominee'),
                'relation_id' => $this->input->post('relation_id') ?: null,
                'nominee_mobile' => $this->input->post('nominee_mobile') ?: null,
                'nominee_email' => $this->input->post('nominee_email') ?: null,
                'nominee_present_address' => $this->input->post('nominee_present_address') ?: null,
                'nominee_parmanent_address' => $this->input->post('nominee_parmanent_address') ?: null,
                'bank_name_id' => $this->input->post('bank_name_id') ?: null,
                'branch_name' => $this->input->post('branch_name') ?: null,
                'account_name' => $this->input->post('account_name') ?: null,
                'account_number' => $this->input->post('account_number') ?: null,
                'ipd_discount' => $this->input->post('ipd_discount') ?: 0,
                'opd_discount' => $this->input->post('opd_discount') ?: 0,
                'test_discount' => $this->input->post('test_discount') ?: 0,
                'emergency_discount' => $this->input->post('emergency_discount') ?: 0,
                'phygiotherapy_discount' => $this->input->post('phygiotherapy_discount') ?: 0,
                'pharmachy_discount' => $this->input->post('pharmachy_discount') ?: 0,
                'sign_in_mobile_no' => $this->input->post('sign_in_mobile_no'),
                'category' => $this->input->post('category')
            );

            // Check if it's an update or insert
            $id = $this->input->post('id');

            if ($id) {
                // Update existing record
                $this->db->where('id', $id);
                $result = $this->db->update('director', $data);
                $message = 'Director updated successfully';
            } else {
                // Insert new record
                $result = $this->db->insert('director', $data);
                $message = 'Director added successfully';
            }

            if ($result) {
                echo json_encode(array(
                    'success' => true,
                    'message' => $message,
                    'unique_id' => $this->input->post('unique_id')
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Failed to save director holder data'
                ));
            }
        } else {
            show_404();
        }
    }

    /**
     * Handle file upload for director holder
     */
    private function upload_director_file($field_name, $upload_path)
    {
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload($field_name)) {
            $upload_data = $this->upload->data();
            return $upload_data['file_name'];
        } else {
            return false;
        }
    }


    public function update_director_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $config['upload_path'] = 'assets/director/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $picture = '';
            $this->upload->do_upload('picture');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            $picture = $sdata['file_name'];
            if ($picture == '') {
                $picture = $this->input->post('old_picture');
            }

            $config['upload_path'] = 'assets/director/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $nid_file = '';
            $this->upload->do_upload('nid_file');
            $sdata = $this->upload->data();
            $nid_file = $sdata['file_name'];
            if ($nid_file == '') {
                $nid_file = $this->input->post('old_nid_file');
            }

            $director_id = $this->input->post('director_id');
            // Get form data
            $data = array(
                'name' => $this->input->post('name'),
                'unique_id' => $this->input->post('unique_id'),
                'father_name' => $this->input->post('father_name'),
                'mother_name' => $this->input->post('mother_name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email') ?: null,
                'gender' => $this->input->post('gender'),
                'nid_number' => $this->input->post('nid_number') ?: null,
                'present_address' => $this->input->post('present_address') ?: null,
                'permanent_address' => $this->input->post('permanent_address') ?: null,
                'nationality_id' => $this->input->post('nationality_id') ?: null,
                'religion_id' => $this->input->post('religion_id') ?: null,
                'profession_id' => $this->input->post('profession_id') ?: null,
                'date_of_join' => $this->input->post('date_of_join') ?: null,
                'no_of_share' => $this->input->post('no_of_share'),
                'amount_per_share' => $this->input->post('amount_per_share'),
                'total_amount' => $this->input->post('total_amount') ?: 0,
                'category' => $this->input->post('category'),
                'yearly_share_value_increment_rate' => $this->input->post('yearly_share_value_increment_rate') ?: 0,
                'current_share_value' => $this->input->post('current_share_value') ?: $this->input->post('amount_per_share'),
                'picture' => $picture,
                'nid_file' => $nid_file,
                'name_of_nominee' => $this->input->post('name_of_nominee'),
                'relation_id' => $this->input->post('relation_id') ?: null,
                'nominee_mobile' => $this->input->post('nominee_mobile') ?: null,
                'nominee_email' => $this->input->post('nominee_email') ?: null,
                'nominee_present_address' => $this->input->post('nominee_present_address') ?: null,
                'nominee_parmanent_address' => $this->input->post('nominee_parmanent_address') ?: null,
                'bank_name_id' => $this->input->post('bank_name_id') ?: null,
                'branch_name' => $this->input->post('branch_name') ?: null,
                'account_name' => $this->input->post('account_name') ?: null,
                'account_number' => $this->input->post('account_number') ?: null,
                'ipd_discount' => $this->input->post('ipd_discount') ?: 0,
                'opd_discount' => $this->input->post('opd_discount') ?: 0,
                'test_discount' => $this->input->post('test_discount') ?: 0,
                'emergency_discount' => $this->input->post('emergency_discount') ?: 0,
                'phygiotherapy_discount' => $this->input->post('phygiotherapy_discount') ?: 0,
                'pharmachy_discount' => $this->input->post('pharmachy_discount') ?: 0,
                'sign_in_mobile_no' => $this->input->post('sign_in_mobile_no')
            );
            $this->db->where('director_id', $director_id)->update('director', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'director Info updated and director Id is=' . $director_id
            );
            $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data updated successfully.');

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function view_director()
    {
        $unique_id = $this->input->post('unique_id');
        $category = $this->input->post('category');
        $name = $this->input->post('name');
        $mobile = $this->input->post('mobile');
        
        $config['base_url'] = base_url() . "index.php/DirectorController/view_director";
        $config['total_rows'] = $this->DirectorModel->count_all_directors($unique_id, $name, $mobile, $category);
        $config['per_page'] = 100;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $choice = $config['total_rows'] / $config['per_page'];

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
        // get books list

        $data['director_data'] = $this->DirectorModel->get_director($config['per_page'], $page, $unique_id, $name, $mobile, $category);
      
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('hrm/director/view_director', $data, true);
        $page_data = array(
            'page_name' => 'hrm/director/view_director',
            'page_title' => 'View Director',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function delete_this_director($director_id)
    {
        $this->db->where('director_id', $director_id)->delete('director');
        $sdata['director_deleted'] = 'Data has been deleted successfully.';
        $this->session->set_userdata($sdata);
        redirect(base_url('view-director'));
    }
    function print_director($director_id)
    {
        $data['director_id'] = $director_id;
        $data['director_data'] = $this->DirectorModel->get_director_by_id($director_id);
        $this->load->view('hrm/director/print_director', $data, true);
        $page_data = array(
            'page_name' => 'hrm/director/print_director',   
            'page_title' => 'Print Director',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_director_again($director_id)
    {
        $data['director_id'] = $director_id;
        $data['director'] = $this->DirectorModel->get_director_by_id($director_id);
        $this->load->view('hrm/director/print_director', $data, true);
        $page_data = array(
            'page_name' => 'hrm/director/print_director',   
            'page_title' => 'Print Director',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    
    /**
     * Export all directorholders to Excel - Basic Format
     */
    public function export_directors_excel()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date);

        // Set headers for Excel download
        $filename = "directors_list_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        // Pass data to view
        $data['director_data'] = $directors;
        $this->load->view('hrm/director/export_directors_excel', $data);
    }

    /**
     * Export comprehensive directorholders data to Excel
     */
    public function export_directors_comprehensive_excel()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';
        $category = $this->input->get('category') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date, $category);

        // Set headers for Excel download
        $filename = "directors_comprehensive_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        // Pass data to view
        $data['director_data'] = $directors;
        $this->load->view('hrm/director/export_directors_comprehensive_excel', $data);
    }

    /**
     * Export directorholders summary to Excel
     */
    public function export_directors_summary_excel()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';
        $category = $this->input->get('category') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date, $category);

        // Set headers for Excel download
        $filename = "directors_summary_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        // Pass data to view
        $data['director_data'] = $directors;
        $this->load->view('hrm/director/export_directors_summary_excel', $data);
    }

    /**
     * Export directorholders financial analysis to Excel
     */
    public function export_directors_financial_excel()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';
        $category = $this->input->get('category') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date, $category);

        // Set headers for Excel download
        $filename = "directors_financial_analysis_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        // Pass data to view
        $data['director_data'] = $directors;
        $this->load->view('hrm/director/export_directors_financial_excel', $data);
    }

    /**
     * Export nominees and bank details to Excel
     */
    public function export_directors_nominee_bank_excel()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date);

        // Set headers for Excel download
        $filename = "directors_nominee_bank_details_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        // Pass data to view
        $data['director_data'] = $directors;
        $this->load->view('hrm/director/export_directors_nominee_bank_excel', $data);
    }

    /**
     * Export all directorholders to PDF
     */
    public function export_directors_pdf()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date);

        // Set headers for PDF download
        $filename = "directorholders_list_" . date('Y-m-d_H-i-s') . ".pdf";
        
        // Pass data to view
        $data['director_data'] = $directors;
        $data['filename'] = $filename;
        
        // Load PDF view
        $this->load->view('hrm/director/export_directors_pdf', $data);
    }

    /**
     * Print all directorholders - optimized for printing
     */
    public function print_directors()
    {
        // Get search parameters if any
        $unique_id = $this->input->get('unique_id') ?: '';
        $from_date = $this->input->get('from_date') ?: '';
        $to_date = $this->input->get('to_date') ?: '';

        // Get all directorholders data
        $directors = $this->DirectorModel->get_director(10000, 0, $unique_id, $from_date, $to_date);

        // Pass data to view
        $data['director_data'] = $directors;
        $this->load->view('hrm/director/print_director_list', $data);
    }

    /**
     * Export Dashboard - Central hub for all export options
     */
    public function export_dashboard()
    {
        $page_data = array(
            'page_name' => 'hrm/director/export_dashboard',
            'page_title' => 'director Export Dashboard',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
