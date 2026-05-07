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
    function load_color_row() {
        var id = document.getElementById("idControl").value * 1;


        document.getElementById("idControl").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('color_table').appendChild(newdiv);


            }
        }

        xhttp.open("POST", "<?php echo site_url('ProductController/load_color_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }
    function SomeDeleteRowFunction(btndel) {
        if (typeof (btndel) == "object") {
            $(btndel).closest("tr").remove();
        } else {
            return false;
        }
    }
</script>
<div class="container" style=" background-color: white">
    <div class="panel panel-primary" style="width: 70%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Add Color</h3>
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
            <form class="form-horizontal" method="post" action="<?php echo site_url('ProductController/add_color_data_save') ?>" enctype='multipart/form-data'>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="name">Model</label>
                    <div class="col-sm-10">
                        <select type="text" required="" class="form-control"  id="model_id" name="model_id">
                            <option selected="" disabled="">Select Model</option> 
                            <?php
                            $model = $this->db->select('*')->get('model')->result();
                            foreach ($model as $value) {
                                ?>
                                <option value="<?php echo $value->model_id; ?>"><?php echo $value->model_name; ?></option>
                                <?php
                            }
                            ?>

                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd">Color Name</label>
                    <div class="col-sm-10">          
                        <table id="color_table" style="margin-left:10;margin-right:30px;   ">
                            <input type="hidden"   id="idControl" value="1">
                            <input type="hidden"   id="current_id" value="1">
                            <tr id="1">

                                <td><input type="text"   id="color_1" class="form-control" size="131" name="color_name[]" placeholder="Enter Color Name 1"  ></td>
                                <td><input type="button" onclick="load_color_row()" style="width:50px" readonly id="add_more_0" title="Click To Add" value="+"  ></td>

                            </tr>


                        </table>
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