<script>
    function product_stock_load(product_category_id)
    {
        $('#img').show();
        // document.getElementById(product_category_id).style.backgroundColor = "#ff0000";

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("stock_list").innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/product_stock_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id);

    }
</script>
<div class="row">
   
</div>
<div class="row">

    <div class="left" style="width:50%;float: left">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 style="text-align: center;color: white">Product Category</h2>
            </div>
            <div class="panel-body">
                <div style="width:50%;float:left">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td>Sl</td>
                            <td>Category Name</td>
                        
                        </tr>
                        <?php
                        $category = $this->db->select('*')->get('product_category');
                        $sl = 1;
                        $total_half = ceil($category->num_rows() / 2);

                        $category = $this->db->order_by('product_category_id')->limit($total_half)->select('*')->get('product_category')->result();
                        foreach ($category as $value) {
                            $product_stock = $this->db
                                    ->select_sum('stock', 'stock')
                                    ->where('product_category_id', $value->product_category_id)
                                    ->where('is_deleted', '0')
                                    ->get('product')
                                    ->result();
                            ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><a style="text-decoration: none;color: black" onclick="product_stock_load(this.id)" id="<?php echo $value->product_category_id ?>" href="#"><?php echo $value->category_name; ?>-(<?php echo $product_stock[0]->stock ?>)</a></td>
                                
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <div style="width:50%;float:left">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td>Sl</td>
                            <td>Category Name</td>
                          
                        </tr>
                        <?php
                        $category = $this->db->select('*')->get('product_category');

                        $total_half = ceil($category->num_rows() / 2);
                        $category = $this->db->select('*')->limit($total_half + 1, $total_half * 1 + 1)->get('product_category')->result();
                        foreach ($category as $value) {
                            $product_stock = $this->db
                                    ->select_sum('stock', 'stock')
                                    ->where('product_category_id', $value->product_category_id)
                                    ->where('is_deleted', '0')
                                    ->get('product')
                                    ->result();
                            ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><a style="text-decoration: none;color: black" onclick="product_stock_load(this.id)" id="<?php echo $value->product_category_id ?>" href="#"><?php echo $value->category_name; ?>-( <?php echo $product_stock[0]->stock ?>)</a></td>
                               
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>

            </div>

        </div>
    </div>

    <div class="right" style="width:50%;float: left">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 style="text-align: center;color:white">Product Stock</h2>
                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none;float: right"/>
            </div>
            <div class="panel-body">
 
                <div id="stock_list">

                </div>
            </div>
        </div>
    </div>
</div>
