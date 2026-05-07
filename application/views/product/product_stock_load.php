<style>
    .dropbtn {

        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #ddd;}

    .dropdown:hover .dropdown-content {display: block;}

    .dropdown:hover .dropbtn {background-color: #3e8e41;}
</style>


<script>
    $(document).ready(function () {
// alert();
        $('#product_category_id').select2();
        $('#model_id').select2();
        $('#color_id').select2();
        $('#retail_customer_id').select2();
    });
    function model_load(product_category_id)
    {


        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("model_id").innerHTML = xhttp.responseText;
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/model_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("product_category_id=" + product_category_id);
    }
</script>
<div class="container" style="width: 100%;color:black">
    <div class="row">

        <div class="panel panel-primary">


            <table style="color:black" class="table table-hover table-bordered table-condensed">
                <?php
                $category = $this->db->where('product_category_id', $product_category_id)
                                ->get('product_category')->row();
                ?>
                <tr>
                    <td colspan="5" style="text-align:center;font-size:25px;color:black "><?php echo $category->category_name ?></td>



                </tr>
                <tr>
                    <td>#</td>

                    <td>Model</td>
                    <td>P.Price</td>
                    <td>MRP</td>

                    <td>R.Price</td>
                    <td>Stock</td>

                </tr>
                <?php
                $sl = 1;

                $category = $this->db->where('product_category_id', $product_category_id)
                                ->get('product_category')->row();
                $model = $this->db->where('product_category_id', $category->product_category_id)
                                ->get('model')->result();

                //   print_r($category);
                $grand_stock = 0;

                foreach ($model as $model_value) {
                    $product_stock = $this->db
                            ->order_by('model_id', 'asc')
                            ->where('model_id', $model_value->model_id)
                            ->where('is_deleted', '0')
                            ->get('product')
                            ->result();
                    $mrp = 0;
                    $purchase_price = 0;
                    $stock = 0;
                    $retail_sell_price = 0;
                    // print_r($product_stock);
                    foreach ($product_stock as $value) {
                        $mrp = $value->mrp;
                        $purchase_price = $value->purchase_price;
                        $retail_sell_price = $value->retail_sell_price;
                        $stock += $value->stock;
                    }
                    $grand_stock += $stock;
                    ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>

                        <td><?php echo $model_value->model_name ?></td>
                        <td><?php echo $purchase_price ?></td>
                        <td><?php echo $mrp ?></td>
                        <td><?php echo $retail_sell_price ?></td>

                        <td><?php echo $stock ?></td>

                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="5" style="text-align:right">Total Stock</td>
                    <td  style=""><?php echo $grand_stock ?></td>



                </tr>
            </table>  


        </div>
    </div>
</div>	

