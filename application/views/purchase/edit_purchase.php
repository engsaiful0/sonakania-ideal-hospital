
<script type="text/javascript">
    function resetFn()
    {

        window.location.href = "http://192.168.0.102/maadrug/product/purchase_product";
    }
    $(document).ready(function () {

        $("#drug_add0").select2();
        $("#supplier").select2();
        $("#paymenttype").select2();
        $("#type_name_0").select2();

    });
    $(document).keypress(function (e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid > 0)
            {
                $("#submit").click();
            } else if (paid == 0)
            {
                $("#add_more").click();
            }

        }

    });
    function dueCal()
    {
        var due = $("#due").val() * 1;
        var total = $("#total").val() * 1;
        var paid = $("#paid").val() * 1;
        //$("#due").val(total - paid);

        if (paid > total)
        {
            alert('Paid amount can not be greater thn total amount');
            $('#paid').val(0);
            $('#due').val(0);
        } else
        {
            var due = total - paid;
            $("#due").val(due.toFixed(2));
        }
    }

    function purchase_rate(element, e)
    {
        var seq = $(element).attr('sequence');

        var box_rate = ($("#boxrate" + seq).val());
        var boxqty = ($("#boxqty" + seq).val());
        var pbq = ($("#pbq" + seq).val());
        var vat = ($("#vat" + seq).val());
        var discount = ($("#discount" + seq).val());
        if (!vat)
            vat = 0;
        if (!discount)
            discount = 0;
        var pur_rate = box_rate / pbq;

        var box_rate_af_dis = box_rate - ((discount * box_rate) / 100);

        pur_rate = (box_rate_af_dis + ((vat * box_rate_af_dis) / 100)) / pbq;
        pur_rate = pur_rate.toFixed(2);
        $("#pur_rate" + seq).val(pur_rate);
    }
    function getamount(element, e)
    {

        var seq = $(element).attr('sequence');

        var box_rate = $("#boxrate" + seq).val();
        var pbq = $("#pbq" + seq).val();
        var boxqty = $("#boxqty" + seq).val();
        var vat = 0;
        var discount = $("#discount" + seq).val();
        var total_qty = boxqty;
//        alert(total_qty);
        var discount = (boxqty * box_rate * (discount / 100));
        var amount = total_qty * box_rate;


//        var amount = boxqty * box_rate;

//        var amount_discount = (amount * discount) / 100;

//        var amount_after_discount=amount-amount_discount;
        var amount_vat = (box_rate * boxqty) * (vat / 100);




        amount = amount + amount_vat - discount;
        amount = amount.toFixed(2);
        $("#amount" + seq).val(amount);
        totalamount();
    }
    function getqty(element, e)
    {
        var seq = $(element).attr('sequence');

        var boxqty = $("#boxqty" + seq).val();
        var pbq = $("#pbq" + seq).val();
        var totalqty = boxqty * pbq;
        $("#qty" + seq).val(totalqty);
    }
    function details(element, e)
    {
        var seq = $(element).attr('sequence');
        var drug = $("#drug_add" + seq).val();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('PurchaseController/details') ?>",
            data: "drug=" + drug,
            dataType: "json",
            success: function (msg)
            {
                $("#stock" + seq).val(msg.stock);
                $("#wsr" + seq).val(msg.wsr);
                $("#mrp" + seq).val(msg.mrp);
                $("#pur_rate" + seq).val(msg.pur_rate);
            }
        });
    }
    function SomeDeleteRowFunction(btndel) {

        if (typeof (btndel) == "object") {
            $(btndel).closest("tr").remove();

        } else {
            return false;
        }
        totalamount();
    }
    function totalamount()
    {
        var id = $('#id_control').val();
        //  alert(id);
        var grand_total = 0;

        for (var i = 1; i <= Number(id); i++)
        {

            // alert($('#amount' + i).val());
            if ($("#amount" + i).length)
            {
                if (!isNaN($('#amount' + i).val()))
                {
                    grand_total += Number($('#amount' + i).val());
                }
            }
            // alert(grand_total);
        }
        $('#total').val(grand_total);

        /*Discount Calculation start*/
//        var discount = $('#discount').val().split("%");
//        var grand_total = Math.ceil(grand_total - (grand_total * (Number(discount[0]) / 100)));
//        /*Discount Calculation end*/
//        $('#net_total').val(grand_total);
    }

