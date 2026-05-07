<tr>
    <td>
        <?php
        $assistant_nurse = '';
        if ($next_id == 1) {
            echo '1st Ass. Nurse';
            $assistant_surgon = '1st Ass. Nurse';
        } elseif ($next_id == 2) {
            echo '2nd Ass. Nurse';
            $assistant_surgon = '2nd Ass. Nurse';
        } elseif ($next_id == 3) {
            echo '3rd Ass. Nurse';
            $assistant_surgon = '3rd Ass. Nurse';
        } elseif ($next_id == 4) {
            echo '4th Ass. Nurse';
            $assistant_surgon = '4th Ass. Nurse';
        } elseif ($next_id == 5) {
            echo '5th Ass. Nurse';
            $assistant_surgon = '5th Ass. Nurse';
        } elseif ($next_id == 6) {
            echo '6th Ass. Nurse';
            $assistant_surgon = '6th Ass. Nurse';
        } elseif ($next_id == 7) {
            echo '7th Ass. Nurse';
            $assistant_surgon = '7th Ass. Nurse';
        } elseif ($next_id == 8) {
            echo '8th Ass. Nurse';
            $assistant_surgon = '8th Ass. Nurse';
        } elseif ($next_id == 9) {
            echo '9th Ass. Nurse';
            $assistant_surgon = '9th Ass. Nurse';
        } elseif ($next_id == 10) {
            echo '10th Ass. Nurse';
            $assistant_surgon = '10th Ass. Nurse';
        }
        ?>
    </td>
    <td>
      
        <select style="width: 100%;" type="text" class="form-control" id="employee_nurse_id_<?php echo $next_id ?>" name="employee_nurse_id[]" onchange="checkNurseDuplicate(this)">
            <option selected="" disabled="" value="">Select Nurse</option>
            <?php
            $this->db->select('employee.*,men_power_categories.name as category_name,men_power_categories.men_power_category_id');
            $this->db->from('employee');
            $this->db->join('men_power_categories', 'men_power_categories.men_power_category_id = employee.men_power_category_id', 'left');
            $this->db->where('men_power_categories.name', 'Nurse');
            $nurses = $this->db->get()->result();

            foreach ($nurses as $nurse) {
            ?>
                <option value="<?php echo $nurse->employee_id ?>"><?php echo $nurse->employee_name . '-' . $nurse->employee_unique_id ?></option>
            <?php
            }
            ?>
        </select>
    </td>

    <td>
        <input type="button" onclick="removetr(this, event)" sequence=<?php echo $next_id ?> style="width:30px" readonly id="add_more_<?php echo $next_id ?>" title="Click TO Remove" value="-">

    </td>
</tr>