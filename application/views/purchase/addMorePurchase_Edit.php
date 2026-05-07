<tr>
    <td >
        <select name="type_name[]" class="form-control" id="type_name_<?php echo $next_id ?>" sequence=0 onchange="drug_name_load(this.id)" required="" style="width:110px;">
            <option value="" selected="">Select Type</option>
            <?php
            $sql = $this->db->select('*')->get('drug_type')->result();

            foreach ($sql as $value) {
                ?>
                <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                <?php
            }
            ?>
        </select>
    </td>
    <td>

        <select name="drug[]" class="form-control" id="drug_add<?php echo $next_id ?>" sequence=<?php echo $next_id ?> onchange="details(this, event);" required="" style="width:160px;">
                <option value="" selected="">Select Drug</option>
                    <?php
                    $sql = $this->db->where('manufacturer', $supplier)->get('drug')->result();

                    foreach ($sql as $value) {
                        ?>
                <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
                <?php
            }
            ?>
        </select>
    </td>
    <td>
        <input type="text"  value="" id="boxqty<?php echo $next_id ?>" name="boxqty[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event), getqty(this, event)" required="">
    </td>

    <td >
        <input type="text"  value="" id="pbq<?php echo $next_id ?>" name="pbq[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event), purchase_rate(this, event), getqty(this, event)" required="">
    </td>
    <td >
        <input type="text"  value="" id="boxrate<?php echo $next_id ?>" name="boxrate[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="purchase_rate(this, event), getamount(this, event)" required="">
    </td>

    <td>
        <input type="text"  value="" id="discount<?php echo $next_id ?>" style="width:40px;" name="discount[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="purchase_rate(this, event), getamount(this, event)">
    </td>

    <td >
        <input type="text"  value="" id="pur_rate<?php echo $next_id ?>" name="pur_rate[]" class="form-control"  sequence=<?php echo $next_id ?> required="">
    </td>
    <td>
        <input type="text"  value="" id="mrp<?php echo $next_id ?>" name="mrp[]" class="form-control" sequence=<?php echo $next_id ?> >
    </td>
    <td>
        <input type="text"  value="" id="qty<?php echo $next_id ?>" name="qty[]" class="form-control" readonly="" sequence=sequence=<?php echo $next_id ?>>
        <input type="hidden"  value="0" id="qty_edit<?php echo $next_id ?>" name="qty_edit[]" class="form-control" readonly="" sequence=<?php echo $next_id ?>>
    </td>

    <td>
        <input type="text"  value="" id="amount<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?> required="">
    </td>
    <td>
        <input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $next_id ?>" title="Click TO Remove"  value="-"  >
    </td>
</tr>