</script>

<div class="panel panel-primary" style="width: 100%;margin: 0 auto">
    <div class="panel panel-heading">

        <h3 style="text-align: center">Edit Purchase</h3>



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
        //     echo '<pre>';
        //     print_r($purchase_id);
        //   die;
        ?>
        <form action="<?php echo site_url('PurchaseController/edit_purchase_save'); ?>" method="POST" class="form">
            <input name="purchase_id" type="hidden" value="<?php echo $purchase_id ?>">
            <script type="text/javascript">
                function supplier_set()
                {
                    $('#img').show();
                    var supplier = document.getElementById('supplier').value;
                    // alert(supplier);

                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (xhttp.readyState == 4 && xhttp.status == 200) {


                            document.getElementById("drug_add0").innerHTML = xhttp.responseText;
                            // alert(xhttp.responseText);
                            $('#img').hide();

                        }
                    }

                    xhttp.open("POST", "<?php echo site_url('PurchaseController/supplierget'); ?>", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    //                                    xhttp.send("fname=Henry&lname=Ford");
                    xhttp.send("supplier=" + supplier);
                }
                function addMore()
                {

                    $('#img').show();
                    var supplier = document.getElementById('supplier').value;
                    // alert(supplier);
                    if (supplier == '')
                    {
                        alert("Select Supplier First");
                    } else
                    {
                        var id_control = document.getElementById('id_control').value * 1;
                        var next_id = id_control + 1;
                        // alert(next_id);


                        document.getElementById('id_control').value = next_id;
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (xhttp.readyState == 4 && xhttp.status == 200) {
                                var newdiv = document.createElement('tr');
                                newdiv.innerHTML = xhttp.responseText;
                                document.getElementById('purchase_table').appendChild(newdiv);
                                $("#drug_add" + next_id).select2();
                                $("#type_name_" + next_id).select2();
                                $(".date" + next_id).datepicker(
                                        {
                                            "format": "dd-mm-yyyy"
                                        });

                                $('#img').hide();
                            }
                        }

                        xhttp.open("POST", "<?php echo site_url('PurchaseController/addMorePurchase_Edit'); ?>", true);
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        //                                    xhttp.send("fname=Henry&lname=Ford");
                        xhttp.send('next_id=' + next_id + '&supplier=' + supplier);
                    }
                }

                function drug_name_load(type_name)
                {
                    $('#img').show();
                    var xhttp = new XMLHttpRequest();

                    var data = type_name.split("_");
                    var type_name_val = document.getElementById(type_name).value;
                    var supplier = document.getElementById('supplier').value;
                    xhttp.onreadystatechange = function () {
                        if (xhttp.readyState == 4 && xhttp.status == 200) {

                            document.getElementById("drug_add" + data[2]).innerHTML = xhttp.responseText;
                            //alert(xhttp.responseText);

                            $("#type_name_" + data[2]).select2();
                            //   $("#type_name_" + data[2]).select2();
                            $('#img').hide();
                        }
                    }

                    xhttp.open("POST", "<?php echo site_url('PurchaseController/add_drug_name_purchase'); ?>", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                    xhttp.send("type_name_val=" + type_name_val + "&supplier=" + supplier);
                }

                $(document).ready(function () {
                    var id_control = $("#id_control").val();
                    for (var i = 1; i <= id_control; i++)
                    {
                        $("#type_name_" + i).select2();
                        $("#drug_add" + i).select2();

                    }


                    $("#customer_type").select2();
                    $("#customer_id").select2();
                    $("#supplier").select2();
                });</script>
            <table width="100%" border="1">
                <tr>
                    <td width="80px;" align="right">
                        <b>MRR No:</b>
                    </td>
                    <td align="left" style="padding:5px;width: 400px;">
                        <input type="text" id="mrr" name="mrr" class="form-control" readonly="" 
                               value="<?php echo $purchase->mrr ?>"/>
                    </td>
                    <td width="80px;" align="left">
                        <b>MRR Date:</b>
                    </td>
                    <td align="left" style="padding:5px;">
                        <input type="text"  value="<?php echo date('d-m-Y', strtotime($purchase->mrr_date)) ?>" id="mrr_date" name="mrr_date" class="form-control" readonly=""/>
                    </td>
                </tr>
                <tr>
                    <td width="80px;">
                        <b>Invoice No:</b>
                    </td>
                    <td align="left" style="padding:5px;">
                        <input type="text"  value="<?php echo $purchase->invoice; ?>" id="invoice" name="invoice" placeholder="Invoice No" class="form-control" required=""/>
                    </td>
                    <td>
                        <b>Invoice&nbsp;Date:&nbsp;</b>
                    </td>
                    <td align="left" style="padding:5px;">
                        <input type="text"  value="<?php echo date('d-m-Y', strtotime($purchase->invoice_date)) ?>" id="invoice_date" name="invoice_date" placeholder="Invoice Date" class="form-control date" required=""/>
                    </td>
                </tr>
                <tr>
                    <td width="80px;">
                        <b>Supplier:</b>
                    </td>
                    <td align="left" style="padding:5px;">
                        <select name="supplier" onchange="supplier_set()" id="supplier" class="form-control" required="">

                            <?php
                            $supplier = $this->db->where('supplier_id', $purchase->supplier)->get('supplier')->row();
                            ?>
                            <option value="<?php echo $supplier->supplier_id ?>"><?php echo $supplier->name ?></option>
                            <?php
                            $company_id = $this->session->userdata('comapny_id');
                            $condition = array(
                                "company_id" => $company_id
                            );
                            echo make_select('supplier', 'supplier_id', 'name', $condition);
                            ?>
                        </select>
                    </td>
                    <td colspan=""><b>Type</b></td>
                    <td align="left" style="padding:5px;">
                        <select name="pur_type" class="form-control input" id="pur_type">
                            <option value="purchase">Purchase</option>

                        </select>
                    </td>
                </tr>
            </table>

            <table width="100%" border="0" class="table table-responsive table-hover "   id="purchase_table">
                <tr>
                    <th style="width:100px;text-align: center;">
                        Type
                    </th>
                    <th style="width:185px;text-align: center;">
                        Drug

                    </th>
                    <th style="width:55px;text-align: center;">
                        <p>Box<br>Qty</p>
                    </th>
                    <th style="width:70px;text-align: center;display:none">
                        Re<br>Qty
                    </th>
                    <th style="text-align: center;width: 78px">
                        PBQ
                    </th>
                    <th style="width:60px;text-align: center;">
                        TP<br>Rate
                    </th>

                    <th style="width:45px;text-align: center;">
                        <p>  Dis<br>(%)</p>
                    </th>


                    <th style="text-align: center;width: 70px">
                        Pur<br>Rate
                    </th>
                    <th style="text-align: center;">
                        MRP
                    </th>
                    <th style="text-align: center;display:none;">
                        WSR
                    </th>
                    <th style="text-align: center;">
                        Qty
                    </th>
                    <th style="text-align: center;">
                        Amount
                    </th>
                    <th style="text-align: center;">

                    </th>
                </tr>
                <?php
                $purchase_details = $this->db->where('purchase_id', $purchase_id)->get('purchase_details')->result();
                $id = 1;
                ?>

                <input type="hidden"  value="<?php echo count($purchase_details) ?>"  name="id_control"  id="id_control" class="form-control" >
                <?php
                foreach ($purchase_details as $purchase_details_value) {
                    $purchase_details_id = $purchase_details_value->purchase_details_id;
                    $drug = $this->db
                            ->where('drug_id', $purchase_details_value->drug)
                            ->get('drug')
                            ->row();
                    $drug_type = $this->db
                            ->where('drug_type_id', $purchase_details_value->type)
                            ->get('drug_type')
                            ->row();
                    ?>
                    <tr>
                        <td>
                            <select name="type_name[]" class="form-control" id="type_name_<?php echo $id ?>"  onchange="drug_name_load(this.id)" required="" style="width:110px;" sequence=<?php echo $id ?>>
                                <option value="<?php echo $drug_type->drug_type_id ?>"><?php echo $drug_type->type_name ?></option>
                                <?php
                                $sql = $this->db->select('*')->get('drug_type')->result();

                                foreach ($sql as $value) {
                                    ?>
                                    <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>

                            <select name="drug[]"  class="form-control" id="drug_add<?php echo $id ?>"  onchange="details(this, event);" required="" style="width:160px;" sequence=<?php echo $id ?>>
                                <option value="<?php echo $drug->drug_id ?>"><?php echo $drug->drug_name ?></option>
                            </select>
                        </td>
                        <td >
                            <input type="text"  value="<?php echo $purchase_details_value->boxqty ?>" id="boxqty<?php echo $id ?>" name="boxqty[]"  class="form-control"  onkeyup="getamount(this, event), getqty(this, event)" required="" sequence=<?php echo $id ?>>
                        </td>

                        <td>
                            <input type="text"  value="<?php echo $purchase_details_value->pbq ?>" id="pbq<?php echo $id ?>" name="pbq[]" class="form-control" sequence=<?php echo $id ?> onkeyup="getamount(this, event), purchase_rate(this, event), getqty(this, event)" required="">
                        </td>
                        <td>
                            <input type="text"  value="<?php echo $purchase_details_value->boxrate ?>" id="boxrate<?php echo $id ?>" name="boxrate[]" class="form-control" sequence=<?php echo $id ?> onkeyup="purchase_rate(this, event), getamount(this, event)" required="">
                        </td>

                        <td >
                            <input type="text"  value="<?php echo $purchase_details_value->discount ?>" id="discount<?php echo $id ?>" name="discount[]" style="width:40px;" class="form-control" sequence=<?php echo $id ?> onkeyup="purchase_rate(this, event), getamount(this, event)">
                        </td>


                        <td >
                            <input type="text"  value="<?php echo $purchase_details_value->pur_rate ?>" id="pur_rate<?php echo $id ?>" name="pur_rate[]" class="form-control"  sequence=<?php echo $id ?> required="">
                        </td>
                        <td>
                            <input type="text"  value="<?php echo $purchase_details_value->mrp ?>" id="mrp<?php echo $id ?>" name="mrp[]" class="form-control" sequence=<?php echo $id ?> >
                        </td>
                        <td>
                            <input type="hidden"  value="<?php echo $purchase_details_value->qty ?>" id="qty_edit<?php echo $id ?>" name="qty_edit[]" class="form-control" readonly="" sequence=<?php echo $id ?>>
                            <input type="text"  value="<?php echo $purchase_details_value->qty ?>" id="qty<?php echo $id ?>" name="qty[]" class="form-control" readonly="" sequence=<?php echo $id ?>>
                        </td>

                        <td >
                            <input type="text"  value="<?php echo $purchase_details_value->amount ?>" id="amount<?php echo $id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $id ?> required="">
                        </td>

                        <?php
                        if ($id == 1) {
                            ?>

                            <td>  <input type="button" onclick="addMore()" type="button" id="add_more" style="width:50px"  id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="+"  ></td>
                            <?php
                        } else {
                            ?>
                            <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="-"  >

                                <?php
                            }
                            ?>
                    </tr>
                    <?php
                    $id++;
                }
                ?>


            </table>
            <div  class="row">
                <div class="col-md-6">
                    <img src="<?php echo base_url() ?>images/ajax-loader.gif" id="img" style="display:none"/>
                </div>
                <div class="col-md-6">
                    <table style="width: 100%;" class="table">
                        <tr>
                            <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                <b>Total:&nbsp;</b>
                                 <input type="hidden" name="total_edit" value="<?php echo $purchase->due ?>" id="total_edit">
                                <input type="text" name="total" value="<?php echo $purchase->total ?>" id="total" readonly="" class="form-control input input-lg" style="width:200px;float: right;">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                <b>Payment Type:&nbsp;</b>
                                <select type="text" name="paymenttype" id="paymenttype" class="form-control input input-lg" style="width:200px;float: right;">
                                    <option><?php echo $purchase->payment_type ?></option>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                <b>Paid:&nbsp;</b>
                                <input type="hidden" value="<?php echo $purchase->paid ?>" name="paid_edit" required=""  id="paid_edit" >
                                <input type="text" value="<?php echo $purchase->paid ?>" name="paid" required=""  id="paid" class="form-control input input-lg" style="width:200px;float: right;">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" align="right" valign="middle" style="padding:5px;">
                                <b>Due:&nbsp;</b>
                                <input type="hidden" name="due_edit" value="<?php echo $purchase->due ?>" id="due_edit">
                                <input type="text" name="due" value="<?php echo $purchase->due ?>" id="due" readonly="" class="form-control input input-lg" style="width:200px;float: right;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="padding:5px;">
                                <button type="submit" id="submit" class="btn btn-primary">Update Purchase</button>&nbsp;

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
