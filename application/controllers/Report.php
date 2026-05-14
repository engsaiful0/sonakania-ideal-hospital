<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Lab-style panel reports (panels → sections → parameters).
 * URLs: report/create_report, report/store_report, report/view_report/{id}, report/ajax_panel_structure/{id}
 */
class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_model');
        $this->load->helper(array('url', 'html'));
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
    }

    public function create_report()
    {
        $page_data = array(
            'page_name' => 'report/create_report',
            'page_title' => 'Create lab report',
            'sidebar' => 'report/report_sidebar',
            'panels' => $this->Report_model->get_all_panels(),
        );
        $this->load->view('content', $page_data);
    }

    /**
     * JSON payload for dynamic form (sections as headings, parameters as inputs).
     */
    public function ajax_panel_structure($panel_id = 0)
    {
        $panel_id = (int) $panel_id;
        if ($panel_id < 1) {
            $this->_json_out(array('ok' => false, 'message' => 'Invalid panel'));
            return;
        }

        $panel = $this->Report_model->get_panel($panel_id);
        if (!$panel) {
            $this->_json_out(array('ok' => false, 'message' => 'Panel not found'));
            return;
        }

        $sections = $this->Report_model->get_sections_with_parameters($panel_id);
        $out = array();
        foreach ($sections as $s) {
            $params = array();
            foreach ($s->parameters as $p) {
                $params[] = array(
                    'id' => (int) $p->id,
                    'parameter_name' => $p->parameter_name,
                    'unit' => isset($p->unit) ? (string) $p->unit : '',
                    'input_type' => isset($p->input_type) ? (string) $p->input_type : 'text',
                    'min_value' => isset($p->min_value) ? $p->min_value : null,
                    'max_value' => isset($p->max_value) ? $p->max_value : null,
                );
            }
            $out[] = array(
                'id' => (int) $s->id,
                'section_name' => $s->section_name,
                'parameters' => $params,
            );
        }

        $this->_json_out(array(
            'ok' => true,
            'panel' => array(
                'id' => (int) $panel->id,
                'panel_name' => $panel->panel_name,
            ),
            'sections' => $out,
        ));
    }

    public function store_report()
    {
        $panel_id = (int) $this->input->post('panel_id');
        if ($panel_id < 1) {
            show_error('Panel is required.', 400);
        }

        $panel = $this->Report_model->get_panel($panel_id);
        if (!$panel) {
            show_error('Invalid panel.', 400);
        }

        $report_data = array(
            'patient_name' => $this->input->post('patient_name', true),
            'age' => $this->input->post('age', true),
            'sex' => $this->input->post('sex', true),
            'patient_id' => $this->input->post('patient_id', true),
            'panel_id' => $panel_id,
            'report_date' => date('Y-m-d'),
        );

        if ($report_data['patient_name'] === '') {
            show_error('Patient name is required.', 400);
        }

        $report_id = $this->Report_model->insert_report($report_data);

        $parameters = $this->input->post('parameters');
        if (!is_array($parameters)) {
            $parameters = array();
        }

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
        }

        redirect('report/view_report/' . $report_id);
    }

    public function view_report($id = 0)
    {
        $id = (int) $id;
        $report = $this->Report_model->get_report_with_panel($id);
        if (!$report) {
            show_404();
        }

        $auto_print = (int) $this->input->get('print') === 1;

        $page_data = array(
            'page_name' => 'report/view_report',
            'page_title' => 'View lab report',
            'sidebar' => 'report/report_sidebar',
            'report' => $report,
            'section_blocks' => $this->Report_model->get_report_results_grouped_by_section($id),
            'auto_print' => $auto_print,
        );
        $this->load->view('content', $page_data);
    }

    protected function _json_out(array $payload)
    {
        $this->output->set_content_type('application/json; charset=utf-8')
            ->set_output(json_encode($payload));
    }
}
