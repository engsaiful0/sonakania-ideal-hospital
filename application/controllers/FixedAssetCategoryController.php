<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FixedAssetCategoryController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper(array('url'));
        $this->load->library('form_validation');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
        $this->load->model('Fixed_asset_category_model', 'category_model');
    }

    public function index()
    {
        $page_data = array(
            'page_name' => 'fixed_asset/categories',
            'page_title' => 'Fixed Asset — Categories',
            'sidebar' => 'store/store_sidebar',
        );
        $this->load->view('content', $page_data);
    }

    public function datatable()
    {
        $rows = $this->category_model->get_all_for_list();
        $data = array();
        foreach ($rows as $r) {
            $data[] = array(
                'id' => (int) $r->id,
                'name' => $r->name,
                'description' => $r->description,
                'is_active' => (int) $r->is_active,
                'status_html' => $r->is_active ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>',
                'created_at' => $r->created_at,
            );
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('data' => $data)));
    }

    public function save()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('is_active', 'Status', 'integer');
        if ($this->form_validation->run() === false) {
            $this->_json_fail(validation_errors(' ', ' '));
            return;
        }
        $name = $this->input->post('name', true);
        if ($this->category_model->name_exists($name)) {
            $this->_json_fail('A category with this name already exists.');
            return;
        }
        $id = $this->category_model->insert_row(array(
            'name' => $name,
            'description' => $this->input->post('description', true),
            'is_active' => (int) $this->input->post('is_active'),
        ));
        $this->_json_ok('Category saved.', array('id' => $id));
    }

    public function update()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = (int) $this->input->post('id');
        if ($id < 1) {
            $this->_json_fail('Invalid category.');
            return;
        }
        $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('is_active', 'Status', 'integer');
        if ($this->form_validation->run() === false) {
            $this->_json_fail(validation_errors(' ', ' '));
            return;
        }
        $name = $this->input->post('name', true);
        if ($this->category_model->name_exists($name, $id)) {
            $this->_json_fail('A category with this name already exists.');
            return;
        }
        $this->category_model->update_row($id, array(
            'name' => $name,
            'description' => $this->input->post('description', true),
            'is_active' => (int) $this->input->post('is_active'),
        ));
        $this->_json_ok('Category updated.');
    }

    public function delete()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = (int) $this->input->post('id');
        if ($id < 1) {
            $this->_json_fail('Invalid category.');
            return;
        }
        if ($this->category_model->count_assets($id) > 0) {
            $this->_json_fail('Cannot delete: assets exist in this category.');
            return;
        }
        $this->category_model->delete_row($id);
        $this->_json_ok('Category deleted.');
    }

    protected function _json_ok($message, $extra = array())
    {
        $this->output->set_content_type('application/json')->set_output(json_encode(array_merge(
            array('success' => true, 'message' => $message),
            $extra
        )));
    }

    protected function _json_fail($message)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'success' => false,
            'message' => $message,
        )));
    }
}
