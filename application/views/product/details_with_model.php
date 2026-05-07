
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
    function color_load()
    {
        var model_id = $('#model_id').val();

        //alert(id_no);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("color_id").innerHTML = xhttp.responseText;
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('ProductController/color_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("model_id=" + model_id);
    }
</script>
<div class="container" style="width: 100%">
    <div class="row">

        <div class="panel panel-primary">
            <?php
            error_reporting(0);
            if ($this->session->userdata('success') != '') {
                ?>
                <div class="alert alert-success">
                    <strong>Success!</strong>Data has been delete successfully.
                </div>
                <?php
                $sdata['success'] = '';
                $this->session->set_userdata($sdata);
            }
             if ($this->session->userdata('update') != '') {
                ?>
                <div class="alert alert-success">
                    <strong>Success!</strong>Data has been updated successfully.
                </div>
                <?php
                $sdata['update'] = '';
                $this->session->set_userdata($sdata);
            }
            ?>
            <form method="post" action="<?php echo site_url('ProductController/details_with_category_model_color') ?>">
                <table>
                    <tr>
                        <td>Category</td>
                        <td>Model</td>
                        <td>Color</td>
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
                            <select type="text" onchange="color_load()"  class="form-control" id="model_id"  name="model_id">
                                <option selected="" disabled=""> Model</option>

                            </select>
                        </td>
                        <td style="width: 300px;">
                            <select type="text"  class="form-control" id="color_id"  name="color_id">
                                <option selected="" disabled=""> Color</option>

                            </select>
                        </td>
                        <td><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>
                </table>
            </form>

            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td style="width:">#</td>
                    <td style="width:35%">Category</td>
                    <td style="width:">Model</td>
                    <td style="width:">Color</td>
                    <td style="width:">MRP</td>
                    <td style="width:">Price</td>
                    <td style="width:">Stock</td>
                    <td style="width:">Edit</td>
                    <td style="width:">Delete</td>
                </tr>
                <?php
                $sl = 1;
                $product = '';
                if ($product_category_id != '' && $model_id != '' && $color_id != '') {
                    $product = $this->db
                                    ->where('product_category_id', $product_category_id)
                                    ->where('model_id', $model_id)
                                    ->where('color_id', $color_id)
                                    ->where('is_deleted', '0')
                                    ->get('product')->result();
                } else {
                    $product = $this->db
                                    ->where('model_id', $model_id)
                                    ->where('is_deleted', '0')
                                    ->get('product')->result();
                }







                foreach ($product as $product_value) {
                    //  echo '<pre>';
                    // print_r($product_value->product_id);
                    //   die;
                    $model = $this->db->where('model_id', $product_value->model_id)->get('model')->row();
                    $color = $this->db->where('color_id', $product_value->color_id)->get('color')->row();

                    $product_category = $this->db->where('product_category_id', $product_value->product_category_id)->get('product_category')->row();
                    ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $product_category->category_name ?></td>
                        <td><?php echo $model->model_name ?></td>
                        <td><?php echo $color->color_name ?></td>
                        <td><?php echo $product_value->mrp ?></td>
                        <td><?php echo $product_value->purchase_price ?></td>
                        <td><?php echo $product_value->stock ?></td>
                        <td>
                            <a href="" id="product_edit_<?php echo $product_value->product_id ?>" onclick="modalLoadEdit(this.id)" class="btn btn-success" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update">Edit</a>


                        </td>
                        <td>
                            <form method="post" action="<?php echo site_url("ProductController/delete_product") ?>">
                                <input name="product_id" type="hidden" value="<?php echo $product_value->product_id ?>">
                                <input class="btn btn-primary" name="delete" onclick="return confirm('Do you want to delete?')" value="Delete" id="delete" type="submit">
                            </form>
                        </td>

                    </tr>
                    <?php
                }
                ?>

            </table>  


        </div>
    </div>
</div>
<div class="modal"  id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
    function modalLoadEdit(rowId) {

        var data = rowId.split('_'); //To get the row id

        //alert(data[2]);

        $.ajax({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
            url: "<?php echo site_url('ProductController/product_edit') ?>" + '/' + data[2],
            type: 'GET',
            cache: false,
            data: {}, //see the $_token
            datatype: 'html',
            beforeSend: function () {
            },
            success: function (data) {

                // alert(data.length);
                //                    $('.modal-content').html(data);
                if (data.length > 0) {
                    // remove modal body
                    $('.modal-body').remove();
                    // add modal content
                    $('.modal-content').html(data);
                } else {
                    // add modal content
                    $('.modal-content').html('info');
                }
            }
        });
    }
</script>