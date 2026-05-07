<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaleModel
 *
 * @author sohag
 */
class PermissionModel extends CI_Model
{
    public function get_users()
    {
        return $this->db->get('user')->result();
    }
    public function get_permissions($user_id)
    {
        $this->db->select('module, action');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_permissions');

        $permissions = [];
        foreach ($query->result() as $row) {
            $permissions[$row->module][] = $row->action;
        }

        return $permissions;
    }
    public function save_user_permissions($user_id, $permissions)
    {
        // Delete existing permissions
        $this->db->delete('user_permissions', ['user_id' => $user_id]);
        // Check if permissions array is empty
        if (empty($permissions)) {
            return true; // Return early if no permissions to insert
        }
        // Prepare new permissions data
        $data = [];
        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $data[] = [
                    'user_id' => $user_id,
                    'module' => $module,
                    'action' => $action
                ];
            }
        }

        // Insert new permissions
        return $this->db->insert_batch('user_permissions', $data);
    }

}
