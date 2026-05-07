<?php

class PrescriptionController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
              //redirect('LoginController');
        }
        $this->load->library('pagination');
    }

    public function index() {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null) {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }
    
    public function prescription_dashboard() {
        $page_data = array(
            'page_name' => 'prescription/prescription_dashboard',
            'page_title' => 'Add Prescription',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    

    public function load_product_row() {
        $data['id'] = $_POST['id'];
        $this->load->view('prescription/load_product_row', $data);
    }

    public function load_advice_row() {
        $data['id'] = $_POST['id'];
        $this->load->view('prescription/load_advice_row', $data);
    }

    public function load_diagnosis_row() {
        $data['id'] = $_POST['id'];
        $this->load->view('prescription/load_diagnosis_row', $data);
    }

    
    public function add_cabin_category() {
        $page_data = array(
            'page_name' => 'patient/add_cabin_category',
            'page_title' => 'Add Cabin Category',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_cabin_category_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('cabin_category');
        $crud->set_subject('Cabin Category');
       
        $crud->required_fields('cabin_category_name');
        $crud->columns('cabin_category_name');
        $crud->fields('cabin_category_name');
        $crud->display_as('cabin_category_name', 'Cabin Category Number');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_prescription_header() {
        $page_data = array(
            'page_name' => 'prescription/add_prescription_header',
            'page_title' => 'Add Patient Header',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_prescription_header_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('prescription_header');
        $crud->set_subject('Prescription Header');
        
        $crud->required_fields('doctors_name_in_english', 'doctors_name_in_bangla', 'degree1_in_bangla', 'degree1_in_english', 'degree2_in_bangla', 'degree2_in_english');
        $crud->columns('doctors_name_in_english', 'doctors_name_in_bangla', 'degree1_in_bangla', 'degree1_in_english', 'degree2_in_bangla', 'degree2_in_english');
        $crud->fields('doctors_name_in_english', 'doctors_name_in_bangla', 'degree1_in_bangla', 'degree1_in_english', 'degree2_in_bangla', 'degree2_in_english');
        $crud->display_as('doctors_name_in_english', "Doctor's Name in English");
        $crud->display_as('doctors_name_in_bangla', "Doctor's Name in Bangla");
        $crud->display_as('degree1_in_bangla', "Degree One in Bangla");
        $crud->display_as('degree1_in_english', "Degree One in English");
        $crud->display_as('degree2_in_bangla', "Degree Two in Bangla");
        $crud->display_as('degree2_in_english', "Degree Two in English");
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_general_bed() {
        $page_data = array(
            'page_name' => 'patient/add_general_bed',
            'page_title' => 'Add Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_general_bed_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('general_bed');
        $crud->set_subject('General Bed');
       
        $crud->required_fields('general_bed_number', 'general_bed_rent');
        $crud->columns('general_bed_number', 'general_bed_rent', 'status');
        $crud->fields('general_bed_number', 'general_bed_rent');
        $crud->display_as('general_bed_number', 'General Bed Number');
        $crud->display_as('general_bed_rent', 'Per Day Rent');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_patient() {
        $page_data = array(
            'page_name' => 'patient/add_patient',
            'page_title' => 'Add Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_expertise_frame() {
        $crud = new Grocery_crud();
        $crud->set_table('doctorspecilization');
        $crud->set_subject('Doctor Specialization');
       
        $crud->required_fields('specilization');
        $crud->columns('specilization');
        $crud->fields('specilization');
        $crud->display_as('specilization', 'Doctor Specialization');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_doctor() {
        $page_data = array(
            'page_name' => 'doctor/add_doctor',
            'page_title' => 'Add Doctor',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_reference_payment_report() {
        $page_data = array(
            'page_name' => 'doctor/doctor_reference_payment_report',
            'page_title' => 'Doctor Reference Payment Report',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_payment_edit($doctor_commission_payment_id) {
        $data['doctor_commission_payment_id'] = $doctor_commission_payment_id;
        $this->load->view('doctor/doctor_payment_edit', $data);
    }

    public function add_prescription() {
        $page_data = array(
            'page_name' => 'prescription/add_prescription',
            'page_title' => 'Add Prescription',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function delete_this_prescription($prescription_id) {
        $data = array('is_deleted' => '1');
        $this->db->where('prescription_id', $prescription_id)->update('prescription', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);


        $invoice_no = $this->input->post('invoice_no');
        $patient_name = $this->input->post('patient_name');
        $gender = $this->input->post('gender');
        $date = '';
        if ($this->input->post('date') != '') {
            $date = date('Y-m-d', strtotime($this->input->post('date')));
        }
        $config['base_url'] = site_url('PrescriptionController/view_prescription');
        $config['total_rows'] = $this->db->count_all('prescription');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->prescription_details($config["per_page"], $data['page'], $patient_name, $gender, $date,$invoice_no);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('prescription/view_prescription', $data, true);
        $page_data = array(
            'page_name' => 'prescription/view_prescription',
            'page_title' => 'View Prescription',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_prescription() {
        $patient_name = $this->input->post('patient_name');
        $gender = $this->input->post('gender');
        $invoice_no = $this->input->post('invoice_no');

        $date = '';
        if ($this->input->post('date') != '') {
            $date = date('Y-m-d', strtotime($this->input->post('date')));
        }
        $config['base_url'] = site_url('PrescriptionController/view_prescription');
        $config['total_rows'] = $this->db->count_all('prescription');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->prescription_details($config["per_page"], $data['page'], $patient_name, $gender, $date, $invoice_no);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('prescription/view_prescription', $data, true);
        $page_data = array(
            'page_name' => 'prescription/view_prescription',
            'page_title' => 'View Prescription',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function patient_edit($patient_id) {
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/patient_edit', $data);
    }

    public function edit_patient_save() {
        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $data = array();
        $data['patient_unique_id'] = $this->input->post('patient_unique_id');
        $this->db->insert('patient_unique_table', $data);

        $data = array();
        /*
          To bolock the sead start
         */
        $cabin_id = $this->input->post('cabin_id');
        if ($cabin_id != '') {
            $booked = array(
                'status' => 'Not Available'
            );
            $this->db->where('cabin_id', $cabin_id)->update('cabin', $booked);
        }
        $general_bed_id = $this->input->post('general_bed_id');
        if ($general_bed_id != '') {
            $booked = array(
                'status' => 'Not Available'
            );
            $this->db->where('general_bed_id', $general_bed_id)->update('general_bed', $booked);
        }
        /*
          To bolock the sead End
         */
        $data['patient_name'] = $this->input->post('patient_name');
        $data['mobile_number'] = $this->input->post('mobile_number');
        $data['patient_unique_id'] = $this->input->post('patient_unique_id');
        $data['gender'] = $this->input->post('gender');

        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['reference_media_id'] = $this->input->post('reference_media_id');
        $data['general_bed_id'] = $this->input->post('general_bed_id');
        $data['cabin_id'] = $this->input->post('cabin_id');
        $data['admission_time'] = $this->input->post('admission_time');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['patient_unique_id'] = $this->input->post('patient_unique_id');

        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));

        $this->db->where('patient_id', $patient_id)->update('patient', $data);
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/print_patient_admission', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'patient/print_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_prescription_save() {
        $data = array();
        $data['patient_name'] = $this->input->post('patient_name');
        $data['age'] = $this->input->post('age');
        $data['invoice_no'] = $this->input->post('invoice_no');
        $data['gender'] = $this->input->post('gender');
        $data['bp'] = $this->input->post('bp');
        $data['follow_up_day_month_year'] = $this->input->post('follow_up_day_month_year');
        $data['follow_up'] = $this->input->post('follow_up');

        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->insert('prescription', $data);
        $prescription_id = $this->db->insert_id();

        $drug_type_id = $this->input->post('drug_type_id');
        $drug_id = $this->input->post('drug_id');
        $medicin_times_id = $this->input->post('medicin_times_id');
        $days = $this->input->post('days');
        $day_or_month_or_year_or_colbay = $this->input->post('day_or_month_or_year_or_colbay');


        $diagnosis_id = $this->input->post('diagnosis_id');
        $advice_id = $this->input->post('advice_id');
        for ($i = 0; $i < count($drug_type_id); $i++) {
            if ($drug_type_id[$i] == '') {
                continue;
            }
            $data1 = array(
                'drug_type_id' => $drug_type_id[$i],
                'drug_id' => $drug_id[$i],
                'day_or_month_or_year_or_colbay' => $day_or_month_or_year_or_colbay[$i],
                'days' => $days[$i],
                'medicin_times_id' => $medicin_times_id[$i],
                'invoice_no' => $this->input->post('invoice_no'),
                'prescription_id' => $prescription_id,
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->insert('prescription_medicin', $data1);
        }

        for ($i = 0; $i < count($diagnosis_id); $i++) {
            if ($diagnosis_id[$i] == '') {
                continue;
            }
            $data2 = array(
                'diagnosis_id' => $diagnosis_id[$i],
                'invoice_no' => $this->input->post('invoice_no'),
                'prescription_id' => $prescription_id,
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->insert('prescription_diagnosis', $data2);
        }
        for ($i = 0; $i < count($advice_id); $i++) {
            if ($advice_id[$i] == '') {
                continue;
            }
            $data3 = array(
                'advice_id' => $advice_id[$i],
                'invoice_no' => $this->input->post('invoice_no'),
                'prescription_id' => $prescription_id,
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->insert('prescription_advice', $data3);
        }
        $data = array();
        $data['prescription_id'] = $prescription_id;
        $this->load->view('prescription/print_prescription', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'prescription/print_prescription',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_prescription_again($prescription_id) {
        $data['prescription_id'] = $prescription_id;
        $this->load->view('prescription/print_prescription', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'prescription/print_prescription',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'prescription/prescription_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function patient_print_again($patient_id) {
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/print_patient_admission', $data, TRUE);
        $page_data = array(
            'page_name' => 'patient/print_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

}

?>