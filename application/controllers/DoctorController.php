<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BankController
 *
 * @author saiful
 */
class DoctorController extends CI_Controller
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
        $this->load->library('pagination');
    }

    public function index()
    {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }
    public function doctor_dashboard()
    {
        $page_data = array(
            'page_name' => 'doctor/doctor_dashboard',
            'page_title' => 'Add Dashboard',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_doctor()
    {
        $page_data = array(
            'page_name' => 'doctor/add_doctor',
            'page_title' => 'Add Doctor',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_doctor_save()
    {
        $doctor_id = $this->input->post('doctor_id');
        if ($this->input->is_ajax_request()) {

            $data['doctor_name'] = $this->input->post('doctor_name');
            $data['doctor_unique_id'] = $this->input->post('doctor_unique_id');
            $data['degree'] = $this->input->post('degree');
            $data['nationality_id'] = $this->input->post('nationality_id');
            $data['fathers_name'] = $this->input->post('fathers_name');
            $data['mothers_name'] = $this->input->post('mothers_name');
            $data['marital_status'] = $this->input->post('marital_status');
            $data['spouse_name'] = $this->input->post('spouse_name');
            $data['phone_home'] = $this->input->post('phone_home');
            $data['mobile'] = $this->input->post('mobile');
            $data['phone_office'] = $this->input->post('phone_office');
            $data['email_personal'] = $this->input->post('email_personal');
            $data['oficial_email'] = $this->input->post('oficial_email');
            $data['department_id'] = $this->input->post('department_id');
            $data['job_title'] = $this->input->post('job_title');
            $data['joining_date'] = date('Y-m-d', strtotime($this->input->post('joining_date')));
            $data['gender'] = $this->input->post('gender');
            $data['remarks'] = $this->input->post('remarks');
            $data['age'] = $this->input->post('age');
            $data['new_patient_fee'] = $this->input->post('new_patient_fee');
            $data['within_seven_days_visiting_fee'] = $this->input->post('within_seven_days_visiting_fee');
            $data['within_fifteen_days_visiting_fee'] = $this->input->post('within_fifteen_days_visiting_fee');
            $data['within_thirty_days_visiting_fee'] = $this->input->post('within_thirty_days_visiting_fee');
            $data['day_care_fee'] = $this->input->post('day_care_fee');
            $data['staff_report_fee'] = $this->input->post('staff_report_fee');
            $data['first_follow_up_fee'] = $this->input->post('first_follow_up_fee');
            $data['second_follow_up_fee'] = $this->input->post('second_follow_up_fee');
            $data['emr_fee'] = $this->input->post('emr_fee');

            $data['ipd_commission_percentage'] = $this->input->post('ipd_commission_percentage');
            $data['opd_patient_percentage'] = $this->input->post('opd_patient_percentage');
            $data['lab_percentage'] = $this->input->post('lab_percentage');
            if (isset($_POST['all_doctor_type'])) {
                $data['all_doctor_type'] = 'Yes';
            }
            if (isset($_POST['reporting_doctor_type_all'])) {
                $data['reporting_doctor_type_all'] = 'Yes';
            }

            if (isset($_POST['internal_doctor'])) {
                $data['internal_doctor'] = 'Yes';
            }

            if (isset($_POST['external_doctor'])) {
                $data['external_doctor'] = 'Yes';
            }
            if (isset($_POST['referral_doctor'])) {
                $data['referral_doctor'] = 'Yes';
            }
            if (isset($_POST['corporate_house_doctor'])) {
                $data['corporate_house_doctor'] = 'Yes';
            }
            if (isset($_POST['primary_doctor'])) {
                $data['primary_doctor'] = 'Yes';
            }
            if (isset($_POST['surgeon'])) {
                $data['surgeon'] = 'Yes';
            }
            if (isset($_POST['anaestesiologist'])) {
                $data['anaestesiologist'] = 'Yes';
            }
            if (isset($_POST['opd_consultation'])) {
                $data['opd_consultation'] = 'Yes';
            }
            if (isset($_POST['ipd_consultation'])) {
                $data['ipd_consultation'] = 'Yes';
            }
            if (isset($_POST['emr_consultation'])) {
                $data['emr_consultation'] = 'Yes';
            }


            if (isset($_POST['lab_technician'])) {
                $data['lab_technician'] = 'Yes';
            }
            if (isset($_POST['medical_technologist'])) {
                $data['medical_technologist'] = 'Yes';
            }
            if (isset($_POST['senior_warrent_officer'])) {
                $data['senior_warrent_officer'] = 'Yes';
            }


            $config['upload_path'] = 'assets/doctor_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $picture = 0;
            $this->upload->do_upload('picture');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            $picture = $sdata['file_name'];
            if ($picture == '') {
                $data['picture'] = $this->input->post('picture_edit');;
            } else {
                $data['picture'] = $picture;
            }
            $config['upload_path'] = 'assets/doctor_signature/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $signature = 0;
            $this->upload->do_upload('signature');
            $sdata = $this->upload->data();
            $signature = $sdata['file_name'];
            if ($signature == '') {
                $data['signature'] = $this->input->post('signature_edit');
            } else {
                $data['signature'] = $signature;
            }

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Doctor updated and Doctor Id is=' . $doctor_id
            );
            $this->db->insert('activity_log', $activity_data);

            $this->db->where('doctor_id', $doctor_id)->update('doctor', $data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_doctor_id'] = $doctor_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function add_doctor_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $data['doctor_name'] = $this->input->post('doctor_name');
            $data['doctor_unique_id'] = $this->input->post('doctor_unique_id');
            $data['degree'] = $this->input->post('degree');
            $data['nationality_id'] = $this->input->post('nationality_id');
            $data['fathers_name'] = $this->input->post('fathers_name');
            $data['mothers_name'] = $this->input->post('mothers_name');
            $data['marital_status'] = $this->input->post('marital_status');
            $data['spouse_name'] = $this->input->post('spouse_name');
            $data['phone_home'] = $this->input->post('phone_home');
            $data['mobile'] = $this->input->post('mobile');
            $data['phone_office'] = $this->input->post('phone_office');
            $data['email_personal'] = $this->input->post('email_personal');
            $data['oficial_email'] = $this->input->post('oficial_email');
            $data['department_id'] = $this->input->post('department_id');
            $data['job_title'] = $this->input->post('job_title');
            $data['joining_date'] = date('Y-m-d', strtotime($this->input->post('joining_date')));
            $data['gender'] = $this->input->post('gender');
            $data['remarks'] = $this->input->post('remarks');
            $data['age'] = $this->input->post('age');
            $data['new_patient_fee'] = $this->input->post('new_patient_fee');
            $data['within_seven_days_visiting_fee'] = $this->input->post('within_seven_days_visiting_fee');
            $data['within_fifteen_days_visiting_fee'] = $this->input->post('within_fifteen_days_visiting_fee');
            $data['within_thirty_days_visiting_fee'] = $this->input->post('within_thirty_days_visiting_fee');
            $data['day_care_fee'] = $this->input->post('day_care_fee');
            $data['staff_report_fee'] = $this->input->post('staff_report_fee');
            $data['first_follow_up_fee'] = $this->input->post('first_follow_up_fee');
            $data['second_follow_up_fee'] = $this->input->post('second_follow_up_fee');
            $data['emr_fee'] = $this->input->post('emr_fee');
            $data['ipd_commission_percentage'] = $this->input->post('ipd_commission_percentage');
            $data['opd_patient_percentage'] = $this->input->post('opd_patient_percentage');
            $data['lab_percentage'] = $this->input->post('lab_percentage');
            $data['user_id'] = $this->session->userdata('user_id');
            if (isset($_POST['all_doctor_type'])) {
                $data['all_doctor_type'] = 'Yes';
            }
            if (isset($_POST['reporting_doctor_type_all'])) {
                $data['reporting_doctor_type_all'] = 'Yes';
            }

            if (isset($_POST['internal_doctor'])) {
                $data['internal_doctor'] = 'Yes';
            }

            if (isset($_POST['external_doctor'])) {
                $data['external_doctor'] = 'Yes';
            }
            if (isset($_POST['referral_doctor'])) {
                $data['referral_doctor'] = 'Yes';
            }
            if (isset($_POST['corporate_house_doctor'])) {
                $data['corporate_house_doctor'] = 'Yes';
            }
            if (isset($_POST['primary_doctor'])) {
                $data['primary_doctor'] = 'Yes';
            }
            if (isset($_POST['surgeon'])) {
                $data['surgeon'] = 'Yes';
            }
            if (isset($_POST['anaestesiologist'])) {
                $data['anaestesiologist'] = 'Yes';
            }
            if (isset($_POST['opd_consultation'])) {
                $data['opd_consultation'] = 'Yes';
            }
            if (isset($_POST['ipd_consultation'])) {
                $data['ipd_consultation'] = 'Yes';
            }
            if (isset($_POST['emr_consultation'])) {
                $data['emr_consultation'] = 'Yes';
            }


            if (isset($_POST['lab_technician'])) {
                $data['lab_technician'] = 'Yes';
            }
            if (isset($_POST['medical_technologist'])) {
                $data['medical_technologist'] = 'Yes';
            }
            if (isset($_POST['senior_warrent_officer'])) {
                $data['senior_warrent_officer'] = 'Yes';
            }


            $config['upload_path'] = 'assets/doctor_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $picture = 0;
            $this->upload->do_upload('picture');
            //echo '<pre>';
            // print_r($this->upload->do_upload('picture'));
            $sdata = $this->upload->data();
            $picture = $sdata['file_name'];
            $data['picture'] = $picture;

            $config['upload_path'] = 'assets/doctor_signature/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $signature = 0;
            $this->upload->do_upload('signature');
            $sdata = $this->upload->data();
            $signature = $sdata['file_name'];
            $data['signature'] = $signature;


            $this->db->insert('doctor', $data);
            $doctor_id = $this->db->insert_id();

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Doctor saved and Doctor Id is=' . $doctor_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_doctor_id'] = $doctor_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function doctor_reference_payment_report()
    {
        $page_data = array(
            'page_name' => 'doctor/doctor_reference_payment_report',
            'page_title' => 'Doctor Reference Payment Report',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_payment_edit($doctor_commission_payment_id)
    {
        $data['doctor_commission_payment_id'] = $doctor_commission_payment_id;
        $this->load->view('doctor/doctor_payment_edit', $data);
    }

    public function doctors_payment()
    {
        $page_data = array(
            'page_name' => 'doctor/doctors_payment',
            'page_title' => 'Add Doctor Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_payment_delete($doctor_commission_payment_id)
    {
        $data = array('is_deleted' => '1');
        $this->db->where('doctor_commission_payment_id', $doctor_commission_payment_id)->update('doctor_commission_payment', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);

        $doctor_id = '';
        $config['base_url'] = site_url('DoctorController/view_doctors_payment');
        $config['total_rows'] = $this->db->count_all('doctor_commission_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->doctors_payment_details($config["per_page"], $data['page'], $doctor_id);

        $data['pagination'] = $this->pagination->create_links();



        $this->load->view('doctor/view_doctors_payment', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctors_payment',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_edit($doctor_id)
    {
        $data['doctor_id'] = $doctor_id;
        $this->load->view('doctor/doctor_edit', $data, true);
        $page_data = array(
            'page_name' => 'doctor/doctor_edit',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function delete_this_doctor($doctor_id)
    {
        $update = array(
            'is_deleted' => '1'
        );
        $this->db->where('doctor_id', $doctor_id)->update('doctor', $update);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);
        redirect('DoctorController/view_doctor');
    }
    public function delete_this_doctor_ajax()
    {
        $doctor_id = $this->input->post('doctor_id');
        if ($this->db->where('doctor_id', $doctor_id)->delete('doctor')) {
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    public function view_doctor()
  {
      $doctor_id = $this->input->post('doctor_id');
      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      // Pagination configuration
      $config['base_url'] = base_url("index.php/DoctorController/view_doctor");
      $config['total_rows'] = $this->DoctorModel->count_doctor($doctor_id);
      $config['per_page'] = 100;
      $config['uri_segment'] = 3;
      $choice = $config['total_rows'] / $config['per_page'];
      $config['num_links'] = floor($choice);

      // Bootstrap pagination styling
      $config['full_tag_open'] = "<ul class='pagination'>";
      $config['full_tag_close'] = '</ul>';
      $config['num_tag_open'] = '<li>';
      $config['num_tag_close'] = '</li>';
      $config['cur_tag_open'] = '<li class="active"><a href="#">';
      $config['cur_tag_close'] = '</a></li>';
      $config['first_tag_open'] = '<li>';
      $config['first_tag_close'] = '</li>';
      $config['last_tag_open'] = '<li>';
      $config['last_tag_close'] = '</li>';
      $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i> Previous Page';
      $config['prev_tag_open'] = '<li>';
      $config['prev_tag_close'] = '</li>';
      $config['next_link'] = 'Next Page <i class="fa fa-long-arrow-right"></i>';
      $config['next_tag_open'] = '<li>';
      $config['next_tag_close'] = '</li>';

      $this->pagination->initialize($config);

      // Fetch data from the model
      $data['detailsList'] = $this->DoctorModel->doctors_details($config['per_page'], $page, $doctor_id);
      $data['pagination'] = $this->pagination->create_links();

      // Optional extra metadata for layout
      $this->load->view('doctor/view_doctor', $data, true);
      $page_data = array(
          'page_name' => 'doctor/view_doctor',
          'page_title' => 'View Doctors ',
          'sidebar' => 'hrm/hrm_sidebar'
      );
      $this->load->view('content', $page_data);
  }

    public function view_doctor_old()
    {
        $doctor_id = $this->input->post('doctor_id');

        $config['base_url'] = base_url() . "index.php/DoctorController/view_doctor";
        $config['total_rows'] = $this->DoctorModel->count_doctor($doctor_id);
        $config['per_page'] = 100;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
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


        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // get books list

        // get books list
        $data['detailsList'] = $this->DoctorModel->doctors_details($config["per_page"], $page, $doctor_id);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('doctor/view_doctor', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctor',
            'page_title' => 'View Doctors ',
            'sidebar' => 'hrm/hrm_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_doctors_payment()
    {
        $doctor_id = $this->input->post('doctor_id');
        $config['base_url'] = site_url('DoctorController/view_doctors_payment');
        $config['total_rows'] = $this->db->count_all('doctor_commission_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->doctors_payment_details($config["per_page"], $data['page'], $doctor_id);

        $data['pagination'] = $this->pagination->create_links();



        $this->load->view('doctor/view_doctors_payment', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctors_payment',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function due_commission_history_load()
    {
        error_reporting(0);
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db->where('doctor_id', $doctor_id)
            ->get('doctor')
            ->row();
        $commission_rate = explode('%', $doctor->commission);
        $commission_rate = $commission_rate[0];
        $doctor_commission_payment = $this->db->select_sum('paid_amount', 'paid_amount')
            ->where('doctor_id', $doctor_id)
            ->where('is_deleted', '0')
            ->get('doctor_commission_payment')->result();
        $total_commission_paid = $doctor_commission_payment[0]->paid_amount;

        $patient_test_entry = $this->db
            ->where('is_deleted', '0')
            ->where('doctor_id', $doctor_id)
            ->get('patient_test_entry')->result();
        $grand_commission = 0;
        foreach ($patient_test_entry as $value) {
            $grand_commission += $value->net_total * ($commission_rate / 100);
        }

        echo $grand_commission - $total_commission_paid;
    }

    public function edit_doctor_payment_save()
    {
        $doctor_commission_payment_id = $this->input->post('doctor_commission_payment_id');
        $data = array();
        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['cash_or_bank'] = $this->input->post('cash_or_bank');
        $data['bank_id'] = $this->input->post('bank_id');
        $data['total_due_commission'] = $this->input->post('total_due_commission');
        $data['current_due_commission'] = $this->input->post('current_due_commission');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->where('doctor_commission_payment_id', $doctor_commission_payment_id)->update('doctor_commission_payment', $data);

        $sdata['update'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $doctor_id = '';
        $config['base_url'] = site_url('DoctorController/view_doctors_payment');
        $config['total_rows'] = $this->db->count_all('doctor_commission_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->doctors_payment_details($config["per_page"], $data['page'], $doctor_id);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('doctor/view_doctors_payment', $data, true);
        $page_data = array(
            'page_name' => 'doctor/view_doctors_payment',
            'page_title' => 'View Doctors Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_doctor_payment_save()
    {
        $doctor_id = $this->input->post('doctor_id');
        $data = array();
        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['cash_or_bank'] = $this->input->post('cash_or_bank');
        $data['bank_id'] = $this->input->post('bank_id');
        $data['total_due_commission'] = $this->input->post('total_due_commission');
        $data['current_due_commission'] = $this->input->post('current_due_commission');
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->insert('doctor_commission_payment', $data);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'doctor/doctors_payment',
            'page_title' => 'Add Due Payment',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_doctor_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('doctor');
        $crud->set_subject('Doctor');
        $crud->required_fields('name');
        $crud->columns('name', 'unique_id', 'doctorspecilization_id', 'visit_fee', 'address', 'commission', 'description', 'picture');
        $crud->fields('name', 'unique_id', 'doctorspecilization_id', 'visit_fee', 'address', 'commission', 'description', 'picture');
        $crud->set_relation('doctorspecilization_id', 'doctorspecilization', 'specilization');
        $crud->callback_add_field('unique_id', function () {
            $uniqu_id = $this->db->select('*')->get('doctor_unique_id_table');
            $unique_id = 'EM' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
            $id = array(
                'unique_id' => $unique_id
            );
            $this->db->insert('doctor_unique_id_table', $id);
            return '<input type="text" maxlength="50" value="' . $unique_id . '" name="unique_id"  readonly>';
        });
        $crud->display_as('doctorspecilization_id', 'Specialization');
        $crud->set_field_upload('picture', 'assets/doctor');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function bank_deposit_report()
    {
        $page_data = array(
            'page_name' => 'bank/bank_deposit_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'bank/bank_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_test_reference_report()
    {

        $page_data = array(
            'page_name' => 'report/doctor_test_reference_report',
            'page_title' => 'Doctors Test Reference Report ',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_test_referece_report_load($ids)
    {
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['doctor_id'] = $ids_array[2];
        $this->load->view('report/doctor_test_referece_report_load_details', $data);
    }

    public function bank_deposit_report_details($ids)
    {
        $data = explode("_", $ids);
        $data['bank_id'] = $data[2];
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $this->load->view('bank/bank_deposit_report_details', $data);
    }

    public function bank_withdraw_report()
    {
        $page_data = array(
            'page_name' => 'bank/bank_withdraw_report',
            'page_title' => 'Bank Deposit Report ',
            'sidebar' => 'bank/bank_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_withdraw_report_details($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['bank_id'] = $data[2];
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $this->load->view('bank/bank_withdraw_report_details', $data);
    }
}
