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
class TestPanelResultController extends CI_Controller
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
    }

    /**
     * Which `test_panel_result/*` view to use for printing a lab report.
     *
     * @param object|null $report Row from Report_model::get_report_with_panel()
     * @return string View path (no .php) under application/views/
     */
    private function panel_test_print_view($report)
    {
        $name = (is_object($report) && isset($report->panel_name)) ? trim((string) $report->panel_name) : '';
        if ($name === 'Urine R/E' || $name === 'Urine R/M/E') {
            return 'test_panel_result/panel_test_print_urine_examination';
        }

        return 'test_panel_result/panel_test_print';
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
        if ($test_group_id === null || $test_group_id === '' || !ctype_digit((string) $test_group_id)) {
            echo '<option value="">All tests in group</option>';
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
    public function print_panel_test_with_id($id)
    {
        $this->load->model('Report_model');
        $id = (int) $id;

        $report = $this->Report_model->get_report_with_panel($id);
        if (!$report) {
            show_404();
        }

        $auto_print = (int) $this->input->get('print') === 1;

        $page_data = array(
            'page_name'      => $this->panel_test_print_view($report),
            'page_title'     => 'Print Panel Test',
            'sidebar'        => 'test_result/test_result_sidebar',
            'report'         => $report,
            'section_blocks' => $this->Report_model->get_report_results_grouped_by_section($id),
            'auto_print'     => $auto_print,
        );
        $this->load->view('content', $page_data);
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
        $test_group_id = $_POST['test_group_id'];
        $patient_test_entry_id = $_POST['patient_test_entry_id'];

        // $test_configuration = $this->db
        //     ->where('name_only', 'no')
        //     ->where('test_group_id', $test_group_id)
        //     ->get('test')->result();

        $this->db->select('test.test_name,test.test_id');
        $this->db->from('test');
        $this->db->join('patient_test_entry_details', 'patient_test_entry_details.test_id = test.test_id');
        $this->db->where('patient_test_entry_details.patient_test_entry_id', $patient_test_entry_id);
        $this->db->where('test.name_only', 'no');
        $this->db->where('test.setting_type', 'Normal');
        // $this->db->where('test.test_group_id', $test_group_id);

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
                            <div class="col-sm-3">
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
                            <div class="col-sm-3">
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

    public function add_panel_test()
    {
        $page_data = array(
            'page_name' => 'test_panel_result/add_panel_test',
            'page_title' => 'Add Panel Test Data',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Listing page for panel-test (lab_reports) entries.
     * Filters: patient (name/id), panel_id, date_from, date_to. Pagination via CI library.
     */
    public function view_panel_test()
    {
        $this->load->model('Report_model');

        $patient = trim((string) $this->input->get_post('patient', true));
        $panel_id = (int) $this->input->get_post('panel_id', true);
        $date_from = trim((string) $this->input->get_post('date_from', true));
        $date_to = trim((string) $this->input->get_post('date_to', true));

        $filters = array(
            'patient' => $patient,
            'panel_id' => $panel_id,
            'date_from' => $date_from,
            'date_to' => $date_to,
        );

        $seg = $this->uri->segment(3);
        $offset = ($seg !== null && $seg !== '' && ctype_digit((string) $seg)) ? (int) $seg : 0;

        $config = array();
        $config['base_url'] = site_url('TestPanelResultController/view_panel_test');
        $config['reuse_query_string'] = true;
        $config['total_rows'] = $this->Report_model->count_panel_reports($filters);
        $config['per_page'] = 25;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_link'] = '<i class="fa fa-long-arrow-left"></i> Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next <i class="fa fa-long-arrow-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $rows = $this->Report_model->panel_reports_filtered($filters, $offset, $config['per_page'], 'lr.id', 'desc');

        $page_data = array(
            'page_name' => 'test_panel_result/view_panel_test',
            'page_title' => 'View Panel Test',
            'sidebar' => 'test_result/test_result_sidebar',
            'panels' => $this->Report_model->get_all_panels(),
            'rows' => $rows,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => (int) $config['total_rows'],
            'per_page' => (int) $config['per_page'],
            'offset' => $offset,
            'sl_start' => $offset + 1,
            'filter_patient' => $patient,
            'filter_panel_id' => $panel_id,
            'filter_date_from' => $date_from,
            'filter_date_to' => $date_to,
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Renders the structured panel-test print view for a saved lab_reports row.
     * URL: TestPanelResultController/panel_test_print/{id}[?print=1]
     */
    public function panel_test_print($id = 0)
    {
        $this->load->model('Report_model');
        $id = (int) $id;
        $report = $this->Report_model->get_report_with_panel($id);
        if (!$report) {
            show_404();
        }

        $auto_print = (int) $this->input->get('print') === 1;

        $page_data = array(
            'page_name' => $this->panel_test_print_view($report),
            'page_title' => 'Panel Test Report',
            'sidebar' => 'test_result/test_result_sidebar',
            'report' => $report,
            'section_blocks' => $this->Report_model->get_report_results_grouped_by_section($id),
            'auto_print' => $auto_print,
        );
        $this->load->view('content', $page_data);
    }

    /**
     * Delete a panel-test report and its result rows.
     */
    public function delete_panel_test($id = 0)
    {
        $this->load->model('Report_model');
        $id = (int) $id;
        if ($id < 1) {
            redirect('TestPanelResultController/view_panel_test');
        }
        $this->Report_model->delete_report_with_results($id);
        $back = $this->input->get('back');
        if (!empty($back)) {
            redirect($back);
        }
        redirect('TestPanelResultController/view_panel_test');
    }

    /**
     * Returns the HTML fragment of section headings + parameter inputs for the chosen panel.
     * POST: panel_test_id
     */
    public function panel_test_load()
    {
        $this->load->model('Report_model');

        $panel_id = (int) $this->input->post('panel_test_id', true);
        if ($panel_id < 1) {
            echo '<p class="text-warning">Select a valid panel.</p>';
            return;
        }
        $panel = $this->Report_model->get_panel($panel_id);
        if (!$panel) {
            echo '<p class="text-danger">Panel not found.</p>';
            return;
        }
        $sections = $this->Report_model->get_sections_with_parameters($panel_id);
        if (empty($sections)) {
            echo '<p class="text-muted">No sections/parameters defined for "' . html_escape($panel->panel_name) . '".</p>';
            return;
        }
        ?>
        <h4 class="text-primary" style="margin-top:0;">
            <?php echo html_escape($panel->panel_name); ?>
        </h4>
        <?php foreach ($sections as $s) {
            // Each section sits inside its own full-width row + clearfix so
            // section blocks always start on a fresh line and parameter
            // columns from different sections can never float into each other.
        ?>
            <div class="row panel-section-row" style="clear:both;">
                <div class="col-md-12">
                    <div class="well well-sm panel-section-well" style="margin-bottom:18px;">
                        <h4 style="margin-top:0;border-bottom:1px solid #ddd;padding-bottom:6px;">
                            <?php echo html_escape($s->section_name); ?>
                        </h4>
                        <?php if (empty($s->parameters)) { ?>
                            <p class="text-muted">No parameters in this section.</p>
                        <?php } else {
                            // Chunk parameters two-per-row so each pair lives
                            // in its own .row container — this prevents
                            // float-height differences from spilling a
                            // following parameter alongside a previous one.
                            $pairs = array_chunk($s->parameters, 2);
                            foreach ($pairs as $pair) { ?>
                                <div class="row">
                                    <?php foreach ($pair as $p) {
                                        $pname = 'parameters[' . (int) $p->id . ']';
                                        $unit = isset($p->unit) && $p->unit !== '' ? ' <span class="text-muted">(' . html_escape($p->unit) . ')</span>' : '';
                                        $type = isset($p->input_type) ? $p->input_type : 'text';
                                    ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-5"><?php echo html_escape($p->parameter_name) . $unit; ?></label>
                                                <div class="col-sm-7">
                                                    <?php if ($type === 'numeric') {
                                                        // NOTE: do NOT emit min/max attributes here. jQuery Validate
                                                        // (loaded globally for this form) would auto-enforce them and
                                                        // surface errors like "Please enter a value less than or
                                                        // equal to 5.9." The normal range is purely informational
                                                        // for the lab and is shown on the printed report.
                                                        echo '<input type="number" step="any" class="form-control panel-param" name="' . $pname . '">';
                                                    } elseif ($type === 'boolean') {
                                                        echo '<select class="form-control panel-param" name="' . $pname . '">'
                                                            . '<option value="">—</option>'
                                                            . '<option value="Negative">Negative</option>'
                                                            . '<option value="Positive">Positive</option>'
                                                            . '</select>';
                                                    } else {
                                                        echo '<input type="text" class="form-control panel-param" name="' . $pname . '" maxlength="500">';
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        <?php }
    }

    /**
     * AJAX save for a panel test entry. Inserts into lab_reports + lab_report_results
     * via Report_model, evaluates status using the Report_engine library.
     * Returns JSON { success, message, report_id, print_url }.
     */
    public function save_panel_test()
    {
        if (!$this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Invalid request.')));
            return;
        }

        $this->load->model('Report_model');

        $panel_id = (int) $this->input->post('panel_test_id', true);
        if ($panel_id < 1) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Please select a panel test.')));
            return;
        }
        $panel = $this->Report_model->get_panel($panel_id);
        if (!$panel) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Panel not found.')));
            return;
        }

        $patient_name = trim((string) $this->input->post('patient_name', true));
        if ($patient_name === '') {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Patient name is required.')));
            return;
        }

        $report_data = array(
            'patient_name' => $patient_name,
            'age_year' => (string) $this->input->post('age_year', true),
            'age_month' => (string) $this->input->post('age_month', true),
            'age_day' => (string) $this->input->post('age_day', true),
            'sex' => (string) $this->input->post('gender', true),
            'patient_id' => (string) $this->input->post('invoice_no', true),
            'panel_id' => $panel_id,
            'report_date' => date('Y-m-d'),
        );
        if ($this->db->field_exists('test_group_id', 'lab_reports')) {
            $raw_gid = $this->input->post('test_group_id', true);
            $gid = ($raw_gid !== null && $raw_gid !== '' && ctype_digit((string) $raw_gid)) ? (int) $raw_gid : 0;
            if ($gid < 1) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode(array('success' => false, 'message' => 'Please select a test group.')));
                return;
            }
            $report_data['test_group_id'] = $gid;
        }
        $report_id = (int) $this->Report_model->insert_report($report_data);
        if ($report_id < 1) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array('success' => false, 'message' => 'Could not save the report.')));
            return;
        }

        $parameters = $this->input->post('parameters');
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $saved_rows = 0;
        foreach ($parameters as $parameter_id => $value) {
            $parameter_id = (int) $parameter_id;
            if ($parameter_id < 1) {
                continue;
            }
            $parameter = $this->Report_model->get_parameter($parameter_id);
            if (!$parameter) {
                continue;
            }
            if (is_array($value)) {
                $value = '';
            }
            $value = trim((string) $value);
            $status = $this->report_engine->evaluate($value, $parameter);

            $this->Report_model->insert_result(array(
                'report_id' => $report_id,
                'parameter_id' => $parameter_id,
                'result_value' => $value,
                'status' => $status,
            ));
            $saved_rows++;
        }

        $print_url = site_url('TestPanelResultController/panel_test_print/' . $report_id) . '?print=1';

        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'success' => true,
            'message' => 'Panel test report saved.',
            'report_id' => $report_id,
            'rows' => $saved_rows,
            'print_url' => $print_url,
        )));
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
        $page_data = array(
            'page_name' => 'test_result/add_test_result',
            'page_title' => 'Add Test Result',
            'sidebar' => 'test_result/test_result_sidebar'
        );
        $this->load->view('content', $page_data);
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

        echo $patient_test_entry->patient_test_entry_id . '*' . $patient_test_entry->patient_name . '*' . $patient_test_entry->mobile_number . '*' . $patient_test_entry->age . '*' . $patient_test_entry->gender . '*' . date('Y-m-d', strtotime($patient_test_entry->date)) . '*' . $patient_test_entry->time;
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

            $data = array(
                'test_group_id' => $this->input->post('test_group_id'),
                //            'test_parameter' => $this->input->post('test_parameter'),
                'test_id' => $this->input->post('test_id'),
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

            $data = array(
                'test_group_id' => $this->input->post('test_group_id'),
                //            'test_parameter' => $this->input->post('test_parameter'),
                'test_id' => $this->input->post('test_id'),
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
        if ($this->input->is_ajax_request()) {
            $test_result_id = $this->input->post('test_result_id');
          
            $config['upload_path'] = 'assets/manual_report/';
            $config['allowed_types'] = 'gif|jpg|png|pdf';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE; // Keep the original file name
            $error = array();
            $sdata = array();
            $manual_report = '';
            // Get the original file name
            $file_name = $_FILES['manual_report']['name'];

            // Append the invoice number before the original file name
            $new_file_name = $this->input->post('invoice_no') . '_' . $file_name;

            // Update the file name in the $_FILES array to reflect the new name
            $_FILES['manual_report']['name'] = $new_file_name;

            // Load the upload library with the config settings
            $this->load->library('upload', $config);

            // Attempt to upload the renamed file
            if ($this->upload->do_upload('manual_report')) {
                // Get the uploaded file's data
                $sdata = $this->upload->data();

                // Get the renamed file name after upload
                $manual_report = $sdata['file_name'];
            } else {
                // Handle the upload error
                $error = $this->upload->display_errors();
                $manual_report = ''; // Reset if there's an error
            }

            if ($manual_report == '') {
                $manual_report = $this->input->post('manual_report_previous');
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

            $delete_data = array(
                'is_deleted' => '1'
            );
            $this->db->where('test_result_id', $test_result_id)->update('test_result_details', $delete_data); /* To delete the previous */

            $test_id = $this->input->post('test_id');
            //        $test_configuration_id = $this->input->post('test_configuration_id');
            $test_configuration_value = $this->input->post('test_configuration_value') ?? [];
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

            $sdata['print_test_result_id'] = $test_result_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            $response = array('success' => true, 'message' => 'Data updated successfully.');
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
        }
    }
    function sms_send($patient)
    {
        $compnay = $this->db->where('company_id', '1')->get('company')->row();
        $company_name = $compnay->company_name;

        $sms_api = getSMSAPI();
        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = $sms_api->api_key;
        $senderid = $sms_api->senderid;
        // $number = "88016xxxxxxxx,88019xxxxxxxx";
        // echo '<pre>';
        // print_r($patient->mobile_number);
        // die;
        if ($sms_api->is_test_result_ready_notification_sms_send == 'yes') { // if is_sms_send==yes, then sms will be sent
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
    }

    public function add_test_result_data_save()
    {
        if ($this->input->is_ajax_request()) {
            $config['upload_path'] = 'assets/manual_report/';
            $config['allowed_types'] = 'gif|jpg|png|pdf';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE; // Keep the original file name
            $error = array();
            $sdata = array();
            $manual_report = '';
            // Get the original file name
            $file_name = $_FILES['manual_report']['name'];

            // Append the invoice number before the original file name
            $new_file_name = $this->input->post('invoice_no') . '_' . $file_name;

            // Update the file name in the $_FILES array to reflect the new name
            $_FILES['manual_report']['name'] = $new_file_name;

            // Load the upload library with the config settings
            $this->load->library('upload', $config);

            // Attempt to upload the renamed file
            if ($this->upload->do_upload('manual_report')) {
                // Get the uploaded file's data
                $sdata = $this->upload->data();

                // Get the renamed file name after upload
                $manual_report = $sdata['file_name'];
            } else {
                // Handle the upload error
                $error = $this->upload->display_errors();
                $manual_report = ''; // Reset if there's an error
            }


            $data = array(
                'patient_test_entry_id' => $this->input->post('patient_test_entry_id'),
                'test_group_id' => $this->input->post('test_group_id'),
                'invoice_no' => $this->input->post('invoice_no'),
                'manual_or_dynamic_report' => $this->input->post('manual_or_dynamic_report'),
                'manual_report' => $manual_report,
                'test_result_no' => $this->input->post('test_result_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'time' => $this->input->post('time'),
                'user_id' => $this->session->userdata('user_id'),
            );
            $patient = getPatientTestEntry($this->input->post('patient_test_entry_id'));
            $this->db->insert('test_result', $data);
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

            $sdata['print_test_result_id'] = $test_result_id;
            $sdata['success'] = 'saved successully';
            $this->session->set_userdata($sdata);
            $response = array('success' => true, 'message' => 'Data saved successfully.');
            if ($result) {
                $response['sms_response'] = $this->sms_send($patient); // To send sms
            }
            // Return a JSON response
            echo json_encode($response);
        } else {
            // If it's not an AJAX request, show an error
            $response = array('error' => true, 'message' => 'Invalid request.');
            echo json_encode($response);
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
            'sidebar' => 'test_result/test_result_sidebar'
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
        $data['sidebar'] = 'test_result/test_result_sidebar';
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
