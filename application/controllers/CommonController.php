<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DealerController
 *
 * @author Lenovo
 */
class CommonController extends CI_Controller
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
    }
    function director_discount_load()
    {
        $reference_director_id = $_POST['reference_director_id'];
        $director = $this->db->where('director_id', $reference_director_id)->get('director')->row();
        echo $director->emergency_discount;
    }
    function director_test_discount_load()
    {
        $reference_director_id = $this->input->post('reference_director_id');
        $director = $this->db->where('director_id', $reference_director_id)->get('director')->row();
        if (!$director) {
            echo '0*0';
            return;
        }
        $pct = isset($director->test_discount) ? $director->test_discount : 0;
        echo (int) $director->director_id . '*' . $pct;
    }
    function director_opd_discount_load()
    {
        $reference_director_id = $_POST['reference_director_id'];
        $director = $this->db->where('director_id', $reference_director_id)->get('director')->row();
        echo $director->opd_discount;
    }
    function director_ipd_discount_load()
    {
        $reference_director_id = $_POST['reference_director_id'];
        $director = $this->db->where('director_id', $reference_director_id)->get('director')->row();
        echo $director->ipd_discount;
    }
    function director_phygiotherapy_discount_load()
    {
        $reference_director_id = $_POST['reference_director_id'];
        $director = $this->db->where('director_id', $reference_director_id)->get('director')->row();
        echo $director->phygiotherapy_discount;
    }
    
    function director_emergency_discount_load()
    {
        $reference_director_id = $_POST['reference_director_id'];
        $director = $this->db->where('director_id', $reference_director_id)->get('director')->row();
        echo $director->emergency_discount;
    }
}
