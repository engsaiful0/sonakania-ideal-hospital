<script type="text/javascript">
    function price_load(test_id_all) {
        $('#img').show();
        var id_no = test_id_all.split("_");
        var test_id = $('#test_id_' + id_no[2]).val();
        // alert(id_no[2]);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("unit_price_" + id_no[2]).value = xhttp.responseText;
                total_price_cal_unit_price(test_id_all);
                $('#img').hide();
            }
        }
        //  alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestController/price_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("test_id=" + test_id);
    }

    function test_name_load(test_group_id) {
        var ids = test_group_id.split("_");
        var test_group_id_value = $('#test_group_id_' + ids[3]).val();
        var idIndex = ids[3];
        // Check for duplicates
        var isDuplicate = false;

        $('select[name="test_group_id[]"]').each(function() {
            console.log("Comparing: ", $(this).val(), " with ", test_group_id_value);
            if ($(this).val() === test_group_id_value && $(this).attr('id') !== test_group_id) {
                isDuplicate = true;
                console.log("Duplicate found!");
                return false; // Exit loop
            }
        });

        // Handle duplicate case
        if (isDuplicate) {
            console.log("Duplicate detected, resetting the dropdown.");
            $.toast({
                heading: 'Error',
                text: "This group name is already selected. Please choose a different one.",
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 2000,
                icon: 'error'
            });

            // Reset the dropdown
            $('#test_group_id_' + idIndex).val('').trigger('change');
            return; // Stop further execution
        }

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("test_id_" + ids[3]).innerHTML = xhttp.responseText;
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("test_group_id=" + test_group_id_value);
    }
