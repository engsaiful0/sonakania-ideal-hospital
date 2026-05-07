<tr id="tr_<?php echo $id ?>" style="margin-top:3px;">
    <td><?php echo $id ?></td>
    <td>
        <select name="diagnosis_id[]" class="form-control" id="diagnosis_<?php echo $id ?>" style="width:100%;">
            <option value="" disabled="" selected="">Diagnosis </option>
            <?php
            $diagnosis = $this->db->select('*')->get('diagnosis')->result();

            foreach ($diagnosis as $value) {
                ?>
                <option value="<?php echo $value->diagnosis_id ?>"><?php echo $value->diagnosis_name ?></option>
                <?php
            }
            ?>
        </select>
    </td> 
    <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="-"  ></td>
</tr>
<?php
