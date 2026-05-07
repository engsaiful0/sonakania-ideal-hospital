<?php
class DrugModel extends CI_Model
{
    public function all_drugs_get_for_view($limit, $offset, $drug_type_id = '', $drug_id = '', $manufacturer_id = '', $drug_name = '')
    {
        //die($drug_name);
        // Apply filters based on conditions
        if ($drug_type_id != '') {
            $this->db->where('drug_type_id', $drug_type_id);
        }
        if ($drug_id != '') {
            $this->db->where('drug_id', $drug_id);
        }
        if ($manufacturer_id != '') {
            $this->db->where('manufacturer_id', $manufacturer_id);
        }
        if ($drug_name != '') {
            $this->db->where('drug_name', $drug_name);  // 'like' is better for partial matches
        }

        // If 'limit' is 0, fetch all rows without pagination
        if ($limit == 0) {
            $query = $this->db->order_by('drug_id', 'DESC')->get('drug');
        } else {
            // Apply limit and offset for pagination
            $query = $this->db->order_by('drug_id', 'DESC')->get('drug', $limit, $offset);
        }

        // Return results as an array of objects
        return $query->result();
    }

    public function count_all_drugs_for_view($drug_type_id = '', $drug_id = '', $manufacturer_id = '', $drug_name = '')
    {
        // Apply filters based on conditions
        if ($drug_type_id != '') {
            $this->db->where('drug_type_id', $drug_type_id);
        }
        if ($drug_id != '') {
            $this->db->where('drug_id', $drug_id);
        }
        if ($manufacturer_id != '') {
            $this->db->where('manufacturer_id', $manufacturer_id);
        }
        if ($drug_name != '') {
            $this->db->like('drug_name', $drug_name);  // 'like' is better for partial matches
        }

        // Count the total number of rows
        return $this->db->count_all_results('drug');
    }


    public function all_drugs_get($limit, $offset, $drug_type_id = '', $drug_id = '', $manufacturer_id = '', $drug_name = '')
    {
        if ($drug_type_id != '' && $drug_id != '' && $manufacturer_id != '' && $drug_name == '') {
            $this->db->where('drug_type_id', $drug_type_id)
                ->where('drug_id', $drug_id)
                ->where('manufacturer_id', $manufacturer_id);
        } else if ($drug_type_id != '' && $drug_id != '' && $manufacturer_id == '' && $drug_name == '') {
            $this->db->where('drug_type_id', $drug_type_id)
                ->where('drug_id', $drug_id);
        } else if ($drug_type_id != ''  && $drug_id == '' && $manufacturer_id != '' && $drug_name == '') {
            $this->db->where('drug_type_id', $drug_type_id)
                ->where('manufacturer_id', $manufacturer_id);
        } else if ($drug_type_id != ''  && $drug_id == '' && $manufacturer_id == '' && $drug_name == '') {
            $this->db->where('drug_type_id', $drug_type_id);
        } else if ($drug_type_id == ''  && $drug_id != '' && $manufacturer_id == '' && $drug_name == '') {
            $this->db->where('drug_id', $drug_id);
        } else if ($drug_type_id == ''  && $drug_id == '' && $manufacturer_id != '' && $drug_name == '') {
            $this->db->where('manufacturer_id', $manufacturer_id);
        } else if ($drug_type_id == ''  && $drug_id == '' && $manufacturer_id == '' && $drug_name != '') {
            $this->db->where('drug_name', $drug_name);
        }

        $query = $this->db->order_by('drug_name', 'ASC')->get('drug', $limit, $offset);
        return $query->result();
    }

    public function count_all_drugs($drug_type_id = '', $drug_id = '', $manufacturer_id = '')
    {
        if ($drug_type_id != '' && $drug_id != '' && $manufacturer_id != '') {
            $this->db->where('drug_type_id', $drug_type_id)
                ->where('drug_id', $drug_id)
                ->where('manufacturer_id', $manufacturer_id);
        } else if ($drug_type_id != '' && $drug_id != '' && $manufacturer_id == '') {
            $this->db->where('drug_type_id', $drug_type_id)
                ->where('drug_id', $drug_id);
        } else if ($drug_type_id != ''  && $drug_id == '' && $manufacturer_id != '') {
            $this->db->where('drug_type_id', $drug_type_id)
                ->where('manufacturer_id', $manufacturer_id);
        } else if ($drug_type_id != ''  && $drug_id == '' && $manufacturer_id == '') {
            $this->db->where('drug_type_id', $drug_type_id);
        } else if ($drug_type_id == ''  && $drug_id != '' && $manufacturer_id == '') {
            $this->db->where('drug_id', $drug_id);
        } else if ($drug_type_id == ''  && $drug_id == '' && $manufacturer_id != '') {
            $this->db->where('manufacturer_id', $manufacturer_id);
        }

        return $this->db->count_all_results('drug');
    }
}
