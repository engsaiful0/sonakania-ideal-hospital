<script type="text/javascript">
    var sequence = 0;

    function change_Cal() {
        var paid = $("#paid").val() * 1;
        var given_amount = $("#given_amount").val() * 1;
        var change_amount = given_amount - paid;
        $("#change_amount").val(change_amount.toFixed(2));
    }

    function dueCal() {
        var paid = $("#paid").val() * 1;
        var nettotal = $("#nettotal").val() * 1;
        if (paid > nettotal) {
            alert('Paid amount can not be greater thn total amount');
            $('#paid').val(0);
            $('#due_sale').val(0);
        } else {
            var due = nettotal - paid;
            $("#due_sale").val(due.toFixed(2));
        }
    }


    function removetr(element, e) {
        var seq = $(element).attr('sequence');
        if (seq != 0)
            $(element).parent().parent().remove();
        totalamount();
    }


    function getamount(element, e) {
        var seq = $(element).attr('sequence');
        var discount = $("#discounteach" + seq).val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var qty = $("#return_quantity" + seq).val();
            var sales_rate = $("#sales_rate" + seq).val();
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var amount = $("#amount" + seq).val();
            var discount = (qty * sales_rate) * (discountAmount / 100);
            var amount = (qty * sales_rate) - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            totalamount();
        } else {
            var qty = $("#return_quantity" + seq).val();
            var sales_rate = $("#sales_rate" + seq).val();
            var amount = qty * sales_rate - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            totalamount();
        }
    }



    function getdrugdetails(element, e) {

        var seq = $(element).attr('sequence');
        var drug = $("#drug_add" + seq).val();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('drug/details') ?>",
            data: "drug=" + drug,
            dataType: "json",
            success: function(msg) {
                var sales_rate = msg.mrp;
                var stock = msg.stock;
                var pur_rate = msg.pur_rate;
                var shelf = msg.shelf;
                var group_name = msg.group_name;
                var wsr = msg.wsr;
                $("#sales_rate" + seq).val(sales_rate);
                $("#stock" + seq).val(stock);
                $("#pur_rate" + seq).val(pur_rate);
                $("#self_no" + seq).val(shelf);
                $("#group_name" + seq).val(group_name);
                $("#wsr" + seq).val(wsr);
                $("#qty" + seq).focus();
            }
        });
    }

    function totalamount() {
        var amount = $(".amount");
        total = 0;
        total = Number(total);
        $.each(amount, function(k, elm) {
            var camount = $(elm).val();
            camount = Number(camount);
            total += camount;
        });
        $("#total").val(total.toFixed(2));
        nettotalamount();
    }

    function nettotalamount() {
        var total = $("#total").val();
        var discount = $("#discount").val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var discount = (total * discountAmount) / 100;
            var nettotal = total - discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#paid").val(nettotal.toFixed(2));
        } else {
            var nettotal = total - discount;
            $("#nettotal").val(nettotal.toFixed(2));
            $("#paid").val(nettotal.toFixed(2));
        }
    }
    $(function() {
        totalamount();
        $(".date").datepicker({
            "format": "dd-mm-yyyy"
        });
    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            //alert('enter key is pressed');
            var paid = $('#paid').val();
            // alert(paid);
            if (paid == '') {
                $("#add_more").click();
            } else {
                $("#submit").click();
            }
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var id_control = document.getElementById('id_control').value * 1;
        for (var index = 0; index < id_control; index++) {

        }
    });
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });

    $(document).ready(function() {

        // Validate the form
        $("#medicine_sale_form").validate({
            rules: {
                date: "required",
                drug_type_id_0: "required",

            },
            messages: {
                date: "Enter date",
                drug_type_id_0: "Please select a type name",

            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#medicine_sale_form').serialize();

            // Check if the form is valid
            if ($("#medicine_sale_form").valid()) {
                $('#medicine_sale_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MedicineSaleReturnController/medicine_sale_return_update_save'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#medicine_sale_form')[0].reset();
                            $('#medicine_sale_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-medicine-sale-return') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#medicine_sale_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#medicine_sale_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Update Medicine Sale Return</h3>
        </div>
        <div class="panel-body">

            <form id="medicine_sale_form" method="POST" class="form">
                <div class="container-fluid">
                    <div class="col-sm-12 col-md-12 main">
                        <?php
                        $uniqu_id = $this->db->select('*')->order_by('medicine_sale_return_invoice_id', 'DESC')->limit('1')->get('medicine_sale_return_invoices')->row();
                        $medicine_sale_return_invoice_no = 'MSR' . time() . '0' . (intval($uniqu_id->id_serial ?? 0) + 1);
                        ?>
                        <input type="hidden" readonly="" class="form-control"
                            value="<?php echo isset($uniqu_id) ? intval($uniqu_id->id_serial) + 1 : 1; ?>"
                            id="id_serial" name="id_serial">
                        <?php

                        $medicine_sale_return = $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->get('medicine_sale_return')->row();
                        $medicine_sale = $this->db->where('medicine_sale_id', $medicine_sale_return->medicine_sale_id)->get('medicine_sales')->row();
                        $medicine_sales_details = $this->db->where('medicine_sale_id', $medicine_sale_return->medicine_sale_id)->get('medicine_sales_details')->result();
                        $medicine_sale_id = $medicine_sale_return->medicine_sale_id;

                        $medicine_sale_return_details = $this->db->where('medicine_sale_return_id', $medicine_sale_return_id)->get('medicine_sale_return_details')->result();
                        ?>
                         <input type="hidden"  class="form-control" value="<?php echo $medicine_sale_return_id ?>" id="medicine_sale_return_id" name="medicine_sale_return_id">
                        <fieldset>
                            <legend>Customer Info</legend>
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td><b>Name</b></td>
                                    <td><?php echo $medicine_sale->name; ?></td>
                                    <td><b>Age</b></td>
                                    <td><?php echo $medicine_sale->age; ?></td>
                                    <td><b>Mobile</b></td>
                                    <td><?php echo $medicine_sale->mobile_number; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Address</b></td>
                                    <td><?php echo $medicine_sale->address; ?></td>
                                    <td><b>Invoice No</b></td>
                                    <td><?php echo $medicine_sale->medicine_sale_invoice_no; ?></td>
                                    <td><b>Date</b></td>
                                    <td><?php echo date('d-m-Y', strtotime($medicine_sale->bill_date)); ?></td>
                                </tr>

                            </table>
                        </fieldset>
                        <fieldset>
                            <legend>Return Info</legend>
                            <div class="row">
                                <input value="<?php echo $medicine_sale->medicine_sale_id ?>" type="hidden" id="medicine_sale_id " name="medicine_sale_id ">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Invoice No</label>
                                        <div class="col-sm-8">
                                            <input type="text" readonly="" class="form-control" value="<?php echo $medicine_sale_return_invoice_no ?>" id="medicine_sale_return_invoice_no" name="medicine_sale_return_invoice_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Remarks</label>
                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Enter Remarks" class="form-control" id="remarks" name="remarks">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Date</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="<?php echo date('d-m-Y') ?>" class="form-control" id="datepicker1" name="date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div style="clear: left;" class="table-responsive">
                            <table width="100%" border="1">
                                <tr>
                                    <input id="total_control" value="" type="hidden">
                                    <td colspan="4" style="padding:5px;">
                                        <table width="100%" cellpadding="4" border="0" id="cart_tab1">
                                            <tr>
                                              
                                                <th style="text-align: center;">
                                                    Medicine Name

                                                </th>
                                                <th id="sale_rate_title" style="text-align: center;">
                                                   MRP
                                                </th>

                                                <th style="text-align: center;">
                                                    Sale Quantity
                                                </th>
                                                <th style="text-align: center;">
                                                    Return Quantity
                                                </th>
                                                <th style="text-align: center;">
                                                    Discount(%)
                                                </th>
                                                <th style="text-align: center;">
                                                    Amount
                                                </th>

                                            </tr>

                                            <input type="hidden" value="<?php echo $medicine_sale_id ?>" name="medicine_sale_id" class="form-control">
                                            <input type="hidden" value="<?php echo count($medicine_sales_details) ?>" name="id_control" id="id_control" class="form-control">
                                            <?php
                                            $seq = 0;
                                            foreach ($medicine_sale_return_details as $value_de) {


                                              
                                                $drug_name = $this->db
                                                    ->where('drug_id', $value_de->drug_id)
                                                    ->get('drug')->row();
                                            ?>

                                                <tr>
                                                    <input type="hidden" value="0" name="id_control" id="id_control" class="form-control">

                                                   
                                                    <td style="padding:5px;">
                                                        <?php echo $drug_name->drug_name ?>
                                                        <input type="hidden" value="<?php echo $value_de->drug_id ?>" id="drug_id<?php echo $seq ?>" name="drug_id[]" class="form-control" required="" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <?php echo $value_de->sales_rate ?>
                                                        <input type="hidden" readonly value="<?php echo $value_de->sales_rate ?>" id="sales_rate<?php echo $seq ?>" name="sales_rate[]" class="form-control" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>

                                                    <td style="padding:5px;">
                                                        <?php echo $value_de->sale_quantity ?>
                                                        <input type="hidden" value="<?php echo $value_de->sale_quantity ?>" id="sale_quantity<?php echo $seq ?>" name="sale_quantity[]" class="form-control" onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <input oninput="validateIntegerInput(this)" type="text" id="return_quantity<?php echo $seq ?>" value="<?php echo $value_de->return_quantity ?>" name="return_quantity[]" class="form-control" onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <input type="text" value="<?php echo $value_de->discounteach ?>" id="discounteach<?php echo $seq ?>" name="discounteach[]" class="form-control" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <input type="text" id="amount<?php echo $seq ?>" value="<?php echo $value_de->amount ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $seq ?>>
                                                    </td>
                                                </tr>
                                            <?php
                                                $seq++;
                                            }
                                            ?>
                                        </table>

                                    </td>
                                </tr>

                            </table>
                            <table style="margin-top:5px;float:right">
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Return Total:&nbsp;</b>
                                        <input type="text" name="total" id="total" class="form-control" style="width:200px;float: right;" value="0" onkeyup="totalamount()">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Discount(%):&nbsp;</b>
                                        <input type="text" name="discount" id="discount" class="form-control" style="width:200px;float: right;" onkeyup="totalamount()">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Return Net Total:&nbsp;</b>
                                        <input type="text" readonly="" name="nettotal" id="nettotal" class="form-control" style="width:200px;float: right;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Paid:&nbsp;</b>
                                        <input type="text" readonly="" name="paid" id="paid" class="form-control" style="width:200px;float: right;">
                                    </td>
                                </tr>

                                <tr>

                                    <td align="right" style="padding:5px;">
                                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                                        <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
