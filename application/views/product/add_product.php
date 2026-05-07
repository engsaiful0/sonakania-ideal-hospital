<script type="text/javascript">
    function model_load(product_category_id)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("model_id").innerHTML = xhttp.responseText;

            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/model_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id);

    }

    function all_colors_stock_load()
    {
        var product_category_id = Number($('#product_category_id').val());
        var model_id = Number($('#model_id').val());
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("all_colors_stock").innerHTML = xhttp.responseText;

            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/all_colors_stock_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id + "&model_id=" + model_id);
    }
    function comission_load()
    {
        var product_category_id = Number($('#product_category_id').val());
     
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("commision").value = xhttp.responseText;

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
        $('#product_category_id').select2();
        $('#model_id').select2();

    });
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
</script>
<div class="container" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Product</h3>
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
            ?>
            <form class="form-horizontal" method="post" action="<?php echo site_url('ProductController/add_data_save') ?>" enctype='multipart/form-data'>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="name">Category</label>
                    <div class="col-sm-10">
                        <select type="text" required="" class="form-control" onchange="model_load(this.value), comission_load(this.value)" id="product_category_id" name="product_category_id">
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
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Model</label>
                    <div class="col-sm-10">          
                        <select type="text" required=""  onchange="all_colors_stock_load()"  class="form-control" id="model_id" placeholder="Enter Model" name="model_id">
                            <option selected="" disabled="">Select Model</option>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">MRP</label>
                    <div class="col-sm-10">          
                        <input type="text" required="" class="form-control" oninput="purchase_price_calculate()" id="mrp" placeholder="Enter MRP" name="mrp">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Commision(%)</label>
                    <div class="col-sm-10">          
                        <input type="text" required="" class="form-control" readonly="" id="commision" oninput="purchase_price_calculate()" placeholder="Enter Commision" name="commision">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Purchase Price</label>
                    <div class="col-sm-10">          
                        <input type="text" required="" class="form-control" readonly="" id="purchase_price" placeholder="Enter Purchase Price" name="purchase_price">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Retail Sell Load(%):</label>
                    <div class="col-sm-10">  
                        <?php
                        $retail_load = $this->db->order_by('retail_load_id', 'desc')->limit('1')->select('*')->get('retail_load')->row();
                        ?>
                        <input type="text" required="" class="form-control" value="<?php echo $retail_load->load_amount; ?>" readonly="" id="retail_sell_load" placeholder="Enter Retail Sell Load" name="retail_sell_load">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Retail Sell Price:</label>
                    <div class="col-sm-10">          
                        <input type="text" required="" class="form-control" readonly="" id="retail_sell_price" placeholder="Enter Retail Sell Price" name="retail_sell_price">
                    </div>
                </div>

                <div id="all_colors_stock">
                </div>
                <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>