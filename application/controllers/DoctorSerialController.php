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

class DoctorSerialController extends CI_Controller
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
    function sms_send($data)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $doctor = getDoctorById($data['doctor_id']);
        $sms_api = getSMSAPI();

        if ($sms_api->is_opd_sms_send == 'yes') { // if is_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['mobile_number'];
            $message = "Dear patient, you have booked the serial successfully. Patient Name: " . $data['patient_name'] . ", Serial: " . $data['serial_numaber'] . ', Visiting Date and Time: ' . $data['visiting_date'] . $data['visiting_time'] . ', Doctor Name: ' . $doctor->doctor_name . ',' . $company_name;

            $data = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];

            // Send SMS first
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            // Parse the response to check if SMS was sent successfully
            $response_code = null;
            if (!empty($response)) {
                // Decode JSON response if it's JSON format
                $response_data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($response_data['response_code'])) {
                    $response_code = $response_data['response_code'];
                } elseif (is_numeric($response)) {
                    // If response is just a number (response code)
                    $response_code = (int)$response;
                }
            }

            // Only save to database if SMS was sent successfully (code 202)
            if ($response_code == 202) {
                $send_sms = array(
                    'mobile_number' => $number,
                    'message' => $message,
                    'type' => 'Doctor Serial',
                    'date' => date('Y-m-d'), // Today date
                    'user_id' => $this->session->userdata('user_id'),
                    'response_code' => $response_code,
                    'status' => 'Sent'
                );
                $this->db->insert('send_sms', $send_sms);
            }

            return $response;
        }
    }
    public function serial_load()
    {
        $doctor_id = $_POST['doctor_id'];
        $doctor = $this->db
            ->where('doctor_id', $doctor_id)
            ->where('entry_date', date('Y-m-d'))
            ->get('doctor_serial');
        echo $doctor->num_rows() + 1;
    }
    public function discount_reference_load()
    {
        $parameter = $this->input->post('parameter');
        $data_patient = [];

        if (!empty($parameter)) {
            // Search in doctor table
            $doctors = $this->db->select("doctor_name AS discount_reference")
                ->like('doctor_name', $parameter)
                ->get('doctor')
                ->result();

            // Search in director table
            $directors = $this->db->select("name AS discount_reference")
                ->like('name', $parameter)
                ->get('director')
                ->result();

            // Search in employee table
            $employees = $this->db->select("employee_name AS discount_reference")
                ->like('employee_name', $parameter)
                ->get('employee')
                ->result();

            // Search in share_holder table
            $share_holders = $this->db->select("name AS discount_reference")
                ->like('name', $parameter)
                ->get('share_holder')
                ->result();

            // Search in reference_media table
            $reference_media = $this->db->select("reference_media_name AS discount_reference")
                ->like('reference_media_name', $parameter)
                ->get('reference_media')
                ->result();

            // Merge all results
            $all_results = array_merge($doctors, $directors, $employees, $share_holders, $reference_media);

            // Extract discount_reference
            foreach ($all_results as $value) {
                $data_patient[] = $value->discount_reference;
            }
        }

        echo json_encode($data_patient);
    }


    public function doctor_serial_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('doctor_serial_unique_id', $parameter)
                ->from('doctor_serial');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('doctor_serial');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->doctor_serial_unique_id);
        }
        echo json_encode($data_patient);
    }
    public function patient_name_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('doctor_serial_name', $parameter)
                ->from('doctor_serial');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('doctor_serial');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->doctor_serial_name);
        }
        echo json_encode($data_patient);
    }
    public function mobile_number_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('mobile_number', $parameter)
                ->from('doctor_serial');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('doctor_serial');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->mobile_number);
        }
        echo json_encode($data_patient);
    }

    public function doctor_serial_report()
    {
        $page_data = array(
            'page_name' => 'doctor_serial/doctor_serial_report',
            'page_title' => 'OPD Patient Report',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_serial_report_details($ids)
    {
        $data = array();
        $data = explode("_", $ids);
        $data['from_date'] = $data[0];
        $data['to_date'] = $data[1];
        $data['doctor_id'] = $data[2];
        $this->load->view('doctor_serial/doctor_serial_report_details', $data);
    }

    public function add_doctor_serial()
    {
        $page_data = array(
            'page_name' => 'doctor_serial/add_doctor_serial',
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
    public function delete_doctor_serial_ajax()
    {
        $doctor_serial_id = $this->input->post('doctor_serial_id');

        if ($doctor_serial_id === null || $doctor_serial_id === '' || !ctype_digit((string) $doctor_serial_id)) {
            $response = array('status' => 'error', 'message' => 'Invalid record id.');
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $this->db->where('doctor_serial_id', (int) $doctor_serial_id)->delete('doctor_serial');

        if ($this->db->affected_rows() > 0) {
            $response = array('status' => 'success', 'message' => 'Patient deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function doctor_serial_edit($doctor_serial_id)
    {
        $data['doctor_serial_id'] = $doctor_serial_id;
        $this->load->view('doctor_serial/doctor_serial_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'doctor_serial/doctor_serial_edit',
            'page_title' => 'Opd Patient Edit',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function doctor_serial_return($doctor_serial_id)
    {
        $data['doctor_serial_id'] = $doctor_serial_id;
        $this->load->view('doctor_serial/doctor_serial_return', $data, TRUE);
        $page_data = array(
            'page_name' => 'doctor_serial/doctor_serial_return',
            'page_title' => 'Opd Patient Return',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function edit_doctor_serial_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $doctor_serial_id = $this->input->post('doctor_serial_id');
            $data = array();
            $data['patient_name'] = $this->input->post('patient_name');
            $data['doctor_serial_unique_id'] = $this->input->post('doctor_serial_unique_id');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['address'] = $this->input->post('address');

            $data['gender'] = $this->input->post('gender');


            $data['entry_date'] = date('Y-m-d', strtotime($this->input->post('entry_date')));
            $data['serial_numaber'] = $this->input->post('serial_numaber');
            $data['visiting_date'] = date('Y-m-d', strtotime($this->input->post('visiting_date')));
            $data['visiting_time'] = $this->input->post('visiting_time');
            
            $data['discount_reference'] = $this->input->post('discount_reference');

            $data['age_year'] = $this->input->post('age_year');
            $data['age_month'] = $this->input->post('age_month');
            $data['age_day'] = $this->input->post('age_day');
            $data['years_or_days'] = $this->input->post('years_or_days');
            $data['department_id'] = $this->input->post('department_id') ?: null;
            $data['doctor_id'] = $this->input->post('doctor_id') ?: null;
            $data['reference_director_id'] = $this->input->post('reference_director_id') ?: null;
            $data['reference_doctor_id'] = $this->input->post('reference_doctor_id') ?: null;
            $data['reference_media_id'] = $this->input->post('reference_media_id') ?: null;
            $data['reference_employee_id'] = $this->input->post('reference_employee_id') ?: null;


            $this->db->where('doctor_serial_id', $doctor_serial_id)->update('doctor_serial', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Doctor Serial updated and Doctor Serial Id is=' . $doctor_serial_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['doctor_serial_id'] = $doctor_serial_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    
    public function add_doctor_serial_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['doctor_serial_unique_id'] = $this->input->post('doctor_serial_unique_id');
            $data['id_serial'] = $this->input->post('id_serial');
            $this->db->insert('doctor_serial_unique_table', $data);

            $data = array();

            $data['patient_name'] = $this->input->post('patient_name');
            $data['doctor_serial_unique_id'] = $this->input->post('doctor_serial_unique_id');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['address'] = $this->input->post('address');
            $data['gender'] = $this->input->post('gender');
            $data['department_id'] = $this->input->post('department_id') ?: null;
            $data['doctor_id'] = $this->input->post('doctor_id') ?: null;

            $data['entry_date'] = date('Y-m-d', strtotime($this->input->post('entry_date')));
            $data['serial_numaber'] = $this->input->post('serial_numaber');
            $data['visiting_date'] = date('Y-m-d', strtotime($this->input->post('visiting_date')));
            
            $data['visiting_time'] = $this->input->post('visiting_time');
            
            $data['discount_reference'] = $this->input->post('discount_reference');
            $data['age_year'] = $this->input->post('age_year');
            $data['age_month'] = $this->input->post('age_month');
            $data['age_day'] = $this->input->post('age_day');
            $data['years_or_days'] = $this->input->post('years_or_days');
            

            $data['reference_director_id'] = $this->input->post('reference_director_id') ?: null;
            $data['reference_doctor_id'] = $this->input->post('reference_doctor_id') ?: null;
            $data['reference_media_id'] = $this->input->post('reference_media_id') ?: null;
            $data['reference_employee_id'] = $this->input->post('reference_employee_id') ?: null;
            $data['user_id'] =  $this->session->userdata('user_id') ?: null;

            $save_result = $this->db->insert('doctor_serial', $data);

            $doctor_serial_id = $this->db->insert_id();
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data); //To send sms
            }
            $sdata['doctor_serial_id'] = $doctor_serial_id;

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Doctor Serial added and Doctor Serial Id is=' . $doctor_serial_id
            );
            $this->db->insert('activity_log', $activity_data);

            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function doctor_serial_print()
    {
        $page_data = array(
            'page_name' => 'doctor_serial/print_doctor_serial',
            'page_title' => 'Print Doctor Serial',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function doctor_serial_print_again($doctor_serial_id)
    {
        $data['doctor_serial_id'] = $doctor_serial_id;
        $this->session->set_userdata('doctor_serial_id', $doctor_serial_id);
        $this->load->view('doctor_serial/print_doctor_serial', $data, TRUE);
        $page_data = array(
            'page_name' => 'doctor_serial/print_doctor_serial',
            'page_title' => 'Print Doctor Serial',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

  

    public function patient_data_load()
    {
        $patient_id = $_POST['patient_id'];
        $patient = $this->db->where('patient_id', $patient_id)->get('patient')->row();
        echo $patient->patient_name . '-' . $patient->mobile_number . '-' . $patient->age;
    }

    public function view_doctor_serial()
    {
        $doctor_id = $this->input->post('doctor_id');
        $department_id = $this->input->post('department_id');
        $gender = $this->input->post('gender');
        $patient_name = $this->input->post('patient_name');
        $mobile_number = $this->input->post('mobile_number');
        $doctor_serial_unique_id = $this->input->post('doctor_serial_unique_id');
        $reference_media_id = $this->input->post('reference_media_id');
        


        $to_date = '';
        $from_date = '';
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] = base_url() . "index.php/DoctorSerialController/view_doctor_serial";
        $config['total_rows'] = $this->DoctorSerialModel->count_all_doctor_serials(
            $patient_name,
            $mobile_number,
            $doctor_serial_unique_id,
            $gender,
            $reference_media_id,
            $doctor_id,
            $from_date,
            $to_date
        );
        $config['per_page'] = 100;
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

        $this->pagination->initialize($config);

        $data['page'] = $page;

        // Get OPD patient list
        $data['detailsList'] = $this->DoctorSerialModel->doctor_serial_details(
            $config["per_page"],
            $page,
            $patient_name,
            $mobile_number,
            $doctor_serial_unique_id,
            $gender,
            $reference_media_id,
            $doctor_id,
            $from_date,
            $to_date
        );
        $data['pagination'] = $this->pagination->create_links();

        // Load view with pagination
        $this->load->view('doctor_serial/view_doctor_serial', $data, true);
        $page_data = array(
            'page_name' => 'doctor_serial/view_doctor_serial',
            'page_title' => 'View Doctor Serial',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function print_selected_doctor_serials()
    {
        $selected_ids = $this->input->post('selected_ids');
        if (empty($selected_ids) || !is_array($selected_ids)) {
            show_error('No records selected for printing.');
            return;
        }

        $data['selected_serials'] = $this->DoctorSerialModel->get_serials_by_ids($selected_ids);
        $this->load->view('doctor_serial/print_selected_doctor_serials', $data);
    }

    public function export_selected_to_pdf()
    {
        $selected_ids = $this->input->post('selected_ids');
        if (empty($selected_ids) || !is_array($selected_ids)) {
            show_error('No records selected for PDF export.');
            return;
        }

        $selected_serials = $this->DoctorSerialModel->get_serials_by_ids($selected_ids);
        $company = $this->db->where('company_id', '1')->get('company')->row();

        // Try to load TCPDF, fallback to simple HTML if not available
        if (file_exists(APPPATH . 'third_party/tcpdf/tcpdf.php')) {
            $this->export_with_tcpdf($selected_serials, $company);
        } else {
            $this->export_with_simple_pdf($selected_serials, $company);
        }
    }

    private function export_with_tcpdf($selected_serials, $company)
    {
        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator($company->company_name);
        $pdf->SetAuthor($company->company_name);
        $pdf->SetTitle('Doctor Serial Report');
        $pdf->SetSubject('Doctor Serial Report');

        // Set default header data
        $pdf->SetHeaderData('', 0, $company->company_name, 'Doctor Serial Report');

        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Create HTML content
        $html = $this->generate_pdf_html($selected_serials, $company);

        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('doctor_serials_' . date('Y-m-d') . '.pdf', 'D');
    }

    private function export_with_simple_pdf($selected_serials, $company)
    {
        $this->load->library('Simple_pdf');
        
        $this->simple_pdf->set_title('Doctor Serial Report');
        $html = $this->generate_pdf_html($selected_serials, $company);
        $this->simple_pdf->add_html($html);
        $this->simple_pdf->output('doctor_serials_' . date('Y-m-d') . '.html', 'D');
    }

    private function generate_pdf_html($selected_serials, $company)
    {
        $html = '<h2 style="text-align:center;">Doctor Serial Report</h2>';
        $html .= '<p style="text-align:center;">' . $company->company_name . '</p>';
        $html .= '<table border="1" cellpadding="4" style="width:100%;">';
        $html .= '<thead>';
        $html .= '<tr style="background-color:#f0f0f0;">';
        $html .= '<th>Sl</th>';
        $html .= '<th>Patient Name</th>';
        $html .= '<th>Mobile</th>';
        $html .= '<th>Age</th>';
        $html .= '<th>Gender</th>';
        $html .= '<th>Doctor</th>';
        $html .= '<th>Serial</th>';
        $html .= '<th>Visiting Date</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $sl = 1;
        foreach ($selected_serials as $serial) {
            $doctor = $this->db->where('doctor_id', $serial->doctor_id)->get('doctor')->row();
            
            $age_parts = [];
            if ($serial->age_year > 0) {
                $age_parts[] = $serial->age_year . ' ' . ($serial->age_year == 1 ? 'Year' : 'Years');
            }
            if ($serial->age_month > 0) {
                $age_parts[] = $serial->age_month . ' ' . ($serial->age_month == 1 ? 'Month' : 'Months');
            }
            if ($serial->age_day > 0) {
                $age_parts[] = $serial->age_day . ' ' . ($serial->age_day == 1 ? 'Day' : 'Days');
            }
            $age = implode(' ', $age_parts);

            $html .= '<tr>';
            $html .= '<td>' . $sl++ . '</td>';
            $html .= '<td>' . htmlspecialchars($serial->patient_name ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($serial->mobile_number ?? '') . '</td>';
            $html .= '<td>' . $age . '</td>';
            $html .= '<td>' . htmlspecialchars($serial->gender ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($doctor->doctor_name ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars($serial->serial_numaber ?? '') . '</td>';
            $html .= '<td>' . date('d-m-Y', strtotime($serial->visiting_date)) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<br><p style="text-align:center;">Generated on: ' . date('d-m-Y H:i:s') . '</p>';
        
        return $html;
    }

    public function export_selected_to_excel()
    {
        $selected_ids = $this->input->post('selected_ids');
        if (empty($selected_ids) || !is_array($selected_ids)) {
            show_error('No records selected for Excel export.');
            return;
        }

        $selected_serials = $this->DoctorSerialModel->get_serials_by_ids($selected_ids);
        $company = $this->db->where('company_id', '1')->get('company')->row();

        // Try to load PhpSpreadsheet, fallback to CSV if not available
        if (file_exists(APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php')) {
            $this->export_with_phpspreadsheet($selected_serials, $company);
        } else {
            $this->export_with_simple_excel($selected_serials, $company);
        }
    }

    private function export_with_phpspreadsheet($selected_serials, $company)
    {
        require_once(APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php');

        // Create new Spreadsheet object
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator($company->company_name)
            ->setTitle('Doctor Serial Report')
            ->setSubject('Doctor Serial Report')
            ->setDescription('Doctor Serial Report generated on ' . date('Y-m-d H:i:s'));

        // Set sheet title
        $sheet->setTitle('Doctor Serials');

        // Add headers
        $sheet->setCellValue('A1', $company->company_name);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Doctor Serial Report');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set column headers
        $headers = ['Sl', 'Patient Name', 'Mobile', 'Age', 'Gender', 'Doctor', 'Serial', 'Visiting Date'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue($columnLetters[$i] . '4', $headers[$i]);
            $sheet->getStyle($columnLetters[$i] . '4')->getFont()->setBold(true);
        }

        // Add data
        $row = 5;
        $sl = 1;
        foreach ($selected_serials as $serial) {
            $doctor = $this->db->where('doctor_id', $serial->doctor_id)->get('doctor')->row();
            
            $age_parts = [];
            if ($serial->age_year > 0) {
                $age_parts[] = $serial->age_year . ' ' . ($serial->age_year == 1 ? 'Year' : 'Years');
            }
            if ($serial->age_month > 0) {
                $age_parts[] = $serial->age_month . ' ' . ($serial->age_month == 1 ? 'Month' : 'Months');
            }
            if ($serial->age_day > 0) {
                $age_parts[] = $serial->age_day . ' ' . ($serial->age_day == 1 ? 'Day' : 'Days');
            }
            $age = implode(' ', $age_parts);

            $sheet->setCellValue('A' . $row, $sl++);
            $sheet->setCellValue('B' . $row, $serial->patient_name ?? '');
            $sheet->setCellValue('C' . $row, $serial->mobile_number ?? '');
            $sheet->setCellValue('D' . $row, $age);
            $sheet->setCellValue('E' . $row, $serial->gender ?? '');
            $sheet->setCellValue('F' . $row, $doctor->doctor_name ?? '');
            $sheet->setCellValue('G' . $row, $serial->serial_numaber ?? '');
            $sheet->setCellValue('H' . $row, date('d-m-Y', strtotime($serial->visiting_date)));
            $row++;
        }

        // Auto-size columns
        foreach ($columnLetters as $letter) {
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }

        // Set filename and headers for download
        $filename = 'doctor_serials_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function export_with_simple_excel($selected_serials, $company)
    {
        $this->load->library('Simple_excel');
        
        $this->simple_excel->set_filename('doctor_serials_' . date('Y-m-d') . '.csv');
        
        // Add headers
        $headers = ['Sl', 'Patient Name', 'Mobile', 'Age', 'Gender', 'Doctor', 'Serial', 'Visiting Date'];
        $this->simple_excel->add_headers($headers);
        
        // Add data
        $sl = 1;
        foreach ($selected_serials as $serial) {
            $doctor = $this->db->where('doctor_id', $serial->doctor_id)->get('doctor')->row();
            
            $age_parts = [];
            if ($serial->age_year > 0) {
                $age_parts[] = $serial->age_year . ' ' . ($serial->age_year == 1 ? 'Year' : 'Years');
            }
            if ($serial->age_month > 0) {
                $age_parts[] = $serial->age_month . ' ' . ($serial->age_month == 1 ? 'Month' : 'Months');
            }
            if ($serial->age_day > 0) {
                $age_parts[] = $serial->age_day . ' ' . ($serial->age_day == 1 ? 'Day' : 'Days');
            }
            $age = implode(' ', $age_parts);

            $row_data = [
                $sl++,
                $serial->patient_name ?? '',
                $serial->mobile_number ?? '',
                $age,
                $serial->gender ?? '',
                $doctor->doctor_name ?? '',
                $serial->serial_numaber ?? '',
                date('d-m-Y', strtotime($serial->visiting_date))
            ];
            
            $this->simple_excel->add_row($row_data);
        }
        
        $this->simple_excel->output();
    }

  
   

   
}
