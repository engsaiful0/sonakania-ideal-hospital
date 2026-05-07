<tr id="tr_<?php echo $id ?>" style="margin-top:3px; ">
    <td style="width: 20%;display:none">
        <select style="width: 100%;" type="text" required="" class="form-control" onchange="test_name_load(this.id)" id="test_group_id_<?php echo $id ?>" name="test_group_id[]">
            <option selected="" value="" disabled="">Select Group Name</option>
            <?php
            $test_group = $this->db->select('*')->get('test_group')->result();
            foreach ($test_group as $value) {
            ?>
                <option value="<?php echo $value->test_group_id; ?>"><?php echo $value->test_group_name; ?></option>
            <?php
            }
            ?>

        </select>
    </td>
    <td>
        <input type="hidden"  class="form-control" id="test_category_id_<?php echo $id ?>"   name="test_category_id[]">
        <select style="width: 100%;" type="text" required="" class="form-control" onchange="price_load(this.id)" id="test_id_<?php echo $id ?>" name="test_id[]">
            <option value="" disabled="" selected="">Select Test Name</option>
            <?php
            $test = getAllTestNames();
            foreach ($test as $value) {
            ?>
                <option value="<?php echo $value->test_id; ?>"><?php echo $value->test_name; ?></option>
            <?php
            } ?>

        </select>
    </td>
    <td>
        <input type="text" value="<?php echo date('d-m-Y'); ?>" class="form-control" id="datepicker<?php echo $id ?>" name="delivery_date[]">
    </td>

    <td>
        <input type="text" value="1" required="" class="form-control" oninput="total_price_cal(this.id)" id="quantity_<?php echo $id ?>" placeholder="Enter Quantity" name="quantity[]">
    </td>
    <td>
        <input type="text" required="" class="form-control" oninput="total_price_cal_unit_price(this.id)" id="unit_price_<?php echo $id ?>" placeholder="Enter Unit Price" name="unit_price[]">
    </td>
    <td>

        <input type="text" required="" class="form-control" id="total_price_<?php echo $id ?>" placeholder="Enter Total Price" name="total_price[]">
    </td>
    <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove" value="-"></td>
</tr>
<?php
