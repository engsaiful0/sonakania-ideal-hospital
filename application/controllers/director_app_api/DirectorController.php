<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DirectorController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
    }

    private function setCors()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }

    /**
     * Daily Discount
     */
    public function getDirectorInfo()
    {
        $this->setCors();
        $director_id = $this->input->post('director_id');
        
        if (!$director_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Director ID is required']));
            return;
        }
        
        $this->db->select('*');
        $this->db->from('director');
        $this->db->where('director_id', $director_id);
        $query = $this->db->get();
        $director_info = $query->row();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['director_info' => $director_info]));
    }

}
