<?php

    use Laminas\Barcode\Barcode;

    class DischargeSlipController extends CI_Controller
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
        public function discharged_patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('*') // Specify the columns to select, or '*' for all columns
                ->from('ipd_patient') // Define the table
                ->like('patient_unique_id', $parameter) // Add the LIKE condition
                ->where('status', 'Discharged'); // Add the WHERE condition
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        echo json_encode($data_patient);
    }
        
        public function patient_unique_id_load()
        {
            $parameter = $_POST['parameter'];
            if (!empty($parameter)) {
                // Query with condition
                $this->db->select('')
                    ->like('patient_unique_id', $parameter)
                    ->from('discharge_slips');
            } else {
                // Query without condition
                $this->db->select('')
                    ->from('discharge_slips');
            }
            $sql = $this->db->get()->result();
            $data_patient = array();
            foreach ($sql as $value) {
                array_push($data_patient, $value->patient_unique_id);
            }
            if (count($data_patient) == 0) {
                $data_patient = array('No discharge patient found!');
            }
            echo json_encode($data_patient);
        }
        public function discharge_slip_id_load()
        {
            $parameter = $_POST['parameter'];
            if (!empty($parameter)) {
                // Query with condition
                $this->db->select('')
                    ->like('discharge_slip_unique_id', $parameter)
                    ->from('discharge_slips');
            } else {
                // Query without condition
                $this->db->select('')
                    ->from('discharge_slips');
            }
            $sql = $this->db->get()->result();
            $data_discharge_slips = array();
            foreach ($sql as $value) {
                array_push($data_discharge_slips, $value->discharge_slip_unique_id);
            }
            if (count($data_discharge_slips) == 0) {
                $data_discharge_slips = array('No discharge patient found!');
            }
            echo json_encode($data_discharge_slips);
        }

        public function load_product_row()
        {
            $data['id'] = $_POST['id'];
            $this->load->view('discharge_slip/load_product_row', $data);
        }

        public function load_advice_row()
        {
            $data['id'] = $_POST['id'];
            $this->load->view('discharge_slip/load_advice_row', $data);
        }

        public function load_diagnosis_row()
        {
            $data['id'] = $_POST['id'];
            $this->load->view('discharge_slip/load_diagnosis_row', $data);
        }


        public function add_discharge_slip()
        {
            $page_data = array(
                'page_name' => 'discharge_slip/add_discharge_slip',
                'page_title' => 'Add Discharge Slip',
                'sidebar' => 'patient/patient_sidebar'
            );
            $this->load->view('content', $page_data);
        }
        public function discharged_patient_data_load_by_unique_id()
        {
            $patient_unique_id = $_POST['patient_unique_id'];
            $patient = $this->db->where('patient_unique_id', $patient_unique_id)->get('ipd_patient')->row();
            $discharge = $this->db->where('patient_unique_id', $patient_unique_id)->get('discharge')->row();
            echo $patient->ipd_patient_id . '*' . $patient->patient_name . '*' . $patient->mobile_number . '*' . $patient->age . '*' . $patient->date . '*' . $patient->admission_time . '*' . $discharge->discharge_date . '*' . $discharge->discharge_time . '*' . $patient->age_year . '*' . $patient->age_month . '*' . $patient->age_day;
        }
        public function view_discharge_slip()
        {

            $patient_unique_id = $this->input->post('patient_unique_id');
            $discharge_slip_unique_id = $this->input->post('discharge_slip_unique_id');
            $date = '';
            if ($this->input->post('admission_date') != '') {
                $date = date('Y-m-d', strtotime($this->input->post('admission_date')));
            }

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            // Pagination configuration
            $config['base_url'] = base_url() . "index.php/MedicineSaleController/view_medicine_sale";
            $config['total_rows'] = $this->DischargeSlipModel->count_all_discharged_slips($patient_unique_id, $discharge_slip_unique_id, $date);
            $config['per_page'] = "20";
            $config["uri_segment"] = 3;
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

            $this->pagination->initialize($config);

            // Ensure $page is an integer or zero

            $this->per_page = $config["per_page"];
            $this->pagination->initialize($config);
            // Get medicine sales list
            $data['detailsList'] = $this->DischargeSlipModel->discharged_slip_details($this->per_page, $page, $patient_unique_id, $discharge_slip_unique_id, $date);
            $data['pagination'] = $this->pagination->create_links();
            $this->load->view('discharge_slip/view_discharge_slip', $data, true);

            $page_data = array(
                'page_name' => 'discharge_slip/view_discharge_slip',
                'page_title' => 'Discharge',
                'sidebar' => 'patient/patient_sidebar'
            );
            $this->load->view('content', $page_data);
        }

        public function edit_discharge_slip($discharge_slip_id)
        {
            $data['discharge_slip_id'] = $discharge_slip_id;
            $this->load->view('discharge_slip/edit_discharge_slip', $data, true);
            $page_data = array(
                'page_name' => 'discharge_slip/edit_discharge_slip',
                'page_title' => 'Discharge',
                'sidebar' => 'patient/patient_sidebar'
            );
            $this->load->view('content', $page_data);
        }

        public function discharge_slip_data_update()
        {
            if ($this->input->is_ajax_request()) {
                $data = array();
                $discharge_slip_id = $this->input->post('discharge_slip_id');
                $discharge_slips_previous_data = $this->db->where('discharge_slip_id', $discharge_slip_id)->get('discharge_slips')->row();

                $data = array();
                $data['patient_unique_id'] = $this->input->post('patient_unique_id');
                $data['ipd_patient_id'] = $this->input->post('ipd_patient_id');
                $data['discharge_slip_unique_id'] = $this->input->post('discharge_slip_unique_id');

                $data['bp_systolic'] = $this->input->post('bp_systolic');
                $data['bp_diastolic'] = $this->input->post('bp_diastolic');
                $data['follow_up_day_month_year'] = $this->input->post('follow_up_day_month_year');
                $data['follow_up'] = $this->input->post('follow_up');
                $data['custom_diagnosis'] = $this->input->post('custom_diagnosis');
                $data['custom_advice'] = $this->input->post('custom_advice');

                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['user_id'] = $this->session->userdata('user_id');
                $this->db->where('discharge_slip_id', $discharge_slip_id)->update('discharge_slips', $data);

                $this->db->where('discharge_slip_id', $discharge_slip_id)->delete('discharge_slip_medicins');
                $this->db->where('discharge_slip_id', $discharge_slip_id)->delete('discharge_slip_diagnosis');
                $this->db->where('discharge_slip_id', $discharge_slip_id)->delete('discharge_slip_advices'); //To delete previous data

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
                        'discharge_slip_unique_id' => $this->input->post('discharge_slip_unique_id'),
                        'discharge_slip_id' => $discharge_slip_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $discharge_slips_previous_data->user_id,
                    );
                    $this->db->insert('discharge_slip_medicins', $data1);
                }

                for ($i = 0; $i < count($diagnosis_id); $i++) {
                    if ($diagnosis_id[$i] == '') {
                        continue;
                    }
                    $data2 = array(
                        'diagnosis_id' => $diagnosis_id[$i],
                        'discharge_slip_unique_id' => $this->input->post('discharge_slip_unique_id'),
                        'discharge_slip_id' => $discharge_slip_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $discharge_slips_previous_data->user_id,
                    );
                    $this->db->insert('discharge_slip_diagnosis', $data2);
                }
                for ($i = 0; $i < count($advice_id); $i++) {
                    if ($advice_id[$i] == '') {
                        continue;
                    }
                    $data3 = array(
                        'advice_id' => $advice_id[$i],
                        'discharge_slip_unique_id' => $this->input->post('discharge_slip_unique_id'),
                        'discharge_slip_id' => $discharge_slip_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $discharge_slips_previous_data->user_id,
                    );
                    $this->db->insert('discharge_slip_advices', $data3);
                }
                $data = array();
                $sdata['print_discharge_slip_id'] = $discharge_slip_id;
                $sdata['success'] = 'saved successully';
                $this->session->set_userdata($sdata);
                $response = array('success' => true, 'message' => 'Data saved successfully.');
        
                // Clear any output buffer and send JSON response
                ob_clean(); 
                echo json_encode($response);
                exit;
            } else {
                // If it's not an AJAX request, show an error
                $response = array('error' => true, 'message' => 'Invalid request.');
                echo json_encode($response);
            }
        }
        public function delete_discharge_slip_ajax()
        {
            $discharge_slip_id = $this->input->post('discharge_slip_id');
            if ($this->db->where('discharge_slip_id', $discharge_slip_id)->delete('discharge_slips')) {
                $response = array('status' => 'success', 'message' => 'Discharge deleted successfully.');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
            }

            echo json_encode($response);
        }
        public function discharge_slip_data_save()
        {
            if ($this->input->is_ajax_request()) {
                $data = array();

                $data['discharge_slip_unique_id'] = $this->input->post('discharge_slip_unique_id');
                $data['id_serial'] = $this->input->post('id_serial');
                $data['user_id'] = $this->session->userdata('user_id');
                $this->db->insert('discharge_slip_ids', $data);

                $data = array();
                $data['patient_unique_id'] = $this->input->post('patient_unique_id');
                $data['ipd_patient_id'] = $this->input->post('ipd_patient_id');
                $data['discharge_slip_unique_id'] = $this->input->post('discharge_slip_unique_id');

                $data['bp_systolic'] = $this->input->post('bp_systolic');
                $data['bp_diastolic'] = $this->input->post('bp_diastolic');
                $data['follow_up_day_month_year'] = $this->input->post('follow_up_day_month_year');
                $data['follow_up'] = $this->input->post('follow_up');
                $data['custom_diagnosis'] = $this->input->post('custom_diagnosis');
                $data['custom_advice'] = $this->input->post('custom_advice');

                $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
                $data['user_id'] = $this->session->userdata('user_id');
                $this->db->insert('discharge_slips', $data);
                $discharge_slip_id = $this->db->insert_id();

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
                        'discharge_slip_unique_id' => $this->input->post('discharge_slip_unique_id'),
                        'discharge_slip_id' => $discharge_slip_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $this->session->userdata('user_id'),
                    );
                    $this->db->insert('discharge_slip_medicins', $data1);
                }

                for ($i = 0; $i < count($diagnosis_id); $i++) {
                    if ($diagnosis_id[$i] == '') {
                        continue;
                    }
                    $data2 = array(
                        'diagnosis_id' => $diagnosis_id[$i],
                        'discharge_slip_unique_id' => $this->input->post('discharge_slip_unique_id'),
                        'discharge_slip_id' => $discharge_slip_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $this->session->userdata('user_id'),
                    );
                    $this->db->insert('discharge_slip_diagnosis', $data2);
                }
                for ($i = 0; $i < count($advice_id); $i++) {
                    if ($advice_id[$i] == '') {
                        continue;
                    }
                    $data3 = array(
                        'advice_id' => $advice_id[$i],
                        'discharge_slip_unique_id' => $this->input->post('discharge_slip_unique_id'),
                        'discharge_slip_id' => $discharge_slip_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $this->session->userdata('user_id'),
                    );
                    $this->db->insert('discharge_slip_advices', $data3);
                }
                $data = array();
                $sdata['print_discharge_slip_id'] = $discharge_slip_id;
                $response = array('success' => true, 'message' => 'Data saved successfully.');
                $sdata['success'] = 'saved successully';
                $this->session->set_userdata($sdata);
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            } else {
                // If it's not an AJAX request, show an error
                $response = array('error' => true, 'message' => 'Invalid request.');
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }
        }

        public function print_discharge_slip()
        {
            $page_data = array(
                'page_name' => 'discharge_slip/print_discharge_slip',
                'page_title' => 'Print Patient Admission',
                'sidebar' => 'patient/patient_sidebar'
            );
            $this->load->view('content', $page_data);
        }
        public function print_discharge_slip_again($discharge_slip_id)
        {
            $data['discharge_slip_id'] = $discharge_slip_id;
            $this->load->view('discharge_slip/print_discharge_slip', $data, TRUE);

            $page_data = array(
                'page_name' => 'discharge_slip/print_discharge_slip',
                'page_title' => 'Print Discharge Slip',
                'sidebar' => 'patient/patient_sidebar'
            );
            $this->load->view('content', $page_data);
        }
        public function add_drug_name()
        {
            $type_name_val = $_POST['type_name_val'];

            $sql = $this->db->where('drug_type_id', $type_name_val)->get('drug')->result();
    ?>
<option value="">Select Drug</option>
<?php
            foreach ($sql as $value) {
?>
    <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
<?php
            }
        }
    }
