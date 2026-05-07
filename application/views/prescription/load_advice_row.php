<tr id="tr_<?php echo $id ?>" style="margin-top:3px;">
     <td><?php echo $id ?></td>
    <td>

        <select name="advice_id[]" class="form-control" id="advice_<?php echo $id ?>" style="width:100%;">
            <option value="" disabled="" selected="">Advice</option>
            <?php
            $advice = $this->db->select('*')->get('advice')->result();

            foreach ($advice as $value) {
                ?>
                <option value="<?php echo $value->advice_id ?>"><?php echo $value->advice_name ?></option>
                <?php
            }
            ?>
        </select>
    </td>
    <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="-"  ></td>
</tr>
<?php
