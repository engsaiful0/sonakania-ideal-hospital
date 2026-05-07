<script type="text/javascript">
    function model_load(product_category_id)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById('model_id_modal').innerHTML = xhttp.responseText;
                alert(xhttp.responseText);
                //   comission_load(product_category_id);

            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/model_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id);

    }

    function color_load()
    {
        var product_category_id = Number($('#product_category_id_modal').val());
        var model_id = Number($('#model_id_modal').val());
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("color_id_modal").innerHTML = xhttp.responseText;

            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/color_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id + "&model_id=" + model_id);
    }
    function comission_load(product_category_id)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                document.getElementById("commision_modal").value = xhttp.responseText;

            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/comission_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id);
    }

</script>
<script>
    $(document).ready(function () {
// alert();
        $('#product_category_id_modal').select2();
        $('#model_id_modal').select2();
        $('#color_id_modal').select2();

    });
    function purchase_price_calculate()
    {
        var mrp = Number($('#mrp_modal').val());
        var commision = Number($('#commision_modal').val());
        var purchase_price = Math.ceil(mrp - (mrp * (commision / 100)));
        $('#purchase_price_modal').val(purchase_price);
        retail_sell_price_calculate();
    }
    function retail_sell_price_calculate()
    {
        var mrp = Number($('#mrp_modal').val());
        var retail_sell_load = Number($('#retail_sell_load_modal').val());
        var retail_sell_price = Math.ceil(mrp + (mrp * (retail_sell_load / 100)));
        $('#retail_sell_price_modal').val(retail_sell_price);
    }
</script>

<div class="modal-content" style="width: 100%; ">
    <!-- Modal Header -->
    <div class="modal-header">
        <h3 style="text-align: center">Edit Product</h3>	
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <!-- Modal body -->
    <div class="modal-body" style="width: 40%;">
        <div class="container">
            <div class="row">
                <?php
                $product = $this->db->where('product_id', $product_id)->get('product')->row();
                $product_category = $this->db->where('product_category_id', $product->product_category_id)->get('product_category')->row();
                $model = $this->db->where('model_id', $product->model_id)->get('model')->row();
                $color = $this->db->where('color_id', $product->color_id)->get('color')->row();
                ?>
                <form method="post" class="form-horizontal"  class="" action="<?php echo site_url('ProductController/product_edit_save') ?>">
                    <input type="hidden"  value="<?php echo $product->product_id; ?>" class="form-control"  id="product_id"  name="product_id">

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="name">Category</label>
                        <div class="col-sm-6">
                            <select type="text" required="" class="form-control" onchange="model_load(this.value)" id="product_category_id_modal" name="product_category_id">
                                <option value="<?php echo $product_category->product_category_id; ?>"><?php echo $product_category->category_name; ?></option> 
                                <?php
                                $category = $this->db->select('*')->get('product_category')->result();
                                foreach ($category as $value) {
                                    ?>
                                    <option value="<?php echo $value->product_category_id; ?>"><?php echo $value->category_name; ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Model</label>
                        <div class="col-sm-6">          
                            <select type="text" required=""  onchange="color_load()"  class="form-control" id="model_id_modal" placeholder="Enter Model" name="model_id">
                                <option value="<?php echo $model->model_id; ?>"><?php echo $model->model_name; ?></option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Color</label>
                        <div class="col-sm-6">          
                            <select type="text" required=""    class="form-control" id="color_id_modal" placeholder="Enter Color" name="color_id">
                                <option value="<?php echo $color->color_id; ?>"><?php echo $color->color_name; ?></option>

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">MRP</label>
                        <div class="col-sm-6">          
                            <input type="text" required="" value="<?php echo $product->mrp; ?>" class="form-control" oninput="purchase_price_calculate()" id="mrp_modal" placeholder="Enter MRP" name="mrp">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Commision(%)</label>
                        <div class="col-sm-6">          
                            <input type="text" required="" value="<?php echo $product->commision; ?>" class="form-control"  id="commision_modal" oninput="purchase_price_calculate()" placeholder="Enter Commision" name="commision">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Purchase Price</label>
                        <div class="col-sm-6">          
                            <input type="text" required="" value="<?php echo $product->purchase_price; ?>" class="form-control" id="purchase_price_modal" placeholder="Enter Purchase Price" name="purchase_price">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Retail Sell Load(%):</label>
                        <div class="col-sm-6">  
                            <?php
                            $retail_load = $this->db->order_by('retail_load_id', 'desc')->limit('1')->select('*')->get('retail_load')->row();
                            ?>
                            <input type="text" required="" value="<?php echo $product->retail_sell_load; ?>" class="form-control" value="<?php echo $retail_load->load_amount; ?>"  id="retail_sell_load_modal" placeholder="Enter Retail Sell Load" name="retail_sell_load">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Retail Sell Price:</label>
                        <div class="col-sm-6">          
                            <input type="text" required="" value="<?php echo $product->retail_sell_price; ?>" class="form-control"  id="retail_sell_price_modal" placeholder="Enter Retail Sell Price" name="retail_sell_price">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Stock:</label>
                        <div class="col-sm-6">          
                            <input type="text" required="" value="<?php echo $product->stock; ?>" class="form-control"  id="stock" placeholder="Enter Stock" name="stock">
                        </div>
                    </div>
                    <div id="all_colors_stock">
                    </div>
                    <div class="form-group">        
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>

</div>


