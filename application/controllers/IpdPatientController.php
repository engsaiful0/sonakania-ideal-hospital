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

class IpdPatientController extends CI_Controller
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
        if (!empty($parameter)) {
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
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('patient_name', $parameter)
                ->from('ipd_patient');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('ipd_patient');
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
        if (!empty($parameter)) {
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


    public function add_opd_patient()
    {
        $page_data = array(
            'page_name' => 'ipd_patient/add_opd_patient',
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

    public function add_ipd_patient()
    {
        $page_data = array(
            'page_name' => 'ipd_patient/add_ipd_patient',
            'page_title' => 'Add Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function delete_ipd_patient_ajax()
    {
        $ipd_patient_id = $this->input->post('ipd_patient_id');
        $ipd_patient = $this->db->where('ipd_patient_id', $ipd_patient_id)->get('ipd_patient')->row();

        if (!$ipd_patient) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Patient not found.'
            ]);
            return;
        }

        //Make Bed or Cabin Available
        makeBedAvailable($ipd_patient->bed_id);
        makeCabinAvailable($ipd_patient->cabin_id);

        if ($this->db->where('ipd_patient_id', $ipd_patient_id)->delete('ipd_patient')) {
            $response = array('status' => 'success', 'message' => 'Patient deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }


    public function view_ipd_patient()
    {
        $patient_name = $this->input->post('patient_name');
        $mobile_number = $this->input->post('mobile_number');
        $patient_unique_id = $this->input->post('patient_unique_id');
        $gender = $this->input->post('gender');
        $reference_doctor_id = $this->input->post('reference_doctor_id');
        $reference_media_id = $this->input->post('reference_media_id');
        $bed_id = $this->input->post('bed_id');
        $ward_id = $this->input->post('ward_id');
        $cabin_id = $this->input->post('cabin_id');
        $status = $this->input->post('status');
        $from_date = '';
        $to_date = '';
        if ($this->input->post('from_date') != '') {
            $from_date = date('Y-m-d', strtotime($this->input->post('from_date')));
        }
        if ($this->input->post('to_date') != '') {
            $to_date = date('Y-m-d', strtotime($this->input->post('to_date')));
        }
        $config['base_url'] = base_url() . "index.php/IpdPatientController/view_ipd_patient";;
        $config['total_rows'] = $this->IpdPatientModel->count_all_ipd_patients($patient_name, $mobile_number, $patient_unique_id, $gender, $reference_media_id, $ward_id, $bed_id, $cabin_id, $reference_doctor_id, $from_date, $to_date, $status);
        $config['per_page'] = 100;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = 3;

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
        $data['detailsList'] = $this->IpdPatientModel->ipd_patient_details($this->per_page, $page, $patient_name, $mobile_number, $patient_unique_id, $gender, $reference_media_id, $ward_id, $bed_id, $cabin_id, $reference_doctor_id, $from_date, $to_date, $status);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('ipd_patient/view_ipd_patient', $data, true);
        $page_data = array(
            'page_name' => 'ipd_patient/view_ipd_patient',
            'page_title' => 'View Patient',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function ipd_patient_edit($patient_id)
    {
        $data['ipd_patient_id'] = $patient_id;
        $this->load->view('ipd_patient/ipd_patient_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'ipd_patient/ipd_patient_edit',
            'page_title' => 'IPD Patient Edit',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_ipd_patient_save()
    {
        $ipd_patient_id = $this->input->post('ipd_patient_id');
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $data = array();
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['id_serial'] = $this->input->post('id_serial');
            $this->db->insert('ipd_patient_uniqueid_table', $data);
            $patient = $this->db->where('ipd_patient_id', $ipd_patient_id)
                ->get('ipd_patient')
                ->row();
            $data = array();
            /*
          To block the seat start
         */
            //Firstly make available the previous booked seat
            makeBedAvailable($patient->bed_id);
            makeCabinAvailable($patient->cabin_id);
            //Firstly make available the previous booked seat

            $cabin_or_ward = $this->input->post('cabin_or_ward');
            $cabin_id = $this->input->post('cabin_id');
            $bed_id = $this->input->post('bed_id');
            $data['cabin_or_ward'] = $this->input->post('cabin_or_ward');


            if ($cabin_or_ward === 'Cabin') {
                // Assign to cabin
                $this->db->where('cabin_id', $cabin_id)->update('cabin', [
                    'status' => 'Not Available',
                    'ipd_patient_id' => $ipd_patient_id,
                ]);
                $data['cabin_category_id'] = $this->input->post('cabin_category_id');
                $data['cabin_id'] = $cabin_id;

                // Release any previously assigned bed
                $this->db->where('bed_id', $bed_id)->update('bed', [
                    'status' => 'Available',
                    'ipd_patient_id' => '',
                ]);
                $data['bed_id'] = null;
            } elseif ($cabin_or_ward === 'Ward') {
                // Assign to bed
                $this->db->where('bed_id', $bed_id)->update('bed', [
                    'status' => 'Not Available',
                    'ipd_patient_id' => $ipd_patient_id,
                ]);
                $data['ward_id'] = $this->input->post('ward_id');
                $data['bed_id'] = $bed_id;
                // Release any previously assigned cabin

                $this->db->where('cabin_id', $cabin_id)->update('cabin', [
                    'status' => 'Available',
                    'ipd_patient_id' => '',
                ]);
                $data['cabin_id'] = null;
            }
            /*
          To block the sea End
         */
            $data['patient_name'] = $this->input->post('patient_name');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['gender'] = $this->input->post('gender');
            $data['age_year'] = $this->input->post('age_year');
            $data['age_month'] = $this->input->post('age_month');
            $data['age_day'] = $this->input->post('age_day');
            // $data['years_or_days'] = $this->input->post('years_or_days');
            $data['address'] = $this->input->post('address');
            $data['religion_id'] = $this->input->post('religion_id');
            $data['relation'] = $this->input->post('relation');
            $data['gurdian_name'] = $this->input->post('gurdian_name');
            $data['father_or_husband_name'] = $this->input->post('father_or_husband_name');
            $data['father_or_husband_selection'] = $this->input->post('father_or_husband_selection');

            $data['reference_director_id'] = $this->input->post('reference_director_id');
            $data['reference_doctor_id'] = $this->input->post('reference_doctor_id');
            $data['under_doctor_id'] = $this->input->post('under_doctor_id');
            $data['reference_media_id'] = $this->input->post('reference_media_id');
            $data['reference_employee_id'] = $this->input->post('reference_employee_id');
            $data['remarks'] = $this->input->post('remarks');

            $data['admission_time'] = trim($this->input->post('admission_time_hour')) . ':' . trim($this->input->post('admission_time_minute')) . ':' . trim($this->input->post('admission_time_second')) . ' ' . trim($this->input->post('admission_time_meridian'));
            $data['admission_time_hour'] = $this->input->post('admission_time_hour');
            $data['admission_time_minute'] = $this->input->post('admission_time_minute');
            $data['admission_time_second'] = $this->input->post('admission_time_second');
            $data['admission_time_meridian'] = $this->input->post('admission_time_meridian');

            $data['paid_amount'] = $this->input->post('paid_amount');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['status'] = 'Admitted';
            $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $this->db->where('ipd_patient_id', $ipd_patient_id)->update('ipd_patient', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD patient updated and IPD Patient Id is=' . $ipd_patient_id
            );
            $this->db->insert('activity_log', $activity_data);

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_ipd_patient_id'] = $ipd_patient_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    private function generate_password($length = 11)
    {
        // Generate a random string
        $randomString = bin2hex(random_bytes(8)); // 16 characters hex string

        // Hash the random string using MD5
        $md5Hash = md5($randomString);

        // Trim the MD5 hash to get the desired password length
        $password = substr($md5Hash, 0, $length);

        return $password;
    }
    function sms_send($data)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;

        $sms_api = getSMSAPI();
        if ($sms_api->is_ipd_sms_send == 'yes') { // if is_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['mobile_number'];
            $message = "Dear patient, you have admitted successfully. Patient Name: "
                . $data['patient_name']
                . ", Gender: " . $data['gender']
                . ", Age: "
                . $data['age_year'] . " " . ($data['age_year'] == 1 ? "Year" : "Years") . " "
                . $data['age_month'] . " " . ($data['age_month'] == 1 ? "Month" : "Months") . " "
                . $data['age_day'] . " " . ($data['age_day'] == 1 ? "Day" : "Days") . ", "
                . $company_name;


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
                    'type' => 'IPD Patient',
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
    public function add_ipd_patient_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $doctor_id = $this->input->post('doctor_id');
            $data = array();
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['id_serial'] = $this->input->post('id_serial');
            $this->db->insert('ipd_patient_uniqueid_table', $data);

            $data = array();

            $data['patient_name'] = $this->input->post('patient_name');
            $data['mobile_number'] = $this->input->post('mobile_number');
            $data['username'] = $this->input->post('mobile_number'); //For later signin
            $data['password'] = generate_password();
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['gender'] = $this->input->post('gender');
            $data['age_year'] = $this->input->post('age_year');
            $data['age_month'] = $this->input->post('age_month');
            $data['age_day'] = $this->input->post('age_day');
            // $data['years_or_days'] = $this->input->post('years_or_days');
            $data['address'] = $this->input->post('address');
            $data['religion_id'] = $this->input->post('religion_id');
            $data['relation'] = $this->input->post('relation');
            $data['gurdian_name'] = $this->input->post('gurdian_name');
            $data['father_or_husband_name'] = $this->input->post('father_or_husband_name');
            $data['father_or_husband_selection'] = $this->input->post('father_or_husband_selection');

            $data['reference_director_id'] = $this->input->post('reference_director_id');
            $data['reference_doctor_id'] = $this->input->post('reference_doctor_id');
            $data['under_doctor_id'] = $this->input->post('under_doctor_id');
            $data['reference_media_id'] = $this->input->post('reference_media_id');
            $data['reference_employee_id'] = $this->input->post('reference_employee_id');
            $data['remarks'] = $this->input->post('remarks');
            $data['mobile_number'] = $this->input->post('mobile_number');

            $cabin_or_ward = $this->input->post('cabin_or_ward');
            $cabin_id = $this->input->post('cabin_id');
            $bed_id = $this->input->post('bed_id');



            if ($cabin_or_ward === 'Cabin') {
                // Assign to cabin
                if (!empty($cabin_id)) {
                    $data['cabin_id'] = $cabin_id;
                }
                // Release any previously assigned bed
                if (!empty($bed_id)) {
                    $data['bed_id'] = '';
                }
                $data['cabin_category_id'] = $this->input->post('cabin_category_id');
            } elseif ($cabin_or_ward === 'Ward') {
                // Assign to bed
                if (!empty($bed_id)) {
                    $data['bed_id'] = $bed_id;
                }
                // Release any previously assigned cabin
                if (!empty($cabin_id)) {
                    $data['cabin_id'] = '';
                }
                $data['ward_id'] = $this->input->post('ward_id');
            }
            $data['cabin_or_ward'] = $this->input->post('cabin_or_ward');

            $data['admission_time'] = trim($this->input->post('admission_time_hour')) . ':' . trim($this->input->post('admission_time_minute')) . ':' . trim($this->input->post('admission_time_second')) . ' ' . trim($this->input->post('admission_time_meridian'));

            $data['admission_time_hour'] = $this->input->post('admission_time_hour');
            $data['admission_time_minute'] = $this->input->post('admission_time_minute');
            $data['admission_time_second'] = $this->input->post('admission_time_second');
            $data['admission_time_meridian'] = $this->input->post('admission_time_meridian');


            $data['paid_amount'] = $this->input->post('paid_amount');
            $data['patient_unique_id'] = $this->input->post('patient_unique_id');
            $data['user_id'] = $this->session->userdata('user_id');
            $data['status'] = 'Admitted';
            $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
            $save_result = $this->db->insert('ipd_patient', $data);

            $ipd_patient_id = $this->db->insert_id();
            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data); //To send sms
            }
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'IPD patient added and IPD Patient Id is=' . $ipd_patient_id
            );
            $this->db->insert('activity_log', $activity_data);

            /*
          To block the seat start
         */
            $cabin_id = $this->input->post('cabin_id');
            if ($cabin_id != '') {
                $booked = array(
                    'status' => 'Not Available',
                    'ipd_patient_id' => $ipd_patient_id,
                );
                $this->db->where('cabin_id', $cabin_id)->update('cabin', $booked);
            }
            $bed_id = $this->input->post('bed_id');
            if ($bed_id != '') {
                $booked = array(
                    'status' => 'Not Available',
                    'ipd_patient_id' => $ipd_patient_id,
                );
                $this->db->where('bed_id', $bed_id)->update('bed', $booked);
            }
            /*
      To block the seat End
     */

            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_ipd_patient_id'] = $ipd_patient_id;
            $sdata['success'] = 'saved successfully';
            $this->session->set_userdata($sdata);
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function ipd_patient_print()
    {

        $page_data = array(
            'page_name' => 'ipd_patient/print_ipd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ipd_with_bed()
    {

        $page_data = array(
            'page_name' => 'ipd_patient/ipd_with_bed',
            'page_title' => 'IPD with Bed',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function ipd_with_cabin()
    {

        $page_data = array(
            'page_name' => 'ipd_patient/ipd_with_cabin',
            'page_title' => 'IPD with Cabin',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function ipd_patient_print_again($patient_id)
    {
        // die;
        $data['ipd_patient_id'] = $patient_id;
        $this->load->view('ipd_patient/print_ipd_patient_admission', $data, TRUE);
        $page_data = array(
            'page_name' => 'ipd_patient/print_ipd_patient_admission',
            'page_title' => 'Print Patient Admission',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bed_number_load()
    {
        $ward_id = $_POST['ward_id'];
        $beds = $this->db->where('status', 'Available')->where('ward_id', $ward_id)->get('bed')->result();
        foreach ($beds as $value) {
?>
            <option value="<?php echo $value->bed_id ?>"><?php echo $value->bed_number ?></option>
        <?php
        }
    }
    public function cabin_number_load()
    {
        $cabin_category_id = $_POST['cabin_category_id'];
        $cabins = $this->db->where('status', 'Available')->where('cabin_category_id', $cabin_category_id)->get('cabin')->result();
        foreach ($cabins as $value) {
            $cabin_category = $this->db->where('cabin_category_id', $value->cabin_category_id)->get('cabin_category')->row();
        ?>
            <option value="<?php echo $value->cabin_id ?>"><?php echo $value->cabin_number . '-' . $cabin_category->cabin_category_name . '-' . $value->cabin_rent ?></option>
<?php
        }
    }

    public function patient_data_load()
    {
        $ipd_patient_id = $_POST['ipd_patient_id'];
        $patient = $this->db->where('ipd_patient_id', $ipd_patient_id)->get('ipd_patient')->row();
        echo $patient->patient_name . '*' . $patient->mobile_number . '*' . $patient->age . '*' . $patient->date . '*' . $patient->admission_time . '*' . $patient->address;
    }
    public function patient_data_load_by_unique_id()
    {
        $patient_unique_id = $_POST['patient_unique_id'];
        $patient = $this->db->where('patient_unique_id', $patient_unique_id)->get('ipd_patient')->row();
        echo $patient->ipd_patient_id . '*' . $patient->patient_name . '*' . $patient->mobile_number . '*' . $patient->age_year . '*' . $patient->age_month . '*' . $patient->age_day . '*' . $patient->date . '*' . $patient->admission_time . '*' . $patient->address;
    }
}
