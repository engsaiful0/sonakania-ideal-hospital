<tr>
    <td>
        <?php
        $assistant_surgon='';
        if ($next_id == 1) {
            echo '1st Ass. Surgon';
            $assistant_surgon='1st Ass. Surgon';
        } elseif ($next_id == 2) {
            echo '2nd Ass. Surgon';
            $assistant_surgon='2nd Ass. Surgon';
        } elseif ($next_id == 3) {
            echo '3rd Ass. Surgon';
            $assistant_surgon='3rd Ass. Surgon';
        } elseif ($next_id == 4) {
            echo '4th Ass. Surgon';
            $assistant_surgon='4th Ass. Surgon';
        } elseif ($next_id == 5) {
            echo '5th Ass. Surgon';
            $assistant_surgon='5th Ass. Surgon';
        } elseif ($next_id == 6) {
            echo '6th Ass. Surgon';
            $assistant_surgon='6th Ass. Surgon';
        } elseif ($next_id == 7) {
            echo '7th Ass. Surgon';
            $assistant_surgon='7th Ass. Surgon';
        } elseif ($next_id == 8) {
            echo '8th Ass. Surgon';
            $assistant_surgon='8th Ass. Surgon';
        } elseif ($next_id == 9) {
            echo '9th Ass. Surgon';
            $assistant_surgon='9th Ass. Surgon';
        } elseif ($next_id == 10) {
            echo '10th Ass. Surgon';
            $assistant_surgon='10th Ass. Surgon';
        }
        ?>
    </td>
    <td>
        <select onchange="checkDuplicate(this)" style="width: 100%;" type="text" class="form-control" id="surgon_doctor_id_<?php echo $next_id ?>" name="surgon_doctor_id[]">
            <option selected="" disabled="" value="">Select <?php echo $assistant_surgon?></option>
            <?php
            $doctor = $this->db->select('*')->order_by('doctor_name', 'ASC')->get('doctor')->result();
            foreach ($doctor as $doctor_value) {
            ?>
                <option value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
            <?php
            }
            ?>
        </select>
    </td>

    <td>
        <input type="button" onclick="removetr(this, event)" sequence=<?php echo $next_id ?> style="width:30px" readonly id="add_more_<?php echo $next_id ?>" title="Click TO Remove" value="-">

    </td>
</tr>