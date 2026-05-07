<?php 
class User_Model extends CI_Model{    
    private $table = 'tblusers';    
    public function getCount() {
        return $this->db->count_all($this->table);
    }
    public function getUsers($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        return $query->result();
    }
}
?>