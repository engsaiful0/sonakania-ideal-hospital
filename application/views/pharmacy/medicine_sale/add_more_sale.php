<tr>
    <td class="medicine_serial"></td>
    <td style="padding:5px;">
        <input type="hidden" id="drug_id_value<?php echo $next_id ?>" sequence=<?php echo $next_id ?> readonly name="drug_id_value[]" class="form-control" ">
        <input  type=" text" placeholder="Enter Medicine Name" id="drug_id<?php echo $next_id ?>" sequence=<?php echo $next_id ?> name="drug_id[]" class="form-control" ">
    </td>
<td style=" padding:5px;">
        <input type="text" value="0" id="sales_rate<?php echo $next_id ?>" name="sales_rate[]"
            class="form-control" sequence=<?php echo $next_id ?> required="" onkeyup="getamount(this, event)">
    </td>

    <td style="padding:5px;">
        <input type="text" value="" id="quantity<?php echo $next_id ?>" name="quantity[]" class="form-control"
            sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)" required=""
            onkeyup="getamount(this, event)">
    </td>
    <td style="padding:5px;">
        <input type="text" value="" id="discounteach<?php echo $next_id ?>" name="discounteach[]" class="form-control"
            sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)">
    </td>


    <input type="hidden" value="0" id="pur_rate<?php echo $next_id ?>" name="pur_rate[]" class="form-control"
        sequence=<?php echo $next_id ?> readonly="">



    <td style="padding:5px;">
        <input type="text" id="amount<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly=""
            sequence=<?php echo $next_id ?>>
    </td>
    <td>
        <button title="Use Shift + Shortcut Key" onclick="removetr(this, event)" sequence="<?php echo $next_id; ?>" type="button" class="btn btn-sm btn-default">
            <i class="glyphicon glyphicon-remove"></i>
        </button>
    </td>
</tr>