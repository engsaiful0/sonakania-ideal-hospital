
<script>

    function purchase_price_calculate()
    {
        var mrp = Number($('#mrp').val());
        var commision = Number($('#commision').val());
        var purchase_price = Math.ceil(mrp - (mrp * (commision / 100)));
        $('#purchase_price').val(purchase_price);
        retail_sell_price_calculate();
    }
    function retail_sell_price_calculate()
    {
        var mrp = Number($('#mrp').val());
        var retail_sell_load = Number($('#retail_sell_load').val());
        var retail_sell_price = Math.ceil(mrp + (mrp * (retail_sell_load / 100)));
        $('#retail_sell_price').val(retail_sell_price);
    }
    function SomeDeleteRowFunction(btndel) {

        if (typeof (btndel) == "object") {
            $(btndel).closest("tr").remove();
        } else {
            return false;
        }
        sub_total();
    }
    function sub_total()
    {
        var id = $('#idControl').val();
        //  alert(id);
        var grand_total = 0;
        for (var i = 1; i <= Number(id); i++)
        {
            if (!isNaN($('#total_price_' + i).val()))
            {
                grand_total += Number($('#total_price_' + i).val());
            }


            // alert(grand_total);
        }
        $('#sub_total').val(grand_total);

        /*Discount Calculation start*/
        var discount = $('#discount').val().split("%");
        var grand_total = Math.ceil(grand_total - (grand_total * (Number(discount[0]) / 100)));
        /*Discount Calculation end*/
        $('#net_total').val(grand_total);
        //  $('#paid').val(grand_total);
    }
    function total_price_cal(quantity_id)
    {
        var id_no = quantity_id.split("_");
        //alert(id_no);
        var quantit = $('#quantity_' + id_no[1]).val();
        var unit_price = $('#purchase_unit_price_' + id_no[1]).val();
        $('#total_price_' + id_no[1]).val(Number(quantit) * Number(unit_price));
        var grand_total = 0;
        for (var i = 1; i <= Number(id_no[1]); i++)
        {
            grand_total += Number($('#total_price_' + i).val());
        }
        $('#sub_total').val(grand_total);

        /*Discount Calculation start*/
        var discount = $('#discount').val().split("%");
        var grand_total = Math.ceil(grand_total - (grand_total * (Number(discount[0]) / 100)));
        /*Discount Calculation end*/

        $('#net_total').val(grand_total);
        // $('#paid').val(grand_total);


    }
    function discount_cal(discount)
    {
        var net_total = $('#net_total').val();
        $('#net_total').val(Number(net_total) - Number(discount));
    }
    function due_cal(paid)
    {
        if (paid > Number($('#net_total').val()))
        {
            alert('Paid amount cant be greater than the net total amount');
            $('#paid').val(0);
        } else
        {
            var net_total = $('#net_total').val();
            var current_due = Number(net_total) - Number(paid);
            var previous_due = $('#due_temp').val();
            $('#due').val(Number(previous_due) + Number(current_due));
        }

    }
  
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Purchase Product</h3>
        </div>
        <div class="panel-body">
            <?php
            if ($this->session->userdata('success') != '') {
                ?>
                <div class="alert alert-success">
                    <strong>Success!</strong>Data has been saved successfully.
                </div>
                <?php
                $sdata['success'] = '';
                $this->session->set_userdata($sdata);
            }
            $purchase = $this->db->where('purchase_id', $purchase_id)->get('purchase')->row();
            ?>
            <form class="form-horizontal" method="post" action="<?php echo site_url('PurchaseController/edit_purchase_save') ?>" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-6">
                        <input id="purchase_id" name="purchase_id" type="hidden" value="<?php echo $purchase_id ?>">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Supplier</label>
                            <div class="col-sm-10">
                                <select type="text" required="" class="form-control"  id="supplier_id" name="supplier_id">

                                    <?php
                                    $supplier = $this->db->where('supplier_id', $purchase->supplier_id)->get('supplier')->row();
                                    ?>
                                    <option value="<?php echo $supplier->supplier_id; ?>"><?php echo $supplier->supplier_name; ?></option>
                                    <?php
                                    $supplier = $this->db->select('*')->get('supplier')->result();
                                    foreach ($supplier as $value) {
                                        ?>
                                        <option value="<?php echo $value->supplier_id; ?>"><?php echo $value->supplier_name; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">Date</label>
                            <div class="col-sm-10">          
                                <input type="text" required=""  value="<?php echo date('d-m-Y', strtotime($purchase->date)); ?>" class="form-control"  id="datepicker"  name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="stock_message">

                </div>
                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none"/>

                <div class="form-group">

                    <div class="col-sm-12">  
                        <table id="product_table" class="table table-bordered table-hover table-striped">

                            <tr id="1">
                                <td>Category</td>
                                <td>Model</td>
                                <td>Color</td>

                                <td>Quantity</td>
                                <td>Price</td>
                                <td></td>
                            </tr>
                            <?php
                            $purchase_product = $this->db->where('purchase_id', $purchase_id)->get('purchase_product')->result();
                            ?>
                            <input type="hidden"   id="idControl" value="<?php echo count($purchase_product) ?>">
                            <input type="hidden"   id="current_id" value="<?php echo count($purchase_product) ?>">
                            <?php
                            $id = 1;
                            foreach ($purchase_product as $purchase_product_value):
                                ?>
                                <tr>
                                    <td style="width: 20%;">
                                        <select type="text" required=""  class="form-control" onchange="model_load(this.id)" id="product_category_id_<?php echo $id ?>" name="product_category_id[]">
                                            <option selected="" disabled="">Category</option> 
                                            <?php
                                            $category = $this->db->where('product_category_id', $purchase_product_value->product_category_id)
                                                    ->get('product_category')
                                                    ->row();
                                            ?>
                                            <option value="<?php echo $category->product_category_id; ?>"><?php echo $category->category_name; ?></option>
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
                                    <td style="width: 22%;">
                                        <select type="text" required=""  onchange="color_load(this.id)"  class="form-control" id="model_id_<?php echo $id ?>"  name="model_id[]">
                                            <?php
                                            $model = $this->db->where('model_id', $purchase_product_value->model_id)
                                                    ->get('model')
                                                    ->row();
                                            ?>
                                            <option value="<?php echo $model->model_id; ?>"><?php echo $model->model_name; ?></option>

                                        </select>
                                    </td>
                                    <td style="width: 10%;">
                                        <select type="text" required=""  onchange="prodcut_id(this.id)"  class="form-control" id="color_id_<?php echo $id ?>"  name="color_id[]">
                                            <?php
                                            $color = $this->db->where('color_id', $purchase_product_value->color_id)
                                                    ->get('color')
                                                    ->row();
                                            ?>
                                            <option value="<?php echo $color->color_id; ?>"><?php echo $color->color_name; ?></option>


                                        </select>
                                    </td>

                                    <td style="width: 16%;">
                                        <input type="hidden" name="quantity_edit[]" value="<?php echo $purchase_product_value->quantity; ?>">
                                        <input type="text" required="" class="form-control"  id="quantity_<?php echo $id ?>" value="<?php echo $purchase_product_value->quantity; ?>" placeholder="Quantity" oninput="total_price_cal(this.id)" name="quantity[]">
                                    </td>
                                    <td  style="width: 15%;">
                                        <input type="text" required="" readonly="" class="form-control"  id="purchase_unit_price_<?php echo $id ?>" value="<?php echo $purchase_product_value->purchase_unit_price; ?>" placeholder=" Price" name="purchase_unit_price[]">
                                    </td>
                                    <td  style="width: 15%;">
                                        <input type="hidden" class="form-control"  id="product_id_1"  name="product_id[]">
                                        <input type="text" required="" class="form-control"  id="total_price_<?php echo $id ?>" placeholder=" Total Price" value="<?php echo $purchase_product_value->total_price; ?>" name="total_price[]">
                                    </td>
                                    <?php
                                    if ($id == 1) {
                                        ?>
                                        <td  style="width: 2%;"><input type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click To Add" value="+"  ></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="-"  ></td>

                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                $id = $id + 1;
                            endforeach;
                            ?>



                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">Sub Total</label>
                            <div class="col-sm-10">  

                                <input type="text" value="<?php echo $purchase->sub_total; ?>"  class="form-control"  readonly=""  id="sub_total"  name="sub_total">
                            </div>
                        </div>
                        <div class="form-group" style="display: none" >
                            <label class="control-label col-sm-2" for="pwd">Discount</label>
                            <div class="col-sm-10">  

                                <input type="text"  style="display: none" class="form-control" oninput="discount_cal(this.value)" value="<?php echo $purchase->discount; ?>"  id="discount" placeholder="Enter Discount" name="discount">
                            </div>
                        </div>
                        <div class="form-group" style="display: none" >
                            <label class="control-label col-sm-2" for="pwd">Net Total</label>
                            <div class="col-sm-10">          
                                <input type="text"  style="display: none" readonly="" class="form-control"  value="<?php echo $purchase->net_total; ?>" id="net_total"  name="net_total">
                            </div>
                        </div>
                        <div class="form-group" style="display: none" >
                            <label class="control-label col-sm-2" for="pwd">Paid</label>
                            <div class="col-sm-10">          
                                <input type="text"  style="display: none" class="form-control" oninput="due_cal(this.value)" value="<?php echo $purchase->paid; ?>"  id="paid"  name="paid">
                            </div>
                        </div>
                        <div class="form-group" style="display: none" >
                            <label class="control-label col-sm-2" for="pwd">Due</label>
                            <div class="col-sm-10">    
                                <input type="hidden" id="due_temp" name="due_temp">
                                <input type="text"  style="display: none" readonly="" value="0" value="<?php echo $purchase->due; ?>" class="form-control"  id="due"  name="due">
                            </div>
                        </div>

                        <div id="all_colors_stock" style="display: none" >
                        </div>
                        <div class="form-group">        
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

