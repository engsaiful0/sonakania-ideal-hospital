<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FixedAssetController extends CI_Controller
{
    protected $upload_rel_path = 'assets/fixed_assets/';

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library('form_validation');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
        $this->load->model('Fixed_asset_model', 'asset_model');
        $this->load->model('Fixed_asset_category_model', 'category_model');
        $this->load->model('Fixed_asset_maintenance_model', 'maintenance_model');
    }

    public function dashboard()
    {
        $stats = $this->asset_model->dashboard_stats();
        $dep_by_cat = $this->asset_model->depreciation_summary_by_category();
        $page_data = array(
            'page_name' => 'fixed_asset/dashboard',
            'page_title' => 'Fixed Assets — Dashboard',
            'sidebar' => 'store/store_sidebar',
            'stats' => $stats,
            'dep_by_cat' => $dep_by_cat,
        );
        $this->load->view('content', $page_data);
    }

    public function reports()
    {
        $stats = $this->asset_model->dashboard_stats();
        $cat_report = $this->asset_model->category_wise_report();
        $dep_summary = $this->asset_model->depreciation_summary_by_category();
        $page_data = array(
            'page_name' => 'fixed_asset/reports',
            'page_title' => 'Fixed Assets — Reports',
            'sidebar' => 'store/store_sidebar',
            'stats' => $stats,
            'cat_report' => $cat_report,
            'dep_summary' => $dep_summary,
        );
        $this->load->view('content', $page_data);
    }

    public function assets()
    {
        $page_data = array(
            'page_name' => 'fixed_asset/assets_list',
            'page_title' => 'Fixed Assets — Register',
            'sidebar' => 'store/store_sidebar',
        );
        $this->load->view('content', $page_data);
    }

    public function asset_datatable()
    {
        $draw = (int) $this->input->post('draw');
        $start = (int) $this->input->post('start');
        $length = (int) $this->input->post('length');
        if ($length < 1 || $length > 100) {
            $length = 25;
        }
        $search = '';
        $s = $this->input->post('search');
        if (is_array($s) && isset($s['value'])) {
            $search = trim((string) $s['value']);
        }

        list($rows, $filtered) = $this->asset_model->datatable_filtered($search, $start, $length);
        $total = $this->asset_model->count_all_assets();

        $out = array();
        foreach ($rows as $r) {
            $dept = isset($r->department_name) ? html_escape($r->department_name) : '—';
            $emp = isset($r->employee_name) ? html_escape($r->employee_name) : '—';
            $img = $r->image_path
                ? '<img class="img-thumbnail fa-thumb" src="' . base_url($this->upload_rel_path . $r->image_path) . '" alt="" />'
                : '<span class="text-muted">—</span>';
            $actions = '<a class="btn btn-xs btn-primary" href="' . site_url('fixed-assets/edit/' . $r->id) . '">Edit</a> '
                . '<a class="btn btn-xs btn-info" href="' . site_url('fixed-assets/maintenance/' . $r->id) . '">Maintenance</a> '
                . '<button type="button" class="btn btn-xs btn-danger fa-del-asset" data-id="' . (int) $r->id . '">Delete</button>';
            $out[] = array(
                $img,
                html_escape($r->asset_code),
                html_escape($r->asset_name),
                html_escape($r->category_name),
                html_escape($r->purchase_date),
                number_format((float) $r->purchase_cost, 2),
                number_format((float) $r->annual_depreciation, 2),
                number_format((float) $r->current_book_value, 2),
                $dept,
                $emp,
                html_escape($r->condition_status),
                $actions,
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $out,
        )));
    }

    public function add()
    {
        $page_data = array(
            'page_name' => 'fixed_asset/asset_form',
            'page_title' => 'Fixed Assets — Add',
            'sidebar' => 'store/store_sidebar',
            'asset' => null,
            'categories' => $this->category_model->get_all_active(),
        );
        $this->load->view('content', $page_data);
    }

    public function edit($id = 0)
    {
        $id = (int) $id;
        $asset = $this->asset_model->find($id);
        if (!$asset) {
            show_404();
        }
        $page_data = array(
            'page_name' => 'fixed_asset/asset_form',
            'page_title' => 'Fixed Assets — Edit',
            'sidebar' => 'store/store_sidebar',
            'asset' => $asset,
            'categories' => $this->category_model->get_all_for_list(),
        );
        $this->load->view('content', $page_data);
    }

    public function save_asset()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->_set_asset_validation_rules();
        if ($this->form_validation->run() === false) {
            $this->_json_fail(validation_errors(' ', ' '));
            return;
        }
        if ($this->asset_model->code_exists($this->input->post('asset_code', true))) {
            $this->_json_fail('Asset code already exists.');
            return;
        }

        $row = $this->_collect_asset_post();
        $this->asset_model->apply_depreciation_fields($row);
        $pic = $this->_handle_image_upload(null);
        if ($pic === false) {
            return;
        }
        if ($pic) {
            $row['image_path'] = $pic;
        }
        $row['user_id'] = $this->session->userdata('user_id');
        $id = $this->asset_model->insert_row($row);

        $this->db->insert('fa_asset_assignment_history', array(
            'asset_id' => $id,
            'department_id' => $row['department_id'] ?: null,
            'employee_id' => $row['employee_id'] ?: null,
            'notes' => 'Initial record',
            'user_id' => $this->session->userdata('user_id'),
        ));

        $this->_json_ok('Asset saved.', array('redirect' => site_url('fixed-assets')));
    }

    public function update_asset()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = (int) $this->input->post('id');
        if ($id < 1) {
            $this->_json_fail('Invalid asset.');
            return;
        }
        $old = $this->asset_model->find($id);
        if (!$old) {
            $this->_json_fail('Asset not found.');
            return;
        }
        $this->_set_asset_validation_rules($id);
        if ($this->form_validation->run() === false) {
            $this->_json_fail(validation_errors(' ', ' '));
            return;
        }
        if ($this->asset_model->code_exists($this->input->post('asset_code', true), $id)) {
            $this->_json_fail('Asset code already exists.');
            return;
        }

        $row = $this->_collect_asset_post();
        $this->asset_model->apply_depreciation_fields($row);
        $pic = $this->_handle_image_upload($old->image_path);
        if ($pic === false) {
            return;
        }
        if ($pic !== null) {
            $row['image_path'] = $pic;
        }
        $row['user_id'] = $this->session->userdata('user_id');
        $this->asset_model->update_row($id, $row);

        $old_dept = $old->department_id ? (int) $old->department_id : 0;
        $new_dept = $row['department_id'] ? (int) $row['department_id'] : 0;
        $old_emp = $old->employee_id ? (int) $old->employee_id : 0;
        $new_emp = $row['employee_id'] ? (int) $row['employee_id'] : 0;
        if ($old_dept !== $new_dept || $old_emp !== $new_emp) {
            $this->db->insert('fa_asset_assignment_history', array(
                'asset_id' => $id,
                'department_id' => $row['department_id'] ?: null,
                'employee_id' => $row['employee_id'] ?: null,
                'notes' => 'Assignment updated',
                'user_id' => $this->session->userdata('user_id'),
            ));
        }

        $this->_json_ok('Asset updated.', array('redirect' => site_url('fixed-assets')));
    }

    public function delete_asset()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = (int) $this->input->post('id');
        if ($id < 1) {
            $this->_json_fail('Invalid asset.');
            return;
        }
        $asset = $this->asset_model->find($id);
        if ($asset && !empty($asset->image_path)) {
            $this->_unlink_image($asset->image_path);
        }
        $this->db->where('asset_id', $id)->delete('fa_asset_maintenance');
        $this->db->where('asset_id', $id)->delete('fa_asset_assignment_history');
        $this->asset_model->delete_row($id);
        $this->_json_ok('Asset deleted.');
    }

    public function maintenance($asset_id = 0)
    {
        $asset_id = (int) $asset_id;
        $asset = $this->asset_model->find($asset_id);
        if (!$asset) {
            show_404();
        }
        $page_data = array(
            'page_name' => 'fixed_asset/maintenance',
            'page_title' => 'Fixed Assets — Maintenance',
            'sidebar' => 'store/store_sidebar',
            'asset' => $asset,
        );
        $this->load->view('content', $page_data);
    }

    public function maintenance_datatable()
    {
        $asset_id = (int) $this->input->post('asset_id');
        $rows = $this->maintenance_model->list_by_asset($asset_id);
        $data = array();
        foreach ($rows as $r) {
            $data[] = array(
                $r->id,
                html_escape($r->maintenance_date),
                html_escape($r->description),
                number_format((float) $r->cost, 2),
                html_escape($r->performed_by),
                $r->next_due_date ? html_escape($r->next_due_date) : '—',
                '<button type="button" class="btn btn-xs btn-danger fa-del-maint" data-id="' . (int) $r->id . '">Delete</button>',
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('data' => $data)));
    }

    public function save_maintenance()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->form_validation->set_rules('asset_id', 'Asset', 'required|integer');
        $this->form_validation->set_rules('maintenance_date', 'Date', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');
        $this->form_validation->set_rules('cost', 'Cost', 'decimal');
        $this->form_validation->set_rules('performed_by', 'Performed by', 'trim');
        $this->form_validation->set_rules('next_due_date', 'Next due', 'trim');
        if ($this->form_validation->run() === false) {
            $this->_json_fail(validation_errors(' ', ' '));
            return;
        }
        $asset_id = (int) $this->input->post('asset_id');
        if (!$this->asset_model->find($asset_id)) {
            $this->_json_fail('Invalid asset.');
            return;
        }
        $next = $this->input->post('next_due_date', true);
        $this->maintenance_model->insert_row(array(
            'asset_id' => $asset_id,
            'maintenance_date' => date('Y-m-d', strtotime($this->input->post('maintenance_date'))),
            'description' => $this->input->post('description', true),
            'cost' => $this->input->post('cost') !== '' ? (float) $this->input->post('cost') : 0,
            'performed_by' => $this->input->post('performed_by', true),
            'next_due_date' => $next !== '' ? date('Y-m-d', strtotime($next)) : null,
            'user_id' => $this->session->userdata('user_id'),
        ));
        $this->_json_ok('Maintenance record saved.');
    }

    public function delete_maintenance()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = (int) $this->input->post('id');
        if ($id < 1) {
            $this->_json_fail('Invalid record.');
            return;
        }
        $this->maintenance_model->delete_row($id);
        $this->_json_ok('Record deleted.');
    }

    protected function _set_asset_validation_rules($id = null)
    {
        $this->form_validation->set_rules('asset_name', 'Asset name', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('asset_code', 'Asset code', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('category_id', 'Category', 'required|integer');
        $this->form_validation->set_rules('purchase_date', 'Purchase date', 'required');
        $this->form_validation->set_rules('purchase_cost', 'Purchase cost', 'required|decimal');
        $this->form_validation->set_rules('salvage_value', 'Salvage value', 'decimal');
        $this->form_validation->set_rules('useful_life_years', 'Useful life', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('department_id', 'Department', 'trim');
        $this->form_validation->set_rules('employee_id', 'Staff', 'trim');
        $this->form_validation->set_rules('warranty_expiry', 'Warranty expiry', 'trim');
        $this->form_validation->set_rules('condition_status', 'Condition', 'required|trim');
        $this->form_validation->set_rules('notes', 'Notes', 'trim');
        if ($id) {
            $this->form_validation->set_rules('id', 'ID', 'required|integer');
        }
    }

    protected function _collect_asset_post()
    {
        $dept = $this->input->post('department_id', true);
        $emp = $this->input->post('employee_id', true);
        $war = $this->input->post('warranty_expiry', true);
        return array(
            'asset_name' => $this->input->post('asset_name', true),
            'asset_code' => $this->input->post('asset_code', true),
            'category_id' => (int) $this->input->post('category_id'),
            'purchase_date' => date('Y-m-d', strtotime($this->input->post('purchase_date'))),
            'purchase_cost' => (float) $this->input->post('purchase_cost'),
            'salvage_value' => $this->input->post('salvage_value') !== '' ? (float) $this->input->post('salvage_value') : 0,
            'useful_life_years' => (int) $this->input->post('useful_life_years'),
            'department_id' => $dept !== '' && $dept !== null ? (int) $dept : null,
            'employee_id' => $emp !== '' && $emp !== null ? (int) $emp : null,
            'warranty_expiry' => $war !== '' ? date('Y-m-d', strtotime($war)) : null,
            'condition_status' => $this->input->post('condition_status', true),
            'notes' => $this->input->post('notes', true),
        );
    }

    /**
     * @param string|null $previous_filename
     * @return string|false|null New filename, null if no new file, false on validation error (response sent)
     */
    protected function _handle_image_upload($previous_filename)
    {
        if (empty($_FILES['asset_image']['name'])) {
            return null;
        }
        $dir = FCPATH . $this->upload_rel_path;
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $config = array(
            'upload_path' => $this->upload_rel_path,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size' => 2048,
            'encrypt_name' => true,
        );
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('asset_image')) {
            $this->_json_fail($this->upload->display_errors(' ', ' '));
            return false;
        }
        $data = $this->upload->data();
        if ($previous_filename) {
            $this->_unlink_image($previous_filename);
        }
        return $data['file_name'];
    }

    protected function _unlink_image($filename)
    {
        if (!$filename) {
            return;
        }
        $path = FCPATH . $this->upload_rel_path . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
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
