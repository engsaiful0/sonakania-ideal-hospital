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
        
        updateMedicineSerials(); // Update serials after removing
    }


    function getamount(element, e) {
        var seq = $(element).attr('sequence');
        var discount = $("#discounteach" + seq).val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var quantity = $("#quantity" + seq).val();
            var sales_rate = $("#sales_rate" + seq).val();
            var disArray = discount.split('%');
            discountAmount = disArray[0];
            var amount = $("#amount" + seq).val();
            var discount = (quantity * sales_rate) * (discountAmount / 100);
            var amount = (quantity * sales_rate) - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            
        } else {
            var quantity = $("#quantity" + seq).val();
            var sales_rate = $("#sales_rate" + seq).val();
            var amount = quantity * sales_rate - discount;
            $("#amount" + seq).val(amount.toFixed(2));
            
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
                $("#sales_rate" + seq).val(sales_rate);
                $("#stock" + seq).val(stock);
                $("#pur_rate" + seq).val(pur_rate);
                $("#self_no" + seq).val(shelf);
                $("#group_name" + seq).val(group_name);
                $("#wsr" + seq).val(wsr);
                $("#quantity" + seq).focus();
            }
        });
    }

    
    $(function() {
     
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

    function drug_info_set(drug_name, sequence) {
        console.log("sequence=" + sequence)
        $('#img').show();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('DrugController/drug_info_by_drug_name') ?>",
            data: {
                drug_name: drug_name
            },
            dataType: "json",
            success: function(response) {
                var sales_rate = response.mrp;
                var drug_id = response.drug_id;
                $("#sales_rate" + sequence).val(sales_rate);
                $("#drug_id_value" + sequence).val(drug_id);
                $("#quantity" + sequence).focus();
                $('#img').hide();
            }
        });
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
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var id_control = document.getElementById('id_control').value * 1;
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

                $('#img').hide();
            }
        }
        xhttp.open("POST", "<?php echo site_url('ProductController/add_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("type_name_val=" + type_name_val);
    }

    function patient_data_set(patient_unique_id) {
        $('#img').show();
        //alert(product_category_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var patient = xhttp.responseText;
                var patient_array = patient.split('*');
                //alert(patient_array);
                if (patient_array[0] == 'ipd_patient') {
                    document.getElementById("ipd_patient_id").value = patient_array[1];
                    document.getElementById("name").value = patient_array[2];
                    document.getElementById("mobile_number").value = patient_array[3];
                    document.getElementById("age_year").value = patient_array[4];
                    document.getElementById("age_month").value = patient_array[5];
                    document.getElementById("age_day").value = patient_array[6];
                    document.getElementById("address").value = patient_array[7];
                } else {
                    document.getElementById("opd_patient_id").value = patient_array[1];
                    document.getElementById("name").value = patient_array[2];
                    document.getElementById("mobile_number").value = patient_array[3];
                    document.getElementById("age_year").value = patient_array[4];
                    document.getElementById("age_month").value = patient_array[5];
                    document.getElementById("age_day").value = patient_array[6];
                    document.getElementById("address").value = patient_array[7];

                }

                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('MedicineSaleController/patient_data_load_by_unique_id'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("patient_unique_id=" + patient_unique_id);
    }


    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            addMore();
        }
    });

    function addMore() {
        var id_control = document.getElementById('id_control').value * 1;
        var next_id = id_control + 1;
        document.getElementById('id_control').value = next_id;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('cart_tab1').appendChild(newdiv);
                // Reinitialize autocomplete for the new input field
                initializeAutocomplete("#drug_id" + next_id);
                $("#drug_id" + next_id).focus();
                updateMedicineSerials(); // Update serials after adding
            }
        };
        xhttp.open("POST", "<?php echo site_url('MedicineSaleController/addMoreSale'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('next_id=' + next_id);
    }



    $(document).ready(function() {
        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('MedicineSaleController/patient_unique_id_load'); ?>",
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
                $('#patient_unique_id').val(ui.item.label);
                patient_data_set(ui.item.value);
                return false;
            }
        });
        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('MedicineSaleController/patient_unique_id_load'); ?>",
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
                $('#patient_unique_id').val(ui.item.label);
                patient_data_set(ui.item.value);
                return false;
            }
        });
        // Validate the form
        $("#medicine_sale_form").validate({
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
            var formData = $('#medicine_sale_form').serialize();

            // Check if the form is valid
            if ($("#medicine_sale_form").valid()) {
                $('#medicine_sale_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('MedicineSaleController/save_medicine_sale_due_payment_data'); ?>",
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
                                window.location.href = "<?php echo base_url('print-medicine-sale') ?>";
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
            <h3 style="text-align: center">Medicine Sale Due Payment</h3>
        </div>
        <div class="panel-body">

            <form id="medicine_sale_form" method="POST" class="form">
                <div class="container-fluid">
                    <div class="col-sm-12 col-md-12 main">

                        <?php
                        $medicine_sale = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales')->row();
                        $medicine_sales_details = $this->db->where('medicine_sale_id', $medicine_sale_id)->get('medicine_sales_details')->result();
                        ?>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Patient ID </label>
                                    <div class="col-sm-8">

                                        <input readonly value="<?php echo $medicine_sale->ipd_patient_id ?>" type="hidden" id="ipd_patient_id" name="ipd_patient_id">
                                        <input readonly value="<?php echo $medicine_sale_id ?>" type="hidden" id="medicine_sale_id" name="medicine_sale_id">

                                        <input readonly onchange="patient_data_set(this.value)" placeholder="Scan or Enter Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" readOnly="" required="" value="<?php echo date('d-m-Y', strtotime($medicine_sale->bill_date)); ?>" class="form-control" id="" name="bill_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Invoice No</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" class="form-control" value="<?php echo $medicine_sale->medicine_sale_invoice_no ?>" id="medicine_sale_invoice_no" name="medicine_sale_invoice_no">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Name </label>

                                    <div class="col-sm-8">
                                        <input readonly type="text" placeholder="Enter Name" value="<?php echo $medicine_sale->name ?>" class="form-control" id="name" name="name">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="name">Age</label>
                                    <div class="col-sm-3">
                                        <input readonly type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $medicine_sale->age_year ?>" id="age_year" name="age_year">
                                    </div>
                                    <div class="col-sm-3">
                                        <input readonly type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $medicine_sale->age_month ?>" id="age_month" name="age_month">
                                    </div>
                                    <div class="col-sm-3">
                                        <input readonly type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $medicine_sale->age_day ?>" id="age_day" name="age_day">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" oninput="validatePhoneNumberInput(this)" placeholder="Enter Mobile Number" class="form-control" value="<?php echo $medicine_sale->mobile_number ?>" id="mobile_number" name="mobile_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Address</label>
                                    <div class="col-sm-8">
                                        <input readonly type="text" placeholder="Enter Address" class="form-control" id="address" value="<?php echo $medicine_sale->address ?>" name="address">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Remarks</label>
                                    <div class="col-sm-8">
                                        <textarea readonly type="text" placeholder="Enter Remarks" class="form-control" id="remarks" name="remarks">
                                        <?php echo $medicine_sale->remarks ?>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear: left;" class="table-responsive">
                            <table width="100%" border="1">
                                <tr>
                                    <input id="total_control" value="" type="hidden">
                                    <td colspan="4" style="padding:5px;">
                                        <table width="100%" cellpadding="4" border="0" id="cart_tab1">
                                            <tr>
                                                <th>No</th>
                                                <th style="padding-left:10px;width: 280px;text-align: center;">
                                                    Medicine Name

                                                </th>
                                                <th id="sale_rate_title" style="padding-left:10px;width: 190px;text-align: center;">
                                                    MRP
                                                </th>

                                                <th style="padding:5px;padding:5px;width: 200px;text-align: center;">
                                                    Quantity
                                                </th>
                                                <th style="padding:5px;padding:5px;width: 210px;text-align: center;">
                                                    Discount(%)
                                                </th>
                                                <th style="padding:5px;padding:5px;width: 170px;text-align: left;">
                                                    Amount
                                                </th>
                                                <!-- <th style="padding:5px;">
                                                    &nbsp;
                                                </th> -->
                                            </tr>

                                            <input type="hidden" value="<?php echo $medicine_sale_id ?>" name="medicine_sale_id" class="form-control">
                                            <input type="hidden" value="<?php echo count($medicine_sales_details) ?>" name="id_control" id="id_control" class="form-control">
                                            <?php
                                            error_reporting(0);
                                            $seq = 0;
                                            foreach ($medicine_sales_details as $value_de) {

                                                $drug_name = $this->db
                                                    ->where('drug_id', $value_de->drug_id)
                                                    ->get('drug')->row();
                                            ?>

                                                <tr>
                                                    <td class="medicine_serial"></td>
                                                    <input type="hidden" value="0" name="id_control" id="id_control" class="form-control">

                                                    <td style="padding:5px;">
                                                        <input type="hidden" id="drug_id_value0" value="<?php echo $drug_name->drug_id ?>" readonly name="drug_id_value[]" class="form-control" ">
                                                    <input readonly type=" text" placeholder="Enter Medicine Name" value="<?php echo $drug_name->drug_name ?>" id="drug_id0" sequence=0 name="drug_id[]" class="form-control" ">
                                                </td>

                                                    <td style=" padding:5px;">
                                                        <input readonly type="text" value="<?php echo $value_de->sales_rate ?>" id="sales_rate<?php echo $seq ?>" name="sales_rate[]" class="form-control" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>

                                                    <td style="padding:5px;">
                                                        <input readonly type="number" value="<?php echo $value_de->quantity ?>" id="quantity<?php echo $seq ?>" name="quantity[]" class="form-control" onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <td style="padding:5px;">
                                                        <input readonly type="text" value="<?php echo $value_de->discounteach ?>" id="discounteach<?php echo $seq ?>" name="discounteach[]" class="form-control" onkeyup="getamount(this, event)" sequence=<?php echo $seq ?>>
                                                    </td>

                                                    <input type="hidden" value="<?php echo $value_de->pur_rate ?>" id="pur_rate<?php echo $seq ?>" name="pur_rate[]" class="form-control" readonly="" sequence=<?php echo $seq ?>>



                                                    <td style="padding:5px;">
                                                        <input readonly type="text" id="amount<?php echo $seq ?>" value="<?php echo $value_de->amount ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $seq ?>>
                                                    </td>
                                                    <?php
                                                    if ($seq == 0) {
                                                    ?>
                                                        <!-- <td style="padding:5px;">
                                                            <button readonly onclick="addMore()" type="button" id="add_more" class="btn btn-sm btn-default">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                            </button>
                                                        </td> -->
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <!-- <td style="padding:5px;">
                                                            <button readonly title="Use Shift + Shortcut Key" onclick="removetr(this, event)" sequence="<?php echo $seq ?>" type="button" class="btn btn-sm btn-default">
                                                                <i class="glyphicon glyphicon-remove"></i>
                                                            </button>
                                                        </td> -->
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
                            <?php 
                            // echo '<pre>';
                            // print_r($medicine_sale);
                            ?>
                            <table style="margin-top:5px;float:right;width:60%">
                                <tr>
                                    <td colspan="" align="right" valign="middle" style="padding:5px;">
                                        <b>Total:&nbsp;</b>
                                        <input value="<?php echo $medicine_sale->total ?>" type="text" readonly="" name="total" id="total" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>

                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Discount(%):&nbsp;</b>
                                        <input value="<?php echo $medicine_sale->discount ?>" type="text" name="discount" id="discount" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Discount Reference</b>
                                        <input placeholder="Enter Discount Reference" type="text" name="discount_reference" id="discount_reference" class="form-control" style="width:200px;float: right;" value="<?php echo $medicine_sale->discount_reference ?>">
                                    </td>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Total Discount</b>

                                        <input readonly type="text" name="total_discount" value="<?php echo $medicine_sale->total_discount ?>" id="total_discount" class="form-control" style="width:200px;float: right;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Net Total:&nbsp;</b>
                                        <input value="<?php echo $medicine_sale->nettotal ?>" type="text" readonly="" name="nettotal" id="nettotal" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>

                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Total Paid:&nbsp;</b>
                                        <input readonly value="<?php echo $medicine_sale->paid ?>" type="text" name="paid" id="paid_" oninput="dueCal()" required="" class="form-control" style="width:200px;float: right;" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                          <b>Due Payment Date:&nbsp;</b>
                                        <input value="<?php echo date('Y-m-d') ?>" type="text" name="due_payment_date" id="due_payment_date" readonly="" class="form-control" style="width:200px;float: right;">
                                     </td>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Total Due:&nbsp;</b>
                                        <input value="<?php echo $medicine_sale->due ?>" type="text" name="due" id="due_sale" readonly="" class="form-control" style="width:200px;float: right;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                          <b>Due Payment Time:&nbsp;</b>
                                        <input value="<?php echo date('H:i:s') ?>" type="text" name="due_payment_time" id="due_payment_time" readonly="" class="form-control" style="width:200px;float: right;">
                                     </td>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Due Payment:&nbsp;</b>
                                        <input readonly value="<?php echo $medicine_sale->due ?>" type="text" name="due_payment" id="due_payment" class="form-control" style="width:200px;float: right;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Change:&nbsp;</b>
                                        <input type="text" name="change_amount" id="change_amount" class="form-control" style="width:200px;float: right;" value="">
                                    </td>
                                    <td align="right" style="padding:5px;">
                                        <b>Due Reference</b>
                                        <input placeholder="Enter Due Reference" type="text" value="<?php echo $medicine_sale->due_reference ?>" name="due_reference" id="due_reference" class="form-control" style="width:200px;float: right;">
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