<?php

class Drug extends CI_Controller
{

    private $defaults = array();

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

    public function drug_update_save()
    {
        $drug_id = $this->input->post('drug_id');
        $update = array(
            'manufacturer' => $this->input->post('manufacturer'),
            'drug_name' => $this->input->post('drug_name'),
            'type' => $this->input->post('type'),
            'mrp' => $this->input->post('mrp'),
            'whole_sale_rate' => $this->input->post('whole_sale_rate'),
            'pur_rate' => $this->input->post('pur_rate'),
            'stock' => $this->input->post('stock'),
            'shelf' => $this->input->post('shelf'),
            'expdate' => date('Y-m-d', strtotime($this->input->post('expdate'))),
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

    public function drug_add_save()
    {
        $update = array(
            'manufacturer' => $this->input->post('manufacturer'),
            'drug_name' => $this->input->post('drug_name'),
            'type' => $this->input->post('type'),
            'mrp' => $this->input->post('mrp'),
            'whole_sale_rate' => $this->input->post('whole_sale_rate'),
            'pur_rate' => $this->input->post('pur_rate'),
            'stock' => $this->input->post('stock'),
            'shelf' => $this->input->post('shelf'),
            'expdate' => date('Y-m-d', strtotime($this->input->post('expdate'))),
        );
        $this->db->insert('drug', $update);

        $sdata = array('msg' => 'success');
        $this->session->set_userdata($sdata);
        redirect('Drug/drug_add_view');
    }

    public function alldrug_st()
    {
        $drug_name = $this->input->post('drug_name');
        $manufacturer = $this->input->post('manufacture_id');
        $config['base_url'] = site_url('Drug/alldrug_st');
        $config['total_rows'] = $this->db->count_all('drug');
        $config['per_page'] = 600;
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
        $data['detailsList'] = $this->all_drug_get($config["per_page"], $data['page'], $drug_name, $manufacturer);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('stock_entry_search', $data);
    }

    function all_drug_get($limit, $start, $drug_name, $manufacturer)
    {
        // print_r('nam'.$drug_name.'<br>');
        // print_r('manu'.$manufacturer);


        if ($drug_name != '' && $manufacturer != '') {
            $sql = "select  * from drug where drug_id='$drug_name%' and manufacturer='$manufacturer' order by manufacturer asc limit " . $start . ", " . $limit;
            $query = $this->db->query($sql);
            return $query->result();
        } else if ($drug_name == '' && $manufacturer != '') {
            $sql = "select  * from drug where  manufacturer='$manufacturer' order by manufacturer asc limit " . $start . ", " . $limit;
            $query = $this->db->query($sql);
            return $query->result();
        } else if ($drug_name == '' && $manufacturer == '') {
            $sql = "select  * from drug  order by manufacturer asc limit " . $start . ", " . $limit;
            $query = $this->db->query($sql);
            return $query->result();
        }
    }


    function log_user_after_insert($post_array, $primary_key)
    {

        $data = array();
        $data['drug'] = $primary_key;
        $data['quantity'] = $post_array['stock'];
        $data['re_order_qty'] = $post_array['re_order_qty'];

        $this->db->insert('stock', $data);

        $dr = $this->db->where('drug_id', $primary_key)->get('drug')->row();
        $dr_type = $this->db->where('drug_type_id', $dr->type)->get('drug_type')->row();
        $this->db->where('drug_id', $primary_key)->update('drug', array('drug_type_name' => $dr_type->type_name));

        return true;
    }

    function stock_update($post_array, $primary_key)
    {

        return true;
    }

    function drugtype()
    {
        $this->grocery_crud->set_subject('Drug Type');
        $output = $this->grocery_crud->set_table('drug_type')
            //->set_theme('datatables')
            ->where('drug_type.company_id', $this->session->userdata('company_id'))
            ->columns('type_name')
            ->display_as('type_name', 'Type Name')
            ->field_type('company_id', 'hidden', $this->session->userdata('company_id'))
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function path_test_name()
    {
        $this->grocery_crud->set_subject('Test Name');
        $output = $this->grocery_crud->set_table('pat_test_name')
            //->set_theme('datatables')
            ->columns('test_name')
            ->display_as('test_name', 'Test Name')
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function company()
    {
        $this->grocery_crud->set_subject('Company');
        $output = $this->grocery_crud->set_table('company')
            ->columns('company_name', 'company_address', 'company_phone', 'company_email')
            ->display_as('company_name', 'Company Name')
            ->display_as('company_address', 'Address')
            ->display_as('company_phone', 'Phone')
            ->display_as('company_email', 'Email')
            ->unset_add()
            ->unset_delete()
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function manufacturer()
    {
        $this->grocery_crud->set_subject('Manufacturer');
        $output = $this->grocery_crud->set_table('manufacturer')
            ->columns('name', 'phone', 'email', 'address', 'contact_person', 'contact_person_phone')
            ->field_type('company_id', 'hidden', $this->session->userdata('company_id'))
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function unit()
    {
        $this->grocery_crud->set_subject('Unit');
        $output = $this->grocery_crud->set_table('unit')
            ->where('unit.company_id', $this->session->userdata('company_id'))
            ->columns('name')
            ->field_type('company_id', 'hidden', $this->session->userdata('company_id'))
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function group()
    {
        $this->grocery_crud->set_subject('Group');
        $output = $this->grocery_crud->set_table('drug_group')
            //			->where('drug_group.company_id',$this->session->userdata('company_id'))
            ->columns('name')
            ->field_type('company_id', 'hidden', $this->session->userdata('company_id'))
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function test_details()
    {

        $test_name = $this->input->post('test');
        //     print_r($test_name);
        if (!$test_name)
            return;
        $condition = array(
            'id' => $test_name
        );
        $details['price'] = get_single_value('price', 'pat_test_name', $condition);
        echo json_encode($details);
    }

    function details()
    {
        error_reporting(0);
        $drug = $this->input->post('drug');
        $drug = $this->db->where('drug_id', $drug)->get('drug')->row();
        $details = array();
        $details['mrp'] = $drug->mrp;
        $details['wsr'] = $drug->whole_sale_rate;
        $details['purchase_rate'] = $drug->purchase_rate;
        $type = $this->db->where('drug_type_id', $drug->drug_type_id)->get('drug_type')->row();
        $shelf = $this->db->where('shelf_id', $drug->shelf_id)->get('shelfs')->row();

        $details['shelf'] = $shelf->shelf_number;
        $details['group_name'] = $type->type_name;
        echo json_encode($details);
    }
}
