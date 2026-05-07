<?php

class DirectorModel extends CI_Model
{
    public function get_director_by_id($director_id)
    {
        $this->db->from('director');
        $this->db->where('director_id', $director_id);
        $query = $this->db->get();
        return $query->row();
    }
    public function get_director($limit, $offset, $unique_id = '', $name = '', $mobile = '', $category = '')
    {
        $this->db->from('director');

        if ($unique_id != '') {
            $this->db->where('unique_id', $unique_id);
        }

        if ($name != '') {
            $this->db->where('name', $name);
        }

        if ($mobile != '') {
            $this->db->where('mobile', $mobile);
        }

        if ($category != '') {
            $this->db->where('category', $category);
        }

        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        return $query->result();
    }

    public function count_all_directors($unique_id = '', $name = '', $mobile = '', $category = '')
    {
        $this->db->from('director');

        if ($unique_id != '') {
            $this->db->where('unique_id', $unique_id);
        }

        if ($name != '') {
            $this->db->where('name', $name);
        }

        if ($mobile != '') {
            $this->db->where('mobile', $mobile);
        }

        if ($category != '') {
            $this->db->where('category', $category);
        }

        return $this->db->count_all_results();
    }
}
