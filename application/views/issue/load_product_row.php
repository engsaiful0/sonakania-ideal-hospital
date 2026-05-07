<tr>
    <td >
        <select  style="width:100%" type="text" required="" sequence=<?php echo $next_id ?> class="form-control" onchange="available_quantity_load(this.id)" id="item_id_<?php echo $next_id ?>" name="item_id[]">
            <option selected="" value="" disabled="">Select Item</option>
            <?php
            $items = $this->db->select('*')->order_by('item_name','ASC')->get('item')->result();
            foreach ($items as $value) {
            ?>
                <option value="<?php echo $value->item_id; ?>"><?php echo $value->item_name; ?></option>
            <?php
            }
            ?>

        </select>
    </td>
    <td >
        <input style="width:100%" readonly type="text" required="" sequence=<?php echo $next_id ?> value="" class="form-control" id="available_quantity_<?php echo $next_id ?>" placeholder="Available Quantity" name="available_quantity[]">
    </td>

    <td >
        <input style="width:100%" type="text" required="" sequence=<?php echo $next_id ?> value="" oninput="getTotalQuantity(this, event)" class="form-control issue_quantity" id="issue_quantity_<?php echo $next_id ?>" placeholder="Issue Quantity" name="issue_quantity[]">
    </td>
    <td >
        <input style="width: 40px" type="button" onclick="removetr(this, event)" sequence=<?php echo $next_id ?> style="width:50px" readonly id="add_more_<?php echo $next_id ?>" title="Click TO Remove" value="-">
    </td>
</tr>