</script>
<script>
    $(document).ready(function() {
        $('#reference_director_id').select2();
        $('#reference_doctor_id').select2();
        $('#reference_employee_id').select2();
        $('#gender').select2();
        $('#reference_media_id').select2();
        $('#years_or_days').select2();
        $('#doctor_id').select2();
        $('#test_id_1').select2();
        $('#datepicker1').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        var id = Number($('#current_id').val());
        for (var i = 1; i <= id; i++) {
            $('#test_group_id_' + i).select2();
            $('#test_id_' + i).select2();
            $('#datepicker' + i).datepicker({
                "changeMonth": true,
                "changeYear": true,
                "dateFormat": "dd-mm-yy",
                "yearRange": '1995:2030'
            });
        }
    });

    function SomeDeleteRowFunction(btndel) {

        if (typeof(btndel) == "object") {
            $(btndel).closest("tr").remove();
        } else {
            return false;
        }
        sub_total();

        discount_cal();
    }

    function sub_total() {
        var id = $('#idControl').val();
        //  alert(id);
        var grand_total = 0;
        for (var i = 1; i <= Number(id); i++) {
            if (!isNaN($('#total_price_' + i).val())) {
                grand_total += Number($('#total_price_' + i).val());
            }
        }
        $('#sub_total').val(Math.ceil(grand_total));
        $('#net_total').val(Math.ceil(grand_total));
        due_cal();
        discount_cal();
    }

    function total_price_cal_unit_price(unit_price) {
        var id_no = unit_price.split("_");
        var quantit = $('#quantity_' + id_no[2]).val();
        var unit_price = $('#unit_price_' + id_no[2]).val();
        $('#total_price_' + id_no[2]).val(Number(quantit) * Number(unit_price));
        var grand_total = 0;
        for (var i = 1; i <= Number(id_no[2]); i++) {
            var quantity = $('#quantity_' + i).val();
            var total_price = Number($('#unit_price_' + i).val()) * Number(quantity);
            if (!isNaN(total_price)) {
                grand_total += Number(total_price);
            }
        }
        $('#sub_total').val(Math.ceil(grand_total));
        $('#net_total').val(Math.ceil(grand_total));
        due_cal();
        discount_cal();
    }

    function total_price_cal(quantity_id) {
        var id_no = quantity_id.split("_");
        var quantit = $('#quantity_' + id_no[1]).val();
        var unit_price = $('#unit_price_' + id_no[1]).val();
        $('#total_price_' + id_no[1]).val(Number(quantit) * Number(unit_price));
        var grand_total = 0;
        for (var i = 1; i <= Number(id_no[1]); i++) {
            var quantity = $('#quantity_' + i).val();
            var total_price = Number($('#unit_price_' + i).val()) * Number(quantity);
            if (!isNaN(total_price)) {
                grand_total += Number(total_price);
            }
        }
        $('#sub_total').val(Math.ceil(grand_total));
        $('#net_total').val(Math.ceil(grand_total));
        due_cal();
        discount_cal();
    }

    function director_discaount_amount_calculate() {
        var director_discount_percentage = $("#director_discount_percentage").val();
        var total = $("#sub_total").val();
        var director_discount = Number(total) * (Number(director_discount_percentage) / 100);
        $("#director_discount").val(director_discount.toFixed(2));
    }

    function director_discount_load() {
        var reference_director_id = $('#reference_director_id').val();
        document.getElementById('director_discount_container').style.display = 'block';
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                $('#director_discount_percentage_show').text(xhttp.responseText);
                $('#director_discount_percentage').val(xhttp.responseText);
                director_discaount_amount_calculate();
                discount_cal();
            }
        }
        xhttp.open("POST", "<?php echo site_url('CommonController/director_discount_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("reference_director_id=" + reference_director_id);
    }

    function discount_cal() {
        //
        var director_discount = $("#director_discount").val();
        var discount = $('#discount').val();
        if (discount.includes("%")) {
            var discount_percentage = discount.split("%");
            var total_discount_value = discount_percentage[0];
            var sub_total = $('#sub_total').val();
            total_discount_amount = sub_total * Number(total_discount_value / 100);
            $('#net_total').val(Math.ceil(Number(sub_total) - Number(total_discount_amount) - Number(director_discount)));
            $('#total_discount').val(Number(total_discount_amount) + Number(director_discount));
        } else {
            var sub_total = $('#sub_total').val();
            console.log('sub_total', sub_total);
            console.log('discount', discount);
            console.log('director_discount', director_discount);
            $('#net_total').val(Number(sub_total) - Number(discount) - Number(director_discount));
            $('#total_discount').val(Number(discount) + Number(director_discount));
        }
        var purr_amount = $('#net_total').val();
        var vat_in_percentage = $('#vat_in_percentage').val();
        var vat = purr_amount * (vat_in_percentage / 100);
        $('#vat').val(vat)
        $('#net_total').val(Number(vat) + Number(purr_amount));
        $('#paid').val(Number(vat) + Number(purr_amount));
        due_cal();

    }

    function due_cal() {
        var net_total = Number($('#net_total').val());
        var paid = Number($('#paid').val());
        if (Number(net_total) < Number(paid)) {
            alert("Paid amount can not be greater than net total");
            $('#paid').val(0);
        } else {
            $('#due').val((Number(net_total) - Number(paid)).toFixed(3));
        }

    }
    document.addEventListener('keydown', function(event) {
        //Ctrl+Shift+Press(+)
        if (event.shiftKey && event.key === '+') {
            event.preventDefault();
            load_product_row();
        }
    });

    function load_product_row() {
        $('#img').show();
        var id = document.getElementById("idControl").value * 1;


        document.getElementById("idControl").value = id + 1;
        id = Number(id) + 1;
        // alert(id);

        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

                var newdiv = document.createElement('tr');
                newdiv.innerHTML = xhttp.responseText;
                document.getElementById('product_table').appendChild(newdiv);
                for (var i = 2; i <= id; i++) {
                    $('#test_id_' + i).select2();
                    $('#test_group_id_' + i).select2();
                    $('#datepicker' + i).datepicker({
                        "changeMonth": true,
                        "changeYear": true,
                        "dateFormat": "dd-mm-yy",
                        "yearRange": '1995:2030'
                    });
                }

                $('#img').hide();
            }
        }

        xhttp.open("POST", "<?php echo site_url('TestController/load_product_row'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("id=" + id);
    }


    $(function() {
        $('[name="time"]').timeselector()
    });
