<tr>
    <td>
        <select name="canteen_raw_goods_id[]" class="form-control" onchange="unit_load(this.id)" id="canteen_raw_goods_id_<?php echo $next_id ?>" sequence=0 required="" style="width:200px;">
            <option value="" selected="">Select Item</option>
            <?php
            $items = $this->db->select('*')->order_by('name', 'ASC')->get('canteen_raw_goods')->result();

            foreach ($items as $item) {
            ?>
                <option value="<?php echo $item->canteen_raw_goods_id ?>"><?php echo $item->name ?></option>
            <?php
            }
            ?>
        </select>
    </td>
    <td>
        <input onkeydown="validateNumberInput(event)" placeholder="Enter Quantity" type="text" value="" id="quantity<?php echo $next_id ?>" name="quantity[]" class="form-control" oninput="getamount(this, event)" sequence=<?php echo $next_id ?>>
    </td>
    <td>
        <input readonly type="text" id="unit_name<?php echo $next_id ?>" name="unit_name[]" class="form-control" sequence=0 required="">
        <input type="hidden" type="text" id="unit_id<?php echo $next_id ?>" name="unit_id[]" class="form-control" sequence=0 required="">
    </td>
    
    <td>
        <input onkeydown="validateNumberInput(event)" placeholder="Enter Unit Price" type="text" value="" id="price<?php echo $next_id ?>" name="price[]" class="form-control" oninput="getamount(this, event)" sequence=<?php echo $next_id ?> required="">
    </td>



    <td>
        <input placeholder="Total Amount" type="number" value="" id="total_amount<?php echo $next_id ?>" name="total_amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?> required="">
    </td>
    <td>
        <button class="btn btn-danger  btn-xs remove" type="button" sequence=<?php echo $next_id ?> onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
    </td>
</tr>