<tr>
    <td style="padding:5px;">
        <select name="phygiotherapy_service_id[]" class="form-control" id="phygiotherapy_service_id_<?php echo $next_id ?>" sequence=0 onchange="phygiotherapy_service_price_load(this.id)" required="" style="width:150px;">
            <option value="" selected="">Select Service</option>
            <?php
            $phygiotherapy_services = $this->db->select('*')->get('phygiotherapy_service')->result();

            foreach ($phygiotherapy_services as $value) {
            ?>
                <option value="<?php echo $value->phygiotherapy_service_id ?>"><?php echo $value->name ?></option>
            <?php
            }
            ?>
        </select>
    </td>

    <td style="padding:5px;">
        <input readonly type="text" value="0" id="price_<?php echo $next_id ?>" name="price[]" class="form-control" sequence=<?php echo $next_id ?> required="" onkeyup="getamount(this, event)">
    </td>

    <td style="padding:5px;">

        <input type="text" value="1" oninput="getTotalAmount(this, event)" id="quantity_<?php echo $next_id ?>" name="quantity[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)">
    </td>
    <td style="padding:5px;">
        <input type="text" value="" id="discounteach_<?php echo $next_id ?>" name="discounteach[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)">
    </td>




    <td style="padding:5px;">
        <input type="text" id="amount_<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?>>
    </td>
    <td style="padding:5px;">
        <input type="button" onclick="removetr(this, event)" sequence=<?php echo $next_id ?> style="width:50px" readonly id="add_more_<?php echo $next_id ?>" title="Click TO Remove" value="-">

    </td>
</tr>