<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 5px;
        vertical-align: middle;
        /* Ensures the button stays centered */
        text-align: center;
        /* Centers horizontally */
        overflow: hidden;
        /* Prevents overflow */
    }

    .btn-sm {
        padding: 2px 5px;
        height: auto;
        line-height: normal;
        margin: 0;
    }

    /* Optional: Add some spacing around the table */
    .table-container {
        margin: 20px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $("#supplier_id").select2();
        $('#payment_method_id').select2();
        $('#mobile_bank_id').select2();
        $('#bank_account_id').select2();
    });

    function drug_name_load(type_name) {
        var xhttp = new XMLHttpRequest();
        $('#img').show();
        var data = type_name.split("_");
        var type_name_val = document.getElementById(type_name).value;
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("drug_id" + data[3]).innerHTML = xhttp.responseText;
                //alert(xhttp.responseText);
                $("#drug_type_id" + data[3]).select2();
                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('ProductController/add_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("type_name_val=" + type_name_val);
    }

    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });


    $(document).ready(function() {
        // Validate the form
        $("#medicine_purchase_form").validate({
            rules: {
                date: "required",
                type_name_0: "required",
                paid: "required",
            },
            messages: {
                date: "Enter date",
                type_name_0: "Please select a type name",
                paid: "Please enter paid amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#medicine_purchase_form').serialize();

            // Check if the form is valid
            if ($("#medicine_purchase_form").valid()) {
                $('#medicine_purchase_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MedicinePurchaseController/medicine_purchase_edit_save'); ?>",
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
                            $('#medicine_purchase_form')[0].reset();
                            $('#medicine_purchase_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-medicine-purchase') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#medicine_purchase_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#medicine_purchase_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>
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
        updateMedicineSerials(); // Update serials after removing
    }


    function getamount(element, e) {
        var seq = $(element).attr('sequence');
        var discount = $("#discounteach" + seq).val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var quantity = $("#quantity" + seq).val();
            var purchase_rate = $("#purchase_rate" + seq).val();
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var amount = $("#amount" + seq).val();
            var discount = (quantity * purchase_rate) * (discountAmount / 100);
            var amount = (quantity * purchase_rate) - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            totalamount();
        } else {
            var quantity = $("#quantity" + seq).val();
            var purchase_rate = $("#purchase_rate" + seq).val();
            var amount = quantity * purchase_rate - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            totalamount();
        }
    }



    function getdrugdetails(element, e) {

        var seq = $(element).attr('sequence');
        var drug = $("#drug_id" + seq).val();
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
                $("#mrp_rate" + seq).val(sales_rate);
                $("#stock" + seq).val(stock);
                $("#purchase_rate" + seq).val(pur_rate);
                $("#self_no" + seq).val(shelf);
                $("#mfg_date" + seq).focus();
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
        $("#nettotal").val(total.toFixed(2));
        calculateNetTotal();
    }

    function calculateNetTotal() {
        var total = parseFloat($("#total").val()) || 0;

        // Handle VAT
        var vatInput = $("#vat").val().trim();
        var vatAmount = 0;
        if (vatInput.endsWith('%')) {
            var vatPercent = parseFloat(vatInput.replace('%', '')) || 0;
            vatAmount = (total * vatPercent) / 100;
        } else {
            vatAmount = parseFloat(vatInput) || 0;
        }

        // Add VAT to total
        var totalWithVat = total + vatAmount;

        // Handle Discount
        var discountInput = $("#discount").val().trim();
        var discountAmount = 0;
        if (discountInput.endsWith('%')) {
            var discountPercent = parseFloat(discountInput.replace('%', '')) || 0;
            discountAmount = (totalWithVat * discountPercent) / 100;
        } else {
            discountAmount = parseFloat(discountInput) || 0;
        }

        // Subtract Discount
        var nettotal = totalWithVat - discountAmount;

        // Set results
        $("#nettotal").val(nettotal.toFixed(2));
        $("#paid").val(nettotal.toFixed(2));
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

    function show_bank(payment_method_id) {
        if (payment_method_id == 3) {
            document.getElementById('bank_container').style.display = "block";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 2) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "block";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 1) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "none";
        } else if (payment_method_id == 4) {
            document.getElementById('bank_container').style.display = "none";
            document.getElementById('mobile_banking_container').style.display = "none";
            document.getElementById('check_details_container').style.display = "block";
        }
    }

    function addMore() {
        var id_control = document.getElementById('id_control').value * 1;
        var next_id = id_control + 1;
        document.getElementById('id_control').value = next_id;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                // $('#cart_tab1').prepend(xhttp.responseText);
                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('cart_tab1').appendChild(newdiv);
                // Reinitialize autocomplete for the new input field
                initializeAutocomplete("#drug_id" + next_id);
                $("#drug_id" + next_id).focus();
                $('#mfg_datepicker' + next_id).datepicker({
                    "changeMonth": true,
                    "changeYear": true,
                    "dateFormat": "dd-mm-yy",
                    "yearRange": '1995:2030'
                });
                $('#exp_date' + next_id).datepicker({
                    "changeMonth": true,
                    "changeYear": true,
                    "dateFormat": "dd-mm-yy",
                    "yearRange": '1995:2030'
                });
                updateMedicineSerials(); // Update serials after removing
            }
        }
        xhttp.open("POST", "<?php echo site_url('MedicinePurchaseController/addMorePurchase'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //                                    xhttp.send("fname=Henry&lname=Ford");
        xhttp.send('next_id=' + next_id);

    }

    function initializeAutocomplete(selector) {
        $(selector).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('DrugController/drug_info_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $(this).val(ui.item.label); // Set the selected value
                let sequence = $(this).attr("sequence"); // Get sequence number
                //$("#sales_rate" + sequence).val(ui.item.sales_rate); // Set sales rate
                drug_info_set(ui.item.value, sequence);
                return false;
            }
        });
    }
    $(document).ready(function() {
        $("input[name='drug_id[]']").each(function() {
            initializeAutocomplete("#" + $(this).attr("id"));
        });
    });

    function drug_info_set(drug_name, sequence) {
        console.log("sequence=" + sequence)
        $('#img').show();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('DrugController/drug_info_by_drug_name') ?>",
            data: { drug_name: drug_name },
            dataType: "json",
            success: function(response) {
                var sales_rate = response.mrp;
                var purchase_rate = response.purchase_rate;
                var drug_id = response.drug_id;
                $("#mrp_rate" + sequence).val(sales_rate);
                $("#purchase_rate" + sequence).val(purchase_rate);
                $("#drug_id_value" + sequence).val(drug_id);
                $("#quantity" + sequence).focus();
                $('#img').hide();
            }
        });
    }

     function updateMedicineSerials() {
        $('#cart_tab1 .medicine_serial').each(function(index) {
            $(this).text(index + 1);
        });
    }


    // Call after page load to set initial serial
    $(document).ready(function() {
        updateMedicineSerials();
    });
