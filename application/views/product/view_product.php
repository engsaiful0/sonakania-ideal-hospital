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
<div class="container" style="width: 100%">
    <div class="row">

        <div class="panel panel-primary">
            <form method="post" action="<?php echo site_url('ProductController/view_product') ?>">
                <table>
                    <tr>
                        <td>Category</td>
                        <td>Model</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td style="width: 300px;">
                            <select type="text" required=""  class="form-control" onchange="model_load(this.value)" id="product_category_id" name="product_category_id">
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

                        <td style="width: 300px;">
                            <select type="text"    class="form-control" id="model_id"  name="model_id">
                                <option selected="" disabled=""> Model</option>

                            </select>
                        </td>
                        <td><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>
                </table>
            </form>

            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Category</td>
                    <td>Model</td>
                    <td>MRP</td>
                    <td>Price</td>
                    <td>Stock</td>
                    <td>Color Stock</td>
                    <td>Details</td>
                </tr>
                <?php
                //print_r( count($detailsList));
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $category = $this->db->where('product_category_id', $detailsList[$i]->product_category_id)->get('product_category')->row();

                    if ($model_id != '') {
                        $model = $this->db->where('model_id', $model_id)->get('model')->result();
                    } else {
                        $model = $this->db->where('product_category_id', $category->product_category_id)->get('model')->result();
                    }
                    //   print_r($category);

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
                        // print_r($product_stock);
                        foreach ($product_stock as $value) {
                            $mrp = $value->mrp;
                            $purchase_price = $value->purchase_price;
                            $stock += $value->stock;
                        }
                        ?>
                        <tr>
                            <td><?php echo $sl++ ?></td>
                            <td><?php echo $category->category_name ?></td>
                            <td><?php echo $model_value->model_name ?></td>
                            <td><?php echo $mrp ?></td>
                            <td><?php echo $purchase_price ?></td>
                            <td><?php echo $stock ?></td>
                            <td>
                                <a data-target="#globalModalDetails_<?php echo $model_value->model_id ?>"  data-toggle="modal" data-placement="top" data-content="update" class="btn btn-primary" >Color Stock</a>

                                <div class="modal fade" id="globalModalDetails_<?php echo $model_value->model_id ?>" role="dialog" >
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content" style="width: 800px;">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Color Stock</h4>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                error_reporting(0);
                                                $product = $this->db->where('model_id', $model_value->model_id)->get('product')->result();
                                                // var_dump($model_value->model_id);
                                                foreach ($product as $product_value) {
                                                    $color = $this->db->where('color_id', $product_value->color_id)->get('color')->row();
                                                    ?>
                                                    <a style="margin:0px;" class="btn btn-success col-md-2" href="<?php echo site_url("ProductController/DetailsWithColor/$product_value->product_id") ?>"><?php echo $color->color_name . '-' . $product_value->stock ?></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div style="clear: left " class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </td>
                            <td>
                                <a class="btn btn-success" href="<?php echo site_url("ProductController/DetailsWithModel/$model_value->model_id") ?>">Details</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>

            </table>  


        </div>
    </div>
</div>	
<div class="container" style="width: 100%">
    <div class="row" style="list-style: none ">

        <?php echo $pagination; ?>



    </div>

</div>
