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
class TestResultController extends CI_Controller
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
        $this->load->database();
        $this->load->helper('url');


        $this->load->library('pagination');
        $this->load->model('TestResultModel');
    }
    public function patient_unique_id_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('patient_unique_id', $parameter)
                ->from('patient_test_entry');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('patient_test_entry');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        echo json_encode($data_patient);
    }

    public function test_name_load()
    {
        $test_group_id = $this->input->post('test_group_id', true);
        $allow_all_when_no_group = ((string) $this->input->post('allow_all_when_no_group', true) === '1');

        if ($test_group_id === null || $test_group_id === '' || !ctype_digit((string) $test_group_id)) {
            if ($allow_all_when_no_group) {
                $tests = $this->db->order_by('test_name', 'ASC')->get('test')->result();
                ?>
        <option value="">All tests</option>
                <?php
                foreach ($tests as $value) {
                    ?>
            <option value="<?php echo (int) $value->test_id; ?>"><?php echo html_escape($value->test_name); ?></option>
                    <?php
                }
            } else {
                echo '<option value="">All tests in group</option>';
            }
            return;
        }

        $test = $this->db->where('test_group_id', (int) $test_group_id)->order_by('test_name', 'ASC')->get('test')->result();
        ?>
        <option value="">All tests in group</option>
        <?php
        foreach ($test as $value) {
        ?>
            <option value="<?php echo (int) $value->test_id; ?>"><?php echo html_escape($value->test_name); ?></option>
            <?php
        }
    }

    public function patient_unique_id_load_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('patient_unique_id', $parameter)
                ->from('patient_test_entry');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('patient_test_entry');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->patient_unique_id);
        }
        echo json_encode($data_patient);
    }
    public function invoice_no_load()
    {
        $parameter = $_POST['parameter'];
        if (!empty($parameter)) {
            // Query with condition
            $this->db->select('')
                ->like('invoice_no', $parameter)
                ->from('test_result');
        } else {
            // Query without condition
            $this->db->select('')
                ->from('test_result');
        }
        $sql = $this->db->get()->result();
        $data_patient = array();
        foreach ($sql as $value) {
            array_push($data_patient, $value->invoice_no);
        }
        echo json_encode($data_patient);
    }

    public function test_configuration_load()
    {
        $test_group_id = $this->input->post('test_group_id', true);
        $patient_test_entry_id = $this->input->post('patient_test_entry_id', true);

        if ($patient_test_entry_id === null || $patient_test_entry_id === '' || !ctype_digit((string) $patient_test_entry_id)) {
            echo '<p class="text-warning">Enter a valid invoice to load patient tests.</p>';
            return;
        }

        $apply_group = ($test_group_id !== null && $test_group_id !== '' && ctype_digit((string) $test_group_id));

        $this->db->select('test.test_name,test.test_id');
        $this->db->from('test');
        $this->db->join('patient_test_entry_details', 'patient_test_entry_details.test_id = test.test_id');
        $this->db->where('patient_test_entry_details.patient_test_entry_id', (int) $patient_test_entry_id);
        if ($apply_group) {
            $this->db->where('test.test_group_id', (int) $test_group_id);
        }
        $this->db->where('test.name_only', 'no');
        $this->db->where('test.setting_type', 'Normal');

        $query = $this->db->get();
        $test_configuration = $query->result();
        $check = 1;
        foreach ($test_configuration as $value) {
            //            $test = $this->db->where('test_id', $value->test_id)->get('test')->row();
            if ($check % 2 != 0) :
            ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-6" for="name"><?php echo $value->test_name ?></label>
                            <div class="col-sm-3">
                                <input type="hidden" class="form-control" value="<?php echo $value->test_id ?>" id="test_id" name="test_id[]">
                                <!--<input type="hidden"  class="form-control" value="<?php echo $value->test_configuration_id ?>"  id="test_configuration_id"  name="test_configuration_id[]">-->
                                <input type="text" class="form-control" id="test_configuration_value" name="test_configuration_value[]">
                            </div>
                            <div class="col-sm-3" style="display: none;">
                                <select class="form-control" name="bold[]">
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php
            endif;
            if ($check % 2 == 0) :
                ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-6" for="name"><?php echo $value->test_name ?></label>
                            <div class="col-sm-3">
                                <input type="hidden" class="form-control" value="<?php echo $value->test_id ?>" id="test_id" name="test_id[]">
                                <!--<input type="hidden"  class="form-control" value="<?php echo $value->test_configuration_id ?>"  id="test_configuration_id"  name="test_configuration_id[]">-->
                                <input type="text" class="form-control" id="test_configuration_value" name="test_configuration_value[]">
                            </div>
                            <div class="col-sm-3" style="display: none;">
                                <select class="form-control" name="bold[]">
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
<?php
            endif;
            $check++;
        }
    }

    public function edit_biochemical_test($biomedical_test_id)
    {
        $data['biomedical_test_id'] = $biomedical_test_id;
        $this->load->view('test_result/edit_biochemical_test', $data);
    }

    public function add_biochemical_test()
    {
        $page_data = array(
            'page_name' => 'test_result/add_biochemical_test',
            'page_title' => 'Add Biochemical Test Data',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_hormone_test()
    {
        $page_data = array(
            'page_name' => 'test_result/add_hormone_test',
            'page_title' => 'Add Hormone Test',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_urine_test()
    {
        $page_data = array(
            'page_name' => 'test_result/add_urine_test',
            'page_title' => 'Add Urine Test',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function test_result_report_print()
    {
        $page_data = array(
            'page_name' => 'test_result/test_result_report_print',
            'page_title' => 'Test  Result Report Print',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_result_edit($test_result_id)
    {
        $test_result_id = (int) $test_result_id;
        if ($test_result_id < 1) {
            show_404();
        }

        $entry_detail = $this->TestResultModel->get_entry_detail_id_for_test_result($test_result_id);
        if ($entry_detail) {
            redirect('enter-test-result/' . (int) $entry_detail->patient_test_entry_details_id);
            return;
        }

        $data['test_result_id'] = $test_result_id;
        $this->load->view('test_result/test_result_edit', $data, TRUE);
        $page_data = array(
            'page_name' => 'test_result/test_result_edit',
            'page_title' => 'Add Test Result',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_test_result()
    {
        $invoice_id = trim((string) $this->input->get('invoice_id', true));
        $mobile_number = trim((string) $this->input->get('mobile_number', true));
        $test_name = trim((string) $this->input->get('test_name', true));
        $offset_seg = $this->uri->segment(3);
        $offset = ($offset_seg !== null && $offset_seg !== '' && ctype_digit((string) $offset_seg)) ? (int) $offset_seg : 0;

        $config = array();
        $config['base_url'] = site_url('TestResultController/add_test_result');
        $config['reuse_query_string'] = true;
        $config['total_rows'] = $this->TestResultModel->count_add_test_result_entries($invoice_id, $mobile_number, $test_name);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
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
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i> Previous';
        $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $page_data = array(
            'detailsList' => $this->TestResultModel->get_add_test_result_entries($config['per_page'], $offset, $invoice_id, $mobile_number, $test_name),
            'pagination' => $this->pagination->create_links(),
            'sl_start' => $offset + 1,
            'filter_invoice_id' => $invoice_id,
            'filter_mobile_number' => $mobile_number,
            'filter_test_name' => $test_name,
            'page_name' => 'test_result/add_test_result',
            'page_title' => 'Add Test Result',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_test_result_list_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $invoice_id = trim((string) $this->input->get_post('invoice_id', true));
        $mobile_number = trim((string) $this->input->get_post('mobile_number', true));
        $test_name = trim((string) $this->input->get_post('test_name', true));
        $offset_raw = $this->input->get_post('offset', true);
        $offset = ($offset_raw !== null && $offset_raw !== '' && ctype_digit((string) $offset_raw)) ? (int) $offset_raw : 0;

        $config = array();
        $config['base_url'] = site_url('TestResultController/add_test_result');
        $config['reuse_query_string'] = true;
        $config['total_rows'] = $this->TestResultModel->count_add_test_result_entries($invoice_id, $mobile_number, $test_name);
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
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
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i> Previous';
        $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $payload = array(
            'success' => true,
            'html' => $this->load->view('test_result/partials/add_test_result_list', array(
                'detailsList' => $this->TestResultModel->get_add_test_result_entries($config['per_page'], $offset, $invoice_id, $mobile_number, $test_name),
                'pagination' => $this->pagination->create_links(),
                'sl_start' => $offset + 1,
            ), true),
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($payload));
    }

    public function add_test_result_entry_form_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $entry_detail_id = (int) $this->input->post('patient_test_entry_details_id', true);
        $entry = $this->TestResultModel->get_add_test_result_entry($entry_detail_id);
        if (!$entry) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Selected test entry was not found.')));
            return;
        }

        if ((int) $entry->existing_test_result_id > 0) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'Result already exists for this test. Use edit if needed.',
                    'existing_test_result_id' => (int) $entry->existing_test_result_id,
                    'print_url' => site_url('TestResultController/test_result_report_print_again/' . (int) $entry->existing_test_result_id),
                )));
            return;
        }

        $html = $this->load->view('test_result/partials/add_test_result_entry_form', array(
            'entry' => $entry,
        ), true);

        $this->output->set_content_type('application/json')
            ->set_output(json_encode(array('success' => true, 'html' => $html)));
    }

    /**
     * Full-page test result entry for one patient_test_entry_details row.
     */
    public function enter_test_result($patient_test_entry_details_id = 0)
    {
        $entry = $this->TestResultModel->get_add_test_result_entry((int) $patient_test_entry_details_id);
        if (!$entry) {
            show_404();
        }

        if ($this->TestResultModel->entry_is_panel_test($entry)) {
            $this->enter_panel_test_result_page($entry);
            return;
        }

        $patient = $this->db->where('patient_test_entry_id', (int) $entry->patient_test_entry_id)
            ->get('patient_test_entry')
            ->row();
        if (!$patient) {
            show_404();
        }

        $test_result_id = (int) $entry->existing_test_result_id;
        if ($test_result_id < 1) {
            $test_result_id = $this->TestResultModel->find_existing_test_result_id(
                (int) $entry->patient_test_entry_id,
                (int) $entry->test_id
            );
        }
        $edit_mode = $test_result_id > 0;
        $test_result = null;
        $existing_value = '';
        $test_result_no = '';

        if ($edit_mode) {
            $test_result = $this->db->where('test_result_id', $test_result_id)->get('test_result')->row();
            if (!$test_result) {
                show_404();
            }
            $test_result_no = $test_result->test_result_no;
            $detail = $this->db->where('test_result_id', $test_result_id)
                ->where('test_id', (int) $entry->test_id)
                ->where('IFNULL(is_deleted, 0) =', 0, false)
                ->order_by('test_result_details_id', 'DESC')
                ->limit(1)
                ->get('test_result_details')
                ->row();
            if (!$detail) {
                $detail = $this->db->where('test_result_id', $test_result_id)
                    ->where('test_id', (int) $entry->test_id)
                    ->order_by('test_result_details_id', 'DESC')
                    ->limit(1)
                    ->get('test_result_details')
                    ->row();
            }
            if ($detail) {
                $existing_value = $detail->test_configuration_value;
            }
        } else {
            $serial = $this->db->select('*')->get('test_result');
            $test_result_no = 'TR' . str_pad($serial->num_rows() + 1, 5, '0', STR_PAD_LEFT);
        }

        $page_data = array(
            'page_name' => 'test_result/enter_test_result',
            'page_title' => $edit_mode ? 'Edit Test Result' : 'Enter Test Result',
            'sidebar' => 'test_result/test_result_sidebar',
            'entry' => $entry,
            'patient' => $patient,
            'test_result' => $test_result,
            'test_result_no' => $test_result_no,
            'test_result_id' => $test_result_id,
            'existing_value' => $existing_value,
            'edit_mode' => $edit_mode,
            'referring_doctor_label' => $this->format_referring_doctor_label($entry),
            'back_url' => site_url('add-test-result'),
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Panel test result entry (sections/parameters from test_panels settings).
     *
     * @param object $entry Row from TestResultModel::get_add_test_result_entry()
     */
    private function enter_panel_test_result_page($entry)
    {
        $this->load->model('Report_model');

        if ((int) $entry->existing_lab_report_id > 0) {
            redirect('panel-test-edit/' . (int) $entry->existing_lab_report_id);
            return;
        }

        $panel = $this->Report_model->resolve_panel_for_test(
            isset($entry->test_name) ? $entry->test_name : '',
            isset($entry->test_group_id) ? (int) $entry->test_group_id : 0
        );
        if (!$panel && isset($entry->resolved_panel_id) && (int) $entry->resolved_panel_id > 0) {
            $panel = $this->Report_model->get_panel((int) $entry->resolved_panel_id);
        }
        if (!$panel) {
            show_error(
                'Panel test settings were not found for "' . html_escape(isset($entry->test_name) ? $entry->test_name : '') . '". '
                . 'Configure a matching panel in Test Settings (panel name must match the test name).',
                404
            );
            return;
        }

        $existing = $this->Report_model->find_lab_report_for_invoice_and_panel(
            isset($entry->invoice_no) ? $entry->invoice_no : '',
            (int) $panel->id
        );
        if ($existing) {
            redirect('panel-test-edit/' . (int) $existing->id);
            return;
        }

        $patient = $this->db->where('patient_test_entry_id', (int) $entry->patient_test_entry_id)
            ->get('patient_test_entry')
            ->row();
        if (!$patient) {
            show_404();
        }

        $panel_test_group_id = (int) $entry->test_group_id;
        if ($this->db->field_exists('test_group_id', 'test_panels')
            && isset($panel->test_group_id) && (int) $panel->test_group_id > 0) {
            $panel_test_group_id = (int) $panel->test_group_id;
        }

        $page_data = array(
            'page_name' => 'test_result/enter_panel_test_result',
            'page_title' => 'Enter Panel Test Result',
            'sidebar' => 'test_result/test_result_sidebar',
            'entry' => $entry,
            'patient' => $patient,
            'panel' => $panel,
            'panel_id' => (int) $panel->id,
            'panel_test_group_id' => $panel_test_group_id,
            'require_lab_test_group' => $this->db->field_exists('test_group_id', 'lab_reports'),
            'referring_doctor_label' => $this->format_referring_doctor_label($entry),
            'back_url' => site_url('add-test-result'),
        );
        $this->load->view('content', $page_data);
    }

    /**
     * @param object $entry
     * @return string
     */
    private function format_referring_doctor_label($entry)
    {
        $ref_name = isset($entry->referring_doctor_name) ? trim((string) $entry->referring_doctor_name) : '';
        $ref_degree = isset($entry->referring_doctor_degree) ? trim((string) $entry->referring_doctor_degree) : '';
        if ($ref_name !== '' && $ref_degree !== '') {
            return $ref_name . ', ' . $ref_degree;
        }
        if ($ref_name !== '') {
            return $ref_name;
        }

        return $ref_degree;
    }

    public function view_biomedical_test()
    {
        $biomedical_test_no = $this->input->post('biomedical_test_no');
        $patient_test_entry_id = $this->input->post('patient_test_entry_id');

        $config['base_url'] = site_url('TestResultController/biomedical_test');
        $config['total_rows'] = $this->db->count_all('biomedical_test');
        $config['per_page'] = "50";
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
        $data['detailsList'] = $this->ProductModel->biomedical_test_details($config["per_page"], $data['page'], $biomedical_test_no, $patient_test_entry_id);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('test_result/view_biomedical_test', $data, true);
        $page_data = array(
            'page_name' => 'test_result/view_biomedical_test',
            'page_title' => 'View Biomedical Test',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function view_test_result()
    {
        $config = array();
        $test_group_id = $this->input->post('test_group_id');
        $invoice_no = $this->input->post('invoice_no');

        $test_id = $this->input->post('test_id');
        $patient_test_entry_id = $this->input->post('patient_test_entry_id');

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        // Configure pagination
        $config['base_url'] = base_url() . "/index.php/TestResultController/view_test_result";
        $config["total_rows"] = $this->TestResultModel->count_all_test_result($test_group_id, $test_id, $patient_test_entry_id, $invoice_no);
        $config["per_page"] = 50;
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
        $data['detailsList'] = $this->TestResultModel->get_test_result_details($config['per_page'], $page, $test_group_id, $test_id, $patient_test_entry_id, $invoice_no);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'test_result/view_test_result';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'test_result/test_result_sidebar';
        $this->load->view('content', $data);
    }




    public function patient_info_load()
    {
        error_reporting(0);
        $patient_test_entry_id = $_POST['patient_test_entry_id'];
        $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
        $doctor = $this->db->where('doctor_id', $patient_test_entry->doctor_id)->get('doctor')->row();
        echo $patient_test_entry->age . '_' . $patient_test_entry->invoice_no . '_' . date('Y-m-d', strtotime($patient_test_entry->date)) . '_' . $patient_test_entry->mobile . '_' . $patient_test_entry->gender . '_' . $doctor->doctor_name;
    }
    public function patient_data_load_by_test_invoice_no()
    {

        $invoice_no = $_POST['invoice_no'];
        $patient_test_entry = $this->db->where('invoice_no', $invoice_no)->get('patient_test_entry')->row();

        echo $patient_test_entry->patient_test_entry_id . '*' . $patient_test_entry->patient_name . '*' . $patient_test_entry->mobile_number . '*' . $patient_test_entry->age . '*' . $patient_test_entry->gender . '*' . date('Y-m-d', strtotime($patient_test_entry->date)) . '*' . $patient_test_entry->time . '*' . $patient_test_entry->age_year . '*' . $patient_test_entry->age_month . '*' . $patient_test_entry->age_day;
    }

    public function edit_biomedical_data_save()
    {
        $biomedical_test_id = $this->input->post('biomedical_test_id');
        $data = array(
            'patient_test_entry_id' => $this->input->post('patient_test_entry_id'),
            'fasting_food_sugar' => $this->input->post('fasting_food_sugar'),
            's_creatinine' => $this->input->post('s_creatinine'),
            'corresponding_urine_sugar_fasting_food_sugar' => $this->input->post('corresponding_urine_sugar_fasting_food_sugar'),
            'HbA1c' => $this->input->post('HbA1c'),
            'post_prandial_blood_sugar_ppbs_al_ad' => $this->input->post('post_prandial_blood_sugar_ppbs_al_ad'),
            't_billirubin' => $this->input->post('t_billirubin'),
            'corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad' => $this->input->post('corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad'),
            's_alt_sgpt' => $this->input->post('s_alt_sgpt'),
            'two_hours_after_75gm_glucose' => $this->input->post('two_hours_after_75gm_glucose'),
            's_ast_sgot' => $this->input->post('s_ast_sgot'),
            'corresponding_urine_sugar_2hours_after_75gm_glucose' => $this->input->post('corresponding_urine_sugar_2hours_after_75gm_glucose'),
            'alk_phosphates' => $this->input->post('alk_phosphates'),
            'random_blood_sugar' => $this->input->post('random_blood_sugar'),
            's_uric_acid' => $this->input->post('s_uric_acid'),
            'corresponding_urine_sugar_random_blood_sugar' => $this->input->post('corresponding_urine_sugar_random_blood_sugar'),
            'serum_electrolytes_sodium_na_plus' => $this->input->post('serum_electrolytes_sodium_na_plus'),
            'lipid_profile_f_s_cholesterol' => $this->input->post('lipid_profile_f_s_cholesterol'),
            'serum_electrolytes_potassium_k_plus' => $this->input->post('serum_electrolytes_potassium_k_plus'),
            'lipid_profile_f_s_hdl_cholesterol' => $this->input->post('lipid_profile_f_s_hdl_cholesterol'),
            'serum_electrolytes_chloride_cl_minus' => $this->input->post('serum_electrolytes_chloride_cl_minus'),
            'lipid_profile_f_s_ldl_cholesterol' => $this->input->post('lipid_profile_f_s_ldl_cholesterol'),
            'lipid_profile_f_triglyceride_tg_cholesterol' => $this->input->post('lipid_profile_f_triglyceride_tg_cholesterol'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->where('biomedical_test_id', $biomedical_test_id)->update('biomedical_test', $data);
        $data['biomedical_test_id'] = $biomedical_test_id;
        $this->load->view('test_result/biomedical_test_report_print', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'test_result/biomedical_test_report_print',
            'page_title' => 'Biomedical Test Report Print',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Whether test_configuration already has a row for this test_id.
     *
     * @param int $test_id
     * @param int $exclude_configuration_id  Current row when editing (0 on add).
     * @return bool
     */
    private function test_configuration_exists_for_test($test_id, $exclude_configuration_id = 0)
    {
        $test_id = (int) $test_id;
        if ($test_id < 1) {
            return false;
        }
        $this->db->where('test_id', $test_id);
        $exclude_configuration_id = (int) $exclude_configuration_id;
        if ($exclude_configuration_id > 0) {
            $this->db->where('test_configuration_id !=', $exclude_configuration_id);
        }

        return ((int) $this->db->count_all_results('test_configuration')) > 0;
    }

    /**
     * AJAX: check duplicate configuration for a test (by test_id).
     * POST: test_id, optional test_configuration_id (edit).
     */
    public function test_configuration_duplicate_check()
    {
        if (!$this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Invalid request.')));
            return;
        }

        $test_id = (int) $this->input->post('test_id', true);
        $exclude_id = (int) $this->input->post('test_configuration_id', true);
        if ($test_id < 1) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => true, 'exists' => false)));
            return;
        }

        $exists = $this->test_configuration_exists_for_test($test_id, $exclude_id);
        $test_name = '';
        if ($exists) {
            $row = $this->db->select('test_name')->where('test_id', $test_id)->get('test')->row();
            $test_name = ($row && isset($row->test_name)) ? (string) $row->test_name : '';
        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => true,
                'exists' => $exists,
                'test_name' => $test_name,
                'message' => $exists
                    ? 'Configuration for this test already exists' . ($test_name !== '' ? ' (' . $test_name . ').' : '.')
                    : '',
            )));
    }

    public function edit_test_configuration_save()
    {
        $test_configuration_id = $this->input->post('test_configuration_id');
        if ($this->input->is_ajax_request()) {
            $config['upload_path'] = 'assets/manual_report/';
            $config['allowed_types'] = 'gif|jpg|png|pdf';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $manual_report = '';
            $this->upload->do_upload('manual_report');
            $sdata = $this->upload->data();
            $manual_report = $sdata['file_name'];
            if ($manual_report != '') {
                $manual_report = $this->input->post('invoice_no') . '_' . $sdata['file_name'];
            }
            if ($manual_report == '') {
                $manual_report = $this->input->post('manual_report_previous');
            }

            $test_id = (int) $this->input->post('test_id', true);
            if ($test_id < 1) {
                echo json_encode(array('success' => false, 'message' => 'Please select a test name.'));
                return;
            }
            if ($this->test_configuration_exists_for_test($test_id, (int) $test_configuration_id)) {
                $test_row = $this->db->select('test_name')->where('test_id', $test_id)->get('test')->row();
                $label = ($test_row && isset($test_row->test_name)) ? $test_row->test_name : 'this test';
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Configuration already exists for: ' . $label,
                ));
                return;
            }

            $data = array(
                'test_group_id' => $this->input->post('test_group_id'),
                //            'test_parameter' => $this->input->post('test_parameter'),
                'test_id' => $test_id,
                'unit' => $this->input->post('unit'),
                'normal_range' => $this->input->post('normal_range'),
                'absolute_value' => $this->input->post('absolute_value'),
            );
            $this->db->where('test_configuration_id', $test_configuration_id)->update('test_configuration', $data);
            $sdata['updated'] = 'saved successully';
            $this->session->set_userdata($sdata);
            $response = array('success' => true, 'message' => 'Data has  been updated successfully.');
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function test_configuration_save()
    {
        if ($this->input->is_ajax_request()) {

            $test_id = (int) $this->input->post('test_id', true);
            if ($test_id < 1) {
                echo json_encode(array('success' => false, 'message' => 'Please select a test name.'));
                return;
            }
            if ($this->test_configuration_exists_for_test($test_id)) {
                $test_row = $this->db->select('test_name')->where('test_id', $test_id)->get('test')->row();
                $label = ($test_row && isset($test_row->test_name)) ? $test_row->test_name : 'this test';
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Configuration already exists for: ' . $label,
                ));
                return;
            }

            $data = array(
                'test_group_id' => $this->input->post('test_group_id'),
                //            'test_parameter' => $this->input->post('test_parameter'),
                'test_id' => $test_id,
                'unit' => $this->input->post('unit'),
                'normal_range' => $this->input->post('normal_range'),
                'absolute_value' => $this->input->post('absolute_value'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->insert('test_configuration', $data);

            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }

    public function add_biomedical_data_save()
    {
        $data = array(
            'patient_test_entry_id' => $this->input->post('patient_test_entry_id'),
            'fasting_food_sugar' => $this->input->post('fasting_food_sugar'),
            's_creatinine' => $this->input->post('s_creatinine'),
            'corresponding_urine_sugar_fasting_food_sugar' => $this->input->post('corresponding_urine_sugar_fasting_food_sugar'),
            'HbA1c' => $this->input->post('HbA1c'),
            'post_prandial_blood_sugar_ppbs_al_ad' => $this->input->post('post_prandial_blood_sugar_ppbs_al_ad'),
            't_billirubin' => $this->input->post('t_billirubin'),
            'corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad' => $this->input->post('corrponding_urine_sugar_post_prandial_blood_sugar_ppbs_al_ad'),
            's_alt_sgpt' => $this->input->post('s_alt_sgpt'),
            'two_hours_after_75gm_glucose' => $this->input->post('two_hours_after_75gm_glucose'),
            's_ast_sgot' => $this->input->post('s_ast_sgot'),
            'corresponding_urine_sugar_2hours_after_75gm_glucose' => $this->input->post('corresponding_urine_sugar_2hours_after_75gm_glucose'),
            'alk_phosphates' => $this->input->post('alk_phosphates'),
            'random_blood_sugar' => $this->input->post('random_blood_sugar'),
            's_uric_acid' => $this->input->post('s_uric_acid'),
            'corresponding_urine_sugar_random_blood_sugar' => $this->input->post('corresponding_urine_sugar_random_blood_sugar'),
            'serum_electrolytes_sodium_na_plus' => $this->input->post('serum_electrolytes_sodium_na_plus'),
            'lipid_profile_f_s_cholesterol' => $this->input->post('lipid_profile_f_s_cholesterol'),
            'serum_electrolytes_potassium_k_plus' => $this->input->post('serum_electrolytes_potassium_k_plus'),
            'lipid_profile_f_s_hdl_cholesterol' => $this->input->post('lipid_profile_f_s_hdl_cholesterol'),
            'serum_electrolytes_chloride_cl_minus' => $this->input->post('serum_electrolytes_chloride_cl_minus'),
            'lipid_profile_f_s_ldl_cholesterol' => $this->input->post('lipid_profile_f_s_ldl_cholesterol'),
            'lipid_profile_f_triglyceride_tg_cholesterol' => $this->input->post('lipid_profile_f_triglyceride_tg_cholesterol'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->insert('biomedical_test', $data);
        $biomedical_test_id = $this->db->insert_id();

        $data['biomedical_test_id'] = $biomedical_test_id;
        $this->load->view('test_result/biomedical_test_report_print', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'test_result/biomedical_test_report_print',
            'page_title' => 'Biomedical Test Report Print',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_test_result_data_save()
    {
        if (!$this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Invalid request.')));
            return;
        }

        $test_result_id = (int) $this->input->post('test_result_id');
        if ($test_result_id < 1) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Invalid test result.')));
            return;
        }

        $manual_report = $this->input->post('manual_report_previous');
        if (!empty($_FILES['manual_report']['name'])) {
            $config['upload_path'] = 'assets/manual_report/';
            $config['allowed_types'] = 'gif|jpg|png|pdf';
            $config['overwrite'] = false;
            $config['encrypt_name'] = false;
            $_FILES['manual_report']['name'] = $this->input->post('invoice_no') . '_' . $_FILES['manual_report']['name'];
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('manual_report')) {
                $upload_data = $this->upload->data();
                $manual_report = $upload_data['file_name'];
            }
        }

        $data = array(
            'patient_test_entry_id' => $this->input->post('patient_test_entry_id'),
            'invoice_no' => $this->input->post('invoice_no'),
            'manual_report' => $manual_report,
            'manual_or_dynamic_report' => $this->input->post('manual_or_dynamic_report'),
            'test_group_id' => $this->input->post('test_group_id'),
            'test_result_no' => $this->input->post('test_result_no'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->where('test_result_id', $test_result_id)->update('test_result', $data);

        $this->db->where('test_result_id', $test_result_id)->update('test_result_details', array('is_deleted' => '1'));

        $test_id = $this->input->post('test_id');
        $test_configuration_value = $this->input->post('test_configuration_value') ?? array();
        $bold = $this->input->post('bold');
        if (is_array($test_configuration_value) && count($test_configuration_value) > 0) {
            for ($i = 0; $i < count($test_configuration_value); $i++) {
                $row = array(
                    'test_id' => is_array($test_id) ? ($test_id[$i] ?? null) : $test_id,
                    'test_configuration_value' => $test_configuration_value[$i],
                    'bold' => (is_array($bold) ? ($bold[$i] ?? 0) : ($bold ?? 0)),
                    'test_result_no' => $this->input->post('test_result_no'),
                    'test_result_id' => $test_result_id,
                    'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                    'user_id' => $this->session->userdata('user_id'),
                );
                $this->db->insert('test_result_details', $row);
            }
        }

        $this->session->set_userdata(array(
            'print_test_result_id' => $test_result_id,
            'success' => 'saved successully',
        ));
        $this->output->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => true,
                'message' => 'Data updated successfully.',
                'print_url' => site_url('TestResultController/test_result_report_print_again/' . $test_result_id),
            )));
    }
    function sms_send($patient)
    {
        $sms_api = getSMSAPI();
        if (!$sms_api || !isset($sms_api->is_test_result_ready_notification_sms_send) || $sms_api->is_test_result_ready_notification_sms_send !== 'yes') {
            return null;
        }

        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = ($compnay && isset($compnay->company_name)) ? $compnay->company_name : '';

        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = $sms_api->api_key;
        $senderid = $sms_api->senderid;
        if ($patient && isset($patient->mobile_number) && $patient->mobile_number !== '') {
            $number = "88" . $patient->mobile_number;
            $message = "Dear patient, your report is ready! Patient Name: " . $patient->patient_name . ", Mobile: " . $patient->mobile_number . ", Invoice No: " . $patient->invoice_no.','.$company_name;

            $data = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];

            $send_sms = array(
                'mobile_number' => $number,
                'message' => $message,
                'type' => 'Report Ready',
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->insert('send_sms', $send_sms);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }

        return null;
    }

    /**
     * Update one test's result from the enter-test-result (add-test-result) form.
     *
     * @param int $test_result_id
     */
    private function update_single_test_result_data_save($test_result_id)
    {
        $test_result_id = (int) $test_result_id;
        $patient_test_entry_id = (int) $this->input->post('patient_test_entry_id', true);
        $test_id = $this->input->post('test_id');
        $first_test_id = (is_array($test_id) && isset($test_id[0])) ? (int) $test_id[0] : 0;

        $existing = $this->db->where('test_result_id', $test_result_id)->get('test_result')->row();
        if (!$existing || (int) $existing->patient_test_entry_id !== $patient_test_entry_id) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Test result not found.')));
            return;
        }
        if ($first_test_id < 1) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Invalid test information.')));
            return;
        }

        $test_configuration_value = $this->input->post('test_configuration_value') ?? array();
        if (!is_array($test_configuration_value) || trim((string) ($test_configuration_value[0] ?? '')) === '') {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Please enter a result value.')));
            return;
        }

        $header = array(
            'test_group_id' => $this->input->post('test_group_id'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'time' => $this->input->post('time'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->where('test_result_id', $test_result_id)->update('test_result', $header);

        $bold = $this->input->post('bold');
        $bold_val = is_array($bold) ? ($bold[0] ?? 0) : ($bold ?? 0);
        $detail_row = array(
            'test_configuration_value' => $test_configuration_value[0],
            'bold' => $bold_val,
            'date' => $header['date'],
            'user_id' => $this->session->userdata('user_id'),
            'is_deleted' => '0',
        );

        $active_detail = $this->db->where('test_result_id', $test_result_id)
            ->where('test_id', $first_test_id)
            ->where('IFNULL(is_deleted, 0) =', 0, false)
            ->order_by('test_result_details_id', 'DESC')
            ->limit(1)
            ->get('test_result_details')
            ->row();

        if ($active_detail) {
            $this->db->where('test_result_details_id', (int) $active_detail->test_result_details_id)
                ->update('test_result_details', $detail_row);
        } else {
            $this->db->where('test_result_id', $test_result_id)
                ->where('test_id', $first_test_id)
                ->update('test_result_details', array('is_deleted' => '1'));

            $detail_row['test_id'] = $first_test_id;
            $detail_row['test_result_no'] = $existing->test_result_no;
            $detail_row['test_result_id'] = $test_result_id;
            $this->db->insert('test_result_details', $detail_row);
        }

        $this->session->set_userdata(array(
            'print_test_result_id' => $test_result_id,
            'success' => 'saved successully',
        ));
        $this->output->set_content_type('application/json')
            ->set_output(json_encode(array(
                'success' => true,
                'message' => 'Data updated successfully.',
                'test_result_id' => $test_result_id,
                'print_url' => site_url('TestResultController/test_result_report_print_again/' . $test_result_id),
            )));
    }

    /**
     * Resolve test_result_id for save (POST, entry detail row, or patient+test lookup).
     *
     * @return int
     */
    private function resolve_test_result_id_for_save()
    {
        $test_result_id = (int) $this->input->post('test_result_id');
        if ($test_result_id > 0) {
            return $test_result_id;
        }

        $entry_detail_id = (int) $this->input->post('patient_test_entry_details_id');
        if ($entry_detail_id > 0) {
            $entry = $this->TestResultModel->get_add_test_result_entry($entry_detail_id);
            if ($entry && (int) $entry->existing_test_result_id > 0) {
                return (int) $entry->existing_test_result_id;
            }
            if ($entry) {
                $found = $this->TestResultModel->find_existing_test_result_id(
                    (int) $entry->patient_test_entry_id,
                    (int) $entry->test_id
                );
                if ($found > 0) {
                    return $found;
                }
            }
        }

        $patient_test_entry_id = (int) $this->input->post('patient_test_entry_id');
        $test_id = $this->input->post('test_id');
        $first_test_id = (is_array($test_id) && isset($test_id[0])) ? (int) $test_id[0] : (int) $test_id;

        return $this->TestResultModel->find_existing_test_result_id($patient_test_entry_id, $first_test_id);
    }

    public function add_test_result_data_save()
    {
        if ($this->input->is_ajax_request()) {
            $patient_test_entry_id = (int) $this->input->post('patient_test_entry_id');
            $test_id = $this->input->post('test_id');
            $first_test_id = (is_array($test_id) && isset($test_id[0])) ? (int) $test_id[0] : (int) $test_id;
            if ($patient_test_entry_id < 1 || $first_test_id < 1) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode(array('success' => false, 'message' => 'Invalid test information.')));
                return;
            }

            $test_result_id = $this->resolve_test_result_id_for_save();
            if ($test_result_id > 0) {
                $this->update_single_test_result_data_save($test_result_id);
                return;
            }

            $manual_report = '';
            if (!empty($_FILES['manual_report']['name'])) {
                $config['upload_path'] = 'assets/manual_report/';
                $config['allowed_types'] = 'gif|jpg|png|pdf';
                $config['overwrite'] = false;
                $config['encrypt_name'] = false;
                $_FILES['manual_report']['name'] = $this->input->post('invoice_no') . '_' . $_FILES['manual_report']['name'];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('manual_report')) {
                    $upload_data = $this->upload->data();
                    $manual_report = $upload_data['file_name'];
                }
            }

            $manual_or_dynamic = $this->input->post('manual_or_dynamic_report', true);
            if ($manual_or_dynamic === 'dynamic_report') {
                $manual_or_dynamic = 'Dynamic';
            }

            $data = array(
                'patient_test_entry_id' => $patient_test_entry_id,
                'test_group_id' => $this->input->post('test_group_id'),
                'invoice_no' => $this->input->post('invoice_no'),
                'manual_or_dynamic_report' => $manual_or_dynamic,
                'manual_report' => $manual_report,
                'test_result_no' => $this->input->post('test_result_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'time' => $this->input->post('time'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $patient = getPatientTestEntry($patient_test_entry_id);
            if (!$this->db->insert('test_result', $data)) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode(array('success' => false, 'message' => 'Could not save test result.')));
                return;
            }
            $test_result_id = $this->db->insert_id();
            $test_id = $this->input->post('test_id');
            //        $test_configuration_id = $this->input->post('test_configuration_id');
            $test_configuration_value = $this->input->post('test_configuration_value') ?? [];
            $bold = $this->input->post('bold');
            $result = '';
            if (is_array($test_configuration_value) && count($test_configuration_value) > 0) {
                for ($i = 0; $i < count($test_configuration_value); $i++) {
                    $data = array(
                        'test_id' => $test_id[$i] ?? null,
                        'test_configuration_value' => $test_configuration_value[$i],
                        'bold' => $bold[$i] ?? 0,
                        'test_result_no' => $this->input->post('test_result_no'),
                        'test_result_id' => $test_result_id,
                        'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                        'user_id' => $this->session->userdata('user_id'),
                    );
                    $result = $this->db->insert('test_result_details', $data);
                }
            } else {
                log_message('error', 'test_configuration_value is not an array or is empty.');
            }

            $flash = array(
                'print_test_result_id' => $test_result_id,
                'success' => 'saved successully',
            );
            $this->session->set_userdata($flash);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            if ($result) {
                $this->sms_send($patient);
            }
            $response['test_result_id'] = (int) $test_result_id;
            $response['print_url'] = site_url('TestResultController/test_result_report_print_again/' . (int) $test_result_id);
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Invalid request.')));
        }
    }

    public function test_result_report_print_again($test_result_id)
    {
        $data['test_result_id'] = $test_result_id;
        $this->load->view('test_result/test_result_report_print', $data, TRUE);
        $page_data = array(
            'page_name' => 'test_result/test_result_report_print',
            'page_title' => 'Test Result Print',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function test_result_dashboard()
    {

        $page_data = array(
            'page_name' => 'test_result/test_result_dashboard',
            'page_title' => 'Add Test Result Dashboard',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function add_test_configuration()
    {

        $page_data = array(
            'page_name' => 'test_result/add_test_configuration',
            'page_title' => 'Add Test Configuration',
            'sidebar' => 'settings/settings_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_test_configuration()
    {
        $config = array();

        $tc_filter_key = 'view_test_configuration_filters';
        // Form submit sends filter keys in query/body; plain pagination URLs use session fallback.
        $has_explicit = isset($_GET['test_group_id']) || isset($_GET['test_id']) || isset($_GET['group_id'])
            || isset($_POST['test_group_id']) || isset($_POST['test_id']) || isset($_POST['group_id']);

        $test_group_id = '';
        $test_id = '';
        if ($has_explicit) {
            $raw_group = $this->input->get_post('test_group_id', true);
            if (($raw_group === null || $raw_group === false || trim((string) $raw_group) === '')
                && isset($_GET['group_id'])) {
                $raw_group = $this->input->get('group_id', true);
            }
            $raw_test = $this->input->get_post('test_id', true);
            $test_group_id = ($raw_group !== '' && $raw_group !== false && $raw_group !== null
                && ctype_digit(trim((string) $raw_group))) ? trim((string) $raw_group) : '';
            $test_id = ($raw_test !== '' && $raw_test !== false && $raw_test !== null
                && ctype_digit(trim((string) $raw_test))) ? trim((string) $raw_test) : '';
            if ($test_group_id === '' && $test_id === '') {
                $this->session->unset_userdata($tc_filter_key);
            } else {
                $this->session->set_userdata($tc_filter_key, array(
                    'test_group_id' => $test_group_id,
                    'test_id' => $test_id,
                ));
            }
        } else {
            $saved = $this->session->userdata($tc_filter_key);
            if (is_array($saved)) {
                $gid = isset($saved['test_group_id']) ? trim((string) $saved['test_group_id']) : '';
                $tid = isset($saved['test_id']) ? trim((string) $saved['test_id']) : '';
                $test_group_id = ($gid !== '' && ctype_digit($gid)) ? $gid : '';
                $test_id = ($tid !== '' && ctype_digit($tid)) ? $tid : '';
                if ($test_group_id === '' && $test_id === '') {
                    $this->session->unset_userdata($tc_filter_key);
                }
            }
        }

        if ($test_group_id !== '' && $test_id !== '') {
            $this->db->reset_query()->where('test_id', $test_id)->where('test_group_id', $test_group_id);
            $ok = ((int) $this->db->count_all_results('test')) > 0;
            if (!$ok) {
                $test_id = '';
                if ($has_explicit || $this->session->userdata($tc_filter_key)) {
                    $this->session->set_userdata($tc_filter_key, array(
                        'test_group_id' => $test_group_id,
                        'test_id' => '',
                    ));
                }
            }
        }

        $seg = $this->uri->segment(3);
        $page = ($seg !== null && $seg !== '' && ctype_digit((string) $seg)) ? (int) $seg : 0;

        $data['selected_test_group_id'] = $test_group_id;
        $data['selected_test_id'] = $test_id;
        $data['filter_tests'] = array();
        if ($test_group_id !== '') {
            $data['filter_tests'] = $this->db->where('test_group_id', $test_group_id)->order_by('test_name', 'ASC')->get('test')->result();
        }

        // Configure pagination — site_url respects index_page; reuse_query_string keeps filter params on page links.
        $config['base_url'] = site_url('TestResultController/view_test_configuration');
        $config['reuse_query_string'] = true;
        $config["total_rows"] = $this->TestResultModel->count_all_test_configuration($test_group_id, $test_id);
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
        $data['detailsList'] = $this->TestResultModel->get_test_configuration_details($config['per_page'], $page, $test_group_id, $test_id);
        // Create the pagination links
        $data['pagination'] = $this->pagination->create_links();
        // Load view
        $data['page_name'] = 'test_result/view_test_configuration';
        $data['page_title'] = 'View Pharmacy';
        $data['sidebar'] = 'settings/settings_sidebar';
        $this->load->view('content', $data);
    }
    public function view_test_configuration1($offset = 0)
    {
        $test_group_id = $this->input->post('test_group_id');
        $test_id = $this->input->post('test_id');
        $config['base_url'] = site_url('TestResultController/view_test_configuration');
        $config['total_rows'] = $this->db->count_all('test_configuration');
        $config['per_page'] = "20";
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        // get books list
        $data['detailsList'] = $this->ProductModel->get_test_configuration_details($config["per_page"], $offset, $test_group_id, $test_id);
        $this->load->view('test_result/view_test_configuration', $data, true);

        $page_data = array(
            'page_name' => 'test_result/view_test_configuration',
            'page_title' => 'View Test Configuration',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_test_configuration($test_configuration_id)
    {
        $data['test_configuration_id'] = $test_configuration_id;
        $this->load->view('test_result/edit_test_configuration', $data);
    }

    public function this_test_test_delete($test_result_id)
    {
        $data = array(
            'is_deleted' => '1'
        );
        $this->db->where('test_result_id', $test_result_id)->update('test_result', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);
        redirect('TestResultController/view_test_result', 'refresh');
    }
    public function test_result_delete_ajax()
    {
        $test_result_id = $this->input->post('test_result_id');
        if ($this->db->where('test_result_id', $test_result_id)->delete('test_result')) {
            $this->db->where('test_result_id', $test_result_id)->delete('test_result_details');
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }
    public function test_configuration_delete_ajax()
    {
        $test_configuration_id = $this->input->post('test_configuration_id');
        if ($this->db->where('test_configuration_id', $test_configuration_id)->delete('test_configuration')) {
            $response = array('status' => 'success', 'message' => 'Data has been deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete patient.');
        }
        echo json_encode($response);
    }

    public function test_configuration_delete($test_configuration_id)
    {
        $data = array(
            'is_deleted' => '1'
        );
        $this->db->where('test_configuration_id', $test_configuration_id)->update('test_configuration', $data);
        $sdata['deleted'] = 'saved successully';
        $this->session->set_userdata($sdata);
        redirect('TestResultController/view_test_configuration', 'refresh');
    }
}
