<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_model');
        $this->load->library('Report_engine');
    }

    /**
     * STEP 1: Create Report Form
     */
    public function create_report()
    {
        $panel_id = $this->input->get('panel_id');

        if (!$panel_id) {
            show_error('Panel ID is required');
        }

        // Load panel
        $data['panel'] = $this->Report_model->get_panel($panel_id);

        // Load sections + parameters
        $data['sections'] = $this->Report_model->get_sections_with_parameters($panel_id);

        // Load dynamic form view
        $this->load->view('report/create_report', $data);
    }


    /**
     * STEP 2: Store Report
     */
    public function store_report()
    {
        $panel_id = $this->input->post('panel_id');

        $report_data = [
            'patient_name' => $this->input->post('patient_name'),
            'age'          => $this->input->post('age'),
            'sex'          => $this->input->post('sex'),
            'patient_id'   => $this->input->post('patient_id'),
            'panel_id'     => $panel_id,
            'report_date'  => date('Y-m-d')
        ];

        $report_id = $this->Report_model->insert_report($report_data);

        $parameters = $this->input->post('parameters'); 
        // format: parameters[parameter_id] = value

        foreach ($parameters as $parameter_id => $value) {

            $parameter = $this->Report_model->get_parameter($parameter_id);

            // Evaluate result using library
            $status = $this->report_engine->evaluate($value, $parameter);

            $this->Report_model->insert_result([
                'report_id'    => $report_id,
                'parameter_id' => $parameter_id,
                'result_value' => $value,
                'status'       => $status
            ]);
        }

        redirect('report/view_report/' . $report_id);
    }


    /**
     * STEP 3: View Report
     */
    public function view_report($id)
    {
        $data['report']  = $this->Report_model->get_report($id);
        $data['results'] = $this->Report_model->get_report_results($id);

        $this->load->view('report/view_report', $data);
    }
}