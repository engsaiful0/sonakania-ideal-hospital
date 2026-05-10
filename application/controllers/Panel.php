<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Panel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_model');
    }

    /**
     * Add Test Panel
     */
    public function add_panel()
    {
        if ($this->input->post()) {

            $data = [
                'panel_name'  => $this->input->post('panel_name'),
                'description' => $this->input->post('description')
            ];

            $this->Report_model->insert_panel($data);
            redirect('panel/list');
        }

        $this->load->view('panel/add_panel');
    }


    /**
     * Add Section under Panel
     */
    public function add_section()
    {
        if ($this->input->post()) {

            $data = [
                'panel_id'     => $this->input->post('panel_id'),
                'section_name' => $this->input->post('section_name')
            ];

            $this->Report_model->insert_section($data);
            redirect('panel/view/' . $data['panel_id']);
        }

        $this->load->view('panel/add_section');
    }


    /**
     * Add Parameter under Section
     */
    public function add_parameter()
    {
        if ($this->input->post()) {

            $data = [
                'section_id'     => $this->input->post('section_id'),
                'parameter_name' => $this->input->post('parameter_name'),
                'unit'           => $this->input->post('unit'),
                'input_type'     => $this->input->post('input_type'),
                'min_value'      => $this->input->post('min_value'),
                'max_value'      => $this->input->post('max_value')
            ];

            $this->Report_model->insert_parameter($data);
            redirect('panel/view/' . $this->input->post('panel_id'));
        }

        $this->load->view('panel/add_parameter');
    }
}