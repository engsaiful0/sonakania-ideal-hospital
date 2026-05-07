<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttendanceController
 *
 * @author Lenovo
 */
class AttendanceController extends CI_Controller
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
    public function save_bulk_attendance_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $data = [];
            $employee_ids = $this->input->post('employee_id');
            $date = $this->input->post('date');
            $in_times = $this->input->post('in_time');
            $out_times = $this->input->post('out_time');
            $working_hours = $this->input->post('working_hours');
            $remarks = $this->input->post('remarks');

            // Loop through employees and prepare data for insertion
            foreach ($employee_ids as $key => $employee_id) {
                $data[] = [
                    'employee_id' => $employee_id,
                    'date' => date('Y-m-d', strtotime($date)),
                    'in_time' => $in_times[$key],
                    'out_time' => $out_times[$key],
                    'working_hours' => $working_hours[$key],
                    'remarks' => $remarks[$key] ?? '',
                    'user_id' => $this->session->userdata('user_id'),
                ];
            }

            // Insert data into the database
            if (!empty($data)) {
                $this->db->insert_batch('attendance', $data); // Use batch insert for multiple rows
            }

            $response = ['success' => true, 'message' => 'Data saved successfully.'];
            echo json_encode($response);
        } else {
            $response = ['error' => true, 'message' => 'Invalid request.'];
            echo json_encode($response);
        }
    }

    public function save_attendance_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $postedDate = $this->input->post('date');
            $date = $postedDate ? date('Y-m-d', strtotime($postedDate)) : null;

            $out_time_am_or_pm = $this->input->post('out_time_am_or_pm');
            $out_time_minute = $this->input->post('out_time_minute');
            $out_time_hour = $this->input->post('out_time_hour');

            $in_time_am_or_pm = $this->input->post('in_time_am_or_pm');
            $in_time_minute = $this->input->post('in_time_minute');
            $in_time_hour = $this->input->post('in_time_hour');
            // echo '<pre>';
            // print_r($this->input->post());

            // Validate required inputs
            if (!$date || !$in_time_hour || !$in_time_minute || !$out_time_hour || !$out_time_minute) {
                $response = array('error' => true, 'message' => 'All time fields and date are required.');
                echo json_encode($response);
                return;
            }

            // Convert "in time" to 24-hour format
            $in_hour_24 = ($in_time_am_or_pm == 'PM' && $in_time_hour != 12) ? $in_time_hour + 12 : $in_time_hour;
            $in_hour_24 = ($in_time_am_or_pm == 'AM' && $in_time_hour == 12) ? 0 : $in_hour_24;

            // Convert "out time" to 24-hour format
            $out_hour_24 = ($out_time_am_or_pm == 'PM' && $out_time_hour != 12) ? $out_time_hour + 12 : $out_time_hour;
            $out_hour_24 = ($out_time_am_or_pm == 'AM' && $out_time_hour == 12) ? 0 : $out_hour_24;

            try {
                // Create DateTime objects
                $in_time = new DateTime("$in_hour_24:$in_time_minute");
                $out_time = new DateTime("$out_hour_24:$out_time_minute");

                // Handle overnight shifts
                if ($out_time < $in_time) {
                    $out_time->modify('+1 day');
                }

                // Calculate the difference
                $interval = $in_time->diff($out_time);

                // Get hours and minutes from the interval
                $working_hours = $interval->h;
                $working_minutes = $interval->i;

                $data = array(
                    'employee_id' => $this->input->post('employee_id'),
                    'date' => $date,
                    'out_time_am_or_pm' => $out_time_am_or_pm,
                    'out_time_minute' => $out_time_minute,
                    'out_time_hour' => $out_time_hour,
                    'in_time_am_or_pm' => $in_time_am_or_pm,
                    'in_time_hour' => $in_time_hour,
                    'in_time_minute' => $in_time_minute,
                    'remarks' => $this->input->post('remarks'),
                    'working_hours' => $working_hours,
                    'working_minutes' => $working_minutes,
                    'user_id' => $this->session->userdata('user_id'),
                );

                // Insert into the database
                if ($this->db->insert('attendance', $data)) {
                    $response = array('success' => true, 'message' => 'Data saved successfully.');
                } else {
                    $response = array('error' => true, 'message' => 'Failed to save data. Please try again.');
                }
            } catch (Exception $e) {
                $response = array('error' => true, 'message' => 'Error calculating working hours: ' . $e->getMessage());
            }

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function  delete_this_attendance($attendance_id)
    {
        $delete_update = array('is_deleted' => 1);
        $this->db->where('attendance_id', $attendance_id)
            ->update('attendance', $delete_update);


        $sdata['deleted'] = 'Data has been deleted successfully.';
        $this->session->set_userdata($sdata);
        redirect(base_url('view-attendance'));
    }


    function edit_single_attendance($attendance_id)
    {
        $data['attendance_id'] = $attendance_id;
        $this->load->view('attendance/edit_single_attendance', $data, true);
        $page_data = array(
            'page_name' => 'attendance/edit_single_attendance',
            'page_title' => 'Edit Attendance',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function update_attendance_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $postedDate = $this->input->post('date');
            $date = '';
            if ($postedDate) {
                $date = date('Y-m-d', strtotime($postedDate));
            }
            $in_time = $this->input->post('old_in_time');
            $out_time = $this->input->post('old_out_time');
            // Assuming this code is within your controller method where you handle the form submission
            if ($this->input->post('in_time') != '') {
                $in_time = $this->input->post('in_time');
            }
            if ($this->input->post('out_time') != '') {
                $out_time = $this->input->post('out_time');
            }

            $attendance_id = $this->input->post('attendance_id');
            // Assuming this code is within your controller method where you handle the form submission
            $out_time_am_or_pm = $this->input->post('out_time_am_or_pm');
            $out_time_minute = $this->input->post('out_time_minute');
            $out_time_hour = $this->input->post('out_time_hour');

            $in_time_am_or_pm = $this->input->post('in_time_am_or_pm');
            $in_time_minute = $this->input->post('in_time_minute');
            $in_time_hour = $this->input->post('in_time_hour');

            // Convert "in time" to 24-hour format
            $in_hour_24 = ($in_time_am_or_pm == 'PM' && $in_time_hour != 12) ? $in_time_hour + 12 : $in_time_hour;
            $in_hour_24 = ($in_time_am_or_pm == 'AM' && $in_time_hour == 12) ? 0 : $in_hour_24;

            // Convert "out time" to 24-hour format
            $out_hour_24 = ($out_time_am_or_pm == 'PM' && $out_time_hour != 12) ? $out_time_hour + 12 : $out_time_hour;
            $out_hour_24 = ($out_time_am_or_pm == 'AM' && $out_time_hour == 12) ? 0 : $out_hour_24;

            // Create DateTime objects
            $in_time = new DateTime("$in_hour_24:$in_time_minute");
            $out_time = new DateTime("$out_hour_24:$out_time_minute");

            // Calculate the difference
            $interval = $in_time->diff($out_time);

            // Get hours and minutes from the interval
            $working_hours = $interval->h;
            $working_minutes = $interval->i;

            $data = array(
                'employee_id' => $this->input->post('employee_id'),
                'date' => $date,
                'out_time_am_or_pm' => $out_time_am_or_pm,
                'out_time_minute' => $out_time_minute,
                'out_time_hour' => $out_time_hour,
                'in_time_am_or_pm' => $in_time_am_or_pm,
                'in_time_hour' => $in_time_hour,
                'in_time_minute' => $in_time_minute,
                'remarks' => $this->input->post('remarks'),
                'working_hours' => $working_hours,
                'working_minutes' =>  $working_minutes,
                'user_id' => $this->session->userdata('user_id'),
            );

            $this->db->where('attendance_id', $attendance_id)->update('attendance', $data);
            $response = array('success' => true, 'message' => 'Data updated successfully.');
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function add_attendance()
    {
        $page_data = array(
            'page_name' => 'attendance/add_attendance',
            'page_title' => 'Add Attendance',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function bulk_attendance()
    {
        $page_data = array(
            'page_name' => 'attendance/bulk_attendance',
            'page_title' => 'Bulk Attendance',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function all_attendance()
    {
        $page_data = array(
            'page_name' => 'attendance/all_attendance',
            'page_title' => 'All Attendance',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_attendance()
    {

        $employee_id = $this->input->post('employee_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $to_date = '';
        $from_date = '';
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        $config['base_url'] = base_url() . "index.php/AttendanceController/view_attendance";;
        $config['total_rows'] = $this->AttendanceModel->count_all_attendance($employee_id, $from_date, $to_date);
        $config['per_page'] = 20;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

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

        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['attendance_data'] = $this->AttendanceModel->get_attendane($config["per_page"], $page, $employee_id, $from_date, $to_date);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('attendance/view_attendance', $data, true);

        $page_data = array(
            'page_name' => 'attendance/view_attendance',
            'page_title' => 'Add Attendance',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
