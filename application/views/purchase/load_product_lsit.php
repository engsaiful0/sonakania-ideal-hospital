<?php
$id = $_POST['id'];
?>
<tr id="tr_<?php echo $id ?>" style="margin-top:3px; ">
    <td>
        <select type="text" required="" class="form-control" onchange="model_load(this.id)" id="product_category_id_<?php echo $id ?>" name="product_category_id[]">
            <option selected="" disabled="">Product Category</option> 
            <?php
            $category = $this->db->select('*')->get('product_category')->result();
            foreach ($category as $value) {
                ?>
                <option value="<?php echo $value->product_category_id; ?>"><?php echo $value->category_name; ?></option>
                <?php
            }
            ?>

        </select>
    </td>
    <td>
        <select type="text" required=""  onchange="color_load(this.id), price_load(this.id)"  class="form-control" id="model_id_<?php echo $id ?>"  name="model_id[]">
            <option selected="" disabled=""> Model</option>

        </select>
    </td>
    <td>
        <a data-target="#globalModalDetails_<?php echo $id ?>"  data-toggle="modal" data-placement="top" data-content="update" class="btn btn-primary" >Color</a>

        <div class="modal fade" id="globalModalDetails_<?php echo $id ?>" role="dialog" >
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 800px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Color Stock</h4>
                    </div>
                    <div class="modal-body">
                        <div id="color_<?php echo $id ?>">

                        </div>
                    </div>
                    <div style="clear: left " class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
<!--        <select type="text" required="" onchange="prodcut_id(this.id)"  class="form-control" id="color_id_<?php echo $id ?>"  name="color_id_<?php echo $id ?>[]">


</select>-->
    </td>

    <td>
        <input type="number" required="" class="form-control"  oninput="total_price_cal(this.id)"  id="quantity_<?php echo $id ?>" placeholder="Enter Quantity" name="quantity[]">
    </td>
    <td>
        <input type="text" required="" readonly="" class="form-control"  id="purchase_unit_price_<?php echo $id ?>" placeholder="Enter Unit Price" name="purchase_unit_price[]">
    </td>
    <td>
        <!--<input type="hidden" class="form-control"  id="product_id_<?php echo $id ?>"  name="product_id[]">-->

        <input type="text" required="" class="form-control"  id="total_price_<?php echo $id ?>" placeholder="Enter Total Price" name="total_price[]">
    </td>
    <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="-"  ></td>
</tr>
<?php
