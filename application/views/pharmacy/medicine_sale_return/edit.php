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
    function drug_name_load(type_name) {
        var xhttp = new XMLHttpRequest();
        $('#img').show();
        var data = type_name.split("_");
        var type_name_val = document.getElementById(type_name).value;
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                document.getElementById("drug_id" + data[3]).innerHTML = xhttp.responseText;
                //alert(xhttp.responseText);

                $("#drug_type_id_" + data[3]).select2();
                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('ProductController/add_drug_name'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("type_name_val=" + type_name_val);
    }

    function addMoreSale() {
        var next_id = parseInt($('#id_control').val()) + 1;
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

        xhttp.open("POST", "<?php echo site_url('MedicineSaleReturnWithoutInvoiceController/addMoreSale'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('next_id=' + next_id);
    }

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
        // Validate the form
        $("#medicine_sale_form").validate({
            rules: {
                date: "required",
                type_name_0: "required",
                return_amount: "required",
            },
            messages: {
                date: "Enter date",
                type_name_0: "Please select a type name",
                return_amount: "Please enter return amount",
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
                    url: "<?php echo base_url('MedicineSaleReturnWithoutInvoiceController/update/' . $medicine_sale_return_id_without_invoice); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been updated successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                            $('#medicine_sale_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Update');

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-medicine-sale-return-without-invoice') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#medicine_sale_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Update');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#medicine_sale_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Update');
                    }
                });
            }
        });
    });