</script>
<script type="text/javascript">
    $(document).keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('#add_more').click();
        }
    });

    function change_calculate() {
        var given = $('#given').val();
        var paid = $('#paid').val();
        $('#change').val(Number(given) - Number(paid));
    }
    $(document).ready(function() {
        $("#patient_unique_id").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('PatientController/patient_unique_id_load'); ?>",
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
                return false;
            }
        });
        // Validate the form
        $("#test_entry_return_form").validate({
            rules: {
                return_reason: "required",
                returnable_amount: "required"
            },
            messages: {
                return_reason: "Please Enter Reason of Return",
                returnable_amount: "Please Enter Returnable Amount",
            }
        });

        // On form submission
        $('#submit_button').click(function(e) {
            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#test_entry_return_form').serialize();

            // ✅ Check if form is valid
            if ($("#test_entry_return_form").valid()) {

                // ✅ Check if at least one total_return has a value > 0
                let hasValidReturn = false;
                $('input[name="total_return[]"]').each(function() {
                    let val = parseFloat($(this).val());
                    if (!isNaN(val) && val > 0) {
                        hasValidReturn = true;
                        return false; // exit loop early
                    }
                });

                if (!hasValidReturn) {
                    $.toast({
                        heading: 'Warning',
                        text: 'At least one return amount must be greater than 0.',
                        showHideTransition: 'fade',
                        position: 'top-right',
                        hideAfter: 3000,
                        icon: 'warning'
                    });
                    return; // prevent form submission
                }

                // ✅ Proceed with submission
                $('#test_entry_return_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('TestController/return_test_entry_save'); ?>",
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
                            $('#test_entry_return_form')[0].reset();
                            $('#test_entry_return_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            setTimeout(function() {
                                window.location.href = "<?php echo base_url('print-test-entry') ?>";
                            }, 1002);
                        } else {
                            alert('Error: ' + response.message);
                            $('#test_entry_return_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#test_entry_return_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });

    });

    function deduction_calculate() {
        var deduction = $('#deduction').val();
        var paid = parseFloat($('#paid').val()) || 0;
        var grand_total = 0;

        if (!deduction) {
            $('#returnable_amount').val(paid);
            return;
        }

        if (deduction.endsWith('%')) {
            var percentValue = parseFloat(deduction.replace('%', ''));

            if (percentValue > 100) {
                alert('Deduction percentage cannot be more than 100%.');
                $('#deduction').val('');
                $('#returnable_amount').val(paid);
                return;
            }

            grand_total = Math.ceil(paid - (paid * (percentValue / 100)));
        } else {
            var numericDeduction = parseFloat(deduction);

            if (numericDeduction > paid) {
                alert('Deduction amount cannot be greater than paid amount.');
                $('#deduction').val('');
                $('#returnable_amount').val(paid);
                return;
            }

            grand_total = Math.ceil(paid - numericDeduction);
        }

        $('#returnable_amount').val(grand_total);
    }
</script>
<script>
    function total_price_cal(changedId) {
        let total = 0;
        let errorShown = false;

        const totalReturnInputs = document.querySelectorAll('input[name="total_return[]"]');

        totalReturnInputs.forEach((input, index) => {
            const returnId = index + 1;
            const returnInput = document.getElementById('total_return_' + returnId);
            const priceInput = document.getElementById('total_price_' + returnId);

            returnInput.classList.remove('is-invalid');

            let returnVal = parseFloat(returnInput.value) || 0;
            let priceVal = parseFloat(priceInput.value) || 0;

            if (returnVal > priceVal) {
                returnInput.classList.add('is-invalid');
                $.toast({
                    heading: 'Error',
                    text: 'Return value cannot exceed the total price.',
                    showHideTransition: 'fade',
                    position: 'top-right',
                    hideAfter: 3000,
                    icon: 'error'
                });
                errorShown = true;
                document.getElementById('total_return_' + returnId).value = 0;
                return; // skip adding to total
            }

            total += returnVal;
        });

        var paid = document.getElementById('paid').value;
        if (paid < total) {
            if (!errorShown) {
                $.toast({
                    heading: 'Error',
                    text: 'Paid amount cannot be less than the total returnable amount.',
                    showHideTransition: 'fade',
                    position: 'top-right',
                    hideAfter: 3000,
                    icon: 'error'
                });
            }
            document.getElementById('total_return_' + changedId).value = 0;
            return;
        }
        // Update the total returnable amount
        document.getElementById('returnable_amount').value = total.toFixed(2);
    }
</script>


<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">Return Test </h3>
        </div>
        <div class="panel-body">
            <?php
            $patient_test_entry = $this->db->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
            ?>
            <form id="test_entry_return_form" class="form-horizontal" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Patient ID</label>
                            <div class="col-sm-8">

                                <input type="hidden" id="ipd_patient_id" name="ipd_patient_id">
                                <input readonly onchange="patient_data_set(this.value)" placeholder="Scan or Enter Patient ID..." type="text" id="patient_unique_id" name="patient_unique_id" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Name *</label>
                            <div class="col-sm-8">
                                <input readonly type="text" required="" class="form-control" id="patient_name" value="<?php echo $patient_test_entry->patient_name ?>" placeholder="Enter Patient Name" name="patient_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="name">Age</label>


                            <div class="col-sm-3">
                                <input type="text" placeholder="Year" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $patient_test_entry->age_year ?>" id="age_year" name="age_year">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Month" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $patient_test_entry->age_month ?>" id="age_month" name="age_month">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" placeholder="Day" oninput="validateIntegerInput(this)" class="form-control" value="<?php echo $patient_test_entry->age_day ?>" id="age_day" name="age_day">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Mobile *</label>
                            <div class="col-sm-8">
                                <input readonly type="text" required="" oninput="validatePhoneNumberInput(this)" class="form-control" value="<?php echo $patient_test_entry->mobile_number ?>" id="mobile_number" name="mobile_number">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Address</label>
                            <div class="col-sm-8">
                                <input readonly type="text" class="form-control" value="<?php echo $patient_test_entry->address ?>" id="address" name="address">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Invoice No</label>
                            <div class="col-sm-8">

                                <input readonly type="text" readonly="" class="form-control" value="<?php echo $patient_test_entry->invoice_no ?>" placeholder="Invoice No" id="invoice_no" name="invoice_no">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Gender *</label>
                            <div class="col-sm-8">
                                <select disabled type="text" required="" class="form-control" id="gender" name="gender">
                                    <option><?php echo $patient_test_entry->gender ?></option>
                                    <option <?php echo $patient_test_entry->gender == 'Male' ? "selected" : "" ?>>Male</option>
                                    <option <?php echo $patient_test_entry->gender == 'Female' ? "selected" : "" ?>>Female</option>
                                    <option <?php echo $patient_test_entry->gender == 'Other' ? "selected" : "" ?>>Other</option>
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Date *</label>
                            <div class="col-sm-8">
                                <input readonly type="text" required="" value="<?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?>" class="form-control" id="datepidatecker" name="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Time</label>
                            <div class="col-sm-8">
                                <input readonly type="text" value="<?php echo $patient_test_entry->time ?>" class="form-control" id="time" name="time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input name="patient_test_entry_id" type="hidden" value="<?php echo $patient_test_entry_id ?>">
                        <table id="product_table" class="table table-bordered table-hover table-striped">
                            <?php
                            $patient_test_entry_details = $this->db
                                ->where('patient_test_entry_id', $patient_test_entry_id)
                                ->get('patient_test_entry_details')
                                ->result();
                            ?>
                            <input type="hidden" id="idControl" value="<?php echo count($patient_test_entry_details) ?>">
                            <input type="hidden" id="current_id" value="<?php echo count($patient_test_entry_details) ?>">
                            <tr id="1">
                                <td>Select Group Name *</td>
                                <td>Select Test Name *</td>
                                <td>Delivery Date *</td>
                                <td>Quantity *</td>
                                <td>Unit Price *</td>
                                <td>Total Price *</td>
                                <td>Return Amount *</td>
                            </tr>
                            <?php
                            $id = 1;
                            foreach ($patient_test_entry_details as $patient_test_entry_details_value) :
                            ?>
                                <input readonly type="text" value="<?php echo $patient_test_entry_details_value->patient_test_entry_details_id; ?>" class="form-control" id="patient_test_entry_details_id_<?php echo $id ?>" name="patient_test_entry_details_id[]">
                                <tr>
                                    <td style="width: 20%;display:none">
                                        <select disabled style="width: 100% " type="text" required="" class="form-control" onchange="test_name_load(this.id)" id="test_group_id_<?php echo $id ?>" name="test_group_id[]">

                                            <?php
                                            $test_group = $this->db->select('*')->get('test_group')->result();
                                            foreach ($test_group as $value) {
                                            ?>
                                                <option <?php echo $patient_test_entry_details_value->test_group_id == $value->test_group_id ? "selected" : "" ?> value="<?php echo $value->test_group_id; ?>"><?php echo $value->test_group_name; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </td>
                                    <td style="width: 20%;">
                                        <select disabled type="text" required="" style="width: 100% " class="form-control" onchange="price_load(this.id)" id="test_id_<?php echo $id ?>" name="test_id[]">



                                            <option value="" disabled="" selected="">Select Test Name</option>
                                            <?php
                                            $test = getAllTestNames();
                                            foreach ($test as $value) {
                                            ?>
                                                <option <?php echo $patient_test_entry_details_value->test_id == $value->test_id ? "selected" : "" ?> value="<?php echo $value->test_id; ?>"><?php echo $value->test_name; ?></option>
                                            <?php
                                            } ?>

                                        </select>
                                    </td>
                                    <td style="width: 10%;">
                                        <input readonly type="text" value="<?php echo date('d-m-Y', strtotime($patient_test_entry_details_value->delivery_date)); ?>" class="form-control" id="datepicker<?php echo $id ?>" name="delivery_date[]">
                                    </td>


                                    <td style="width: 10%;">
                                        <input readonly type="text" onkeydown="validateNumberInput(event)" required="" value="<?php echo $patient_test_entry_details_value->quantity ?>" class="form-control" id="quantity_<?php echo $id ?>" placeholder="Quantity" oninput="total_price_cal(this.id)" name="quantity[]">
                                    </td>
                                    <td style="width: 10%;">
                                        <input readonly type="text" required="" class="form-control" value="<?php echo $patient_test_entry_details_value->unit_price ?>" id="unit_price_<?php echo $id ?>" oninput="total_price_cal_unit_price(this.id)" placeholder="Price" name="unit_price[]">
                                    </td>
                                    <td style="width: 10%;">

                                        <input readonly type="text" onkeydown="validateNumberInput(event)" required="" class="form-control" id="total_price_<?php echo $id ?>" value="<?php echo $patient_test_entry_details_value->total_price ?>" placeholder="Total Price" name="total_price[]">
                                    </td>
                                    <td style="width: 10%;">

                                        <input type="text" onkeydown="validateNumberInput(event)" oninput="total_price_cal(this.id)" class="form-control" id="total_return_<?php echo $id ?>" value="<?php echo $patient_test_entry_details_value->total_return ?>" placeholder="Total Return" name="total_return[]">
                                    </td>
                                    <?php
                                    if ($id == 1) {
                                    ?>
                                        <td title="Ctrl+Shift+Press(+)" style="width: 2%;"><input disabled type="button" onclick="load_product_row()" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click To Add" value="+"></td>
                                    <?php
                                    } else {
                                    ?>
                                        <td style="width: 2%;"><input disabled type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove" value="-"></td>

                                    <?php
                                    }
                                    ?>

                                </tr>
                            <?php
                                $id = $id + 1;
                            endforeach;
                            ?>

                        </table>

                    </div>
                </div>

                <div class="row">

                    <div style="width: 70%;float:left">
                        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                        <fieldset style="background-color:whitesmoke;">
                            <legend>Reference</legend>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Reference Director</label>
                                        <div class="col-sm-8">
                                            <select disabled onchange="director_discount_load()" type="text" class="form-control" id="reference_director_id" name="reference_director_id">
                                                <option selected="" disabled="" value="">Select Reference Director</option>
                                                <?php
                                                $directors = $this->db->select('*')->get('director')->result();
                                                foreach ($directors as $director) {
                                                ?>
                                                    <option <?php echo $patient_test_entry->reference_director_id == $director->director_id ? "selected" : "" ?> value="<?php echo $director->director_id ?>"><?php echo $director->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Reference Doctor</label>
                                        <div class="col-sm-8">
                                            <select disabled type="text" class="form-control" id="reference_doctor_id" name="reference_doctor_id">
                                                <option selected="" value="">Select Reference Doctor</option>
                                                <?php
                                                $doctor = $this->db->select('*')->get('doctor')->result();
                                                foreach ($doctor as $doctor_value) {
                                                ?>
                                                    <option <?php echo $patient_test_entry->reference_doctor_id == $doctor_value->doctor_id ? "selected" : "" ?> value="<?php echo $doctor_value->doctor_id ?>"><?php echo $doctor_value->doctor_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-top: 30px;">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Reference Media</label>
                                        <div class="col-sm-8">
                                            <select disabled type="text" class="form-control" id="reference_media_id" name="reference_media_id">
                                                <option selected="" value="">Select Reference Media</option>
                                                <?php
                                                $reference_media = $this->db->select('*')->get('reference_media')->result();
                                                foreach ($reference_media as $reference_media_value) {
                                                ?>
                                                    <option <?php echo $patient_test_entry->reference_media_id == $reference_media_value->reference_media_id ? "selected" : "" ?> value="<?php echo $reference_media_value->reference_media_id ?>"><?php echo $reference_media_value->reference_media_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-top: 30px;">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Reference Officer</label>
                                        <div class="col-sm-8">
                                            <select disabled style="width:100% ;" type="text" class="form-control" id="reference_employee_id" name="reference_employee_id">
                                                <option selected="" value="">Select Employee</option>
                                                <?php
                                                $employees = $this->db->select('*')->get('employee')->result();
                                                foreach ($employees as $employee) {
                                                ?>
                                                    <option <?php echo $patient_test_entry->reference_employee_id == $employee->employee_id ? "selected" : "" ?> value="<?php echo $employee->employee_id; ?>"><?php echo $employee->employee_name . '-' . $employee->employee_unique_id ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Return</legend>
                            <div class="row" style="clear:left;margin-top:10px;">
                                <div style="display:none" class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="name">Deduction</label>
                                        <div class="col-sm-8">
                                            <input oninput="deduction_calculate(this.value)" type="text" class="form-control" id="deduction" placeholder="Enter Deduction" name="deduction">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Returnable Amount</label>
                                        <div class="col-sm-8">
                                            <input required readonly type="text" class="form-control" placeholder="Returnable Amount" id="returnable_amount" name="returnable_amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    //  date_default_timezone_set('Your/Timezone'); // Set your timezone
                                    $currentDate = date('Y-m-d'); // Get current date
                                    $currentTime = date('H:i'); // Get current time in 24-hour format
                                    ?>

                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Return Date </label>
                                        <div class="col-sm-8">
                                            <input readonly type="text" class="form-control" id="datepicker" name="return_date" value="<?php echo $currentDate; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:30px;">
                                    <?php
                                    //  date_default_timezone_set('Your/Timezone'); // Set your timezone
                                    $currentDate = date('Y-m-d'); // Get current date
                                    $currentTime = date('H:i'); // Get current time in 24-hour format
                                    ?>

                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Return Time </label>

                                        <div class="col-sm-8">
                                            <input readonly type="text" class="form-control" id="return_time" name="return_time" value="<?php date_default_timezone_set('Asia/Dhaka');
                                                                                                                                        echo date("h:i:s A"); ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6" style="margin-top:30px;">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">Reason</label>
                                        <div class="col-sm-8">
                                            <textarea required autofocus placeholder="Enter Reason" type="text" class="form-control" id="return_reason" name="return_reason"><?php echo $patient_test_entry->return_reason ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div style="width: 30%;float:right">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Sub Total *</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="<?php echo $patient_test_entry->sub_total ?>" required="" readonly="" id="sub_total" name="sub_total">
                            </div>
                        </div>
                        <?php
                        $display = 'none';
                        if ($patient_test_entry->director_discount != 0) {
                            $display = 'block';
                        }
                        ?>
                        <div class="form-group" style="display: <?php echo $display ?>;" id="director_discount_container">
                            <label class="control-label col-sm-4" for="pwd">D.Discount(<span id="director_discount_percentage_show"><?php echo $patient_test_entry->director_discount_percentage ?></span>)%</label>
                            <div class="col-sm-8">
                                <input readonly type="text" value="<?php echo $patient_test_entry->director_discount ?>" name="director_discount" id="director_discount" class="form-control">
                                <input type="hidden" value="<?php echo $patient_test_entry->director_discount_percentage ?>" name="director_discount_percentage" id="director_discount_percentage">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Discount</label>
                            <div class="col-sm-8">
                                <input readonly type="text" value="<?php echo $patient_test_entry->discount ?>" class="form-control" oninput="discount_cal(),due_cal()" id="discount" placeholder="Enter Discount" name="discount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Total Discount</label>
                            <div class="col-sm-8">
                                <input readonly type="text" readonly class="form-control" value="<?php echo $patient_test_entry->total_discount ?>" id="total_discount" placeholder="" name="total_discount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Discount Reference</label>
                            <div class="col-sm-8">
                                <input readonly type="text" class="form-control" value="<?php echo $patient_test_entry->discount_reference ?>" id="discount_reference" placeholder="Enter Discount Ref.." name="discount_reference">
                            </div>
                        </div>
                        <div class="form-group" style="display:none">
                            <?php
                            $compnay = $this->db->where('company_id', '1')->get('company')->row();
                            ?>
                            <input type="hidden" value="<?php echo $compnay->vat ?>" class="form-control" id="vat_in_percentage" name="vat_in_percentage">
                            <label class="control-label col-sm-4" for="pwd">Vat(<?php echo $compnay->vat ?>)%</label>
                            <div class="col-sm-8">

                                <input readonly type="text" readonly="" value="<?php echo $patient_test_entry->vat ?>" class="form-control" id="vat" name="vat">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Net Total *</label>
                            <div class="col-sm-8">
                                <input type="text" required="" readonly="" value="<?php echo $patient_test_entry->net_total ?>" class="form-control" id="net_total" name="net_total">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Paid *</label>
                            <div class="col-sm-8">
                                <input type="hidden" value="0" id="complete_confirm">
                                <input readonly type="text" required="" class="form-control" value="<?php echo $patient_test_entry->paid ?>" oninput="due_cal()" id="paid" name="paid">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Due *</label>
                            <div class="col-sm-8">
                                <input type="text" required="" readonly="" value="<?php echo $patient_test_entry->due ?>" class="form-control" id="due" name="due">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Given</label>
                            <div class="col-sm-8">
                                <input readonly type="text" class="form-control" value="<?php echo $patient_test_entry->given ?>" oninput="change_calculate()" id="given" name="given">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Change</label>
                            <div class="col-sm-8">
                                <input type="text" readonly="" class="form-control" value="<?php echo $patient_test_entry->change ?>" id="change" name="change">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                            </div>

                        </div>

                    </div>


                </div>
            </form>

        </div>

    </div>

</div><?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
