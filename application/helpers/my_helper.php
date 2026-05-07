<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	function make_select($tablename='',$value='',$name='',$condition=array(),$selected_value='')
	{
		$ci=& get_instance();
		$ci->load->database();
		$ci->db->select($value.",".$name);
		$ci->db->from($tablename);
		$result=$ci->db->get();
		$optionstr='';
		foreach($result->result() as $row)
		{
			$optionvalue=$row->$value;
			$optionname=$row->$name;
			if($selected_value==$optionvalue)
				$optionstr.="<option value='".$optionvalue."' selected>".$optionname."</option>";
			else
				$optionstr.="<option value='".$optionvalue."'>".$optionname."</option>";
		}
		return $optionstr;
	}
	function getuniqeid($id='')
	{
		$id="INV".rand(1,999999999999999);
		$ci=& get_instance();
		$ci->load->database();
		$ci->db->where(array('invoice'=>$id));
		$ci->db->from('sales');
		$result=$ci->db->get();
		if($result->num_rows()>0)
		{
			return getuniqeid();
		}
		return $id;
	}
	function get_single_value($row,$table,$condition=array())
	{
		$ci=& get_instance();
		$ci->load->database();
		if(count($condition))
		$ci->db->where($condition);
		$ci->db->select($row);
		$ci->db->from($table);
		$result=$ci->db->get();
		$value=0;
		foreach($result->result() as $rows)
		{
			$value=$rows->$row;
		}
		return $value;
	}
	function generate_id($table,$field,$prefix='ZIT-',$length=10,$condition=array())
	{
		$ci=& get_instance();
		$ci->load->database();
		if(count($condition))
		$ci->db->where($condition);
		$ci->db->select($field);
		$ci->db->from($table);
		$result=$ci->db->get();
		$value='';
		$id=str_pad($prefix,$length,"0");
		foreach($result->result() as $rows)
		{
			$value=$rows->$field;
		}
		if($value)
		{
			$prefix_lenght=strlen($prefix);
			$suffix=substr($value,$prefix_lenght,$length-$prefix_lenght);
			$suffix=(int)$suffix;
			$suffix++;
			$suffix_length=strlen($suffix);
			$id=substr($id,0,-$suffix_length);
			$id=$id.$suffix;
		}
		else
		{
			$id=substr($id,0,-1);
			$id.='1';
		}
		return $id;
	}
	
	/**
	 * Generate unique_id for share_holder table
	 * Format: SH + timestamp + serial_number (3 digits)
	 * @param string $prefix - Prefix for the unique_id (default: 'SH')
	 * @return string - Generated unique_id
	 */
	function generate_share_holder_unique_id($prefix = 'SH') {
		$ci =& get_instance();
		$ci->load->database();
		
		// Get the last record to determine next serial number
		$last_record = $ci->db->select('unique_id')
			->order_by('id', 'DESC')
			->limit(1)
			->get('share_holder')
			->row();
		
		$serial_number = 1;
		
		if ($last_record && !empty($last_record->unique_id)) {
			// Extract serial number from existing unique_id
			$unique_id_parts = explode($prefix, $last_record->unique_id);
			if (count($unique_id_parts) > 1) {
				$timestamp_part = $unique_id_parts[1];
				// Extract the serial number (last 3 digits)
				preg_match('/(\d{3})$/', $timestamp_part, $matches);
				if (!empty($matches[1])) {
					$serial_number = intval($matches[1]) + 1;
				}
			}
		}
		
		// Generate new unique_id: prefix + timestamp + serial_number
		$unique_id = $prefix . time() . str_pad($serial_number, 3, '0', STR_PAD_LEFT);
		
		// Ensure uniqueness by checking if it already exists
		while (is_share_holder_unique_id_exists($unique_id)) {
			$serial_number++;
			$unique_id = $prefix . time() . str_pad($serial_number, 3, '0', STR_PAD_LEFT);
		}
		
		return $unique_id;
	}
	
	/**
	 * Check if unique_id already exists in share_holder table
	 * @param string $unique_id - The unique_id to check
	 * @return bool - True if exists, false otherwise
	 */
	function is_share_holder_unique_id_exists($unique_id) {
		$ci =& get_instance();
		$ci->load->database();
		
		$count = $ci->db->where('unique_id', $unique_id)
			->count_all_results('share_holder');
		return $count > 0;
	}
	
	/**
	 * Update unique_id for existing share_holders that don't have one
	 * @return int - Number of records updated
	 */
	function update_share_holder_unique_ids() {
		$ci =& get_instance();
		$ci->load->database();
		
		// Get all share_holders without unique_id or with empty unique_id
		$share_holders = $ci->db->where('unique_id IS NULL OR unique_id = ""')
			->get('share_holder')
			->result();
		
		$updated_count = 0;
		
		foreach ($share_holders as $share_holder) {
			$unique_id = generate_share_holder_unique_id();
			
			$ci->db->where('id', $share_holder->id)
				->update('share_holder', array('unique_id' => $unique_id));
			
			$updated_count++;
		}
		
		return $updated_count;
	}
?>