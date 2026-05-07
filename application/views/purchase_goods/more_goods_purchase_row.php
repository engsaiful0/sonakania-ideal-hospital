<tr>
    <td>
        <select name="item_id[]" class="form-control" onchange="price_select(this.id)" id="item_id_<?php echo $next_id ?>" sequence=0  required="" style="width:200px;">
            <option value="" selected="">Select Item</option>
            <?php
            $items = $this->db->select('*')->order_by('item_name', 'ASC')->get('item')->result();

            foreach ($items as $item) {
            ?>
                <option value="<?php echo $item->item_id ?>"><?php echo $item->item_name ?></option>
            <?php
            }
            ?>
        </select>
    </td>
   

    <td>
        <input onkeydown="validateNumberInput(event)" placeholder="Enter Unit Price" type="text" value="" id="price<?php echo $next_id ?>" name="price[]" class="form-control" oninput="getamount(this, event)" sequence=<?php echo $next_id ?> required="">
    </td>
  
    <td>
        <input onkeydown="validateNumberInput(event)" placeholder="Enter Quantity" type="text" value="" id="quantity<?php echo $next_id ?>" name="quantity[]" class="form-control" oninput="getamount(this, event)" sequence=<?php echo $next_id ?>>
    </td>

    <td>
        <input placeholder="Total Amount" type="number" value="" id="total_amount<?php echo $next_id ?>" name="total_amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?> required="">
    </td>
    <td>
        <button class="btn btn-danger  btn-xs remove" type="button" sequence=<?php echo $next_id ?> onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
    </td>
</tr>