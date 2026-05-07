ALTER TABLE `retail_customer` ADD `note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `retail_customer_name`;
      /* To update note start*/
            $update_note = array(
                'note' => $this->input->post('note')
            );

            $this->db->where('retail_customer_id', $retail_customer_id_no->retail_customer_id)
                    ->update('retail_customer', $update_note); /* To update note end*/