</script>
<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Edit Medicine Purchase</h3>
        </div>
        <div class="panel-body">
            <?php
            $medicine_purchase = $this->db->where('medicine_purchase_id', $medicine_purchase_id)->get('medicine_purchase')->row();
            $medicine_purchase_details = $this->db->where('medicine_purchase_id', $medicine_purchase_id)->get('medicine_purchase_details')->result();
            ?>
            <form id="medicine_purchase_form" method="POST" class="form">
                <input type="hidden" readonly="" class="form-control"
                    value="<?php echo $medicine_purchase->medicine_purchase_id  ?>" id="medicine_purchase_id" name="medicine_purchase_id">

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Supplier *</label>
                            <div class="col-sm-8">
                                <select name="supplier_id" id="supplier_id" class="form-control" required="">
                                    <option value="">Supplier</option>
                                    <?php
                                    $sql = $this->db->where('type','medicine_supplier')->order_by('name', 'ASC')->get('supplier')->result();

                                    foreach ($sql as $value) {
                                    ?>
                                        <option <?php echo $medicine_purchase->supplier_id == $value->supplier_id ? "selected" : '' ?> value="<?php echo $value->supplier_id ?>"><?php echo $value->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input readOnly="" type="text" required="" value="<?php echo date('d-m-Y', strtotime($medicine_purchase->date)); ?>" class="form-control" id="" name="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Invoice No</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $medicine_purchase->medicine_purchase_invoice_no ?>" id="medicine_purchase_invoice_no" name="medicine_purchase_invoice_no">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Status</label>
                            <div class="col-sm-8">
                                <select type="text" readonly="" class="form-control" id="status" name="status">
                                    <option <?php echo $medicine_purchase->status == 'Received' ? "selected" : '' ?>>Received</option>
                                    <option <?php echo $medicine_purchase->status == 'Ordered' ? "selected" : '' ?>>Ordered</option>
                                    <option <?php echo $medicine_purchase->status == 'Quotation' ? "selected" : '' ?>>Quotation</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Remarks</label>
                            <div class="col-sm-8">
                                <textarea type="text" placeholder="Enter Remarks" class="form-control" id="remarks" name="remarks">
                                <?php echo $medicine_purchase->remarks ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Sample/Normal</label>
                            <div class="col-sm-8">
                                <select id="purchase_type" name="purchase_type" class="form-control">
                                    <option <?php echo $medicine_purchase->purchase_type == 'normal' ? "selected" : '' ?> value="normal">Normal Purchase</option>
                                    <option <?php echo $medicine_purchase->purchase_type == 'sample' ? "selected" : '' ?> value="sample">Sample (Free Item)</option>
                                </select>
                              
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: left;margin-top:5px" class="table-responsive">
                    <table width="100%" border="1" class="table table-bordered table-striped">
                        <tr>
                            <input id="total_control" value="" type="hidden">
                            <td colspan="4" style="padding:5px;">

                                <table width="100%" class="table-responsive table table-bordered table-striped " id="cart_tab1">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 200px;text-align: center;">
                                            Medicine Name
                                        </th>
                                        <th style="display:none" id="sale_rate_title" style="width: 90px;text-align: center;">
                                            MFG Date
                                        </th>
                                        <th style="display:none" id="sale_rate_title" style="width: 90px;text-align: center;">
                                            Exp Date
                                        </th>
                                        <th>
                                            MRP
                                        </th>
                                        <th>
                                            Purchase Rate
                                        </th>
                                        <th>
                                            Quantity
                                        </th>
                                        <th>
                                            Bonus Quantity
                                        </th>
                                        <th style="display:none">
                                            Discount(%)
                                        </th>

                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                            &nbsp;
                                        </th>
                                    </tr>

                                    <input type="hidden" value="<?php echo count($medicine_purchase_details) ?>" name="id_control" id="id_control" class="form-control">
                                    <?php
                                    error_reporting(0);
                                    $seq = 0;
                                    foreach ($medicine_purchase_details as $value_de) {

                                        $drug_name = $this->db
                                            ->where('drug_id', $value_de->drug_id)
                                            ->get('drug')->row();
                                    ?>
                                        <tr>
                                            <td class="medicine_serial"></td>
                                            <input type="hidden" value="0" name="id_control" id="id_control" class="form-control">


                                            <td style="padding:5px;">
                                                <input type="hidden" id="drug_id_value<?php echo $seq ?>" value="<?php echo $drug_name->drug_id ?>" readonly name="drug_id_value[]" class="form-control" ">
                                                    <input type=" text" placeholder="Enter Medicine Name" value="<?php echo $drug_name->drug_name ?>" id="drug_id<?php echo $seq ?>" sequence=0 name="drug_id[]" class="form-control" ">
                                                </td>
                                                <td style=" display:none">
                                                <input type=" text" id="mfg_datepicker<?php echo $seq ?>"
                                                    name="mfg_date[]"
                                                    value="<?php echo !empty($value_de->mfg_date) ? date('d-m-Y', strtotime($value_de->mfg_date)) : ''; ?>"
                                                    class="form-control"
                                                    sequence=0

                                                    style="width:90px;">
                                            </td>
                                            <td style="display:none">
                                                <input type="text" id="exp_date<?php echo $seq ?>"
                                                    name="exp_date[]"
                                                    value="<?php echo !empty($value_de->exp_date) ? date('d-m-Y', strtotime($value_de->exp_date)) : ''; ?>"
                                                    class="form-control"
                                                    sequence=0

                                                    style="width:90px;">
                                            </td>

                                            <td>
                                                <input type="text" id="mrp_rate<?php echo $seq ?>" placeholder="Enter MRP" name="mrp_rate[]" value="<?php echo $value_de->mrp_rate ?>" class="form-control" sequence=0 required="">
                                            </td>
                                            <td>
                                                <input type="text" id="purchase_rate<?php echo $seq ?>" name="purchase_rate[]" value="<?php echo $value_de->purchase_rate ?>" class="form-control" sequence=0 required="" onkeyup="getamount(this, event)">
                                            </td>

                                            <td>
                                                <input type="number" id="quantity<?php echo $seq ?>" placeholder="Enter Quantity" name="quantity[]" value="<?php echo $value_de->quantity ?>" class="form-control" sequence=0 onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)">
                                            </td>
                                            <td>
                                                <input type="number" id="bonus_quantity<?php echo $seq ?>" placeholder="Enter Bonus Quantity" name="bonus_quantity[]" value="<?php echo $value_de->bonus_quantity ?>" class="form-control" sequence=0>
                                            </td>
                                            <td style="display:none">
                                                <input type="text" id="discounteach<?php echo $seq ?>" name="discounteach[]" value="<?php echo $value_de->discounteach ?>" class="form-control" sequence=0 onkeyup="getamount(this, event)">
                                            </td>
                                            <td>
                                                <input type="text" id="amount<?php echo $seq ?>" name="amount[]" value="<?php echo $value_de->amount ?>" class="form-control amount" readonly="" sequence=0>
                                            </td>
                                            <?php
                                            if ($seq == 0) {
                                            ?>
                                                <td style="padding:5px;">
                                                    <button onclick="addMore()" type="button" id="add_more" class="btn btn-sm btn-default">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                    </button>
                                                </td>
                                            <?php
                                            } else {
                                            ?>
                                                <td style="padding:5px;">
                                                    <button title="Use Shift + Shortcut Key" onclick="removetr(this, event)" sequence="<?php echo $seq ?>" type="button" class="btn btn-sm btn-default">
                                                        <i class="glyphicon glyphicon-remove"></i>
                                                    </button>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    <?php
                                        $seq++;
                                    }
                                    ?>
                                </table>

                            </td>
                        </tr>
                    </table>
                    <table style="margin-top:5px;float:right;width:60%">
                        <tr>
                            <td>Total</td>
                            <td>
                                <input type="text" readonly="" name="total" id="total" value="<?php echo $medicine_purchase->total ?>" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td>Vat(TK) or %</td>
                            <td><input type="text" onkeyup="totalamount()" name="vat" id="vat" class="form-control" value="<?php echo $medicine_purchase->vat ?>"></td>
                        </tr>
                        <tr>

                            <td>Discount(%)</td>
                            <td><input type="text" name="discount" id="discount" class="form-control" value="<?php echo $medicine_purchase->discount ?>" onkeyup="totalamount()"></td>
                        </tr>
                        <tr>
                            <td>Net Total</td>
                            <td><input type="text" readonly="" name="nettotal" id="nettotal" value="<?php echo $medicine_purchase->nettotal ?>" class="form-control" value="0"></td>

                            <td style="display: none;">Paid</td>
                            <td style="display: none;"><input type="text" name="paid" id="paid" value="<?php echo $medicine_purchase->paid ?>" oninput="dueCal()" class="form-control"></td>
                        </tr>
                        <tr style="display: none;">
                            <td>Due</td>
                            <td><input type="text" name="due" value="<?php echo $medicine_purchase->due ?>" id="due_sale" readonly="" class="form-control"></td>

                            <td>Payment Note</td>
                            <td> <textarea type="text" name="payment_note" id="payment_note" class="form-control"><?php echo $medicine_purchase->payment_note ?></textarea></td>
                        </tr>
                        <tr style="display: none;">
                            <td>Payment Method</td>
                            <td><select onchange="show_bank(this.value)" type="text" class="form-control" id="payment_method_id" name="payment_method_id">
                                    <option disabled="" value="">Select Payment Method</option>
                                    <?php
                                    $payment_methods = $this->db->select('*')->get('payment_methods')->result();
                                    foreach ($payment_methods as $payment_method) {
                                    ?>
                                        <option <?php echo $medicine_purchase->payment_method_id == $payment_method->payment_method_id ? "selected" : '' ?> value="<?php echo $payment_method->payment_method_id ?>"><?php echo $payment_method->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>
                                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
                            </td>
                            <td>
                                <button type="submit" name="submit_button" id="submit_button" class="pull-left btn btn-primary">Update</button>

                            </td>
                        </tr>

                    </table>

                </div>



                <div class="row" style="margin-top:20px;">

                    <div class="col-md-3">
                        <?php
                        $bank_container_display = 'none';
                        $mobile_banking_container_display = 'none';
                        $check_details_container_display = 'none';
                        if ($medicine_purchase->payment_method_id == 3) {
                            $bank_container_display = 'block';
                        } else if ($medicine_purchase->payment_method_id == 2) {
                            $mobile_banking_container_display = 'block';
                        } else if ($medicine_purchase->payment_method_id == 1) {
                            $bank_container_display = 'none';
                            $mobile_banking_container_display = 'none';
                            $check_details_container_display = 'none';
                        } else if ($medicine_purchase->payment_method_id == 4) {
                            $check_details_container_display = 'block';
                        }
                        ?>
                        <div class="form-group" id="bank_container" style="display:<?php echo $bank_container_display ?>">
                            <textarea placeholder="Enter Bank Details" type="text" class="form-control" id="bank_details" name="bank_details"><?php echo $medicine_purchase->bank_details ?></textarea>
                        </div>
                        <div class="form-group" id="mobile_banking_container" style="display:<?php echo $mobile_banking_container_display ?>">
                            <textarea placeholder="Enter Mobile Banking Details" type="text" class="form-control" id="mobile_banking_details" name="mobile_banking_details"><?php echo $medicine_purchase->mobile_banking_details ?></textarea>
                        </div>
                        <div class="form-group" id="check_details_container" style="display:<?php echo $check_details_container_display ?>">
                            <textarea placeholder="Enter Check Details" type="text" class="form-control" id="check_details" name="check_details"><?php echo $medicine_purchase->check_details ?></textarea>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>
<style>
    /* Adjust the padding/margin for specific elements */
    .control-label {
        padding-left: 0;
        padding-right: 0;
    }

    .form-control {
        margin-bottom: 0;
    }

    .col-md-3>.form-group>.col-sm-8 {
        padding-left: 0;
        padding-right: 0;
    }
</style>