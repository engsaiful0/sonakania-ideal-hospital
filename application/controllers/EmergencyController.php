<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Laminas\Barcode\Barcode;

class EmergencyController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('EmergencyModel'); // Load your model
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }

    private $defaults = array();
    public function name_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('name', $parameter)
                ->from('emergency');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('emergency');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->name);
        }
        echo json_encode($data_patient);
    }
    public function phone_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('phone', $parameter)
                ->from('emergency');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('emergency');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->phone);
        }
        echo json_encode($data_patient);
    }
    public function emergency_invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('emergency_invoice_no', $parameter)
                ->from('emergency');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('emergency');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->emergency_invoice_no);
        }
        echo json_encode($data_patient);
    }
    public function delete_ipd_emergency_ajax()
    {
        $emergency_id = $this->input->post('emergency_id');
        if ($this->db->where('emergency_id', $emergency_id)->delete('emergency')) {
            $this->db->where('emergency_id', $emergency_id)->delete('emergency_details');
            $response = array('status' => 'success', 'message' => 'Emergency deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }

        echo json_encode($response);
    }
    public function view_emergency()
    {
        $emergency_invoice_no = $this->input->post('emergency_invoice_no');
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $gender = $this->input->post('gender');
        $reference_doctor_id = $this->input->post('reference_doctor_id');
        $reference_media_id = $this->input->post('reference_media_id');
        $status = $this->input->post('status');

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $config['base_url'] =  base_url() . "index.php/EmergencyController/view_emergency";
        $config['total_rows'] = $this->EmergencyModel->count_all_emergency($phone, $name, $emergency_invoice_no, $gender, $reference_doctor_id, $reference_media_id, $from_date, $to_date, $status);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;

        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = 5; // Number of page links to display on either side of the current page

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
        // Ensure $page is an integer or zero

        $this->per_page = $config["per_page"];
        $this->pagination->initialize($config);
        // Get medicine sales list

        $data['emergencies'] = $this->EmergencyModel->get_emergency($this->per_page, $page, $phone, $name, $emergency_invoice_no, $gender, $reference_doctor_id, $reference_media_id, $from_date, $to_date, $status);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('emergency/view_emergency', $data, true);
        $page_data = array(
            'page_name' => 'emergency/view_emergency',
            'page_title' => 'View Emergency',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function add_emergency()
    {
        $page_data = array(
            'page_name' => 'emergency/add_emergency',
            'page_title' => 'Add Emergency',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_emergency($emergency_id)
    {
        $data['emergency_id'] = $emergency_id;
        $this->load->view('emergency/edit_emergency', $data, true);
        $page_data = array(
            'page_name' => 'emergency/edit_emergency',
            'page_title' => 'Edit Emergency',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    function return_emergency($emergency_id)
    {
        $data['emergency_id'] = $emergency_id;
        $this->load->view('emergency/return_emergency', $data, true);
        $page_data = array(
            'page_name' => 'emergency/return_emergency',
            'page_title' => 'Return Emergency',
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
    function print_emergency()
    {
        $page_data = array(
            'page_name' => 'emergency/print_emergency',
            'page_title' => 'Print Emergency',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function print_emergency_with_id($emergency_id)
    {
        $sdata['print_emergerncy_id'] = $emergency_id;
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'emergency/print_emergency',
            'page_title' => 'Print Emergency',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function emergency_service_price_load()
    {
        $emergency_service_id = $_POST['emergency_service_id'];
        $emergency_service = $this->db->where('emergency_service_id', $emergency_service_id)->get('emergency_service')->row();
        echo $emergency_service->price;
    }


    function delete_this_emergency($emergency_id)
    {
        $sales_id = $this->input->post('sales_id');
        $this->db->where('emergency_id', $emergency_id)->delete('emergency');
        $this->db->where('emergency_id', $emergency_id)->delete('emergency_details');
        $sdata['emergency_deleted'] = 'Data has been deleted successfully.';
        $this->session->set_userdata($sdata);
        redirect(base_url('view-emergency'));
    }

    public function invoice_load()
    {
        $manufacture_id = $_POST['manufacture_id'];
        $sql = $this->db->where('supplier', $manufacture_id)->get('purchase')->result();
?><option>Select Invoice</option>
        <?php
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->purchase_id ?>"><?php echo $value->mrr ?></option>
<?php
        }
    }

    public function add_more_emergency_row()
    {
        $data['next_id'] = $_POST['next_id'];
        $this->load->view('emergency/more_emergency_row', $data);
    }

    public function retrun_emergency_data_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $emergency_id = $this->input->post('emergency_id');
            $data = array(
                'return_reason' => $this->input->post('return_reason'),
                'returnable_amount' => $this->input->post('returnable_amount'),
                'deduction' => $this->input->post('deduction'),
                'return_time' => $this->input->post('return_time'),
                'status' => "Returned",
                'return_user_id' => $this->session->userdata('user_id'),
                'return_date' => date('Y-m-d', strtotime($this->input->post('return_date'))),
                // Add more fields as needed
            );
            $this->db->where('emergency_id', $emergency_id)->update('emergency', $data);
            $response = array('success' => true, 'message' => 'Data has been returned successfully.');
            $sdata['print_emergerncy_id'] = $emergency_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function emergency_due_payment($emergency_id)
    {
        $data['emergency_id'] = $emergency_id;
        $this->load->view('emergency/emergency_due_payment', $data, true);
        $page_data = array(
            'page_name' => 'emergency/emergency_due_payment',
            'page_title' => 'Edit Emergency',
            'sidebar' => 'patient/patient_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function save_emergency_due_payment_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            $emergency_id = $this->input->post('emergency_id');
            
            // Get emergency record before update for SMS
            $emergency = $this->db->where('emergency_id', $emergency_id)->get('emergency')->row();
            
            // Data for main emergency table
            $data = array(
                'due_payment' => $this->input->post('due_payment') ?? null,
                'due' => 0,
                'paid_or_due_status' => 'paid',
                'due_payment_date' => $this->input->post('date') ?? null,
                'due_payment_time' => $this->input->post('due_payment_time') ?? null,
                'due_payment_user_id' => $this->session->userdata('user_id'),
            );
            // Update main emergency record
            $this->db->where('emergency_id', $emergency_id)->update('emergency', $data);

            // Log activity
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Emergency due payment updated and Emergency Id is=' . $emergency_id,
            );
            $this->db->insert('activity_log', $activity_data);
            
            // Send SMS notification for due payment
            $this->sms_send_due_payment($emergency, $this->input->post('due_payment'));
            
            // Session for printing
            $sdata['print_emergerncy_id'] = $emergency_id;
            $this->session->set_userdata($sdata);
            // Return success JSON
            echo json_encode(array('success' => true, 'message' => 'Data updated successfully.'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Invalid request.'));
        }
    }
    public function update_emergency_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {

            $paid_or_due_status='';
            if($this->input->post('due')==0)
            {
                $paid_or_due_status='paid';
            }else{
                $paid_or_due_status='due';
            }
            $emergency_id = $this->input->post('emergency_id');
            $emergency_previous_data = $this->db->where('emergency_id', $emergency_id)->get('emergency')->row();

            // Safely collect optional variables
            $ipd_patient_id = $this->input->post('ipd_patient_id');
            $doctor_id = $this->input->post('doctor_id');
            $employee_nurse_id = $this->input->post('employee_nurse_id');
            $reference_employee_id = $this->input->post('reference_employee_id');
            $reference_media_id = $this->input->post('reference_media_id');
            $reference_director_id = $this->input->post('reference_director_id');
            $reference_doctor_id = $this->input->post('reference_doctor_id');

            // Data for main emergency table
            $data = array(
                'emergency_invoice_no' => $this->input->post('emergency_invoice_no') ?? null,
                'date' => $this->input->post('date') ?? null,
                'emergency_time' => $this->input->post('emergency_time') ?? null,
                'ipd_patient_id' => !empty($ipd_patient_id) ? $ipd_patient_id : null,
                'patient_unique_id' => $this->input->post('patient_unique_id') ?? null,
                'name' => $this->input->post('name') ?? null,
                'age_year' => $this->input->post('age_year') ?? null,
                'age_month' => $this->input->post('age_month') ?? null,
                'age_day' => $this->input->post('age_day') ?? null,
                'years_or_days' => $this->input->post('years_or_days') ?? null,
                'gender' => $this->input->post('gender') ?? null,
                'phone' => $this->input->post('phone') ?? null,
                'address' => $this->input->post('address') ?? null,
                'attendant' => $this->input->post('attendant') ?? null,
                'doctor_id' => (!empty($this->input->post('doctor_id'))) ? $this->input->post('doctor_id') : null,
                'employee_nurse_id' => (!empty($this->input->post('employee_nurse_id'))) ? $this->input->post('employee_nurse_id') : null,
                'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                'total' => $this->input->post('total') ?? null,
                'discount' => $this->input->post('discount') ?? null,
                'total_discount' => $this->input->post('total_discount') ?? null,
                'director_discount_percentage' => $this->input->post('director_discount_percentage') ?? null,
                'director_discount' => $this->input->post('director_discount') ?? null,
                'discount_reference_director_id' => (!empty($this->input->post('discount_reference_director_id'))) ? $this->input->post('discount_reference_director_id') : null,
                'discount_reference_doctor_id' => (!empty($this->input->post('discount_reference_doctor_id'))) ? $this->input->post('discount_reference_doctor_id') : null,
                'discount_reference_employee_id' => (!empty($this->input->post('discount_reference_employee_id'))) ? $this->input->post('discount_reference_employee_id') : null,
                'discount_reference_media_id' => (!empty($this->input->post('discount_reference_media_id'))) ? $this->input->post('discount_reference_media_id') : null,

                'discount_reference' => $this->input->post('discount_reference') ?? null,
                'director_discount_percentage' => $this->input->post('director_discount_percentage') ?? null,
                'director_discount' => $this->input->post('director_discount') ?? null,
                'nettotal' => $this->input->post('nettotal') ?? null,
                'paid' => $this->input->post('paid') ?? null,
                'due' => $this->input->post('due') ?? null,
                'paid_or_due_status' => $paid_or_due_status,
                'user_id' => $this->session->userdata('user_id') ?? null,
            );

            // Update main emergency record
            $this->db->where('emergency_id', $emergency_id)->update('emergency', $data);

            // Emergency details handling
            $emergency_service_id = $this->input->post('emergency_service_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');

            // Remove existing emergency details for the emergency
            $this->db->where('emergency_id', $emergency_id)->delete('emergency_details');

            // Prepare new emergency details
            $emergency_details = array();
            if (is_array($emergency_service_id)) {
                for ($i = 0; $i < count($emergency_service_id); $i++) {
                    $emergency_details[] = array(
                        'emergency_id' => $emergency_id,
                        'emergency_service_id' => $emergency_service_id[$i] ?? null,
                        'price' => $price[$i] ?? null,
                        'quantity' => $quantity[$i] ?? null,
                        'date' => $this->input->post('date') ?? null,
                        'discounteach' => $discounteach[$i] ?? null,
                        'amount' => $amount[$i] ?? null,
                        'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                        'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                        'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                        'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                        'user_id' => $emergency_previous_data->user_id ?? null,
                    );
                }
            }

            // Insert new emergency details
            if (!empty($emergency_details)) {
                $this->db->insert_batch('emergency_details', $emergency_details);
            }

            // Log activity
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Emergency updated and Emergency Id is=' . $emergency_id,
            );
            $this->db->insert('activity_log', $activity_data);

            // Session for printing
            $sdata['print_emergerncy_id'] = $emergency_id;
            $this->session->set_userdata($sdata);

            // Return success JSON
            echo json_encode(array('success' => true, 'message' => 'Data updated successfully.'));
        } else {
            echo json_encode(array('error' => true, 'message' => 'Invalid request.'));
        }
    }

    public function save_emergency_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $paid_or_due_status = '';
            if ($this->input->post('due') == 0) {
                $paid_or_due_status = 'paid';
            } else {
                $paid_or_due_status = 'due';
            }
            $data = array(
                'emergency_invoice_no' => $this->input->post('emergency_invoice_no') ?? null,
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'emergency_time' => $this->input->post('emergency_time') ?? null,
                'ipd_patient_id' => (!empty($ipd_patient_id)) ? $ipd_patient_id : null,
                'patient_unique_id' => $this->input->post('patient_unique_id') ?? null,
                'name' => $this->input->post('name') ?? null,
                'age_year' => $this->input->post('age_year') ?? null,
                'age_month' => $this->input->post('age_month') ?? null,
                'age_day' => $this->input->post('age_day') ?? null,
                'years_or_days' => $this->input->post('years_or_days') ?? null,
                'gender' => $this->input->post('gender') ?? null,
                'phone' => $this->input->post('phone') ?? null,
                'address' => $this->input->post('address') ?? null,
                'attendant' => $this->input->post('attendant') ?? null,
                'doctor_id' => (!empty($this->input->post('doctor_id'))) ? $this->input->post('doctor_id') : null,
                'employee_nurse_id' => (!empty($this->input->post('employee_nurse_id'))) ? $this->input->post('employee_nurse_id') : null,
                'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                'total' => $this->input->post('total') ?? null,
                'discount' => $this->input->post('discount') ?? null,
                'total_discount' => $this->input->post('total_discount') ?? null,
                'discount_reference' => $this->input->post('discount_reference') ?? null,

                'discount_reference_director_id' => (!empty($this->input->post('discount_reference_director_id'))) ? $this->input->post('discount_reference_director_id') : null,
                'discount_reference_doctor_id' => (!empty($this->input->post('discount_reference_doctor_id'))) ? $this->input->post('discount_reference_doctor_id') : null,
                'discount_reference_employee_id' => (!empty($this->input->post('discount_reference_employee_id'))) ? $this->input->post('discount_reference_employee_id') : null,
                'discount_reference_media_id' => (!empty($this->input->post('discount_reference_media_id'))) ? $this->input->post('discount_reference_media_id') : null,

                'director_discount_percentage' => $this->input->post('director_discount_percentage') ?? null,
                'director_discount' => $this->input->post('director_discount') ?? null,
                'nettotal' => $this->input->post('nettotal') ?? null,
                'paid' => $this->input->post('paid') ?? null,
                'due' => $this->input->post('due') ?? null,
                'paid_or_due_status' => $paid_or_due_status,
                'user_id' => $this->session->userdata('user_id') ?? null,
            );
            $emergency = $this->db->insert('emergency', $data);

            $emergency_id = $this->db->insert_id();

            $emergency_service_id = $this->input->post('emergency_service_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');

            $emergency_details = array();
            for ($loop = 0; $loop < count($emergency_service_id); $loop++) {
                $emergency_details[] = array(
                    'emergency_id' => $emergency_id, // fixed typo (removed extra space)
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'emergency_service_id' => $emergency_service_id[$loop] ?? null,
                    'price' => $price[$loop] ?? null,
                    'quantity' => $quantity[$loop] ?? null,
                    'discounteach' => $discounteach[$loop] ?? null,
                    'amount' => $amount[$loop] ?? null,
                    'reference_employee_id' => (!empty($this->input->post('reference_employee_id'))) ? $this->input->post('reference_employee_id') : null,
                    'reference_media_id' => (!empty($this->input->post('reference_media_id'))) ? $this->input->post('reference_media_id') : null,
                    'reference_director_id' => (!empty($this->input->post('reference_director_id'))) ? $this->input->post('reference_director_id') : null,
                    'reference_doctor_id' => (!empty($this->input->post('reference_doctor_id'))) ? $this->input->post('reference_doctor_id') : null,
                    'user_id' => $this->session->userdata('user_id') ?? null,
                );
            }

            $invoice_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'emergency_invoice_no' => $this->input->post('emergency_invoice_no'),
                'emergency_invoice_serial' => $this->input->post('emergency_invoice_serial'),
            );
            $emergency = $this->db->insert('emergency_invoice', $invoice_data);


            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Emergency added and Emergency Id is=' . $emergency_id,
            );
            $emergency = $this->db->insert('activity_log', $activity_data);


            $save_result = $this->db->insert_batch('emergency_details', $emergency_details);

            if ($save_result) {
                $response['sms_send_status'] = $this->sms_send($data); //To send sms
            }
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            $sdata['print_emergerncy_id'] = $emergency_id;
            $this->session->set_userdata($sdata);

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function sms_send($data)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $sms_api = getSMSAPI();

        if ($sms_api->is_emergency_sms_send == 'yes') { // if is_emergency_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $data['phone'];

            $age_text = "";
            if (!empty($data['age_year'])) {
                $age_text .= $data['age_year'] . " " . ($data['age_year'] == 1 ? "Year" : "Years") . " ";
            }
            if (!empty($data['age_month'])) {
                $age_text .= $data['age_month'] . " " . ($data['age_month'] == 1 ? "Month" : "Months") . " ";
            }
            if (!empty($data['age_day'])) {
                $age_text .= $data['age_day'] . " " . ($data['age_day'] == 1 ? "Day" : "Days");
            }

            $message = "Dear Patient, you have successfully booked an emergency treatment appointment. "
                . "Patient Name: " . $data['name']
                . ", Gender: " . $data['gender']
                . ", Age: " . trim($age_text)
                . ", " . $company_name;


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
                    'type' => 'Emergency',
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
    
    function sms_send_due_payment($emergency, $due_payment_amount)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;
        $sms_api = getSMSAPI();

        if ($sms_api->is_emergency_due_sms_send == 'yes') { // if is_emergency_due_sms_send==yes, then sms will be sent
            $url = "http://bulksmsbd.net/api/smsapi";
            $api_key = $sms_api->api_key;
            $senderid = $sms_api->senderid;
            // $number = "88016xxxxxxxx,88019xxxxxxxx";
            $number = "88" . $emergency->phone;
            $message = "Dear Patient, your emergency treatment due payment has been received successfully. Patient Name: " . $emergency->name . ", Amount Paid: " . $due_payment_amount . " BDT, " . $company_name;

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
                    'type' => 'Emergency Due Payment',
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
}
