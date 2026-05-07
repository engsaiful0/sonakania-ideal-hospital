<?php

class DrugController extends CI_Controller
{

    private $defaults = array();
    private $per_page;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url', 'file');

        $this->load->library('Grocery_crud');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('pagination');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }
    public function drug_info_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('drug_name', $parameter)
                ->from('drug');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('drug');
        }
        $sql = $this->db->get()->result();
        $data_drug = array();
        foreach ($sql as $value) {
            array_push($data_drug, $value->drug_name . '(stock ' . getStock($value->drug_id) . ')');
        }
        echo json_encode($data_drug);
    }
    public function drug_info_by_drug_name()
    {
        error_reporting(0);
        $drug_name_full = $this->input->post('drug_name');
        $drug_name = explode('(stock', $drug_name_full)[0];
        
        $data = $this->db->select('*')
            ->from('drug')
            ->where('drug_name', $drug_name)
            ->get()
            ->row();
        $details = array();
        $details['drug_id'] = $data->drug_id;
        $details['drug_name'] = $data->drug_name;
        $details['mrp'] = $data->mrp;
        $details['purchase_rate'] = $data->purchase_rate;
        echo json_encode($details);
    }
    public function load_user_based_sell_report_for_user()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $user_id = $this->session->userdata('user_id');


        // Prepare data for the view
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['user_id'] = $user_id;

        // Capture the HTML output of the view
        $html = $this->load->view('drug/report/load_user_based_sell_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }
    public function load_user_based_sell_report_for_admin()
    {
        $user_id = $this->input->post('user_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        // Prepare data for the view
        $data['to_date'] = $to_date;
        $data['from_date'] = $from_date;
        $data['user_id'] = $user_id;

        // Capture the HTML output of the view
        $html = $this->load->view('drug/report/load_user_based_sell_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }
    public function drug_list()
    {
        $page_data = array(
            'page_name' => 'drug/drug_list',
            'page_title' => 'Drug List',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function drug_name_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('drug_name', $parameter)
                ->from('drug');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('drug');
        }
        $sql = $this->db->get()->result();
        $drug_array = array();
        foreach ($sql as $value) {
            array_push($drug_array, $value->drug_name);
        }
        echo json_encode($drug_array);
    }

    public function my_sale_report()
    {
        $page_data = array(
            'page_name' => 'drug/report/my_sale_report',
            'page_title' => 'Drug List',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function load_all_users_medicine_sale_report()
    {

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        // Prepare data for the view
        $data['to_date'] =  date('Y-m-d', strtotime($to_date));
        $data['from_date'] = date('Y-m-d', strtotime($from_date));


        // Capture the HTML output of the view
        $html = $this->load->view('drug/report/load_all_users_medicine_sale_report', $data, TRUE);

        // Return the output as a JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $html
        ]);
        exit;
    }
    public function all_users_medicine_sale_report()
    {
        $page_data = array(
            'page_name' => 'drug/report/all_users_medicine_sale_report',
            'page_title' => 'All User Medicine Sale Report',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function drug_list_iframe()
    {
        error_reporting(0);
        $crud = new Grocery_crud();
        $crud->set_table('drug');
        $crud->set_subject('Medicine');
        $crud->required_fields('drug_name', 'drug_type_id', 'supplier_id', 'mrp', 'purchase_rate');

        $crud->set_relation('shelf_id', 'shelfs', 'shelf_number');
        $crud->set_relation('drug_type_id', 'drug_type', 'type_name');
        $crud->columns('drug_name', 'drug_type_id', 'shelf_id', 'mrp', 'purchase_rate', 'opening_stock');
        $crud->fields('drug_name', 'drug_type_id', 'shelf_id', 'mrp', 'purchase_rate', 'opening_stock');

        $crud->display_as('drug_type_id', "Drug Type");
        $crud->display_as('shelf_id', "Shelf Number");
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }
    public function export_database()
    {
        $this->load->dbutil();
        $prefs = array(
            'format' => 'zip',
            'filename' => 'pharmacy.sql'
        );
        $backup = &$this->dbutil->backup($prefs);
        $db_name = 'maadrug_db_' . date("Y-m-d-H-i-s") . '.zip';
        $save = '../../../' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->load->helper('download');
        force_download($db_name, $backup);
    }
    public function drug_update()
    {

        $data = array();

        $data['drug_id'] = $this->input->post('drug_id');
        //print_r($data);
        //  return $data;
        $this->load->view('drug/drug_update', $data);
    }
    public function drug_add_view()
    {
        $this->load->view('drug/drug_add');
    }

    public function check_duplicate_drug_name()
    {
        $drug_name = $this->input->post('drug_name'); // Use CodeIgniter's input class to retrieve POST data
        $data_drug = [];

        if (!empty($drug_name)) {
            // Query to check for duplicates
            $this->db->select('*');
            $this->db->from('drug');
            $this->db->like('drug_name', $drug_name); // Using like for partial matches

            $query = $this->db->get();
            $data_drug = $query->result_array(); // Fetch results as an array
        }

        // Output the results as JSON
        echo json_encode($data_drug);
    }
    public function drug_update_save()
    {
        $drug_id = $this->input->post('drug_id');
        $update = array(
            'manufacturer_id' => $this->input->post('manufacturer_id'),
            'drug_name' => $this->input->post('drug_name'),
            'type' => $this->input->post('type'),
            'mrp' => $this->input->post('mrp'),
            'whole_sale_rate' => $this->input->post('whole_sale_rate'),
            'pur_rate' => $this->input->post('pur_rate'),
            'stock' => $this->input->post('stock'),
            'shelf' => $this->input->post('shelf'),
            'reorder_quantity' => $this->input->post('reorder_quantity'),
        );
        $this->db->where('drug_id', $drug_id)->update('drug', $update);
        $sdata = array('msg' => 'success');
        $this->session->set_userdata($sdata);
        redirect('Drug/alldrug_st');
    }
    public function drug_delete()
    {
        $drug_id = $this->input->post('drug_id');
        $this->db->where('drug_id', $drug_id)->delete('drug');
        $sdata = array('msg' => 'delete');
        $this->session->set_userdata($sdata);
        redirect('Drug/alldrug_st');
    }


    public function add_drug_data_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'manufacturer_id' => $this->input->post('manufacturer_id'),
                'drug_type_id' => $this->input->post('drug_type_id'),
                'drug_name' => $this->input->post('drug_name'),
                'status' => $this->input->post('status'),
                'strenght' => $this->input->post('strenght'),
                'shelf_id' => $this->input->post('shelf_id'),
                'purchase_rate' => $this->input->post('purchase_rate'),
                'mrp' => $this->input->post('mrp'),
                'whole_sale_rate' => $this->input->post('whole_sale_rate'),
                'opening_stock' => $this->input->post('opening_stock'),
                'reorder_quantity' => $this->input->post('reorder_quantity'),
                'user_id' => $this->session->userdata('user_id'),
                // Add more fields as needed
            );
            $this->db->insert('drug', $data);
            $drug_id = $this->db->insert_id();
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Drug added and Drug Id is=' . $drug_id,
            );
            $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data saved successfully.');

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function drug_stock_report()
    {
        $config = array();
        $drug_id = $this->input->post('drug_id');
        $drug_type_id = $this->input->post('drug_type_id');
        $supplier_id = $this->input->post('supplier_id');
        $drug_name = $this->input->post('drug_name');


        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/DrugController/drug_stock_report";
        $config["total_rows"] = $this->DrugModel->count_all_drugs($drug_type_id, $drug_id, $supplier_id, $drug_name);
        $config["per_page"] = 100;
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
        $data['detailsList'] = $this->DrugModel->all_drugs_get($config['per_page'], $page, $drug_type_id, $drug_id, $supplier_id, $drug_name);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'drug/drug_stock_report';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }
    // Update opening stock for a single drug
    public function update_opening_stock_ajax()
    {
        $drug_id = $this->input->post('drug_id');
        $stock_value = $this->input->post('stock_value');

        if (!is_numeric($stock_value) || $stock_value < 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid stock value.']);
            return;
        }

        $this->db->where('drug_id', $drug_id);
        $updated = $this->db->update('drug', ['opening_stock' => $stock_value]);

        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Opening stock updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update opening stock.']);
        }
    }

    // Update opening stock for multiple selected drugs
    public function update_selected_opening_stock_ajax()
    {
        $selectedRows = $this->input->post('selectedRows');

        if (empty($selectedRows)) {
            echo json_encode(['status' => 'error', 'message' => 'No rows selected.']);
            return;
        }

        foreach ($selectedRows as $row) {
            $drug_id = $row['drug_id'];
            $stock_value = $row['stock_value'];

            if (!is_numeric($stock_value) || $stock_value < 0) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid stock value for drug ' . $drug_id]);
                return;
            }

            // Update stock for each selected drug
            $this->db->where('drug_id', $drug_id);
            $this->db->update('drug', ['opening_stock' => $stock_value]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Selected stocks updated successfully.']);
    }

    public function view_drug()
    {
        $config = array();
        $drug_id = $this->input->post('drug_id');
        $drug_type_id = $this->input->post('drug_type_id');
        $manufacturer_id = $this->input->post('manufacturer_id');
        $drug_name = $this->input->post('drug_name');
      

        // Default per_page if not set
        $per_page = $this->input->post('per_page') ? $this->input->post('per_page') : 100;

        // Pagination setup
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/DrugController/view_drug";
        $config["total_rows"] = $this->DrugModel->count_all_drugs_for_view($drug_type_id, $drug_id, $manufacturer_id, $drug_name);
        $config["per_page"] = $per_page;  // Dynamic per page
        $config["uri_segment"] = 3;

        // Pagination style
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
        $config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        // Fetch data with limit and offset
        $data['detailsList'] = $this->DrugModel->all_drugs_get_for_view($config['per_page'], $page, $drug_type_id, $drug_id, $manufacturer_id, $drug_name);

        // Create pagination links
        $data['pagination'] = $this->pagination->create_links();

        // Pass the selected per_page value to the view
        $data['per_page'] = $per_page;

        // Load the view
        $data['page_name'] = 'drug/view_drug';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'pharmacy/pharmacy_sidebar';
        $this->load->view('content', $data);
    }


    function add_drug()
    {
        $page_data = array(
            'page_name' => 'drug/add_drug',
            'page_title' => 'Add Emergency',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    function edit_drug($drug_id)
    {
        $data['drug_id'] = $drug_id;
        $this->load->view('drug/edit_drug', $data, true);
        $page_data = array(
            'page_name' => 'drug/edit_drug',
            'page_title' => 'Edit Emergency',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }


    public function drug_delete_ajax()
    {
        $drug_id = $this->input->post('drug_id');
        if ($this->db->where('drug_id', $drug_id)->delete('drug')) {
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }



    public function edit_drug_data_save()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $drug_id = $this->input->post('drug_id');
            $data = array(
                'manufacturer_id' => $this->input->post('manufacturer_id'),
                'drug_type_id' => $this->input->post('drug_type_id'),
                'drug_name' => $this->input->post('drug_name'),
                'status' => $this->input->post('status'),
                'strenght' => $this->input->post('strenght'),
                'shelf_id' => $this->input->post('shelf_id'),
                'purchase_rate' => $this->input->post('purchase_rate'),
                'mrp' => $this->input->post('mrp'),
                'whole_sale_rate' => $this->input->post('whole_sale_rate'),
                'opening_stock' => $this->input->post('opening_stock'),
                'reorder_quantity' => $this->input->post('reorder_quantity'),
                // Add more fields as needed
            );

            $this->db->where('drug_id', $drug_id)->update('drug', $data);

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Drug updated and Emergency Id is=' . $drug_id,
            );
            $emergency = $this->db->insert('activity_log', $activity_data);
            $response = array('success' => true, 'message' => 'Data updated successfully.');

            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    public function save_emergency_data()
    {
        // Check if it's an AJAX request
        if ($this->input->is_ajax_request()) {
            // Get form data
            $data = array(
                'emergency_invoice_no' => $this->input->post('emergency_invoice_no'),
                'date' => $this->input->post('date'),
                'ipd_patient_id' => $this->input->post('ipd_patient_id'),
                'patient_unique_id' => $this->input->post('patient_unique_id'),
                'name' => $this->input->post('name'),
                'age' => $this->input->post('age'),
                'gender' => $this->input->post('gender'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'attendant' => $this->input->post('attendant'),
                'doctor_id' => $this->input->post('doctor_id'),
                'reference_employee_id' => $this->input->post('reference_employee_id'),
                'reference_media_id' => $this->input->post('reference_media_id'),
                'reference_director_id' => $this->input->post('reference_director_id'),
                'reference_doctor_id' => $this->input->post('reference_doctor_id'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'director_discount_percentage' => $this->input->post('director_discount_percentage'),
                'director_discount' => $this->input->post('director_discount'),
                'nettotal' => $this->input->post('nettotal'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
                // Add more fields as needed
            );

            $emergency = $this->db->insert('emergency', $data);

            $emergency_id = $this->db->insert_id();

            $emergency_service_id = $this->input->post('emergency_service_id');
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $discounteach = $this->input->post('discounteach');
            $amount = $this->input->post('amount');

            $emerygency_details = array();
            for ($loop = 0; $loop < count($emergency_service_id); $loop++) {
                $emerygency_details[] = array(
                    'emergency_id ' => $emergency_id,
                    'emergency_service_id' => $emergency_service_id[$loop],
                    'price' => $price[$loop],
                    'quantity' => $quantity[$loop],
                    'discounteach' => $discounteach[$loop],
                    'amount' => $amount[$loop],
                    'user_id' => $this->session->userdata('user_id'),
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


            $this->db->insert_batch('emergency_details', $emerygency_details);
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
}
