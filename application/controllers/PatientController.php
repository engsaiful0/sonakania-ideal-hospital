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
use Laminas\Barcode\Barcode;
class PatientController extends CI_Controller
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
        $this->load->library('zend');
      
    }

    public function index()
    {
        $this->_example_output((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    public function _example_output($output = null, $page_data = null)
    {
        $this->load->view('product/grocery_crud_view', (array) $output, $page_data);
    }

    public function patient_dashbaord()
    {
        $page_data = array(
            'page_name' => 'patient/patient_dashbaord',
            'page_title' => 'Add Dashboard',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if(!empty($parameter)) {
            // Query with condition
           $this->db->select('')
                     ->like('patient_unique_id', $parameter)
                     ->from('ipd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                     ->from('ipd_patient');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
       echo json_encode($data_patient);
    }
    public function patient_name_load()
    {
        $parameter = $_POST['parameter'];
        if(!empty($parameter)) {
            // Query with condition
           $this->db->select('')
                     ->like('patient_name', $parameter)
                     ->from('ipd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                     ->from('patient');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_name);
        }
       echo json_encode($data_patient);
    }
    public function mobile_number_load()
    {
        $parameter = $_POST['parameter'];
        if(!empty($parameter)) {
            // Query with condition
           $this->db->select('')
                     ->like('mobile_number', $parameter)
                     ->from('ipd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                     ->from('ipd_patient');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->mobile_number);
        }
       echo json_encode($data_patient);
    }
    
    public function opd_patient_report()
    {
        $page_data = array(
            'page_name' => 'patient/opd_patient_report',
            'page_title' => 'OPD Patient Report',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_patient_report_details($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['doctor_id'] = $data[2];
        $this->load->view('patient/opd_patient_report_details', $data);
    }

    public function add_opd_patient()
    {
        $page_data = array(
            'page_name' => 'patient/add_opd_patient',
            'page_title' => 'Add OPD Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function set_barcode($code)
    {
        // Configure barcode options
        $barcodeOptions = ['text' => $code];
        $rendererOptions = [];

        // Generate the barcode
        $barcode = Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions);
        
        // Send the image to the browser
        $imageResource = $barcode->draw();
        header('Content-Type: image/png');
        imagepng($imageResource);
        imagedestroy($imageResource);
    }

    public function print_patient_info()
    {
        $patient_id = $this->session->userdata('print_patient_id');
        if (!$patient_id) {
            show_404();
        }

        $data['patient'] = $this->db->where('patient_id', $patient_id)->get('patient')->row();
        $data['cabin'] = $this->db->where('cabin_id', $data['patient']->cabin_id)->get('cabin')->row();
        $data['ward'] = $this->db->where('ward_id', $data['patient']->ward_id)->get('ward')->row();
        $data['bed'] = $this->db->where('bed_id', $data['patient']->bed_id)->get('bed')->row();
        $data['doctor'] = $this->db->where('doctor_id', $data['patient']->doctor_id)->get('doctor')->row();
        $data['company'] = $this->db->where('company_id', '1')->get('company')->row();

        $this->load->view('patient_print_view', $data);
    }
    public function add_ipd_patient()
    {
       
        $page_data = array(
            'page_name' => 'patient/add_ipd_patient',
            'page_title' => 'Add Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_expertise_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('doctorspecilization');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
        $crud->set_subject('Doctor Specialization');
        $crud->required_fields('specilization');
        $crud->columns('specilization');
        $crud->fields('specilization');
        $crud->display_as('specilization', 'Doctor Specialization');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    public function add_doctor()
    {
        $page_data = array(
            'page_name' => 'doctor/add_doctor',
            'page_title' => 'Add Doctor',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
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

    public function delete_this_opd_patient($opd_patient_id)
    {
        $data = array('is_deleted' => '1');
        $this->db->where('opd_patient_id', $opd_patient_id)->update('opd_patient', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);
        //var_dump($_SESSION);
        redirect('PatientController/view_opd_patient');
    }

    public function delete_this_ipd_patient($patient_id)
    {
        $data = array('is_deleted' => '1');
        $this->db->where('patient_id', $patient_id)->update('patient', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);

        $patient_name = $this->input->post('patient_name');
        $mobile_number = $this->input->post('mobile_number');
        $patient_unique_id = $this->input->post('patient_unique_id');
        $gender = $this->input->post('gender');
        $doctor_id = $this->input->post('doctor_id');
        $reference_media_id = $this->input->post('reference_media_id');
        $bed_id = $this->input->post('bed_id');
        $cabin_id = $this->input->post('cabin_id');
        $date = date('Y-m-d', strtotime($this->input->post('date')));

        $config['base_url'] = site_url('PatientController/view_ipd_patient');
        $config['total_rows'] = $this->db->count_all('patient');
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
        $data['detailsList'] = $this->ProductModel->patient_details($config["per_page"], $data['page'], $patient_name, $mobile_number, $patient_unique_id, $gender, $reference_media_id, $bed_id, $cabin_id, $date, $doctor_id);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('patient/view_ipd_patient', $data, true);
        $page_data = array(
            'page_name' => 'patient/view_ipd_patient',
            'page_title' => 'View Patient',
            'sidebar' => 'patient/patient_sidebar'
        );

        $this->load->view('content', $page_data);
    }

    public function view_ipd_patient()
    {
        $patient_name = $this->input->post('patient_name');
        $mobile_number = $this->input->post('mobile_number');
        $patient_unique_id = $this->input->post('patient_unique_id');
        $gender = $this->input->post('gender');
        $doctor_id = $this->input->post('doctor_id');
        $reference_media_id = $this->input->post('reference_media_id');
        $bed_id = $this->input->post('bed_id');
        $ward_id = $this->input->post('ward_id');
        $cabin_id = $this->input->post('cabin_id');
        $date = '';
        if ($this->input->post('date') != '') {
            $date = date('Y-m-d', strtotime($this->input->post('date')));
        }

        $config['base_url'] = site_url('PatientController/view_ipd_patient');
        $config['total_rows'] = $this->db->count_all('patient');
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
        $data['detailsList'] = $this->IpdPatientModel->patient_details($config["per_page"], $data['page'], $patient_name, $mobile_number, $patient_unique_id, $gender, $reference_media_id, $bed_id, $cabin_id, $date, $doctor_id);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('patient/view_ipd_patient', $data, true);
        $page_data = array(
            'page_name' => 'patient/view_ipd_patient',
            'page_title' => 'View Patient',
            'sidebar' => 'patient/patient_sidebar'
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

    public function ipd_patient_edit($patient_id)
    {
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/ipd_patient_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'patient/ipd_patient_edit',
            'page_title' => 'IPD Patient Edit',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_patient_edit($opd_patient_id)
    {
        $data['opd_patient_id'] = $opd_patient_id;
        $this->load->view('patient/opd_patient_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'patient/opd_patient_edit',
            'page_title' => 'Opd Patient Edit',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_ipd_patient_save()
    {
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
        $bed_id = $this->input->post('bed_id');
        if ($bed_id != '') {
            $booked = array(
                'status' => 'Not Available'
            );
            $this->db->where('bed_id', $bed_id)->update('bed', $booked);
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
        $data['bed_id'] = $this->input->post('bed_id');
        $data['cabin_id'] = $this->input->post('cabin_id');
        $data['admission_time'] = $this->input->post('admission_time');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['patient_unique_id'] = $this->input->post('patient_unique_id');

        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));

        $this->db->where('patient_id', $patient_id)->update('patient', $data);
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/print_ipd_patient_admission', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'patient/print_ipd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_ipd_patient_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $doctor_id = $this->input->post('doctor_id');
            $data = array();
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['id_serial'] = $this->input->post('id_serial');
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
            $bed_id = $this->input->post('bed_id');
            if ($bed_id != '') {
                $booked = array(
                    'status' => 'Not Available'
                );
                $this->db->where('bed_id', $bed_id)->update('bed', $booked);
            }
            /*
          To bolock the sead End
         */
            $data['patient_name'] = $this->input->post('patient_name');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['gender'] = $this->input->post('gender');
            $data['age'] = $this->input->post('age');
            $data['doctor_id'] = $this->input->post('doctor_id');
            $data['reference_media_id'] = $this->input->post('reference_media_id');
            $data['bed_id'] = $this->input->post('bed_id');
            $data['ward_id'] = $this->input->post('ward_id');
            $data['cabin_id'] = $this->input->post('cabin_id');
            $data['admission_time'] = $this->input->post('admission_time');
            $data['paid_amount'] = $this->input->post('paid_amount');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');

            $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $this->db->insert('patient', $data);
            $patient_id = $this->db->insert_id();
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_patient_id'] = $patient_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function edit_opd_patient_save()
    {
        $opd_patient_id = $this->input->post('opd_patient_id');
        $data = array();
        $data['opd_patient_name'] = $this->input->post('opd_patient_name');
        $data['opd_patient_unique_id'] = $this->input->post('opd_patient_unique_id');
        $data['mobile_number'] = $this->input->post('mobile_number');
        $data['gender'] = $this->input->post('gender');
        $data['department_id'] = $this->input->post('department_id');
        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['entry_date'] = date('Y-m-d', strtotime($this->input->post('entry_date')));
        $data['serial_numaber'] = $this->input->post('serial_numaber');
        $data['visiting_date'] = date('Y-m-d', strtotime($this->input->post('visiting_date')));

        $data['visiting_fee'] = $this->input->post('visiting_fee');
        $data['visiting_time'] = $this->input->post('visiting_time');
        $data['discount'] = $this->input->post('discount');
        $data['age'] = $this->input->post('age');

        $data['payable'] = $this->input->post('payable');
        $data['reference_media_id'] = $this->input->post('reference_media_id');
        $data['referred_by'] = $this->input->post('referred_by');
        $this->db->where('opd_patient_id', $opd_patient_id)->update('opd_patient', $data);
        $data['opd_patient_id'] = $opd_patient_id;
        $this->load->view('patient/print_opd_patient_admission', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'patient/print_opd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_opd_patient_save()
    {
        $data = array();
        $data['opd_patient_name'] = $this->input->post('opd_patient_name');
        $data['opd_patient_unique_id'] = $this->input->post('opd_patient_unique_id');
        $data['mobile_number'] = $this->input->post('mobile_number');
        $data['gender'] = $this->input->post('gender');
        $data['department_id'] = $this->input->post('department_id');
        $data['doctor_id'] = $this->input->post('doctor_id');
        $data['entry_date'] = date('Y-m-d', strtotime($this->input->post('entry_date')));
        $data['serial_numaber'] = $this->input->post('serial_numaber');
        $data['visiting_date'] = date('Y-m-d', strtotime($this->input->post('visiting_date')));

        $data['visiting_fee'] = $this->input->post('visiting_fee');
        $data['visiting_time'] = $this->input->post('visiting_time');
        $data['discount'] = $this->input->post('discount');
        $data['age'] = $this->input->post('age');

        $data['payable'] = $this->input->post('payable');
        $data['reference_media_id'] = $this->input->post('reference_media_id');
        $data['referred_by'] = $this->input->post('referred_by');


        $this->db->insert('opd_patient', $data);
        $data['opd_patient_id'] = $this->db->insert_id();
        $this->load->view('patient/print_opd_patient_admission', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'patient/print_opd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ipd_patient_print()
    {
       
        $page_data = array(
            'page_name' => 'patient/print_ipd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ipd_patient_print_again($patient_id)
    {
        // die;
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/print_ipd_patient_admission', $data, TRUE);
        $page_data = array(
            'page_name' => 'patient/print_ipd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_patient_print_again($opd_patient_id)
    {
        $data['opd_patient_id'] = $opd_patient_id;
        $this->load->view('patient/print_opd_patient_admission', $data, TRUE);
        $page_data = array(
            'page_name' => 'patient/print_opd_patient_admission',
            'page_title' => 'Print Opd Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_doctor_frame()
    {
        $crud = new Grocery_crud();
        $crud->set_table('doctor');
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 'admin') :
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
        endif;
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

    public function doctor_test_reference_report()
    {

        $page_data = array(
            'page_name' => 'report/doctor_test_reference_report',
            'page_title' => 'Doctors Test Reference Report ',
            'sidebar' => 'doctor/doctor_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function opd_payment()
    {
        $page_data = array(
            'page_name' => 'patient/opd_payment',
            'page_title' => 'OPD Payment',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function bed_number_load()
    {
        $ward_id = $_POST['ward_id'];
        $beds = $this->db->where('ward_id', $ward_id)->get('bed')->result();
        foreach ($beds as $value) {
?>
            <option value="<?php echo $value->bed_id ?>"><?php echo $value->bed_number ?></option>
        <?php
        }
    }
    public function opd_payment_load()
    {
        $data = array();
        $data['from_date'] = $_POST['from_date'];
        $data['to_date'] = $_POST['to_date'];
        $data['doctor_id'] = $_POST['doctor_id'];
        $this->load->view('patient/opd_payment_load', $data);
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

    public function doctor_load()
    {
        $department_id = $_POST['department_id'];
        $doctor = $this->db->where('department_id', $department_id)
            ->get('doctor')->result();
        ?>
        <option selected="" disabled="" value="">Select Doctor</option>
        <?php
        foreach ($doctor as $value) {
        ?>
            <option value="<?php echo $value->doctor_id; ?>"><?php echo $value->doctor_name.' - '.$value->doctor_unique_id; ?></option>
<?php
        }
    }
    public function patient_data_load()
    {
        $patient_id = $_POST['patient_id'];
        $patient = $this->db->where('patient_id', $patient_id)->get('patient')->row();
        echo $patient->patient_name . '-' . $patient->mobile_number . '-' . $patient->age;
    }

    public function view_opd_patient()
    {
        $doctor_id = $this->input->post('doctor_id');
        $department_id = $this->input->post('department_id');
        $gender = $this->input->post('gender');
        $to_date = '';
        $from_date = '';
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }
        $config['base_url'] = site_url('PatientController/view_opd_patient');
        $config['total_rows'] = $this->db->count_all('opd_patient');
        $config['per_page'] = "100";
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
        $data['detailsList'] = $this->ProductModel->opd_patient_details($config["per_page"], $data['page'], $doctor_id, $gender, $department_id, $from_date, $to_date);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('patient/view_opd_patient', $data, true);
        $page_data = array(
            'page_name' => 'patient/view_opd_patient',
            'page_title' => 'View OPD Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function visiting_fee_load()
    {
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db->where('doctor_id', $doctor_id)
            ->get('doctor')->row();
        echo $doctor->new_patient_fee;
    }
    public function select_visit_interval_fee()
    {
        $doctor_id = $_POST['doctor_id'];
        $visiting_interval = $_POST['visiting_interval'];

        $doctor = $this->db->where('doctor_id', $doctor_id)
            ->get('doctor')->row();
        if ($visiting_interval == 'first_time') {
            echo $doctor->new_patient_fee;
        } else if ($visiting_interval == 'within_seven_days') {
            echo $doctor->within_seven_days_visiting_fee;
        } else if ($visiting_interval == 'within_fifty_days') {
            echo $doctor->within_fifteen_days_visiting_fee;
        } else if ($visiting_interval == 'within_thirty_days') {
            echo $doctor->within_thirty_days_visiting_fee;
        }
        
    }
    public function serial_load()
    {
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db
            ->where('doctor_id', $doctor_id)
            ->where('entry_date', date('Y-m-d'))
            ->get('opd_patient');
        echo $doctor->num_rows() + 1;
    }

    public function opd_patient_payment_save()
    {
        $opd_patient_id = $this->input->post('opd_patient_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $doctor_id = $this->input->post('doctor_id');
        $doctor = $this->db->where('doctor_id', $doctor_id)->get('doctor')->row();
        $total_visitiong_fee = 0;
        $total_doctors_commission = 0;
        $total_patients = 0;
        for ($i = 0; $i < count($opd_patient_id); $i++) {
            $update = array('is_doctor_commision_paid' => 'yes');
            $this->db->where('opd_patient_id', $opd_patient_id[$i])->update('opd_patient', $update);
            $opd_patient = $this->db->where('opd_patient_id', $opd_patient_id[$i])->get('opd_patient')->row();

            $total_visitiong_fee += $opd_patient->payable;
            $total_doctors_commission += $opd_patient->payable * ($doctor->opd_patient_percentage / 100);
            $total_patients++;
        }
        $data = array(
            'doctor_id' => $doctor_id,
            'percentage_of_commission' => $doctor->opd_patient_percentage,
            'from_date' => date('Y-m-d', strtotime($from_date)),
            'to_date' => date('Y-m-d', strtotime($to_date)),
            'total_patients' => $total_patients,
            'total_visitiong_fee' => $total_visitiong_fee,
            'total_doctors_commission' => $total_doctors_commission,
            'paid_date' => date('Y-m-d')
        );
        $this->db->insert('opd_doctor_commision_payment', $data);
        $data['opd_doctor_commision_payment_id'] = $this->db->insert_id();
        $this->load->view('patient/opd_patient_doctor_payment_voucer', $data, true);
        $page_data = array(
            'page_name' => 'patient/opd_patient_doctor_payment_voucer',
            'page_title' => 'Payment Voucher',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
