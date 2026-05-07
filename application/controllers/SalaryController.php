<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class SalaryController extends CI_Controller
{
    private $per_page;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    public function working_day_load()
    {
        $month_id = $this->input->post('month_id'); // more secure and CI-friendly

        $month = $this->db->select('number_of_working_days')
            ->from('month')
            ->where('month_id', $month_id)
            ->get()
            ->row();

        if ($month) {
            echo $month->number_of_working_days;
        } else {
            echo 0; // or an error message
        }
    }

  
    function add_salary()
    {
        $page_data = array(
            'page_name' => 'salary/add_salary',
            'page_title' => 'Add Salary',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_salary()
    {

        $month_id = $this->input->post('month_id') ?: '';
        $year_id = $this->input->post('year_id') ?: '';

        $config = array();


        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Configure pagination
        $config['base_url'] = base_url() . "index.php/SalaryController/view_salary";
        $config["total_rows"] = $this->SalaryModel->count_all_salary($month_id, $year_id);
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
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
        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Fetch data with limit and offset
        $data['detailsList'] = $this->SalaryModel->get_salary_details($this->per_page, $page, $month_id, $year_id);

        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $data['page_name'] = 'salary/view_salary';
        $data['page_title'] = 'View Salary';
        $data['sidebar'] = 'hrm/hrm_sidebar';
        $this->load->view('content', $data);
    }

    function edit_salary($salary_id)
    {
        $data['salary_id'] = $salary_id;
        $this->load->view('salary/edit_salary', $data, TRUE);
        $page_data = array(
            'page_name' => 'salary/edit_salary',
            'page_title' => 'Salary Edit',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_salary()
    {
        $page_data = array(
            'page_name' => 'salary/print_salary',
            'page_title' => 'Print Salary',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_salary_again($salary_id)
    {
        $data['salary_id'] = $salary_id;
        $sdata['print_salary_id'] = $salary_id;
        $this->session->set_userdata($sdata);
        $this->load->view('salary/print_salary', $data, TRUE);
        $page_data = array(
            'page_name' => 'salary/print_salary',
            'page_title' => 'Print Salary',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function salary_data_save()
    {
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('max_input_time', 300);
        ini_set('memory_limit', '256M');

        if ($this->input->is_ajax_request()) {
            $sales = array(
                'year_id' => $this->input->post('year_id'),
                'net_payable' => $this->input->post('net_payable'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'month_id' => $this->input->post('month_id'),
                'user_id' => $this->session->userdata('user_id'),
            );

            $insert = $this->db->insert('salaries', $sales);
            if ($insert)
                $salary_id  = $this->db->insert_id();

            $salary_details = array();

            $employee_id = $this->input->post('employee_id');
            $gross_salary = $this->input->post('gross_salary');
            $working_days = $this->input->post('working_days');
            $daily_salary = $this->input->post('daily_salary');
            $over_time = $this->input->post('over_time');
            $ot_allowance = $this->input->post('ot_allowance');
            $other_allowance = $this->input->post('other_allowance');
            $total_salary = $this->input->post('total_salary');
            $absent = $this->input->post('absent');
            $late_day = $this->input->post('late_day');
            $deduction = $this->input->post('deduction');
            $payable = $this->input->post('payable');

            for ($loop = 0; $loop < count($employee_id); $loop++) {
                $salary_details[] = array(
                    'salary_id' => $salary_id,
                    'gross_salary' => $gross_salary[$loop],
                    'employee_id' => $employee_id[$loop],
                    'working_days' => $working_days[$loop],
                    'daily_salary' => $daily_salary[$loop],
                    'over_time' => $over_time[$loop],
                    'ot_allowance' => $ot_allowance[$loop],
                    'other_allowance' => $other_allowance[$loop],
                    'total_salary' => $total_salary[$loop],
                    'absent' => $absent[$loop],
                    'late_day' => $late_day[$loop],
                    'deduction' => $deduction[$loop],
                    'payable' => $payable[$loop],
                    'date' =>  date('Y-m-d', strtotime($this->input->post('date'))),
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            if ($insert) {
                $insert = $this->db->insert_batch('salary_details', $salary_details);
            }
            $sdata['print_salary_id'] = $salary_id;
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['salary_saved'] = 'saved successully';
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function salary_edit_save()
    {
        if ($this->input->is_ajax_request()) {
            $salary_id = $this->input->post('salary_id');


            $salary = array(
                'year_id' => $this->input->post('year_id'),
                'net_payable' => $this->input->post('net_payable'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'month_id' => $this->input->post('month_id'),
            );
            $insert = $this->db->where('salary_id', $salary_id)->update('salaries', $salary);
            //Delete the old data of details
            $this->db->where('salary_id', $salary_id)->delete('salary_details');


            $salary_details = array();

            $employee_id = $this->input->post('employee_id');
            $gross_salary = $this->input->post('gross_salary');
            $working_days = $this->input->post('working_days');
            $daily_salary = $this->input->post('daily_salary');
            $over_time = $this->input->post('over_time');
            $ot_allowance = $this->input->post('ot_allowance');
            $other_allowance = $this->input->post('other_allowance');
            $total_salary = $this->input->post('total_salary');
            $absent = $this->input->post('absent');
            $late_day = $this->input->post('late_day');
            $deduction = $this->input->post('deduction');
            $payable = $this->input->post('payable');

            for ($loop = 0; $loop < count($employee_id); $loop++) {
                $salary_details[] = array(
                    'salary_id' => $salary_id,
                    'gross_salary' => $gross_salary[$loop],
                    'employee_id' => $employee_id[$loop],
                    'working_days' => $working_days[$loop],
                    'daily_salary' => $daily_salary[$loop],
                    'over_time' => $over_time[$loop],
                    'ot_allowance' => $ot_allowance[$loop],
                    'other_allowance' => $other_allowance[$loop],
                    'total_salary' => $total_salary[$loop],
                    'absent' => $absent[$loop],
                    'late_day' => $late_day[$loop],
                    'deduction' => $deduction[$loop],
                    'payable' => $payable[$loop],
                    'date' =>  date('Y-m-d', strtotime($this->input->post('date'))),
                    'user_id' => $this->session->userdata('user_id'),
                );
            }
            if ($insert) {
                $insert = $this->db->insert_batch('salary_details', $salary_details);
            }
            $sdata['print_salary_id'] = $salary_id;

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $this->session->set_userdata($sdata);
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }


    public function salary_delete_ajax()
    {
        $salary_id = $this->input->post('salary_id');
        if ($this->db->where('salary_id', $salary_id)->delete('salaries')) {
            $this->db->where('salary_id', $salary_id)->delete('salary_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
}
