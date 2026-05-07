<tr>
    <td style="padding:5px;">
        <select style="width: 300px;" name="ipd_service_item_id[]" class="form-control" id="ipd_service_item_id_<?php echo $next_id ?>" sequence=0 onchange="ipd_service_price_load(this.id)" required="" style="width:150px;">
            <option selected="" value="" disabled="">Select Service Item</option>
            <?php
            $ipd_services = $this->db->where('service_type','Optional')->get('ipd_service_item')->result();

            foreach ($ipd_services as $value) {
            ?>
                <option value="<?php echo $value->ipd_service_item_id; ?>"><?php echo $value->name; ?></option>
            <?php
            }
            ?>
        </select>
    </td>
    <td style="padding:5px;">

        <input  style="width: 100px;" type="text" value="1" oninput="total_price_cal(this.id)" id="quantity_<?php echo $next_id ?>" name="quantity[]" class="form-control" sequence=<?php echo $next_id ?>  required="" onkeyup="getamount(this, event)">
    </td>
    <td style="padding:5px;">
        <input style="width: 100px;" readonly type="text"  id="price_<?php echo $next_id ?>" name="price[]" class="form-control" sequence=<?php echo $next_id ?> required="" onkeyup="getamount(this, event)">
    </td>

    <td style="padding:5px;">
        <input style="width: 100px;" type="text" id="amount_<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?>>
    </td>
    <td style="padding:5px;">
        <input  type="button" onclick="removetr(this, event)" sequence=<?php echo $next_id ?> style="width:40px" readonly id="add_more_<?php echo $next_id ?>" title="Click TO Remove" value="-">

    </td>
</tr>