</script>
<script type="text/javascript">
    var sequence = 0;

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
            var sales_rate = $("#sales_rate" + seq).val();
            var total = quantity * sales_rate;
            var discountPercent = discount.substring(0, len - 1);
            discountAmount = (total * discountPercent) / 100;
        } else {
            discountAmount = discount;
        }
        var quantity = $("#quantity" + seq).val();
        var sales_rate = $("#sales_rate" + seq).val();
        var total = quantity * sales_rate;
        var amount = total - discountAmount;
        $("#amount" + seq).val(amount.toFixed(2));
        totalamount();
    }

    function totalamount() {
        var total = 0;
        $("input[name='amount[]']").each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $("#total").val(total.toFixed(2));
        var discount = $("#discount").val();
        var len = discount.length;
        var discountAmount = 0;
        if (discount[len - 1] == '%') {
            var discountPercent = discount.substring(0, len - 1);
            discountAmount = (total * discountPercent) / 100;
        } else {
            discountAmount = discount;
        }
        var nettotal = total - discountAmount;
        $("#nettotal").val(nettotal.toFixed(2));
        $("#return_amount").val(nettotal.toFixed(2));
    }

    function patient_data_set(patient_unique_id) {
        $.ajax({
            type: "post",
            url: "<?php echo site_url('MedicineSaleReturnWithoutInvoiceController/patient_data_load_by_name_unique_id') ?>",
            data: {
                search_parameter: patient_unique_id
            },
            dataType: "text",
            success: function(response) {
                var data = response.split('*');
                if (data[0] == 'ipd_patient') {
                    $("#ipd_patient_id").val(data[1]);
                    $("#opd_patient_id").val('');
                    $("#name").val(data[2]);
                    $("#mobile_number").val(data[3]);
                    $("#age_year").val(data[4]);
                    $("#age_month").val(data[5]);
                    $("#age_day").val(data[6]);
                    $("#address").val(data[7]);
                } else if (data[0] == 'opd_patient') {
                    $("#opd_patient_id").val(data[1]);
                    $("#ipd_patient_id").val('');
                    $("#name").val(data[2]);
                    $("#mobile_number").val(data[3]);
                    $("#age_year").val(data[4]);
                    $("#age_month").val(data[5]);
                    $("#age_day").val(data[6]);
                    $("#address").val(data[7]);
                } else {
                    alert('No patient found');
                }
            }
        });
    }

    function validateIntegerInput(input) {
        // Remove any non-digit characters
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validatePhoneNumberInput(input) {
        // Remove any non-digit characters except + at the beginning
        input.value = input.value.replace(/[^0-9+]/g, '');
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
            <h3 style="text-align: center">Edit Medicine Sale Return Without Invoice</h3>
        </div>
        <div class="panel-body">

            <form id="medicine_sale_form" method="POST" class="form">
                <div class="container-fluid">
                    <div class="col-sm-12 col-md-12 main">
                        <input type="hidden" readonly="" class="form-control"
                            value="<?php echo $medicine_sale_return->id_serial ?? 1; ?>"
                            id="id_serial" name="id_serial">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Patient ID </label>
                                    <div class="col-sm-8">
                                    <input type="hidden" id="ipd_patient_id" name="ipd_patient_id" value="<?php echo $medicine_sale_return->ipd_patient_id; ?>">
                                    <input type="hidden" id="opd_patient_id" name="opd_patient_id" value="<?php echo $medicine_sale_return->opd_patient_id; ?>">
                                        <input onchange="patient_data_set(this.value)" placeholder="Scan or Enter Patient ID" type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="pwd">Date *</label>
                                    <div class="col-sm-8">
                                        <input readOnly="" type="text" required="" value="<?php echo date('d-m-Y', strtotime($medicine_sale_return->return_date)); ?>" class="form-control" id="return_date" name="return_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Return Invoice No</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly="" class="form-control" value="<?php echo $medicine_sale_return->return_invoice_no; ?>" id="return_invoice_no" name="return_invoice_no">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Name </label>

                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Name" class="form-control" id="name" name="name" value="<?php echo $medicine_sale_return->name; ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="name">Age</label>
                                    <div class="col-sm-3">
                                        <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" id="age_year" name="age_year" value="<?php echo $medicine_sale_return->age_year; ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" id="age_month" name="age_month" value="<?php echo $medicine_sale_return->age_month; ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" id="age_day" name="age_day" value="<?php echo $medicine_sale_return->age_day; ?>">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Mobile</label>
                                    <div class="col-sm-8">
                                        <input type="text" oninput="validatePhoneNumberInput(this)" placeholder="Enter Mobile Number" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo $medicine_sale_return->mobile_number; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Address" class="form-control" id="address" name="address" value="<?php echo $medicine_sale_return->address; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Return Reference</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Return Reference" class="form-control" id="return_reference" name="return_reference" value="<?php echo $medicine_sale_return->return_reference; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="name">Remarks</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Enter Remarks" class="form-control" id="remarks" name="remarks" value="<?php echo $medicine_sale_return->remarks; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="cart_tab1">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Medicine Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sequence = 0;
                                            foreach ($medicine_sale_return_details as $detail): 
                                                $drug = $this->db->where('drug_id', $detail->drug_id)->get('drug')->row();
                                            ?>
                                            <tr>
                                                <td class="medicine_serial"><?php echo ++$sequence; ?></td>
                                                <td>
                                                    <input type="text" sequence="<?php echo $sequence; ?>" id="drug_id<?php echo $sequence; ?>" name="drug_id[]" class="form-control" value="<?php echo $drug->drug_name; ?>" placeholder="Enter Medicine Name">
                                                    <input type="hidden" id="drug_id_value<?php echo $sequence; ?>" name="drug_id_value[]" value="<?php echo $detail->drug_id; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" sequence="<?php echo $sequence; ?>" id="quantity<?php echo $sequence; ?>" name="quantity[]" class="form-control" value="<?php echo $detail->quantity; ?>" onkeyup="getamount(this, event)" placeholder="Quantity">
                                                </td>
                                                <td>
                                                    <input type="text" sequence="<?php echo $sequence; ?>" id="sales_rate<?php echo $sequence; ?>" name="sales_rate[]" class="form-control" value="<?php echo $detail->sales_rate; ?>" onkeyup="getamount(this, event)" placeholder="Unit Price">
                                                </td>
                                                <td>
                                                    <input type="text" sequence="<?php echo $sequence; ?>" id="discounteach<?php echo $sequence; ?>" name="discounteach[]" class="form-control" value="<?php echo $detail->discounteach; ?>" onkeyup="getamount(this, event)" placeholder="Discount">
                                                </td>
                                                <input type="hidden" value="<?php echo $detail->pur_rate; ?>" id="pur_rate<?php echo $sequence; ?>" name="pur_rate[]" class="form-control">
                                                <td>
                                                    <input type="text" sequence="<?php echo $sequence; ?>" id="amount<?php echo $sequence; ?>" name="amount[]" class="form-control" value="<?php echo $detail->amount; ?>" readonly placeholder="Amount">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" sequence="<?php echo $sequence; ?>" onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success" onclick="addMoreSale()">Add More Medicine</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px">
                        <table style="margin-top:5px;float:right;width:60%">
                                <tr>
                                <td align="right" valign="middle" style="padding:5px;">
                                        <b>Discount(%):&nbsp;</b>
                                        <input type="text" name="discount" id="discount" value="<?php echo $medicine_sale_return->discount; ?>" class="form-control" style="width:200px;float: right;" value="0" onkeyup="totalamount()">
                                    </td>
                                    <td colspan="" align="right" valign="middle" style="padding:5px;">
                                        <b>Total:&nbsp;</b>
                                        <input type="text" readonly="" name="total" id="total" value="<?php echo $medicine_sale_return->total; ?>" class="form-control" style="width:200px;float: right;" value="0">
                                    </td>
                                </tr>
                                <tr>
                                <td align="right" valign="middle" style="padding:5px;">
                                        <b>Total Discount</b>
                                        <input readonly type="text" name="total_discount" id="total_discount" value="<?php echo $medicine_sale_return->total_discount; ?>" class="form-control" style="width:200px;float: right;">
                                    </td>
                                    <td>
                                         <b>Discount Reference</b>
                                        <input placeholder="Enter Discount Reference" type="text" value="<?php echo $medicine_sale_return->discount_reference; ?>" name="discount_reference" id="discount_reference" class="form-control" style="width:200px;float: right;">
                                    </td>
                                   
                                </tr>
                                <tr>
                                    <td align="right" valign="middle" style="padding:5px;">
                                        <b>Return Amount:&nbsp;</b>
                                        <input readonly type="text" name="return_amount" id="return_amount" value="<?php echo $medicine_sale_return->return_amount; ?>" required="" class="form-control" style="width:200px;float: right;" value="">
                                    </td>
                               
                                    <td align="right" style="padding:5px;">
                                        <b>Return Reference</b>
                                        <input placeholder="Enter Return Reference" type="text" value="<?php echo $medicine_sale_return->return_reference; ?>" name="return_reference" id="return_reference" class="form-control" style="width:200px;float: right;">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
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

<input type="hidden" id="id_control" value="<?php echo $sequence; ?>">