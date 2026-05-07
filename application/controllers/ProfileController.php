<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileController extends CI_Controller
{

    public function index()
    {
        $this->load->view('profile/profile_update');
    }
    public function update_profile_save()
    {
        // Load required helpers and libraries
        $this->load->library('form_validation');
        $this->load->helper('security');

        // Set validation rules
        $this->form_validation->set_rules('user_name', 'User Name', 'required|trim');
        $this->form_validation->set_rules('new_password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric');


        // Check if form validation passes
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, return errors
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        // Retrieve input values
        $user_id = $this->session->userdata('user_id');
        $user_name = $this->input->post('user_name', TRUE); // TRUE ensures XSS clean
        $new_password = $this->input->post('new_password', TRUE);
        $mobile = $this->input->post('mobile', TRUE);
        $email = $this->input->post('email', TRUE);

        // Prepare data for update
        $data = [
            'user_name' => $user_name,
            'mobile' => $mobile,
            'email' => $email,
            'password' => $new_password,
        ];
        // Handle file upload for profile picture
            $config['upload_path'] =  'assets/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // Max size in KB (2 MB)
            $config['file_name'] = 'profile_' . $user_id . '_' . time(); // Unique file name

            $this->load->library('upload', $config);
            
        
            if ($this->upload->do_upload('picture')) {
                // Successfully uploaded
                $uploadData = $this->upload->data();
                $data['picture'] = $uploadData['file_name'];
            } else {
                // Upload failed, return error
                echo json_encode([
                    'success' => false,
                    'message' => $this->upload->display_errors()
                ]);
                return;
            }
        // Update the database
        $this->db->where('user_id', $user_id);
        $update = $this->db->update('user', $data);

        if ($update) {
            // Success response
            echo json_encode([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        } else {
            // Database update failed
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update profile. Please try again.'
            ]);
        }
    }
}
